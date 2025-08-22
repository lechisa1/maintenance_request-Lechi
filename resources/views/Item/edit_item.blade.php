@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="card p-4">
        <h2 class="text-center text-primary">Edit Item</h2>

        <form action="{{ route('update_item', $item->id) }}" method="POST">
            @csrf


            <div class="row g-3">
                <div class="col-md-4">
                    <label>Item Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $item->name) }}"
                        id="name">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label>Unit Measure</label>
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
                    <select name="unit" class="form-control" id="unit">
                        <option value="">Select Unit</option>
                        @foreach ($units as $key => $label)
                            <option value="{{ $key }}" {{ old('unit', $item->unit) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- <div class="col-md-4">
                    <label>Stock Quantity</label>
                    <input name="in_stock" class="form-control" value="{{ old('in_stock', $item->in_stock) }}"
                        id="in_stock">
                    @error('in_stock')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div> --}}

                <div class="col-md-4">
                    <label>Maintenance Categories</label>
                    <div class="border p-2" style="max-height: 150px; overflow-y: auto;">
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    value="{{ $category->id }}" id="category_{{ $category->id }}"
                                    {{ in_array($category->id, old('categories', $item->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('categories')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                        <i class="bi bi-save me-1"></i> Update Item
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
