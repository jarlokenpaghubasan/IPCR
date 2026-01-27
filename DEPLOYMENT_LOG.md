# IPCR System - Railway Deployment Log

> **Date:** January 28, 2026  
> **Project:** University of Rizal System Binangonan - IPCR/OPCR Module  
> **Deployment URL:** https://ipcr-production.up.railway.app  
> **Repository:** https://github.com/jarlokenpaghubasan/IPCR.git  
> **Branch:** temp-deployment (merged to main)

---

## Table of Contents
1. [Overview](#overview)
2. [Creating Temporary Branch](#creating-temporary-branch)
3. [Railway Project Setup](#railway-project-setup)
4. [Environment Variables](#environment-variables)
5. [Deployment Configuration Files](#deployment-configuration-files)
6. [Issues Encountered & Solutions](#issues-encountered--solutions)
7. [Code Changes Summary](#code-changes-summary)
8. [Terminal Commands Reference](#terminal-commands-reference)
9. [Future Debugging Tips](#future-debugging-tips)

---

## Overview

This document summarizes the complete deployment process of the Laravel IPCR System to Railway.app, including all issues encountered and their solutions.

### Tech Stack
- **Framework:** Laravel 12.47.0
- **PHP:** 8.2
- **Database:** MySQL (Railway)
- **Frontend:** Vite 7.3.1, TailwindCSS
- **Hosting:** Railway.app

---

## Creating Temporary Branch

### Commands Used
```bash
# Create and switch to temp-deployment branch
git checkout -b temp-deployment

# Pull latest from main (if needed)
git pull origin main

# Push branch to GitHub
git push -u origin temp-deployment
```

---

## Railway Project Setup

### Step 1: Create Railway Project
1. Go to [railway.app](https://railway.app)
2. Click "New Project" → "Deploy from GitHub repo"
3. Select your repository and branch (temp-deployment)
4. Railway auto-detects Laravel/PHP

### Step 2: Add MySQL Database
1. In Railway dashboard, click "New" → "Database" → "MySQL"
2. Wait for provisioning
3. Note the connection details from Variables tab

### Step 3: Generate Domain
1. Click on your service → "Settings" → "Networking"
2. Click "Generate Domain"
3. Your app will be available at: `https://[project-name].up.railway.app`

### Step 4: Configure Builder (IMPORTANT)
Railway may auto-detect wrong builder. To fix:
1. Go to Settings → Build
2. Change "Builder" from "Railpack" to "Nixpacks"
3. This ensures nixpacks.toml is respected

---

## Environment Variables

### Railway Environment Variables Configuration
Set these in Railway dashboard → Variables:

```env
APP_NAME="IPCR System"
APP_ENV=production
APP_KEY=base64:... (generate with php artisan key:generate --show)
APP_DEBUG=false
APP_URL=https://ipcr-production.up.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=YefDMzggnafwDDbJjajgLFgZgoBDmAfr

SESSION_DRIVER=database
CACHE_STORE=database

LOG_CHANNEL=stderr
LOG_LEVEL=debug
```

### Important Notes on Variable References
- **DO NOT USE** `${{MYSQL_HOST}}` format - Railway uses `${{MYSQLHOST}}` (no underscore)
- Better to manually copy values from MySQL service variables
- Use `mysql.railway.internal` for private networking (faster)

---

## Deployment Configuration Files

### 1. nixpacks.toml (Railway Build Config)
```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer']

[phases.install]
cmds = [
  'composer install --optimize-autoloader --no-dev'
]

[phases.build]
cmds = [
  'echo "Using pre-built assets"',
  'mkdir -p storage/app/public/user_photos',
  'chmod -R 775 storage'
]

[start]
cmd = 'rm -f public/storage; php artisan storage:link; ls -la public/ | grep storage; ls -la storage/app/ | grep public; php artisan serve --host=0.0.0.0 --port=$PORT'
```

### 2. vite.config.js (Updated Build Config)
```javascript
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
        manifest: 'manifest.json',  // IMPORTANT: Changed from true to 'manifest.json'
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
```

### 3. app/Http/Middleware/TrustProxies.php (New File)
```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
```

### 4. app/Providers/AppServiceProvider.php (Updated)
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production to fix mixed content errors
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
```

---

## Issues Encountered & Solutions

### Issue 1: Database Connection Refused
**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Cause:** Environment variables using wrong format `${{MYSQL_HOST}}` instead of `${{MYSQLHOST}}`

**Solution:** Manually copy MySQL values from Railway MySQL service:
```env
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=[actual password from MySQL service]
```

---

### Issue 2: Vite Manifest Not Found
**Error:** `Vite manifest not found at: /app/public/build/manifest.json`

**Cause:** Vite 7 changed manifest location from `public/build/manifest.json` to `public/build/.vite/manifest.json`

**Solution:** Update vite.config.js to explicitly set manifest path:
```javascript
build: {
    manifest: 'manifest.json',  // Changed from: manifest: true
    outDir: 'public/build',
}
```

Then rebuild assets:
```bash
npm run build
git add -f public/build  # Force add because it's in .gitignore
git commit -m "Fix: Correct Vite manifest path"
git push origin temp-deployment
```

---

### Issue 3: Mixed Content (HTTP/HTTPS) Errors
**Error:** Console shows "Mixed Content: The page was loaded over HTTPS, but requested an insecure stylesheet/script"

**Cause:** Laravel generating HTTP URLs for assets while Railway serves over HTTPS

**Solution:** Two-part fix:

1. Create TrustProxies middleware (see code above)
2. Force HTTPS in AppServiceProvider:
```php
if ($this->app->environment('production')) {
    URL::forceScheme('https');
}
```

---

### Issue 4: Builder Conflict (Railpack vs Nixpacks)
**Symptom:** Railway ignores nixpacks.toml, uses wrong build process

**Cause:** Railway's auto-detection chose Railpack over Nixpacks

**Solution:** 
1. Delete Procfile if it exists (triggers Railpack detection)
2. Go to Railway Settings → Build → Change "Builder" to "Nixpacks"

---

### Issue 5: CSS/JS Not Loading Locally After Changes
**Error:** Same Vite manifest error locally

**Solution:** Clear Laravel cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
npm run build
```

---

### Issue 6: User Photos Not Showing (404)
**Error:** Photo URLs return 404 on Railway

**Cause:** 
1. Storage symlink not created
2. Railway filesystem is ephemeral - files don't persist across deployments
3. Local files aren't uploaded to Railway

**Partial Solution:** 
1. Add storage:link to start command in nixpacks.toml
2. Create Railway Volume at `/app/storage/app/public`
3. Re-upload photos on Railway (local files don't transfer)

**Note:** For production, consider using cloud storage (AWS S3, Cloudinary) for persistent file uploads.

---

## Code Changes Summary

| File | Change Type | Purpose |
|------|-------------|---------|
| `nixpacks.toml` | Created | Railway build configuration |
| `DEPLOYMENT.md` | Created | Deployment instructions |
| `vite.config.js` | Modified | Fix Vite manifest path |
| `app/Http/Middleware/TrustProxies.php` | Created | Trust Railway proxy headers |
| `app/Providers/AppServiceProvider.php` | Modified | Force HTTPS in production |
| `public/build/*` | Created | Pre-built Vite assets |

---

## Terminal Commands Reference

### Git Commands
```bash
# Create branch
git checkout -b temp-deployment

# Stage files (including gitignored)
git add -f public/build

# Commit
git commit -m "Your message"

# Push
git push origin temp-deployment

# Merge to main
git checkout main
git merge temp-deployment
git push origin main
```

### Laravel Commands
```bash
# Generate app key
php artisan key:generate --show

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Create storage symlink
php artisan storage:link

# Start local server
php artisan serve
```

### Vite/NPM Commands
```bash
# Build assets for production
npm run build

# Development with hot reload
npm run dev
```

### Railway CLI (Optional)
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login
railway login

# Link to project
railway link

# View logs
railway logs

# Run commands on Railway
railway run php artisan migrate --force
```

---

## Future Debugging Tips

### 1. Check Railway Deployment Logs
- Go to Railway dashboard → Deployments → Click on deployment
- Look for build errors or start command failures

### 2. Check Runtime Logs
- In Railway dashboard, click "View Logs" on your service
- Look for PHP errors, Laravel exceptions

### 3. Enable Debug Mode Temporarily
Set in Railway variables:
```env
APP_DEBUG=true
```
**Remember to disable after debugging!**

### 4. Test Database Connection
Add this route temporarily to test:
```php
Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return 'Connected to: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
```

### 5. Verify Storage Symlink
SSH into Railway or add to start command:
```bash
ls -la public/ | grep storage
```
Should show: `storage -> /app/storage/app/public`

### 6. Check Environment Variables
Add this route temporarily:
```php
Route::get('/env-check', function() {
    return [
        'APP_ENV' => env('APP_ENV'),
        'APP_URL' => env('APP_URL'),
        'DB_HOST' => env('DB_HOST'),
    ];
});
```

### 7. Common Railway Issues

| Issue | Check |
|-------|-------|
| 502 Bad Gateway | Check start command, look for PHP fatal errors |
| Assets not loading | Check Vite manifest, verify HTTPS forcing |
| Database errors | Verify DB variables, check MySQL service is running |
| Files disappearing | Add Railway Volume for persistent storage |

---

## Deployment Checklist

- [ ] Create temp-deployment branch
- [ ] Create nixpacks.toml
- [ ] Build Vite assets: `npm run build`
- [ ] Force add build folder: `git add -f public/build`
- [ ] Create Railway project from GitHub
- [ ] Add MySQL database service
- [ ] Configure environment variables
- [ ] Set Builder to Nixpacks in Settings
- [ ] Generate domain
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed database if needed: `php artisan db:seed --force`
- [ ] Test all pages
- [ ] Verify HTTPS working
- [ ] Add Volume for file uploads (optional)
- [ ] Merge to main when ready

---

## Useful Links

- [Railway Documentation](https://docs.railway.app/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Nixpacks Configuration](https://nixpacks.com/docs/configuration/file)
- [Vite Laravel Plugin](https://laravel.com/docs/vite)

---

*Last Updated: January 28, 2026*
