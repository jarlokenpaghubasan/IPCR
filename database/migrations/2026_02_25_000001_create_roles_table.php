<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();       // e.g., "admin", "faculty"
            $table->string('acronym')->unique();     // e.g., "ADM", "FAC" — used in employee ID generation
            $table->timestamps();
        });

        // Seed default roles
        DB::table('roles')->insert([
            ['name' => 'admin',    'acronym' => 'ADM',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'hr',       'acronym' => 'HRD',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'director', 'acronym' => 'DIR',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dean',     'acronym' => 'DEAN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'faculty',  'acronym' => 'FAC',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
