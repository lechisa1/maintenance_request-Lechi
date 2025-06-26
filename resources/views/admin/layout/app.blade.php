{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    @include('admin.headers')

    <div class="d-flex" style="height: 100vh;">
        @include('admin.sidebar')

        <main class="content flex-grow-2 p-5 mt-5">
            @yield('content')
        </main>
    </div>

    @include('admin.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<style>
    main {
        margin-top: 20%;

        /* same as header height */
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar .toggle-sidebar {
        z-index: 1000;
        /* Ensure it stays on top */
    }

    .sidebar ul.nav {
        padding-left: 10px;
    }

    .sidebar ul.nav {
        padding-left: 10px;
    }

    button.btn img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        /* Ensures the image doesn't distort */
    }

    button.btn span {
        font-size: 14px;
        /* Adjust font size if needed */
        color: #fff;
        /* Ensures text matches the header theme */
    }

    .sidebar ul li ul.list-unstyled {
        padding-left: 15px;
        /* Indent submenus */
    }

    .sidebar .collapse.show {
        display: block !important;
    }

    .sidebar .dropdown-toggle::after {
        content: ' ▼';
        float: right;
    }

    .sidebar.collapsed .collapse {
        display: none !important;
    }

    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 10px;
       
    }

    .sidebar {
        margin-top: 4%;
        width: 250px;
        transition: width 0.3s ease-in-out;
        height: 100vh;
        overflow-y: auto;
        max-height: 100vh;
        background-color: #f8f9fa;
    }
</style>

</html> --}}
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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

</head>

<body>
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


</body>

</html>
