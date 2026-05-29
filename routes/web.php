<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Kiosk routes (public access for customer self-service)
Route::prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/', [KioskController::class, 'index'])->name('index');
    Route::post('/ticket', [KioskController::class, 'store'])->name('store');
    Route::get('/services', [KioskController::class, 'services'])->name('services');
    Route::get('/status/{branch}', [KioskController::class, 'branchStatus'])->name('status');
    Route::get('/status', [KioskController::class, 'status'])->name('status.query'); // For query param
});

// Display routes (public access for TV display)
Route::prefix('display')->name('display.')->group(function () {
    Route::get('/{branchId}', [DisplayController::class, 'show'])->name('show');
    Route::get('/{branchId}/data', [DisplayController::class, 'data'])->name('data');
});

// Dashboard redirect based on role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Superadmin routes
    Route::prefix('superadmin')->name('superadmin.')->middleware('role:superadmin')->group(function () {
        Route::get('/', [SuperadminController::class, 'dashboard'])->name('dashboard');
        
        // Branches management
        Route::get('/branches', [SuperadminController::class, 'branches'])->name('branches');
        Route::post('/branches', [SuperadminController::class, 'storeBranch'])->name('branches.store');
        Route::put('/branches/{branch}', [SuperadminController::class, 'updateBranch'])->name('branches.update');
        Route::delete('/branches/{branch}', [SuperadminController::class, 'destroyBranch'])->name('branches.destroy');
        
        // Users management
        Route::get('/users', [SuperadminController::class, 'users'])->name('users');
        Route::post('/users', [SuperadminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [SuperadminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [SuperadminController::class, 'destroyUser'])->name('users.destroy');
        
        // Reports
        Route::get('/reports', [SuperadminController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [SuperadminController::class, 'exportReports'])->name('reports.export');
        
        // Media management
        Route::get('/media', [SuperadminController::class, 'media'])->name('media');
        Route::post('/media', [SuperadminController::class, 'storeMedia'])->name('media.store');
        Route::put('/media/{media}', [SuperadminController::class, 'updateMedia'])->name('media.update');
        Route::delete('/media/{media}', [SuperadminController::class, 'destroyMedia'])->name('media.destroy');
    });

    // Operator routes (Teller, Admin, CS)
    Route::prefix('operator')->name('operator.')->middleware('role:teller,admin,cs')->group(function () {
        Route::get('/', [OperatorController::class, 'dashboard'])->name('dashboard');
        Route::post('/call-next', [OperatorController::class, 'callNext'])->name('call-next');
        Route::post('/finish', [OperatorController::class, 'finish'])->name('finish');
        Route::post('/skip', [OperatorController::class, 'skip'])->name('skip');
        Route::post('/recall', [OperatorController::class, 'recall'])->name('recall');
        Route::get('/status', [OperatorController::class, 'status'])->name('status');
    });
});

require __DIR__.'/auth.php';

