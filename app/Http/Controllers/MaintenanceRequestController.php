<?php

namespace App\Http\Controllers;

use id;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CompletionRejectedNotification;
use App\Notifications\RequestAcceptedByUserNotification;

class MaintenanceRequestController extends Controller
{
    //
    // In the controller
    public function index()
    {
        $maintenances = MaintenanceRequest::with(['user', 'categories', 'item'])->where('user_id', auth()->id())->latest()->paginate(10);
        return view('maintenance_requests.index', compact('maintenances'));
    }
    public function create()
    {

        $categories = Category::all();

        // Add "Unknown Cause" as a custom option
        // Check if "Unknown Cause" already exists before adding it
        if (!$categories->contains('name', 'Unknown Cause')) {
            $categories->push((object) ['id' => 'unknown', 'name' => 'Unknown Cause']);
        }

        $items = Item::all();
        return view('maintenance_requests.create', compact('categories', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'item_id' => 'required|exists:items,id',
            'description' => 'required|string',

            'priority' => 'required|in:low,medium,high,emergency',
            'categories' => 'nullable|array',
            'attachments.*' => 'file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);
        $randomId = rand(10000, 99999);

        $ticketNumber = 'REQ-' . now()->format('Ymd') . '-' . auth()->id() . '-' . mt_rand(1000, 9999);
        $maintenanceRequest = auth()->user()->maintenanceRequests()->create([

            'item_id' => $validated['item_id'],
            'description' => $validated['description'],

            'priority' => $validated['priority'],
            'status' => 'pending',
            'ticket_number' => $randomId,
        ]);

        if (isset($validated['categories'])) {

            $selectedCategories = array_diff($validated['categories'], ['unknown']);

            $maintenanceRequest->categories()->sync($selectedCategories);


            if (in_array('unknown', $validated['categories'])) {

                $maintenanceRequest->update(['has_unknown_cause' => true]);
            }
        }


        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $maintenanceRequest->attachments()->create([
                    'user_id' => auth()->id(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }
        // Log the creation
        $maintenanceRequest->updates()->create([
            'user_id' => auth()->id(),
            'update_text' => 'Request created',
            'update_type' => 'creation',
            'request_id' => $maintenanceRequest->id,
        ]);
        // Notify the Director(s)
        $directors = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->get();

        foreach ($directors as $director) {
            $director->notify(new \App\Notifications\RequestCreatedNotification($maintenanceRequest));
        }

        return redirect()->route('requests_indexs', $maintenanceRequest)
            ->with('success', 'Maintenance request submitted successfully!');
    }
    public function edit($id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        $categories = Category::all();
        $items = Item::all();
        // Add "Unknown Cause" manually
        $categories->push((object) ['id' => 'unknown', 'name' => 'Unknown Cause']);
        return view('maintenance_requests.edit', compact('maintenanceRequest', 'categories', 'items'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'description' => 'required|string',

            'priority' => 'required|in:low,medium,high,emergency',
            'categories' => 'nullable|array',
            'attachments.*' => 'file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        $maintenanceRequest->update([
            'item_id' => $validated['item_id'],
            'description' => $validated['description'],
            // 'location' => $validated['location'],
            'priority' => $validated['priority'],
            'status' => "pending",
        ]);

        if (isset($validated['categories'])) {
            // Remove 'unknown' before syncing categories if needed
            $selectedCategories = array_diff($validated['categories'], ['unknown']);

            $maintenanceRequest->categories()->sync($selectedCategories);


            if (in_array('unknown', $validated['categories'])) {
                $maintenanceRequest->update(['has_unknown_cause' => true]);
            }
        }

        if ($request->hasFile('attachments')) {
            foreach ($maintenanceRequest->attachments as $oldFile) {
                Storage::delete($oldFile->file_path);
                $oldFile->delete();
            }

            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $maintenanceRequest->attachments()->create([
                    'user_id' => auth()->id(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Log the update
        $maintenanceRequest->updates()->create([
            'user_id' => auth()->id(),
            'update_text' => 'Request updated',
            'update_type' => 'update',
            'request_id' => $maintenanceRequest->id,
        ]);

        return redirect()->route('requests_indexs', $maintenanceRequest)
            ->with('success', 'Maintenance request updated successfully!');
    }
    public function destroy(MaintenanceRequest $maintenanceRequest)
    {

        if (Auth::id() !== $maintenanceRequest->user_id) {
            return back()->withErrors(['message' => 'You are not authorized to delete this request.']);
        }
        $maintenanceRequest->delete();
        return redirect()->route('requests_indexs', $maintenanceRequest)
            ->with('success', 'Maintenance request deleted successfully!');
    }
    public function respondToCompletion(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $request->validate([
            'action' => ['required', Rule::in(['accept', 'reject'])],
            'rejection_reason' => 'nullable|required_if:action,reject|string|min:10'
        ]);

        // Prevent duplicate acceptance
        if ($maintenanceRequest->user_feedback !== 'pending') {
            return back()->with('error', 'You have already responded to this request.');
        }

        if ($request->action === 'accept') {
            $maintenanceRequest->update(['user_feedback' => 'accepted']);

            // Notify Director
            $director = $maintenanceRequest->assignments()->latest()->first()?->director;
            if ($director) {
                $director->notify(new RequestAcceptedByUserNotification($maintenanceRequest));
            }

            return back()->with('success', 'You have accepted the maintenance completion. The director has been notified.');
        }

        if ($request->action === 'reject') {
            $latestAssignment = $maintenanceRequest->assignments()->latest()->first();
            $technician = $latestAssignment?->technician;

            // Update status and feedback
            $maintenanceRequest->update([
                'user_feedback' => 'rejected',
                'status' => 'in_progress',
                'rejection_reason' => $request->rejection_reason,
                'technician_id' => $technician?->id, // optional: re-link technician_id directly if present
            ]);

            // Notify Technician
            if ($technician) {
                $technician->notify(new CompletionRejectedNotification($maintenanceRequest));
            }

            return back()->with('error', 'You have rejected the work. The technician has been notified.');
        }
    }
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);
        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }
    public function show($id)
    {
        $maintenanceRequest = MaintenanceRequest::with([
            'user',
            'assignedTechnicians',
            'workLogs.technician',
            'user.department',
            'attachments',
            'assignments',
            'latestAssignment',
            'rejectedBy',
            'categories',
            'item'
        ])->findOrFail($id);

        return view('maintenance_requests.show', compact('maintenanceRequest'));
    }
}