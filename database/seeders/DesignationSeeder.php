<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('designations')->insert([
            [
                'title' => 'Professor',
                'code' => 'PROF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Associate Professor',
                'code' => 'ASSOC_PROF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Assistant Professor',
                'code' => 'ASST_PROF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Instructor',
                'code' => 'INST',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Lecturer',
                'code' => 'LEC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}