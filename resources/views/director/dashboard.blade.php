@extends('director.layout.layout')
@section('content')
    <div class="container-fluid px-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center ">
                <h1 class="h3 mb-0 text-gray-800 me-3 ">Directors Dashboard</h1>
               
                <div id="date-time" class="badge bg-light text-primary shadow-sm px-3 py-2 rounded-pill"
                    style="font-size: 18px;margin-left:120px;">Today is:</div>

            </div>

            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="bi bi-download me-1"></i> Generate Report
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-total fs-2 text-primary-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completed }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-success fs-2 text-success-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- here not_fixed --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-secondary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Not Fixed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notFixed }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-success fs-2 text-secondary-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end_of not fixed --}}

            {{-- start of assigned --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-purple h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                    Assigned</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assigned }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-success fs-2 text-purple-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end assigned --}}

            {{-- rejected --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Rejected</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rejected }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-success fs-2 text-warning-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end rejected --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-pink h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-pink text-uppercase mb-1">
                                    Pending </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-comments fs-2 text-pink-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info border-bottom-primary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    In Progress </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inProgress }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-comments fs-2 text-info-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->





        <!-- Add Project Modal -->
        <div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="projectForm">
                            <div class="mb-3">
                                <label for="projectName" class="form-label">Project Name</label>
                                <input type="text" class="form-control" id="projectName" required>
                            </div>
                            <div class="mb-3">
                                <label for="projectBudget" class="form-label">Budget</label>
                                <input type="number" class="form-control" id="projectBudget" required>
                            </div>
                            <div class="mb-3">
                                <label for="projectStatus" class="form-label">Status</label>
                                <select class="form-select" id="projectStatus" required>
                                    <option value="Active">Active</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Delayed">Delayed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="projectCompletion" class="form-label">Completion (%)</label>
                                <input type="range" class="form-range" id="projectCompletion" min="0"
                                    max="100">
                                <div class="text-center" id="completionValue">50%</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveProjectBtn">Save Project</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Required CSS -->
        <style>
            /* Expand/collapse row styling */
            td.details-control {
                background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>') no-repeat center center;
                cursor: pointer;
                width: 30px;
            }

            tr.shown td.details-control {
                background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/></svg>') no-repeat center center;
            }

            /* DataTables styling */
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.25rem 0.5rem;
                margin-left: 2px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: var(--primary-color);
                color: white !important;
                border: none;
            }
        </style>

        <!-- Required JavaScript -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    </div>


    <!-- Footer -->




    <style>
        .text-purple {
            color: #6610f2 !important;
        }

        .text-success {
            color: #28a745 !important;
        }
        .text-secondary {
            color: #1684e6 !important;
        }

        .border-left-purple {
            border-left: 0.25rem solid #6610f2 !important;
        }

        .border-bottom-primary {
            border-left: 0.25rem solid #10f2f2 !important;
        }

        .border-left-secondary {
            border-left: 0.25rem solid #789c74 !important;
        }

        .border-left-primary {
            border-left: 0.25rem solid #ceaa08 !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #23e40e !important;
        }

        .text-pink {
            color: #e83e8c !important;
        }

        .border-left-pink {
            border-left: 0.25rem solid #e83e8c !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #3eb8e8 !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #e4730a !important;
        }
    </style>
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const formatted = now.toLocaleDateString('en-US', options);
            document.getElementById('date-time').textContent = formatted;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime(); // Initial call
    </script>
    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');

            // Save state in localStorage
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });

        // Check for saved state
        if (localStorage.getItem('sidebarCollapsed') {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    document.body.classList.add('sidebar-collapsed');
                }
            }

            // Mobile sidebar toggle
            document.getElementById('sidebarToggleMobile').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });

            // Initialize charts
    </script>
    </body>
    <div class="container">


        <!-- Cards -->


        <!-- Charts -->
        <div class="row">
            <!-- Pie Chart -->
            <div class="col-md-4 d-flex justify-content-center mb-3">
                <canvas id="statusPieChart" width="220" height="220" style="max-width: 220px;"></canvas>
            </div>



            <!-- Bar Chart -->
            <div class="col-md-4 d-flex justify-content-center mb-3">
                <canvas id="statusBarChart" width="300" height="220" style="max-width: 300px;"></canvas>
            </div>
            <!-- Doughnut Chart -->
            <div class="col-md-4 d-flex justify-content-center mb-3">
                <canvas id="statusChart" width="220" height="220" style="max-width: 220px;"></canvas>
            </div>

        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statusCounts = @json($statusCounts);

        const statusColorMap = {
            'pending': '#ffc107', // Yellow
            'completed': '#28a745', // Green
            'in_progress': '#17a2b8', // Blue
            'rejected': '#dc3545', // Red
            'not_fixed': '#6c757d', // Grey
            'assigned': '#6610f2' // Purple
        };

        const statuses = Object.keys(statusCounts);
        const colors = statuses.map(status => statusColorMap[status] || '#000'); // fallback to black

        // Doughnut Chart
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statuses,
                datasets: [{
                    data: Object.values(statusCounts),
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
        // Pie Chart
        const ctxPie = document.getElementById('statusPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: statuses,
                datasets: [{
                    data: Object.values(statusCounts),
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart
        const ctxBar = document.getElementById('statusBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: statuses,
                datasets: [{
                    label: 'Request Count',
                    data: Object.values(statusCounts),
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
