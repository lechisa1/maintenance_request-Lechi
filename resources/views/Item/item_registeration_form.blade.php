@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : 'employeers.dashboard.layout')))

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 p-4 bg-white">
            <div class="mb-4 text-center card-header bg-white rounded-top-4">
                <h2 class="text-primary ">
                    📦 Item Registration Form
                </h2>

            </div>

            <form action="{{ route('item_store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Item Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Item Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control shadow-sm rounded-3 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            placeholder="Enter item name" value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold">Unit of Measure</label>
                        @php
                            $units = [
                                'pcs' => 'Pieces',
                                'kg' => 'Kilograms',
                                'ltr' => 'Liters',
                                'm' => 'Meters',
                                'm2' => 'Square Meters',
                                'm3' => 'Cubic Meters',
                                'box' => 'Box',
                                'pack' => 'Pack',
                                'bottle' => 'Bottle',
                            ];
                        @endphp
                        <select name="unit" id="unit"
                            class="form-select shadow-sm rounded-3 {{ $errors->has('unit') ? 'is-invalid' : '' }}">
                            <option value="">Select Unit</option>
                            @foreach ($units as $key => $label)
                                <option value="{{ $key }}" {{ old('unit') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        @error('unit')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Maintenance Categories -->
                    {{-- <div class="col-md-4">
                        <label class="form-label fw-semibold">Maintenance Categories</label>
                        <div class="border rounded shadow-sm p-2 bg-white" style="max-height: 150px; overflow-y: auto;">
                            @foreach ($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                        value="{{ $category->id }}" id="category_{{ $category->id }}"
                                        {{ collect(old('categories'))->contains($category->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('categories')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div> --}}

                    <!-- Submit Button -->
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-4 rounded-pill shadow"
                            onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm\'></span> Submitting...';">
                            <i class="bi bi-plus-circle me-2"></i> Register Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
