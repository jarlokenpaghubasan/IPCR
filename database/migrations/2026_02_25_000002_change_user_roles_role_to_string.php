<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to varchar to support dynamic roles
        DB::statement("ALTER TABLE user_roles MODIFY COLUMN role VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        // Revert back to enum
        DB::statement("ALTER TABLE user_roles MODIFY COLUMN role ENUM('admin','hr','director','dean','faculty') NOT NULL");
    }
};
