<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\RegencySyncController;
use App\Http\Controllers\SyncLogController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/prayer-times', [PrayerTimeController::class, 'index'])->name('prayer-times.index');

    Route::resource('/activities', ActivityController::class)->except(['show']);

    Route::resource('/activity-types', ActivityTypeController::class)->except(['show']);

    Route::get('/regency-sync', [RegencySyncController::class, 'index'])->name('regency-sync.index');
    Route::post('/regency-sync/{regency}/sync', [RegencySyncController::class, 'sync'])->name('regency-sync.sync');

    Route::get('/sync-logs', [SyncLogController::class, 'index'])->name('sync-logs.index');
});
