<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_photos', function (Blueprint $table) {
            if (!Schema::hasColumn('user_photos', 'cloudinary_public_id')) {
                $table->string('cloudinary_public_id')->nullable()->after('path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_photos', function (Blueprint $table) {
            if (Schema::hasColumn('user_photos', 'cloudinary_public_id')) {
                $table->dropColumn('cloudinary_public_id');
            }
        });
    }
};
