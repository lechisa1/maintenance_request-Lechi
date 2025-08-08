<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;

class EmployeeController extends Controller
{
    //
// public function employeeDashboard()
// {
//     $loggedInUserId = auth()->id();

//     // Get users who report to this user
//     $reportingUserIds = User::where('reports_to', $loggedInUserId)->pluck('id')->toArray();

//     // Include the supervisor's own ID
//     $userIds = array_merge([$loggedInUserId], $reportingUserIds);

//     // Queries using whereIn to include both self and subordinates
//     $totalReq = MaintenanceRequest::with(['user', 'categories', 'assignments'])
//         ->whereIn('user_id', $userIds)
//         ->count();

//     $pending = MaintenanceRequest::with(['user', 'categories', 'assignments'])
//         ->where('status', 'pending')
//         ->whereIn('user_id', $userIds)
//         ->count();

//     $assigned = MaintenanceRequest::with(['user', 'categories', 'assignments'])
//         ->where('status', 'assigned')
//         ->where('user_feedback', 'pending')
//         ->whereIn('user_id', $userIds)
//         ->count();

//     $completed = MaintenanceRequest::with(['user', 'categories', 'assignments'])
//         ->where('status', 'completed')
//         ->where('user_feedback', 'accepted')
//         ->whereIn('user_id', $userIds)
//         ->count();

//     $in_progress = MaintenanceRequest::with(['user', 'categories', 'assignments'])
//         ->where('status', 'in_progress')
//         ->whereIn('user_id', $userIds)
//         ->count();

//     $rejected = MaintenanceRequest::with(['user', 'categories', 'assignments'])
//         ->where('status', 'rejected')
//         ->whereIn('user_id', $userIds)
//         ->count();

//     return view('employeers.dashboard', compact(
//         'totalReq', 'pending', 'assigned', 'completed', 'in_progress', 'rejected'
//     ));
// }
public function employeeDashboard()
{
    $loggedInUser = auth()->user();
    $userIds = [$loggedInUser->id]; // Always include self

    // Check role and adjust visibility scope
    if ($loggedInUser->hasRole('general_director')) {
        $sectorUsers = User::where('sector_id', $loggedInUser->sector_id);

        // Check if sector has divisions
        $hasDivisions = Division::where('sector_id', $loggedInUser->sector_id)->exists();

        if ($hasDivisions) {
            // Only include division managers (not regular users)
            $userIds = array_merge($userIds, $sectorUsers->role('division_manager')->pluck('id')->toArray());
        } else {
            // Include all users in the sector
            $userIds = array_merge($userIds, $sectorUsers->pluck('id')->toArray());
        }

    } elseif ($loggedInUser->hasRole('division_manager')) {
        $divisionUsers = User::where('division_id', $loggedInUser->division_id);

        // Check if division has departments
        $hasDepartments = Department::where('division_id', $loggedInUser->division_id)->exists();

        if ($hasDepartments) {
            // Only include department managers
            $userIds = array_merge($userIds, $divisionUsers->role('department_manager')->pluck('id')->toArray());
        } else {
            // Include all users in the division
            $userIds = array_merge($userIds, $divisionUsers->pluck('id')->toArray());
        }

    } elseif ($loggedInUser->hasRole('department_manager')) {
        // All users in same department
        $departmentUsers = User::where('department_id', $loggedInUser->department_id)->pluck('id')->toArray();
        $userIds = array_merge($userIds, $departmentUsers);
    }

    // Now fetch request statistics
    $totalReq = MaintenanceRequest::whereIn('user_id', $userIds)->count();

    $pending = MaintenanceRequest::where('status', 'pending')
        ->whereIn('user_id', $userIds)->count();

    $assigned = MaintenanceRequest::where('status', 'assigned')
        ->where('user_feedback', 'pending')
        ->whereIn('user_id', $userIds)->count();

    $completed = MaintenanceRequest::where('status', 'completed')
        ->where('user_feedback', 'accepted')
        ->whereIn('user_id', $userIds)->count();

    $in_progress = MaintenanceRequest::where('status', 'in_progress')
        ->whereIn('user_id', $userIds)->count();

    $rejected = MaintenanceRequest::where('status', 'rejected')
        ->whereIn('user_id', $userIds)->count();

    return view('employeers.dashboard', compact(
        'totalReq', 'pending', 'assigned', 'completed', 'in_progress', 'rejected'
    ));
}


    public function index()
    {
        $maintenances = MaintenanceRequest::with(['user', 'categories', 'item'])->where('user_id', auth()->id())->latest()->paginate(10);
        return view('employeers.maintenance.index', compact('maintenances'));
    }
    public function completedRequests()
    {
        $completedRequest = MaintenanceRequest::with(['user', 'item', 'latestAssignment', 'categories', 'item.categories', 'rejectedBy'])->where('status', 'completed')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('employeers.maintenance.completed', compact('completedRequest'));
    }
    public function pendingRequests()
    {
        $pendingRequest = MaintenanceRequest::with(['user', 'item', 'latestAssignment', 'categories', 'item.categories', 'rejectedBy'])->where('status', 'pending')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('employeers.maintenance.pending', compact('pendingRequest'));
    }
    public function inProgressRequests()
    {
        $inProgressRequest = MaintenanceRequest::with(['user', 'item', 'latestAssignment', 'categories', 'item.categories', 'rejectedBy'])->where('status', 'in_progress')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('employeers.maintenance.in_progress', compact('inProgressRequest'));
    }
    public function assignedRequests()
    {
        $AssignedRequest = MaintenanceRequest::with(['user', 'item', 'latestAssignment', 'categories', 'item.categories', 'rejectedBy', 'latestAssignment.technician'])->where('status', 'assigned')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('employeers.maintenance.assigned', compact('AssignedRequest'));
    }
    public function rejectedRequests()
    {
        $inProgressRequest = MaintenanceRequest::with(['user', 'item', 'latestAssignment', 'categories', 'item.categories', 'rejectedBy'])->where('status', 'rejected')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('employeers.maintenance.in_progress', compact('inProgressRequest'));
    }
}
