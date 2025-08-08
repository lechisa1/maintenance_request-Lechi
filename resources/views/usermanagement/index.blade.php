@extends('admin.layout.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-primary fw-bold mb-0"><span class="bi bi-people">Users List</span></h3>
                    <a href="{{ route('create_users') }}" class="btn btn-primary rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add New User
                    </a>
                </div>

                <div class="d-flex justify-content-end mb-3">
                    <input type="text" class="form-control w-25 shadow-sm rounded-pill" id="searchInput"
                        placeholder="🔍 Search..." onkeyup="filterTable()">
                </div>

                <div class="table-responsive rounded-3 shadow-sm">
                    <table class="table table-hover align-middle mb-0" id="requestsTable">
                        <thead class="bg-blue text-white text-center"
                            style="background: linear-gradient(90deg, #0d6efd, #6610f2); position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>More</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $key => $user)
                                <tr class="text-center">
                                    <td class="fw-bold">{{ $key + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->first()->name ?? 'No role' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border shadow-sm rounded-circle"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                title="View More Details">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg rounded-4"
                                                style="min-width: 260px;">
                                                <h6 class="dropdown-header text-primary">User Details</h6>
                                                <div class="dropdown-item-text mb-2">
                                                    <div class="small text-muted">Phone</div>
                                                    <div class="fw-semibold">{{ $user->phone }}</div>
                                                </div>

                                                <div class="dropdown-item-text mb-2">
                                                    <div class="small text-muted">Department</div>
                                                    <div class="fw-semibold">{{ $user->department->name ?? 'N/A' }}</div>
                                                </div>
                                                <div class="dropdown-item-text mb-2">
                                                    <div class="small text-muted">Sector</div>
                                                    <div class="fw-semibold">{{ $user->sector->name ?? 'None' }}</div>
                                                </div>
                                                <div class="dropdown-item-text mb-2">
                                                    <div class="small text-muted">Division</div>
                                                    <div class="fw-semibold">{{ $user->division->name ?? 'None' }}</div>
                                                </div>
                                                <div class="dropdown-item-text">
                                                    <div class="small text-muted">Job Position</div>
                                                    <div class="fw-semibold">{{ $user->jobPosition->title ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('edit_user', $user->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded-circle"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
<button class="btn btn-sm btn-outline-danger rounded-circle"
        data-bs-toggle="modal" data-bs-target="#deleteModal"
        data-user-id="{{ $user->id }}"
        data-url="{{ route('delete_user', $user->id) }}" title="Delete">
    <i class="bi bi-trash"></i>
</button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger py-4">
                                        <i class="bi bi-exclamation-circle"></i> No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="mt-4">
                    @include('components.pagination', ['paginator' => $users])
                </div>
            </div>
        </div>


    </div>


    <script>
        function filterTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("#requestsTable tbody tr");

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const form = document.getElementById('deleteUserForm');
                const userUrl = button.getAttribute('data-url');
                form.action = userUrl; // Adjust if your route is different
            });
        });
    </script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
@endsection
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form method="POST" id="deleteUserForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

