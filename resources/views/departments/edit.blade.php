@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0 rounded-4 mt-3">
            <div class="card-header bg-gray-900 text-primary rounded-top-4 ">
                <h4 class="mb-0 text-center fw-semibold">
                    <i class="bi bi-building-add me-2"></i> Edit Department
                </h4>
            </div>
            <div class="card-body px-4 py-5">
                <form action="{{ route('department_update', $department->id) }}" method="post">
                    @csrf
                       <div class="row g-4">

                        <div class="col-md-4">
                            <label for="name" class="form-label fw-bold">Department Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $department->name) }}"class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter department name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter description (optional)" rows="2">{{ old('description', $department->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="director_id">Department Director</label>
                            <select name="director_id" id="director_id" class="form-control">
                                <option value="">No Director</option>
                                @foreach ($potentialDirectors as $user)
                                    <option value="{{ $user->id }}" @selected(old('director_id', $department->director_id ?? '') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Submit Button -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="bi bi-plus-circle me-1"></i> Update Department
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
