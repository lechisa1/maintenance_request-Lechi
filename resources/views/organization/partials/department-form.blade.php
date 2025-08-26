<div class="department-form card mb-3" data-dept-index="{{ $deptIndex }}">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        {{ $labels['department'] }} Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="departments[{{ $deptIndex }}][name]" 
                           placeholder="e.g. Frontend Development" required>
                    <div class="invalid-feedback">Please provide a department name.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-department-btn">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        @if($divisionIndex !== null)
            <input type="hidden" name="departments[{{ $deptIndex }}][division_index]" value="{{ $divisionIndex }}">
        @endif
    </div>
</div>