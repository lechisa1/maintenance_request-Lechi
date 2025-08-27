<div class="col-6">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center p-2">
            <i class="fas fa-file-alt text-muted mb-2"></i>
            <p class="text-truncate small mb-2">{{ $file->original_name }}</p>

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                   class="btn btn-sm btn-outline-secondary rounded-pill preview-btn"
                   data-url="{{ Storage::url($file->file_path) }}"
                   data-type="{{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}">
                    <i class="fas fa-eye me-1"></i> Preview
                </a>

                <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                   class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="fas fa-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

{{-- If bucket is public --}}
{{-- <div class="col-6">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center p-2">
            <i class="fas fa-file-alt text-muted mb-2"></i>
            <p class="text-truncate small mb-2">{{ $file->original_name }}</p>

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ Storage::disk('obs')->url($file->file_path) }}" target="_blank"
                   class="btn btn-sm btn-outline-secondary rounded-pill preview-btn"
                   data-url="{{ Storage::disk('obs')->url($file->file_path) }}"
                   data-type="{{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}">
                    <i class="fas fa-eye me-1"></i> Preview
                </a>

                <a href="{{ Storage::disk('obs')->url($file->file_path) }}" target="_blank"
                   class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="fas fa-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div> --}}
{{-- If bucket is private --}}

{{-- <a href="{{ $file->temporary_url }}" target="_blank"
   class="btn btn-sm btn-outline-secondary rounded-pill preview-btn"
   data-url="{{ $file->temporary_url }}"
   data-type="{{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}">
    <i class="fas fa-eye me-1"></i> Preview
</a>

<a href="{{ $file->temporary_url }}" target="_blank"
   class="btn btn-sm btn-outline-primary rounded-pill">
    <i class="fas fa-download me-1"></i> Download
</a> --}}
