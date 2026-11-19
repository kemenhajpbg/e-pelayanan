<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\NewsReportController;
use App\Http\Controllers\DailyWorkReportController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TandaTerimaController;

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

    // Tanda Terima Berkas Pelayanan
    Route::prefix('tanda-terima')->name('tanda-terima.')->group(function () {
        Route::get('/', [TandaTerimaController::class, 'index'])->name('index');
        Route::get('/create', [TandaTerimaController::class, 'create'])->name('create');
        Route::post('/', [TandaTerimaController::class, 'store'])->name('store');
        Route::get('/laporan', [TandaTerimaController::class, 'laporan'])->name('laporan');
        Route::get('/export-excel', [TandaTerimaController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-pdf', [TandaTerimaController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{id}', [TandaTerimaController::class, 'show'])->name('show');
        Route::get('/{id}/print', [TandaTerimaController::class, 'printThermal'])->name('print');
        Route::post('/{id}/status', [TandaTerimaController::class, 'updateStatus'])->name('update-status');
    });

    // Pengalihan dari modul lama Surat Masuk & Keluar ke Laporan Kinerja Harian
    Route::get('/letters', function () {
        return redirect()->route('daily-reports.index');
    })->name('letters.index');
});

// Utilitas Pembaruan & Migrasi Database di Hosting (Bisa diakses langsung jika terjadi kendala tabel)
Route::get('/system/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response("<div style='font-family:sans-serif;max-width:650px;margin:50px auto;padding:30px;background:#0f172a;color:#14b8a6;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.3);'><h2 style='color:#ffffff;margin-top:0;'>Migrasi Database Berhasil!</h2><pre style='background:#1e293b;color:#f8fafc;padding:16px;border-radius:8px;font-size:13px;line-height:1.5;overflow-x:auto;'>" . htmlspecialchars($output ?: 'Seluruh tabel database sudah mutakhir.') . "</pre><div style='margin-top:20px;'><a href='/' style='background:#2dd4bf;color:#0f172a;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:bold;display:inline-block;'>&larr; Buka Dashboard e-Pelayanan</a></div></div>");
    } catch (\Throwable $e) {
        return response("<div style='font-family:sans-serif;max-width:650px;margin:50px auto;padding:30px;background:#450a0a;color:#fca5a5;border-radius:16px;'><h2 style='color:#ffffff;margin-top:0;'>Gagal Migrasi Database:</h2><pre style='background:#1e1e1e;color:#fca5a5;padding:16px;border-radius:8px;font-size:13px;overflow-x:auto;'>" . htmlspecialchars($e->getMessage()) . "</pre><div style='margin-top:20px;'><a href='/' style='background:#991b1b;color:#ffffff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:bold;display:inline-block;'>&larr; Kembali</a></div></div>", 500);
    }
})->name('system.migrate');

