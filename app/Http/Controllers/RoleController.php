<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
public function listOfRoles(Request $request)
{
    $query = Role::with('permissions')->latest();
    
    // Add search functionality
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('permissions', function($permissionQuery) use ($searchTerm) {
                  $permissionQuery->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }
    
    $roles = $query->paginate(10); // Adjust pagination as needed
    
    // Append search parameter to pagination links if it exists
    if ($request->has('search')) {
        $roles->appends(['search' => $request->search]);
    }
    
    return view('role.list', compact('roles'));
}
    public function addRoleForm()
    {
        $permissions= Permission::all();
        return view('role.role_form', compact('permissions'));
        
    }
public function saveRole(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'required|array',
        "description" => "nullable|string"
    ]);
    $data = [
        "name" => $validated['name'],
        "description" => $validated['description'] ?? null
    ];
    $role = Role::create($data);
    $role->syncPermissions($validated['permissions']);
    return redirect()->route('roles_create')->with('success', 'Role created and permissions assigned.');
}
    public function editRole(Role $role)
    {
        $permissions= Permission::all();

        return view('role.edit_role',compact('role','permissions'));
    }
public function updateRole(Request $request, Role $role)
{
    $validated = $request->validate([
        "name" => "required|string",
        "permissions" => "required|array",
        "description" => "nullable|string"
    ]);

    $role->update([
        "name" => $validated['name'],
        "description" => $validated['description'] ?? null
    ]);
    $role->syncPermissions($validated['permissions']);

    return redirect()->route('roles_with_permission')->with('success', 'Role updated successfully.');
}
    public function deleteRole(Role $role)
    {
        $role->delete();
        return redirect()->route('roles_with_permission')->with(["success" => "Role deleted successfully"]);
    }
}