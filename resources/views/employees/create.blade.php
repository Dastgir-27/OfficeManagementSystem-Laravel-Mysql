{{-- resources/views/employees/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Add Employee - Office Management System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-plus me-2"></i>Add New Employee</h1>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Employee Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Personal Information</h6>
                    
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                               id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                               id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Work Information -->
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Work Information</h6>
                    
                    <div class="mb-3">
                        <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('job_title') is-invalid @enderror" 
                               id="job_title" name="job_title" value="{{ old('job_title') }}" required>
                        @error('job_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-select @error('department_id') is-invalid @enderror" 
                                id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="supervisor_id" class="form-label">Supervisor</label>
                        <select class="form-select @error('supervisor_id') is-invalid @enderror" 
                                id="supervisor_id" name="supervisor_id">
                            <option value="">No Supervisor</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->full_name }} - {{ $supervisor->job_title }}
                                </option>
                            @endforeach
                        </select>
                        @error('supervisor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('salary') is-invalid @enderror" 
                                   id="salary" name="salary" value="{{ old('salary') }}">
                        </div>
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="hire_date" class="form-label">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                               id="hire_date" name="hire_date" value="{{ old('hire_date') }}" required>
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Location Information -->
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="text-muted mb-3">Location Information</h6>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select @error('location.country') is-invalid @enderror" 
                                id="country" name="location[country]">
                            <option value="">Select Country</option>
                        </select>
                        @error('location.country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select @error('location.state') is-invalid @enderror" 
                                id="state" name="location[state]" disabled>
                            <option value="">Select State</option>
                        </select>
                        @error('location.state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select @error('location.city') is-invalid @enderror" 
                                id="city" name="location[city]" disabled>
                            <option value="">Select City</option>
                        </select>
                        @error('location.city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save me-2"></i>Save Employee
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Location selection script loaded');
    
    // Check if jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    // Load countries on page load
    loadCountries();
    
    // Country change handler
    $('#country').on('change', function() {
        var country = $(this).val();
        console.log('Country selected:', country);
        
        $('#state').prop('disabled', true).html('<option value="">Select State</option>');
        $('#city').prop('disabled', true).html('<option value="">Select City</option>');
        
        if (country) {
            loadStates(country);
        }
    });
    
    // State change handler
    $('#state').on('change', function() {
        var country = $('#country').val();
        var state = $(this).val();
        console.log('State selected:', state, 'for country:', country);
        
        $('#city').prop('disabled', true).html('<option value="">Select City</option>');
        
        if (country && state) {
            loadCities(country, state);
        }
    });
    
    function loadCountries() {
        console.log('Loading countries...');
        
        // Show loading state
        $('#country').html('<option value="">Loading countries...</option>').prop('disabled', true);
        
        $.ajax({
            url: '/api/countries',
            method: 'GET',
            timeout: 10000, // 10 second timeout
            success: function(data) {
                console.log('Countries loaded successfully:', data);
                
                if (!Array.isArray(data) || data.length === 0) {
                    console.error('Invalid countries data received:', data);
                    $('#country').html('<option value="">Error loading countries</option>');
                    return;
                }
                
                var options = '<option value="">Select Country</option>';
                data.forEach(function(country) {
                    if (country && typeof country === 'string') {
                        options += '<option value="' + escapeHtml(country) + '">' + escapeHtml(country) + '</option>';
                    }
                });
                
                $('#country').html(options).prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('Failed to load countries:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                
                $('#country').html('<option value="">Error loading countries</option>').prop('disabled', false);
                
                // Show user-friendly error message
                showErrorMessage('Failed to load countries. Please refresh the page.');
            }
        });
    }
    
    function loadStates(country) {
        console.log('Loading states for country:', country);
        
        // Show loading state
        $('#state').html('<option value="">Loading states...</option>').prop('disabled', true);
        
        $.ajax({
            url: '/api/states/' + encodeURIComponent(country),
            method: 'GET',
            timeout: 10000,
            success: function(data) {
                console.log('States loaded successfully for', country, ':', data);
                
                if (!Array.isArray(data)) {
                    console.error('Invalid states data received:', data);
                    data = [];
                }
                
                var options = '<option value="">Select State</option>';
                data.forEach(function(state) {
                    if (state && typeof state === 'string') {
                        options += '<option value="' + escapeHtml(state) + '">' + escapeHtml(state) + '</option>';
                    }
                });
                
                $('#state').html(options).prop('disabled', false);
                
                if (data.length === 0) {
                    console.log('No states found for', country);
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to load states for', country, ':', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
                
                $('#state').html('<option value="">Error loading states</option>').prop('disabled', false);
                showErrorMessage('Failed to load states for ' + country);
            }
        });
    }
    
    function loadCities(country, state) {
        console.log('Loading cities for country:', country, 'state:', state);
        
        // Show loading state
        $('#city').html('<option value="">Loading cities...</option>').prop('disabled', true);
        
        $.ajax({
            url: '/api/cities/' + encodeURIComponent(country) + '/' + encodeURIComponent(state),
            method: 'GET',
            timeout: 10000,
            success: function(data) {
                console.log('Cities loaded successfully for', country, state, ':', data);
                
                if (!Array.isArray(data)) {
                    console.error('Invalid cities data received:', data);
                    data = [];
                }
                
                var options = '<option value="">Select City</option>';
                data.forEach(function(city) {
                    if (city && typeof city === 'string') {
                        options += '<option value="' + escapeHtml(city) + '">' + escapeHtml(city) + '</option>';
                    }
                });
                
                $('#city').html(options).prop('disabled', false);
                
                if (data.length === 0) {
                    console.log('No cities found for', country, state);
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to load cities for', country, state, ':', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
                
                $('#city').html('<option value="">Error loading cities</option>').prop('disabled', false);
                showErrorMessage('Failed to load cities for ' + state + ', ' + country);
            }
        });
    }
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    // Helper function to show error messages
    function showErrorMessage(message) {
        // You can customize this to show errors in your preferred way
        console.error(message);
        
        // Example: Show a toast notification if you have one
        // toastr.error(message);
        
        // Or show an alert (not recommended for production)
        // alert(message);
    }
});

// Test function you can call from browser console
function testLocationAPI() {
    console.log('Testing location API endpoints...');
    
    // Test countries
    fetch('/api/countries')
        .then(response => {
            console.log('Countries response status:', response.status);
            return response.json();
        })
        .then(data => console.log('Countries data:', data))
        .catch(error => console.error('Countries error:', error));
}
</script>
@endpush