<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ipcr_submissions', function (Blueprint $table) {
            $table->json('so_count_json')->nullable()->after('table_body_html');
            $table->boolean('is_active')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('ipcr_submissions', function (Blueprint $table) {
            $table->dropColumn(['so_count_json', 'is_active']);
        });
    }
};
