import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';


export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Auth pages
                'resources/css/auth_login.css',
                'resources/css/auth_login-selection.css',
                'resources/css/auth_verify-code.css',
                'resources/css/auth_reset-password.css',
                'resources/js/auth_login.js',
                'resources/js/auth_verify-code.js',
                'resources/js/auth_reset-password.js',

                // Dashboard - Faculty
                'resources/css/dashboard_faculty_index.css',
                'resources/css/dashboard_faculty_profile.css',
                'resources/css/dashboard_faculty_my-ipcrs.css',
                'resources/js/dashboard_faculty_index.js',
                'resources/js/dashboard_faculty_profile.js',
                'resources/js/dashboard_faculty_my-ipcrs.js',

                // Dashboard - Admin
                // 'resources/css/dashboard_admin_index.css', // Consolidated
                // 'resources/js/dashboard_admin_index.js', // Consolidated

                // Admin Layout (shared)
                'resources/css/admin_layout.css',
                'resources/js/admin_layout.js',

                // Admin Users
                // 'resources/css/admin_users_index.css', // Consolidated
                'resources/css/admin_users_show.css',
                'resources/css/admin_users_edit.css',
                // 'resources/js/admin_users_index.js', // Consolidated
                'resources/js/admin_users_show.js',
                'resources/js/admin_users_edit.js',

                // Admin Database
                // 'resources/css/admin_database_index.css', // Consolidated
                // 'resources/js/admin_database_index.js', // Consolidated
            ],
            refresh: true,
        }),

    ],
    build: {
        manifest: 'manifest.json',
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
