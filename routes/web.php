<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Auth Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google')->middleware('throttle:5,1');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Default redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// User Routes (protected)
Route::middleware('auth.session')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/home', [UserController::class, 'home'])->name('home');
        Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/photo', [UserProfileController::class, 'updatePhoto'])->name('profile.photo');
        Route::get('/export/journals', [UserController::class, 'exportJournals'])->name('export.journals');
        Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [UserController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [UserController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
        Route::get('/settings', [UserController::class, 'settings'])->name('settings');
        Route::post('/settings/preferences', [UserController::class, 'updateNotificationPreferences'])->name('settings.preferences');
        Route::post('/settings/theme', [UserController::class, 'updateThemeAndDefaultPage'])->name('settings.theme');
        Route::post('/push-subscriptions', [UserController::class, 'storePushSubscription'])->name('push-subscriptions.store');
        Route::delete('/push-subscriptions', [UserController::class, 'deletePushSubscription'])->name('push-subscriptions.destroy');
        Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
        Route::get('/journals/create', [JournalController::class, 'create'])->name('journals.create');
        Route::post('/journals', [JournalController::class, 'store'])->name('journals.store');
        Route::post('/journals/bulk-delete', [JournalController::class, 'bulkDelete'])->name('journals.bulk-delete');
        Route::get('/journals/{journal}', [JournalController::class, 'show'])->name('journals.show');
        Route::get('/journals/{journal}/edit', [JournalController::class, 'edit'])->name('journals.edit');
        Route::put('/journals/{journal}', [JournalController::class, 'update'])->name('journals.update');
        Route::delete('/journals/{journal}', [JournalController::class, 'destroy'])->name('journals.destroy');
    });
});

// Admin Routes (protected)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/export', [AdminController::class, 'exportOptions'])->name('export.options');
    Route::get('/stats/export', [AdminController::class, 'exportStats'])->name('stats.export');
    Route::get('/export/user-journals', [AdminController::class, 'exportUserJournals'])->name('export.user-journals');
    Route::get('/export/posts', [AdminController::class, 'exportPosts'])->name('export.posts');
    Route::get('/export/admins', [AdminController::class, 'exportAdmins'])->name('export.admins');
    Route::get('/export/employees', [AdminController::class, 'exportEmployees'])->name('export.employees');
    Route::get('/export/journals-detailed', [AdminController::class, 'exportDetailedJournals'])->name('export.journals-detailed');
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::post('/posts/bulk-approve', [AdminController::class, 'bulkApprove'])->name('posts.bulk-approve');
    Route::post('/posts/bulk-revise', [AdminController::class, 'bulkRevise'])->name('posts.bulk-revise');
    Route::get('/posts/{journal}', [AdminController::class, 'showPost'])->name('posts.show');
    Route::post('/posts/{journal}/toggle-received', [AdminController::class, 'toggleJournalReceived'])->name('posts.toggle-received');
    Route::post('/posts/{journal}/cancel-received', [AdminController::class, 'cancelJournalReceived'])->name('posts.cancel-received');
    Route::post('/posts/{journal}/reject', [AdminController::class, 'rejectJournal'])->name('posts.reject');
    Route::delete('/posts/{journal}', [AdminController::class, 'destroyPost'])->name('posts.destroy');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/bulk-delete', [AdminController::class, 'bulkDeleteUsers'])->name('users.bulk-delete');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{id}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::get('/employees/{id}', [AdminController::class, 'employeeDetail'])->name('employees.show');
    Route::post('/employees/reject-all/{id}', [AdminController::class, 'rejectEmployeeJournals'])->name('employees.reject-all');
    
    // New Admin Routes
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings/theme', [AdminController::class, 'updateThemeSettings'])->name('settings.theme');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/read-all', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
    Route::post('/export/backup', [AdminController::class, 'exportBackup'])->name('export.backup');
    Route::post('/export/restore', [AdminController::class, 'restoreFromSql'])->name('export.restore');
    Route::post('/storage/cleanup', [AdminController::class, 'cleanupOldDocuments'])->name('storage.cleanup');
    
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/email', [AdminController::class, 'updateEmail'])->name('profile.email');
    Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [AdminController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/settings/preferences', [AdminController::class, 'updateNotificationPreferences'])->name('settings.preferences');
});
