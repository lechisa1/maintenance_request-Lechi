{{-- <div class="department-form mb-4 p-3 border rounded bg-light" id="department-{{ $dept_index }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 text-secondary">
            Department #{{ $dept_index + 1 }}
            @if($division_id !== null)
                (for Division {{ $division_id + 1 }})
            @endif
        </h6>
        <button type="button" class="btn btn-sm btn-danger remove-department-btn">
            <i class="material-icons">delete</i> Remove
        </button>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Department Name</label>
                <input type="text" class="form-control" name="departments[{{ $dept_index }}][name]" required>
            </div>
        </div>
                <div class="col-md-6">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Description </label>
                <input type="text" class="form-control" name="description[{{ $descIndex }}][description]" required>
            </div>
        </div>
    </div>
    
    @if($division_id !== null)
        <input type="hidden" name="departments[{{ $dept_index }}][division_id]" value="{{ $division_id }}">
    @endif
</div> --}}
<div class="department-form mb-4 p-4 border rounded bg-white" 
     id="department-{{ $dept_index }}"
     data-division-index="{{ $division_index ?? '' }}">
     
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 text-secondary fw-bold">
            <i class="bi bi-people-fill me-2"></i> Department #{{ $dept_index + 1 }}
            @if($division_index !== null)
                (for Division {{ $division_index + 1 }})
            @else
                (for Sector)
            @endif
        </h6>
        <button type="button" class="btn btn-sm btn-danger remove-department-btn">
            <i class="bi bi-trash"></i> Remove
        </button>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="input-group input-group-outline my-3 focused is-focused">
                <label class="form-label">Department Name</label>
                <input type="text" class="form-control" 
                       name="departments[{{ $dept_index }}][name]" required>
            </div>
        </div>
    </div>
    
    @if($division_index !== null)
        <input type="hidden" name="departments[{{ $dept_index }}][division_index]" 
               value="{{ $division_index }}">
    @endif
</div>