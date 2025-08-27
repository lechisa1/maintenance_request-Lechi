@extends('admin.layout.app')

@section('content')
    <div class="container py-4 card bg-white mt-4">

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i> Organizations</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal"
                    data-bs-target="#organizationModal" onclick="openCreateModal()">
                    <i class="bi bi-plus-lg"></i> Add New
                </button>
            </div>

            <div class="card-body p-0">
                @if ($organizations->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No organizations found.
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($organizations as $organization)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-building text-primary me-2"></i> {{ $organization->name }}
                                </div>
                                <div>
                                    <button class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal"
                                        data-bs-target="#organizationModal"
                                        onclick="openEditModal({{ $organization->id }}, '{{ $organization->name }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>


                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteOrganizationModal"
                                        onclick="openDeleteModal({{ $organization->id }}, '{{ $organization->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>


    <!-- Reusable Modal -->
    <div class="modal fade" id="organizationModal" tabindex="-1" aria-labelledby="organizationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="organizationForm" method="POST">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="organizationModalLabel">
                            <i class="bi bi-plus-lg me-2"></i> Add Organization
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="orgName" class="form-label">Organization Name</label>
                            <input type="text" class="form-control" name="name" id="orgName" required>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="modalSubmitBtn">
                            <i class="bi bi-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteOrganizationModal" tabindex="-1" aria-labelledby="deleteOrganizationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteOrganizationForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteOrganizationModalLabel">
                            <i class="bi bi-trash me-2"></i> Delete Organization
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="deleteOrgName"></strong>?
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<script>
    function openCreateModal() {
        document.getElementById('organizationModalLabel').innerHTML =
            '<i class="bi bi-plus-lg me-2"></i> Add Organization';
        document.getElementById('organizationForm').action = "{{ route('organization.name.store') }}"; // POST route
        document.getElementById('method').value = "POST";
        document.getElementById('orgName').value = "";
        document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-save me-1"></i> Save';
    }

    function openEditModal(id, name) {
        document.getElementById('organizationModalLabel').innerHTML =
            '<i class="bi bi-pencil-square me-2"></i> Edit Organization';

        // Use Laravel route template with placeholder
        let updateRoute = "{{ route('organization.name.update', ':id') }}";
        updateRoute = updateRoute.replace(':id', id); // replace placeholder with actual id

        document.getElementById('organizationForm').action = updateRoute; // set form action to named route
        document.getElementById('method').value = "PUT"; // set PUT method
        document.getElementById('orgName').value = name; // set old value
        document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-save me-1"></i> Update';
    }

    function openDeleteModal(id, name) {
        // Set organization name in modal
        document.getElementById('deleteOrgName').innerText = name;

        // Set form action to the correct delete route
        let deleteRoute = "{{ route('organization.name.destroy', ':id') }}";
        deleteRoute = deleteRoute.replace(':id', id);
        document.getElementById('deleteOrganizationForm').action = deleteRoute;
    }
</script>
