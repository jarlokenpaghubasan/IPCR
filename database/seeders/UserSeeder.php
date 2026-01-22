<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User (MIS)
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@university.edu',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'phone' => '09123456789',
            'role' => 'admin',
            'department_id' => null,
            'designation_id' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Director User
        DB::table('users')->insert([
            'name' => 'Dr. Maria Santos',
            'email' => 'director@university.edu',
            'username' => 'director',
            'password' => Hash::make('password'),
            'phone' => '09198765432',
            'role' => 'director',
            'department_id' => null,
            'designation_id' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Dean for COA
        DB::table('users')->insert([
            'name' => 'Dean Juan Dela Cruz',
            'email' => 'dean.coa@university.edu',
            'username' => 'dean_coa',
            'password' => Hash::make('password'),
            'phone' => '09111111111',
            'role' => 'dean',
            'department_id' => 1, // COA
            'designation_id' => 1, // Professor
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Dean for COB
        DB::table('users')->insert([
            'name' => 'Dean Maria Reyes',
            'email' => 'dean.cob@university.edu',
            'username' => 'dean_cob',
            'password' => Hash::make('password'),
            'phone' => '09222222222',
            'role' => 'dean',
            'department_id' => 2, // COB
            'designation_id' => 1, // Professor
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Dean for CCS
        DB::table('users')->insert([
            'name' => 'Dean Roberto Garcia',
            'email' => 'dean.ccs@university.edu',
            'username' => 'dean_ccs',
            'password' => Hash::make('password'),
            'phone' => '09333333333',
            'role' => 'dean',
            'department_id' => 3, // CCS
            'designation_id' => 1, // Professor
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sample Faculty for COA
        DB::table('users')->insert([
            'name' => 'Prof. Antonio Bautista',
            'email' => 'antonio.bautista@university.edu',
            'username' => 'faculty_coa_1',
            'password' => Hash::make('password'),
            'phone' => '09444444444',
            'role' => 'faculty',
            'department_id' => 1, // COA
            'designation_id' => 2, // Associate Professor
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sample Faculty for COB
        DB::table('users')->insert([
            'name' => 'Prof. Isabella Lopez',
            'email' => 'isabella.lopez@university.edu',
            'username' => 'faculty_cob_1',
            'password' => Hash::make('password'),
            'phone' => '09555555555',
            'role' => 'faculty',
            'department_id' => 2, // COB
            'designation_id' => 3, // Assistant Professor
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sample Faculty for CCS
        DB::table('users')->insert([
            'name' => 'Prof. Carlos Mendoza',
            'email' => 'carlos.mendoza@university.edu',
            'username' => 'faculty_ccs_1',
            'password' => Hash::make('password'),
            'phone' => '09666666666',
            'role' => 'faculty',
            'department_id' => 3, // CCS
            'designation_id' => 4, // Instructor
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}