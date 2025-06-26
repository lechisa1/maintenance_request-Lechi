@extends('technician.dashboard.layout')
@section('content')
    <div class="container-fluid px-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center ">
                <h1 class="h3 mb-0 text-gray-800 me-3 ">Technician Dashboard</h1>
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
                                    Total Assigned</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAssigned }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-hourglass-split fs-2 text-gray-300"></i>
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
                                    Completed This Week</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedThisWeek }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle fs-2 text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Resolution Time
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $avgResolutionTime }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clipboard-data fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Inprogress Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-comments fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <!-- Bar Chart (Weekly Requests) -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">Requests This Week</div>
                    <div class="card-body">
                        <canvas id="weeklyBarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart (Status Breakdown) -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">Requested Status</div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="statusPieChart" style="max-height: 100%; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

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
    </script>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const rawStatusData = @json($statusBreakdown);

        // Define a fixed status-color map
        const statusColorMap = {
            'pending': '#f6c23e',
            'assigned': '#4e73df',
            'in_progress': '#36b9cc',
            'completed': '#1cc88a',
            'cancelled': '#e74a3b',
            'on_hold': '#858796'
        };

        // Build pie chart data in fixed order
        const pieLabels = [];
        const pieData = [];
        const pieColors = [];

        for (const status in statusColorMap) {
            if (rawStatusData.hasOwnProperty(status)) {
                pieLabels.push(status.replace('_', ' ').toUpperCase()); // Optional: prettify label
                pieData.push(rawStatusData[status]);
                pieColors.push(statusColorMap[status]);
            }
        }

        const pieCtx = document.getElementById('statusPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieColors,
                }]
            }
        });
    </script>
    <script>
        const weeklyData = @json($weeklyData);

        // Sort the dates (keys) to ensure the bar chart is ordered
        const sortedDates = Object.keys(weeklyData).sort();

        const barLabels = sortedDates;
        const barData = sortedDates.map(date => weeklyData[date]);

        const barCtx = document.getElementById('weeklyBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Requests',
                    data: barData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Optional: use gradient or multiple colors
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
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
