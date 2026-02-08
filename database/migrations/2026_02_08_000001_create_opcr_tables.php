<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opcr_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('period')->nullable();
            $table->text('content')->nullable();
            $table->longText('table_body_html')->nullable();
            $table->string('school_year')->nullable();
            $table->string('semester')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('so_count_json')->nullable();
            $table->timestamps();
        });

        Schema::create('opcr_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('school_year');
            $table->string('semester');
            $table->longText('table_body_html');
            $table->json('so_count_json')->nullable();
            $table->string('status')->default('submitted');
            $table->boolean('is_active')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('opcr_saved_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('school_year')->nullable();
            $table->string('semester')->nullable();
            $table->longText('table_body_html');
            $table->timestamp('saved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opcr_saved_copies');
        Schema::dropIfExists('opcr_submissions');
        Schema::dropIfExists('opcr_templates');
    }
};
