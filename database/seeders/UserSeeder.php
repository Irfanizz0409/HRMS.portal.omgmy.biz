<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Ahmad Rahman',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => 'EMP001',
            'ic_number' => '850101-01-1234',
            'phone' => '012-3456789',
            'address' => 'No 123, Jalan Admin, Kuala Lumpur',
            'date_of_birth' => '1985-01-01',
            'gender' => 'male',
            'department' => 'Management',
            'position' => 'System Administrator',
            'hire_date' => '2020-01-15',
            'salary' => 8000.00,
            'employment_status' => 'active',
            'emergency_contact_name' => 'Siti Rahman',
            'emergency_contact_relationship' => 'Spouse',
            'emergency_contact_phone' => '012-9876543',
        ]);

        // HR
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'hr@example.com',
            'password' => Hash::make('password'),
            'role' => 'hr',
            'employee_id' => 'EMP002',
            'ic_number' => '880215-14-5678',
            'phone' => '012-2345678',
            'address' => 'No 45, Jalan HR, Petaling Jaya',
            'date_of_birth' => '1988-02-15',
            'gender' => 'female',
            'department' => 'Human Resources',
            'position' => 'HR Manager',
            'hire_date' => '2021-03-01',
            'salary' => 6500.00,
            'employment_status' => 'active',
            'emergency_contact_name' => 'Ali Nurhaliza',
            'emergency_contact_relationship' => 'Father',
            'emergency_contact_phone' => '012-8765432',
        ]);

        // Staff
        User::create([
            'name' => 'Muhammad Faiz',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'employee_id' => 'EMP003',
            'ic_number' => '900520-10-9876',
            'phone' => '012-3344556',
            'address' => 'No 78, Jalan Staff, Shah Alam',
            'date_of_birth' => '1990-05-20',
            'gender' => 'male',
            'department' => 'Operations',
            'position' => 'Senior Executive',
            'hire_date' => '2022-06-15',
            'salary' => 4500.00,
            'employment_status' => 'active',
            'emergency_contact_name' => 'Aminah Faiz',
            'emergency_contact_relationship' => 'Mother',
            'emergency_contact_phone' => '012-7654321',
        ]);

        // Part Time
        User::create([
            'name' => 'Lim Wei Ming',
            'email' => 'parttime@example.com',
            'password' => Hash::make('password'),
            'role' => 'part_time',
            'employee_id' => 'EMP004',
            'ic_number' => '950830-08-1122',
            'phone' => '012-4455667',
            'address' => 'No 12, Jalan Parttime, Subang Jaya',
            'date_of_birth' => '1995-08-30',
            'gender' => 'male',
            'department' => 'Support',
            'position' => 'Part Time Assistant',
            'hire_date' => '2023-01-10',
            'salary' => 2000.00,
            'employment_status' => 'active',
            'emergency_contact_name' => 'Lim Ah Choo',
            'emergency_contact_relationship' => 'Mother',
            'emergency_contact_phone' => '012-6543210',
        ]);

        // Intern
        User::create([
            'name' => 'Nurul Aina',
            'email' => 'intern@example.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'employee_id' => 'EMP005',
            'ic_number' => '001215-06-3344',
            'phone' => '012-5566778',
            'address' => 'No 99, Jalan Intern, Cyberjaya',
            'date_of_birth' => '2000-12-15',
            'gender' => 'female',
            'department' => 'IT',
            'position' => 'Software Development Intern',
            'hire_date' => '2024-02-01',
            'salary' => 800.00,
            'employment_status' => 'active',
            'emergency_contact_name' => 'Rosli Aina',
            'emergency_contact_relationship' => 'Father',
            'emergency_contact_phone' => '012-5432109',
        ]);

        // Additional Staff Members
        User::create([
            'name' => 'Rajesh Kumar',
            'email' => 'rajesh@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'employee_id' => 'EMP006',
            'ic_number' => '870910-10-5566',
            'phone' => '012-6677889',
            'address' => 'No 55, Jalan Finance, Bangsar',
            'date_of_birth' => '1987-09-10',
            'gender' => 'male',
            'department' => 'Finance',
            'position' => 'Accountant',
            'hire_date' => '2021-08-20',
            'salary' => 5200.00,
            'employment_status' => 'active',
            'emergency_contact_name' => 'Priya Kumar',
            'emergency_contact_relationship' => 'Spouse',
            'emergency_contact_phone' => '012-4321098',
        ]);

        // Blocked Employee (for testing)
        User::create([
            'name' => 'John Doe',
            'email' => 'blocked@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'employee_id' => 'EMP007',
            'ic_number' => '920505-14-7788',
            'phone' => '012-7788990',
            'address' => 'No 88, Jalan Blocked, KL',
            'date_of_birth' => '1992-05-05',
            'gender' => 'male',
            'department' => 'Marketing',
            'position' => 'Marketing Executive',
            'hire_date' => '2023-05-01',
            'salary' => 3800.00,
            'employment_status' => 'blocked', // This user is blocked
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_relationship' => 'Spouse',
            'emergency_contact_phone' => '012-3210987',
        ]);
    }
}