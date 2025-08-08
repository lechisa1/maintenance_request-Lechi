{{-- <div class="division-form mb-4 p-3 border rounded bg-light" id="division-{{ $index }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 text-info">Division #{{ $index + 1 }}</h6>
        <button type="button" class="btn btn-sm btn-danger remove-division-btn">
            <i class="material-icons">delete</i> Remove
        </button>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Division Name</label>
                <input type="text" class="form-control" name="divisions[{{ $index }}][name]" required>
            </div>
        </div>
    </div>
    
    <div class="text-end">
        <button type="button" class="btn btn-sm btn-success add-department-to-division" data-division-id="{{ $index }}">
            <i class="material-icons">add</i> Add Department to This Division
        </button>
    </div>
</div> --}}

<div class="division-form mb-4 p-4 border rounded bg-white" 
     id="division-{{ $index }}" 
     data-division-index="{{ $index }}">
     
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 text-info fw-bold">
            <i class="bi bi-diagram-3 me-2"></i> Division #{{ $index + 1 }}
        </h6>
        <button type="button" class="btn btn-sm btn-danger remove-division-btn">
            <i class="bi bi-trash"></i> Remove
        </button>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="input-group input-group-outline my-3 focused is-focused">
                <label class="form-label">Division Name</label>
                <input type="text" class="form-control" 
                       name="divisions[{{ $index }}][name]" required>
            </div>
        </div>
    </div>

    <!-- Department Container -->
    <div class="division-departments mt-4">
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle"></i> No departments added yet
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-sm btn-success add-department-to-division" 
                data-division-index="{{ $index }}">
            <i class="bi bi-plus-circle me-1"></i> Add Department to This Division
        </button>
    </div>
</div>
