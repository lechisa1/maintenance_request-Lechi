<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
class DepartmentController extends Controller
{
    //
    public function index()
    {
        $departments = Department::with('director')->paginate(10);
        return view('departments.index', compact('departments'));
    }
    public function create()
    {
        
// $potentialDirectors = User::whereNull('department_id')->get();
$potentialDirectors = User::all();
        return view('departments.create',compact('potentialDirectors'));
    }
    public function store(Request $request)
    {
        $department = $request->validate([
            "name" => "required|unique:departments,name",
            "description" => "nullable|string",
            "director_id" => "nullable|exists:users,id"
        ]);
        Department::create($department);
        $notification = [
            "message" => "Department Created Successfully",
            "alert" => "success"
        ];

        return redirect()->route('department_index')->with("success", "Department Created Successfully");
    }
    public function edit(Department $department)
    {
    // Get all user IDs who are already directors
    $directorIds = Department::whereNotNull('director_id')->pluck('director_id');

    // Get users who are NOT directors
    $potentialDirectors = User::whereNotIn('id', $directorIds)->get();
        return view('departments.edit', compact('department','potentialDirectors'));
    }
    public function update(Request $request, Department $department)
    {
        $validation = $request->validate([
            "name" => "required|unique:departments,name," . $department->id,
            "description" => "nullable|string",
            "director_id" => "nullable|exists:users,id"
        ]);
        $department->update($validation);
        $notification = [
            "message" => "Department Created Successfully",
            "alert" => "success"
        ];

        return redirect()->route('department_index')->with("success", "Department Updated Successfully");
    }
    public function destroy(Department $department)
    {

        $department->delete();
        $notification = [
            "message" => "Department Deleted Successfully",
            "alert" => "success"
        ];

        return redirect()->route('department_index')->with("success", "Department Deleted Successfully");
    }
}