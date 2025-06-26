@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="card p-4">
        <h4 class="mb-4 text-center text-danger-emphasis">Edit Department</h4>
        <form action="{{ route('department_update', $department->id) }}" method="post">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="name" class="form-label fw-bold">Department Name</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $department->name) }}"class="form-control @error('name') is-invalid @enderror"
                        placeholder="Enter department name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                        placeholder="Enter description (optional)" rows="2">{{ old('description', $department->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Update Department
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
