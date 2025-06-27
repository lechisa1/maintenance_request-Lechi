@extends(Auth::user()->roles->first()->name === 'admin' ? 'admin.layout.app' : (Auth::user()->roles->first()->name === 'director' ? 'director.layout.layout' : (Auth::user()->roles->first()->name === 'technician' ? 'technician.dashboard.layout' : (Auth::user()->roles->first()->name === 'employer' ? 'employeers.dashboard.layout' : 'employeers.dashboard.layout'))))
@section('content')
    <div class="container mt-4">
        <!-- Header -->
        <h2 class="mb-4 text-center text-primary">🌟 Maintenance Request Details 🌟</h2>

        <div class="row">
            <!-- Request Details Card -->
            <div class="col-6">
                <div class="card shadow-sm h-100 border-primary">
                    <div class="card-header bg-primary text-white text-center">
                        📋 Request Information
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Item Name:</strong>
                                {{ $maintenanceRequest->item ? $maintenanceRequest->item->name : 'N/A' }}</li>
                            <li class="list-group-item"><strong>Description:</strong> {{ $maintenanceRequest->description }}
                            </li>
                            <li class="list-group-item"><strong>Department:</strong>
                                {{ $maintenanceRequest->user->department->name }}</li>
                            <li class="list-group-item"><strong>Priority:</strong>
                                <span class="badge bg-warning text-dark">{{ ucfirst($maintenanceRequest->priority) }}</span>
                            </li>
                            <li class="list-group-item"><strong>Status:</strong>
                                <span class="badge bg-info">{{ ucfirst($maintenanceRequest->status) }}</span>
                            </li>
                            @if ($maintenanceRequest->status === 'rejected')
                            <div class="alert alert-danger mt-3">
                                <strong>Rejection Reason:</strong> {{ $maintenanceRequest->rejection_reason }}
                            </div>
                        @endif
                            <li class="list-group-item"><strong>Requested By:</strong> {{ $maintenanceRequest->user->name }}
                            </li>
                            <li class="list-group-item"><strong>Requested At:</strong>
                                {{ $maintenanceRequest->created_at->format('Y-m-d H:i') }}</li>
                        </ul>
                        @if ($maintenanceRequest->attachments->count())
                            <div class="mb-4">
                                <h6>Attachments</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($maintenanceRequest->attachments as $file)
                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-paperclip me-1"></i> {{ $file->original_name }}
                                        </a>
                                        {{-- <a href="{{ route('attachments.download', $file->id) }}" class="btn btn-sm btn-outline-secondary"> --}}
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assignment Details Cards -->
            @if ($maintenanceRequest->assignments->isNotEmpty())
                @php
                    // Get the most recent assignment (latest one)
                    $assignment = $maintenanceRequest->assignments()->latest()->first();
                @endphp

                <div class="col-6">
                    <div class="card shadow-sm h-100 border-success">
                        <div class="card-header bg-success text-white text-center">
                            🛠️ Assignment Details
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Technician(s):</strong>
                                    <!-- Display a comma-separated list of technician names -->
                                    {{ $maintenanceRequest->assignments->pluck('technician.name')->join(', ') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Assigned By (Director):</strong>
                                    {{ $assignment->director->name ?? 'N/A' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Director Notes:</strong>
                                    {{ $assignment->director_notes ?? 'N/A' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Expected Completion:</strong>
                                    {{ $assignment->expected_completion_date ? $assignment->expected_completion_date->format('Y-m-d H:i') : 'N/A' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Assigned At:</strong>
                                    {{ $assignment->created_at->format('Y-m-d H:i') }}
                                </li>
                                {{-- If completed_at is available, show it --}}
                                @if ($maintenanceRequest->completed_at)
                                    <li class="list-group-item">
                                        <strong>Completed At:</strong>
                                        {{ $maintenanceRequest->completed_at->format('Y-m-d H:i') }}
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <strong>Status:</strong> Not completed yet
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-6 mt-5">
                <div class="card shadow-sm h-100 border-success">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0 text-center">Technician Activities</h5>
                    </div>

                    {{-- @if ($maintenanceRequest->completed_at)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Completed At:</strong>
                                {{ $maintenanceRequest->completed_at->format('Y-m-d H:i') }}</li>
                        @else
                            
                        </ul>
                    @endif --}}
                    @forelse($maintenanceRequest->workLogs as $log)
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">


                                <li class="list-group-item"><strong> Technician Name: </strong>

                                    {{ $log->technician->name }}
                                </li>


                                <li class="list-group-item"><strong>Activity Performed: </strong>{{ $log->work_done }}</li>
                                @if ($log->materials_used )
                                    <li class="list-group-item"><strong>Material used: </strong>{{ $log->materials_used }}
                                    </li>
                                @endif
                                @if ($log->completion_notes)
                                    <li class="list-group-item"><strong>Summary: </strong>{{ $log->completion_notes }}</li>
                                @endif
                            </ul>
                        </div>
                </div>
            @empty
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        No Activity Found
                    </li>
                    @endforelse
                </ul>
            </div>

        </div>
        @if ($maintenanceRequest->status === 'rejected')
            <div class="col-6" style="margin: 15px">
                <div class="card shadow-sm h-100 border-warning">
                    <div class="card-header bg-danger text-white text-center">
                        The Following are Rejection Details
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Rejected By:</strong>{{ $maintenanceRequest->rejectedBy->name }}
                            </li>
                            <li class="list-group-item"><strong>Rejection
                                    Reason:</strong>{{ $maintenanceRequest->rejection_reason }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <!-- Update Logs Card -->
@if($maintenanceRequest->status === 'pending')
    <div class="mt-5 text-center">
        <a href="{{ route('requests.showAssignForm', $maintenanceRequest->id) }}" class="btn btn-primary btn-lg text-decoration-none">
            Assign
        </a>
    </div>
@endif


    </div>
    </div>
@endsection
