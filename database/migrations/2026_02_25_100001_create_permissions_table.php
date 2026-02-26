<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();       // e.g. "faculty.ipcr.templates"
            $table->string('name');                 // e.g. "Manage IPCR Templates"
            $table->string('group');                // e.g. "Faculty"
            $table->timestamps();
        });

        // Seed all permissions
        $now = now();
        DB::table('permissions')->insert([
            // Admin permissions
            ['key' => 'admin.dashboard',              'name' => 'Access Admin Dashboard',                'group' => 'Admin',    'created_at' => $now, 'updated_at' => $now],
            ['key' => 'admin.users.manage',            'name' => 'Manage Users (Create, Edit, Delete)',  'group' => 'Admin',    'created_at' => $now, 'updated_at' => $now],
            ['key' => 'admin.users.toggle-active',     'name' => 'Activate / Deactivate Users',         'group' => 'Admin',    'created_at' => $now, 'updated_at' => $now],
            ['key' => 'admin.photos.manage',           'name' => 'Manage User Photos',                  'group' => 'Admin',    'created_at' => $now, 'updated_at' => $now],
            ['key' => 'admin.database.manage',         'name' => 'Database Backup & Restore',           'group' => 'Admin',    'created_at' => $now, 'updated_at' => $now],
            ['key' => 'admin.activity-logs.view',      'name' => 'View & Export Activity Logs',         'group' => 'Admin',    'created_at' => $now, 'updated_at' => $now],
            ['key' => 'admin.role-management.manage',  'name' => 'Manage Roles, Depts & Designations',  'group' => 'Admin',    'created_at' => $now, 'updated_at' => $now],

            // Faculty permissions
            ['key' => 'faculty.dashboard',             'name' => 'Access Faculty Dashboard',             'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.profile.manage',        'name' => 'Manage Profile & Password',            'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.ipcr.templates',        'name' => 'Manage IPCR Templates',                'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.ipcr.submissions',      'name' => 'Manage IPCR Submissions',              'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.ipcr.saved-copies',     'name' => 'Manage IPCR Saved Copies',             'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.opcr.templates',        'name' => 'Manage OPCR Templates',                'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.opcr.submissions',      'name' => 'Manage OPCR Submissions',              'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.opcr.saved-copies',     'name' => 'Manage OPCR Saved Copies',             'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faculty.supporting-documents',  'name' => 'Manage Supporting Documents',          'group' => 'Faculty',  'created_at' => $now, 'updated_at' => $now],

            // Dean permissions
            ['key' => 'dean.dashboard',                'name' => 'Access Dean Dashboard',                'group' => 'Dean',     'created_at' => $now, 'updated_at' => $now],
            ['key' => 'dean.review.faculty',           'name' => 'Review Faculty Submissions',           'group' => 'Dean',     'created_at' => $now, 'updated_at' => $now],
            ['key' => 'dean.review.deans',             'name' => 'View Dean Calibration Submissions',    'group' => 'Dean',     'created_at' => $now, 'updated_at' => $now],

            // Director permissions
            ['key' => 'director.dashboard',            'name' => 'Access Director Dashboard',            'group' => 'Director', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
