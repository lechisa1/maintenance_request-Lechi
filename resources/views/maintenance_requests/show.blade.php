@php
    if (auth()->user()->hasRole('admin')) {
        $layout = 'admin.layout.app';
    } elseif (auth()->user()->hasRole('director')) {
        $layout = 'director.layout.layout';
    } elseif (auth()->user()->hasRole('technician')) {
        $layout = 'technician.dashboard.layout';
    } else {
        $layout = 'employeers.dashboard.layout';
    }
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <!-- Request Header Card -->
                <div class="card shadow-lg border-0 rounded-4 mb-4">
                    <div
                        class="card-header bg-gradient-primary bg-opacity-10 border-bottom d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h2 class="mb-0 text-primary">
                                <i class="fas fa-tools me-2"></i> Request Details
                            </h2>
                            <p class="text-muted mb-0">Requested {{ $maintenanceRequest->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <span
                                class="badge rounded-pill bg-{{ $maintenanceRequest->priority == 'high' ? 'danger' : ($maintenanceRequest->priority == 'medium' ? 'warning' : 'success') }} py-2 px-3">
                                <i
                                    class="fas fa-{{ $maintenanceRequest->priority == 'high' ? 'exclamation-triangle' : ($maintenanceRequest->priority == 'medium' ? 'exclamation-circle' : 'check-circle') }} me-1"></i>
                                {{ ucfirst($maintenanceRequest->priority) }} Priority
                            </span>
                            <span
                                class="badge rounded-pill bg-{{ $maintenanceRequest->status == 'completed' ? 'success' : ($maintenanceRequest->status == 'in_progress' ? 'info' : ($maintenanceRequest->status == 'rejected' ? 'danger' : 'warning')) }} py-2 px-3 ms-2">
                                <i
                                    class="fas fa-{{ $maintenanceRequest->status == 'completed' ? 'check-circle' : ($maintenanceRequest->status == 'in_progress' ? 'spinner fa-spin' : ($maintenanceRequest->status == 'rejected' ? 'times-circle' : 'clock')) }} me-1"></i>
                                {{ ucfirst($maintenanceRequest->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Request Details Section -->
                <div class="row g-4">
                    <!-- Request Information Card -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-primary bg-opacity-10 border-bottom d-flex align-items-center py-3">
                                <i class="fas fa-info-circle text-primary me-2 fs-4"></i>
                                <h5 class="mb-0 text-primary">Request Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                       
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Item Name</h6>
                                        <p class="mb-0 fw-semibold">
                                            {{ $maintenanceRequest->item ? $maintenanceRequest->item->name : 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="fas fa-align-left text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Description</h6>
                                        <p class="mb-0">{{ $maintenanceRequest->description }}</p>
                                    </div>
                                </div>

                                @if ($maintenanceRequest->categories->count())
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-light rounded-3 p-2 me-3">
                                            <i class="fas fa-tags text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Issue Categories</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($maintenanceRequest->categories as $category)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Attachments -->
                                {{-- Requester's Attachments --}}
                                @if (
                                    $maintenanceRequest->attachments &&
                                        $maintenanceRequest->attachments->where('user_id', $maintenanceRequest->user_id)->isNotEmpty())
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="bg-light rounded-3 p-2 me-3">
                                            <i class="fas fa-paperclip text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Requester's Attachments</h6>
                                            <div class="row g-2">
                                                @foreach ($maintenanceRequest->attachments->where('user_id', $maintenanceRequest->user_id) as $file)
                                                    @include('partials.attachment_card', ['file' => $file])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Supervisor's Letter --}}
                                @if (
                                    $maintenanceRequest->attachments &&
                                        $maintenanceRequest->attachments->where('user_id', '!=', $maintenanceRequest->user_id)->isNotEmpty())
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="bg-light rounded-3 p-2 me-3">
                                            <i class="fas fa-paperclip text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Supervisor's Letter</h6>
                                            <div class="row g-2">
                                                @foreach ($maintenanceRequest->attachments->where('user_id', '!=', $maintenanceRequest->user_id) as $file)
                                                    @include('partials.attachment_card', ['file' => $file])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>
                    </div>

                    <!-- Requester Information Card -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-primary bg-opacity-10 border-bottom d-flex align-items-center py-3">
                                <i class="fas fa-user text-primary me-2 fs-4"></i>
                                <h5 class="mb-0 text-primary">Requester Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Requester</h6>
                                        <p class="mb-0 fw-semibold">{{ $maintenanceRequest->user->name }}</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="fas fa-briefcase text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Position</h6>
                                        <p class="mb-0">{{ $maintenanceRequest->user->jobPosition->title }}</p>
                                    </div>
                                </div>
    <div class="d-flex align-items-start mb-3">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="bi bi-phone text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Phone</h6>
                                        <p class="mb-0">{{ $maintenanceRequest->user->phone }}</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Division</h6>
                                        <p class="mb-0">{{ $maintenanceRequest->user->department->name }}</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded-3 p-2 me-3">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Request Date</h6>
                                        <p class="mb-0">
                                            {{ $maintenanceRequest->created_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Logs Section -->
                @if (auth()->user()->hasRole('technician') || auth()->user()->hasRole('director'))
                @if ($maintenanceRequest->workLogs->isNotEmpty())
                    <div class="card shadow-lg border-0 rounded-4 mt-4">
                        <div
                            class="card-header bg-gradient-primary bg-opacity-10 border-bottom d-flex align-items-center py-3">
                            <i class="fas fa-clipboard-list text-primary me-2 fs-4"></i>
                            <h5 class="mb-0 text-primary">Technician Activities</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach ($maintenanceRequest->workLogs as $log)
                                    <div class="timeline-item mb-4 pb-4 border-bottom">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-light rounded-3 p-3 me-3">
                                                <i class="fas fa-user-cog text-primary fs-4"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="mb-0 fw-semibold">{{ $log->technician->name }}</h6>
                                                    <small
                                                        class="text-muted">{{ $log->created_at->format('M j, Y g:i A') }}</small>
                                                </div>

                                                <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                                                    <h6 class="text-primary mb-2">Work Performed</h6>
                                                    <p class="mb-0">{{ $log->work_done }}</p>
                                                </div>

                                                @if ($log->materials_used)
                                                    <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                                                        <h6 class="text-primary mb-2">Materials Used</h6>
                                                        <p class="mb-0">{{ $log->materials_used }}</p>
                                                    </div>
                                                @endif
                                                

                                                @if ($log->completion_notes)
                                                    <div class="card bg-light border-0 rounded-3 p-3">
                                                        <h6 class="text-primary mb-2">Completion Notes</h6>
                                                        <p class="mb-0">{{ $log->completion_notes }}</p>
                                                    </div>
                                                @endif
                                             
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @endif
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Assignment Details Card -->
                @if ($maintenanceRequest->assignments->isNotEmpty())
                    @php
                        $assignment = $maintenanceRequest->assignments()->latest()->first();
                    @endphp
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        <div
                            class="card-header bg-gradient-success bg-opacity-10 border-bottom d-flex align-items-center py-3">
                            <i class="fas fa-tasks text-success me-2 fs-4"></i>
                            <h5 class="mb-0 text-success">Assignment Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-user-cog text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Assigned Technician(s)</h6>
                                            <p class="mb-0 fw-semibold">
                                                {{ $maintenanceRequest->assignments->pluck('technician.name')->join(', ') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-user-tie text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Assigned By</h6>
                                            <p class="mb-0 fw-semibold">{{ $assignment->director->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-calendar-check text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Assignment Date</h6>
                                            <p class="mb-0">{{ $assignment->created_at->format('M j, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- @if ($assignment->expected_completion_date)
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-3 p-3 me-3">
                                    <i class="fas fa-clock text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Expected Completion</h6>
                                    <p class="mb-0 {{ now() > $assignment->expected_completion_date && $maintenanceRequest->status != 'completed' ? 'text-danger' : '' }}">
                                        {{ $assignment->expected_completion_date->format('M j, Y \a\t g:i A') }}
                                        @if (now() > $assignment->expected_completion_date && $maintenanceRequest->status != 'completed')
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif --}}
@if (auth()->user()->hasRole('technician') || auth()->user()->hasRole('director'))
                                @if ($assignment->director_notes)
                                    <div class="col-12">
                                        <div class="card bg-light border-0 rounded-3 p-3">
                                            <h6 class="text-success mb-2">Director Notes</h6>
                                            <p class="mb-0">{{ $assignment->director_notes }}</p>
                                        </div>
                                    </div>
                                @endif
@endif
                                @if ($maintenanceRequest->completed_at)
                                    <div class="col-12">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-light rounded-3 p-3 me-3">
                                                <i class="fas fa-check-circle text-success fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-muted mb-1">Completed At</h6>
                                                <p class="mb-0">
                                                    {{ $maintenanceRequest->completed_at->format('M j, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Rejection Details Card -->
                @if (
                    $maintenanceRequest->status === 'rejected' &&
                        (auth()->user()->id === $maintenanceRequest->user_id ||
                            auth()->user()->id === $maintenanceRequest->rejected_by))
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        <div
                            class="card-header bg-gradient-danger bg-opacity-10 border-bottom d-flex align-items-center py-3">
                            <i class="fas fa-times-circle text-danger me-2 fs-4"></i>
                            <h5 class="mb-0 text-danger">Rejection Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-user-slash text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Rejected By</h6>
                                            <p class="mb-0 fw-semibold">{{ $maintenanceRequest->rejectedBy->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card bg-light border-0 rounded-3 p-3">
                                        <h6 class="text-danger mb-2">Rejection Reason</h6>
                                        <p class="mb-0">{{ $maintenanceRequest->rejection_reason }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Button for Director -->
                @if ($maintenanceRequest->status === 'pending' && auth()->user()->hasRole('director'))
                    <div class="d-grid">
                        <a href="{{ route('requests.showAssignForm', $maintenanceRequest->id) }}"
                            class="btn btn-primary btn-lg rounded-pill shadow-sm py-3">
                            <i class="fas fa-user-plus me-2"></i> Assign Technician
                        </a>
                    </div>
                @endif
                 @if ($maintenanceRequest->status === 'not_fixed' && auth()->user()->hasRole('director'))
                    <div class="d-grid">
                        <a href="{{ route('requests.showAssignForm', $maintenanceRequest->id) }}"
                            class="btn btn-primary btn-lg rounded-pill shadow-sm py-3">
                            <i class="fas fa-user-plus me-2"></i> Re assign Technician
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
        }

        .bg-gradient-primary.bg-opacity-10 {
            background: linear-gradient(135deg, rgba(58, 123, 213, 0.1) 0%, rgba(0, 210, 255, 0.1) 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
        }

        .bg-gradient-success.bg-opacity-10 {
            background: linear-gradient(135deg, rgba(0, 176, 155, 0.1) 0%, rgba(150, 201, 61, 0.1) 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        }

        .bg-gradient-danger.bg-opacity-10 {
            background: linear-gradient(135deg, rgba(255, 65, 108, 0.1) 0%, rgba(255, 75, 43, 0.1) 100%);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.preview-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const fileUrl = this.getAttribute('data-url');
                    const fileType = this.getAttribute('data-type').toLowerCase();

                    // Supported preview types
                    const supportedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                    if (supportedTypes.includes(fileType)) {
                        window.open(fileUrl, '_blank'); // open in new tab
                    } else {
                        alert(
                            'Preview not supported for this file type. Please download it instead.');
                    }
                });
            });
        });
    </script>

@endsection
