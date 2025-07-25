<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand d-flex align-items-center">
            <!-- Circular Logo -->
            <div class="logo-circle me-2">
                <img src="https://th.bing.com/th/id/OIP.fXgYUVPqjNT-LJDpgiKoVAHaHa?rs=1&pid=ImgDetMain" alt="Logo"
                    class="rounded-circle">
            </div>
            <!-- Hidden on mobile -->
        </a>

        <button class="btn btn-link text-white d-md-none" id="sidebarToggleMobile">
            <i class="bi bi-x"></i>
        </button>
    </div>
@php
    use Illuminate\Support\Facades\Auth;
    $isSupervisor = \App\Models\User::where('reports_to', Auth::id())->exists();
@endphp
    <div class="px-3 pt-3">
        <div class="mb-4">
            <div class="text-white fw-bold text-uppercase text-xs mb-2">Home</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

            </ul>
        </div>
        <div class="mb-4">
            <div class="text-white fw-bold text-uppercase text-xs mb-2">User Management</div>
            <ul class="nav flex-column">
                @if (auth()->user()->can('manage_user_roles'))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#usersMenu">
                            <i class="bi bi-people"></i>
                            <span>User Management</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="usersMenu">
                            <ul class="nav flex-column ps-3">
                                <li class="nav-item">
                                <li class="nav-item">
                                    <a href="{{ route('users_index') }}" class="nav-link text-primary bi bi-list fs-6">
                                        User Lists</a>
                                </li>
                                <a href="{{ route('create_users') }}" class="nav-link text-warning bi bi-plus fs-6">
                                    Add Users</a>


                            </ul>
                        </div>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#productsMenu">
                        <i class="bi bi-diagram-3"></i>
                        <span>Organization Unit</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="productsMenu">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item">
                                <a href="{{ route('department_index') }}"
                                    class="nav-link bi bi-list text-primary fs-6">Organization Unit
                                    Lists</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('create_department') }}"
                                    class="nav-link bi bi-plus text-info fs-6">Add
                                    Organization Unit
                                </a>
                            </li>



                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#rolesMenu">
                        <i class="bi bi-shield-lock"></i>
                        <span>Roles</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="rolesMenu">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item">
                                <a href="{{ route('roles_create') }}"
                                    class="nav-link bi bi-list text-primary fs-6">Roles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles_with_permission') }}"
                                    class="nav-link bi bi-plus text-info fs-6">Add
                                    Roles
                                </a>
                            </li>



                        </ul>
                    </div>
                </li>
                            @if ($isSupervisor)
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#pendingMenu">
                    <i class="bi bi-people"></i>
                    <span>Request From Staff</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="pendingMenu">
                    <ul class="nav flex-column ps-3">
                        <li class="nav-item">
                            <a href="{{ route('supervisor_requests') }}" class="nav-link active">
                                <i class="bi bi-clipboard-data"></i>
                                <span>Pending Requests</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            </ul>
        </div>

    </div>
</div>



{{-- <script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('collapsed');
    });
</script> --}}
