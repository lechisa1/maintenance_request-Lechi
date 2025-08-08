{{-- @extends('admin.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Create Organization Structure</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Please fix the following issues:
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="organizationForm" action="{{ route('organization.store') }}" method="POST">
                        @csrf

                        <!-- Sector Section (always visible) -->
                        <div class="card card-body border mb-4" id="sectorSection">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 text-primary">1. Sector Information</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Sector Name</label>
                                        <input type="text" class="form-control" name="sector_name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" id="proceedToDivisions" class="btn btn-info">
                                    Next: Add Divisions <i class="material-icons">arrow_forward</i>
                                </button>
                            </div>
                        </div>

                        <!-- Divisions Section (hidden initially) -->
                        <div class="card card-body border mb-4 d-none" id="divisionsSection">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 text-info">2. Divisions</h5>
                                <button type="button" id="addDivisionBtn" class="btn btn-sm btn-info">
                                    <i class="material-icons">add</i> Add Division
                                </button>
                            </div>
                            <div id="divisionsContainer">
                                <!-- Dynamic division forms will appear here -->
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="backToSector" class="btn btn-secondary">
                                    <i class="material-icons">arrow_back</i> Back
                                </button>
                                <button type="button" id="proceedToDepartments" class="btn btn-info">
                                    Next: Add Departments <i class="material-icons">arrow_forward</i>
                                </button>
                            </div>
                        </div>

                        <!-- Departments Section (hidden initially) -->
                        <div class="card card-body border mb-4 d-none" id="departmentsSection">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 text-secondary">3. Departments</h5>
                                <button type="button" id="addDepartmentBtn" class="btn btn-sm btn-secondary">
                                    <i class="material-icons">add</i> Add Department
                                </button>
                            </div>
                            <div id="departmentsContainer">
                                <!-- Dynamic department forms will appear here -->
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="backToDivisions" class="btn btn-secondary">
                                    <i class="material-icons">arrow_back</i> Back
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="material-icons">save</i> Submit All
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    let divisionCount = 0;
    let departmentCount = 0;
    
    // Navigation between sections
    $('#proceedToDivisions').click(function() {
        $('#sectorSection').addClass('d-none');
        $('#divisionsSection').removeClass('d-none');
    });
    
    $('#backToSector').click(function() {
        $('#divisionsSection').addClass('d-none');
        $('#sectorSection').removeClass('d-none');
    });
    
    $('#proceedToDepartments').click(function() {
        $('#divisionsSection').addClass('d-none');
        $('#departmentsSection').removeClass('d-none');
    });
    
    $('#backToDivisions').click(function() {
        $('#departmentsSection').addClass('d-none');
        $('#divisionsSection').removeClass('d-none');
    });
    
    // Add division form
    $('#addDivisionBtn').click(function() {
        $.ajax({
            url: "{{ route('organization.getDivisionForm') }}",
            method: 'GET',
            data: { index: divisionCount },
            success: function(data) {
                $('#divisionsContainer').append(data);
                divisionCount++;
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                console.error("Status:", status);
                console.error("Response:", xhr.responseText);
                alert('Failed to load division form. Please try again.');
            }
        });
    });

    // Add department form (standalone - not tied to division)
    $('#addDepartmentBtn').click(function() {
        $.ajax({
            url: "{{ route('organization.getDepartmentForm') }}",
            method: 'GET',
            data: { 
                division_index: null,
                dept_index: departmentCount
            },
            success: function(data) {
                $('#departmentsContainer').append(data);
                departmentCount++;
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                console.error("Status:", status);
                console.error("Response:", xhr.responseText);
                alert('Failed to load department form. Please try again.');
            }
        });
    });

    // Add department to specific division
    $(document).on('click', '.add-department-to-division', function() {
        const divisionIndex = $(this).data('division-index');
        $.ajax({
            url: "{{ route('organization.getDepartmentForm') }}",
            method: 'GET',
            data: { 
                division_index: divisionIndex,
                dept_index: departmentCount
            },
            success: function(data) {
                $('#departmentsContainer').append(data);
                departmentCount++;
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                console.error("Status:", status);
                console.error("Response:", xhr.responseText);
                alert('Failed to load department form. Please try again.');
            }
        });
    });
    
    // Remove division form
    $(document).on('click', '.remove-division-btn', function() {
        $(this).closest('.division-form').fadeOut(300, function() {
            $(this).remove();
        });
    });
    
    // Remove department form
    $(document).on('click', '.remove-department-btn', function() {
        $(this).closest('.department-form').fadeOut(300, function() {
            $(this).remove();
        });
    });
});
</script>
@endsection --}}


