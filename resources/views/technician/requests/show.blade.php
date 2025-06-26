@extends('technician.dashboard.layout')

@section('title', 'Request #' . $request->id)

@section('content')
    <div class="row">

        <div class="col-md-8">

            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-info">Request Details </h5>
                    <div>
                        <span
                            class="badge bg-{{ $request->priority == 'high' ? 'danger' : ($request->priority == 'medium' ? 'warning' : 'success') }}">
                            {{ ucfirst($request->priority) }} Priority
                        </span>
                        <span
                            class="badge bg-{{ $request->status == 'completed' ? 'success' : ($request->status == 'in_progress' ? 'info' : 'warning') }} ms-2">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Item
                            Name:<strong>{{ $request->item ? $request->item->name : 'N/A' }}</strong></li>
                        <li class="list-group-item">
                            Requester Name: <strong>{{ $request->user->name }}</strong></li>
                        <li class="list-group-item">Department:<strong>{{ $request->user->department->name }}</strong></li>
                        <li class="list-group-item">Requester Phone:<strong>{{ $request->user->phone }}</strong></li>




                        <li class="list-group-item">Description:
                            <strong>{{ $request->description }}</strong>
                        </li>


                        @if ($request->categories->count())

                            <li class="list-group-item">Reasons:

                                @foreach ($request->categories as $category)
                                    <span class="badge bg-secondary">{{ $category->name }}</span>
                                @endforeach

                            </li>

                        @endif

                        <!-- Attachments Section -->
                        @if ($request->attachments->count())
                            <div class="mb-4">
                                <h6>Attachments</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($request->attachments as $file)
                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-paperclip me-1"></i> {{ $file->original_name }}
                                        </a>
                                        {{-- <a href="{{ route('attachments.download', $file->id) }}" class="btn btn-sm btn-outline-secondary"> --}}
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($request->user_feedback === 'rejected')
                            <li class="list-group-item">Requester Reject Your Work.

                                Rejection Reason<strong>{{ $request->rejection_reason }}</strong></li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Work Logs Section -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-center text-warning">Activities</h5>
                </div>
                <div class="card-body">
                    @forelse($request->workLogs as $log)
                        <div class="work-log mb-3 border-bottom pb-3">
                            <ul class="list-group list-group-flush">


                                <li class="list-group-item">Technician: <strong>

                                        {{ $log->technician->name }}
                                    </strong></li>


                                <li class="list-group-item">Activity Performed:<strong>{{ $log->work_done }}</strong></li>
                                @if ($log->materials_used)
                                    <li class="list-group-item">Materials used:<strong>{{ $log->materials_used }}</strong>
                                    </li>
                                @endif

                                @if ($log->completion_notes)
                                    <li class="list-group-item">Summary:<strong>{{ $log->completion_notes }}</strong></li>
                                @endif
                            </ul>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            No maintenance work yet
                        </div>
                    @endforelse
                </div>
                
                @if($request->status === 'assigned')
    <div class="mt-5 text-center">
        <a href="{{ route('tecknician_work_form', $request->id) }}" class="btn btn-primary btn-lg text-decoration-none">
            Add Progress
        </a>
    </div>
@endif

            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">

            <!-- Request Status Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light d-flex align-items-center">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    <h5 class="mb-0 text-primary">Request Status</h5>
                </div>
                @php
                    $latestUpdate = $request->updates->sortByDesc('created_at')->first();
                @endphp

                @if ($latestUpdate)
                    <div class="card-body">
                        <ul class="list-group list-group-flush">

                            <li class="list-group-item">
                                Requested At:
                                <strong>{{ $request->created_at->format('M d, Y h:i A') }}</strong>
                                <span class="text-muted">({{ $request->created_at->diffForHumans() }})</span>
                            </li>

                            <li class="list-group-item">
                                Assigned On:
                                <strong>{{ $request->latestAssignment->assigned_at->format('M d, Y h:i A') }}</strong>
                                <span
                                    class="text-muted text-info">({{ $request->latestAssignment->assigned_at->diffForHumans() }})</span>
                            </li>

                            @if ($request->status === 'completed' && $request->completed_at)
                                <li class="list-group-item">
                                    Completed At:
                                    <strong>{{ $request->completed_at->format('M d, Y h:i A') }}</strong>
                                    <span class="text-muted">({{ $request->completed_at->diffForHumans() }})</span>
                                </li>
                            @endif

                            @if ($request->status === 'in_progress')
                                <li class="list-group-item">
                                    Started At:

                                    <strong>{{ $request->created_at->format('M d, Y h:i A') }}</strong>
                                    <span class="text-muted">({{ $request->created_at->diffForHumans() }})</span>
                                </li>
                            @endif

                        </ul>
                    </div>
                @endif
            </div>

            <!-- Assignment Details Card -->
             @if ($request->latestAssignment)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light d-flex align-items-center">
                        <i class="fas fa-tasks text-primary me-2"></i>
                        <h5 class="mb-0 text-primary">Assignment Details</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <span class="fw-semibold text-muted">Assigned To:</span>
                                <i class="fas fa-user-cog me-1 text-info"></i>
                                {{ $request->latestAssignment->technician->name }}
                            </li>
                            <li class="mb-3">
                                <span class="fw-semibold text-muted">Assigned By:</span>
                                <i class="fas fa-user-tie me-1 text-warning"></i>
                                {{ $request->latestAssignment->director->name }}
                            </li>
                            {{-- <li class="mb-3">
                                <span class="fw-semibold text-muted">Assigned On:</span><br>
                                <i class="fas fa-calendar-check me-1 text-secondary"></i>
                                {{ $request->latestAssignment->assigned_at->format('M d, Y h:i A') }}
                            </li> --}}
                            {{-- <li class="mb-3">
                                <span class="fw-semibold text-muted">Expected Completion:</span><br>
                                <i class="fas fa-clock me-1 text-danger"></i>
                                <span
                                    class="{{ now() > $request->latestAssignment->expected_completion_date && $request->status != 'completed' ? 'text-danger' : '' }}">
                                    {{ $request->latestAssignment->expected_completion_date}}
                                </span>
                                @if (now() > $request->latestAssignment->expected_completion_date && $request->status != 'completed')
                                    <span class="badge bg-danger ms-2">Overdue</span>
                                @endif
                            </li> --}}
                            @if ($request->latestAssignment->director_notes)
                                <li>
                                    <span class="fw-semibold text-muted">Director Instruction:</span>
                                    <p class="text-muted fst-italic border-start ps-2 mt-1">
                                        {{ $request->latestAssignment->director_notes }}
                                    </p>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <style>
        .request-description {
            white-space: pre-line;
        }

        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-badge {
            position: absolute;
            left: -20px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .timeline-content {
            padding-bottom: 15px;
        }

        .work-log {
            position: relative;
            padding-left: 15px;
        }

        .work-log::before {
            content: "";
            position: absolute;
            left: 0;
            top: 10px;
            height: calc(100% - 20px);
            width: 2px;
            background: #dee2e6;
        }
    </style>
@endsection
