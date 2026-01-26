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

## 📦 Latest Updates (January 27, 2026)

### Vite Integration & Code Refactoring
- ✅ **Extracted all inline CSS/JS** from 9 blade templates into external files
- ✅ **Configured Vite** to bundle all assets with hot module replacement
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

---

## 🚀 Installation & Setup

### Prerequisites

Make sure you have the following installed:
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)
- XAMPP (recommended for Windows)

---

### 📋 Step-by-Step Setup

#### 1️⃣ Clone the Repository

```bash
git clone https://github.com/jarlokenpaghubasan/IPCR.git
cd IPCR
```

---

#### 2️⃣ Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install

# Build frontend assets
npm run build
```

---

#### 3️⃣ Environment Configuration

Create your `.env` file from the example:

```bash
cp .env.example .env
```

**Edit the `.env` file** with your database credentials:

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

---

#### 4️⃣ Database Setup

**Create Database:**

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `ipcr_system`

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

---

#### 5️⃣ Storage Setup

Create the symbolic link for file storage:

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`.

---

#### 6️⃣ Create Admin User

Run the database seeder to create departments, designations, and a default admin user:

```bash
php artisan db:seed
```

**OR** Create an admin user manually via Tinker:

```bash
php artisan tinker
```

Then paste this code:

```php
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

// Create admin user
$admin = User::create([
    'first_name' => 'Admin',
    'middle_name' => '',
    'last_name' => 'User',
    'employee_id' => 'URS26-ADM00001',
    'username' => 'admin',
    'email' => 'admin@ipcr.system',
    'password' => Hash::make('password123'),
    'department_id' => 1,
    'designation_id' => 1,
    'phone_number' => '09123456789',
    'is_active' => true,
]);

// Assign admin role
UserRole::create([
    'user_id' => $admin->id,
    'role_name' => 'admin',
    'is_primary' => true,
]);

echo "Admin user created successfully!\n";
echo "Username: admin\n";
echo "Password: password123\n";
exit;
```

**Default Admin Credentials:**
- **Username:** `admin`
- **Password:** `password123`

---

#### 7️⃣ Create Logo Image Folder

Create the images folder for the system logo:

```bash
# For Windows (PowerShell)
New-Item -Path "public/images" -ItemType Directory -Force

# For Linux/Mac
mkdir -p public/images
```

**Add your logo:**
- Place your logo image as `public/images/urs_logo.jpg`
- Recommended size: 100x100px or similar square/rectangular logo

---

#### 8️⃣ Start the Development Server

**For Development (with Vite Hot Reload):**

Open **two terminal windows**:

```bash
# Terminal 1 - Start Vite development server
npm run dev

# Terminal 2 - Start Laravel server
php artisan serve
```

**For Production:**

```bash
# Build optimized assets first
npm run build

# Then start Laravel server
php artisan serve
```

The application will be available at: **http://localhost:8000**

**What each command does:**
- `npm run dev` - Starts Vite with hot module replacement (auto-refreshes on file changes)
- `npm run build` - Creates optimized production assets in `public/build/`
- `php artisan serve` - Runs Laravel development server

---

## 🎯 Usage

### Login

1. Go to http://localhost:8000
2. Select your role (Admin, Director, Dean, or Faculty)
3. Enter credentials:
   - **Admin:** `admin` / `password123`

### Admin Panel

After logging in as admin, you can:
- Manage users (Create, Edit, Delete)
- Assign roles to users
- Upload profile photos
- Activate/Deactivate users
- View user details

### Faculty Dashboard

Faculty users can:
- View performance metrics
- Access IPCR forms
- Manage their profile
- Change password
- View notifications

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
# Remove old link
rm public/storage

# Create new link
php artisan storage:link
```

### Issue: Migration errors

```bash
# Rollback and re-migrate
php artisan migrate:fresh

# Then seed again
php artisan db:seed
```

### Issue: Permission denied (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🛠️ Development

### Running the Application

**Development Mode (Recommended):**
```bash
# Terminal 1 - Vite dev server with hot reload
npm run dev

# Terminal 2 - Laravel development server
php artisan serve
```
Access at: http://localhost:8000

**Production Build:**
```bash
# Build optimized assets
npm run build

# Start server
php artisan serve
```

### Running Tests

```bash
php artisan test
```

### Clearing Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Asset Management with Vite

```bash
# Development with hot module replacement
npm run dev

# Production build (optimized, minified, hashed)
npm run build
```

**Vite Features:**
- ⚡ Lightning-fast hot module replacement (HMR)
- 📦 Optimized production builds with tree-shaking
- 🔄 Auto-refresh browser on file changes
- 🎯 Cache-busting with content hashes
- 📊 Build size analysis

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
