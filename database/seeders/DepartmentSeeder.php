<?php
// database/seeders/DepartmentSeeder.php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Human Resources', 'description' => 'Manages employee relations and policies', 'budget' => 150000.00],
            ['name' => 'Information Technology', 'description' => 'Manages company technology infrastructure', 'budget' => 300000.00],
            ['name' => 'Finance', 'description' => 'Handles financial planning and accounting', 'budget' => 200000.00],
            ['name' => 'Marketing', 'description' => 'Promotes company products and services', 'budget' => 250000.00],
            ['name' => 'Sales', 'description' => 'Drives revenue through client relationships', 'budget' => 400000.00],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}