<?php

use Illuminate\Support\Facades\Route;

// --- 1. Import Controllers ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KoordinatorController;

// Mahasiswa
use App\Http\Controllers\Mahasiswa\SidangMahasiswaController;
use App\Http\Controllers\Mahasiswa\ProposalMahasiswaController;

// Koordinator
use App\Http\Controllers\Koordinator\PenetapanController;
use App\Http\Controllers\Koordinator\UserController;

// Dosen
use App\Http\Controllers\Dosen\SidangDosenController;
use App\Http\Controllers\Dosen\MonitoringController;
use App\Http\Controllers\Dosen\RequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 2. Public Route ---
Route::get('/', function () {
    return redirect()->route('login');
});

// --- 3. Authenticated Routes ---
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- PROFILE ---
    // Menggunakan Route::controller agar lebih ringkas
    Route::controller(ProfileController::class)->group(function () {
        // PENTING: Tambahkan ini jika di navigasi ada link route('profile.index')
        // Jika controller tidak punya method 'index', arahkan ke 'edit' atau buat method index.
        Route::get('/profile/view', 'index')->name('profile.index'); 
        
        // Halaman edit profil (biasanya jadi halaman utama profil)
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        
    });


    // ====================================================
    // A. AREA MAHASISWA
    // ====================================================
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        
        // 1. Proposal (Upload Judul)
        Route::controller(ProposalMahasiswaController::class)->group(function() {
            Route::get('/proposal/status', 'index')->name('proposal.status');
            Route::get('/proposal/create', 'create')->name('proposal.create');
            Route::post('/proposal', 'store')->name('proposal.store');
            Route::get('/proposal/{id}/download', 'download')->name('proposal.download');
        });

        // 2. Jadwal Sidang Saya & Reschedule
        Route::controller(SidangMahasiswaController::class)->prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', 'index')->name('index'); 
            Route::post('/{id}/ajukan-perubahan', 'ajukanPerubahan')->name('reschedule');
        });
    });


    // ====================================================
    // B. AREA KOORDINATOR
    // ====================================================
    // Grup Utama Koordinator
    Route::middleware(['role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function() {
        
        // 1. Approval Reschedule
        Route::controller(KoordinatorController::class)->group(function() {
            Route::get('/approval', 'index')->name('approval');
            Route::post('/approval/{id}/approve', 'approve')->name('approve');
            Route::post('/approval/{id}/reject', 'reject')->name('reject');
        });

        // 2. Penetapan Dosen (Pembimbing & Penguji)
        Route::controller(PenetapanController::class)->prefix('penetapan')->name('penetapan.')->group(function() {
            Route::get('/', 'index')->name('index');           // List
            Route::get('/{id}/detail', 'show')->name('show');  // Detail
            Route::get('/{id}/download', 'download')->name('download'); 
            
            Route::get('/{id}/proses', 'edit')->name('edit');  // Form Penetapan
            Route::put('/{id}', 'update')->name('update');     // Proses Penetapan
            Route::put('/{id}/keputusan', 'updateKeputusan')->name('keputusan');
        });

        Route::controller(UserController::class)->group(function() {
        Route::get('/users', 'index')->name('users.index');
        Route::put('/users/{id}/update-role', 'updateRole')->name('users.update-role');
        });
    });

    // [KHUSUS] Buat Jadwal Sidang (Akses Koordinator, tapi Nama Route 'dosen.sidang...')
    // Kita pisahkan dari grup di atas agar nama route-nya tidak kena prefix 'koordinator.'
    Route::middleware(['role:koordinator'])->prefix('dosen')->name('dosen.')->group(function() {
        Route::get('/sidang/create', [SidangDosenController::class, 'create'])->name('sidang.create');
        Route::post('/sidang/store', [SidangDosenController::class, 'store'])->name('sidang.store');
    });


    // ====================================================
    // C. AREA DOSEN
    // ====================================================
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function() {
        
        // 1. Monitoring Bimbingan
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

        // 2. Request Kesediaan (Terima/Tolak)
        Route::controller(RequestController::class)->prefix('requests')->name('request.')->group(function() {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/respond', 'respond')->name('respond');
        });

        Route::controller(App\Http\Controllers\Dosen\DosenProposalController::class)->group(function() {
            // Menu Bimbingan
            Route::get('/bimbingan', 'indexBimbingan')->name('bimbingan.index');
            
            // Menu Menguji
            Route::get('/penguji', 'indexPenguji')->name('penguji.index');
            
            // Detail Proposal
            Route::get('/proposal/{id}', 'show')->name('proposal.show');

            Route::put('/proposal/{id}/keputusan', 'updateKeputusan')->name('proposal.keputusan');
        });
    });

});

require __DIR__.'/auth.php';