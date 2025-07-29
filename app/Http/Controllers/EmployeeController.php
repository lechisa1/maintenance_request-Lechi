<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;

class EmployeeController extends Controller
{
    //
public function employeeDashboard()
{
    $loggedInUserId = auth()->id();

    // Get users who report to this user
    $reportingUserIds = User::where('reports_to', $loggedInUserId)->pluck('id')->toArray();

    // Include the supervisor's own ID
    $userIds = array_merge([$loggedInUserId], $reportingUserIds);

    // Queries using whereIn to include both self and subordinates
    $totalReq = MaintenanceRequest::with(['user', 'categories', 'assignments'])
        ->whereIn('user_id', $userIds)
        ->count();

    $pending = MaintenanceRequest::with(['user', 'categories', 'assignments'])
        ->where('status', 'pending')
        ->whereIn('user_id', $userIds)
        ->count();

    $assigned = MaintenanceRequest::with(['user', 'categories', 'assignments'])
        ->where('status', 'assigned')
        ->where('user_feedback', 'pending')
        ->whereIn('user_id', $userIds)
        ->count();

    $completed = MaintenanceRequest::with(['user', 'categories', 'assignments'])
        ->where('status', 'completed')
        ->where('user_feedback', 'accepted')
        ->whereIn('user_id', $userIds)
        ->count();

    $in_progress = MaintenanceRequest::with(['user', 'categories', 'assignments'])
        ->where('status', 'in_progress')
        ->whereIn('user_id', $userIds)
        ->count();

    $rejected = MaintenanceRequest::with(['user', 'categories', 'assignments'])
        ->where('status', 'rejected')
        ->whereIn('user_id', $userIds)
        ->count();

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
