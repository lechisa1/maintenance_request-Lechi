{{-- @extends('admin.layout.app') --}}
@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="card p-4">
        <h2>Category Issue</h2>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Maintenence Type</label>
                    <input type="text" name="name" class="form-control" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill"
                        onclick="this.innerHTML='Submitting...';">
                        <i class="bi bi-plus-circle me-1"></i> Add Category
                    </button>

                </div>
            </div>
        </form>
    </div>
@endsection
