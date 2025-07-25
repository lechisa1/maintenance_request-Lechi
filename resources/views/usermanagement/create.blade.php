@extends('admin.layout.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-5 bg-white">

                <h3 class="text-center text-primary mb-4">
                    <i class="bi bi-person-plus-fill me-2"></i>Add New User
                </h3>
<hr/>
                <form action="{{ route('save_users') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        {{-- Name --}}
                        <div class="col-md-4">
                            <label for="name" class="form-label">Full Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g., John Doe" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email Address<span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="e.g., john@example.com" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="e.g., 0912345678" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-md-4">
                            <label for="department_id" class="form-label">Department<span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror">
                                <option value="" disabled selected>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Position --}}
                        <div class="col-md-4">
                            <label for="job_position_id" class="form-label">Job Position<span class="text-danger">*</span></label>
                            <select name="job_position_id" id="job_position_id"
                                    class="form-select @error('job_position_id') is-invalid @enderror">
                                <option value="" disabled selected>Select Job Position</option>
                                @foreach ($job_positions as $position)
                                    <option value="{{ $position->id }}" {{ old('job_position_id') == $position->id ? 'selected' : '' }}>
                                        {{ $position->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('job_position_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-md-4">
                            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Create a strong password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Supervisor --}}
                        <div class="col-md-4">
                            <label for="reports_to" class="form-label">Supervisor</label>
                            <select name="reports_to" id="reports_to"
                                    class="form-select @error('reports_to') is-invalid @enderror">
                                <option value="" disabled selected>Select Supervisor</option>
                                @foreach ($users as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('reports_to') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reports_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Roles --}}
                        <div class="col-md-4">
                            <label for="roles" class="form-label">Role<span class="text-danger">*</span></label>
                            <select name="roles" id="roles"
                                    class="form-select @error('roles') is-invalid @enderror">
                                <option value="" disabled selected>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('roles') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                                <i class="bi bi-save me-2"></i>Save User
                            </button>
                        </div>

                        {{-- Validation Summary --}}
                        @if ($errors->any())
                            <div class="col-12 mt-3">
                                <div class="alert alert-danger">
                                    Please fix the highlighted fields and try again.
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
