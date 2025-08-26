<div class="division-form card mb-3" data-division-index="{{ $index }}">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        {{ $labels['division'] }} Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="divisions[{{ $index }}][name]" 
                           placeholder="e.g. Software Development" required>
                    <div class="invalid-feedback">Please provide a division name.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-sm btn-primary add-department-to-division" 
                            data-division-index="{{ $index }}">
                        <i class="bi bi-plus-circle me-1"></i>Add {{ $labels['department'] }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-division-btn">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Container for division-specific departments -->
        <div class="division-departments mt-3">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> Add {{ $labels['department'] }}s specific to this {{ $labels['division'] }}.
            </div>
        </div>
    </div>
</div>