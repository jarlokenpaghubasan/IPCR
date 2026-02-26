<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->unique(['role_id', 'permission_id']);
        });

        // Seed default role-permission mappings
        $this->seedDefaults();
    }

    private function seedDefaults(): void
    {
        $roles = DB::table('roles')->pluck('id', 'name');
        $permissions = DB::table('permissions')->pluck('id', 'key');

        $mappings = [
            // Admin gets ALL permissions
            'admin' => array_keys($permissions->toArray()),

            // Faculty gets faculty.* permissions
            'faculty' => [
                'faculty.dashboard',
                'faculty.profile.manage',
                'faculty.ipcr.templates',
                'faculty.ipcr.submissions',
                'faculty.ipcr.saved-copies',
                'faculty.opcr.templates',
                'faculty.opcr.submissions',
                'faculty.opcr.saved-copies',
                'faculty.supporting-documents',
            ],

            // Dean gets dean.* + faculty.* permissions
            'dean' => [
                'dean.dashboard',
                'dean.review.faculty',
                'dean.review.deans',
                'faculty.dashboard',
                'faculty.profile.manage',
                'faculty.ipcr.templates',
                'faculty.ipcr.submissions',
                'faculty.ipcr.saved-copies',
                'faculty.opcr.templates',
                'faculty.opcr.submissions',
                'faculty.opcr.saved-copies',
                'faculty.supporting-documents',
            ],

            // Director gets director.* permissions
            'director' => [
                'director.dashboard',
            ],

            // HR gets admin user management permissions
            'hr' => [
                'admin.dashboard',
                'admin.users.manage',
                'admin.users.toggle-active',
                'admin.photos.manage',
                'admin.activity-logs.view',
            ],
        ];

        $inserts = [];
        foreach ($mappings as $roleName => $permKeys) {
            if (!isset($roles[$roleName])) continue;
            $roleId = $roles[$roleName];

            foreach ($permKeys as $permKey) {
                if (!isset($permissions[$permKey])) continue;
                $inserts[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissions[$permKey],
                ];
            }
        }

        if (!empty($inserts)) {
            DB::table('role_permissions')->insert($inserts);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
