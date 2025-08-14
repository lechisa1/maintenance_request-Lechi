
@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
        <div >
            <h4 class="text-primary m-3 text-center">In Progress  Requests List</h4>
            {{-- <a href="{{ route('requests.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Req
            </a> --}}

        </div>
                <div class="gap-3 mb-3"><input type="text" class="form-control" id="searchInput" placeholder="🔍 Search..."
                        onkeyup="filterTable()" style="max-width: 250px;margin-left:70%">
                </div>

                <div class="table-responsive rounded-3 shadow-sm">
                    <table class="table table-hover align-middle mb-0" id="requestsTable">
                        <thead class="bg-blue text-white text-center"
                            style="background: linear-gradient(90deg, #0d6efd, #6610f2); position: sticky; top: 0; z-index: 1;">
                    <tr>

                        <th>#</th>
                        <th>Ticket Id</th>

                        {{-- <th>Item Name</th> --}}
                        <th>Requester</th>
                        {{-- <th> Department</th>
                        <th>Phone</th> --}}
                        <th>Priority</th>

                        {{-- <th>Requested At</th> --}}
                        <th>Status</th>

                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($InProgressRequests  as $key => $request)
                        <tr class="text-center">
                            {{-- <td class="text-center fw-bold">{{ $key + 1 }}</td> --}}

                            <td class="text-center fw-bold">
                                <button class="btn btn-sm " onclick="toggleDetails({{ $request->id }})">
                                    <i class="bi bi-plus-lg text-primary" id="icon-{{ $request->id }}"></i>
                                </button>
                                {{ $key + 1 }}
                            </td>
                            <td>{{ $request->ticket_number }}</td>

                            <td>{{ $request->user->name }}</td>
                            {{-- <td>{{ $request->item ? $request->item->name : 'N/A' }}</td>
                            <td>{{ $request->user->department->name }}</td>
                            <td>{{ $request->user->phone }}</td> --}}
                            {{-- <td>{{ $request->location }}</td> --}}
                            <td>
                                <span
                                    class="badge 
        @if ($request->priority === 'high') bg-danger 
        @elseif($request->priority === 'medium') bg-warning text-dark 
        @else bg-secondary @endif">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>


                            {{-- <td>{{ $request->requested_at->format('Y-m-d') }}</td> --}}
                            <td>
                                <span
                                    class="text 
        @if ($request->status === 'pending') text-warning 
        @elseif ($request->status === 'in_progress') text-primary 
        @elseif ($request->status === 'completed') text-success 
        @else text-secondary @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> <span class="d-none d-md-inline">Details</span>
                                </a>

                            </td>
                            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger" id="deleteModalLabel">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this maintenance request? This action cannot be
                                            undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
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
                        </tr>
                        <tr id="details-{{ $request->id }}" class="d-none bg-light">
                            <td colspan="8">
                                <div class="row g-2 p-3 text-start" style="list-style: none">
                                    {{-- <div class="col-md-4"><strong>Rejected By:</strong>
                                        {{ $request->rejectedBy?->name ?? 'N/A' }}</div> --}}
                                    <div class="col-12 border-bottom py-1"><strong>Description:</strong>
                                        {{ $request->description }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Phone:</strong>
                                        {{ $request->user->phone }}
                                    </div>
                                                                                    <div class="col-12 border-bottom py-1"><strong>Job Position:</strong>
                                                    {{ $request->user->jobPosition->title ?? 'N/A' }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Item:</strong>
                                        {{ $request->item?->name ?? 'N/A' }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Issue:</strong>
                                @if ($request->categories && $request->categories->count())
                                    @foreach ($request->categories as $category)
                                        <span class=" text-dark">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    <span class=" text-dark">Uknown Cause</span>
                                @endif
                                    </div>

                                    <div class="col-12 border-bottom py-1"><strong>Department:</strong>
                                        {{ $request->user->department->name }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Requested At:</strong>
                                        {{ $request->requested_at->format('M d, Y h:i A') }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Assigned At:</strong>
                                        {{ $request->latestAssignment->assigned_at }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Assigned To:</strong>
                                        {{ $request->latestAssignment->technician->name }}</div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">
                                <i class="bi bi-exclamation-circle"></i> No Request found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
        @include('components.pagination', ['paginator' => $InProgressRequests])
    </div>
        </div>
    </div>

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
