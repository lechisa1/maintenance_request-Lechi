<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    //
public function dashboard()
{
    $totalUsers = User::count(); // Total number of users
    $totalDepartments = Department::count(); // Total number of departments
    $auth = auth()->user();
    $ad=$auth->hasRole('admin');
    if (!$auth || !$auth->hasRole('admin')) {
        abort(403, 'Unauthorized');
    }
    $usersPerDepartment = Department::withCount('users')->get(); // Each department with user count

    return view('admin.dashboard', compact('totalUsers', 'totalDepartments', 'usersPerDepartment','ad'));
}

}