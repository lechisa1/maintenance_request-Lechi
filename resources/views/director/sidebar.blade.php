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
            <div class="mb-2">
                <div class="text-white fw-bold text-uppercase text-xs mb-2">Home</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('director.dashboard') }}" class="nav-link active">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="mb-2">
                <div class="text-white fw-bold text-uppercase text-xs mb-2">List Of Task</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#usersMenu">
                            <i class="bi bi-list-task"></i>
                            <span>Maintenance Tasks</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="usersMenu">
                            <ul class="nav flex-column ps-3">
                                <li class="nav-item">
                                    <a href="{{ route('pending_maintenance') }}"
                                        class="nav-link  bi bi-hourglass-split text-info">
                                        Pending </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('assigned_maintenance') }}"
                                        class="nav-link bi bi-arrow-right-circle text-warning">Assigned</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('in_progress_maintenance') }}"
                                        class="nav-link bi bi-arrow-repeat text-primary">
                                        In Progress </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('completed_maintenance') }}"
                                        class="nav-link bi bi-check-circle text-success">Completed</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('rejected_maintenance') }}"
                                        class="nav-link bi bi-slash-circle text-danger">Rejected</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#MaintenanceMenu">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Maintenance Request</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="MaintenanceMenu">
                            <ul class="nav flex-column ps-3">
                                <li class="nav-item">
                                    <a href="{{ route('requests_indexs') }}"
                                        class="nav-link bi bi-arrow-right-circle text-warning">Requested
                                        List</a>
                                </li>


                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#productsMenu">
                            <i class="bi bi-tools"></i>
                            <span>Maintenance Type</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="productsMenu">
                            <ul class="nav flex-column ps-3">
                                <li class="nav-item">
                                    <a href="{{ route('categories.index') }}"
                                        class="nav-link bi bi-hammer text-info">Miantenance
                                        Category</a>
                                </li>


                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ItemRegister">
                            <i class="bi bi-laptop"></i>
                            <span>Item Register</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="ItemRegister">
                            <ul class="nav flex-column ps-3">
                                <li class="nav-item">
                                    <a href="{{ route('item_index') }}" class="nav-link bi bi-box text-info">Item
                                        Register</a>
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

            {{-- <div class="mb-2">
                <div class="text-white fw-bold text-uppercase text-xs mb-2">System</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link">
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-question-circle"></i>
                            <span>Help</span>
                        </a>
                    </li>
                </ul>
            </div> --}}
        </div>
    </div>
