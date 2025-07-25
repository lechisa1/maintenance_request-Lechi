@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="card shadow-none border-0">
        <div class="d-flex justify-content-between align-items-center m-3">
            <h4 class="text-primary fw-bold mb-0">Maintenance Request List</h4>
            <a href="{{ route('requests.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Request
            </a>
        </div>
<div class="card border-0">
      <div class="gap-3 m-3"><input type="text" class="form-control" id="searchInput" placeholder="🔍 Search..."
                onkeyup="filterTable()" style="max-width: 250px;margin-left:70%">
        </div>
        <div class="table-responsive ">
            <table id="requestsTable" class="table table-borderless">
                <thead class="bg-blue">
                    <tr>
                        <th>#</th>
                        <th>Ticket ID</th>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Requester</th>
                        <th>Department</th>
                         <th>Phone</th>
                        <th>Priority</th>
                        <th>Requested At</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                        <th class="text-center">Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $key => $request)
                        <tr>
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td>{{ $request->ticket_number }}</td>
                              <td>{{ $request->item? $request->item->name :"N/A"}}</td>
                            <td class="text-truncate" style="max-width: 200px;">{{ $request->description }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->user->department->name }}</td>
                            <td>{{ $request->user->phone }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $request->priority === 'high' ? 'danger' : ($request->priority === 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>
                            <td>{{ $request->requested_at->format('Y-m-d') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'in_progress' ? 'primary' : ($request->status === 'completed' ? 'success' : 'secondary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td class="d-flex">
                                @if ($request->user_feedback === 'pending' && $request->status === 'pending')
                                    <a href="{{ route('requests.edit', $request->id) }}"
                                        class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $request->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal-{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">Confirm Delete</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this maintenance request? This cannot be
                                                    undone.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('requests.delete', $request->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                                                       <div class="btn btn-sm btn-outline-secondary disabled">N/A</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($request->status === 'completed' && $request->user_feedback === 'pending' && auth()->id() === $request->user_id)
                                    <form action="{{ route('requests.respond', $request) }}" method="POST">
                                        @csrf
                                        <button name="action" value="accept"
                                            class="btn btn-sm btn-success me-1">Accept</button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="document.getElementById('reject-form-{{ $request->id }}').style.display='block'">Reject</button>
                                    </form>
                                    <form id="reject-form-{{ $request->id }}"
                                        action="{{ route('requests.respond', $request) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <textarea name="rejection_reason" required class="form-control mt-2 mb-2" placeholder="Reason for rejection..."></textarea>
                                        <button type="submit" class="btn btn-warning btn-sm">Submit Rejection</button>
                                    </form>
                                @else
                                    <span
                                        class="badge bg-{{ $request->user_feedback === 'accepted' ? 'success' : 'secondary' }}">
                                        @if ($request->user_feedback === 'pending')
                                            <h5>No Feedback yet</h5>
                                        @else
                                            {{ ucfirst($request->user_feedback ?? 'Waiting') }}
                                        @endif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-exclamation-circle me-1"></i> No  Requests Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
        @include('components.pagination', ['paginator' => $maintenances])
    </>
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
