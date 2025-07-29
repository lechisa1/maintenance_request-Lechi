@extends('admin.layout.app')

@section('content')
<div class="container py-4 card bg-white mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Roles & Permissions</h2>
        <a href="{{ route('roles_create') }}" class="btn btn-success">Add New Role</a>
    </div>

    @if(session('success'))
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
                  <button 
    class="btn btn-sm btn-danger" 
    data-bs-toggle="modal" 
    data-bs-target="#deleteModal"
    data-role-id="{{ $role->id }}"
    data-url="{{ route('delete_role', $role->id) }}"
>
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
                                    <span class="text-primary">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</span>
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
</div>

<script>
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-url'); // Laravel route
        const form = document.getElementById('deleteRoleForm');
        form.action = url;
    });
</script>

@endsection
