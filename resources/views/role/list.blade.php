{{-- @extends('admin.layout.app')

@section('content')
    <div class="container py-4 card bg-white mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Roles & Permissions</h2>
            <a href="{{ route('roles_create') }}" class="btn btn-success">Add New Role</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($roles as $role)
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>{{ ucfirst($role->name) }}</h5>

                    </div>
                    <div>
                        <a href="{{ route('edit_role', $role->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                            data-role-id="{{ $role->id }}" data-url="{{ route('delete_role', $role->id) }}">
                            Delete
                        </button>

                    </div>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Permissions</h6>
                    <div class="row">
                        @forelse($role->permissions as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="card bg-light border-0 shadow-sm p-2">
                                    <div class="card-body py-2 px-3">
                                        <span
                                            class="text-primary">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col">
                                <span class="text-muted">No permissions assigned.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No roles found.</div>
        @endforelse
    </div>


    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-url'); // Laravel route
            const form = document.getElementById('deleteRoleForm');
            form.action = url;
        });
    </script>

@endsection
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteRoleForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this role? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
</div> --}}

@extends('admin.layout.app')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-lg ">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h2 class="h5 mb-0 fw-bold text-dark">
                    <i class="bi bi-shield-lock me-2"></i>Roles & Permissions Management
                </h2>
                <a href="{{ route('roles_create') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Role
                </a>
            </div>

            <div class="card-body px-0 pb-0 bg-white">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3 rounded-3" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="accordion accordion-flush" id="rolesAccordion">
                    @forelse($roles as $index => $role)
                        <div class="accordion-item border-bottom">
                            <div
                                class="accordion-header d-flex justify-content-between align-items-center bg-light px-4 py-3">
                                <button class="accordion-button collapsed bg-transparent shadow-none" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#role-{{ $role->id }}"
                                    aria-expanded="false" aria-controls="role-{{ $role->id }}">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary rounded-pill me-3">{{ $index + 1 }}</span>
                                        <h5 class="mb-0 fw-semibold">{{ ucfirst($role->name) }}</h5>
                                    </div>
                                </button>
                                <div class="d-flex">
                                    <a href="{{ route('edit_role', $role->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill me-2" data-bs-toggle="tooltip"
                                        title="Edit Role">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
<button class="btn btn-sm btn-outline-danger rounded-pill" 
        data-bs-toggle="modal" 
        data-bs-target="#deleteModal"
        data-role-id="{{ $role->id }}" 
        title="Delete Role">
    <i class="bi bi-trash"></i>
</button>
                                </div>
                            </div>

                            <div id="role-{{ $role->id }}" class="accordion-collapse collapse"
                                data-bs-parent="#rolesAccordion">
                                <div class="accordion-body pt-3">
                                    <h6 class="text-muted mb-3 d-flex align-items-center text-center">
                                        <i class="bi bi-key me-2 text-center"></i>Assigned Permissions
                                    </h6>

                                    @if ($role->permissions->count() > 0)
                                        <div class="row g-3">
                                            @foreach ($role->permissions as $permission)
                                                <div class="col-md-4 col-6">
                                                    <div class="card border-0 shadow-sm h-100">
                                                        <div class="card-body py-2 px-3 d-flex align-items-center">
                                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                            <span class="text-truncate">
                                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-light border text-center py-4">
                                            <i class="bi bi-exclamation-circle text-warning me-2"></i>
                                            No permissions assigned to this role
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-shield-slash fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Roles Found</h5>
                            <p class="text-muted">Create your first role to get started</p>
                            <a href="{{ route('roles_create') }}" class="btn btn-primary rounded-pill mt-2">
                                <i class="bi bi-plus-circle me-1"></i> Create Role
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>





<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Delete modal handler
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const roleId = button.getAttribute('data-role-id');
                const form = document.getElementById('deleteRoleForm');
                if (form) {
                    // Correct way to set the route with parameter
                    form.action = "{{ route('delete_role', ':id') }}".replace(':id', roleId);
                    console.log('Delete form action set to:', form.action);
                }
            });
        }
    });
</script>
@endsection
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteRoleForm">
            @csrf
            @method('DELETE')
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="fs-1 text-danger mb-3">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                    <h5 class="mb-2">Are you sure?</h5>
                    <p class="text-muted">This will permanently delete the role and all its associations.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-trash-fill me-1"></i> Delete
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
