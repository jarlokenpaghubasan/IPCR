<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Dashboard\FacultyDashboardController;
use App\Http\Controllers\Dashboard\DeanDashboardController;
use App\Http\Controllers\Dashboard\DirectorDashboardController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Admin\DatabaseManagementController;
use App\Http\Controllers\Faculty\IpcrTemplateController;
use App\Http\Controllers\Faculty\IpcrSubmissionController;
use App\Http\Controllers\Faculty\IpcrSavedCopyController;
use App\Http\Controllers\Faculty\OpcrTemplateController;
use App\Http\Controllers\Faculty\OpcrSubmissionController;
use App\Http\Controllers\Faculty\OpcrSavedCopyController;
use App\Http\Controllers\Dean\DeanReviewController;
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

// Email Verification Routes
Route::post('/email/verification/send', [EmailVerificationController::class, 'sendVerificationCode'])
    ->name('verification.send')
    ->middleware('auth');

Route::post('/email/verification/verify', [EmailVerificationController::class, 'verifyCode'])
    ->name('verification.verify')
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

Route::post('/faculty/ipcr/submissions/{id}/unsubmit', [IpcrSubmissionController::class, 'unsubmit'])
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

// OPCR Template Routes (Dean only via faculty gate)
Route::get('/faculty/opcr/templates', [OpcrTemplateController::class, 'index'])
    ->name('faculty.opcr.templates.index')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/store', [OpcrTemplateController::class, 'store'])
    ->name('faculty.opcr.store')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/templates/from-saved-copy', [OpcrTemplateController::class, 'storeFromSavedCopy'])
    ->name('faculty.opcr.templates.from-saved-copy')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/templates/{id}/save-copy', [OpcrTemplateController::class, 'saveCopy'])
    ->name('faculty.opcr.templates.save-copy')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/opcr/templates/{id}', [OpcrTemplateController::class, 'show'])
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/opcr/templates/{id}', [OpcrTemplateController::class, 'destroy'])
    ->middleware(['auth', 'role:faculty']);

Route::put('/faculty/opcr/templates/{id}', [OpcrTemplateController::class, 'update'])
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/templates/{id}/set-active', [OpcrTemplateController::class, 'setActive'])
    ->middleware(['auth', 'role:faculty']);

// OPCR Submission Routes
Route::post('/faculty/opcr/submissions', [OpcrSubmissionController::class, 'store'])
    ->name('faculty.opcr.submissions.store')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/opcr/submissions/{id}', [OpcrSubmissionController::class, 'show'])
    ->middleware(['auth', 'role:faculty']);

Route::put('/faculty/opcr/submissions/{id}', [OpcrSubmissionController::class, 'update'])
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/submissions/{id}/set-active', [OpcrSubmissionController::class, 'setActive'])
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/submissions/{id}/deactivate', [OpcrSubmissionController::class, 'deactivate'])
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/submissions/{id}/unsubmit', [OpcrSubmissionController::class, 'unsubmit'])
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/opcr/submissions/{id}', [OpcrSubmissionController::class, 'destroy'])
    ->middleware(['auth', 'role:faculty,admin']);

// OPCR Saved Copy Routes
Route::get('/faculty/opcr/saved-copies', [OpcrSavedCopyController::class, 'index'])
    ->name('faculty.opcr.saved-copies.index')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/opcr/saved-copies', [OpcrSavedCopyController::class, 'store'])
    ->name('faculty.opcr.saved-copies.store')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/opcr/saved-copies/{id}', [OpcrSavedCopyController::class, 'show'])
    ->name('faculty.opcr.saved-copies.show')
    ->middleware(['auth', 'role:faculty']);

Route::put('/faculty/opcr/saved-copies/{id}', [OpcrSavedCopyController::class, 'update'])
    ->name('faculty.opcr.saved-copies.update')
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/opcr/saved-copies/{id}', [OpcrSavedCopyController::class, 'destroy'])
    ->name('faculty.opcr.saved-copies.destroy')
    ->middleware(['auth', 'role:faculty']);

// Supporting Document Routes
Route::get('/faculty/supporting-documents', [\App\Http\Controllers\Faculty\SupportingDocumentController::class, 'index'])
    ->name('faculty.supporting-documents.index')
    ->middleware(['auth', 'role:faculty']);

Route::post('/faculty/supporting-documents', [\App\Http\Controllers\Faculty\SupportingDocumentController::class, 'store'])
    ->name('faculty.supporting-documents.store')
    ->middleware(['auth', 'role:faculty']);

Route::delete('/faculty/supporting-documents/{id}', [\App\Http\Controllers\Faculty\SupportingDocumentController::class, 'destroy'])
    ->name('faculty.supporting-documents.destroy')
    ->middleware(['auth', 'role:faculty']);

Route::put('/faculty/supporting-documents/{id}/rename', [\App\Http\Controllers\Faculty\SupportingDocumentController::class, 'rename'])
    ->name('faculty.supporting-documents.rename')
    ->middleware(['auth', 'role:faculty']);

Route::get('/faculty/supporting-documents/{id}/download', [\App\Http\Controllers\Faculty\SupportingDocumentController::class, 'download'])
    ->name('faculty.supporting-documents.download')
    ->middleware(['auth', 'role:faculty']);


/*
|--------------------------------------------------------------------------
| Dean Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('/dean/dashboard', [DeanDashboardController::class, 'index'])
    ->name('dean.dashboard')
    ->middleware(['auth', 'role:dean']);

// Dean IPCR Review Routes
Route::get('/dean/review/faculty-submissions', [DeanReviewController::class, 'facultySubmissions'])
    ->name('dean.review.faculty-submissions')
    ->middleware(['auth', 'role:dean']);

Route::get('/dean/review/faculty-submissions/{id}', [DeanReviewController::class, 'showFacultySubmission'])
    ->name('dean.review.faculty-submission.show')
    ->middleware(['auth', 'role:dean']);

Route::get('/dean/review/dean-submissions', [DeanReviewController::class, 'deanSubmissions'])
    ->name('dean.review.dean-submissions')
    ->middleware(['auth', 'role:dean']);

Route::get('/dean/review/dean-submissions/{id}', [DeanReviewController::class, 'showDeanSubmission'])
    ->name('dean.review.dean-submission.show')
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

    // Database Management
    Route::get('database', [DatabaseManagementController::class, 'index'])->name('database.index');
    Route::post('database/backup', [DatabaseManagementController::class, 'backup'])->name('database.backup');
    Route::get('database/download/{filename}', [DatabaseManagementController::class, 'download'])->name('database.download');
    Route::post('database/restore/{filename}', [DatabaseManagementController::class, 'restore'])->name('database.restore');
    Route::delete('database/{filename}', [DatabaseManagementController::class, 'delete'])->name('database.delete');
    Route::post('database/upload', [DatabaseManagementController::class, 'upload'])->name('database.upload');
    Route::post('database/settings', [DatabaseManagementController::class, 'updateSettings'])->name('database.settings');

    // Activity Logs
    Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
});