import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Auth pages
                'resources/css/auth_login.css',
                'resources/css/auth_login-selection.css',
                'resources/js/auth_login.js',
                
                // Dashboard - Faculty
                'resources/css/dashboard_faculty_index.css',
                'resources/css/dashboard_faculty_profile.css',
                'resources/css/dashboard_faculty_my-ipcrs.css',
                'resources/js/dashboard_faculty_index.js',
                'resources/js/dashboard_faculty_profile.js',
                'resources/js/dashboard_faculty_my-ipcrs.js',
                
                // Dashboard - Admin
                'resources/css/dashboard_admin_index.css',
                'resources/js/dashboard_admin_index.js',
                
                // Admin Users
                'resources/css/admin_users_index.css',
                'resources/css/admin_users_show.css',
                'resources/css/admin_users_edit.css',
                'resources/js/admin_users_index.js',
                'resources/js/admin_users_show.js',
                'resources/js/admin_users_edit.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
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
