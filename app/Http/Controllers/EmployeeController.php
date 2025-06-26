<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;

class EmployeeController extends Controller
{
    //
    public function employeeDashboard()
    {
        $totalReq = MaintenanceRequest::with(['user', 'categories', 'assignments'])->where('user_id', auth()->id())->count();
        $pending = MaintenanceRequest::with(['user', 'categories', 'assignments'])->where('status', 'pending')->where('user_id', auth()->id())->count();
        $assigned = MaintenanceRequest::with(['user', 'categories', 'assignments'])->where('status', 'assigned')->where('user_id', auth()->id())->where('user_feedback', 'pending')->count();
        $completed = MaintenanceRequest::with(['user', 'categories', 'assignments'])->where('status', 'completed')->where('user_id', auth()->id())->where('user_feedback', 'accepted')->count();
        $in_progress = MaintenanceRequest::with(['user', 'categories', 'assignments'])->where('status', 'in_progress')->where('user_id', auth()->id())->count();
        $rejected = MaintenanceRequest::with(['user', 'categories', 'assignments'])->where('status', 'rejected')->where('user_id', auth()->id())->count();
        return view('employeers.dashboard', compact('totalReq', 'pending', 'assigned', 'completed', 'in_progress', 'rejected'));
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