@extends('admin.layout.app')

@section('content')

    <div class="container py-4 card bg-white mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Progress Steps -->
                <div class="mb-4">
                    <ul class="nav nav-pills nav-justified" id="progressSteps">
                        <li class="nav-item">
                            <a class="nav-link active" id="sector-tab" data-bs-toggle="pill" href="#sector-section">
                                <i class="bi bi-building me-1"></i> Sector
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="divisions-tab" data-bs-toggle="pill" href="#divisions-section">
                                <i class="bi bi-diagram-3 me-1"></i> Divisions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="departments-tab" data-bs-toggle="pill"
                                href="#departments-section">
                                <i class="bi bi-people-fill me-1"></i> Departments
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
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="bi bi-building me-2"></i> Sector Information</h5>
                                </div>
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Error!</strong> Please fix the following issues:
                                            <ul class="mt-2 mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="sector_name" class="form-label fw-semibold">Sector Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="sector_name"
                                            name="sector_name" placeholder="e.g. Technology Division" required>
                                        <div class="invalid-feedback">Please provide a sector name.</div>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="hasDivisions"
                                            name="has_divisions">
                                        <label class="form-check-label" for="hasDivisions">This sector will have
                                            divisions</label>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary btn-lg" id="nextToDivisions">
                                            Next <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Divisions Section -->
                        <div class="tab-pane fade" id="divisions-section">
                            <div class="card shadow-sm border-0 mb-4">
                                <div
                                    class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i> Divisions</h5>
                                    <button type="button" id="addDivisionBtn" class="btn btn-light btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Add Division
                                    </button>
                                </div>
                                <div class="card-body" id="divisionsContainer">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> Divisions are optional. Add them if your
                                        sector is divided into multiple units.
                                    </div>
                                </div>
                                <div class="card-footer bg-light d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="backToSector">
                                        <i class="bi bi-arrow-left me-2"></i> Back
                                    </button>
                                    <button type="button" class="btn btn-primary" id="nextToDepartments">
                                        Next <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Departments Section -->
                        <div class="tab-pane fade" id="departments-section">
                            <div class="card shadow-sm border-0 mb-4">
                                <div
                                    class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Departments</h5>
                                    <div>
                                        <button type="button" id="addDepartmentBtn" class="btn btn-light btn-sm me-2">
                                            <i class="bi bi-plus-circle me-1"></i> Add Department
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" id="departmentsContainer">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> Add departments that belong directly to the
                                        sector or to specific divisions.
                                    </div>
                                </div>
                                <div class="card-footer bg-light d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="backToDivisions">
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

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            let divisionCount = 0;
            let departmentCount = 0;
            const hasDivisionsCheckbox = $('#hasDivisions');

            // Initialize tabs
            const tabElms = document.querySelectorAll('button[data-bs-toggle="pill"]');
            tabElms.forEach(tabElm => {
                tabElm.addEventListener('show.bs.tab', function(event) {
                    event.target.classList.add('active');
                    $(event.relatedTarget).removeClass('active');
                });
            });

            // Sector form validation
            $('#organizationForm').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#sector-tab').click();
                }
                this.classList.add('was-validated');
            });

            // Toggle division section based on checkbox
            hasDivisionsCheckbox.change(function() {
                if ($(this).is(':checked')) {
                    $('#divisions-tab').removeClass('disabled');
                } else {
                    $('#divisions-tab').addClass('disabled');
                    $('#departments-tab').removeClass('disabled');
                }
            });

            // Navigation between sections
            $('#nextToDivisions').click(function() {
                if ($('#sector_name').val() === '') {
                    $('#sector_name').addClass('is-invalid');
                    return;
                }

                if (hasDivisionsCheckbox.is(':checked')) {
                    $('#divisions-tab').removeClass('disabled').click();
                } else {
                    $('#departments-tab').removeClass('disabled').click();
                }
            });

            $('#backToSector').click(function() {
                $('#sector-tab').click();
            });

            $('#nextToDepartments').click(function() {
                $('#departments-tab').removeClass('disabled').click();
            });

            $('#backToDivisions').click(function() {
                $('#divisions-tab').click();
            });

            // Add division form
            $('#addDivisionBtn').click(function() {
                $.get("{{ route('organization.getDivisionForm') }}", {
                    index: divisionCount
                }, function(html) {
                    $('#divisionsContainer .alert').remove();
                    $('#divisionsContainer').append(html);

                    // Add animation
                    const newDiv = $('#divisionsContainer .division-form').last();
                    newDiv.hide().fadeIn(300);

                    divisionCount++;
                }).fail(function(xhr) {
                    console.error("Error loading division form:", xhr.responseText);
                    alert('Failed to load division form. Please try again.');
                });
            });

            // Add department form (standalone or linked to division)
            $('#addDepartmentBtn').click(function() {
                addDepartmentForm(null);
            });

