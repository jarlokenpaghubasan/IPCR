# 🎓 IPCR System

A comprehensive Individual Performance Commitment and Review (IPCR) management system built with Laravel, designed for educational institutions to track and manage faculty performance evaluations.

## ✨ Features

- 🔐 **Multi-Role Authentication** (Admin, Director, Dean, Faculty)
- � **Password Reset via Email** with 6-digit verification codes
- �📊 **Performance Dashboard** with charts and metrics
- 📱 **Fully Responsive Design** (Mobile, Tablet, Desktop)
- 🔔 **Notification System** with real-time updates
- 👤 **User Profile Management** with photo uploads
- 🆔 **Automatic Employee ID Generation**
- 🔑 **Password Management** with secure hashing
- 🕒 **Last Login Tracking**
- 🌏 **Hong Kong Timezone (Asia/Hong_Kong)**
- ⚡ **Vite Asset Bundling** with optimized CSS/JS
- 🎨 **Tailwind CSS v4.0** for modern styling

## 📦 Latest Updates (February 4, 2026)

### 🔐 Password Reset Feature (NEW!)
- 📧 **Email-based Password Reset** - Users can reset forgotten passwords via email verification
- 🔢 **6-Digit Verification Code** - Secure random codes sent to user's email address
- ⏱️ **15-Minute Expiration** - Reset codes automatically expire after 15 minutes for security
- 📨 **Brevo SMTP Integration** - Production-ready email delivery through Brevo (formerly Sendinblue)
- 🎨 **Custom Email Templates** - Branded emails with URS name and customized messaging
- 🔒 **Secure Token Storage** - Hashed tokens stored in dedicated password_reset_tokens table
- ✅ **Password Validation** - Minimum 8 characters with confirmation required
- 🌐 **Accessible from Login** - "Forgot password?" link available on all login pages
- 📱 **Responsive Forms** - Mobile-friendly forgot password and reset password pages
- 🚀 **Free Tier Support** - 300 emails/day with Brevo's free plan

### ✨ Image Crop & Resize Feature
- 🎬 **Interactive Crop Modal** - Draggable crop area with 1:1 aspect ratio constraint
- 🔍 **Zoom Controls** - Zoom in/out with 0.1 increments for precise image positioning
- 🔄 **Rotation Controls** - Rotate images left/right by 90° increments
- ↩️ **Reset Functionality** - Reset image to default position with one click
- 📸 **Live Preview** - Real-time preview of crop selection as you adjust
- ✅ **Crop & Upload Workflow** - Seamless from selection → crop → upload to Cloudinary
- 🎨 **Consistent UI** - Available in both Faculty Profile and Admin User Management
- 🌟 **400x400px Output** - All cropped images automatically resized to 400x400 pixels
- 📊 **Progress Tracking** - Visual progress bar during upload with success/error messaging

### Photo Management Enhancements
- 📸 **Automatic Image Compression** - All uploaded photos are automatically compressed to reduce file size
- 🖼️ **Smart Resizing** - Images larger than 1200px are automatically resized while maintaining aspect ratio
- 🎯 **Quality Optimization** - JPEG/WebP files compressed to 80% quality for perfect balance
- 👤 **Default Avatar System** - Users without photos get clean SVG silhouette avatars
- 🎨 **Styled Modals** - Consistent confirmation modals for deleting and setting profile photos
- ⚡ **Hover Controls** - Intuitive photo management with hover-triggered controls

### Admin Protection & UI Improvements
- 🔒 **Protected Administrator Account** - Main admin account (URS26-ADM00001) cannot be edited, deleted, or deactivated
- 🎨 **Compact Table Design** - User management table redesigned with reduced padding and spacing
- 📊 **No Department for Admin** - Administrator account has no department/designation assignment
- 🔐 **Lock Icon Indicator** - Admin account displays lock icon instead of edit/delete buttons
- 🔄 **Login Route Fix** - Added GET /login route that redirects to role selection

### Database Seeding Update
- ✅ **Simplified UserSeeder** - Now creates only the admin user (previously created 8 sample users)
- ✅ **Updated credentials** - Admin password changed to `password` for consistency
- ✅ **Cleaner setup** - Removed outdated sample users (Director, Deans, Faculty)
- ✅ **Aligned with schema** - Seeder now matches actual database structure

### Vite Integration & Code Refactoring
- ✅ **Extracted all inline CSS/JS** - From 9 blade templates into external files
- ✅ **Configured Vite** - Bundle all assets with hot module replacement
- ✅ **Optimized JavaScript** - Exposed functions to global scope for Vite compatibility
- ✅ **Production-ready builds** - All assets hashed for cache-busting
- ✅ **Fixed mobile menu & notifications** - Now working with bundled assets

