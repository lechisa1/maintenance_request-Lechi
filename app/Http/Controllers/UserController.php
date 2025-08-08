<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use App\Models\Division;
use App\Models\Sector;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with('reportsTo','division','sector')->latest()->paginate(10);
        $departments = Department::all();
        // Get the count of users for each department
        $departmentUserCount = Department::withCount('users')->get();
        $totalUsers = User::count();
        // Get the count of users for each role using Spatie's relationship
        $roleUserCount = \Spatie\Permission\Models\Role::withCount(['users'])->get();

        return view('usermanagement.index', compact('users', 'departments', 'departmentUserCount', 'roleUserCount', 'totalUsers'));
    }
    public function getDivisions($id)
    {
        $divisions = Division::where('sector_id', $id)->get();
        return response()->json($divisions);
    }

    public function getDepartments($id)
    {
        $departments = Department::where('division_id', $id)->get();
        return response()->json($departments);
    }


    public function create()
    {
        $loggedInUser = Auth::user();
        $roles = Role::all();
        $sectors = Sector::all();
        $job_positions = JobPosition::all();
        $users = User::where('id', '!=', $loggedInUser->id)->get(); // Exclude the logged-in user
        $departments = Department::all();
        return view('usermanagement.create', compact('departments', 'roles', 'users', 'job_positions', 'sectors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", 'string'],
            "email" => "required|unique:users,email|email|max:30",
            'sector_id' => 'required|exists:sectors,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            "phone" => "nullable|string|unique:users,phone",
            'job_position_id' => 'nullable|exists:job_positions,id',
            "password" => "required|min:8",
            "specialization" => "nullable",
            'roles' => 'required|string|exists:roles,name',
            'reports_to' => 'nullable|exists:users,id',

            // ensure roles are selected


        ]);


// dd($data['division_id']);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'job_position_id' => $data['job_position_id'] ?? null,
            'sector_id' => $data['sector_id'] ?? null,
            'division_id' => $data['division_id'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'reports_to' => $data['reports_to'] ?? null,
        ]);
        $user->syncRoles($data['roles']); // this updates model_has_roles correctly

        return redirect()->route('users_index')->with('success', 'User Created successfully.');
    }
    public function edit(User $user)
    {

        $departments = Department::all();
        $loggedInUser = Auth::user();
        $sectors = Sector::all();
       $divisions = Division::all();
        $roles = Role::all();
        $sectors = Sector::all();
        $job_positions = JobPosition::all();
        $users = User::where('id', '!=', $user->id)->get();
        // $users = User::where('id', '!=', $loggedInUser->id)->get(); 
        return view('usermanagement.edit', compact('user', 'departments', 'roles', 'users', 'job_positions','sectors','divisions'));
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
                        'sector_id' => 'required|exists:sectors,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            "phone" => [
                "nullable",
                "string",
                Rule::unique('users', 'phone')->ignore($user->id),
                "regex:/^(\+251|251|0)?9\d{8}$/",
            ],
            'job_position_id' => 'nullable|exists:job_positions,id',
            "password" => "nullable|min:8",
            "specialization" => "nullable",
            'roles' => 'required|string|exists:roles,name',
            'reports_to' => 'nullable|exists:users,id',
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'sector_id' => $data['sector_id'] ?? null,
            'division_id' => $data['division_id'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'job_position_id' => $data['job_position_id'] ?? null,
            'reports_to' => $data['reports_to'] ?? null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        $user->syncRoles($data['roles']);

        return redirect()->route('users_index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        $notification = [
            "message" => "User deleted Successfully!!!",
            "alert" => "success"
        ];
        return redirect()->route('users_index')->with('success', 'User Deleted successfully.');
    }
    public function showChangePasswordForm()
{
    return view('users.change_password'); // create this view
}
public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required'],
        'new_password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[a-z]/',      // at least one lowercase
            'regex:/[A-Z]/',      // at least one uppercase
            'regex:/[0-9]/',      // at least one digit
            'regex:/[@$!%*?&]/'   // at least one special char
        ],
    ], [
        'new_password.regex' => 'Password must include upper/lowercase letters, a number, and a special character.',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    // ✅ Log password change (activity log)
    Log::info('User password changed', [
        'user_id' => $user->id,
        'email' => $user->email,
        'timestamp' => now()->toDateTimeString()
    ]);

    return back()->with('success', 'Password changed successfully.');
}
}
