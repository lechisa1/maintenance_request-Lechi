@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($role) ? route('update_role', $role->id) : route('roles.save') }}">
                        @csrf
                        @if(isset($role))
                            @method('PUT')
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $role->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="2">{{ old('description', $role->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <h4 class="form-label text-center">Assign Permissions <span class="text-danger">*</span></h4>
                            <hr/>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                   value="{{ $permission->name }}"
                                                   id="perm_{{ $permission->id }}"
                                                   {{ (isset($role) && $role->permissions->contains('name', $permission->name)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('roles_with_permission') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($role) ? 'Update Role' : 'Create Role' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