// Replace your existing addDepartmentForm function with this:
function addDepartmentForm(divisionIndex) {
    console.log("Adding department to division:", divisionIndex);
    
    $.ajax({
        url: "{{ route('organization.getDepartmentForm') }}",
        method: 'GET',
        data: { 
            division_index: divisionIndex,
            dept_index: departmentCount
        },
        success: function(data) {
            console.log("Department form received:", data);
            
            if (divisionIndex !== null) {
                // Add to specific division's container
                const container = $(`.division-form[data-division-index="${divisionIndex}"] .division-departments`);
                container.find('.alert').remove();
                const newDept = $(data).hide();
                container.append(newDept);
                newDept.fadeIn(300);
                console.log("Added to division container:", container.length);
            } else {
                // Add to main departments container
                $('#departmentsContainer .alert').remove();
                const newDept = $(data).hide();
                $('#departmentsContainer').append(newDept);
                newDept.fadeIn(300);
                console.log("Added to main container");
            }
            
            departmentCount++;
            
            // Debug: Verify DOM
            console.log("Current department forms:", $('.department-form').length);
        },
        error: function(xhr, status, error) {
            console.error("Error:", error, "Response:", xhr.responseText);
            alert('Failed to add department. Please try again.');
        }
    });
}

// Update your click handler
$(document).on('click', '.add-department-to-division', function() {
    const divisionIndex = $(this).data('division-index');
    console.log("Button clicked for division:", divisionIndex);
    addDepartmentForm(divisionIndex);
});

            // Remove division form
            $(document).on('click', '.remove-division-btn', function() {
                const divisionForm = $(this).closest('.division-form');
                divisionForm.fadeOut(300, function() {
                    divisionForm.remove();
                    if ($('#divisionsContainer .division-form').length === 0) {
                        $('#divisionsContainer').html(
                            '<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i> Divisions are optional. Add them if your sector is divided into multiple units.</div>'
                            );
                    }
                });
            });

            // Remove department form
            $(document).on('click', '.remove-department-btn', function() {
                const deptForm = $(this).closest('.department-form');
                deptForm.fadeOut(300, function() {
                    deptForm.remove();
                    if ($('#departmentsContainer .department-form').length === 0) {
                        $('#departmentsContainer').html(
                            '<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i> Add departments that belong directly to the sector or to specific divisions.</div>'
                            );
                    }
                });
            });
        });
    </script>
@endsection
