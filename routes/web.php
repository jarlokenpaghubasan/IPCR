<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Dashboard\FacultyDashboardController;
use App\Http\Controllers\Dashboard\DeanDashboardController;
use App\Http\Controllers\Dashboard\DirectorDashboardController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Faculty\IpcrTemplateController;
use App\Http\Controllers\Faculty\IpcrSubmissionController;
use App\Http\Controllers\Faculty\IpcrSavedCopyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'showLoginSelection'])
    ->name('login.selection')
    ->middleware('guest');

Route::get('/login', function () {
    return redirect()->route('login.selection');
})->middleware('guest');

Route::get('/login/{role}', [LoginController::class, 'showLoginForm'])
    ->name('login.form')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login')
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])
    ->name('password.request')
    ->middleware('guest');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])
    ->name('password.email')
    ->middleware('guest');

Route::get('/verify-code', [PasswordResetController::class, 'showVerifyCodeForm'])
    ->name('password.verify.form')
    ->middleware('guest');

Route::post('/verify-code', [PasswordResetController::class, 'verifyCode'])
    ->name('password.verify')
    ->middleware('guest');

Route::get('/reset-password', [PasswordResetController::class, 'showResetPasswordForm'])
    ->name('password.reset.form')
    ->middleware('guest');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update')
    ->middleware('guest');


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

Route::patch('/faculty/profile/update', [FacultyDashboardController::class, 'updateProfile'])
    ->name('faculty.profile.update')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/profile/photo/upload', [FacultyDashboardController::class, 'uploadPhoto'])
    ->name('faculty.profile.photo.upload')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/profile/photos', [FacultyDashboardController::class, 'getPhotos'])
    ->name('faculty.profile.photos')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/profile/photo/set-profile', [FacultyDashboardController::class, 'setProfilePhoto'])
    ->name('faculty.profile.photo.set-profile')
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/profile/photo/{id}', [FacultyDashboardController::class, 'deletePhoto'])
    ->name('faculty.profile.photo.delete')
    ->middleware(['auth', 'role:faculty']);

// IPCR Template Routes
Route::get('/faculty/ipcr/templates', [IpcrTemplateController::class, 'index'])
    ->name('faculty.ipcr.templates.index')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/ipcr/store', [IpcrTemplateController::class, 'store'])
    ->name('faculty.ipcr.store')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/ipcr/templates/from-saved-copy', [IpcrTemplateController::class, 'storeFromSavedCopy'])
    ->name('faculty.ipcr.templates.from-saved-copy')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/ipcr/templates/{id}/save-copy', [IpcrTemplateController::class, 'saveCopy'])
    ->name('faculty.ipcr.templates.save-copy')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/ipcr/templates/{id}', [IpcrTemplateController::class, 'show'])
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/ipcr/templates/{id}', [IpcrTemplateController::class, 'destroy'])
    ->middleware(['auth', 'role:faculty']);

Route::put('/faculty/ipcr/templates/{id}', [IpcrTemplateController::class, 'update'])
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/ipcr/templates/{id}/set-active', [IpcrTemplateController::class, 'setActive'])
    ->middleware(['auth', 'role:faculty']);

// IPCR Submission Routes
Route::post('/faculty/ipcr/submissions', [IpcrSubmissionController::class, 'store'])
    ->name('faculty.ipcr.submissions.store')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/ipcr/submissions/{id}', [IpcrSubmissionController::class, 'show'])
    ->middleware(['auth', 'role:faculty']);

Route::put('/faculty/ipcr/submissions/{id}', [IpcrSubmissionController::class, 'update'])
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/ipcr/submissions/{id}/set-active', [IpcrSubmissionController::class, 'setActive'])
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/ipcr/submissions/{id}/deactivate', [IpcrSubmissionController::class, 'deactivate'])
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/ipcr/submissions/{id}', [IpcrSubmissionController::class, 'destroy'])
    ->middleware(['auth', 'role:faculty,admin']);

// IPCR Saved Copy Routes
Route::get('/faculty/ipcr/saved-copies', [IpcrSavedCopyController::class, 'index'])
    ->name('faculty.ipcr.saved-copies.index')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/ipcr/saved-copies', [IpcrSavedCopyController::class, 'store'])
    ->name('faculty.ipcr.saved-copies.store')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/ipcr/saved-copies/{id}', [IpcrSavedCopyController::class, 'show'])
    ->name('faculty.ipcr.saved-copies.show')
    ->middleware(['auth', 'role:faculty']);

Route::put('/faculty/ipcr/saved-copies/{id}', [IpcrSavedCopyController::class, 'update'])
    ->name('faculty.ipcr.saved-copies.update')
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/ipcr/saved-copies/{id}', [IpcrSavedCopyController::class, 'destroy'])
    ->name('faculty.ipcr.saved-copies.destroy')
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