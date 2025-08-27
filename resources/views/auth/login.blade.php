<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MRS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

</head>

<body>
<div class="login-card">
    @if(session('success'))
        <div class="notification show" style="background: #4CC9F0; display: block;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="notification show" style="background: #F72585; display: block;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    <div class="card-header">
        <div class="logo-container">
            <img src="{{ asset('image/logo.png') }}" alt="Logo">
        </div>
        <h1 class="title">Maintenance Request System</h1>
        <p class="subtitle">Sign in to access your account</p>
    </div>

    {{-- Display session messages here --}}
   

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf
        <div class="form-group">
            <div class="input-with-icon">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
        </div>

        <div class="form-group">
            <div class="input-with-icon">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
        </div>

        <div class="remember-forgot">
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
        </div>

        <button type="submit" class="login-btn">Sign In</button>
    </form>
</div>


    <div class="notification" id="notification">
        <i class="fas fa-check-circle"></i> Login successful! Redirecting...
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggle = document.querySelector('.password-toggle');
            const passwordInput = document.getElementById('password');

            passwordToggle.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordToggle.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    passwordInput.type = 'password';
                    passwordToggle.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });

            // Form submission
            const loginForm = document.querySelector('.login-form');
            const notification = document.getElementById('notification');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Simple validation
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                if (!email || !password) {
                    showNotification('Please fill in all fields', 'error');
                    return;
                }

                if (!isValidEmail(email)) {
                    showNotification('Please enter a valid email address', 'error');
                    return;
                }

                // Show success notification
                showNotification('Login successful! Redirecting...', 'success');

                // Simulate redirect
                setTimeout(() => {
                    window.location.href = '#dashboard'; // Replace with actual URL
                }, 2000);
            });

            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function showNotification(message, type) {
                notification.innerHTML =
                    `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
                notification.style.background = type === 'success' ? '#4CC9F0' : '#F72585';

                notification.classList.add('show');

                setTimeout(() => {
                    notification.classList.remove('show');
                }, 3000);
            }

            // Add focus effects to form inputs
            const formControls = document.querySelectorAll('.form-control');

            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                control.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
</body>

</html>
