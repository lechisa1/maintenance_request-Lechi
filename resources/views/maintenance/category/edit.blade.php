@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="card p-4">
        <h4 class="mb-4 text-center text-danger-emphasis">Edit Category Issue</h4>

        <form action="{{ route('categories.update', $category->id) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-6">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label>Description</label>
                    <input type="text" name="description" value="{{ $category->description }}" class="form-control">
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Update Category
                </button>
            </div>

        </form>
    </div>
@endsection