### Files Refactored
**CSS Files Created (9):**
- `resources/css/admin_users_edit.css`
- `resources/css/admin_users_index.css`
- `resources/css/admin_users_show.css`
- `resources/css/auth_login.css`
- `resources/css/auth_login-selection.css`
- `resources/css/dashboard_admin_index.css`
- `resources/css/dashboard_faculty_index.css`
- `resources/css/dashboard_faculty_profile.css`
- `resources/css/dashboard_faculty_my-ipcrs.css`

**JS Files Created (8):**
- `resources/js/admin_users_edit.js`
- `resources/js/admin_users_index.js`
- `resources/js/admin_users_show.js`
- `resources/js/auth_login.js`
- `resources/js/dashboard_admin_index.js`
- `resources/js/dashboard_faculty_index.js`
- `resources/js/dashboard_faculty_profile.js`
- `resources/js/dashboard_faculty_my-ipcrs.js`

### Technical Improvements
- 🔧 **vite.config.js** - Configured with 17 entry points
- 🔧 **window.function** pattern - All interactive functions exposed globally
- 🔧 **@vite directive** - All blade files now use Laravel Vite plugin
- 🔧 **Build optimization** - Assets reduced from ~200KB to gzipped builds

### UI/UX Enhancements
- 🎨 **Split-Screen Login Design** - Modern login page with title on left, form on right
- 🌊 **Fixed Blob Animations** - Eliminated visual artifacts during scrolling
- 📱 **Mobile Scrolling Fix** - Proper overflow handling for mobile devices
- 🎯 **Responsive Layout** - Optimized for all screen sizes (mobile, tablet, desktop)

### Developer Experience
- 🔧 **Laravel Blade Snippets** - Recommended VS Code extension for better IDE support
  - Install: "Laravel Blade Snippets" by Winnie Lin
  - Eliminates false route() parameter warnings
  - Provides autocomplete for Blade directives
  - Improves syntax highlighting

---

## 🚀 Quick Start

For detailed installation instructions, see **[INSTRUCTIONS.md](INSTRUCTIONS.md)**

### Prerequisites
- PHP >= 8.1 with GD Extension
- Composer
- Node.js & NPM (v16+)
- MySQL/MariaDB
- XAMPP (recommended for Windows)

### Quick Installation

```bash
# Clone repository
git clone https://github.com/jarlokenpaghubasan/IPCR.git
cd IPCR

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env, then:
php artisan migrate
php artisan db:seed
php artisan storage:link

# Build and run
npm run build
php artisan serve
```

**Access:** http://localhost:8000  
**Login:** `admin` / `password`

### Cloudinary Setup (Required for Photo Uploads)

Add these to your `.env` file:

```env
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
CLOUDINARY_URL=cloudinary://your_api_key:your_api_secret@your_cloud_name
```

See [INSTRUCTIONS.md](INSTRUCTIONS.md) for detailed Cloudinary setup guide.

---

## 🎯 Usage

### First Time Login

1. **Start the server** (if not already running):
   ```bash
   php artisan serve
   ```

2. **Open your browser** and go to: http://localhost:8000

3. **Select role:** Click on "Admin" from the login selection page

4. **Enter credentials:**
   - **Username:** `admin`
   - **Password:** `password`

5. **You're in!** You'll be redirected to the admin dashboard

### Admin Panel Features

After logging in as admin, you can:

**User Management:**
- ✅ Create new users (Faculty, Dean, Director, or additional Admins)
- ✅ Edit user information (except the main Administrator account)
- ✅ Assign multiple roles to users
- ✅ Upload profile photos for users
- ✅ Activate/Deactivate user accounts
- ✅ Delete users (except the main Administrator account)
- ✅ View detailed user information
- ✅ Filter users by department
- ✅ Search users by name, email, or username

**Dashboard:**
- View total users count
- View active/inactive users
- View users by role (Admin, Director, Dean, Faculty)
- Quick access to user management

**Protected Administrator Account:**
- The main admin account (URS26-ADM00001) is protected
- Cannot be edited, deleted, or deactivated
- Displayed with a 🔒 lock icon in the user list
- Ensures system always has at least one administrator

### Faculty Dashboard

Faculty users can:
- View performance metrics
- Access IPCR forms
- Manage their profile
- Change password
- Update profile photo
- View notifications

### Creating Additional Users

1. Go to **User Management**
2. Click **"Create New User"**
3. Fill in the form:
   - Name, Email, Username
   - Password (minimum 8 characters)
   - Phone number (optional)
   - Select one or more roles
   - Select department (required for Faculty/Dean)
   - Select designation
   - Set active status
