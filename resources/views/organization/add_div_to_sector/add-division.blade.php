@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Add Division to {{ $sector->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('organization.sector.store-division', $sector->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Division Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <input type="hidden" name="sector_id" value="{{ $sector->id }}">
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('organization.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Division</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection