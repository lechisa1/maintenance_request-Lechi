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
                    @if (auth()->user()->can('view_dashboard'))
                        <li class="nav-item">
                            <a href="{{ route('employer.dashboard') }}" class="nav-link active">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </div>

            <div class="mb-4">
                <div class="text-white fw-bold text-uppercase text-xs mb-2">List Of Task</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#usersMenu">
                            <i class="bi bi-clipboard-data"></i>
                            <span>Track Requests</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="usersMenu">
                            <ul class="nav flex-column ps-3">
                                <li class="nav-item">
                                    @if (auth()->user()->can('view_maintenance_requests'))
                                <li class="nav-item">
                                    <a href="{{ route('employer.pending') }}"
                                        class="nav-link text-info bi bi-hourglass-split">
                                        Pending Approval</a>
                                </li>
                                @endif
                                @if (auth()->user()->can('view_assigned_requests'))
                                    <a href="{{ route('employer.assigned') }}"
                                        class="nav-link text-warning bi bi-clipboard-check">
                                        Assigned to Technician</a>
                    </li>
                    @endif
                    @if (auth()->user()->can('view_maintenance_requests'))
                        <li class="nav-item">
                            <a href="{{ route('employer.in_progress') }}"
                                class="nav-link text-purple bi bi-arrow-repeat">
                                Work in Progress</a>
                        </li>
                    @endif
                    @if (auth()->user()->can('view_maintenance_requests'))
                        <li class="nav-item">
                            <a href="{{ route('employer.completed') }}"
                                class="nav-link text-success bi bi-check-circle">
                                Completed Tasks</a>
                        </li>
                    @endif

                </ul>
            </div>
            </li>


            @if (auth()->user()->can('create_maintenance_request'))
                <li class="nav-item mt-3">
                    <a href="{{ route('requests_indexs') }}" class="nav-link bi bi-question-circle ">New
                        Request</a>
                </li>
            @endif
            @if (auth()->user()->can('approve_staff_request') || auth()->user()->can('reject_staff_request'))
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#pendingMenu">
                        <i class="bi bi-people"></i>
                        <span>Staff Requests</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="pendingMenu">
                        <ul class="nav flex-column ps-3">

                            @if (auth()->user()->can('approve_staff_request'))
                                <li class="nav-item">
                                    <a href="{{ route('supervisor_requests') }}" class="nav-link"
                                        title="Needs Approval (Hardware)">
                                        <i class="bi bi-tools"></i>
                                        <span>Needs Approval (HWR)</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->can('view_their_division_requests'))
                                <li class="nav-item">
                                    <a href="{{ route('division_director_request_view') }}" class="nav-link">
                                        <i class="bi bi-eye"></i>
                                        <span>View Staff Requests</span>
                                    </a>
                                </li>
                            @endif
            @endif
            </ul>
        </div>
        </li>



        </li>

        </ul>
    </div>

    </div>
    </div>
