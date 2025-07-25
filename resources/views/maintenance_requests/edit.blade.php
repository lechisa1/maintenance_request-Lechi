@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@if (session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
@section('content')
<div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 bg-white">
            <div class="card-body px-4 py-4">
        <div class="text-center mb-5 card-header bg-white text-primary rounded-top-4">
            {{-- <h3 class="fw-bold text-primary">Maintenance Request Form</h3> --}}
            <h3 class="fw-bold ">Edit Maintenance Request</h3>

        </div>

        <form action="{{ route('requests.update', $maintenanceRequest->id) }}" method="post"enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <div class="col-md-4">
                    <label for="item_id" class="form-label fw-semibold">Item</label>
                    <select name="item_id" id="item_id"
                        class="form-select rounded-pill @error('item_id') is-invalid @enderror" required>
                        <option value="">Select an Item</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('item_id', $maintenanceRequest->item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('item_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="col-md-4">
                    <label for="priority" class="form-label fw-semibold">Priority</label>
                    <select id="priority" name="priority"
                        class="form-select rounded-pill @error('priority') is-invalid @enderror" required>
                        <option value="low"
                            {{ old('priority', $maintenanceRequest->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium"
                            {{ old('priority', $maintenanceRequest->priority) === 'medium' ? 'selected' : '' }}>Medium
                        </option>
                        <option value="high"
                            {{ old('priority', $maintenanceRequest->priority) === 'high' ? 'selected' : '' }}>High</option>
                        <option value="emergency"
                            {{ old('priority', $maintenanceRequest->priority) === 'emergency' ? 'selected' : '' }}>Emergency
                        </option>
                    </select>

                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Reason</label>
                    <div class="bg-light border rounded-3 p-2">
                        @php
                            $selectedCategories = old(
                                'categories',
                                $maintenanceRequest->categories->pluck('id')->toArray(),
                            );
                        @endphp

                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    value="{{ $category->id }}" id="category_{{ $category->id }}"
                                    {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach

                    </div>
                    @error('categories')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="attachments" class="form-label fw-semibold">Attachments</label>
                    <input type="file" name="attachments[]" id="attachments"
                        class="form-control rounded-pill @error('attachments') is-invalid @enderror" multiple
                        onchange="previewFiles()">
                    @if ($maintenanceRequest->attachments && is_array($maintenanceRequest->attachments))
                        <div class="mt-2">
                            <label class="form-label fw-semibold">Existing Attachments:</label>
                            <ul class="list-unstyled">
                                @foreach ($maintenanceRequest->attachments as $file)
                                    <li>
                                        <a href="{{ asset('storage/attachments/' . $file) }}" target="_blank">
                                            <i class="bi bi-paperclip"></i> {{ $file }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @error('attachments')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <div id="file-preview" class="mt-2 text-muted small fst-italic"></div>
                </div>

                <div class="col-md-8">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="form-control rounded-4 @error('description') is-invalid @enderror"
                        placeholder="Describe the issue clearly...">{{ old('description', $maintenanceRequest->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Update Request
                    </button>
                </div>
            </div>
        </form>
    </div>
        </div>
</div>
    <script>
        function previewFiles() {
            const fileInput = document.getElementById('attachments');
            const previewContainer = document.getElementById('file-preview');
            previewContainer.innerHTML = ''; // Clear existing previews

            Array.from(fileInput.files).forEach(file => {
                const reader = new FileReader();
                const fileType = file.type;
                const preview = document.createElement('div');
                preview.classList.add('file-preview-item', 'mb-2');

                reader.onload = function(event) {
                    if (fileType.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.alt = file.name;
                        img.style.maxWidth = '100%';
                        img.style.height = 'auto';
                        preview.appendChild(img);
                    } else if (fileType === 'application/pdf') {
                        const iframe = document.createElement('iframe');
                        iframe.src = event.target.result;
                        iframe.width = '100%';
                        iframe.height = '250px';
                        preview.appendChild(iframe);
                    } else if (fileType.startsWith('text/')) {
                        const textPreview = document.createElement('pre');
                        textPreview.textContent = event.target.result;
                        textPreview.style.whiteSpace = 'pre-wrap';
                        textPreview.style.maxHeight = '300px';
                        textPreview.style.overflowY = 'auto';
                        preview.appendChild(textPreview);
                    } else {
                        const fileInfo = document.createElement('p');
                        fileInfo.textContent = `File: ${file.name}`;
                        preview.appendChild(fileInfo);
                    }

                    previewContainer.appendChild(preview);
                };

                // For text preview, read as text, otherwise use Data URL
                if (fileType.startsWith('text/')) {
                    reader.readAsText(file);
                } else {
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
@endsection
