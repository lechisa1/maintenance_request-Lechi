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
use App\Models\Attachment;
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
        // if (!$categories->contains('name', 'Unknown Cause')) {
        //     $categories->push((object) ['id' => 'unknown', 'name' => 'Unknown Cause']);
        // }

        $items = Item::all();
        return view('maintenance_requests.create', compact('categories', 'items'));
    }
    // public function addSupervisorLetter()
    // {
    //     $user = Auth::user();

    //     // Fetch the ID of the "hardware replacement" category
    //     $hardwareCategory = Category::where('name', 'Hardware Replecement')->first();

    //     // Skip if not found
    //     if (!$hardwareCategory) {
    //         return redirect()->back()->with('error', 'Hardware replacement category not found.');
    //     }


    //     $requests = MaintenanceRequest::with('categories')->where('status', 'pending')
    //         ->whereHas('categories', function ($q) use ($hardwareCategory) {
    //             $q->where('category_id', $hardwareCategory->id);
    //         })
    //         ->whereHas('user', function ($q) use ($user) {
    //             $q->where('reports_to', $user->id);
    //         })
    //         ->with('user.department')
    //         ->get();

    //     return view('supervisor_requests', compact('requests'));
    // }
    public function addSupervisorLetter()
    {
        $user = Auth::user();

        $hardwareCategory = Category::where('name', 'Hardware Replecement')->first();
        if (!$hardwareCategory) {
            return redirect()->back()->with('error', 'Hardware replacement category not found.');
        }

        $requestsQuery = MaintenanceRequest::with(['categories', 'user'])
            ->where('status', 'pending')
            ->whereHas('categories', function ($q) use ($hardwareCategory) {
                $q->where('category_id', $hardwareCategory->id);
            });

        // Handle based on role of current viewer
        if ($user->hasRole('department_manager')) {
            $requestsQuery->whereHas('user', function ($q) use ($user) {
                // Only fetch requests of users who are Employees
                $q->whereHas('roles', function ($r) {
                    $r->where('name', 'employee');
                })
                    // Priority: department → division → sector
                    ->where(function ($inner) use ($user) {
                        $inner->where(function ($cond) use ($user) {
                            $cond->whereNotNull('department_id')
                                ->where('department_id', $user->department_id);
                        })
                            ->orWhere(function ($cond) use ($user) {
                                $cond->whereNull('department_id')
                                    ->whereNotNull('division_id')
                                    ->where('division_id', $user->division_id);
                            })
                            ->orWhere(function ($cond) use ($user) {
                                $cond->whereNull('department_id')
                                    ->whereNull('division_id')
                                    ->where('sector_id', $user->sector_id);
                            });
                    });
            });
        } elseif ($user->hasRole('division_manager')) {
            $requestsQuery->whereHas('user', function ($q) use ($user) {
                // Fetch requests from department managers only
                $q->whereHas('roles', function ($r) {
                    $r->where('name', 'department_manager');
                })
                    ->where(function ($inner) use ($user) {
                        $inner->whereNotNull('division_id')
                            ->where('division_id', $user->division_id)
                            ->orWhere(function ($cond) use ($user) {
                                $cond->whereNull('division_id')
                                    ->where('sector_id', $user->sector_id);
                            });
                    });
            });
        } elseif ($user->hasRole('general_director')) {
            $requestsQuery->whereHas('user', function ($q) use ($user) {
                // Fetch requests from division managers only
                $q->whereHas('roles', function ($r) {
                    $r->where('name', 'division_manager');
                })
                    ->where('sector_id', $user->sector_id);
            });
        } else {
            return redirect()->back()->with('error', 'You are not authorized to view these requests.');
        }

        $requests = $requestsQuery->get();
        return view('supervisor_requests', compact('requests'));
    }


    // public function requestFromStaff()
    // {
    //     $user = Auth::user();

    //     // Fetch the "Hardware Replacement" category
    //     $hardwareCategory = Category::where('name', 'Hardware Replecement')->first();

    //     $requests = MaintenanceRequest::with('categories')
    //         ->where('status', 'pending')
    //         ->whereDoesntHave('categories', function ($q) use ($hardwareCategory) {
    //             $q->where('category_id', $hardwareCategory->id);
    //         })
    //         ->whereHas('user', function ($q) use ($user) {
    //             $q->where('reports_to', $user->id);
    //         })
    //         ->with('user.department')
    //         ->get();

    //     return view('supervisor_requests', compact('requests'));
    // }
    public function requestFromStaff()
    {
        $user = Auth::user();

        // Get the "Hardware Replecement" category
        $hardwareCategory = Category::where('name', 'Hardware Replecement')->first();

        // Base query for non-hardware replacement pending requests
        $requestsQuery = MaintenanceRequest::with(['categories', 'user.department'])
            ->where('status', 'pending')
            ->whereDoesntHave('categories', function ($q) use ($hardwareCategory) {
                if ($hardwareCategory) {
                    $q->where('category_id', $hardwareCategory->id);
                }
            });

        // Apply role-based access
        if ($user->hasRole('general_director')) {
            $requestsQuery->whereHas('user', function ($q) use ($user) {
                $q->where('sector_id', $user->sector_id)
                    ->whereNull('division_id')
                    ->whereNull('department_id');
            });
        } elseif ($user->hasRole('division_manager')) {
            $requestsQuery->whereHas('user', function ($q) use ($user) {
                $q->where('sector_id', $user->sector_id)
                    ->where('division_id', $user->division_id)
                    ->whereNull('department_id');
            });
        } elseif ($user->hasRole('department_manager')) {
            $requestsQuery->whereHas('user', function ($q) use ($user) {
                $q->where('sector_id', $user->sector_id)
                    ->where('division_id', $user->division_id)
                    ->where('department_id', $user->department_id);
            });
        } else {
            return redirect()->back()->with('error', 'You are not authorized to view these requests.');
        }

        $requests = $requestsQuery->get();

        return view('supervisor_requests', compact('requests'));
    }

    public function approveAndForward(Request $request, $id)
    {
        $request->validate([
            'letter' => 'required|file|mimes:pdf,docx|max:2048',
        ]);

        $req = MaintenanceRequest::findOrFail($id);

        $file = $request->file('letter');
        $path = $file->store('letters', 'public');

        // Save attachment
        Attachment::create([
            'maintenance_request_id' => $req->id,
            'user_id' => auth()->id(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName(),
        ]);

        // Update request status
        $req->supervisor_status = 'approved';
        $req->save();
        $directors = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'Ict_director');
        })->get();

        foreach ($directors as $director) {
            $director->notify(new \App\Notifications\RequestCreatedNotification($req));
        }
        return back()->with('success', 'Letter uploaded and request forwarded to ICT Director.');
    }
    public function supervisorRejectRequest(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $req = MaintenanceRequest::findOrFail($id);
        $req->supervisor_status = 'rejected';
        $req->rejection_reason = $request->reason;
        $req->rejected_by = auth()->user()->id;
        $req->save();
        // Notify the user
        $req->user->notify(new \App\Notifications\MaintenanceRequestRejected($req));

        return back()->with('success', 'Request rejected with reason.');
    }

    //this work fine 
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([

    //         'item_id' => 'required|exists:items,id',
    //         'description' => 'required|string',

    //         'priority' => 'required|in:low,medium,high,emergency',
    //         'categories' => 'nullable|array',
    //         'attachments.*' => 'file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
    //     ]);
    //     $randomId = rand(10000, 99999);

    //     $ticketNumber = 'REQ-' . now()->format('Ymd') . '-' . auth()->id() . '-' . mt_rand(1000, 9999);
    //     $maintenanceRequest = auth()->user()->maintenanceRequests()->create([

    //         'item_id' => $validated['item_id'],
    //         'description' => $validated['description'],

    //         'priority' => $validated['priority'],
    //         'status' => 'pending',
    //         'ticket_number' => $randomId,
    //     ]);

    //     if (isset($validated['categories'])) {

    //         $selectedCategories = array_diff($validated['categories'], ['unknown']);

    //         $maintenanceRequest->categories()->sync($selectedCategories);


    //         if (in_array('unknown', $validated['categories'])) {

    //             $maintenanceRequest->update(['has_unknown_cause' => true]);
    //         }
    //     }


    //     if ($request->hasFile('attachments')) {
    //         foreach ($request->file('attachments') as $file) {
    //             $path = $file->store('attachments', 'public');

    //             $maintenanceRequest->attachments()->create([
    //                 'user_id' => auth()->id(),
    //                 'file_path' => $path,
    //                 'file_type' => $file->getMimeType(),
    //                 'original_name' => $file->getClientOriginalName(),
    //             ]);
    //         }
    //     }
    //     // Log the creation
    //     $maintenanceRequest->updates()->create([
    //         'user_id' => auth()->id(),
    //         'update_text' => 'Request created',
    //         'update_type' => 'creation',
    //         'request_id' => $maintenanceRequest->id,
    //     ]);
    //     // Notify the Director(s)
    //     $directors = \App\Models\User::whereHas('roles', function ($query) {
    //         $query->where('name', 'director');
    //     })->get();

    //     foreach ($directors as $director) {
    //         $director->notify(new \App\Notifications\RequestCreatedNotification($maintenanceRequest));
    //     }

    //     return redirect()->route('requests_indexs', $maintenanceRequest)
    //         ->with('success', 'Maintenance request submitted successfully!');
    // }
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

        // Create request first
        $maintenanceRequest = auth()->user()->maintenanceRequests()->create([
            'item_id' => $validated['item_id'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'ticket_number' => $randomId,
            'supervisor_status' => 'pending',
        ]);
        $isHardwareReplacement = false;

        // Attach categories and check conditions
        if (isset($validated['categories'])) {
            $selectedCategories = array_diff($validated['categories'], ['unknown']);
            $maintenanceRequest->categories()->sync($selectedCategories);

            if (in_array('unknown', $validated['categories'])) {
                $maintenanceRequest->update(['has_unknown_cause' => true]);
            }

            // Check if 'hardware replacement' is one of the selected categories
            $hardwareReplacementId = Category::where('name', 'Hardware Replecement')->value('id');
            if ($hardwareReplacementId && in_array($hardwareReplacementId, $selectedCategories)) {
                $maintenanceRequest->update(['supervisor_status' => 'pending']);
                $isHardwareReplacement = true;
            }
        }

        // Handle attachments
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

        // Log creation
        $maintenanceRequest->updates()->create([
            'user_id' => auth()->id(),
            'update_text' => 'Request created',
            'update_type' => 'creation',
            'request_id' => $maintenanceRequest->id,
        ]);

        // Notify directors
        if ($isHardwareReplacement) {
            $supervisor = auth()->user()->reportsTo; // assumes relationship or reports_to field
            if ($supervisor) {
                $supervisor->notify(new \App\Notifications\RequestCreatedNotification($maintenanceRequest));
            }
        } else {
            $directors = \App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'Ict_director');
            })->get();

            foreach ($directors as $director) {
                $director->notify(new \App\Notifications\RequestCreatedNotification($maintenanceRequest));
            }
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
        // $categories->push((object) ['id' => 'unknown', 'name' => 'Unknown Cause']);
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
            'supervisor_status' => 'pending',
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
            'item',
            'user.jobPosition'
        ])->findOrFail($id);

        return view('maintenance_requests.show', compact('maintenanceRequest'));
    }

    // public function division_director_request_view()
    // {
    //     $supervisorId = auth()->user()->id;

    //     $pendingRequest = MaintenanceRequest::with([
    //         'user',
    //         'categories',
    //         'item',
    //         'item.categories',
    //         'latestAssignment'
    //     ])
    //         ->where('status', 'pending')
    //         ->where('user_feedback', 'pending')
    //         ->whereHas('user', function ($query) use ($supervisorId) {
    //             $query->where('reports_to', $supervisorId);
    //         })
    //         ->latest()
    //         ->paginate(10);

    //     return view('division_director_view', compact('pendingRequest'));
    // }
    public function division_director_request_view()
    {
        $user = auth()->user();

        $query = MaintenanceRequest::with([
            'user',
            'categories',
            'item',
            'item.categories',
            'latestAssignment'
        ])
            ->where('status', 'pending')
            ->where('user_feedback', 'pending');

        if ($user->hasRole('general_director')) {
            // Get all division managers under this sector
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('sector_id', $user->sector_id)
                    ->where(function ($q2) {
                        $q2->whereHas('roles', function ($qr) {
                            $qr->where('name', 'division_manager');
                        })
                            ->orWhereNull('division_id'); // if sector has no division
                    });
            });
        } elseif ($user->hasRole('division_manager')) {
            // Get all department managers under this division
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('division_id', $user->division_id)
                    ->where(function ($q2) {
                        $q2->whereHas('roles', function ($qr) {
                            $qr->where('name', 'department_manager');
                        })
                            ->orWhereNull('department_id'); // if division has no department
                    });
            });
        } elseif ($user->hasRole('department_manager')) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->where('id', '!=', $user->id) // exclude own requests
                    ->whereDoesntHave('roles', function ($qr) {
                        $qr->where('name', 'division_manager');
                    });
            });
        }



        $pendingRequest = $query->latest()->paginate(10);

        return view('division_director_view', compact('pendingRequest'));
    }
}
