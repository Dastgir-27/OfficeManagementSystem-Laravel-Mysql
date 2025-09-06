<?php
// app/Http/Requests/EmployeeRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $employeeId = $this->route('employee') ? $this->route('employee')->id : null;
        
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employeeId,
            'phone' => 'nullable|string|max:20',
            'job_title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'supervisor_id' => 'nullable|exists:employees,id',
            'salary' => 'nullable|numeric|min:0|max:999999.99',
            'hire_date' => 'required|date',
            'location.country' => 'nullable|string|max:255',
            'location.state' => 'nullable|string|max:255',
            'location.city' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,terminated'
        ];
    }
}