@extends('employeers.dashboard.layout')
@section('content')
    <div class="container-fluid px-4 card bg-white mt-5 ">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center ">
                <h1 class="h3 mb-0 text-gray-800 me-3 ">Employers Dashboard</h1>
                <div id="date-time" class="badge bg-light text-primary shadow-sm px-3 py-2 rounded-pill"
                    style="font-size: 18px;margin-left:120px;"></div>

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
                                    Total Your Req</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReq }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-sum fs-2 text-primary-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-arrow fs-2 text-warning-300"></i>
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
                                    completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completed }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-arrow fs-2 text-success-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    In Progress</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $in_progress }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-arrow fs-2 text-info-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-pink h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-pink text-uppercase mb-1">
                                    Assigned Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assigned }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-comments fs-2 text-pink-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-purple h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                    Rejected Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rejected }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-comments fs-2 text-purple-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-primary fw-bold text-center">
                        Request Status - Pie Chart
                    </div>
                    <div class="card-body">
                        <canvas id="statusPieChart" width="100%" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header text-primary fw-bold text-center">
                        Request Status - Bar Chart
                    </div>
                    <div class="card-body">
                        <canvas id="statusBarChart" width="100%" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

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
        <!-- Required JavaScript -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            document.addEventListener('DOMContentLoaded', function() {
                const statusLabels = ['Pending', 'Assigned', 'Completed', 'In Progress', 'Rejected'];
                const statusData = [{{ $pending }}, {{ $assigned }}, {{ $completed }}, {{ $in_progress }},
                    {{ $rejected }}
                ];

                // Pie Chart
                const pieCtx = document.getElementById('statusPieChart').getContext('2d');
                new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusData,
                            backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#4e73df', '#dc3545'],
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: false
                            }
                        }
                    }
                });

                // Bar Chart
                const barCtx = document.getElementById('statusBarChart').getContext('2d');
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            label: 'Requests',
                            data: statusData,
                            backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#4e73df', '#dc3545'],
                            borderRadius: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
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
            });
        </script>

    </div>


    <!-- Footer -->





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
            })

            // Mobile sidebar toggle
            document.getElementById('sidebarToggleMobile').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });

        // Initialize charts
    </script>
    </body>
@endsection
