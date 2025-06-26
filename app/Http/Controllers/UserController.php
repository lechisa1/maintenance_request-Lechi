<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::latest()->paginate(10);
        $departments = Department::all();
        // Get the count of users for each department
        $departmentUserCount = Department::withCount('users')->get();

        // Get the count of users for each role
        $roleUserCount = Role::withCount('users')->get();
        return view('usermanagement.index', compact('users', 'departments'));
    }
    public function create()
    {

        $roles = Role::all();
        $departments = Department::all();
        return view('usermanagement.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", 'string'],
            "email" => "required|unique:users,email|email|max:30",
            "department_id" => "required|exists:departments,id", // match the form input name
            "phone" => "nullable|string|unique:users,phone|regex:/^\+?[1-9]\d{1,14}$/",
            "password" => "required|min:8",
            "specialization" => "nullable",
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);



        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'specialization' => $data['specialization'] ?? null,
        ]);
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
            $notification = [
                "message" => "User Created Successfully!!!",
                "alert" => "success"
            ];
            return redirect()->route('users_index')->with($notification);
        }

        return redirect()->back()->with([
            "message" => "oops something wrong",
            "alert" => "danger"
        ]);
    }
    public function edit(User $user)
    {
        $departments = Department::all();
        $roles=Role::all();
        return view('usermanagement.edit', compact('user', 'departments','roles'));
    }
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            "name" => ["required", 'string'],
            "email" => [
                "required",
                "email",
                "max:30",
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            "department_id" => "required|exists:departments,id",
            "phone" => [
                "nullable",
                "string",
                Rule::unique('users', 'phone')->ignore($user->id),
                "regex:/^\+?[1-9]\d{1,14}$/",
            ],
            "password" => "nullable|min:8",
            "specialization" => "nullable",
            "roles" => "array",
            "roles.*" => "exists:roles,id",
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'department_id' => $data['department_id'],
            'specialization' => $data['specialization'] ?? null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()->route('users_index')->with([
            "message" => "User updated successfully!",
            "alert" => "success"
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        $notification = [
            "message" => "User deleted Successfully!!!",
            "alert" => "success"
        ];
        return redirect()->route('users_index')->with($notification);
    }
}