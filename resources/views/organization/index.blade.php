@extends('admin.layout.app')

@section('content')
    <div class="container py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold text-primary">
                    <i class="bi bi-diagram-3-fill text-primary me-2"></i>Organizational Structure
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Organization</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route ('organization.create') }}" class="btn btn-primary px-2 py-2">
                <i class="bi bi-plus-lg me-2"></i>Add Structure
            </a>
        </div>

        <!-- Stats Cards -->
        @php
            $stats = [
                ['label' => $labels['sector'], 'count' => $sectors->count(), 'icon' => 'bi-bank', 'color' => 'primary'],
                [
                    'label' => $labels['division'],
                    'count' => $totalDivisions,
                    'icon' => 'bi-diagram-2',
                    'color' => 'info',
                ],
                [
                    'label' => $labels['department'],
                    'count' => $totalDepartments,
                    'icon' => 'bi-diagram-3',
                    'color' => 'success',
                ],
            ];
        @endphp

        <div class="row mb-4 g-3">
            @foreach ($stats as $stat)
                <div class="col-md-4">
                    <div class="card border-start border-3 border-{{ $stat['color'] }} shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-{{ $stat['color'] }} bg-opacity-10 p-3 rounded me-3">
                                <i class="bi {{ $stat['icon'] }} text-{{ $stat['color'] }} fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total {{ $stat['label'] }}</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ $stat['count'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Search Form -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('organization.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Search sectors, divisions, or departments..." value="{{ $searchTerm ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>
                    @if (!empty($searchTerm))
                        <div class="col-md-2">
                            <a href="{{ route('organization.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-lg me-1"></i> Clear
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Organization Structure Accordion -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success border-bottom py-3 text-center text-white">
                <h5 class="mb-0">
                    <i class="bi bi-diagram-3-fill me-2"></i>Structure Overview
                </h5>
                @if (!empty($searchTerm))
                    <span class="badge bg-white text- mt-2 d-inline-block">
                        Search results for: "{{ $searchTerm }}"
                    </span>
                @endif
            </div>
            <div class="card-body p-0">
                @if ($sectors->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi {{ !empty($searchTerm) ? 'bi-search' : 'bi-diagram-3' }} fs-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">
                            {{ !empty($searchTerm) ? 'No matching results found' : 'No Organization Structure Found' }}
                        </h5>
                        <p class="text-muted">
                            {{ !empty($searchTerm)
                                ? "Your search for \"$searchTerm\" didn't match any sectors, divisions or departments"
                                : 'Please set up your organization structure' }}
                        </p>
                        <a href="{{ !empty($searchTerm) ? route('organization.index') : route('organization.create') }}"
                            class="btn {{ !empty($searchTerm) ? 'btn-outline-primary' : 'btn-primary' }}">
                            <i class="bi bi-{{ !empty($searchTerm) ? 'arrow-counterclockwise' : 'plus-lg' }} me-2"></i>
                            {{ !empty($searchTerm) ? 'Clear Search' : 'Add Structure' }}
                        </a>
                    </div>
                @else
                    <div class="accordion accordion-flush" id="structureAccordion">
                        @foreach ($sectors as $sIndex => $sector)
                            <div class="accordion-item border-bottom">
                                <h2 class="accordion-header" id="sector-{{ $sIndex }}">
                                    <button class="accordion-button collapsed fw-semibold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#sector-collapse-{{ $sIndex }}"
                                        aria-expanded="false">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-3">
                                                <i>{{ $sIndex + 1 }}</i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $sector->name }}</h6>
                                                <small class="text-muted">
                                                    Organization: {{ $sector->organization?->name ?? 'N/A' }} •
                                                    {{ $sector->divisions->count() }} {{ $labels['division'] }} •
                                                    {{ $sector->departments->where('division_id', null)->count() }} Direct
                                                    {{ $labels['department'] }}
                                                </small>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="sector-collapse-{{ $sIndex }}" class="accordion-collapse collapse"
                                    aria-labelledby="sector-{{ $sIndex }}" data-bs-parent="#structureAccordion">
                                    <div class="accordion-body pt-3">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <a href="{{ route('organization.sector.add-division', $sector->id) }}"
                                                    class="btn btn-sm btn-primary me-2">
                                                    <i class="bi bi-plus-circle me-1"></i>Add {{ $labels['division'] }}
                                                </a>
                                                <a href="{{ route('organization.sector.add-department', $sector->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>Add Direct
                                                    {{ $labels['department'] }}
                                                </a>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('organization.sector.edit', $sector->id) }}"
                                                    class="btn btn-sm btn-primary">
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
                                        @if ($sector->departments->where('division_id', null)->count())
                                            <div class="mb-4">
                                                <h6 class="d-flex align-items-center text-muted mb-3">
                                                    <i class="bi bi-people-fill text-secondary me-2"></i>Direct
                                                    {{ $labels['department'] }}
                                                </h6>
                                                <div class="row g-3">
                                                    @foreach ($sector->departments->where('division_id', null) as $department)
                                                        <div class="col-md-6">
                                                            <div
                                                                class="card border-start border-warning border-3 shadow-sm h-100">
                                                                <div
                                                                    class="card-body d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h6 class="fw-bold mb-1">{{ $department->name }}
                                                                        </h6>
                                                                        <small class="text-muted">Direct to
                                                                            {{ $sector->name }}</small>
                                                                    </div>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="{{ route('organization.department.edit', $department->id) }}"
                                                                            class="btn btn-sm btn-primary">
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
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Divisions -->
                                        @if ($sector->divisions->count())
                                            <div class="mt-4">
                                                <h6 class="d-flex align-items-center text-muted mb-3">
                                                    <i
                                                        class="bi bi-diagram-3-fill text-info me-2"></i>{{ $labels['division'] }}
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
                                                                                {{ $labels['department'] }}</small>
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </h2>
                                                            <div id="division-collapse-{{ $sIndex }}-{{ $dIndex }}"
                                                                class="accordion-collapse collapse"
                                                                aria-labelledby="division-{{ $sIndex }}-{{ $dIndex }}"
                                                                data-bs-parent="#divisionAccordion-{{ $sIndex }}">
                                                                <div class="accordion-body pt-3">
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <a href="{{ route('organization.division.add-department', $division->id) }}"
                                                                            class="btn btn-sm btn-primary">
                                                                            <i class="bi bi-plus-circle me-1"></i>Add
                                                                            {{ $labels['department'] }}
                                                                        </a>
                                                                        <div class="d-flex gap-2">
                                                                            <a href="{{ route('organization.division.edit', $division->id) }}"
                                                                                class="btn btn-sm btn-primary">
                                                                                <i
                                                                                    class="bi bi-pencil-square me-1"></i>Edit
                                                                            </a>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-outline-danger btn-delete"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#deleteModal"
                                                                                data-action="{{ route('organization.division.destroy', $division->id) }}"
                                                                                data-name="{{ $division->name }}">
                                                                                <i class="bi bi-trash me-1"></i>Delete
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    @if ($division->departments->count())
                                                                        <div class="row g-3">
                                                                            @foreach ($division->departments as $department)
                                                                                <div class="col-md-6">
                                                                                    <div
                                                                                        class="card border-start border-success border-3 shadow-sm h-100">
                                                                                        <div
                                                                                            class="card-body d-flex justify-content-between align-items-start">
                                                                                            <div>
                                                                                                <h6 class="fw-bold mb-1">
                                                                                                    {{ $department->name }}
                                                                                                </h6>
                                                                                                <small
                                                                                                    class="text-muted">Under
                                                                                                    {{ $division->name }}</small>
                                                                                            </div>
                                                                                            <div class="d-flex gap-2">
                                                                                                <a href="{{ route('organization.department.edit', $department->id) }}"
                                                                                                    class="btn btn-sm btn-primary">
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
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <div class="text-center py-4 bg-light rounded">
                                                                            <i class="bi bi-inbox text-muted fs-1"></i>
                                                                            <p class="text-muted mt-2">No
                                                                                {{ $labels['department'] }} in this
                                                                                division</p>
                                                                            <a href="{{ route('organization.division.add-department', $division->id) }}"
                                                                                class="btn btn-sm btn-primary">
                                                                                <i class="bi bi-plus-circle me-1"></i>Add
                                                                                {{ $labels['department'] }}
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
                                                <p class="text-muted mt-2">No {{ $labels['division'] }} in this sector</p>
                                                <a href="{{ route('organization.sector.add-division', $sector->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>Add {{ $labels['division'] }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="position-fixed bottom-5 end-3 z-10">
        <div class="dropup">
            <button class="btn btn-primary btn-lg rounded-circle shadow-lg p-3" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-plus-lg fs-4"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                @foreach (['sector', 'division', 'department'] as $type)
                    <li>
                        <a class="dropdown-item rounded-3 d-flex align-items-center py-2"
                            href="{{ route('organization.create') }}?type={{ $type }}">
                            <i
                                class="bi {{ $type === 'sector' ? 'bi-building' : ($type === 'division' ? 'bi-collection' : 'bi-people-fill') }} me-2"></i>
                            Add New {{ $labels[$type] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

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

    <!-- Scripts -->
    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                const deleteForm = document.getElementById('deleteModalForm');
                const deleteItemName = document.getElementById('deleteItemName');

                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', () => {
                        deleteForm.setAttribute('action', button.dataset.action);
                        deleteItemName.textContent = button.dataset.name;
                    });
                });

                // Bootstrap Tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
            });
        </script>
    

@endsection
