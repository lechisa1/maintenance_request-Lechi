<!-- resources/views/technician/requests/index.blade.php -->
@extends('technician.dashboard.layout')

{{-- @section('title', 'My Assigned Requests') --}}

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
                <div class="">
                    <h5 class="mb-0">List of Request Assigned To You</h5>
                </div>

                <div class="d-flex justify-content-end mb-3">
                    <input type="text" class="form-control w-25 shadow-sm rounded-3" id="searchInput"
                        placeholder="🔍 Search..." onkeyup="filterTable()">
                </div>

                <div class="table-responsive rounded-3 shadow-sm">
                    <table class="table table-hover align-middle mb-0" id="requestsTable">
                        <thead class="bg-blue text-white text-center"
                            style="background: linear-gradient(90deg, #0d6efd, #6610f2); position: sticky; top: 0; z-index: 1;">
                            <tr class="">
                                <th>#</th>
                                <th>Ticket No</th>

                                <th>Item Name</th>
                                <th>Requester</th>
                                {{-- <th>Department</th>
                            <th>Requester Phone</th> --}}
                                <th>Priority</th>
                                <th>Status</th>

                                <th>Actions</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $key=> $request)
                                <tr>
                                    <td class="text-center fw-bold">
                                        <button class="btn btn-sm "
                                            onclick="toggleDetails({{ $request->id }})">
                                            <i class="bi bi-plus-lg text-primary" id="icon-{{ $request->id }}"></i>
                                        </button>{{ $key + 1 }}
                                    </td>
                                    <td>{{ $request->ticket_number }}</td>

                                    <td>{{ $request->item ? $request->item->name : 'N/A' }}</td>
                                    <td>{{ $request->user->name }}</td>
                                    {{-- <td>{{ $request->user->department->name }}</td>
                                <td>{{ $request->user->phone }}</td> --}}
                                    <td>
                                        <span
                                            class="text-{{ $request->priority == 'high' ? 'danger' : ($request->priority == 'medium' ? 'warning' : 'success') }}">
                                            {{ ucfirst($request->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-{{ $request->status == 'completed' ? 'success' : ($request->status == 'in_progress' ? 'info' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    {{-- <td>{{ $request->assignments->assigned_at->format('M d, Y') }}</td> --}}
                                    {{-- <td>{{ $request->assignments->expected_completion_date->format('M d, Y') }}</td> --}}
                                    <td class="d-flex">
                                        {{-- <a href="{{ route('technician.show', $request->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye fs-5"></i>
                                    </a> --}}
                                        @if ($request->status != 'completed')
                                            <a href="{{ route('tecknician_work_form', $request->id) }}"
                                                class="btn btn-sm btn-outline-primary" style="margin-left: 3px">
                                                <i class="bi bi-plus text-warning fs-5"></i> Progress
                                            </a>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('technician.show', $request->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                    </td>

                                </tr>
                                <tr id="details-{{ $request->id }}" class="d-none bg-light">
                                    <td colspan="8">
                                        <div class="row g-2 p-3 text-start" style="list-style: none;">
                                            <div class="col-12 border-bottom py-1"><strong>Assigned At:</strong>
                                                {{ $request->latestAssignment->assigned_at->format('M d, Y h:i A') }}</div>
                                            {{-- <div class="col-md-4"><strong>Rejection Reason:</strong>
                                            {{ $request->rejection_reason }}</div> --}}
                                            <div class="col-12 border-bottom py-1"><strong>Requester Phone:</strong>
                                                {{ $request->user->phone }}
                                            </div>
                                            <div class="col-12 border-bottom py-1"><strong>Description:</strong>
                                                {{ $request->description }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Requested At:</strong>
                                                {{ $request->requested_at->format('M d, Y h:i A') }}</div>
                                            <div class="col-12 border-bottom py-1"><strong>Requester Department:</strong>
                                                {{ $request->user->department->name }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No requests assigned to you yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

 
                @include('components.pagination', ['paginator' => $requests])

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
