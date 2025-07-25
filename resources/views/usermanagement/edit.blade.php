@extends('admin.layout.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body px-5 py-4 bg-white">
            <h3 class="text-center text-primary fw-bold mb-4">
                <i class="bi bi-person-lines-fill me-2"></i>Edit User Profile
            </h3>
<hr/>
            <form action="{{ route('update_user', $user->id) }}" method="POST">
                @csrf
                <div class="row g-4">

                    {{-- User Name --}}
                    <div class="col-md-4">
                        <label for="name" class="form-label fw-semibold">User Name</label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter full name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-4">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', $user->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-4">
                        <label for="phone" class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" id="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="Enter phone number">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div class="col-md-4">
                        <label for="department_id" class="form-label fw-semibold">Division</label>
                        <select name="department_id" id="department_id"
                            class="form-select @error('department_id') is-invalid @enderror">
                            <option value="" disabled selected>Select Division</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Supervisor --}}
                    <div class="col-md-4">
                        <label for="reports_to" class="form-label fw-semibold">Supervisor</label>
                        <select name="reports_to" id="reports_to"
                            class="form-select @error('reports_to') is-invalid @enderror">
                            <option value="">Select Supervisor</option>
                            @foreach ($users as $supervisor)
                                <option value="{{ $supervisor->id }}"
                                    {{ old('reports_to', $user->reports_to ?? '') == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('reports_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="col-md-4">
                        <label for="roles" class="form-label fw-semibold">Role</label>
                        <select name="roles" id="roles"
                            class="form-select @error('roles') is-invalid @enderror">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ old('roles', optional($user->roles->first())->name) == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Job Position --}}
                    <div class="col-md-4">
                        <label for="job_position_id" class="form-label fw-semibold">Job Position</label>
                        <select name="job_position_id" id="job_position_id"
                            class="form-select @error('job_position_id') is-invalid @enderror">
                            <option value="" disabled {{ old('job_position_id', $user->job_position_id ?? '') === null ? 'selected' : '' }}>
                                Select Job Position
                            </option>
                            @foreach ($job_positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ old('job_position_id', $user->job_position_id ?? '') == $position->id ? 'selected' : '' }}>
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
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" id="password"
                            class="form-control" placeholder="Leave blank to keep current password">
                    </div>

                    {{-- Submit Button --}}
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Update User
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
