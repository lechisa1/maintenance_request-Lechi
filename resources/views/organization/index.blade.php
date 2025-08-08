@extends('admin.layout.app')

@section('content')
    <div class="container py-4 bg-white shadow-2xl mt-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold text-primary ">
                    <i class="bi bi-diagram-3-fill text-primary me-2"></i>Organizational Structure
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Organization</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('organization.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Add Structure
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card border-start border-primary border-3 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-bank text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Sectors</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ $sectors->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-info border-3 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-diagram-2 text-info fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Divisions</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ $totalDivisions }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-success border-3 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-diagram-3 me-2 text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Departments</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ $totalDepartments }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organization Structure -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success border-bottom py-3">
                <h5 class="mb-0 text-white text-center">
                    <i class="bi bi-diagram-3-fill text-white me-2 "></i>Structure Overview
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="accordion accordion-flush" id="structureAccordion">
                    @foreach ($sectors as $sIndex => $sector)
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="sector-{{ $sIndex }}">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#sector-collapse-{{ $sIndex }}"
                                    aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-3">
                                            <i class="">{{ $sIndex + 1 }}</i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $sector->name }}</h6>
                                            <small class="text-muted">
                                                {{ $sector->divisions->count() }} Divisions •
                                                {{-- {{ $sector->departments->count() }} Direct Departments --}}
                                            </small>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="sector-collapse-{{ $sIndex }}" class="accordion-collapse collapse"
                                aria-labelledby="sector-{{ $sIndex }}" data-bs-parent="#structureAccordion">
                                <div class="accordion-body pt-3">
                                    <div class="d-flex justify-content-end mb-3">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('organization.sector.edit', $sector->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square me-1"></i>Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-action="{{ route('organization.sector.destroy', $sector->id) }}"
                                                data-name="{{ $sector->name }}">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>

                                        </div>
                                    </div>


                                    <!-- Direct Departments -->
                                    @if ($sector->departments->count())
                                        <div class="mb-4">
                                            <h6 class="d-flex align-items-center text-muted mb-3">
                                                <i class="bi bi-people-fill text-secondary me-2"></i>Direct Departments
                                            </h6>
                                            <div class="row g-3">
                                                @foreach ($sector->departments as $department)
                                                    <div class="col-md-6">
                                                        <div
                                                            class="card border-start border-warning border-3 shadow-sm h-100">
                                                            <div class="card-body">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h6 class="fw-bold mb-1">{{ $department->name }}
                                                                        </h6>
                                                                        <small class="text-muted">Direct to
                                                                            {{ $sector->name }}</small>
                                                                    </div>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="{{ route('organization.department.edit', $department->id) }}"
                                                                            class="btn btn-sm btn-outline-secondary">
                                                                            <i class="bi bi-pencil-square"></i>
                                                                        </a>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-danger btn-delete"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#deleteModal"
                                                                            data-action="{{ route('organization.department.destroy', $department->id) }}"
                                                                            data-name="{{ $department->name }}">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>

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
                                            <h6 class="d-flex align-items-center text-muted mb-3">
                                                <i class="bi bi-diagram-3-fill text-info me-2"></i>Divisions
                                            </h6>
                                            <div class="accordion" id="divisionAccordion-{{ $sIndex }}">
                                                @foreach ($sector->divisions as $dIndex => $division)
                                                    <div class="accordion-item border-0 mb-3 shadow-sm">
                                                        <h2 class="accordion-header"
                                                            id="division-{{ $sIndex }}-{{ $dIndex }}">
                                                            <button class="accordion-button collapsed shadow-none"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#division-collapse-{{ $sIndex }}-{{ $dIndex }}"
                                                                aria-expanded="false">
                                                                <div class="d-flex align-items-center w-100">
                                                                    <div
                                                                        class="bg-info bg-opacity-10 text-info p-2 rounded-circle me-3">
                                                                        <i class="bi bi-collection"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">{{ $division->name }}</h6>
                                                                        <small
                                                                            class="text-muted">{{ $division->departments->count() }}
                                                                            Departments</small>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="division-collapse-{{ $sIndex }}-{{ $dIndex }}"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="division-{{ $sIndex }}-{{ $dIndex }}"
                                                            data-bs-parent="#divisionAccordion-{{ $sIndex }}">
                                                            <div class="accordion-body pt-3">
                                                                <div class="d-flex justify-content-end mb-3">
                                                                    <div class="d-flex gap-2">
                                                                        <a href="{{ route('organization.division.edit', $division->id) }}"
                                                                            class="btn btn-sm btn-outline-primary">
                                                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                                                        </a>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-danger btn-delete"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#deleteModal"
                                                                            data-action="{{ route('organization.division.destroy', $division->id) }}"
                                                                            data-name="{{ $sector->name }}">
                                                                            <i class="bi bi-trash me-1"></i>Delete
                                                                        </button>

                                                                    </div>

                                                                </div>

                                                                @if ($division->departments->count())
                                                                    <div class="row g-3">
                                                                        <h3 class="text-center muted">Departments</h3>
                                                                        @foreach ($division->departments as $department)
                                                                            <div class="col-md-6">
                                                                                <div
                                                                                    class="card border-start border-success border-3 shadow-sm h-100">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-start">
                                                                                            <div>
                                                                                                <h6 class="fw-bold mb-1">
                                                                                                    {{ $department->name }}
                                                                                                </h6>
                                                                                                <small
                                                                                                    class="text-muted">Under
                                                                                                    {{ $division->name }}</small>
                                                                                            </div>
                                                                                            <div class="btn-group">
                                                                                                <a href="{{ route('organization.department.edit', $department->id) }}"
                                                                                                    class="btn btn-sm btn-outline-secondary me-2">
                                                                                                    <!-- Added me-2 -->
                                                                                                    <i
                                                                                                        class="bi bi-pencil-square"></i>
                                                                                                </a>
                                                                                                <button type="button"
                                                                                                    class="btn btn-sm btn-outline-danger btn-delete"
                                                                                                    data-bs-toggle="modal"
                                                                                                    data-bs-target="#deleteModal"
                                                                                                    data-action="{{ route('organization.department.destroy', $department->id) }}"
                                                                                                    data-name="{{ $department->name }}">
                                                                                                    <i
                                                                                                        class="bi bi-trash"></i>
                                                                                                </button>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="text-center py-4 bg-light rounded">
                                                                        <i class="bi bi-inbox text-muted fs-1"></i>
                                                                        <p class="text-muted mt-2">No departments in this
                                                                            division</p>
                                                                        <a href="{{ route('organization.create') }}?division_id={{ $division->id }}"
                                                                            class="btn btn-sm btn-outline-primary">
                                                                            <i class="bi bi-plus-circle me-1"></i>Add
                                                                            Department
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
                                        <div class="text-center py-5 bg-light rounded">
                                            <i class="bi bi-diagram-3 text-muted fs-1"></i>
                                            <p class="text-muted mt-2">No divisions in this sector</p>
                                            <a href="{{ route('organization.create') }}?sector_id={{ $sector->id }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-plus-circle me-1"></i>Add Division
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="position-fixed bottom-3 end-3 z-3">
        <div class="dropup">
            <button class="btn btn-primary btn-lg rounded-circle shadow-lg p-3" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-plus-lg fs-4"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li>
                    <a class="dropdown-item rounded-3 d-flex align-items-center py-2"
                        href="{{ route('organization.create') }}">
                        <i class="bi bi-building me-2"></i>Add New Sector
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-3 d-flex align-items-center py-2"
                        href="{{ route('organization.create') }}">
                        <i class="bi bi-collection me-2"></i>Add New Division
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-3 d-flex align-items-center py-2"
                        href="{{ route('organization.create') }}">
                        <i class="bi bi-people-fill me-2"></i>Add New Department
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Delete Confirmation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteModalForm');
            const deleteItemName = document.getElementById('deleteItemName');

            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', () => {
                    const action = button.getAttribute('data-action');
                    const name = button.getAttribute('data-name');
                    deleteForm.setAttribute('action', action);
                    deleteItemName.textContent = name;
                });
            });

            // Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
@endsection
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form method="POST" id="deleteModalForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteItemName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
