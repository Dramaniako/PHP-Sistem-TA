<?php

use Illuminate\Support\Facades\Route;

// ====================================================
// 1. IMPORTS DARI CURRENT CHANGE (ManajemenProfil)
// ====================================================
// Pastikan folder Controller ini ada sesuai screenshot Anda
use App\Http\Controllers\ManajemenProfile\PageController;
use App\Http\Controllers\ManajemenProfile\AuthController;
use App\Http\Controllers\ManajemenProfile\ProfileController;

// ====================================================
// 2. IMPORTS DARI KODINGAN ANDA (Sistem TA)
// ====================================================
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\Mahasiswa\SidangMahasiswaController;
use App\Http\Controllers\Mahasiswa\ProposalMahasiswaController;
use App\Http\Controllers\Koordinator\PenetapanController;
use App\Http\Controllers\Dosen\SidangDosenController;
use App\Http\Controllers\Dosen\MonitoringController;
use App\Http\Controllers\Dosen\RequestController;

/*
|--------------------------------------------------------------------------
| Web Routes (GABUNGAN)
|--------------------------------------------------------------------------
*/

// --- A. LOGIN & PUBLIC (Menggunakan Tampilan ManajemenProfil) ---
// Mengarahkan root '/' ke halaman login custom
Route::get('/', function() {
    return redirect()->route('login');
});

// Route Login & About dari Branch ManajemenProfil
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']); 
Route::get('/about', [PageController::class, 'about']);

// --- B. PROFILE (Menggunakan Tampilan ManajemenProfil) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// --- C. DASHBOARD & SISTEM TA (Tetap Menggunakan Punya Anda) ---
// Kita gunakan middleware 'auth' agar dashboard terlindungi
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard Utama (Sistem Anda)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. AREA MAHASISWA
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::controller(ProposalMahasiswaController::class)->group(function() {
            Route::get('/proposal/create', 'create')->name('proposal.create');
            Route::post('/proposal', 'store')->name('proposal.store');
        });
        Route::controller(SidangMahasiswaController::class)->prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', 'index')->name('index'); 
            Route::post('/{id}/ajukan-perubahan', 'ajukanPerubahan')->name('reschedule');
        });
    });

    // 3. AREA KOORDINATOR
    Route::middleware(['role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function() {
        Route::controller(KoordinatorController::class)->group(function() {
            Route::get('/approval', 'index')->name('approval');
            Route::post('/approval/{id}/approve', 'approve')->name('approve');
            Route::post('/approval/{id}/reject', 'reject')->name('reject');
        });
        Route::controller(PenetapanController::class)->prefix('penetapan')->name('penetapan.')->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/detail', 'show')->name('show');
            Route::get('/{id}/download', 'download')->name('download');
            Route::get('/{id}/proses', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::put('/{id}/keputusan', 'updateKeputusan')->name('keputusan');
        });
        Route::prefix('../dosen')->name('../dosen.')->group(function() {
            Route::get('/sidang/create', [SidangDosenController::class, 'create'])->name('sidang.create');
            Route::post('/sidang/store', [SidangDosenController::class, 'store'])->name('sidang.store');
        });
    });

    // 4. AREA DOSEN
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function() {
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::controller(RequestController::class)->prefix('requests')->name('request.')->group(function() {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/respond', 'respond')->name('respond');
        });
    });

});

// NOTE: Saya menonaktifkan require auth.php bawaan agar tidak bentrok dengan Login baru
// require __DIR__.'/auth.php';