@extends('admin.layout.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4 bg-white">
        <div class="card-body px-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary fw-bold mb-0"><span class="bi bi-people">Users List</span></h3>
                <a href="{{ route('create_users') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add User
                </a>
            </div>

            <div class="d-flex justify-content-end mb-3">
                <input type="text" class="form-control w-25 shadow-sm rounded-pill" id="searchInput" placeholder="🔍 Search..."
                    onkeyup="filterTable()">
            </div>

            <div class="table-responsive rounded-3 shadow-sm">
                <table class="table table-hover align-middle mb-0" id="requestsTable">
                    <thead class="bg-blue text-white text-center" style="background: linear-gradient(90deg, #0d6efd, #6610f2); position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Division</th>
                            <th>Phone</th>
                            <th>Supervisor</th>
                            <th>Role</th>
                            <th>Job Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                            <tr class="text-center">
                                <td class="fw-bold">{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department->name ?? 'N/A' }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->reportsTo->name ?? 'None' }}</td>
                                <td>{{ $user->roles->first()->name ?? 'No role' }}</td>
                                <td>{{ $user->jobPosition->title ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('edit_user', $user->id) }}" class="btn btn-sm btn-outline-warning rounded-circle"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-user-id="{{ $user->id }}"
                                            data-url="{{ route('delete_user', $user->id) }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-danger py-4">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content shadow rounded-4">
                    <div class="modal-header bg-danger text-white rounded-top-4">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-0">Are you sure you want to delete this user?</p>
                        <small class="text-muted">This action cannot be undone.</small>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Yes, Delete</button>
                    </div>
                </div>
            </form>
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
