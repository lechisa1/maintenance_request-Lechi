<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MaintenanceRequestRejected;
use App\Notifications\TechnicianAssignedNotification;
use App\Traits\MaintenanceRequestSearch;
use Termwind\Components\Raw;

class DirectorController extends Controller
{
    use MaintenanceRequestSearch;
    public function directorDashboard(Request $request)
    {
        // Changed: Remove department filter to get ALL requests
        $departmentRequests = MaintenanceRequest::query();  // Now gets all requests
        if ($request->has('search') && !empty($request->search)) {
            $query = $this->applyMaintenanceRequestSearch($query, $request->search);
        }
        $maintenances = $departmentRequests->latest()->paginate(5);

        // All these counts will now be for ALL requests
        $total = (clone $departmentRequests)->count();
        $completed = (clone $departmentRequests)->where('status', 'completed')->count();
        $pending = (clone $departmentRequests)->where('status', 'pending')->count();
        $inProgress = (clone $departmentRequests)->where('status', 'in_progress')->count();
        $notFixed = (clone $departmentRequests)->where('status', 'not_fixed')->count();
        $rejected = (clone $departmentRequests)->where('status', 'rejected')->count();
        $assigned = (clone $departmentRequests)->where('status', 'assigned')->count();

        $statusCounts = (clone $departmentRequests)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('director.dashboard', compact(
            'maintenances',
            'total',
            'assigned',
            'notFixed',
            'rejected',
            'completed',
            'pending',
            'inProgress',
            'statusCounts'
        ));
    }


    public function maintenenceRequestPending(Request $request)
    {
        $pendingRequest = MaintenanceRequest::with(['user', 'categories', 'item'])->whereIn('status', ['pending', 'not_fixed'])->latest()->paginate(10);
                if ($request->has('search') && !empty($request->search)) {
            $query = $this->applyMaintenanceRequestSearch($query, $request->search);
        }
        return view('maintenance_requests.pending_maintenance', compact('pendingRequest'));
    }

    public function showAssignForm($id)
    {
        $maintenanceRequest = MaintenanceRequest::with(['user'])
            ->findOrFail($id);

        $technicians = User::whereHas('roles', function ($q) use ($maintenanceRequest) {
            $q->where('name', 'technician');
        })
            ->withCount(['assignedRequests' => function ($query) {
                $query->whereHas('maintenanceRequest', function ($q) {
                    $q->whereIn('status', ['assigned', 'in_progress']);
                });
            }])
            ->get()
            ->sortBy('assigned_requests_count') // 🔍 Sort here
            ->values();

        $noTechniciansMessage = $technicians->isEmpty() ? 'No technicians available for this department.' : null;

        return view('maintenance_requests.assign', [
            'maintenanceRequest' => $maintenanceRequest,
            'technicians' => $technicians,
            'noTechniciansMessage' => $noTechniciansMessage
        ]);
    }

