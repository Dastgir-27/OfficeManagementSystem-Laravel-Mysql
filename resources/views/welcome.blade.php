{{-- resources/views/welcome.blade.php (Dashboard/Homepage) --}}
@extends('layouts.app')

@section('title', 'Dashboard - Office Management System')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-4">
            <i class="fas fa-tachometer-alt me-3"></i>Dashboard
        </h1>
        <p class="lead text-muted">Welcome to your Office Management System</p>
    </div>
</div>

<div class="row mb-4">
    <!-- Total Employees -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">{{ \App\Models\Employee::count() }}</h2>
                        <p class="mb-0">Total Employees</p>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Employees -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">{{ \App\Models\Employee::where('status', 'active')->count() }}</h2>
                        <p class="mb-0">Active Employees</p>
                    </div>
                    <i class="fas fa-user-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Departments -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">{{ \App\Models\Department::count() }}</h2>
                        <p class="mb-0">Departments</p>
                    </div>
                    <i class="fas fa-sitemap fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Hires This Month -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">{{ \App\Models\Employee::whereMonth('hire_date', now()->month)->whereYear('hire_date', now()->year)->count() }}</h2>
                        <p class="mb-0">New Hires</p>
                    </div>
                    <i class="fas fa-user-plus fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Employees -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Employees
                </h5>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @php
                    $recentEmployees = \App\Models\Employee::with('department')->latest()->limit(5)->get();
                @endphp
                
                @if($recentEmployees->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentEmployees as $employee)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('employees.show', $employee) }}" class="text-decoration-none">
                                            {{ $employee->full_name }}
                                        </a>
                                    </h6>
                                    <p class="mb-1 text-muted">{{ $employee->job_title }}</p>
                                    <small class="text-muted">{{ $employee->department->name ?? 'No Department' }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $employee->status == 'active' ? 'success' : ($employee->status == 'inactive' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $employee->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No employees found</p>
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">Add First Employee</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Department Overview -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Department Overview
                </h5>
                <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @php
                    $departments = \App\Models\Department::withCount('employees')->get();
                @endphp
                
                @if($departments->count() > 0)
                    @foreach($departments as $department)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('departments.show', $department) }}" class="text-decoration-none">
                                        {{ $department->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $department->employees_count }} employees</small>
                            </div>
                            <div class="progress" style="width: 100px; height: 8px;">
                                @php
                                    $maxEmployees = $departments->max('employees_count') ?: 1;
                                    $percentage = ($department->employees_count / $maxEmployees) * 100;
                                @endphp
                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-sitemap fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No departments found</p>
                        <a href="{{ route('departments.create') }}" class="btn btn-primary">Create Department</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('employees.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Add Employee
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('departments.create') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-plus me-2"></i>Create Department
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-search me-2"></i>Search Employees
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('departments.index') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-chart-bar me-2"></i>View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add some interactive elements
    $('.card').hover(
        function() { $(this).addClass('shadow-lg').removeClass('shadow'); },
        function() { $(this).removeClass('shadow-lg').addClass('shadow'); }
    );
});
</script>
@endpush