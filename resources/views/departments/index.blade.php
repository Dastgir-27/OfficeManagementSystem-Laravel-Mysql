{{-- resources/views/departments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Departments - Office Management System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-sitemap me-2"></i>Departments</h1>
    <a href="{{ route('departments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Department
    </a>
</div>

<div class="row">
    @foreach($departments as $department)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $department->name }}</h5>
                    <span class="badge bg-primary">{{ $department->employees_count }} employees</span>
                </div>
                <div class="card-body">
                    @if($department->description)
                        <p class="card-text">{{ Str::limit($department->description, 100) }}</p>
                    @endif
                    
                    @if($department->budget)
                        <p class="card-text">
                            <strong>Budget:</strong> ${{ number_format($department->budget, 2) }}
                        </p>
                    @endif
                    
                    @if($department->location)
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $department->location }}
                        </p>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('departments.show', $department) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                        <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        @if($department->employees_count == 0)
                            <form method="POST" action="{{ route('departments.destroy', $department) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this department?')">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        @else
                            <button class="btn btn-danger btn-sm" disabled title="Cannot delete department with employees">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if($departments->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
        <h4>No Departments Found</h4>
        <p class="text-muted">Get started by adding your first department.</p>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Department
        </a>
    </div>
@endif
@endsection