# IPCR/OPCR Management System

## Brief Summary
This system is a Laravel 12 web application for managing IPCR and OPCR performance workflows in a university setting.

Based on the project structure, routes, and deployment notes, the platform is designed to support end-to-end performance cycle operations:

- role-based access for admin, HR, dean, director, and faculty users
- creation, import, update, activation, and export of IPCR/OPCR templates
- IPCR/OPCR submission workflows, saved copies, and unsubmit/deactivate controls
- dean review and calibration for faculty and dean submissions
- HR summary reports with Excel export (faculty, staff, dean/director, and combined)
- supporting document management for evidence files
- admin panel for user management, role/permission setup, deadlines/notifications, activity logs, and database backup/restore

## Technology Stack
- Backend: PHP 8.2, Laravel 12
- Frontend: Blade, Vite, Tailwind CSS 4, Turbo
- Database: MySQL (XAMPP local setup)
- File/Image services: Cloudinary integration
- Reporting: PhpSpreadsheet (Excel import/export)

## Core Modules
- Authentication: login/logout, password reset via verification code, email verification
- Faculty/Director area: IPCR workflows, profile, supporting docs, exports
- Dean area: dashboard plus review/calibration workflow
- HR area: summary reports and user management access
- Admin area:
  - dashboard
  - user and photo management
  - role/department/designation management
  - notification and deadline management
  - activity log viewing/export
  - database backup, restore, upload, and download

## Setup and Deployment
Detailed setup and deployment instructions are in [DEPLOYMENT_PLAN.md](DEPLOYMENT_PLAN.md).

The deployment plan includes:
- installing Git, Composer, Laravel installer, XAMPP, and Node.js
- cloning from https://github.com/jarloken02-png/ipcr_sys.git
- dependency installation and local configuration
- database creation, migration, and seeding
- Cloudinary account setup and integration
- Brevo account setup and SMTP configuration
- Railway deployment flow and post-deploy validation steps

## Seeded Development Accounts
After running migrations with seeders, the following users are available (default password: `password`):

- admin (Admin)
- dean (Dean)
- director (Director)
- faculty (Faculty)
- faculty2 (Faculty)

## Useful Commands

```powershell
# Run tests
php artisan test

# Run local dev stack (server + queue + vite)
composer dev

# Fresh reset database
php artisan migrate:fresh --seed --force
```

## Notes
- The system uses role and permission middleware to protect routes and modules.
- Director routes currently redirect to the faculty dashboard route.
- For production deployments, verify mail and Cloudinary credentials before enabling user-facing features.
