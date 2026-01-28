<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to force add the column even if it somehow already exists
        DB::statement('ALTER TABLE user_photos ADD COLUMN IF NOT EXISTS cloudinary_public_id VARCHAR(255) NULL AFTER path');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE user_photos DROP COLUMN IF EXISTS cloudinary_public_id');
    }
};
