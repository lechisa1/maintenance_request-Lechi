@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <!-- Loading Spinner -->
    <div id="loading-spinner" style="display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="card shadow-sm p-4 rounded-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-primary fw-bold mb-0">✨ Maintenance Request List</h4>
            <a href="{{ route('requests.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Maintenance
            </a>
        </div>

        <div class="table-responsive rounded-3">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Ticket ID</th>
                        <th>Requester</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                        <th class="text-center">Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequest as $key => $request)
                        <tr>
                            <td class="text-center fw-bold">
                                <button class="btn btn-sm btn-outline-primary" onclick="toggleDetails({{ $request->id }})">
                                    <i class="bi bi-plus-lg" id="icon-{{ $request->id }}"></i>
                                </button>
                                {{ $key + 1 }}
                            </td>
                            <td>{{ $request->ticket_number }}</td>

                            <td>{{ $request->user->name }}</td>

                            <td>
                                <span
                                    class="badge bg-{{ $request->priority === 'high' ? 'danger' : ($request->priority === 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'in_progress' ? 'primary' : ($request->status === 'completed' ? 'success' : 'secondary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td class="d-flex">
                                @if ($request->status === 'pending' && auth()->id() === $request->user_id)
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
                                    <span class="text-muted">—</span>
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
                        <tr id="details-{{ $request->id }}" class="d-none bg-light">
                            <td colspan="8">
                                <div class="row g-2 p-3 text-start" style="list-style: none;">

                                    <div class="col-12 border-bottom py-1"><strong>Phone:</strong>
                                        {{ $request->user->phone }}</div>
                                    <div class="col-12 border-bottom py-1"><strong>Item:</strong>
                                        {{ $request->item?->name ?? 'N/A' }}</div>
                                    @if ($request->description)
                                        <div class="col-12 py-1"><strong>Description:</strong>
                                            {{ $request->description }}</div>
                                    @endif
                                    <div class="col-12 border-bottom py-1"><strong>Requested At:</strong>
                                        {{ $request->requested_at->format('M d, Y h:i A') }}</div>
                                    <div class="col-12 py-1"><strong>Department:</strong>
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
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-exclamation-circle me-1"></i> No Maintenance Requests Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('components.pagination', ['paginator' => $pendingRequest])
    </div>
    <script src="/js/for_table.js"></script>
    <script>
        // Show spinner on page load
        window.onload = function() {
            document.getElementById('loading-spinner').style.display = 'none'; // Hide it once the page is loaded
        };

        // Show spinner when AJAX request starts
        $(document).ajaxStart(function() {
            $('#loading-spinner').show();
        }).ajaxStop(function() {
            $('#loading-spinner').hide();
        });
    </script>
@endsection
