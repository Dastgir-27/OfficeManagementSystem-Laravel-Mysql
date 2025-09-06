<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $departments = Department::all();
        
        if ($departments->isEmpty()) {
            $this->command->error('No departments found. Please run DepartmentSeeder first.');
            return;
        }

        $jobTitles = [
            'Software Engineer', 'Senior Software Engineer', 'Lead Developer', 'Product Manager',
            'UI/UX Designer', 'DevOps Engineer', 'Data Analyst', 'Business Analyst',
            'HR Manager', 'HR Specialist', 'Recruiter', 'Training Coordinator',
            'Financial Analyst', 'Accountant', 'Controller', 'Billing Specialist',
            'Marketing Manager', 'Marketing Specialist', 'Content Creator', 'SEO Specialist',
            'Sales Manager', 'Sales Representative', 'Account Executive', 'Customer Success Manager'
        ];

        $locations = [
            ['country' => 'United States', 'state' => 'California', 'city' => 'San Francisco'],
            ['country' => 'United States', 'state' => 'New York', 'city' => 'New York City'],
            ['country' => 'United States', 'state' => 'Texas', 'city' => 'Austin'],
            ['country' => 'Canada', 'state' => 'Ontario', 'city' => 'Toronto'],
            ['country' => 'Canada', 'state' => 'British Columbia', 'city' => 'Vancouver'],
            ['country' => 'United Kingdom', 'state' => 'England', 'city' => 'London'],
            ['country' => 'India', 'state' => 'Maharashtra', 'city' => 'Mumbai'],
            ['country' => 'India', 'state' => 'Karnataka', 'city' => 'Bangalore'],
            ['country' => 'Australia', 'state' => 'New South Wales', 'city' => 'Sydney'],
            ['country' => 'Germany', 'state' => 'Berlin', 'city' => 'Berlin']
        ];

        // Create employees for each department
        foreach ($departments as $department) {
            $employeeCount = rand(3, 8);
            $departmentEmployees = [];
            
            // Create regular employees first
            for ($i = 0; $i < $employeeCount; $i++) {
                $employee = Employee::create([
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'email' => $faker->unique()->safeEmail,
                    'phone' => $faker->phoneNumber,
                    'job_title' => $faker->randomElement($jobTitles),
                    'department_id' => $department->id,
                    'salary' => $faker->numberBetween(40000, 120000),
                    'hire_date' => $faker->dateTimeBetween('-5 years', 'now'),
                    'location' => $faker->randomElement($locations),
                    'status' => $faker->randomElement(['active', 'active', 'active', 'active', 'inactive']) // 80% active
                ]);
                
                $departmentEmployees[] = $employee;
            }
            
            // Assign supervisors (1-2 per department)
            if (count($departmentEmployees) > 2) {
                $supervisorCount = rand(1, min(2, floor(count($departmentEmployees) / 3)));
                $supervisors = collect($departmentEmployees)->random($supervisorCount);
                
                foreach ($departmentEmployees as $employee) {
                    if (!$supervisors->contains($employee) && rand(0, 100) > 30) {
                        $supervisor = $supervisors->random();
                        $employee->update(['supervisor_id' => $supervisor->id]);
                    }
                }
            }
        }

        $this->command->info('Created ' . Employee::count() . ' employees across ' . $departments->count() . ' departments.');
    }
}
