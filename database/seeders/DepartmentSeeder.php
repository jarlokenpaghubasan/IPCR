<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'name' => 'College of Accountancy',
                'code' => 'COA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'College of Business',
                'code' => 'COB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'College of Computer Studies',
                'code' => 'CCS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}