
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
<!-- Add search form here -->
<div class="px-4 py-3 border-bottom bg-light">
    <form method="GET" action="{{ route('roles_with_permission') }}" class="row g-2 align-items-center">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       name="search" 
                       class="form-control border-start-0" 
                       placeholder="Search roles or permissions..." 
                       value="{{ request('search') }}"
                       aria-label="Search roles">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-1"></i> Search
            </button>
        </div>
        @if(request('search'))
        <div class="col-md-2">
            <a href="{{ route('roles_with_permission') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-x-lg me-1"></i> Clear
            </a>
        </div>
        @endif
    </form>
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
                                    <div class="d-flex align-items-center ">
                                        <span class="badge bg-primary rounded-pill me-3">{{ $index + 1 }}</span>
                                        <h5 class="mb-0 fw-semibold capitalize-text ">{{ ucfirst($role->name) }}</h5>
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
        @if(request('search'))
            <i class="bi bi-search fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No roles found matching your search</h5>
            <a href="{{ route('roles_with_permission') }}" class="btn btn-outline-primary rounded-pill mt-2">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Search
            </a>
        @else
            <i class="bi bi-shield-slash fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No Roles Found</h5>
            <p class="text-muted">Create your first role to get started</p>
            <a href="{{ route('roles_create') }}" class="btn btn-primary rounded-pill mt-2">
                <i class="bi bi-plus-circle me-1"></i> Create Role
            </a>
        @endif
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
<script>
  // Get all elements with the class 'capitalize-text'
  const elements = document.querySelectorAll('.capitalize-text');

  elements.forEach(element => {
    // Replace underscores with spaces and capitalize each word
    element.textContent = element.textContent
      .replace(/_/g, ' ')                // Replace underscores with spaces
      .replace(/\b\w/g, char => char.toUpperCase());  // Capitalize the first letter of each word
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
