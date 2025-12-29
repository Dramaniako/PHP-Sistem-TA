<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\TUController;
// PENTING: Import Controller Sidang Mahasiswa yang baru dibuat
use App\Http\Controllers\Mahasiswa\SidangMahasiswaController;
use App\Http\Controllers\Dosen\SidangDosenController;
use App\Http\Controllers\Dosen\MonitoringController;
use App\Http\Controllers\Koordinator\PenetapanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- FITUR DOSEN/KAPRODI (Existing) ---
    Route::get('/proposal', [SidangMahasiswaController::class, 'index'])->name('proposal.index');
    Route::get('/monitoring', [SidangMahasiswaController::class, 'index'])->name('monitoring.index');

    // --- AREA KHUSUS MAHASISWA ---
    // Hanya user dengan role 'mahasiswa' yang bisa akses
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->group(function () {
        
        Route::controller(SidangMahasiswaController::class)->prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', 'index')->name('index'); 
            Route::post('/{id}/ajukan-perubahan', 'ajukanPerubahan')->name('reschedule');
        });

    });


    // --- AREA KHUSUS KOORDINATOR ---
    // Hanya user dengan role 'koordinator' yang bisa akses
    Route::middleware(['role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function() {
        
        Route::get('/approval', [KoordinatorController::class, 'index'])->name('approval');
        Route::post('/approval/{id}/approve', [KoordinatorController::class, 'approve'])->name('approve');
        Route::post('/approval/{id}/reject', [KoordinatorController::class, 'reject'])->name('reject');

    });

    Route::middleware(['auth', 'role:koordinator'])->prefix('dosen')->name('dosen.')->group(function() {
    
        // Halaman Form Tambah Jadwal
        Route::get('/sidang/create', [SidangDosenController::class, 'create'])->name('sidang.create');
        // Proses Simpan
        Route::post('/sidang/store', [SidangDosenController::class, 'store'])->name('sidang.store');

    });

    // 1. Route Mahasiswa (Untuk Submit Judul)
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function() {
        // ... route sidang sebelumnya ...
        Route::get('/proposal/create', [App\Http\Controllers\Mahasiswa\ProposalMahasiswaController::class, 'create'])->name('proposal.create');
        Route::post('/proposal', [App\Http\Controllers\Mahasiswa\ProposalMahasiswaController::class, 'store'])->name('proposal.store');
    });

    // 2. Route Koordinator (Update bagian Penetapan)
    Route::middleware(['role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function() {
        
        // Penetapan sekarang pakai method EDIT dan UPDATE (bukan Create/Store lagi)
        Route::get('/penetapan', [App\Http\Controllers\Koordinator\PenetapanController::class, 'index'])->name('penetapan.index');
        
        // URL terima ID Proposal: /penetapan/{id}/proses
        Route::get('/penetapan/{id}/proses', [App\Http\Controllers\Koordinator\PenetapanController::class, 'edit'])->name('penetapan.edit');
        Route::put('/penetapan/{id}', [App\Http\Controllers\Koordinator\PenetapanController::class, 'update'])->name('penetapan.update');

        // Di dalam group koordinator...

// Halaman Detail
Route::get('/penetapan/{id}/detail', [PenetapanController::class, 'show'])->name('penetapan.show');

// Proses Simpan Review/Keputusan
Route::put('/penetapan/{id}/keputusan', [PenetapanController::class, 'updateKeputusan'])->name('penetapan.keputusan');

// Proses Download File
Route::get('/penetapan/{id}/download', [PenetapanController::class, 'download'])->name('penetapan.download');
    });

    // Route untuk Dosen (Monitoring)
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function() {
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    });
});

// Di dalam group middleware role:dosen
Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function() {
    
    // ... route dosen lainnya ...

    // FITUR BARU: Request Kesediaan
    Route::get('/requests', [App\Http\Controllers\Dosen\RequestController::class, 'index'])->name('request.index');
    Route::post('/requests/{id}/respond', [App\Http\Controllers\Dosen\RequestController::class, 'respond'])->name('request.respond');

});

require __DIR__.'/auth.php';