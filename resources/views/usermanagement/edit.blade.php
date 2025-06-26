@extends('admin.layout.app')
@section('content')
    <div class="card p-5 bg-light">
        <h4 class="mb-4 text-center text-primary">Edit Users</h4>

        <form action="{{ route('update_user', $user->id) }}" method="post">
            @csrf
            <div class="row g-4">

                <div class="col-md-4">
                    <label for="name" class="form-label fw-bold">User Name</label>
                    <input type="text" name="name" id="name"value="{{ old('name', $user->name) }}"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Enter user name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label fw-bold">Email </label>
                    <input type="text" name="email" id="email"value="{{ old('email', $user->email) }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Enter email ">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label fw-bold">Phone</label>
                    <input type="text" name="phone" id="phone"value="{{ old('phone', $user->phone) }}"
                        class="form-control @error('phone') is-invalid @enderror" placeholder="Enter user phone">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label fw-bold">Department</label>
                    <select name="department_id" id="department_id"value="{{ old('department_id', $user->department) }}"
                        class="form-control @error('department_id') is-invalid @enderror">
                        <option value="" disabled selected>Select Department</option>
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
                <div class="col-md-4">
                    <label for="roles" class="form-label fw-bold">Role</label>
                    <select name="roles[]" id="roles" class="form-control @error('roles') is-invalid @enderror">
                        <option value="" disabled>Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="specialization" class="form-label fw-bold">Specialization</label>
                    <input type="text" name="specialization"
                        id="specialization"value="{{ old('specialization', $user->specialization) }}"
                        class="form-control @error('specialization') is-invalid @enderror"
                        placeholder="Enter specialization (optional)">

                    @error('specialization')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="text" name="password" id="password" class="form-control" placeholder="Enter password ">

                    {{-- @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror --}}
                </div>
                <!-- Submit Button -->
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill"
                        onclick="this.innerHTML='Submitting...';">
                        <i class="bi bi-plus-circle me-1"></i> Add User
                    </button>

                </div>
            </div>
        </form>
    </div>
@endsection
