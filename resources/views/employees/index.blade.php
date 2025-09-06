{{-- resources/views/employees/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Employees - Office Management System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users me-2"></i>Employees</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Employee
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Employee List</h5>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="department_filter" class="form-label">Department</label>
                <select id="department_filter" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="job_title_filter" class="form-label">Job Title</label>
                <select id="job_title_filter" class="form-select">
                    <option value="">All Positions</option>
                    @foreach($jobTitles as $jobTitle)
                        <option value="{{ $jobTitle }}">{{ $jobTitle }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status_filter" class="form-label">Status</label>
                <select id="status_filter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button id="clear_filters" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </button>
            </div>
        </div>

        <!-- DataTable -->
        <div class="table-responsive">
            <table id="employees-table" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Supervisor</th>
                        <th>Salary</th>
                        <th>Hire Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#employees-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employees.data") }}',
            type: 'GET',
            data: function(d) {
                d.department_id = $('#department_filter').val();
                d.job_title = $('#job_title_filter').val();
                d.status = $('#status_filter').val();
            },
            error: function(xhr, error, code) {
                console.log('DataTables AJAX Error:', error);
                console.log('Response:', xhr.responseText);
            }
        },
        columns: [
            { 
                data: 'id', 
                name: 'id',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'full_name', 
                name: 'full_name',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'email', 
                name: 'email',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'job_title', 
                name: 'job_title',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'department_name', 
                name: 'department.name',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'supervisor_name', 
                name: 'supervisor.first_name', 
                orderable: false,
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'salary', 
                name: 'salary', 
                className: 'text-end',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'hire_date', 
                name: 'hire_date',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'location_string', 
                name: 'location_string', 
                orderable: false,
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'status', 
                name: 'status', 
                className: 'text-center',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false, 
                className: 'text-center',
                render: function(data, type, row) {
                    return data || '';
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search employees...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ employees",
            infoEmpty: "No employees found",
            infoFiltered: "(filtered from _MAX_ total employees)",
            emptyTable: "No employees available",
            loadingRecords: "Loading employees...",
            processing: "Processing...",
            zeroRecords: "No matching employees found"
        },
        responsive: true,
        stateSave: true
    });

    // Filter change handlers
    $('#department_filter, #job_title_filter, #status_filter').on('change', function() {
        table.draw();
    });

    // Clear filters
    $('#clear_filters').on('click', function() {
        $('#department_filter, #job_title_filter, #status_filter').val('');
        table.draw();
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endpush