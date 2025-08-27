<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\NotificationController;
// use App\Http\Controllers\technician\TechnicianController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationLabelController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\MaintenanceCategoryController;

Route::middleware('web')->group(function () {

    Route::get('/', [LoginController::class, 'showLoginForm'])->name('loginForm');
    Route::post('user/login/page', [LoginController::class, 'loginMethod'])->name('login');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/profile/image/upload', [ProfileController::class, 'uploadImage'])->name('profile.image.upload');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/supervisor/requests', [MaintenanceRequestController::class, 'addSupervisorLetter'])->name('supervisor_requests');
    Route::post('/supervisor/approve/{id}', [MaintenanceRequestController::class, 'approveAndForward'])->middleware('permission:approve_staff_request')->name('supervisor.approve');
    Route::post('/supervisor/reject/{id}', [MaintenanceRequestController::class, 'supervisorRejectRequest'])->middleware('permission:reject_staff_request')->name('supervisor.reject');
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('change_password_form');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('change_password');

    Route::get('/maintenance/create', [MaintenanceRequestController::class, 'create'])->name('requests.create');
    Route::post('/maintenance/post', [MaintenanceRequestController::class, 'store'])->name('requests.store');
    Route::get('/maintenance/index', [MaintenanceRequestController::class, 'index'])->name('requests_indexs');
    Route::get('/requests/{id}/edit', [MaintenanceRequestController::class, 'edit'])->name('requests.edit');
    Route::post('/requests/{id}', [MaintenanceRequestController::class, 'update'])->name('requests.update');
    Route::delete('/requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'destroy'])->name('requests.delete');
    //here is the route that user can accept or rejected work by technician
    Route::get('/notifications/redirect/{id}', [NotificationController::class, 'redirectNotification'])
        ->name('notifications.redirect');
    Route::post('/maintenance-requests/{id}', [MaintenanceRequestController::class, 'show'])->name('maintenance_requests.show');
    Route::post('/requests/{maintenanceRequest}/respond', [MaintenanceRequestController::class, 'respondToCompletion'])->name('requests.respond');
    Route::get('/attachments/{attachment}/download', [MaintenanceRequestController::class, 'download'])->name('attachments.download');

    // Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('department/create', [DepartmentController::class, 'create'])->middleware('permission:add_new_division')->name('create_department');
        Route::post('department/posting', [DepartmentController::class, 'store'])->middleware('permission:add_new_division')->name('save_department');
        Route::get('department/index', [DepartmentController::class, 'index'])->middleware('permission:edit_division')->name('department_index');
        Route::get('department/{department}/edit', [DepartmentController::class, 'edit'])->middleware('permission:edit_division')->name('department_edit');
        Route::post('department/{department}/edit', [DepartmentController::class, 'update'])->middleware('permission:edit_division')->name('department_update');
        Route::delete('department/{department}', [DepartmentController::class, 'destroy'])->middleware('permission:delete_division')->name('delete_department');
        //here user management
        Route::get('users/index', [UserController::class, 'index'])->name('users_index');
        Route::get('users/', [UserController::class, 'create'])->middleware('permission:add_new_user')->name('create_users');
        Route::get('/sectors/{id}/divisions', [UserController::class, 'getDivisions'])->name('get_division_name');
        Route::get('/divisions/{id}/departments', [UserController::class, 'getDepartments'])->name('get_department_name');


        Route::post('users/create', [UserController::class, 'store'])->middleware('permission:add_new_user')->name('save_users');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:edit_user')->name('edit_user');
        Route::post('users/{user}/edit', [UserController::class, 'update'])->middleware('permission:edit_user')->name('update_user');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete_user')->name('delete_user');
        Route::get('user/roles/create', [RoleController::class, 'addRoleForm'])->middleware('permission:add_new_role')->name('roles_create');
        Route::post('user/roles', [RoleController::class, 'saveRole'])->middleware('permission:add_new_role')->name('roles_store');
        Route::get('users/roles/with_permission', [RoleController::class, 'listOfRoles'])->name('roles_with_permission');
        Route::get('user_roles/{role}/edit', [RoleController::class, 'editRole'])->middleware('permission:edit_role')->name('edit_role');
        Route::put('user_roles/{role}/edit', [RoleController::class, 'updateRole'])->middleware('permission:edit_role')->name('update_role');
        Route::delete('roles/{role}', [RoleController::class, 'deleteRole'])->middleware('permission:delete_role')->name('delete_role');

        Route::get('/organization/create', [OrganizationController::class, 'create'])->name('organization.create');
        Route::post('/organization/store', [OrganizationController::class, 'store'])->name('organization.store');

        // AJAX routes
        Route::get('/organization/get-division-form', [OrganizationController::class, 'getDivisionForm'])->name('organization.getDivisionForm');
        Route::get('/organization/get-department-form', [OrganizationController::class, 'getDepartmentForm'])->name('organization.getDepartmentForm');
        Route::get('/organization/organization_detail', [OrganizationController::class, 'index'])->name('organization.index');
        // Sector routes
        Route::get('organization/{sector}/edit', [OrganizationController::class, 'editSector'])->name('organization.sector.edit');
        Route::put('organization/{sector}', [OrganizationController::class, 'updateSector'])->name('organization.sector.update');
        Route::delete('organization/{sector}', [OrganizationController::class, 'destroySector'])->name('organization.sector.destroy');

        // Division routes
        Route::get('division/{division}/edit', [OrganizationController::class, 'editDivision'])->name('organization.division.edit');
        Route::put('division/{division}', [OrganizationController::class, 'updateDivision'])->name('organization.division.update');
        Route::delete('division/{division}', [OrganizationController::class, 'destroyDivision'])->name('organization.division.destroy');

        // Department routes
        Route::get('department/{department}/edit', [OrganizationController::class, 'editDepartment'])->name('organization.department.edit');
        Route::put('department/{department}', [OrganizationController::class, 'updateDepartment'])->name('organization.department.update');
        Route::delete('department/{department}', [OrganizationController::class, 'destroyDepartment'])->name('organization.department.destroy');
// In your routes file (web.php)
//here adding division to sector
    Route::get('/sector/{sector}/add-division', [OrganizationController::class, 'addDivisionToSector'])->name('organization.sector.add-division');
    Route::post('/sector/{sector}/store-division', [OrganizationController::class, 'storeDivisionToSector'])->name('organization.sector.store-division');
    //here adding department to division
    Route::get('/division/{division}/add-department', [OrganizationController::class, 'addDepartmentToDivision'])->name('organization.division.add-department');
    Route::post('/division/{division}/store-department', [OrganizationController::class, 'storeDepartmentToDivision'])->name('organization.division.store-department');

    //direct adding department to sector 
        Route::get('/sector/to/{sector}/add-department', [OrganizationController::class, 'addDepartmentToSector'])->name('organization.sector.add-department');
    Route::post('/sector/to/{sector}/store-department', [OrganizationController::class, 'storeDepartmentToSector'])->name('organization.sector.store-department');

       Route::get('/organization/created/index', [OrganizationController::class, 'organizationIndex'])->name('organization.name.index');
    // route for craeting organization name

    Route::get('/organization/name/edit/{id}/edit', [OrganizationController::class, 'editOrganization'])->name('organization.name.edit');
    Route::put('/organization/name/{id}', [OrganizationController::class, 'updateOrganization'])->name('organization.name.update');
    Route::delete('/organization/name/delete/{id}', [OrganizationController::class, 'destroyOrganization'])->name('organization.name.destroy');

            Route::get('/organization/data/create', [OrganizationController::class, 'createOrganization'])->name('organization.name.create');
    Route::post('/organization/data/store', [OrganizationController::class, 'storeOrganization'])->name('organization.name.store');
        //dynamic naming organization
        Route::get('/labels', [OrganizationLabelController::class, 'index'])->name('labels.index');
        Route::post('/labels', [OrganizationLabelController::class, 'update'])->name('labels.update');

    });
    Route::post('/requests/{maintenanceRequest}/assign', [DirectorController::class, 'assign'])->name('requests.assign');
    Route::post('/requests/{maintenanceRequest}/reject', [DirectorController::class, 'rejectRequest'])->name('requests.reject');

    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

        Route::get('maintenance/pendings', [MaintenanceRequestController::class, 'division_director_request_view'])->middleware('permission:approve_staff_request')->name('division_director_request_view');
    Route::get(
        '/categories',
        [MaintenanceCategoryController::class, 'index']
    )->name('categories.index');
    Route::post(
        '/categories',
        [MaintenanceCategoryController::class, 'store']
    )->name('categories.store');
    Route::get(
        '/categories/create',
        [MaintenanceCategoryController::class, 'create']
    )->name('categories.create');
    Route::get(
        '/categorie/{category}/edit',
        [MaintenanceCategoryController::class, 'edit']
    )->name('categories.edit');
    Route::post(
        '/requests/{category}/update',
        [MaintenanceCategoryController::class, 'update']
    )->name('categories.update');
    Route::delete(
        '/categories/{category}',
        [MaintenanceCategoryController::class, 'destroy']
    )->name('categories.destroy');

    Route::get('/requests/detail/show/{id}', [DirectorController::class, 'show'])->name('requests.show');

    Route::middleware(['auth', 'role:Ict_director'])->prefix('director')->group(function () {
        Route::get('director/dashboard', [DirectorController::class, 'directorDashboard'])->name('director.dashboard');

        Route::get('maintenance/pending', [DirectorController::class, 'maintenenceRequestPending'])->name('maintenance_request_pending');
        Route::get('maintenece/assign', [DirectorController::class, 'assign'])->name('assign_request_to_technician
    ');
        Route::get('maintenece/completed', [DirectorController::class, 'getCompletedRequests'])->name('completed_maintenance');

        Route::get('maintenece/in_progress', [DirectorController::class, 'getInProgressRequests'])->name('in_progress_maintenance');
        Route::get('maintenance/pendings', [DirectorController::class, 'getPendingRequests'])->name('pending_maintenance');
        Route::get('maintenece/rejected', [DirectorController::class, 'getRejectedRequests'])->name('rejected_maintenance');


        Route::get('maintenece/assigned', [DirectorController::class, 'getAssignedRequests'])->name('assigned_maintenance');
        // Show the assignment form
        Route::get('/requests/{id}/assign', [DirectorController::class, 'showAssignForm'])->name('requests.showAssignForm');

        Route::get('/item/index', [ItemController::class, 'itemIndex'])->name('item_index');
        Route::get('/item/registeration', [ItemController::class, 'itemRegisterationForm'])->name('item_registeration_form');
        Route::post('/item/register/save', [ItemController::class, 'itemRegisteration'])->name('item_store');
        Route::get('/items/{item}/category', [ItemController::class, 'getCategory']);
        Route::delete('/items/{item}', [ItemController::class, 'destroyItem'])->name('delete_item');
        Route::get('/items/{id}/edit', [ItemController::class, 'editItemForm'])->name('edit_item');
        Route::post('/items/{id}', [ItemController::class, 'updateItem'])->name('update_item');
    });

    Route::middleware(['auth', 'role:technician|Division Manager'])->prefix('technician')->group(function () {
        Route::get('technician/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
        Route::get('/technician/request/{request}/work', [TechnicianController::class, 'technicianWorkProgress'])->name('tecknician_work_form');
        Route::post('/technician/requests/{maintenanceRequest}/work', [TechnicianController::class, 'updateWork'])->name('tecknician_work_save');
        Route::get('/technician/assigned-requests', [TechnicianController::class, 'assignedRequests'])->name('technician.requests');
        Route::get('/tecknician/{request}/show', [TechnicianController::class, 'show'])->name('technician.show');
        Route::get('/tecknician/completedTask/completed', [TechnicianController::class, 'completedTask'])->name('completed_task');
        Route::get('/tecknician/inprogress/inprogress', [TechnicianController::class, 'inProgressTask'])->name('inProgress_task');
        // here is tecknician controllers
        // Route::get('/technician/requests/{request}/work', [TechnicianController::class, 'technicianWorkProgress'])->name('tecknician_work_form');
        // Route::post('/technician/requests/{maintenanceRequest}/work', [TechnicianController::class, 'updateWork'])->name('tecknician_work_save');
        // Route::get('/technician/assigned-requests', [TechnicianController::class, 'assignedRequests'])->name('technician.requests');
        // Route::get('/tecknician/{request}/show', [TechnicianController::class, 'show'])->name('requests.show');
        // Route::get('/tecknician/completedTask/completed', [TechnicianController::class, 'completedTask'])->name('completed_task');
        // Route::get('/tecknician/inprogress/inprogress', [TechnicianController::class, 'inProgressTask'])->name('inProgress_task');
    });
    Route::middleware(['auth', 'role:Employee|Division Manager|general_director|division_manager|department_manager'])->group(function () {
        Route::get('employees/dashboard', [EmployeeController::class, 'employeeDashboard'])->name('employer.dashboard');
        Route::get('employeers/maintenance/index', [EmployeeController::class, 'index'])->name('employer.index');
        Route::get('employeers/maintenance/pending', [EmployeeController::class, 'pendingRequests'])->name('employer.pending');
        Route::get('employeers/maintenance/completed', [EmployeeController::class, 'completedRequests'])->name('employer.completed');
        Route::get('employeers/maintenance/in_progress', [EmployeeController::class, 'inProgressRequests'])->name('employer.in_progress');
        Route::get('employeers/maintenance/assigned', [EmployeeController::class, 'assignedRequests'])->name('employer.assigned');

    });
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');