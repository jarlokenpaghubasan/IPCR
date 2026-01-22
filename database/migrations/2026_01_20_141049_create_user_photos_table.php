<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('filename'); // e.g., "1_1234567890_abc123xyz.jpg"
            $table->string('path'); // Full path to file
            $table->string('original_name'); // Original filename uploaded
            $table->string('mime_type'); // e.g., "image/jpeg"
            $table->bigInteger('file_size'); // Size in bytes
            $table->boolean('is_profile_photo')->default(false); // Current profile photo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_photos');
    }
};