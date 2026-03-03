<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move OPCR permissions from Faculty group to Dean group
        // and rename keys from faculty.opcr.* to dean.opcr.*
        $mappings = [
            'faculty.opcr.templates'   => 'dean.opcr.templates',
            'faculty.opcr.submissions' => 'dean.opcr.submissions',
            'faculty.opcr.saved-copies' => 'dean.opcr.saved-copies',
        ];

        foreach ($mappings as $oldKey => $newKey) {
            DB::table('permissions')
                ->where('key', $oldKey)
                ->update([
                    'key'   => $newKey,
                    'group' => 'Dean',
                    'updated_at' => now(),
                ]);
        }

        // Also update any existing role_permissions entries that reference the old permission IDs
        // (IDs don't change since we're updating in-place, so no action needed there)
    }

    public function down(): void
    {
        $mappings = [
            'dean.opcr.templates'    => 'faculty.opcr.templates',
            'dean.opcr.submissions'  => 'faculty.opcr.submissions',
            'dean.opcr.saved-copies' => 'faculty.opcr.saved-copies',
        ];

        foreach ($mappings as $oldKey => $newKey) {
            DB::table('permissions')
                ->where('key', $oldKey)
                ->update([
                    'key'   => $newKey,
                    'group' => 'Faculty',
                    'updated_at' => now(),
                ]);
        }
    }
};
