<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\NewsReportController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\AuthController;

// Rute Autentikasi (Hanya untuk Tamu / Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute Terproteksi (Hanya untuk Admin / Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/export-email', [DashboardController::class, 'exportExcelEmail'])->name('export.email');

    Route::prefix('visitors')->name('visitors.')->group(function () {
        Route::get('/', [VisitorController::class, 'index'])->name('index');
        Route::post('/', [VisitorController::class, 'store'])->name('store');
        Route::delete('/{visitor}', [VisitorController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsReportController::class, 'index'])->name('index');
        Route::post('/', [NewsReportController::class, 'store'])->name('store');
        Route::delete('/{newsReport}', [NewsReportController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('letters')->name('letters.')->group(function () {
        Route::get('/', [LetterController::class, 'index'])->name('index');
        Route::post('/', [LetterController::class, 'store'])->name('store');
        Route::delete('/{letter}', [LetterController::class, 'destroy'])->name('destroy');
    });
});

