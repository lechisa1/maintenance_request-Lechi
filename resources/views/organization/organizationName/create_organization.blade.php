@extends('admin.layout.app')

@section('content')
<div class="container py-5">
    <h3>Add New Organization</h3>
    <form action="{{ route('organization.name.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Organization Name</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Organization</button>
    </form>
</div>
@endsection
