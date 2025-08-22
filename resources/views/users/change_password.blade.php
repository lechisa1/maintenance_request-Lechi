@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))


@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4 ">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i> Change Password</h4>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success rounded-pill">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('change_password') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control rounded-start-pill @error('current_password') is-invalid @enderror">
                                    <span class="input-group-text bg-white border rounded-end-pill"
                                        onclick="toggleVisibility('current_password')">
                                        <i class="bi bi-eye-slash" id="eye_current_password"></i>
                                    </span>
                                </div>
                                @error('current_password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="new_password" id="new_password"
                                        class="form-control rounded-start-pill @error('new_password') is-invalid @enderror"
                                        onkeyup="checkPasswordStrength(this.value)">
                                    <span class="input-group-text bg-white border rounded-end-pill"
                                        onclick="toggleVisibility('new_password')">
                                        <i class="bi bi-eye-slash" id="eye_new_password"></i>
                                    </span>
                                </div>
                                @error('new_password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
<ul id="password-requirements" class="list-unstyled small mt-2">
  <li><input type="checkbox" id="length" readonly> <label for="length">At least 8 characters</label></li>
  <li><input type="checkbox" id="uppercase" readonly> <label for="uppercase">At least one uppercase letter</label></li>
  <li><input type="checkbox" id="lowercase" readonly> <label for="lowercase">At least one lowercase letter</label></li>
  <li><input type="checkbox" id="number" readonly> <label for="number">At least one number</label></li>
  <li><input type="checkbox" id="special" readonly> <label for="special">At least one special character (@$!%*?&)</label></li>
</ul>




                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" name="new_password_confirmation" id="confirm_password"
                                        class="form-control rounded-start-pill">
                                    <span class="input-group-text bg-white border rounded-end-pill"
                                        onclick="toggleVisibility('confirm_password')">
                                        <i class="bi bi-eye-slash" id="eye_confirm_password"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-save me-1"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleVisibility(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById('eye_' + id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }

function checkPasswordStrength(password) {
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[@$!%*?&]/.test(password),
    };

    for (const key in requirements) {
        const checkbox = document.getElementById(key);
        checkbox.checked = requirements[key];
    }
}



    </script>
    
@endsection
