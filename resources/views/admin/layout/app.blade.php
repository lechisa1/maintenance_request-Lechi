
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Req</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
    .table thead.bg-blue th {
    background-color: #11245A; /* Bootstrap's primary blue */
    color: white;
    border-bottom: 2px solid #0a58ca; /* Slightly darker blue for border */
    position: sticky;
    top: 0;
    z-index: 10;
    padding: 12px 8px; /* Slightly larger padding */
}
.btn-primary {
    background-color: #11245A !important;
    border-color: #11245A !important;
}
.text-primary {
   
    color: #11245A !important;
}
.bg-success{
        background-color: #11245A !important;
    border-color: #11245A !important;
}
/* Hover effect for better interactivity */
.table thead.bg-blue th:hover {
    background-color: #0bd79a; /* Slightly darker on hover */
}
/* Main Content Adjustments */
#main-content {
    margin-left: var(--sidebar-width);
    padding: 20px;
    min-height: 100vh;
    position: relative;
    z-index: 1;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

/* Card and Form Containers */
.card {
    overflow: visible !important;
    position: relative;
    z-index: 2;
}

.card-body {
    overflow: visible !important;
}

/* Division and Department Forms */
.division-form {
    position: relative;
    z-index: 3;
    margin-bottom: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.division-departments {
    background-color: #f9f9f9;
    border-radius: 6px;
    padding: 15px;
    margin-top: 15px;
    border-left: 3px solid #0d6efd;
}

.department-form {
    position: relative;
    z-index: 4;
    background-color: white;
    border-radius: 6px;
    margin-bottom: 15px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    border-left: 3px solid #20c997;
}

/* Ensure nothing is hidden */
.tab-content,
.tab-pane,
#divisionsContainer,
#departmentsContainer {
    overflow: visible !important;
    position: relative;
}

/* Fix for accordion items */
.accordion-item {
    overflow: visible;
}

.accordion-body {
    overflow: visible !important;
    padding: 20px;
    background-color: #f9f9f9;
}
</style>
</head>

<body class="bg-white">
    <!-- Sidebar -->


    @include('admin.sidebar')
    <!-- Main Content -->
    <div id="main-content">
        <!-- Header -->

        @include('technician.dashboard.header')
        <!-- Page Content -->
        <div class="container-fluid px-4">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->

    @include('technician.dashboard.footer')
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>