    public function assign(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'technician_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'technician_ids.*' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($maintenanceRequest) {
                    // Check if user is a technician
                    $isTechnician = User::where('id', $value)
                        ->whereHas('roles', function ($q) {
                            $q->where('name', 'technician');
                        })
                        ->exists();
                }
            ],
            'director_notes' => 'nullable|string|max:500',
            'expected_completion_date' => 'nullable|date'
        ]);

        // Check if any technician is overloaded
        foreach ($validated['technician_ids'] as $techId) {
            $technician = User::withCount(['assignedRequests' => function ($query) {
                $query->whereHas('maintenanceRequest', function ($q) {
                    $q->whereIn('status', ['assigned', 'in_progress']);
                });
            }])->find($techId);

            if ($technician->assigned_requests_count >= 5 && !$request->has('confirmed')) {
                return view('maintenance_requests.confirm_assign', [
                    'maintenanceRequest' => $maintenanceRequest,
                    'technician' => $technician,
                    'formData' => $validated
                ]);
            }
        }

        return $this->processAssignment($maintenanceRequest, $validated);
    }



    protected function processAssignment($maintenanceRequest, $validated)
    {
        foreach ($validated['technician_ids'] as $techId) {
            // Create assignment
            $maintenanceRequest->assignments()->create([
                'director_id' => auth()->id(),
                'technician_id' => $techId,
                'director_notes' => $validated['director_notes'],
                'expected_completion_date' => now(),
                'assigned_at' => now(),
            ]);

            // Log the assignment
            $maintenanceRequest->updates()->create([
                'user_id' => auth()->id(),
                'update_text' => "Assigned to technician #{$techId}",
                'update_type' => 'status_change'
            ]);

            // Notify technician
            $technician = User::find($techId);
            $technician->notify(new TechnicianAssignedNotification($maintenanceRequest));
        }

        // Update request status once
        $maintenanceRequest->update(['status' => 'assigned']);

        return redirect()->route('requests.show', $maintenanceRequest)
            ->with('success', 'Request assigned to technicians successfully!');
    }

    public function notifyTechnician(User $technician, MaintenanceRequest $maintenanceRequest)
    {

        $technician->notify(new TechnicianAssignedNotification($maintenanceRequest));

        return true; // Return success if needed
    }
    public function show($id)
    {
        $maintenanceRequest = MaintenanceRequest::with([
            'user',
            'assignments.technician',
            'assignments.director',
            'categories',
            'rejectedBy',
            'attachments',
            'updates',
            'latestAssignment.director',
            'latestAssignment.technician',
            'item',
            'workLogs.technician',
            'updates.user'
        ])->findOrFail($id);


        return view('maintenance_requests.show', compact('maintenanceRequest'));
    }

    public function rejectRequest(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $request->validate([
            "rejection_reason" => "required|string",
        ]);

        $maintenanceRequest->status = 'rejected';
        $maintenanceRequest->rejection_reason = $request->rejection_reason;
        $maintenanceRequest->rejected_by = auth()->id();
        $maintenanceRequest->save();
        // 🔔 Notify the request owner
        $maintenanceRequest->user->notify(new MaintenanceRequestRejected($maintenanceRequest));
        return redirect()->route('rejected_maintenance')->with('success', 'Request rejected successfully.');
    }


    public function index()
    {
        $director = auth()->user();
        $departmentId = $director->department_id;


        $statusCounts = MaintenanceRequest::all()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();


        $statuses = ['completed', 'pending', 'in_progress'];
        foreach ($statuses as $status) {
            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = 0;
            }
        }

        // Prepare data for the chart
        $chartData = [
            'labels' => $statuses,
            'datasets' => [
                [
                    'label' => 'Requests by Status',
                    'data' => array_values($statusCounts),
                    'backgroundColor' => [
                        'rgba(40, 167, 69, 0.5)',
                        'rgba(255, 193, 7, 0.5)',
                        'rgba(23, 162, 184, 0.5)',
                    ],
                    'borderColor' => [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(23, 162, 184, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];

        return view('requests.index', compact('chartData'));
    }
    public function getCompletedRequests()
    {

        $director = auth()->user();
        $check = auth()->user()->roles->first()->name;
        if (!$check) {
            abort(403, 'Unauthorized access');
        }
        $directorDepartmentId = $director->department->id;

        // auth()->user()->department->name;
        $completedRequests = MaintenanceRequest::with('item', 'user', 'latestAssignment', 'categories')->where('status', 'completed')
            ->where('user_feedback', 'accepted')

            ->latest()->paginate(10);


        return view('director.status.completed', compact('completedRequests'));
    }
public function getPendingRequests(Request $request)
{
    $director = auth()->user();
    $check = auth()->user()->roles->first()->name;
    
    if (!$check) {
        abort(403, 'Unauthorized access');
    }

    $directorDepartmentId = $director->department->id;
    
    // Initialize the query
    $query = MaintenanceRequest::with(['user', 'categories', 'item', 'item.categories', 'latestAssignment'])
                ->whereIn('status', ['pending', 'not_fixed'])
                ->where('user_feedback', 'pending');

    // Apply search if search term exists
    if ($request->has('search') && !empty($request->search)) {
        $query = $this->applyMaintenanceRequestSearch($query, $request->search);
    }

    // Get paginated results
    $pendingRequest = $query->latest()->paginate(10);

    return view('director.status.pending', compact('pendingRequest'));
}
    public function getRejectedRequests(Request $request)
    {

        $director = auth()->user();
        $check = auth()->user()->roles->first()->name;
        if (!$check) {
            abort(403, 'Unauthorized access');
        }
        $directorDepartmentId = $director->department->id;


        $query = MaintenanceRequest::with(['user', 'item', 'latestAssignment', 'categories', 'item.categories', 'rejectedBy'])->where('status', 'rejected')
            ->where('user_feedback', 'pending')
            ->orWhere('user_feedback', 'rejected');
                if ($request->has('search') && !empty($request->search)) {
        $query = $this->applyMaintenanceRequestSearch($query, $request->search);
    }

             $pendingRequest=$query->latest()->paginate(10);


        return view('director.status.rejected', compact('pendingRequest'));
    }
    public function getInProgressRequests(Request $request)
    {

        $director = auth()->user();
        $check = auth()->user()->roles->first()->name;
        if (!$check) {
            abort(403, 'Unauthorized access');
        }
        $directorDepartmentId = $director->department->id;


        $query = MaintenanceRequest::with('user', 'item', 'latestAssignment', 'categories', 'item.categories')->where('status', 'in_progress');

    if ($request->has('search') && !empty($request->search)) {
        $query = $this->applyMaintenanceRequestSearch($query, $request->search);
    }
           $InProgressRequests= $query ->latest()->paginate(10);


        return view('director.status.in_progress', compact('InProgressRequests'));
    }
    public function getAssignedRequests(Request $request)
    {

        $director = auth()->user();
        $check = auth()->user()->roles->first()->name;
        if (!$check) {
            abort(403, 'Unauthorized access');
        }
        $directorDepartmentId = $director->department->id;


        $query = MaintenanceRequest::with('user', 'item', 'latestAssignment', 'categories', 'item.categories')->where('status', 'assigned');

    if ($request->has('search') && !empty($request->search)) {
        $query = $this->applyMaintenanceRequestSearch($query, $request->search);
    }
    $AssignedRequests= $query->latest()->paginate(10);


        return view('director.status.assigned', compact('AssignedRequests'));
    }
}
