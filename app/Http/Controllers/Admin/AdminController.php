<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    //
        public function dashboard(){
        $totalUsers = User::count();
        $totalDepartments = Department::count();
        $usersPerDepartment = Department::withCount('users')->get();

        return view('admin.dashboard', compact('totalUsers', 'totalDepartments', 'usersPerDepartment'));
    }
}