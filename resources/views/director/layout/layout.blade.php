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
    background-color:  #11245A; /* Bootstrap's primary blue */
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
/* Hover effect for better interactivity */
.table thead.bg-blue th:hover {
    background-color: #11246A; /* Slightly darker on hover */
}
#password-requirements input[type="checkbox"] {
  accent-color: lightgray; /* default color */
  pointer-events: none; /* prevent interaction even though not disabled */
}

#password-requirements input[type="checkbox"]:checked {
  accent-color: green;
}

</style>
</head>

<body class="bg-white">
    <!-- Sidebar -->


    @include('director.sidebar')
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


</body>

</html>
