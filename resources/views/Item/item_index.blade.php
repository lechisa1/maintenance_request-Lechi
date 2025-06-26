@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="card shadow-sm p-4 rounded-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-primary fw-bold mb-0">✨ Item Registered List</h4>
            <a href="{{ route('item_registeration_form') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Register Item
            </a>
        </div>
        {{-- @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif --}}
        <div class="mt-3 mb-2">
            <input type="text" class="form-control" id="searchInput" placeholder="🔍 Search..." onkeyup="filterTable()"
                style="max-width: 250px; float: right;">
        </div>
        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover align-middle"id="requestsTable">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Unit</th>
                        {{-- <th>Stock Quantity</th> --}}
                        <th>Categories</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $key => $item)
                        <tr>
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->unit }}</td>
                            {{-- <td>{{ $item->in_stock }}</td> --}}
                            <td>
                                @if ($item->categories->isEmpty())
                                    <span class="text-muted">No Categories</span>
                                @else
                                    @foreach ($item->categories as $category)
                                        <span class="badge bg-secondary">{{ $category->name }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="d-flex">
                                <a href="{{ route('edit_item', $item->id) }}" class="btn btn-warning bi bi-pencil"></a>
                                <button class="btn btn-sm btn-outline-danger " data-bs-toggle="modal"
                                    data-bs-target="#deleteModal-{{ $item->id }}" style="margin-left: 5px">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this item ? This cannot be
                                                undone.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('delete_item', $item->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-exclamation-circle me-1"></i> No Item Registered Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- @include('components.pagination', ['paginator' => $maintenances]) --}}
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
@endsection
