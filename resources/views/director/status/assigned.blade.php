@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-primary my-3">Assigned Requests List</h4>

                    <!-- Search Form -->
                    <form method="GET" action="{{ url()->current() }}" class="d-flex">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" name="search" class="form-control rounded-3 shadow-sm"
                                placeholder=" Search requests..." value="{{ request('search') }}"
                                aria-label="Search maintenance requests">
                            <button type="submit" class="btn btn-primary rounded-3 ms-2">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            @if (request('search'))
                                <a href="{{ url()->current() }}" class="btn btn-outline-secondary rounded-pill ms-2">
                                    <i class="bi bi-x-lg me-1"></i> Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                @if (request('search'))
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Showing results for: <strong>"{{ request('search') }}"</strong>
                        <a href="{{ url()->current() }}" class="float-end text-decoration-none">
                            <small>Clear search</small>
                        </a>
                    </div>
                @endif

        
                <div class="table-responsive rounded-3 shadow-sm">
                    <table class="table table-hover align-middle mb-0" id="requestsTable">
                        <thead class="bg-blue text-white text-center"
                            style="background: linear-gradient(90deg, #0d6efd,  #11245A); position: sticky; top: 0; z-index: 1;">
                            <tr>

                                <th>#</th>
                                <th>Ticket ID</th>
                                {{-- <th>Item Name</th> --}}
                                <th>Requester</th>
                            <th>Job Position</th>
                                {{-- <th>Phone</th> --}}
                                <th>Priority</th>
                                {{-- <th>Requested At</th> --}}
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($AssignedRequests as $key => $request)
                                <tr class="text-center">

                                    <td class="text-center fw-bold">
                                        <button class="btn btn-sm "
                                            onclick="toggleDetails({{ $request->id }})">
                                            <i class="bi bi-plus-lg text-primary" id="icon-{{ $request->id }}"></i>
                                        </button>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        @if (request('search') && str_contains(strtolower($request->ticket_number), strtolower(request('search'))))
                                            {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark>$0</mark>', $request->ticket_number) !!}
                                        @else
                                            {{ $request->ticket_number }}
                                        @endif
                                    </td>

                                    <td>
                                        @if (request('search') && str_contains(strtolower($request->user->name), strtolower(request('search'))))
                                            {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark>$0</mark>', $request->user->name) !!}
                                        @else
                                            {{ $request->user->name }}
                                        @endif
                                        </td>
                                        <td>
                                            @if (request('search') &&
                                                    str_contains(strtolower($request->user->jobPosition->title ?? ''), strtolower(request('search'))))
                                                {!! preg_replace(
                                                    '/(' . preg_quote(request('search'), '/') . ')/i',
                                                    '<mark>$0</mark>',
                                                    $request->user->jobPosition->title ?? 'N/A',
                                                ) !!}
                                            @else
                                                {{ $request->user->jobPosition->title ?? 'N/A' }}
                                            @endif
                                        </td>
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
                                        <div class="row g-2 p-3 text-start" style="list-style: none;">
                                            {{-- <div class="col-md-4"><strong>Rejected By:</strong>
                                                    {{ $request->rejectedBy?->name ?? 'N/A' }}</div>
                                                <div class="col-md-4"><strong>Rejection Reason:</strong>
                                                    {{ $request->rejection_reason }}</div> --}}
                                            <div class="col-12 border-bottom py-1"><strong>Phone:</strong>
                                                {{ $request->user->phone }}
                                            </div>
                                            <div class="col-12 border-bottom py-1"><strong>Item:</strong>
                                                {{ $request->item?->name ?? 'N/A' }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Description:</strong>
                                                @if (request('search') && str_contains(strtolower($request->description), strtolower(request('search'))))
                                                    {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark>$0</mark>', $request->description) !!}
                                                @else
                                                    {{ $request->description }}
                                                @endif
                                            </div>
                                            <div class="col-12 border-bottom py-1"><strong>Issue:</strong>
                                                @if ($request->categories && $request->categories->count())
                                                    @foreach ($request->categories as $category)
                                                        <span class="badge bg-info text-dark">{{ $category->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-info text-dark">Uknown Cause</span>
                                                @endif
                                            </div>
                                            <div class="col-12 border-bottom py-1"><strong>Requested At:</strong>
                                                {{ $request->requested_at->format('M d, Y h:i A') }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>{{ $labels['department'] }}:</strong>
                                                @if (request('search') && str_contains(strtolower($request->user->department->name), strtolower(request('search'))))
                                                    {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark>$0</mark>', $request->user->department->name) !!}
                                                @else
                                                    {{ $request->user->department->name }}
                                                @endif
                                            </div>
                                            <div class="col-12 border-bottom py-1"><strong>{{ $labels['sector'] }}:</strong>
                                                {{ $request->user->sector->name ?? 'None' }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>{{ $labels['division'] }}:</strong>
                                                {{ $request->user->division->name ?? 'None' }}</div>
                                           
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

                    @include('components.pagination', ['paginator' => $AssignedRequests])
                </div>
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
