{{-- resources/views/employees/show.blade.php --}}
@extends('layouts.app')

@section('title', $employee->full_name . ' - Employee Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user me-2"></i>{{ $employee->full_name }}</h1>
    <div>
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Employee Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Employee Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">First Name:</td>
                                <td>{{ $employee->first_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Last Name:</td>
                                <td>{{ $employee->last_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Phone:</td>
                                <td>{{ $employee->phone ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status:</td>
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
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Job Title:</td>
                                <td>{{ $employee->job_title }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Department:</td>
                                <td>
                                    <a href="{{ route('departments.show', $employee->department) }}">
                                        {{ $employee->department->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Supervisor:</td>
                                <td>
                                    @if($employee->supervisor)
                                        <a href="{{ route('employees.show', $employee->supervisor) }}">
                                            {{ $employee->supervisor->full_name }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Salary:</td>
                                <td>{{ $employee->salary ? '$' . number_format($employee->salary, 2) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Hire Date:</td>
                                <td>{{ $employee->hire_date->format('F j, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($employee->location)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted">Location</h6>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $employee->location_string }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Years of Service</span>
                    <span class="badge bg-primary">{{ $employee->hire_date->diffInYears(now()) }} years</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Direct Reports</span>
                    <span class="badge bg-info">{{ $employee->subordinates->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Department Size</span>
                    <span class="badge bg-secondary">{{ $employee->department->employees->count() }} people</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subordinates -->
@if($employee->subordinates->count() > 0)
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Direct Reports ({{ $employee->subordinates->count() }})</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($employee->subordinates as $subordinate)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-1">
                                    <a href="{{ route('employees.show', $subordinate) }}">
                                        {{ $subordinate->full_name }}
                                    </a>
                                </h6>
                                <p class="card-text text-muted small mb-1">{{ $subordinate->job_title }}</p>
                                <p class="card-text">
                                    <span class="badge bg-{{ $subordinate->status == 'active' ? 'success' : ($subordinate->status == 'inactive' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($subordinate->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection