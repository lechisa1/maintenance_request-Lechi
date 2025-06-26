@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('content')
    <div class="card p-4">
        <h4 class="mb-4 text-center text-danger-emphasis">Edit Maintenance Request</h4>

        <form action="{{ route('requests.update', $maintenanceRequest->id) }}" method="post"enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-4">
                    <label for="item_id" class="form-label">Item</label>
                    <select class="form-select" id="item_id" name="item_id" required>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('item_id', $maintenanceRequest->item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <div class="col-4">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="low" {{ $maintenanceRequest->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $maintenanceRequest->priority === 'medium' ? 'selected' : '' }}>Medium
                        </option>
                        <option value="high" {{ $maintenanceRequest->priority === 'high' ? 'selected' : '' }}>High
                        </option>
                        <option value="emergency" {{ $maintenanceRequest->priority === 'emergency' ? 'selected' : '' }}>
                            Emergency</option>
                    </select>
                </div>

                <div class="col-4">
                    <label for="categories" class="form-label">Categories</label>
                    <select class="form-select" id="categories" name="categories[]">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">

                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4">
                    <label for="attachments" class="form-label">Attachments</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple
                        onchange="previewFiles()">
                    <div id="file-preview" class="mt-3">
                        <!-- File previews will appear here -->
                    </div>
                </div>
                {{-- <div class="col-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending" {{ $maintenanceRequest->status === 'pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="in_progress" {{ $maintenanceRequest->status === 'in_progress' ? 'selected' : '' }}>
                            In
                            Progress</option>
                        <option value="completed" {{ $maintenanceRequest->status === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                </div> --}}
                <div class="col-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="2" required>{{ old('description', $maintenanceRequest->description) }}</textarea>
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Update Request
                    </button>
                </div>
            </div>
        </form>
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
