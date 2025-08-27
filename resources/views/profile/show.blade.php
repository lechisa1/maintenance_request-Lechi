@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'Ict_director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))

@section('title', 'My Profile')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white d-flex align-items-center">
                        <img src="{{ $user->avatar_url ?? 'https://static.vecteezy.com/system/resources/previews/006/487/917/original/man-avatar-icon-free-vector.jpg' }}"
                            alt="{{ $user->name }}" class="rounded-circle me-3"
                            style="width:60px; height:60px; object-fit:cover; border: 2px solid white;">
                        <h4 class="mb-0">User Profile</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3">{{ $user->name }}</h5>
                        <p><i class="bi bi-envelope-fill me-2 text-primary"></i> <strong>Email:</strong> {{ $user->email }}
                        </p>
                        <p><i class="bi bi-phone-fill me-2 text-primary"></i> <strong>Phone:</strong>
                            {{ $user->phone ?? '-' }}</p>
                        <p><i class="bi bi-building me-2 text-primary"></i> <strong>Department:</strong>
                            {{ $user->department ? $user->department->name : '-' }}</p>
                        <p><i class="bi bi-gear-fill me-2 text-primary"></i> <strong>Specialization:</strong>
                            {{ $user->specialization ?? '-' }}</p>
                        <p><i class="bi bi-person-badge-fill me-2 text-primary"></i> <strong>Roles:</strong>
                            @if ($user->roles->count())
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-secondary me-1">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </p>

                        <div class="mt-4 d-flex justify-content-between">
                            {{-- <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profile
                            </a> --}}
                            {{-- <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Dashboard
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4 shadow-sm">
    <div class="card-header bg-secondary text-white col-6">
        <h5 class="mb-0">Update Profile Picture</h5>
    </div>
    <div class="card-body col-6">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->has('profile_image'))
            <div class="alert alert-danger">
                {{ $errors->first('profile_image') }}
            </div>
        @endif

        <form action="{{ route('profile.image.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="profile_image" class="form-label">Choose a new profile image:</label>
                <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload me-1"></i> Upload Image
            </button>
        </form>
    </div>
</div>

    </div>
@endsection
