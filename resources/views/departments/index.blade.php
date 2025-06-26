@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="card shadow-sm p-4 rounded-4">
        <div class="d-flex">
            <h4 class="mb-4 text-primary fw-normal">✨ Department List</h4>
            <a href="{{ route('create_department') }}" class="btn btn-primary" style="margin-left: 60%">
                <i class="bi bi-plus-circle me-1"></i> Add Department
            </a>
        </div>
         <div class="mb-3" style="margin:5px">
                <input type="text" class="form-control" id="searchInput" placeholder="🔍 Search..." onkeyup="filterTable()"
                    style="max-width: 250px; float: right;">
            </div>
        <div class="table-responsive rounded-3">
            <table id="requestsTable" class="table table-striped align-middle mb-0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col">Department Name</th>
                        <th scope="col" class="d-none d-md-table-cell">Description</th>
                        <th scope="col" style="width: 25%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $key => $department)
                        <tr>
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td>{{ $department->name }}</td>
                            <td class="d-none d-md-table-cell text-muted">{{ $department->description ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('department_edit', $department->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('delete_department', $department->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>



                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">
                                <i class="bi bi-exclamation-circle"></i> No departments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('components.pagination', ['paginator' => $departments])
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
