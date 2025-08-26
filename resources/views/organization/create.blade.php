@extends('admin.layout.app')

@php
    $selectedSector = $sector ?? null;
    $selectedDivision = $division ?? null;
@endphp

@section('content')
    <div class="container py-4 card bg-white mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Progress Steps -->
                <div class="mb-4">
                    <ul class="nav nav-pills nav-justified " id="progressSteps">
                        <li class="nav-item">
                            <a class="nav-link active " id="sector-tab" data-bs-toggle="pill" href="#sector-section">
                                <i class="bi bi-building me-1"></i> {{ $labels['sector'] }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled " id="divisions-tab" data-bs-toggle="pill"
                                href="#divisions-section">
                                <i class="bi bi-diagram-3 me-1"></i> {{ $labels['division'] }}s
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="departments-tab" data-bs-toggle="pill"
                                href="#departments-section">
                                <i class="bi bi-people-fill me-1"></i> {{ $labels['department'] }}s
                            </a>
                        </li>
                    </ul>
                </div>

                <form id="organizationForm" action="{{ route('organization.store') }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf

                    <div class="tab-content">
                        <!-- Sector Section -->
                        <div class="tab-pane fade show active" id="sector-section">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-building me-2"></i>
                                        @if ($selectedSector)
                                            Add {{ $labels['division'] }} to {{ $selectedSector->name }}
                                        @elseif($selectedDivision)
                                            Add {{ $labels['department'] }} to {{ $selectedDivision->name }}
                                        @else
                                            {{ $labels['sector'] }} Information
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if ($selectedSector)
                                        <input type="hidden" name="sector_id" value="{{ $selectedSector->id }}">
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i> Adding {{ $labels['division'] }} to:
                                            <strong>{{ $selectedSector->name }}</strong>
                                        </div>
                                    @elseif($selectedDivision)
                                        <input type="hidden" name="division_id" value="{{ $selectedDivision->id }}">
                                        <input type="hidden" name="sector_id" value="{{ $selectedDivision->sector_id }}">
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i> Adding {{ $labels['department'] }} to:
                                            <strong>{{ $selectedDivision->name }}</strong>
                                            ({{ $selectedDivision->sector->name }})
                                        </div>
                                    @else
                                        <!-- Regular sector name input -->
                                        <!-- Select Organization -->
                                        <div class="mb-3">
                                            <label for="organization_id" class="form-label">Select Organization</label>
                                            <select name="organization_id" id="organization_id" class="form-control"
                                                required>
                                                <option value="">Choose Organization</option>
                                                @foreach ($organizations as $org)
                                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sector_name" class="form-label fw-semibold">
                                                {{ $labels['sector'] }} Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="sector_name"
                                                name="sector_name" placeholder="e.g. Technology {{ $labels['division'] }}"
                                                required value="{{ old('sector_name') }}">
                                            <div class="invalid-feedback">Please provide a sector name.</div>
                                        </div>
                                        <!-- Has divisions toggle -->
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="hasDivisions"
                                                name="has_divisions">
                                            <label class="form-check-label" for="hasDivisions">
                                                Add {{ $labels['division'] }}s to this {{ $labels['sector'] }}
                                            </label>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-light d-flex justify-content-end">
                                    <!-- Next button -->
                                    <button type="button" class="btn btn-primary btn-lg d-none" id="nextToDivisions">
                                        Next <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                    <!-- Save if no divisions -->
                                    <button type="submit" class="btn btn-primary btn-lg ms-2 d-none" id="saveSectorOnly">
                                        <i class="bi bi-save me-2"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Divisions Section -->
                        <div class="tab-pane fade" id="divisions-section">
                            <div class="card shadow-sm border-0 mb-4">
                                <div
                                    class="card-header btn-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>{{ $labels['division'] }}</h5>
                                    <button type="button" id="addDivisionBtn" class="btn btn-light btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Add {{ $labels['division'] }}
                                    </button>
                                </div>
                                <div class="card-body" id="divisionsContainer">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> {{ $labels['division'] }} are optional.
                                    </div>
                                    <!-- Departments toggle -->
                                    <div class="form-check form-switch mb-3 mt-4 d-none" id="deptToggleWrap">
                                        <input class="form-check-input" type="checkbox" id="hasDepartments"
                                            name="has_departments">
                                        <label class="form-check-label" for="hasDepartments">
                                            Add {{ $labels['department'] }}s to these {{ $labels['division'] }}s
                                        </label>
                                    </div>
                                </div>
                                <div class="card-footer bg-light d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary" id="backToSector">
                                        <i class="bi bi-arrow-left me-2"></i> Back
                                    </button>
                                    <div>
                                        <button type="submit" class="btn btn-primary me-2 d-none"
                                            id="saveWithDivisions">
                                            <i class="bi bi-save me-2"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-primary d-none" id="nextToDepartments">
                                            Next <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Departments Section -->
                        <div class="tab-pane fade" id="departments-section">
                            <div class="card shadow-sm border-0 mb-4">
                                <div
                                    class="card-header btn-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>{{ $labels['department'] }}s
                                    </h5>
                                    <button type="button" id="addDepartmentBtn" class="btn btn-light btn-sm me-2">
                                        <i class="bi bi-plus-circle me-1"></i> Add {{ $labels['department'] }}
                                    </button>
                                </div>
                                <div class="card-body" id="departmentsContainer">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> Add {{ $labels['department'] }}s directly
                                        under
                                        the {{ $labels['sector'] }} or link them to specific {{ $labels['division'] }}s.
                                    </div>
                                </div>
                                <div class="card-footer bg-light d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary" id="backToDivisions">
                                        <i class="bi bi-arrow-left me-2"></i> Back
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(function() {
            let divisionCount = 0,
                departmentCount = 0;
            const hasDivisionsCheckbox = $('#hasDivisions');
            const hasDepartmentsCheckbox = $('#hasDepartments');
            const hasPreSelectedSector = {{ $selectedSector ? 'true' : 'false' }};
            const hasPreSelectedDivision = {{ $selectedDivision ? 'true' : 'false' }};

            // Auto-navigate based on pre-selected items
            if (hasPreSelectedSector) {
                // Automatically check divisions and move to divisions tab
                $('#hasDivisions').prop('checked', true).trigger('change');
                $('#divisions-tab').removeClass('disabled').click();
            } else if (hasPreSelectedDivision) {
                // Automatically move to departments tab
                $('#hasDivisions').prop('checked', true).trigger('change');
                $('#divisions-tab').removeClass('disabled');
                $('#departments-tab').removeClass('disabled').click();
            }

            // Toggle Sector buttons
            hasDivisionsCheckbox.change(function() {
                if ($(this).is(':checked')) {
                    $('#nextToDivisions').removeClass('d-none');
                    $('#saveSectorOnly').addClass('d-none');
                } else {
                    $('#nextToDivisions').addClass('d-none');
                    $('#saveSectorOnly').removeClass('d-none');
                }
            }).trigger('change'); // initialize on load

            // Next → Divisions
            $('#nextToDivisions').click(function() {
                if ($('#sector_name').length && !$('#sector_name').val()) {
                    $('#sector_name').addClass('is-invalid');
                    return;
                }
                $('#divisions-tab').removeClass('disabled').click();
            });

            // Back → Sector
            $('#backToSector').click(function() {
                $('#sector-tab').click();
            });

            // Divisions: toggle Save/Next depending on "has departments"
            hasDepartmentsCheckbox.change(function() {
                if ($(this).is(':checked')) {
                    $('#nextToDepartments').removeClass('d-none');
                    $('#saveWithDivisions').addClass('d-none');
                } else {
                    $('#nextToDepartments').addClass('d-none');
                    $('#saveWithDivisions').removeClass('d-none');
                }
            });

            // Next → Departments
            $('#nextToDepartments').click(function() {
                // Validate division forms if any exist
                let divisionsValid = true;
                $('.division-form').each(function() {
                    const divisionName = $(this).find('input[name^="divisions"]').val();
                    if (!divisionName) {
                        $(this).find('input[name^="divisions"]').addClass('is-invalid');
                        divisionsValid = false;
                    } else {
                        $(this).find('input[name^="divisions"]').removeClass('is-invalid');
                    }
                });

                if (!divisionsValid) return;

                $('#departments-tab').removeClass('disabled').click();
            });

            // Back → Divisions
            $('#backToDivisions').click(function() {
                $('#divisions-tab').click();
            });

            // Add Division
            $('#addDivisionBtn').click(function() {
                $.get("{{ route('organization.getDivisionForm') }}", {
                    index: divisionCount
                }, function(html) {
                    $('#divisionsContainer .alert').remove();
                    $('#divisionsContainer').append(html);

                    const $newDiv = $('#divisionsContainer .division-form').last();
                    $newDiv.hide().fadeIn(300);

                    divisionCount++;

                    // Show "add departments?" switch once at least one division exists
                    $('#deptToggleWrap').removeClass('d-none');
                }).fail(function(xhr) {
                    console.error("Error loading division form:", xhr.responseText);
                    alert('Failed to load division form. Please try again.');
                });
            });

            // Add Department to specific Division
            $(document).on('click', '.add-department-to-division', function() {
                const divisionIndex = $(this).data('division-index');
                addDepartmentForm(divisionIndex);
            });

            // Add Department to Sector-level (Departments tab)
            $('#addDepartmentBtn').click(function() {
                addDepartmentForm(null);
            });

            // Core function to add department either under sector or a division
            function addDepartmentForm(divisionIndex) {
                $.get("{{ route('organization.getDepartmentForm') }}", {
                    division_index: divisionIndex,
                    dept_index: departmentCount
                }, function(html) {

                    // If a division index is provided, inject inside that division's container
                    if (divisionIndex !== null && divisionIndex !== undefined && String(divisionIndex) !==
                        '') {
                        const $target = $(
                            `.division-form[data-division-index="${divisionIndex}"] .division-departments`
                            );
                        if ($target.length) {
                            $target.find('.alert').remove();
                            const $el = $(html).hide();
                            $target.append($el);
                            $el.fadeIn(300);
                        } else {
                            // Fallback: if container not found, append to divisions container and warn
                            console.warn('Division container not found for index:', divisionIndex);
                            const $fallback = $(html).hide();
                            $('#divisionsContainer').append($fallback);
                            $fallback.fadeIn(300);
                        }
                    } else {
                        // Sector-level departments (Departments tab)
                        $('#departmentsContainer .alert').remove();
                        const $el = $(html).hide();
                        $('#departmentsContainer').append($el);
                        $el.fadeIn(300);
                    }

                    departmentCount++;
                }).fail(function(xhr) {
                    console.error("Error loading department form:", xhr.responseText);
                    alert('Failed to add department. Please try again.');
                });
            }

            // Remove division
            $(document).on('click', '.remove-division-btn', function() {
                const $divisionForm = $(this).closest('.division-form');
                $divisionForm.fadeOut(300, function() {
                    $divisionForm.remove();
                    if ($('#divisionsContainer .division-form').length === 0) {
                        $('#divisionsContainer').html(
                            '<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i> {{ $labels['division'] }} are optional.</div>'
                        );
                        $('#deptToggleWrap').addClass('d-none');
                        $('#hasDepartments').prop('checked', false).trigger('change');
                    }
                });
            });

            // Remove department
            $(document).on('click', '.remove-department-btn', function() {
                const $deptForm = $(this).closest('.department-form');
                $deptForm.fadeOut(300, function() {
                    $deptForm.remove();
                    if ($('#departmentsContainer .department-form').length === 0) {
                        $('#departmentsContainer').html(
                            '<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i> Add {{ $labels['department'] }}s directly under the {{ $labels['sector'] }} or link them to specific {{ $labels['division'] }}s.</div>'
                        );
                    }
                });
            });

            // Validate division name inputs on change
            $(document).on('input', 'input[name^="divisions"]', function() {
                if ($(this).val()) {
                    $(this).removeClass('is-invalid');
                } else {
                    $(this).addClass('is-invalid');
                }
            });
        });
    </script>
@endsection
