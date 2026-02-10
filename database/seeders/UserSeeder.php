<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'employee_id' => 'URS26-ADM00001',
            'username' => 'admin',
            'email' => 'admin@ipcr.system',
            'password' => Hash::make('password'),
            'phone' => '09123456789',
            'role' => 'admin',
            'department_id' => null,
            'designation_id' => null,
            'is_active' => true,
        ]);

        UserRole::create([
            'user_id' => $admin->id,
            'role' => 'admin',
        ]);

        // Create dean user (College of Computer Studies)
        $dean = User::create([
            'name' => 'Dr. Maria Santos',
            'employee_id' => 'URS26-DEAN0001',
            'username' => 'dean',
            'email' => 'dean@ipcr.system',
            'password' => Hash::make('password'),
            'phone' => '09123456790',
            'role' => 'dean',
            'department_id' => 3, // College of Computer Studies
            'designation_id' => 1, // Professor
            'is_active' => true,
        ]);

        UserRole::create([
            'user_id' => $dean->id,
            'role' => 'dean',
        ]);

        // Create director user
        $director = User::create([
            'name' => 'Dr. Juan Dela Cruz',
            'employee_id' => 'URS26-DIR00001',
            'username' => 'director',
            'email' => 'director@ipcr.system',
            'password' => Hash::make('password'),
            'phone' => '09123456791',
            'role' => 'director',
            'department_id' => null,
            'designation_id' => 1, // Professor
            'is_active' => true,
        ]);

        UserRole::create([
            'user_id' => $director->id,
            'role' => 'director',
        ]);

        // Create faculty user (College of Computer Studies)
        $faculty = User::create([
            'name' => 'Prof. Ana Garcia',
            'employee_id' => 'URS26-FAC00001',
            'username' => 'faculty',
            'email' => 'faculty@ipcr.system',
            'password' => Hash::make('password'),
            'phone' => '09123456792',
            'role' => 'faculty',
            'department_id' => 3, // College of Computer Studies
            'designation_id' => 4, // Instructor
            'is_active' => true,
        ]);

        UserRole::create([
            'user_id' => $faculty->id,
            'role' => 'faculty',
        ]);

        // Create additional faculty user (College of Business)
        $faculty2 = User::create([
            'name' => 'Prof. Carlos Reyes',
            'employee_id' => 'URS26-FAC00002',
            'username' => 'faculty2',
            'email' => 'faculty2@ipcr.system',
            'password' => Hash::make('password'),
            'phone' => '09123456793',
            'role' => 'faculty',
            'department_id' => 2, // College of Business
            'designation_id' => 3, // Assistant Professor
            'is_active' => true,
        ]);

        UserRole::create([
            'user_id' => $faculty2->id,
            'role' => 'faculty',
        ]);
    }
}