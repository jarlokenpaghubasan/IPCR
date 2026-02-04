# 🎓 IPCR System

A comprehensive Individual Performance Commitment and Review (IPCR) management system built with Laravel, designed for educational institutions to track and manage faculty performance evaluations.

## ✨ Features

- 🔐 **Multi-Role Authentication** (Admin, Director, Dean, Faculty)
- 📊 **Performance Dashboard** with charts and metrics
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

### ✨ Image Crop & Resize Feature (NEW!)
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

## 🚀 Installation & Setup

### Prerequisites

Make sure you have the following installed on your computer:
- **PHP >= 8.1** with **GD Extension enabled** - [Download PHP](https://www.php.net/downloads)
- **Composer** - [Download Composer](https://getcomposer.org/download/)
- **MySQL/MariaDB** - Database server
- **Node.js & NPM** (v16 or higher) - [Download Node.js](https://nodejs.org/)
- **XAMPP** (recommended for Windows) - [Download XAMPP](https://www.apachefriends.org/)
  - Includes PHP, MySQL, and Apache in one package

**Note:** If using XAMPP, make sure Apache and MySQL services are running before proceeding.

**Important for Image Uploads:**
The system requires the PHP GD extension for image compression and resizing. If using XAMPP:
1. Open `C:\xampp\php\php.ini` in a text editor (as Administrator)
2. Find `;extension=gd` and remove the semicolon to make it `extension=gd`
3. Save the file and restart Apache in XAMPP Control Panel

---

### 📋 Step-by-Step Setup (Fresh Installation on Another Computer)

#### 1️⃣ Clone the Repository

Open your terminal/command prompt and run:

```bash
git clone https://github.com/jarlokenpaghubasan/IPCR.git
cd IPCR
```

**Or download the ZIP file:**
- Download the repository as ZIP from GitHub
- Extract it to your desired location (e.g., `C:\xampp\htdocs\IPCR` for Windows)
- Open terminal/command prompt and navigate to the project folder

---

#### 2️⃣ Install PHP Dependencies

Make sure you're in the project directory, then run:

```bash
composer install
```

**If you encounter errors:**
- Make sure Composer is installed and in your PATH
- Check that PHP version is 8.1 or higher: `php -v`
- For Windows XAMPP users, you may need to add PHP to PATH or use the full path: `C:\xampp\php\php.exe composer.phar install`

---

#### 3️⃣ Install NPM Dependencies

```bash
npm install
```

**If you encounter errors:**
- Make sure Node.js and NPM are installed: `node -v` and `npm -v`
- Try clearing npm cache: `npm cache clean --force`

---

#### 4️⃣ Environment Configuration

**For Windows (PowerShell):**
```powershell
Copy-Item .env.example .env
```

**For Linux/Mac:**
```bash
cp .env.example .env
```

**Edit the `.env` file** with a text editor and configure your database:

```env
APP_NAME="IPCR System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Hong_Kong
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ipcr_system
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
```

**Generate Application Key:**

```bash
php artisan key:generate
```

This will automatically add the APP_KEY to your .env file.

---

#### 5️⃣ Database Setup

**Option A: Using phpMyAdmin (Recommended for XAMPP users)**

1. Start XAMPP Control Panel
2. Start Apache and MySQL services
3. Open phpMyAdmin: http://localhost/phpmyadmin
4. Click "New" in the left sidebar
5. Database name: `ipcr_system`
6. Collation: `utf8mb4_unicode_ci`
7. Click "Create"

**Option B: Using MySQL Command Line**

```bash
# For XAMPP on Windows
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE ipcr_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# For Linux/Mac
mysql -u root -e "CREATE DATABASE ipcr_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Run Migrations:**

```bash
php artisan migrate
```

This will create all necessary tables:
- users
- departments
- designations
- user_roles
- user_photos
- sessions
- cache
- migrations

---

#### 6️⃣ Storage Setup

Create the symbolic link for file storage:

**For Windows (PowerShell - Run as Administrator):**
```powershell
php artisan storage:link
```

**For Linux/Mac:**
```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for user photo uploads.

**If you encounter permission errors on Windows:**
- Right-click PowerShell/Command Prompt and select "Run as Administrator"
- Or manually create the folder: `mkdir storage\app\public\user_photos`

---

#### 7️⃣ Seed Database with Admin User

Run the database seeder:

```bash
php artisan db:seed
```

This will create:
- **3 Departments:** College of Accountancy (COA), College of Business (COB), College of Computer Studies (CCS)
- **4 Designations:** Professor, Associate Professor, Assistant Professor, Instructor
- **1 Admin User:** Administrator account

**Default Admin Credentials:**
- **Username:** `admin`
- **Password:** `password`
- **Employee ID:** `URS26-ADM00001`
- **Email:** `admin@ipcr.system`

**⚠️ Important:** This admin account is protected and cannot be edited or deleted through the UI.

---

#### 8️⃣ Create Logo Image Folder

**For Windows (PowerShell):**
```powershell
New-Item -Path "public/images" -ItemType Directory -Force
```

**For Linux/Mac:**
```bash
mkdir -p public/images
```

**Add your logo (Optional):**
- Place your logo image as `public/images/urs_logo.jpg`
- Recommended size: 100x100px or similar square/rectangular logo
- If no logo is provided, the system will work without it

---

#### 9️⃣ Build Frontend Assets

Before running the application, you must build the CSS and JavaScript assets:

```bash
npm run build
```

This command:
- Compiles all CSS and JavaScript files
- Optimizes and minifies assets for production
- Creates versioned files in `public/build/` directory
- Should take about 30 seconds to complete

**Important:** You must run this command at least once before starting the server, or assets won't load properly.

---

#### 🔟 Start the Laravel Development Server

**Navigate to the project directory** (very important!):

```bash
# Make sure you're in the IPCR folder, not the parent folder
cd C:\xampp\htdocs\IPCR  # Example for Windows
# OR
cd /path/to/IPCR  # Example for Linux/Mac
```

**Start the server:**

```bash
php artisan serve
```

**You should see:**
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server
```

**Access the application:**
- Open your browser and go to: **http://localhost:8000** or **http://127.0.0.1:8000**

**Common Issues:**
- **Error: "No such file or directory"** → Make sure you're in the project directory
- **Port 8000 already in use** → Stop other Laravel servers or use a different port: `php artisan serve --port=8001`
- **Assets not loading** → Make sure you ran `npm run build`

---

## 🔄 Running on Another Computer (Quick Setup Guide)

If you're transferring this project to another computer:

### 1. Copy Files
- Copy the entire project folder to the new computer
- **Or** clone from GitHub: `git clone https://github.com/jarlokenpaghubasan/IPCR.git`

### 2. Install Prerequisites
- Install PHP >= 8.1, Composer, Node.js, and MySQL
- **For XAMPP users on Windows:**
  1. Download and install XAMPP
  2. Open `C:\xampp\php\php.ini` in a text editor (Run as Administrator)
  3. Find `;extension=gd` and change it to `extension=gd` (remove the semicolon)
  4. Save and restart Apache in XAMPP Control Panel
  5. Verify GD is enabled: `php -m | findstr GD`

### 3. Install Dependencies
```bash
cd IPCR  # Navigate to project folder
composer install
npm install
```

### 4. Environment Setup
```bash
# For Windows (PowerShell)
Copy-Item .env.example .env

# For Linux/Mac
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure Database
Edit `.env` file and update these values:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ipcr_system
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Create Database
**Using phpMyAdmin (XAMPP users):**
- Open http://localhost/phpmyadmin
- Click "New" → Create database named `ipcr_system`
- Collation: `utf8mb4_unicode_ci`

**Or using command line:**
```bash
# Windows XAMPP
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE ipcr_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Linux/Mac
mysql -u root -e "CREATE DATABASE ipcr_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 7. Run Migrations & Seed Data
```bash
php artisan migrate
php artisan db:seed
```

This creates:
- All database tables
- 3 Departments (COA, COB, CCS)
- 4 Designations (Professor levels)
- 1 Admin user (username: `admin`, password: `password`)

### 8. Setup Storage
```bash
# Run as Administrator on Windows
php artisan storage:link
```

### 9. Build Frontend Assets
```bash
npm run build
```

This compiles and optimizes all CSS/JS files. **Must be done before starting the server!**

### 10. Start the Server
```bash
php artisan serve
```

Access at: **http://localhost:8000**

**Login with:**
- Username: `admin`
- Password: `password`

---

### Troubleshooting Common Issues

**"GD extension not found" error when uploading photos:**
- Enable GD in `php.ini` (see step 2 above)
- Restart Apache
- Verify: `php -m | findstr GD` (Windows) or `php -m | grep GD` (Linux/Mac)

**Assets not loading (blank page):**
- Make sure you ran `npm run build`
- Check that `public/build/` folder exists

**Port 8000 already in use:**
- Use different port: `php artisan serve --port=8001`

**Storage link error on Windows:**
- Run PowerShell/CMD as Administrator
- Or manually create: `mkdir storage\app\public\user_photos`

**Database connection error:**
- Verify MySQL is running in XAMPP
- Check `.env` database credentials
- Ensure database `ipcr_system` exists

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

### Issue: "Please provide a valid cache path"

```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: Storage link not working

```bash
# For Windows (Run PowerShell as Administrator)
Remove-Item public/storage -Force -ErrorAction SilentlyContinue
php artisan storage:link

# For Linux/Mac
rm -f public/storage
php artisan storage:link
```

### Issue: Migration errors

```bash
# Rollback and re-migrate (WARNING: This will delete all data)
php artisan migrate:fresh

# Then seed again
php artisan db:seed
```

### Issue: "Class not found" errors

```bash
# Regenerate autoload files
composer dump-autoload

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Issue: Assets (CSS/JS) not loading

```bash
# Rebuild assets
npm run build

# If that doesn't work, clear node_modules and reinstall
rm -rf node_modules
npm install
npm run build
```

### Issue: Permission denied (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue: Cannot run php artisan serve

**Problem:** Command not found or not running from correct directory

**Solution:**
1. Make sure you're in the project directory:
   ```bash
   cd C:\xampp\htdocs\IPCR  # Windows
   cd /path/to/IPCR  # Linux/Mac
   ```
2. Verify you're in the right folder (should see artisan file):
   ```bash
   ls  # Linux/Mac
   dir  # Windows
   ```
3. Check PHP is in PATH:
   ```bash
   php -v
   ```

### Issue: Port 8000 already in use

```bash
# Use a different port
php artisan serve --port=8001

# Or stop the existing server:
# Press Ctrl+C in the terminal running the server
```

### Issue: Database connection error

1. **Check XAMPP services:** Make sure MySQL is running in XAMPP Control Panel
2. **Verify .env settings:** Check DB_DATABASE, DB_USERNAME, DB_PASSWORD
3. **Test MySQL connection:**
   ```bash
   # For XAMPP on Windows
   C:\xampp\mysql\bin\mysql.exe -u root
   
   # For Linux/Mac
   mysql -u root
   ```
4. **Verify database exists:**
   ```sql
   SHOW DATABASES;
   ```

### Issue: "npm command not found"

**Solution:** Install Node.js from https://nodejs.org/
- Download LTS version
- Install with default settings
- Restart terminal/command prompt
- Verify: `node -v` and `npm -v`

### Issue: "composer command not found"

**Solution:** Install Composer from https://getcomposer.org/download/
- For Windows: Download and run Composer-Setup.exe
- For Linux/Mac: Follow installation instructions on website
- Restart terminal/command prompt
- Verify: `composer -v`

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
