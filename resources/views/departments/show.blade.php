{{-- resources/views/departments/show.blade.php --}}
@extends('layouts.app')

@section('title', $department->name . ' - Department Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-sitemap me-2"></i>{{ $department->name }}</h1>
    <div>
        <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Department Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Department Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold" style="width: 150px;">Name:</td>
                        <td>{{ $department->name }}</td>
                    </tr>
                    @if($department->description)
                        <tr>
                            <td class="fw-bold">Description:</td>
                            <td>{{ $department->description }}</td>
                        </tr>
                    @endif
                    @if($department->location)
                        <tr>
                            <td class="fw-bold">Location:</td>
                            <td><i class="fas fa-map-marker-alt me-2"></i>{{ $department->location }}</td>
                        </tr>
                    @endif
                    @if($department->budget)
                        <tr>
                            <td class="fw-bold">Annual Budget:</td>
                            <td>${{ number_format($department->budget, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="fw-bold">Created:</td>
                        <td>{{ $department->created_at->format('F j, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Department Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Employees</span>
                    <span class="badge bg-primary fs-6">{{ $department->employees->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Active Employees</span>
                    <span class="badge bg-success fs-6">{{ $department->employees->where('status', 'active')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Inactive/Terminated</span>
                    <span class="badge bg-warning fs-6">{{ $department->employees->whereIn('status', ['inactive', 'terminated'])->count() }}</span>
                </div>
                @if($department->budget)
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Budget per Employee</span>
                        <span class="badge bg-info fs-6">
                            ${{ $department->employees->count() > 0 ? number_format($department->budget / $department->employees->count(), 0) : number_format($department->budget, 0) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Employees in Department -->
@if($department->employees->count() > 0)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Department Employees ({{ $department->employees->count() }})</h5>
            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Add Employee
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Job Title</th>
                            <th>Email</th>
                            <th>Supervisor</th>
                            <th>Hire Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($department->employees->sortBy('first_name') as $employee)
                            <tr>
                                <td>
                                    <a href="{{ route('employees.show', $employee) }}" class="text-decoration-none">
                                        {{ $employee->full_name }}
                                    </a>
                                </td>
                                <td>{{ $employee->job_title }}</td>
                                <td>
                                    <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                                </td>
                                <td>
                                    @if($employee->supervisor)
                                        <a href="{{ route('employees.show', $employee->supervisor) }}" class="text-decoration-none">
                                            {{ $employee->supervisor->full_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>{{ $employee->hire_date->format('M j, Y') }}</td>
                                <td>
                                    @php
                                        $badgeClass = [
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            'terminated' => 'danger'
                                        ][$employee->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($employee->status) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5>No Employees in this Department</h5>
            <p class="text-muted">This department doesn't have any employees yet.</p>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add First Employee
            </a>
        </div>
    </div>
@endif
@endsection