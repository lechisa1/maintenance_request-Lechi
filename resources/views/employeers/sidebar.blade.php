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

        <div class="px-3 pt-3">
            <div class="mb-4">
                <div class="text-white fw-bold text-uppercase text-xs mb-2">Home</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('employer.dashboard') }}" class="nav-link active">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="mb-4">
                <div class="text-white fw-bold text-uppercase text-xs mb-2">List Of Task</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#usersMenu">
                            <i class="bi bi-wrench"></i>
                            <span>Maintenance status</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="usersMenu">
                            <ul class="nav flex-column ps-3">
                                <li class="nav-item">
                                <li class="nav-item">
                                    <a href="{{ route('employer.pending') }}"
                                        class="nav-link text-primary bi bi-hourglass-split">
                                        Pending</a>
                                </li>
                                <a href="{{ route('employer.assigned') }}"
                                    class="nav-link text-warning bi bi-clipboard-check">
                                    Assigned</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employer.in_progress') }}" class="nav-link text-info bi bi-arrow-repeat">
                            In progress</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employer.completed') }}" class="nav-link text-success bi bi-check-circle">
                            Completed</a>
                    </li>
                </ul>
            </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#productsMenu">
                    <i class="bi bi-gear-fill"></i>
                    <span>Maintenance</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="productsMenu">
                    <ul class="nav flex-column ps-3">
                        <li class="nav-item">
                            <a href="{{ route('requests_indexs') }}"
                                class="nav-link bi bi-question-circle text-warning">Maintenance
                                Request</a>
                        </li>

                    </ul>
                </div>
            </li>

            </ul>
        </div>

        <div class="mb-4">
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
        </div>
    </div>
    </div>
