@extends('technician.dashboard.layout')
@section('title', 'My Assigned Requests')

@section('content')
<div class="card shadow rounded-4 border-0 mt-5">
    <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
        <h4 class="text-primary fw-bold mb-0">Update Work Progress</h4>
        <span class="badge px-3 py-2 fs-6 bg-{{ $request->priority === 'high' ? 'danger' : 'warning text-dark' }}">
            {{ ucfirst($request->priority) }} Priority
        </span>
    </div>

    <div class="card-body px-4 py-5">
        <form method="POST" action="{{ route('tecknician_work_save', $request->id) }}">
            @csrf
            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Materials Used</label>
                    <input type="text" class="form-control shadow-sm @error('materials_used') is-invalid @enderror"
                        name="materials_used" value="{{ old('materials_used') }}"
                        placeholder="e.g., Paint, Screws, etc.">
                    @error('materials_used')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Time Spent (minutes)*</label>
                    <input type="number" step="0.01" min="0.01"
                        class="form-control shadow-sm @error('time_spent_minutes') is-invalid @enderror"
                        name="time_spent_minutes" value="{{ old('time_spent_minutes') }}" required>
                    @error('time_spent_minutes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select shadow-sm @error('status') is-invalid @enderror" name="status" required>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                        <option value="not_fixed" {{ old('status') == 'not_fixed' ? 'selected' : '' }}>
                            Not Fixed
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Work Performed</label>
                    <textarea class="form-control shadow-sm @error('work_done') is-invalid @enderror" name="work_done"
                        rows="4" placeholder="Describe in detail what work you performed" required>{{ old('work_done') }}</textarea>
                    @error('work_done')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Summary</label>
                    <textarea class="form-control shadow-sm @error('completion_notes') is-invalid @enderror"
                        name="completion_notes" rows="4"
                        placeholder="Any additional notes about the completion">{{ old('completion_notes') }}</textarea>
                    @error('completion_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex justify-content-between pt-4">
                    <button type="submit" class="btn btn-primary px-2 py-2">
                        <i class="fas fa-save me-2"></i> Save Progress
                    </button>
                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                </div>

            </div>
        </form>
    </div>
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