4. Click **"Create User"**
5. Employee ID will be auto-generated based on department

---

## 📁 Project Structure

```
ipcr_system/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Admin panel controllers
│   │   ├── Auth/            # Authentication controllers
│   │   └── Dashboard/       # Dashboard controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Middleware/          # Custom middleware
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── public/
│   ├── images/              # Public images (logo, etc.)
│   └── storage/             # Symlinked storage folder
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
├── routes/
│   └── web.php              # Web routes
└── storage/
    └── app/
        └── public/
            └── user_photos/ # User uploaded photos
```

---

## 🔧 Troubleshooting

---

## 🔧 Troubleshooting

For detailed troubleshooting, see **[INSTRUCTIONS.md](INSTRUCTIONS.md)**

### Quick Fixes

```bash
# Clear all caches
php artisan optimize:clear

# Rebuild assets
npm run build

# Reset database (WARNING: deletes all data)
php artisan migrate:fresh --seed

# Regenerate autoload
composer dump-autoload
```

---

## 🛠️ Development

### Running the Application

**Production Mode (Recommended for normal use):**
```bash
# Build optimized assets (only need to run once or after code changes)
npm run build

# Start Laravel development server
php artisan serve
```
Access at: http://localhost:8000

**Development Mode with Hot Reload (For developers making changes):**

Open **two separate terminal windows**:

```bash
# Terminal 1 - Vite dev server with hot reload
npm run dev

# Terminal 2 - Laravel development server
cd C:\xampp\htdocs\IPCR  # Navigate to project folder first
php artisan serve
```

**What's the difference?**
- `npm run build` - Builds assets once, optimized and minified for production
- `npm run dev` - Watches for file changes and auto-reloads browser (useful when editing CSS/JS)

### Running Tests

```bash
php artisan test
```

### Clearing Cache

If you encounter strange behavior or old data showing up:

```bash
# Clear all caches at once
php artisan optimize:clear

# Or clear individually
php artisan cache:clear      # Application cache
php artisan config:clear     # Configuration cache
php artisan route:clear      # Route cache
php artisan view:clear       # Compiled views cache
```

### Asset Management with Vite

```bash
# Development with hot module replacement (auto-refresh)
npm run dev

# Production build (optimized, minified, hashed)
npm run build

# Preview production build locally
npm run preview
```

**Vite Features:**
- ⚡ Lightning-fast hot module replacement (HMR)
- 📦 Optimized production builds with tree-shaking
- 🔄 Auto-refresh browser on file changes (with npm run dev)
- 🎯 Cache-busting with content hashes
- 📊 Build size analysis

### Database Management

**Reset database completely:**
```bash
php artisan migrate:fresh --seed
```
⚠️ **Warning:** This deletes ALL data and recreates tables with fresh seed data

**Add new migration:**
```bash
php artisan make:migration create_something_table
```

**Rollback last migration:**
```bash
php artisan migrate:rollback
```

### Code Generation

```bash
# Create new controller
php artisan make:controller NameController

# Create new model
php artisan make:model ModelName

# Create model with migration
php artisan make:model ModelName -m

# Create new seeder
php artisan make:seeder NameSeeder
```

---

## 📦 Technologies Used

- **Backend:** Laravel 11.x
- **Frontend:** Tailwind CSS v4.0, Blade Templates
- **Build Tool:** Vite 7.x with Laravel plugin
- **Database:** MySQL
- **Charts:** Chart.js
- **Authentication:** Laravel Breeze (customized)
- **Icons:** Font Awesome 6.4.0

---

## 👥 User Roles

| Role | Permissions |
|------|-------------|
| **Admin** | Full system access, user management, all configurations |
| **Director** | View director-level reports and dashboards |
| **Dean** | View dean-level reports and dashboards |
| **Faculty** | View personal dashboard, submit IPCRs, manage profile |

---

## 🔐 Security

- Passwords are hashed using bcrypt
- CSRF protection enabled
- XSS protection via Blade templating
- SQL injection protection via Eloquent ORM
- Role-based access control (RBAC)
- Session management with database driver

---

## 📄 License

This project is open-sourced software licensed under the MIT license.

---

## 👨‍💻 Developer

**Developed by:** Jarlo Ken E. Paghubasan  
**GitHub:** [@jarlokenpaghubasan](https://github.com/jarlokenpaghubasan)

---

## 📞 Support

For issues and questions:
1. Check the troubleshooting section above
2. Create an issue on GitHub
3. Contact the developer

---

**Happy Coding! 🚀**
