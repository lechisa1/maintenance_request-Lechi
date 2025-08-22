{{-- @extends('director.layout.layout') --}}
@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-primary fw-bold mb-0">Pending Maintenance Request List</h3>
                    <a href="{{ route('requests.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Maintenance Request
                    </a>
                </div>

                <!-- Updated Search Form -->
                <form method="GET" action="{{ url()->current() }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control rounded-pill shadow-sm" 
                               placeholder="🔍 Search by ticket, item, description..." 
                               value="{{ request('search') }}"
                               aria-label="Search maintenance requests">
                        <button type="submit" class="btn btn-primary rounded-pill ms-2">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                        @if(request('search'))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary rounded-pill ms-2">
                            <i class="bi bi-x-lg me-1"></i> Clear
                        </a>
                        @endif
                    </div>
                </form>

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
                            @forelse($pendingRequest as $key => $request)
                                <tr class="text-center">
                                    <td class="text-center fw-bold">{{ $key + 1 }}</td>
                                    <td>
                                        @if(request('search') && str_contains(strtolower($request->ticket_number), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->ticket_number) !!}
                                        @else
                                            {{ $request->ticket_number }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(request('search') && str_contains(strtolower($request->title), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->title) !!}
                                        @else
                                            {{ $request->title }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(request('search') && $request->item && str_contains(strtolower($request->item), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->item) !!}
                                        @else
                                            {{ $request->item ? $request->item : 'N/A' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(request('search') && str_contains(strtolower($request->description), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', Str::limit($request->description, 50)) !!}
                                        @else
                                            {{ Str::limit($request->description, 50) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(request('search') && str_contains(strtolower($request->user->name), strtolower(request('search'))))
                                            {!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark>$0</mark>', $request->user->name) !!}
                                        @else
                                            {{ $request->user->name }}
                                        @endif
                                    </td>
                                    <td>{{ $request->user->department->name }}</td>
                                    <td>{{ $request->user->phone }}</td>
                                    <td>{{ ucfirst($request->priority) }}</td>
                                    <td>{{ $request->requested_at->format('Y-m-d') }}</td>
                                    <td class="text-white text-center 
                                        @if ($request->status === 'pending') bg-warning 
                                        @elseif ($request->status === 'in_progress') bg-primary 
                                        @elseif ($request->status === 'completed') bg-success 
                                        @else bg-secondary @endif">
                                        {{ ucfirst($request->status) }}
                                    </td>
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
                                        <a href="{{ route('requests.show', $request->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                        @if ($request->status === 'pending')
                                            <button type="button" class="btn btn-outline-danger me-2"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                Reject
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-danger py-4">
                                        <i class="bi bi-exclamation-circle"></i> 
                                        @if(request('search'))
                                            No maintenance requests found matching your search
                                        @else
                                            No maintenance requests found
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

    <!-- Reject Modal -->
    @isset($request)
    <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1"
        aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('requests.reject', $request->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="rejectModalLabel{{ $request->id }}">Reject Request</h5>
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
    @endisset

    <!-- Delete Modal -->
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
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize delete modal with correct form action
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const form = document.getElementById('deleteForm');
                    if (form) {
                        const closestForm = button.closest('form');
                        if (closestForm) {
                            form.action = closestForm.action;
                        }
                    }
                });
            }
            
            // Remove the old filterTable function since we're using backend search now
            if (typeof filterTable !== 'undefined') {
                delete window.filterTable;
            }
        });
    </script>
@endsection