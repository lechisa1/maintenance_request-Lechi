@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : 'employeers.dashboard.layout')))
@section('title', 'Request #' . $request->id)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Main Request Card -->
                <div class="card shadow-lg border-0 rounded-4 mb-4">
                    <div
                        class="card-header bg-gradient-primary bg-opacity-10 border-bottom d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h3 class="mb-0 text-primary">
                                <i class="fas fa-ticket-alt me-2"></i>Requests Deatils
                            </h3>
                            <p class="text-muted mb-0">{{ $request->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <span
                                class="badge rounded-pill bg-{{ $request->priority == 'high' ? 'danger' : ($request->priority == 'medium' ? 'warning' : 'success') }} py-2 px-3">
                                <i
                                    class="fas fa-{{ $request->priority == 'high' ? 'exclamation-triangle' : ($request->priority == 'medium' ? 'exclamation-circle' : 'check-circle') }} me-1"></i>
                                {{ ucfirst($request->priority) }} Priority
                            </span>
                            <span
                                class="badge rounded-pill bg-{{ $request->status == 'completed' ? 'success' : ($request->status == 'in_progress' ? 'info' : 'warning') }} py-2 px-3 ms-2">
                                <i
                                    class="fas fa-{{ $request->status == 'completed' ? 'check-circle' : ($request->status == 'in_progress' ? 'spinner fa-spin' : 'clock') }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Request Details -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded-3 p-3 me-3">
                                        <i class="fas fa-box text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Item Name</h6>
                                        <p class="mb-0 fs-5 fw-semibold">{{ $request->item ? $request->item->name : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded-3 p-3 me-3">
                                        <i class="fas fa-user text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Requester</h6>
                                        <p class="mb-0 fs-5 fw-semibold">{{ $request->user->name }}</p>
                                        <p class="mb-0 text-muted small">{{ $request->user->department->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded-3 p-3 me-3">
                                        <i class="fas fa-phone text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Contact</h6>
                                        <p class="mb-0 fs-5 fw-semibold">{{ $request->user->phone }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($request->categories->count())
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-tags text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Issue Categories</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($request->categories as $category)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-3 p-3 me-3">
                                    <i class="fas fa-align-left text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 text-primary">Description</h5>
                            </div>
                            <div class="card bg-light border-0 rounded-3 p-3">
                                <p class="mb-0">{{ $request->description }}</p>
                            </div>
                        </div>

                        <!-- Attachments -->
                        @if ($request->attachments && $request->attachments->isNotEmpty())
                            @php
                                $requesterId = $request->user_id;
                                $ownAttachments = $request->attachments->filter(function ($file) use ($requesterId) {
                                    return $file->user_id == $requesterId;
                                });
                            @endphp

                            @if ($ownAttachments && $ownAttachments->isNotEmpty())
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-paperclip text-primary fs-4"></i>
                                        </div>
                                        <h5 class="mb-0 text-primary">Attachments</h5>
                                    </div>
                                    <div class="row g-3">
                                        @foreach ($ownAttachments as $file)
                                            <div class="col-md-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-file-alt text-muted fs-1 mb-3"></i>
                                                        <h6 class="text-truncate">{{ $file->original_name }}</h6>

                                                        <div class="d-flex justify-content-center gap-2 mt-2">
                                                            <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                                                class="btn btn-sm btn-outline-secondary rounded-pill preview-btn"
                                                                data-url="{{ Storage::url($file->file_path) }}"
                                                                data-type="{{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}">
                                                                <i class="fas fa-eye me-1"></i> Preview
                                                            </a>

                                                            <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                                <i class="fas fa-download me-1"></i> Download
                                                            </a>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if (auth()->user()->hasRole('technician') || auth()->user()->hasRole('director'))
                            @if ($request->status === 'assigned')
                                <div class="text-center mt-4">
                                    <a href="{{ route('tecknician_work_form', $request->id) }}"
                                        class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                                        <i class="fas fa-plus-circle me-2"></i> Add Progress Update
                                    </a>
                                </div>
                            @endif
                        @endif
                        <!-- Rejection Notice -->
                        @if ($request->user_feedback === 'rejected')
                            <div class="alert alert-danger rounded-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                                    <div>
                                        <h5 class="alert-heading mb-2">Requester Rejected Your Work</h5>
                                        <p class="mb-0"><strong>Rejection Reason:</strong>
                                            {{ $request->rejection_reason }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Work Logs Section -->
                @if ((auth()->user()->hasRole('technician') || auth()->user()->hasRole('director')) && $request->workLogs->count())
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        <div class="card-header bg-gradient-primary bg-opacity-10 border-bottom py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-3 p-3 me-3">
                                    <i class="fas fa-clipboard-list text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 text-primary"> Activities Performed</h5>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (auth()->user()->hasRole('technician') || auth()->user()->hasRole('director'))
                                @forelse($request->workLogs as $log)
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
                                @empty
                                    <div class="text-center py-5">
                                        <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                            <i class="fas fa-clipboard-question text-muted fs-1"></i>
                                        </div>
                                        <h5 class="text-muted">No maintenance activities yet</h5>
                                    </div>
                                @endforelse
                            @endif
                            {{-- @if ($request->status === 'assigned')
                                <div class="text-center mt-4">
                                    <a href="{{ route('tecknician_work_form', $request->id) }}"
                                        class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                                        <i class="fas fa-plus-circle me-2"></i> Add Progress Update
                                    </a>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Status Timeline Card -->
                <div class="card shadow-lg border-0 rounded-4 mb-4">
                    <div class="card-header bg-gradient-primary bg-opacity-10 border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-3 p-3 me-3">
                                <i class="fas fa-history text-primary fs-4"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Request Timeline</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="timeline">
                            <!-- Request Created -->
                            <div class="timeline-item mb-4">
                                <div class="timeline-badge bg-primary"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1 fw-semibold">Request Created</h6>
                                        <small
                                            class="text-muted">{{ $request->created_at->format('M j, Y g:i A') }}</small>
                                    </div>
                                    <p class="text-muted small mb-0">Request was submitted by {{ $request->user->name }}
                                    </p>
                                </div>
                            </div>
                            {{-- here if request rejected by supervisor --}}
                            @if ($request->rejection_reason)
                                <div class="timeline-item mb-4">
                                    <div class="timeline-badge bg-info"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1 fw-semibold">Rejected By </h6>
                                            <small
                                                class="text-muted">{{ $request->rejectedBy ? $request->rejectedBy->name : '' }}</small>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            Reason {{ $request->rejection_reason }}
                                        </p>
                                    </div>
                                </div>
                            @endif


                            <!-- Assigned -->
                            @if ($request->latestAssignment)
                                <div class="timeline-item mb-4">
                                    <div class="timeline-badge bg-info"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1 fw-semibold">Assigned to Technician</h6>
                                            <small
                                                class="text-muted">{{ $request->latestAssignment->assigned_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            Assigned by {{ $request->latestAssignment->director->name }} to
                                            {{ $request->latestAssignment->technician->name }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- In Progress -->
                            @if ($request->status === 'in_progress')
                                <div class="timeline-item mb-4">
                                    <div class="timeline-badge bg-warning"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1 fw-semibold">Work Started</h6>
                                            <small
                                                class="text-muted">{{ $request->created_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                        <p class="text-muted small mb-0">Maintenance work is in progress</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Completed -->
                            @if ($request->status === 'completed' && $request->completed_at)
                                <div class="timeline-item">
                                    <div class="timeline-badge bg-success"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1 fw-semibold">Request Completed</h6>
                                            <small
                                                class="text-muted">{{ $request->completed_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                        <p class="text-muted small mb-0">Request was successfully completed</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assignment Details Card -->
                @if ($request->latestAssignment)
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        <div class="card-header bg-gradient-primary bg-opacity-10 border-bottom py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-3 p-3 me-3">
                                    <i class="fas fa-tasks text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 text-primary">Assignment Details</h5>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-user-cog text-info fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Assigned Technician</h6>
                                            <p class="mb-0 fs-5 fw-semibold">
                                                {{ $request->latestAssignment->technician->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-3 p-3 me-3">
                                            <i class="fas fa-user-tie text-warning fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Assigned By</h6>
                                            <p class="mb-0 fs-5 fw-semibold">
                                                {{ $request->latestAssignment->director->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @if (auth()->user()->hasRole('technician') || auth()->user()->hasRole('director'))
                                    @if ($request->latestAssignment->director_notes)
                                        <div class="col-12">
                                            <div class="card bg-light border-0 rounded-3 p-3">
                                                <h6 class="text-primary mb-2">Director Remarks</h6>
                                                <p class="mb-0">{{ $request->latestAssignment->director_notes }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
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

        .timeline-badge {
            position: absolute;
            left: -1rem;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            transform: translateX(-50%);
        }

        .timeline-content {
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: -1rem;
            top: 1.5rem;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #e9ecef, transparent);
            transform: translateX(-50%);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
        }

        .bg-gradient-primary.bg-opacity-10 {
            background: linear-gradient(135deg, rgba(58, 123, 213, 0.1) 0%, rgba(0, 210, 255, 0.1) 100%);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.preview-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const fileUrl = this.getAttribute('data-url');
                    const fileType = this.getAttribute('data-type').toLowerCase();

                    const supportedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                    if (supportedTypes.includes(fileType)) {
                        window.open(fileUrl, '_blank');
                    } else {
                        alert(
                            'Preview not supported for this file type. Please download it instead.'
                        );
                    }
                });
            });
        });
    </script>

@endsection
