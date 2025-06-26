<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    //
    public function index()
    {
        $departments = Department::paginate(10);
        return view('departments.index', compact('departments'));
    }
    public function create()
    {
        return view('departments.create');
    }
    public function store(Request $request)
    {
        $department = $request->validate([
            "name" => "required|unique:departments,name",
            "description" => "nullable|string"
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
        // $dep=Department::findOrFail($department);
        return view('departments.edit', compact('department'));
    }
    public function update(Request $request, Department $department)
    {
        $validation = $request->validate([
            "name" => "required|unique:departments,name," . $department->id,
            "description" => "nullable|string",
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