<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login – Smart Inventory System</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #e0e1e2, #e5e7eb);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .header {
            width: 100%;
            height: 100px; /* Set a fixed height for the header */
            padding: 40px 20px;
            text-align: center;
            background: url('https://th.bing.com/th/id/OIP.4Y3TLWhFProqRzO1ed30ewHaDj?rs=1&pid=ImgDetMain&cb=idpwebpc2') no-repeat center center;
            background-size: cover;
            color: palevioletred;
            position: relative; /* Allow absolute positioning of content */
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header i {
            font-size: 60px;
            color: white;
        }

        .header h1 {
            font-weight: 800;
            font-size: 2.5rem;
            margin-top: 10px;
        }

        .header .login-label {
            font-size: 1.2rem;
            font-weight: 600;
            margin-left: 15px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Absolute positioning for content inside header */
        .header-content {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center content vertically and horizontally */
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            background: white;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #4e73df;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #4e73df;
        }
    </style>

</head>

<body>
    <div class="header">
        <div class="header-content">
            <i class="bi bi-box-seam-fill"></i>
            <h1 style="margin-bottom: 5px"><marquee>Maintenance Request System</marquee></h1>
            {{-- <div class="login-label">Login</div> --}}
        </div>
    </div>

    <div class="login-card">
        <div class="text-center mb-3">
            <img src="https://th.bing.com/th/id/OIP.fXgYUVPqjNT-LJDpgiKoVAHaHa?rs=1&pid=ImgDetMain" class="logo" alt="Logo">
            <h4 class="mt-3 text-primary">Maintenance Request System</h4>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember"> Remember Me </label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <div class="mt-3 text-center">
                <a href="#" class="text-decoration-none small">Forgot Password?</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
