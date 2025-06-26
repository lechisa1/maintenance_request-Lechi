<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function listOfRoles()
    {
        return view('role.role_form');
    }
    public function addRoleForm()
    {
        return view('role.role_form');
    }
    public function saveRole(Request $request)
    {
        $validated = $request->validate([
            "role" => "required|string|unique:roles,name",
            "description" => "nullable|string"
        ]);
        $data = [
            "name" => $validated('role'),
            "description" => $validated('description')
        ];
        $role = Role::create($data);
        if ($role) {
            return redirect()->route('role.index');
        }
        return redirect()->back()->withErrors(["message" => "please try again!!"]);
    }
    public function editRole(Role $role)
    {
        $roles=Role::findOrFail($role);
        return view('role.role_form',compact('roles'));
    }
    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            "role" => "required|string",
            "description" => "nullable|string"
        ]);
        $roles = Role::findOrFail($role);
        $roles->update([
            "name" => $validated('role'),
            "description" => $validated('description')
        ]);
        if ($roles) {
            return redirect()->route('role.index')->withSuccess(["message" => "updated successfully"]);
        }
        redirect()->back()->withErrors(["message" => "Oops!! Something Wrong,please try again"]);
    }
    public function deleteRole(Role $role)
    {
        $role->delete();
        redirect()->route('role.index')->withSuccess(["message" => "deleted successfully"]);
    }
}