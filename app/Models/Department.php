<?php
// app/Models/Department.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'budget',
        'location'
    ];

    protected $casts = [
        'budget' => 'decimal:2'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function activeEmployees()
    {
        return $this->hasMany(Employee::class)->where('status', 'active');
    }
}
