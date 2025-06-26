@extends('director.layout.layout')
@section('title', 'Assign Technician')

@section('content')
    <div class="card p-5 bg-light">
        <h4 class="mb-4 text-center text-primary">Assign Technician to Request </h4>

        @if (isset($noTechniciansMessage))
            <div class="alert alert-warning text-center">{{ $noTechniciansMessage }}</div>
        @else
            <form action="{{ route('requests.assign', $maintenanceRequest->id) }}" method="POST" id="assignForm">
                @csrf
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="technician_id" class="form-label">Select Technician</label>
                        <div id="technician-list" class="form-check" style="max-height: 200px; overflow-y: auto;">
                            @foreach ($technicians as $technician)
                                <div class="form-check mb-1">
                                    <input class="form-check-input technician-checkbox" type="checkbox"
                                        name="technician_ids[]" value="{{ $technician->id }}" id="tech{{ $technician->id }}"
                                        data-workload="{{ $technician->assigned_requests_count }}">
                                    <label class="form-check-label" for="tech{{ $technician->id }}"
                                        style="color: {{ $technician->assigned_requests_count >= 5 ? 'red' : 'inherit' }}">
                                        {{ $technician->name }} ({{ $technician->assigned_requests_count }} current tasks)
                                        @if ($technician->assigned_requests_count >= 5)
                                            <strong> (OVERLOADED)</strong>
                                        @endif
                                    </label>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="col-md-4">
                        <label for="director_notes" class="form-label">Director Notes</label>
                        <textarea class="form-control" id="director_notes" name="director_notes" rows="2"></textarea>
                    </div>

                    <div class="col-md-4">
                        <label for="expected_completion_date" class="form-label">Expected Completion Date</label>
                        <input type="date" class="form-control" id="expected_completion_date"
                            name="expected_completion_date" required>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="button" class="btn btn-primary btn-lg rounded-pill" id="assignButton">
                            <i class="bi bi-person-check-fill"></i> Assign Technician
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('assignForm');
            const assignButton = document.getElementById('assignButton');

            assignButton.addEventListener('click', function() {
                const checkedTechs = Array.from(document.querySelectorAll('.technician-checkbox:checked'));

                if (checkedTechs.length === 0) {
                    Swal.fire('Error', 'Please select at least one technician', 'error');
                    return;
                }

                let overloaded = checkedTechs.find(cb => parseInt(cb.dataset.workload) >= 5);

                if (overloaded) {
                    const names = checkedTechs.map(cb => cb.nextElementSibling.textContent.replace(
                        ' (OVERLOADED)', '').trim()).join(', ');

                    Swal.fire({
                        title: 'Confirm Assignment',
                        html: `One or more selected technicians (${names}) are overloaded. Are you sure you want to assign them?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, assign anyway',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'confirmed';
                            input.value = '1';
                            form.appendChild(input);

                            assignButton.innerHTML = '<i class="bi bi-hourglass"></i> Assigning...';
                            assignButton.disabled = true;
                            form.submit();
                        }
                    });
                } else {
                    assignButton.innerHTML = '<i class="bi bi-hourglass"></i> Assigning...';
                    assignButton.disabled = true;
                    form.submit();
                }
            });
        });
    </script>

@endsection
