@extends('admin.layout.app')

@section('content')
<div class="container py-4 card bg-white mt-4">
    <h3>Add New Organization</h3>
    <form action="{{ route('organization.name.store') }}" method="POST">
        @csrf
<div class="row">
            <div class="mb-3 ">
            <label for="name" class="form-label">Organization Name</label>
            <input type="text" class="form-control w-auto" name="name" id="name" required>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save Organization</button>
        </div>

</div>
    </form>
</div>
@endsection
