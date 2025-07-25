@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-lg">
                <!-- Card Header -->
                <div class="card-header bg-success text-white py-3">
                    <h3 class="mb-0 text-center">
                        <i class="bi bi-person-badge me-2"></i>Create New Role
                    </h3>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('roles_store') }}" method="POST">
                        @csrf

                        <!-- Role Name Input -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="bi bi-tag-fill text-primary me-2"></i>Role Name
                            </label>
                            <input type="text" name="name" class="form-control form-control-lg" 
                                   placeholder="e.g. System Admin" required>
                            <small class="text-muted">Enter a descriptive name </small>
                        </div>

                        <!-- Permissions Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-shield-lock text-primary me-2"></i>Assign Permissions
                                </h5>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="selectAll" style="width: 3em; height: 1.5em;">
                                    <label class="form-check-label fw-bold" for="selectAll">Select All</label>
                                </div>
                            </div>
                            
                            <div class="border rounded p-3 bg-light">
                                <div class="row">
                                    @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                                    <div class="col-md-4">
                                        @foreach($chunk as $permission)
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="permissions[]" 
                                                   value="{{ $permission->name }}" 
                                                   class="form-check-input permission-checkbox" 
                                                   id="perm_{{ $permission->id }}">
                                            <label class="form-check-label d-flex align-items-center" for="perm_{{ $permission->id }}">
                                                <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                                                    <i class="bi bi-shield-check"></i>
                                                </span>
                                                {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-end mt-2">
                                <small class="text-muted" id="selectedCount">0 of {{ $permissions->count() }} permissions selected</small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary me-md-2 px-4">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Create Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .form-check-label {
        transition: all 0.2s ease;
    }
    .form-check-input:checked + .form-check-label {
        font-weight: 500;
        color: #0d6efd;
    }
    .badge {
        padding: 0.35em 0.5em;
        font-size: 0.75em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const selectedCount = document.getElementById('selectedCount');
        const totalPermissions = {{ $permissions->count() }};
        
        if (selectAll && checkboxes.length > 0) {
            // Select All functionality
            selectAll.addEventListener('change', function() {
                const isChecked = this.checked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateSelectedCount();
            });
            
            // Individual checkbox functionality
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedCount();
                    // Update select all state
                    const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                    selectAll.checked = checkedCount === checkboxes.length;
                    selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
                });
            });
            
            // Update selected count display
            function updateSelectedCount() {
                const checked = document.querySelectorAll('.permission-checkbox:checked').length;
                selectedCount.textContent = `${checked} of ${totalPermissions} permissions selected`;
            }
            
            // Initialize count
            updateSelectedCount();
        }
    });
</script>
@endsection