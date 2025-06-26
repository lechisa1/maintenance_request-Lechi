@extends('admin.layout.app')
@section('content')
    <div class="card p-5 bg-light">
        <h4 class="mb-4 text-center text-primary">Add Users</h4>

        <form action="{{ route('save_users') }}" method="post">
            @csrf
            <div class="row g-4">

                <div class="col-md-4">
                    <label for="name" class="form-label ">User Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Enter user name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label ">Email </label>
                    <input type="text" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Enter email ">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone"
                        class="form-control @error('phone') is-invalid @enderror" placeholder="Enter user phone">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label ">Department</label>
                    <select name="department_id" id="department_id"
                        class="form-control @error('department_id') is-invalid @enderror">
                        <option value="" disabled selected>Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach

                    </select>

                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="specialization" class="form-label ">Specialization</label>
                    <input type="text" name="specialization" id="specialization"
                        class="form-control @error('specialization') is-invalid @enderror"
                        placeholder="Enter specialization (optional)">

                    @error('specialization')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label ">Password</label>
                    <input type="text" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Enter password ">

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="roles" class="form-label">Roles</label>
                    <select name="roles[]" id="roles" class="form-select @error('roles') is-invalid @enderror">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
