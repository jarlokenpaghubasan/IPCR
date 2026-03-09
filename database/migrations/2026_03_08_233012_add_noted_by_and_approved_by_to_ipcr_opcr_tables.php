<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The six tables that need noted_by and approved_by columns.
     */
    private array $tables = [
        'ipcr_templates',
        'ipcr_submissions',
        'ipcr_saved_copies',
        'opcr_templates',
        'opcr_submissions',
        'opcr_saved_copies',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->string('noted_by')->nullable()->after('table_body_html');
                $blueprint->string('approved_by')->nullable()->after('noted_by');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn(['noted_by', 'approved_by']);
            });
        }
    }
};
