<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\FacultyDashboardController;
use App\Http\Controllers\Dashboard\DeanDashboardController;
use App\Http\Controllers\Dashboard\DirectorDashboardController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PhotoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'showLoginSelection'])
    ->name('login.selection')
    ->middleware('guest');

Route::get('/login/{role}', [LoginController::class, 'showLoginForm'])
    ->name('login.form')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login')
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Faculty Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('/faculty/dashboard', [FacultyDashboardController::class, 'index'])
    ->name('faculty.dashboard')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/my-ipcrs', [FacultyDashboardController::class, 'myIpcrs'])
    ->name('faculty.my-ipcrs')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/profile', [FacultyDashboardController::class, 'profile'])
    ->name('faculty.profile')
    ->middleware(['auth', 'role:faculty']);

Route::patch('/faculty/password/change', [FacultyDashboardController::class, 'changePassword'])
    ->name('faculty.password.change')
    ->middleware(['auth', 'role:faculty']);


/*
|--------------------------------------------------------------------------
| Dean Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('/dean/dashboard', [DeanDashboardController::class, 'index'])
    ->name('dean.dashboard')
    ->middleware(['auth', 'role:dean']);


/*
|--------------------------------------------------------------------------
| Director Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('/director/dashboard', [DirectorDashboardController::class, 'index'])
    ->name('director.dashboard')
    ->middleware(['auth', 'role:director']);


/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth', 'role:admin']);


/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin/panel')->name('admin.')->group(function () {
    // User Management
    Route::resource('users', UserManagementController::class);
    
    Route::patch('users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])
        ->name('users.toggleActive');
    
    // Photo Management
    Route::post('users/{user}/photo/upload', [PhotoController::class, 'upload'])
        ->name('users.photo.upload');
    
    Route::delete('users/{user}/photos/{photo}', [PhotoController::class, 'delete'])
        ->name('users.photo.delete');
    
    Route::patch('users/{user}/photos/{photo}/set-profile', [PhotoController::class, 'setAsProfile'])
        ->name('users.photo.setProfile');
    
    Route::get('users/{user}/photos', [PhotoController::class, 'getUserPhotos'])
        ->name('users.photos.get');
});