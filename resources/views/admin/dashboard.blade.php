@extends('admin.layout.app')
{{-- @extends('employeers.dashboard.layout') --}}
@section('content')
    <!-- Sidebar -->


    <!-- Main Content -->

    <!-- Header -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Page Content -->
    <div class="container-fluid px-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center ">
                <h1 class="h3 mb-0 text-gray-800 me-3 ">Admin Dashboard</h1>
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
                                    Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people fs-2 text-gray-300"></i>
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
                                    Total Divisions</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDepartments }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-diagram-3 fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <!-- Charts Row -->


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





    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');

            // Save state in localStorage
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });


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
    </script>
    </body>
    <div class="container mt-5">
        <h4 class="text-center">Users per Division</h4>
        <canvas id="usersChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('usersChart').getContext('2d');
        const usersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($usersPerDepartment->pluck('name')) !!},
                datasets: [{
                    label: 'Number of Users',
                    data: {!! json_encode($usersPerDepartment->pluck('users_count')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
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
@endsection
