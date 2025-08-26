@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-primary my-3">Pending Requests List</h4>
                    
                    <!-- Search Form -->
                    <form method="GET" action="{{ url()->current() }}" class="d-flex">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" 
                                   name="search" 
                                   class="form-control rounded-3 shadow-sm" 
                                   placeholder=" Search requests..." 
                                   value="{{ request('search') }}"
                                   aria-label="Search maintenance requests">
                            <button type="submit" class="btn btn-primary rounded-3 ms-2">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            @if(request('search'))
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary rounded-2 ms-2">
                                <i class="bi bi-x-lg me-1"></i> Clear
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(request('search'))
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
                            style="background: linear-gradient(90deg, #0d6efd, #6610f2); position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>Ticket Id</th>
                                <th>Requested By</th>
                                <th>Job Position</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingRequest as $key => $request)
                                <tr class="text-center">
                                    <td class="text-center fw-bold">
                                        <button class="btn btn-sm" onclick="toggleDetails({{ $request->id }})">
                                            <i class="bi bi-plus-lg text-primary" id="icon-{{ $request->id }}"></i>
                                        </button>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        @if(request('search') && str_contains(strtolower($request->ticket_number), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->ticket_number) !!}
                                        @else
                                            {{ $request->ticket_number }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(request('search') && str_contains(strtolower($request->user->name), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->user->name) !!}
                                        @else
                                            {{ $request->user->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(request('search') && str_contains(strtolower($request->user->jobPosition->title ?? ''), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->user->jobPosition->title ?? 'N/A') !!}
                                        @else
                                            {{ $request->user->jobPosition->title ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text 
                                            @if ($request->priority === 'high') text-danger 
                                            @elseif($request->priority === 'medium') text-warning 
                                            @else text-secondary @endif">
                                            {{ ucfirst($request->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text 
                                            @if ($request->status === 'pending') text-warning
                                            @elseif ($request->status === 'in_progress') text-primary 
                                            @elseif ($request->status === 'completed') text-success 
                                            @else text-secondary @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="d-flex justify-content-center gap-3">
                                        <!-- Your existing action buttons -->
                                        @if ($request->status === 'pending')
                                            @if (Auth::user()->roles->first()->name === 'admin' || Auth::user()->roles->first()->name === 'Ict_director')
                                                <a href="{{ route('requests.showAssignForm', $request->id) }}" class="btn btn-primary">
                                                    <span class="bi bi-person">Assign</span>
                                                </a>
                                            @endif

                                            @if (Auth::id() === $request->user_id)
                                                <button class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $request->id }}">
                                                    <span class="bi bi-trash"></span>
                                                </button>
                                            @endif

                                            <button type="button" class="btn btn-outline-danger me-2"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                Reject
                                            </button>
                                        @elseif($request->status === 'not_fixed')
                                            @if (Auth::user()->roles->first()->name === 'admin' || Auth::user()->roles->first()->name === 'Ict_director')
                                                <a href="{{ route('requests.showAssignForm', $request->id) }}" class="btn btn-primary">
                                                    <span class="bi bi-re">ReAssign</span>
                                                </a>
                                            @endif
                                        @else
                                            <button disabled class="btn btn-secondary me-2">
                                                <span class="fas fa-tasks">Assign</span>
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> <span class="d-none d-md-inline">Details</span>
                                        </a>
                                    </td>
                                </tr>
                                
                                <!-- Expanded details row -->
                                <tr id="details-{{ $request->id }}" class="d-none bg-light">
                                    <td colspan="8">
                                        <div class="row g-2 p-3 text-start" style="list-style: none">
                                            <div class="col-12 border-bottom py-1"><strong>Phone:</strong> {{ $request->user->phone }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Job Position:</strong> {{ $request->user->job_position }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Item:</strong> {{ $request->item?->name ?? 'N/A' }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Description:</strong> 
                                                @if(request('search') && str_contains(strtolower($request->description), strtolower(request('search'))))
                                                    {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->description) !!}
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
                                            <div class="col-12 border-bottom py-1"><strong>Requested At:</strong> {{ $request->requested_at->format('M d, Y h:i A') }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Requester Department:</strong> {{ $request->user->department->name }}</div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal for each request -->
                                <div class="modal fade" id="deleteModal-{{ $request->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                                                <form action="{{ route('requests.delete', $request->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-danger py-4">
                                        <i class="bi bi-exclamation-circle"></i> 
                                        @if(request('search'))
                                            No requests found matching your search
                                        @else
                                            No pending requests found
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @include('components.pagination', ['paginator' => $pendingRequest])
            </div>
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const detailsRow = document.getElementById('details-' + id);
            const icon = document.getElementById('icon-' + id);

            if (detailsRow.classList.contains('d-none')) {
                detailsRow.classList.remove('d-none');
                icon.classList.remove('bi-plus-lg');
                icon.classList.add('bi-dash-lg');
            } else {
                detailsRow.classList.add('d-none');
                icon.classList.remove('bi-dash-lg');
                icon.classList.add('bi-plus-lg');
            }
        }
    </script>

    <style>
        thead th {
            position: sticky;
            top: 0;
            background-color: #0d6efd;
            color: white;
            z-index: 2;
        }
        
        .bg-blue {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
        }
    </style>
@endsection