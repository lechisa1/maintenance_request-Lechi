@extends('admin.layout.app')

@section('content')
    <div class="container py-5 card shadow-2xl bg-white mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Sector</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('organization.sector.update', $sector) }}" method="POST"
                            class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <!-- Organization Select -->
                            <div class="mb-3">
                                <label for="organization_id" class="form-label">Organization</label>
                                <select name="organization_id" id="organization_id" class="form-control" required>
                                    <option value="">Select Organization</option>
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}"
                                            {{ old('organization_id', $sector->organization_id ?? '') == $organization->id ? 'selected' : '' }}>
                                            {{ $organization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Sector Name</label>
                                <input type="text" name="name" value="{{ old('name', $sector->name) }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('organization.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
