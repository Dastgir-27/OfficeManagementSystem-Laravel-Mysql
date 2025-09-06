<?php
// app/Http/Controllers/EmployeeController.php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\EmployeeRequest;

class EmployeeController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $jobTitles = Employee::distinct()->orderBy('job_title')->pluck('job_title');
        
        return view('employees.index', compact('departments', 'jobTitles'));
    }

    public function data(Request $request)
    {
        $query = Employee::with(['department', 'supervisor'])
            ->select([
                'employees.*', // This ensures all employee fields including 'id' are selected
            ]);

        // Apply filters
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('job_title')) {
            $query->where('job_title', $request->job_title);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addColumn('full_name', function ($employee) {
                return $employee->full_name;
            })
            ->addColumn('department_name', function ($employee) {
                return $employee->department->name ?? 'N/A';
            })
            ->addColumn('supervisor_name', function ($employee) {
                return $employee->supervisor ? $employee->supervisor->full_name : 'N/A';
            })
            ->addColumn('location_string', function ($employee) {
                return $employee->location_string ?: 'N/A';
            })
            ->addColumn('actions', function ($employee) {
                return '
                    <div class="btn-group" role="group">
                        <a href="'.route('employees.show', $employee).'" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="'.route('employees.edit', $employee).'" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST" action="'.route('employees.destroy', $employee).'" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this employee?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                ';
            })
            ->editColumn('salary', function ($employee) {
                return $employee->salary ? '$' . number_format($employee->salary, 0) : 'N/A';
            })
            ->editColumn('hire_date', function ($employee) {
                return $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A';
            })
            ->editColumn('status', function ($employee) {
                $class = [
                    'active' => 'success',
                    'inactive' => 'warning', 
                    'terminated' => 'danger'
                ][$employee->status] ?? 'secondary';
                
                return "<span class='badge bg-{$class}'>".ucfirst($employee->status)."</span>";
            })
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(first_name,' ',last_name) like ?", ["%{$keyword}%"]);
            })
            ->rawColumns(['actions', 'status'])
            ->make(true);
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $supervisors = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('employees.create', compact('departments', 'supervisors'));
    }

    public function store(EmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'supervisor', 'subordinates']);
        
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $supervisors = Employee::where('status', 'active')
            ->where('id', '!=', $employee->id)
            ->orderBy('first_name')->get();
        
        return view('employees.edit', compact('employee', 'departments', 'supervisors'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Check if employee has subordinates
        if ($employee->subordinates()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete employee who has subordinates. Please reassign or remove subordinates first.');
        }
        
        $employee->delete();
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}