@extends('admin.dashboard')
@section('content')
    <div class="container">
        <div class="card-body">
            <form id="projectForm">
                <div class="row">
                    <div class="col-6">
                        <label for="projectName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="projectName" name="role">
                    </div>
                    <div class="col-6">
                        <label for="projectBudget" class="form-label"> Role Descrioption</label>
                        <input type="number" class="form-control" id="projectBudget"name="description" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveProjectBtn">Save Project</button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@endsection
