@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
                <h4 class=" text-center text-primary ">In Progress Task</h4>
                
                    <div class="d-flex justify-content-end mb-3">
                        <input type="text" class="form-control w-25 shadow-sm rounded-pill" id="searchInput"
                            placeholder="🔍 Search..." onkeyup="filterTable()">
                    </div>

                    <div class="table-responsive rounded-3 shadow-sm">
                        <table class="table table-hover align-middle mb-0" id="requestsTable">
                            <thead class="bg-blue text-white text-center"
                                style="background: linear-gradient(90deg, #0d6efd, #6610f2); position: sticky; top: 0; z-index: 1;">
                                <tr class="border-bottom">
                                    <th>#</th>
                                    <th>Ticket ID</th>
                                    {{-- <th>Title</th>
                            <th>Description</th> --}}
                                    <th>Requester</th>
                                    {{-- <th>Department</th> --}}
                                    <th>Priority</th>
                                    {{-- <th>Requested At</th> --}}
                                    <th>Status</th>
                                    <th class="">Actions</th>
                                    <th class="">Feedback</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $key => $request)
                                    <tr class="text-center">
                                        <td class="text-center fw-bold">
                                            <button class="btn btn-sm "
                                                onclick="toggleDetails({{ $request->id }})">
                                                <i class="bi bi-plus-lg text-primary" id="icon-{{ $request->id }}"></i>
                                            </button>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>{{ $request->maintenanceRequest->ticket_number }}</td>


                                        </td>

                                        <td>{{ $request->maintenanceRequest->user->name }}</td>

                                        <td>
                                            <span
                                                class="text-{{ $request->maintenanceRequest->priority === 'high' ? 'danger' : ($request->priority === 'medium' ? 'warning' : 'success') }}">
                                                {{ ucfirst($request->maintenanceRequest->priority) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span
                                                class="text-{{ $request->maintenanceRequest->status === 'pending' ? 'warning' : ($request->maintenanceRequest->status === 'in_progress' ? 'primary' : ($request->maintenanceRequest->status === 'completed' ? 'success' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $request->maintenanceRequest->status)) }}
                                            </span>
                                        </td>
                                        <td class="d-flex">
                                            @if ($request->maintenanceRequest->user_feedback === 'pending' && $request->maintenanceRequest->status === 'pending')
                                                <a href="{{ route('requests.edit', $request->maintenanceRequest->id) }}"
                                                    class="btn btn-sm btn-outline-warning me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $request->maintenanceRequest->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal-{{ $request->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-danger">Confirm Delete</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this maintenance request?
                                                                This
                                                                cannot be
                                                                undone.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('requests.delete', $request->id) }}"
                                                                    method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Yes,
                                                                        Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($request->maintenanceRequest->status === 'in_progress')
<a href="{{ route('tecknician_work_form', $request->maintenanceRequest->id) }}" class="btn btn-sm btn-outline-primary">
    <i class="bi bi-plus text-warning fs-5"></i> Progress
</a>
                                            @elseif ($request->maintenanceRequest->status === 'completed')
                                                <div class="btn btn-sm btn-outline-success disabled">Completed</div>            
                                            @elseif ($request->maintenanceRequest->status === 'rejected')
                                                <div class="btn btn-sm btn-outline-secondary disabled">Rejected</div>
                                            @else
                                                <div class="btn btn-sm btn-outline-secondary disabled">N/A</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (
                                                $request->maintenanceRequest->status === 'completed' &&
                                                    $request->maintenanceRequest->user_feedback === 'pending' &&
                                                    auth()->id() === $request->maintenanceRequest->user_id)
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
                                                    <button type="submit" class="btn btn-warning btn-sm">Submit
                                                        Rejection</button>
                                                </form>
                                            @else
                                                <span
                                                    class="badge bg-{{ $request->user_feedback === 'accepted' ? 'success' : ($request->user_feedback === 'pending' ? 'warning text-dark' : 'secondary') }}">
                                                    {{ $request->user_feedback === 'pending' ? 'Pending' : ucfirst($request->user_feedback ?? 'Waiting') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td><a href="{{ route('technician.show', $request->maintenanceRequest->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="details-{{ $request->id }}" class="d-none bg-light">
                                        <td colspan="8">
                                            <div class="row g-2 p-3 text-start" style="list-style: none;">

                                                {{-- <div class="col-md-4"><strong>Rejection Reason:</strong>
                                            {{ $request->rejection_reason }}</div> --}}
                                                <div class="col-12 border-bottom py-1"><strong>Requester Phone:</strong>
                                                    {{ $request->maintenanceRequest->user->phone }}
                                                </div>
                                                <div class="col-12 border-bottom py-1"><strong>Item:</strong>
                                                    {{ $request->maintenanceRequest->item->name }}</div>
                                                <div class="col-12 border-bottom py-1"><strong>Issues :</strong>
                                                    @foreach ($request->maintenanceRequest->categories as $categories)
                                                        {{ $categories->name }}
                                                    @endforeach
                                                </div>

                                                <div class="col-12 border-bottom py-1"><strong>Description:</strong>
                                                    {{ $request->maintenanceRequest->description }}</div>
                                                <div class="col-12 border-bottom py-1"><strong>Requested At:</strong>
                                                    {{ $request->maintenanceRequest->requested_at->format('M d, Y h:i A') }}
                                                </div>
                                                <div class="col-12 border-bottom py-1"><strong>Requester
                                                        Department:</strong>
                                                    {{ $request->maintenanceRequest->user->department->name }}</div>
                                                <div class="col-12 border-bottom py-1"><strong>Assigned At:</strong>
                                                    {{ $request->maintenanceRequest->latestAssignment->assigned_at->format('M d, Y h:i A') }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            <i class="bi bi-exclamation-circle me-1"></i> No Requests Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                
            </div>
        </div>
    </div>
        @include('components.pagination', ['paginator' => $requests])
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
