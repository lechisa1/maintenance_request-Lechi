@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : 'employeers.dashboard.layout')))

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4 bg-white">
        <div class="card-body px-4 py-4">
  <div class="card-header bg-white">
          <h4 class="mb-4 text-primary text-center">Hardware Replacement Requests 
        </h4>
  </div>
        {{-- <small class="text-muted">(Waiting for Supervisor Letter)</small> --}}

         <div class="d-flex justify-content-end m-3">
                <input type="text" class="form-control w-25 shadow-sm rounded-pill" id="searchInput" placeholder="🔍 Search..."
                    onkeyup="filterTable()">
            </div>

            <div class="table-responsive rounded-3 shadow-sm">
                <table class="table table-hover align-middle mb-0" id="requestsTable">
                    <thead class="bg-blue text-white text-center" style="background: linear-gradient(90deg, #0d6efd, #6610f2); position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th>#</th>
                        <th class="text-center">Requested By</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Issue</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $key=>$req)
                        <tr class="text-center">
                            <td>{{$key+1}}</td>
                            <td>{{ $req->user->name }}</td>
                            <td>{{ $req->user->department->name ?? '-' }}</td>
                            <td class="text-capitalize">
                                @if ($req->categories && $req->categories->count())
                                    @foreach ($req->categories as $category)
                                        <span
                                            class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>

                            <td>{{ $req->description }}</td>
                            <td class="gap-2 d-flex justify-content-center">
                                @if ($req->supervisor_status === 'pending')
                                    
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#approveModal{{ $req->id }}">Approve</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $req->id }}">Reject</button>
                                @elseif ($req->supervisor_status === 'approved')
                                    <span class="badge bg-success">Approved</span>  
                                @elseif ($req->supervisor_status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Approve Modal -->
                        <div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1"
                            aria-labelledby="approveModalLabel{{ $req->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ url('/supervisor/approve/' . $req->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Letter to Approve</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="file" name="letter" required class="form-control mb-3">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1"
                            aria-labelledby="rejectModalLabel{{ $req->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ url('/supervisor/reject/' . $req->id) }}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reason for Rejection</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <textarea name="reason" class="form-control" rows="4" required placeholder="Enter reason..."></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No requests available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
        <script>
        function filterTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("#requestsTable tbody tr");

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const form = document.getElementById('deleteUserForm');
                const userUrl = button.getAttribute('data-url');
                form.action = userUrl; // Adjust if your route is different
            });
        });
    </script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
@endsection
