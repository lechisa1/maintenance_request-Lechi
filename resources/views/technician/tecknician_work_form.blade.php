
@extends('technician.dashboard.layout')
@section('title', 'My Assigned Requests')

@section('content')
    <div class="card-body">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-center">Update Work Progress </h5>
                <span class="badge bg-{{ $request->priority === 'high' ? 'danger' : 'warning' }}">
                    {{ ucfirst($request->priority) }} Priority
                </span>
            </div>
        </div>
    </div>

    <div class="card-body"style="margin-top:3%">
        <form method="POST" action="{{ route('tecknician_work_save', $request->id) }}">
            @csrf
            <div class="row">
                <div class="col-4">
                    <label class="form-label">Materials Used</label>
                    <input type="text" class="form-control @error('materials_used') is-invalid @enderror"
                        name="materials_used" value="{{ old('materials_used') }}" placeholder="e.g., Paint, Screws, etc.">
                    @error('materials_used')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-4">
                    <label class="form-label">Time Spent (minutes)*</label>
                    <input type="number" class="form-control @error('time_spent_minutes') is-invalid @enderror"
                        name="time_spent_minutes" value="{{ old('time_spent_minutes') }}" min="1" required>
                    @error('time_spent_minutes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-4">
                    <label class="form-label">Update Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                            Mark as Completed
                        </option>
                        <option value="not_fixed" {{ old('status') == 'not_fixed' ? 'selected' : '' }}>
                            Not Fixed
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6 mt-4">
                    <label class="form-label">Work Performed</label>
                    <textarea class="form-control @error('work_done') is-invalid @enderror" name="work_done" rows="3" required
                        placeholder="Describe in detail what work you performed">{{ old('work_done') }}</textarea>
                    @error('work_done')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>
                <div class="col-6 mt-4">
                    <label class="form-label">Summary</label>
                    <textarea class="form-control @error('completion_notes') is-invalid @enderror" name="completion_notes"
                        rows="3"placeholder="Any additional notes about the completion">{{ old('completion_notes') }}</textarea>
                    @error('completion_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>



                <div class="row mt-5">
                    <div class="col-4 ">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Work Progress
                        </button>
                    </div>
                    <div class="col-4 m-xl-auto">
                        <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>


    @push('scripts')
        <script>
            // Dynamic form behavior based on status selection
            document.querySelector('select[name="status"]').addEventListener('change', function() {
                if (this.value === 'completed') {
                    // You could add additional dynamic behavior here
                    console.log('Status changed to completed');
                }
            });
        </script>
    @endpush
@endsection
