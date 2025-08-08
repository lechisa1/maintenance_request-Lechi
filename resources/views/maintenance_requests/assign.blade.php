@extends('director.layout.layout')
@section('title', 'Assign Technician')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">

                <div class="text-center mb-5 card-header bg-white text-primary  rounded-top-4">
                    {{-- <h3 class="fw-bold text-primary">Maintenance Request Form</h3> --}}
                    <h3 class="fw-bold ">Assign Technicians to Request</h3>

                </div>
                @if (isset($noTechniciansMessage))
                    <div class="alert alert-warning text-center fs-5">
                        {{ $noTechniciansMessage }}
                    </div>
                @else
                    <form action="{{ route('requests.assign', $maintenanceRequest->id) }}" method="POST" id="assignForm">
                        @csrf
                        <div class="row g-4">

                            {{-- Technician List --}}
                            <div class="col-md-6">
                                <label for="technician_id" class="form-label fw-semibold">Select Technician(s)</label>
                                <div class="border rounded p-3" id="technician-list"
                                    style="max-height: 250px; overflow-y: auto;">
                                    @foreach ($technicians as $technician)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input technician-checkbox" type="checkbox"
                                                name="technician_ids[]" value="{{ $technician->id }}"
                                                id="tech{{ $technician->id }}"
                                                data-workload="{{ $technician->assigned_requests_count }}">
                                            <label class="form-check-label" for="tech{{ $technician->id }}"
                                                style="color: {{ $technician->assigned_requests_count >= 5 ? '#dc3545' : '#212529' }}">
                                                <strong>{{ $technician->name }}</strong>
                                                <span class="small text-muted">
                                                    ({{ $technician->assigned_requests_count }} tasks)
                                                </span>
                                                @if ($technician->assigned_requests_count >= 5)
                                                    <span class="badge bg-danger ms-2">Overloaded</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Director Remarks --}}
                            <div class="col-md-6">
                                <label for="director_notes" class="form-label fw-semibold">Director Remarks</label>
                                <textarea class="form-control rounded-3" id="director_notes" name="director_notes" rows="3"
                                    placeholder="Optional note or comment for the assigned technician(s)...">{{ old('director_notes') }}</textarea>
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-12 text-end mt-3">
                                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill" id="assignButton">
                                    <i class="bi bi-person-check-fill me-1"></i> Assign Technician
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
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
