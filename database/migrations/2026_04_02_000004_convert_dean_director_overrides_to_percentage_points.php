<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            UPDATE dean_director_summary_overrides
            SET
                strategic_score = CASE
                    WHEN strategic_score IS NULL THEN NULL
                    WHEN strategic_score <= 5 THEN strategic_score * 20
                    ELSE strategic_score
                END,
                core_score = CASE
                    WHEN core_score IS NULL THEN NULL
                    WHEN core_score <= 5 THEN core_score * 20
                    ELSE core_score
                END,
                support_score = CASE
                    WHEN support_score IS NULL THEN NULL
                    WHEN support_score <= 5 THEN support_score * 20
                    ELSE support_score
                END
        ');
    }

    public function down(): void
    {
        DB::statement('
            UPDATE dean_director_summary_overrides
            SET
                strategic_score = CASE
                    WHEN strategic_score IS NULL THEN NULL
                    WHEN strategic_score <= 100 THEN strategic_score / 20
                    ELSE strategic_score
                END,
                core_score = CASE
                    WHEN core_score IS NULL THEN NULL
                    WHEN core_score <= 100 THEN core_score / 20
                    ELSE core_score
                END,
                support_score = CASE
                    WHEN support_score IS NULL THEN NULL
                    WHEN support_score <= 100 THEN support_score / 20
                    ELSE support_score
                END
        ');
    }
};
