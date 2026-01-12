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
use App\Http\Controllers\Koordinator\SidangKoordinatorController;

// Dosen
use App\Http\Controllers\Dosen\SidangDosenController;
use App\Http\Controllers\Dosen\MonitoringController;
use App\Http\Controllers\Dosen\RequestController;
use App\Http\Controllers\Dosen\DosenProposalController;
use App\Http\Controllers\Dosen\DosenBimbinganController;

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
    
    // PROFILE
Route::middleware(['auth', 'verified'])->group(function () {

    Route::controller(ProfileController::class)->group(function () {

        // HALAMAN PROFIL (ROLE BASED)
        Route::get('/profile/view', 'index')->name('profile.index');

        // FORM EDIT PROFIL
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

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
            Route::put('/proposal/{id}/update-file', 'updateFile')->name('proposal.updateFile');
            Route::post('/proposal/upload-draft', 'uploadDraft')->name('proposal.upload_draft');
        });

        // 2. Jadwal Sidang Saya & Reschedule
        Route::controller(SidangMahasiswaController::class)->prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', 'index')->name('index'); 
            Route::post('/{id}/ajukan', 'ajukanPerubahan')->name('ajukan_perubahan');
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

        // API Internal untuk Fetch Data (AJAX)
        Route::get('/api/get-proposal/{id}', [App\Http\Controllers\Koordinator\SidangKoordinatorController::class, 'getProposalData']);
        
        // Route Sidang
        Route::get('/sidang', [App\Http\Controllers\Koordinator\SidangKoordinatorController::class, 'index'])->name('sidang.index');
        Route::get('/sidang/create', [App\Http\Controllers\Koordinator\SidangKoordinatorController::class, 'create'])->name('sidang.create');
        Route::post('/sidang', [App\Http\Controllers\Koordinator\SidangKoordinatorController::class, 'store'])->name('sidang.store');
        Route::get('/approval', [\App\Http\Controllers\Koordinator\SidangKoordinatorController::class, 'approval'])
        ->name('approval');
        Route::post('/approval/{id}', [\App\Http\Controllers\Koordinator\SidangKoordinatorController::class, 'prosesApproval'])
        ->name('proses_approval');
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

            // Route untuk Proposal (Download & Nilai) - Tetap ke DosenProposalController
            Route::get('/proposal/{id}/download', [DosenProposalController::class, 'downloadProposal'])->name('proposal.download');
            Route::get('/proposal/{id}/download-khs', [DosenProposalController::class, 'downloadKHS'])->name('proposal.download.khs');
            Route::put('/proposal/{id}/keputusan', [DosenProposalController::class, 'updateKeputusan'])->name('proposal.keputusan');

            // Route untuk Bimbingan (Jadwal) - Pindah ke DosenBimbinganController
            // Perhatikan perubahan Class Controller di bawah ini:
            Route::post('/proposal/{id}/jadwal', [DosenBimbinganController::class, 'store'])->name('proposal.jadwal.store');
            Route::put('/jadwal/{id}/respon', [DosenBimbinganController::class, 'update'])->name('proposal.jadwal.respon');
        
        });

        Route::get('/sidang', [\App\Http\Controllers\Dosen\SidangDosenController::class, 'index'])
        ->name('sidang.index');

        // PROSES REQUEST (Terima/Tolak)
        Route::post('/sidang/pengajuan/{id}', [\App\Http\Controllers\Dosen\SidangDosenController::class, 'prosesPengajuan'])
        ->name('sidang.proses_pengajuan');
            
        // (Route create & store yang lama tetap ada jika dibutuhkan)
        Route::get('/sidang/create', [\App\Http\Controllers\Dosen\SidangDosenController::class, 'create'])->name('sidang.create');
        Route::post('/sidang', [\App\Http\Controllers\Dosen\SidangDosenController::class, 'store'])->name('sidang.store');
    });

});

require __DIR__.'/auth.php';