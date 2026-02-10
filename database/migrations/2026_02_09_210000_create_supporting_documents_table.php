<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supporting_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('documentable_type'); // 'ipcr_submission', 'opcr_submission', 'ipcr_template', 'opcr_template'
            $table->unsignedBigInteger('documentable_id'); // ID of the submission/template
            $table->string('so_label'); // e.g. 'SO I', 'SO II'
            $table->string('filename'); // Cloudinary public_id
            $table->string('path'); // Cloudinary secure_url
            $table->string('original_name'); // Original filename
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamps();

            $table->index(['documentable_type', 'documentable_id', 'so_label'], 'docs_poly_so_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supporting_documents');
    }
};
