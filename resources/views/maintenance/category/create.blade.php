{{-- @extends('admin.layout.app') --}}
@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4 p-4 bg-white">
        <div class="card-header bg-white  rounded-top-4">
            <h4 class="mb-0 text-center fw-semibold">
                <i class="bi bi-issue me-2"></i> Add Issue Category
            </h4>
        </div>
<div class="card-body px-4 py-5">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold ">Issue Type</label>
                    <input type="text" name="name" class="form-control rounded-3 shadow-sm" placeholder="e.g Software Failure,..." required>
                    @error('name')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold ">Description</label>
                    <textarea name="description" rows="3" class="form-control rounded-3 shadow-sm" placeholder="Write a short description..."></textarea>
                    @error('description')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-4 rounded-2 shadow-sm"
                        onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm\'></span> Submitting...';">
                        <i class="bi bi-plus-circle me-1"></i> Add Category
                    </button>
                </div>
            </div>
        </form>
</div>
    </div>
</div>

@endsection
