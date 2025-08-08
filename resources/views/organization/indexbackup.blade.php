best and ui standard for this
@section('content')

    <div class="container-fluid py-4 card bg-white mt-4">
        <!-- Header with Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">
                <i class="bi bi-diagram-3 me-2"></i> Organizational Structure
            </h2>
            <a href="{{ route('organization.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Add New Sector
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary bg-opacity-10 border-primary border-start border-4 rounded-end-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Total Sectors</h5>
                        <p class="display-6 fw-bold text-primary">{{ $sectors->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info bg-opacity-10 border-info border-start border-4 rounded-end-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Total Divisions</h5>
                        <p class="display-6 fw-bold text-info">{{ $totalDivisions }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10 border-success border-start border-4 rounded-end-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Total Departments</h5>
                        <p class="display-6 fw-bold text-success">{{ $totalDepartments }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sectors List -->
        <div class="row g-4">
            @foreach ($sectors as $sector)
                <div class="col-12">
                    <div class="card shadow-sm border-0 overflow-hidden">
                        <!-- Sector Header -->
                        <div class="card-header bg-primary text-white p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white text-primary rounded-circle p-3 me-3">
                                        <i class="bi bi-building fs-3"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0 fw-bold">{{ $sector->name }}</h3>
                                        <p class="mb-0 opacity-75">
                                            {{ $sector->divisions->count() }} Divisions •
                                            {{ $sector->departments->count() }} Direct Departments
                                        </p>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('organization.sector.edit', $sector) }}"
                                        class="btn btn-light btn-sm rounded-circle me-1" data-bs-toggle="tooltip"
                                        title="Edit Sector">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('organization.sector.destroy', $sector) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this sector and all its divisions/departments?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm rounded-circle"
                                            data-bs-toggle="tooltip" title="Delete Sector">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Direct Departments -->
                            @if ($sector->departments->count())
                                <div class="mb-4 ">
                                    <h5 class="d-flex align-items-center text-muted mb-3">
                                        <i class="bi bi-people-fill me-2"></i> Direct Departments
                                    </h5>
                                    <div class="row g-3">
                                        @foreach ($sector->departments as $department)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card border-start border-3 border-warning h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="fw-bold mb-1">{{ $department->name }}</h6>
                                                                <small class="text-muted">Direct to
                                                                    {{ $sector->name }}</small>
                                                            </div>
                                                            <div class="btn-group">
                                                                <a href="{{ route('organization.department.edit', $department) }}"
                                                                    class="btn btn-sm btn-outline-secondary rounded-circle me-1"
                                                                    data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                                <form
                                                                    action="{{ route('organization.department.destroy', $department) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Delete this department?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-secondary rounded-circle"
                                                                        data-bs-toggle="tooltip" title="Delete">
                                                                        <i class="bi bi-trash text-danger"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Divisions -->
                            @if ($sector->divisions->count())
                                <div class="mt-4">
                                    <h5 class="d-flex align-items-center text-muted mb-3">
                                        <i class="bi bi-diagram-3-fill me-2"></i> Divisions
                                    </h5>
                                    <div class="accordion" id="divisionsAccordion{{ $sector->id }}">
                                        @foreach ($sector->divisions as $division)
                                            <div class="accordion-item border-0 mb-3">
                                                <div class="accordion-header">
                                                    <button
                                                        class="accordion-button collapsed shadow-none rounded-3 bg-light"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#division{{ $division->id }}"
                                                        aria-expanded="false">
                                                        <div class="d-flex align-items-center w-100">
                                                            <div
                                                                class="bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                                                                <i class="bi bi-collection"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0 fw-bold">{{ $division->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $division->departments->count() }}
                                                                    Departments</small>
                                                            </div>
                                                            <div class="btn-group">
                                                                <a href="{{ route('organization.division.edit', $division) }}"
                                                                    class="btn btn-sm btn-outline-primary rounded-circle me-1"
                                                                    data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                                <form
                                                                    action="{{ route('organization.division.destroy', $division) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Delete this division and its departments?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-primary rounded-circle"
                                                                        data-bs-toggle="tooltip" title="Delete">
                                                                        <i class="bi bi-trash text-danger"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div id="division{{ $division->id }}" class="accordion-collapse collapse"
                                                    data-bs-parent="#divisionsAccordion{{ $sector->id }}">
                                                    <div class="accordion-body pt-3">
                                                        @if ($division->departments->count())
                                                            <div class="row g-3">
                                                                @foreach ($division->departments as $department)
                                                                    <div class="col-md-6 col-lg-4">
                                                                        <div
                                                                            class="card border-start border-3 border-success h-100">
                                                                            <div class="card-body">
                                                                                <div
                                                                                    class="d-flex justify-content-between align-items-start">
                                                                                    <div>
                                                                                        <h6 class="fw-bold mb-1">
                                                                                            {{ $department->name }}</h6>
                                                                                        <small class="text-muted">Under
                                                                                            {{ $division->name }}</small>
                                                                                    </div>
                                                                                    <div class="btn-group">
                                                                                        <a href="{{ route('organization.department.edit', $department) }}"
                                                                                            class="btn btn-sm btn-outline-secondary rounded-circle me-1"
                                                                                            data-bs-toggle="tooltip"
                                                                                            title="Edit">
                                                                                            <i class="bi bi-pencil"></i>
                                                                                        </a>
                                                                                        <form
                                                                                            action="{{ route('organization.department.destroy', $department) }}"
                                                                                            method="POST"
                                                                                            onsubmit="return confirm('Delete this department?');">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit"
                                                                                                class="btn btn-sm btn-outline-secondary rounded-circle"
                                                                                                data-bs-toggle="tooltip"
                                                                                                title="Delete">
                                                                                                <i
                                                                                                    class="bi bi-trash text-danger"></i>
                                                                                            </button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center py-4">
                                                                <i class="bi bi-inbox text-muted fs-1"></i>
                                                                <p class="text-muted mt-2">No departments in this division
                                                                </p>
                                                                <a href="{{ route('organization.create') }}?division_id={{ $division->id }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-plus-circle me-1"></i> Add Department
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5 bg-light rounded-3">
                                    <i class="bi bi-diagram-3 text-muted fs-1"></i>
                                    <h5 class="text-muted mt-3">No divisions in this sector</h5>
                                    <a href="{{ route('organization.create') }}?sector_id={{ $sector->id }}"
                                        class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Add Division
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    

    <!-- Add Floating Action Button -->
    <div class="position-fixed bottom-0 end-0 p-3"style="z-index: 1050;">
        <div class="btn-group dropup">
            <button type="button" class="btn btn-primary btn-lg rounded-circle shadow-lg p-3" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-plus fs-4"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-2">
                <li>
                    <a class="dropdown-item rounded-3 d-flex align-items-center py-2"
                        href="{{ route('organization.create') }}">
                        <i class="bi bi-building me-2"></i> Add New Sector
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-3 d-flex align-items-center py-2"
                        href="{{ route('organization.create') }}">
                        <i class="bi bi-collection me-2"></i> Add New Division
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-3 d-flex align-items-center py-2"
                        href="{{ route('organization.create') }}">
                        <i class="bi bi-people-fill me-2"></i> Add New Department
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
    <!-- Required CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        })
    </script>
@endsection