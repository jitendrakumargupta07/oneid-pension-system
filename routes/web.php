<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FraudController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SchemeController as AdminSchemeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ApplicationController as UserApplicationController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PaymentController as UserPaymentController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Admin\AdminManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — OneID Pension System
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin Management
    Route::resource('admins', AdminManagerController::class)->only(['index', 'create', 'store']);

    // Citizens management & verification
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/profiles/{profile}/verify', [AdminUserController::class, 'verify'])->name('profiles.verify');
    Route::get('/search', [AdminUserController::class, 'search'])->name('users.search');

    // Pension applications + document review
    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/review', [AdminApplicationController::class, 'review'])->name('applications.review');
    Route::post('/documents/{document}/review', [AdminApplicationController::class, 'reviewDocument'])->name('documents.review');

    // Pension schemes (CRUD)
    Route::resource('schemes', AdminSchemeController::class)->names('schemes');

    // Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [AdminPaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [AdminPaymentController::class, 'store'])->name('payments.store');

    // Fraud Detection
    Route::get('/fraud', [FraudController::class, 'index'])->name('fraud.index');
    Route::post('/fraud/{alert}/resolve', [FraudController::class, 'resolve'])->name('fraud.resolve');

    // Activity Logs (Audit Trail)
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/payments/pdf', [ReportController::class, 'paymentsPdf'])->name('reports.payments.pdf');
    Route::get('/reports/applications/pdf', [ReportController::class, 'applicationsPdf'])->name('reports.applications.pdf');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->middleware(['auth', 'profile_complete'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/create', [UserProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [UserProfileController::class, 'store'])->name('profile.store');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

    // Pension applications
    Route::get('/applications', [UserApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/create', [UserApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [UserApplicationController::class, 'store'])->name('applications.store');

    // Payments & receipts
    Route::get('/payments', [UserPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}/receipt', [UserPaymentController::class, 'receipt'])->name('payments.receipt');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});
