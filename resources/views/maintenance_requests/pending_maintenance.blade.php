{{-- @extends('director.layout.layout') --}}
@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="card shadow-sm p-4 rounded-4">
        <div class="d-flex">
            <h4 class="mb-4 text-primary ">✨ Pending Maintenance Request List</h4>
            <a href="{{ route('requests.create') }}" class="btn btn-primary ms-auto">
                <!-- ms-auto to push the button to the right -->
                <i class="bi bi-plus-circle me-1"></i> Add Req
            </a>
        </div>
        <div class="mt-5">
            <input type="text" class="form-control" id="searchInput" placeholder="🔍 Search..." onkeyup="filterTable()"
                style="max-width: 250px; float: right;">
        </div>
        <div class="table-responsive rounded-3">
            <table class="table table-striped align-middle mb-0"id="requestsTable">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Ticket Id</th>
                        <th>Title</th>
                        <th>Item</th>
                        <th>Description</th>

                        <th>Requested By</th>
                        <th>Requested By Department</th>
                        <th>Phone</th>
                        <th>Priority</th>

                        <th>Requested At</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequest  as $key => $request)
                        <tr>
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td>{{ $request->ticket_number }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ $request->item ? $request->item : 'N/A' }}</td>
                            <td>{{ $request->description }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->user->department->name }}</td>
                            <td>{{ $request->user->phone }}</td>
                            {{-- <td>{{ $request->location }}</td> --}}
                            <td>{{ ucfirst($request->priority) }}</td>


                            <td>{{ $request->requested_at->format('Y-m-d') }}</td>
                            <td
                                class="
    text-white text-center 
                                    @if ($request->status === 'pending') bg-warning 
    @elseif ($request->status === 'in_progress') bg-primary 
    @elseif ($request->status === 'completed') bg-success 
    @else bg-secondary @endif">
                                {{ ucfirst($request->status) }}</td>
                            <td class="d-flex text-center">
                                @if ($request->status === 'pending')
                                    <a href="{{ route('requests.showAssignForm', $request->id) }}"
                                        class="btn btn-info me-2 ">
                                        <span class="fas fa-tasks">Assign</span>
                                    </a>
                                @else
                                    <button disabled><span class="gray "></span>Assign</button>
                                @endif
                                <form action="{{ route('requests.delete', $request->id) }}"
                                    method="POST"style="display:inline;" style="display:inline;" id="deleteForm">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <span class="bi bi-trash"></span>
                                    </button>

                                </form>
                            </td>
                            <td>
                                <a href="{{ route('requests.show', $request->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                                <!-- Reject Button trigger modal -->
                                @if ($request->status === 'pending')
                                    <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $request->id }}">
                                        Reject
                                    </button>
                                @endif

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">
                                <i class="bi bi-exclamation-circle"></i> No Maintenance found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1"
                aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('requests.reject', $request->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="rejectModalLabel{{ $request->id }}">Reject Request
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="rejection_reason" class="form-label">Reason for Rejection:</label>
                                    <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="deleteModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this maintenance request? This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <!-- Only show delete button if the logged-in user is the requester -->
                            @if (Auth::id() === $request->user_id)
                                <form action="{{ route('requests.delete', $request->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <span class="bi bi-trash"></span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


        </div>
        @include('components.pagination', ['paginator' => $pendingRequest])
    </div>


    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
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
