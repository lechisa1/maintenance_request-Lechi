@extends(
    Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' :
    (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' :
    (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' :
    'employeers.dashboard.layout'))
)

@section('content')
<div class="container py-4 ">
    <div class="card shadow-lg border-0 rounded-4 mt-3">
        <div class="card-header bg-gray-900 text-primary rounded-top-4">
            <h4 class="mb-0 text-center fw-semibold">
                <i class="bi bi-building-add me-2"></i> Add New Department
            </h4>
        </div>
        <div class="card-body px-4 py-5">
            <form action="{{ route('save_department') }}" method="POST" novalidate>
                @csrf
                <div class="row g-4">
                    <!-- Department Name -->
                    <div class="col-md-4">
                        <label for="name" class="form-label fw-semibold">Department Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter department name"
                            value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-md-4">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="2"
                            placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Director -->
                    <div class="col-md-4">
                        <label for="director_id" class="form-label fw-semibold">Department Director</label>
                        <select name="director_id" id="director_id"
                            class="form-select @error('director_id') is-invalid @enderror">
                            <option value="">No Director</option>
                            @foreach($potentialDirectors as $user)
                                <option value="{{ $user->id }}"
                                    @selected(old('director_id', $department->director_id ?? '') == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('director_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                            <i class="bi bi-check-circle me-2"></i> Save Department
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
