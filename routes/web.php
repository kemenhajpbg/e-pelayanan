<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\NewsReportController;
use App\Http\Controllers\DailyWorkReportController;
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
        Route::get('/print-daily', [VisitorController::class, 'printDaily'])->name('print-daily');
        Route::get('/print-monthly', [VisitorController::class, 'printMonthly'])->name('print-monthly');
        Route::get('/export-monthly', [VisitorController::class, 'exportMonthly'])->name('export-monthly');
        Route::post('/sync-drive-monthly', [VisitorController::class, 'syncDriveMonthly'])->name('sync-drive-monthly');
        Route::delete('/{visitor}', [VisitorController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsReportController::class, 'index'])->name('index');
        Route::post('/', [NewsReportController::class, 'store'])->name('store');
        Route::post('/generate-ai', [NewsReportController::class, 'generateAi'])->name('generate-ai');
        Route::delete('/{newsReport}', [NewsReportController::class, 'destroy'])->name('destroy');
    });

    // Laporan Kinerja Harian & Rekapitulasi Bulanan
    Route::prefix('daily-reports')->name('daily-reports.')->group(function () {
        Route::get('/', [DailyWorkReportController::class, 'index'])->name('index');
        Route::post('/', [DailyWorkReportController::class, 'store'])->name('store');
        Route::get('/export-monthly', [DailyWorkReportController::class, 'exportMonthly'])->name('export-monthly');
        Route::get('/print-monthly', [DailyWorkReportController::class, 'printMonthly'])->name('print-monthly');
        Route::delete('/{dailyReport}', [DailyWorkReportController::class, 'destroy'])->name('destroy');
    });

    // Pengalihan dari modul lama Surat Masuk & Keluar ke Laporan Kinerja Harian
    Route::get('/letters', function () {
        return redirect()->route('daily-reports.index');
    })->name('letters.index');
});

