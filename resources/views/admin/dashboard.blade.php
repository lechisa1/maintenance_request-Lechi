@extends('admin.layout.app')
{{-- @extends('employeers.dashboard.layout') --}}
@section('content')
    <!-- Sidebar -->


    <!-- Main Content -->

    <!-- Header -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Page Content -->
    <div class="container-fluid px-4  bg-white mt-5">
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
        <div class=" bg-white">
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary h-100">
                    <div class="card-body ">
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
</div>

        <!-- Charts Row -->


        <!-- Required CSS -->
<style>
    body {
        background-color: #f8f9fc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .h3, h4 {
        font-weight: 600;
        color: #343a40;
    }

    #date-time {
        font-size: 16px;
    }

    .badge {
        font-size: 14px;
        font-weight: 500;
    }

    .btn-primary {
        border-radius: 50px;
        padding: 8px 20px;
    }

    canvas {
        max-height: 400px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important;
        background-color: #e9ecef;
        border: none;
        margin: 2px;
        padding: 6px 12px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd;
        color: #fff !important;
    }
</style>


        <!-- Required JavaScript -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    </div>

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
        <div class="card bg-white m-3">
        <h4 class="text-center">Users per Division</h4>
        <div class="m-5">
            <canvas id="usersChart"></canvas></div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('usersChart').getContext('2d');
const usersChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($usersPerDepartment->pluck('name')) !!},
        datasets: [{
            label: 'Users',
            data: {!! json_encode($usersPerDepartment->pluck('users_count')) !!},
            backgroundColor: [
                '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14'
            ],
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
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
