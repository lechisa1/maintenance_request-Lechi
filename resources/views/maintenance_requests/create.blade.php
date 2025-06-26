{{-- @extends('admin.layout.app') --}}
@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@if (session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
@section('content')
    <div class="card p-5 bg-light">
        <h4 class="mb-4 text-center text-primary"> Maintenance Request</h4>
        <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Title --}}
            <div class="row g-4">
                {{-- Item --}}
                <div class="col-md-4">
                    <label for="item_id" class="form-label">Item</label>
                    <select name="item_id" id="item_id" class="form-control @error('item_id') border-red-500 @enderror"
                        required>
                        <option value="">Select an Item</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Location --}}
                {{-- <div class="col-md-4">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location"
                        class="form-control @error('location') border-red-500 @enderror" value="{{ old('location') }}"
                        required>
                    @error('location')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div> --}}
                {{-- Categories --}}
                <div class="col-md-4">
                    <label for="categories" class="form-label">Reason</label>
                    <select name="categories[]" id="categories"
                        class="form-control @error('categories') border-red-500 @enderror">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('categories') && in_array($category->id, old('categories')) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    
                    </select>
                    @error('categories')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Priority --}}
                <div class="col-md-4">
                    <label for="priority"class="form-label">Priority</label>
                    <select name="priority" id="priority" class="form-control @error('priority') border-red-500 @enderror"
                        required>
                        <option value="">Select Priority</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="emergency" {{ old('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-md-6">
                    <label for="description" class="form-label "></label>Description</label>
                    <textarea name="description" id="description" rows="2"
                        class="form-control @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="attachments" class="form-label">Attachments</label>
                    <input type="file" name="attachments[]" id="attachments"
                        class="form-control @error('attachments') border-red-500 @enderror" multiple
                        onchange="previewFiles()">
                    @error('attachments')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                    <div id="file-preview" class="mt-3">
                        <!-- File previews will appear here -->
                    </div>
                </div>
                {{-- Submit --}}
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill"
                        onclick="this.innerHTML='Submitting...';">
                        <i class="bi bi-send"></i>Submit
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
                        iframe.height = '400px';
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemSelect = document.getElementById('item_id');
            const categorySelect = document.getElementById('categories');

            itemSelect.addEventListener('change', function() {
                const itemId = this.value;

                if (!itemId) return;

                fetch(`/items/${itemId}/category`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.category_id) {
                            // Clear previous selection
                            for (let i = 0; i < categorySelect.options.length; i++) {
                                categorySelect.options[i].selected = false;

                                if (categorySelect.options[i].value == data.category_id) {
                                    categorySelect.options[i].selected = true;
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching category:', error);
                    });
            });
        });
    </script>
@endsection
