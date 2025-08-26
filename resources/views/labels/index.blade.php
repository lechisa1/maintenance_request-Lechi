@extends('admin.layout.app')
@section('content')
    <div class="container py-5 card bg-white mt-4 shadow-lg rounded-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="main-card">
                    <!-- Card Header -->
                    <div class="card-header text-center py-4 bg-white  rounded-top">
                        <h3 class="mb-2 fw-bold">
                            <i class="fas fa-tags me-2"></i> Manage Organization Labels
                        </h3>
                        <p class="mb-0 opacity-80">Customize naming conventions for your organizational hierarchy</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Hierarchy Visualization -->
                        <div class="hierarchy-visual">
                            <h6 class="mb-3 fw-bold text-secondary"><i class="fas fa-sitemap me-2"></i> Hierarchy Structure</h6>
                            <div class="d-flex flex-wrap align-items-center mt-4">
                                <div class="hierarchy-item">
                                    <span class="badge bg-primary text-white p-2 rounded-pill">{{ $label['organization'] }}</span>
                                </div>
                                <div class="hierarchy-item">
                                    <i class="fas fa-arrow-right hierarchy-arrow mx-2"></i>
                                    <span class="badge bg-info text-white p-2 rounded-pill">{{ $label['sector'] }}</span>
                                </div>
                                <div class="hierarchy-item">
                                    <i class="fas fa-arrow-right hierarchy-arrow mx-2"></i>
                                    <span class="badge bg-info text-white p-2 rounded-pill">{{ $label['division'] }}</span>
                                </div>
                                <div class="hierarchy-item">
                                    <i class="fas fa-arrow-right hierarchy-arrow mx-2"></i>
                                    <span class="badge bg-info text-white p-2 rounded-pill">{{ $label['department'] }}</span>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('labels.update') }}">
                            @csrf
                            <div class="row g-4 mt-5">
                                @foreach ($labels as $label)
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">
                                            {{ ucfirst($label->label) }} Label
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="labels[{{ $label->id }}]"
                                                   class="form-control form-control-lg"
                                                   value="{{ $label->label }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 text-end">
                                <button type="reset" class="btn btn-light px-4 py-2 me-2 rounded-3 shadow-sm">
                                    <i class="fas fa-undo me-2"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary px-2 py-2 rounded-3 shadow-sm">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Card Footer -->
                 <div class="card-footer text-center text-muted py-3 bg-light rounded-bottom">
    <small>
        <i class="fas fa-info-circle me-1"></i> Customize your hierarchy terms (e.g., {{ $label['sector'] ?? 'Sector' }} → {{ $label['division'] ?? 'Division' }} → {{ $label['department'] ?? 'Department' }})
    </small>
</div>

                </div>

            </div>
        </div>
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Input Field Animation
            const inputs = document.querySelectorAll('input.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.add('shadow-lg');
                });
                input.addEventListener('blur', function() {
                    this.classList.remove('shadow-lg');
                });
            });
            
            // Animate success message
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.add('animate__animated', 'animate__fadeInRight');
            }
        });
    </script>
@endsection
