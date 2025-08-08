<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login – Maintenance Request System</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/login.css') }}" rel="stylesheet">

</head>

<body class="vh-100 d-flex align-items-center justify-content-center bg-light border-2">
    <div class="container">
        <div class="row shadow rounded-3 overflow-hidden bg-white" style="max-width: 700px; margin: auto; min-height: 400px;">
            
            <!-- Left Section (Branding) -->
            <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center bg-purple  p-4 border-end">
                <img src="{{ asset('image/logo.png') }}" class="img-fluid mb-15" style="max-width: 120px;" alt="Logo">
                <h2 class="fw-bold">Maintenance Request System</h2>
             
            </div>

            <!-- Right Section (Login Form) -->
            <div class="col-md-6 p-4">
                <h4 class="text-center mb-0" style="color: var(--primary-color)"></h4>
                <p class="text-muted text-center">Please login to your account</p>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-envelope-fill text-muted"></i>
                            </span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock-fill text-muted"></i>
                            </span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required placeholder="Enter your password">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </div>

                    <div class="divider text-center my-3">
                        <span class="divider-text text-muted">or continue with</span>
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-google me-2"></i>Google
                        </button>
                    </div>
                </form>

                {{-- <div class="text-center mt-4">
                    <small class="text-muted">© {{ date('Y') }} Maintenance Request System. All rights reserved.</small>
                </div> --}}
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>


</html>