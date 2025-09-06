<?php
// app/Models/Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'job_title',
        'department_id',
        'supervisor_id',
        'salary',
        'hire_date',
        'location',
        'status'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hire_date' => 'date',
        'location' => 'array'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getLocationStringAttribute()
    {
        if (!$this->location) return '';
        
        $parts = array_filter([
            $this->location['city'] ?? '',
            $this->location['state'] ?? '',
            $this->location['country'] ?? ''
        ]);
        
        return implode(', ', $parts);
    }
}