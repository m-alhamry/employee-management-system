<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@company.com',
                'position' => 'Software Engineer',
                'salary' => 85000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@company.com',
                'position' => 'Product Manager',
                'salary' => 95000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Carol White',
                'email' => 'carol.white@company.com',
                'position' => 'UX Designer',
                'salary' => 75000.00,
                'status' => 'active',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@company.com',
                'position' => 'DevOps Engineer',
                'salary' => 90000.00,
                'status' => 'inactive',
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma.davis@company.com',
                'position' => 'Frontend Developer',
                'salary' => 80000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Frank Miller',
                'email' => 'frank.miller@company.com',
                'position' => 'Backend Developer',
                'salary' => 88000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Grace Lee',
                'email' => 'grace.lee@company.com',
                'position' => 'QA Engineer',
                'salary' => 70000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Henry Wilson',
                'email' => 'henry.wilson@company.com',
                'position' => 'Data Analyst',
                'salary' => 78000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Isabel Martinez',
                'email' => 'isabel.martinez@company.com',
                'position' => 'HR Manager',
                'salary' => 82000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Jack Taylor',
                'email' => 'jack.taylor@company.com',
                'position' => 'Sales Representative',
                'salary' => 65000.00,
                'status' => 'inactive',
            ],
            [
                'name' => 'Karen Anderson',
                'email' => 'karen.anderson@company.com',
                'position' => 'Marketing Specialist',
                'salary' => 72000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Liam Thomas',
                'email' => 'liam.thomas@company.com',
                'position' => 'System Administrator',
                'salary' => 84000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Mia Jackson',
                'email' => 'mia.jackson@company.com',
                'position' => 'Business Analyst',
                'salary' => 79000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Noah Harris',
                'email' => 'noah.harris@company.com',
                'position' => 'Mobile Developer',
                'salary' => 87000.00,
                'status' => 'active',
            ],
            [
                'name' => 'Olivia Clark',
                'email' => 'olivia.clark@company.com',
                'position' => 'Scrum Master',
                'salary' => 92000.00,
                'status' => 'inactive',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
