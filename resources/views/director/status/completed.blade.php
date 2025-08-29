@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
                <div class="">
                    <h4 class="m-3  text-primary">Completed Requests List</h4>
                    {{-- <a href="{{ route('requests.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add Request
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
                                <th>Ticket ID</th>
                                {{-- <th>Item Name</th> --}}
                                <th>Requester</th>
                                {{-- <th>Department</th>
                                    <th>Phone</th> --}}
                                <th>Priority</th>
                                {{-- <th>Requested At</th> --}}
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedRequests as $key => $request)
                                <tr class="text-center">
                                    {{-- <td>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="toggleDetails({{ $request->id }})">
                                                <i class="bi bi-plus-lg" id="icon-{{ $request->id }}"></i>
                                            </button>
                                        </td> --}}

                                    <td class="text-center fw-bold">
                                        <button class="btn btn-sm "
                                            onclick="toggleDetails({{ $request->id }})">
                                            <i class="bi bi-plus-lg text-primary" id="icon-{{ $request->id }}"></i>
                                        </button>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>{{ $request->ticket_number }}</td>
                                    {{-- <td>{{ $request->item ? $request->item->name : 'N/A' }}</td> --}}
                                    <td>{{ $request->user->name }}</td>
                                    {{-- <td>{{ $request->user->department->name }}</td>
                                        <td>{{ $request->user->phone }}</td> --}}
                                    <td>
                                        <span
                                            class="text 
                                                @if ($request->priority === 'high') text-danger 
                                                @elseif($request->priority === 'medium') text-warning 
                                                @else text-secondary @endif">
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
                                        <a href="{{ route('requests.show', $request->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="details-{{ $request->id }}" class="d-none bg-light">
                                    <td colspan="8">
                                        <div class="row g-2 p-3 text-start" style="list-style: none">
                                            {{-- <div class="col-md-4"><strong>Rejected By:</strong>
                                                    {{ $request->rejectedBy?->name ?? 'N/A' }}</div>
                                                <div class="col-md-4"><strong>Rejection Reason:</strong>
                                                    {{ $request->rejection_reason }}</div> --}}
                                            <div class="col-12 border-bottom py-1"><strong>Phone:</strong>
                                                {{ $request->user->phone }}
                                            </div>
                                            <div class="col-12 border-bottom py-1"><strong>Job Position:</strong>
                                                {{ $request->user->jobPosition->title ?? 'N/A' }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Item:</strong>
                                                {{ $request->item?->name ?? 'N/A' }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Requested At:</strong>
                                                {{ $request->requested_at->format('M d, Y h:i A') }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Requester
                                                    {{ $labels['department'] }}:</strong>
                                                {{ $request->user->department->name }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Issue:</strong>
                                                @if ($request->categories && $request->categories->count())
                                                    @foreach ($request->categories as $category)
                                                        <span class="text-dark">{{ $category->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-dark">Uknown Cause</span>
                                                @endif
                                            </div>
                                            <div class="col-12 border-bottom py-1"><strong>Assigned At:</strong>
                                                {{ $request->latestAssignment->assigned_at }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Assigned To:</strong>
                                                {{ $request->latestAssignment->technician->name }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-danger">
                                        <i class="bi bi-exclamation-circle"></i> No Requests Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @include('components.pagination', ['paginator' => $completedRequests])
                </div>
            </div>
        </div>
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
@endsection
{{-- @extends('director.layout.layout')

@section('content')
    <h3 class="mb-4 text-primary">Completed Tasks</h3>

    <div class="container">
        <div class="row">
            @forelse ($completedRequests as $request)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm rounded-3 border-0 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold text-dark">{{ $request->title }}</h5>
                            <p class="text-muted">{{ $request->description }}</p>
                            <p>Status: {{ $request->status }}</p>
                            <p class="mb-2">
                                Status: {{ $request->status }}
                            </p>
                            <p>User Feedback: {{ $request->user_feedback }}</p>
                            <p>Department: {{ $request->user->department->name }}</p>
                            <p>Requested at: {{ $request->requested_at }}</p>
                        
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No completed tasks yet.</p>
            @endforelse
        </div>
    </div>
@endsection --}}
