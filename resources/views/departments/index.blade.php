@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-primary fw-bold mb-0">Organization Unit</h3>
                    <a href="{{ route('create_department') }}" class="btn btn-primary rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Organization Unit
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
                                <th scope="col" style="width: 5%;">#</th>
                                <th scope="col" class="text-center">Name</th>
                                {{-- <th scope="col" class="d-none d-md-table-cell text-center">Organization Unit Director
                                </th>
                                <th scope="col" class="d-none d-md-table-cell text-center">Description</th> --}}
                                <th scope="col" class="text-cnter">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $key => $department)
                                <tr>
                                    <td class="text-center fw-bold">{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $department->name }}</td>
                                    {{-- <td>{{ $department->director ? $department->director->name : 'N/A' }}</td>
                                    <td class="d-none d-md-table-cell text-muted">{{ $department->description ?? 'N/A' }}
                                    </td> --}}
<td class="text-center">
    <a href="{{ route('department_edit', $department->id) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil-square"></i>
    </a>

    <button type="button"
        class="btn btn-danger btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#deleteDepartmentModal"
        data-id="{{ $department->id }}"
        data-url="{{ route('delete_department', $department->id) }}">
        <i class="bi bi-trash"></i>
    </button>
</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-danger">
                                        <i class="bi bi-exclamation-circle"></i> No departments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDepartmentModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteDepartmentForm">
            @csrf
            @method('DELETE')
            <div class="modal-content shadow rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0">Are you sure you want to delete this department?</p>
                    <small class="text-muted">This action cannot be undone.</small>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

        </div>
        </div>
        @include('components.pagination', ['paginator' => $departments])
    </div>
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toLowerCase();
            let table = document.querySelector("#requestsTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let row = tr[i];
                let text = row.textContent.toLowerCase();

                if (text.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }

        $(document).ready(function() {
            $('#requestsTable').DataTable({
                responsive: true,
                columnDefs: [{
                        orderable: false,
                        targets: [0, 8]
                    },
                    {
                        className: "dt-center",
                        targets: [0, 5, 6, 7, 8]
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
@endsection
