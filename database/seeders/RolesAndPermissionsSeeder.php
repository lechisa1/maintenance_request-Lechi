<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $director = Role::create(['name' => 'Ict_director']);
        $technician = Role::create(['name' => 'technician']);

        $permissions = [
            'view_dashboard',
            'assign_request_to_technician',
            'resolve_maintenance',
            'approve_request',
            'reject_staff_request',
            'approve_staff_request',
            'view_maintenance_requests',
            'create_maintenance_request',
            'edit_maintenance_request',
            'delete_maintenance_request',
            'view_user_profiles',
            'manage_roles_and_permissions',
            'update_request_status',
            'view_assigned_requests',
            'add_maintenance_log',
            'reassign_request',
            'manage_request_priorities',
            'manage_technicians',
            'upload_maintenance_documents',
            'receive_user_feedback',
            'view_maintenance_logs',
         
            'reject_request',
            'comment_on_request',
            'generate_maintenance_report',
            'view_department_requests',
            'view_all_requests',
          
            'close_request',
            'view_asset_information',
            'manage_user_roles',
            'manage_user_permissions',
            'add_new_user',
            'delete_user',
            'edit_user',
            'add_new_role',
            'edit_role',
            'delete_role',
            'view_role_permissions',
            'assign_role_to_user',
            'remove_role_from_user',
            'view_user_roles',
            'view_user_permissions',
            'add_new_division',
            'edit_division',
            'delete_division',
            'view_their_division_requests',
            'view_all_division_requests',
            'view_division_statistics',
            'manage_division_requests',
            'view_division_maintenance_logs',
            'view_division_assets',
            'add_category',
            'edit_category',
            'delete_category',
            'view_categories',
            'add_item',
            'edit_item',
            'delete_item',
            'view_items',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $admin->givePermissionTo(Permission::all());
        $director->givePermissionTo(['view_dashboard', 'assign_request_to_technician', 'approve_request','view_maintenance_requests']);
        $technician->givePermissionTo(['view_dashboard', 'resolve_maintenance']);
    }
}
