@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="card shadow-sm p-4 rounded-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
            <h4 class="text-primary mb-0">✨ Rejected Maintenance Request Lists</h4>
            {{-- <a href="{{ route('requests.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Req
            </a> --}}
        </div>

        <div class="gap-3 mb-3"><input type="text" class="form-control" id="searchInput" placeholder="🔍 Search..."
                onkeyup="filterTable()" style="max-width: 250px;margin-left:70%">
        </div>


        <div class="table-responsive rounded-3 border border-2 p-2">
            <table class="table table-hover table-bordered table-striped align-middle text-center mb-0 shadow-sm">
                <thead class="table-primary">
                    <tr>

                        <th>#</th>
                        <th>Ticket Id</th>
                        <th>Requested By</th>
                        <th>Department</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>View</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pendingRequest as $key => $request)
                        <tr>

                            <td class="fw-bold">
                                <button class="btn btn-sm btn-outline-primary" onclick="toggleDetails({{ $request->id }})">
                                    <i class="bi bi-plus-lg" id="icon-{{ $request->id }}"></i>
                                </button>
                                {{ $key + 1 }}
                            </td>
                            <td>{{ $request->ticket_number }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->user->department->name }}</td>
                            <td>
                                <span
                                    class="badge 
                                @if ($request->priority === 'high') bg-danger 
                                @elseif($request->priority === 'medium') bg-warning text-dark 
                                @else bg-secondary @endif">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge 
                                @if ($request->status === 'pending') bg-warning text-dark 
                                @elseif ($request->status === 'in_progress') bg-primary 
                                @elseif ($request->status === 'completed') bg-success 
                                @elseif ($request->status === 'rejected') bg-danger 
                                @else bg-secondary @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> <span class="d-none d-md-inline">Details</span>
                                </a>
                            </td>
                        </tr>

                        <!-- Expanded details row (initially hidden) -->
                        <tr id="details-{{ $request->id }}" class="d-none bg-light">
                            <td colspan="8">
                                <div class="row g-2 p-3 text-start" style="list-style: none;">
                                    <div class="col-12 border-bottom py-1"><strong>Rejected By:</strong>
                                        {{ $request->rejectedBy?->name ?? 'N/A' }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Rejection Reason:</strong>
                                        {{ $request->rejection_reason }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Requester Phone:</strong>
                                        {{ $request->user->phone }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Item:</strong>
                                        {{ $request->item?->name ?? 'N/A' }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Requested At:</strong>
                                        {{ $request->requested_at->format('M d, Y h:i A') }}</div>
                                    <div class="col-12 py-1"><strong>Requester Department:</strong>
                                        {{ $request->user->department->name }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Issue:</strong>
                                @if ($request->categories && $request->categories->count())
                                    @foreach ($request->categories as $category)
                                        <span class="badge bg-info text-dark">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-info text-dark">Uknown Cause</span>
                                @endif
                                    </div>

                                </div>
                            </td>
                        </tr>


                        <!-- Reject Modal for each request -->
                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1"
                            aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('requests.reject', $request->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger" id="rejectModalLabel{{ $request->id }}">
                                                Reject Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="rejection_reason" class="form-label">Reason for
                                                    Rejection:</label>
                                                <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-danger">
                                <i class="bi bi-exclamation-circle"></i> No Maintenance found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('components.pagination', ['paginator' => $pendingRequest])
    </div>


    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toLowerCase();
            let table = document.querySelector(".table");
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
    </script>
    <script>
        function toggleDetails(id) {
            const detailsRow = document.getElementById('details-' + id);
            const icon = document.getElementById('icon-' + id);

            if (detailsRow.classList.contains('d-none')) {
                detailsRow.classList.remove('d-none'); // Show details row
                icon.classList.remove('bi-plus-lg'); // Switch icon to minus
                icon.classList.add('bi-dash-lg');
            } else {
                detailsRow.classList.add('d-none'); // Hide details row
                icon.classList.remove('bi-dash-lg'); // Switch icon back to plus
                icon.classList.add('bi-plus-lg');
            }
        }
    </script>

    <style>
        #searchInput {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #0d6efd;
            color: white;
            z-index: 2;
        }
    </style>
@endsection
