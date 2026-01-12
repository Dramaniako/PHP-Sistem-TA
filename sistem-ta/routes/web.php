<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\{ProfileController, DashboardController, KoordinatorController, AdministrasiKoorController};
use App\Http\Controllers\Mahasiswa\{SidangMahasiswaController, ProposalMahasiswaController, DokumenTaController as MahasiswaDokumenController};
use App\Http\Controllers\Koordinator\{PenetapanController, UserController, SidangKoordinatorController, AdminMonitoringController};
use App\Http\Controllers\Dosen\{SidangDosenController, MonitoringController, RequestController, DosenProposalController, DosenBimbinganController, JadwalDosenController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES ---
Route::get('/', fn() => redirect()->route('login'));

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD & PROFILE
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile/view', 'index')->name('profile.index');
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // ====================================================
    // A. AREA MAHASISWA
    // ====================================================
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        // Proposal
        Route::controller(ProposalMahasiswaController::class)->prefix('proposal')->name('proposal.')->group(function () {
            Route::get('/status', 'index')->name('status');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/download', 'download')->name('download');
            Route::put('/{id}/update-file', 'updateFile')->name('updateFile');
            Route::post('/upload-draft', 'uploadDraft')->name('upload_draft');
        });

        // Sidang
        Route::controller(SidangMahasiswaController::class)->prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', 'index')->name('index');
            // Catatan: Mahasiswa hanya melihat, tidak ada fitur reschedule mandiri di sini
        });

        // Dokumen Administrasi TA
        Route::controller(MahasiswaDokumenController::class)->prefix('dokumen')->name('dokumen.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{jenis}/detail', 'show')->name('show')->where('jenis', '.*');
            Route::post('/store', 'store')->name('store');
        });

        // Pastikan berada di dalam group middleware mahasiswa
        Route::put('proposal/update-revisi/{id}', [ProposalMahasiswaController::class, 'updateRevisi'])
            ->name('proposal.update_revisi');
    });

    // ====================================================
    // B. AREA KOORDINATOR
    // ====================================================
    Route::middleware(['role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function () {

        // --- FITUR APPROVAL RESCHEDULE ---
        // Diarahkan ke SidangKoordinatorController agar bisa mengatur jadwal manual
        Route::controller(SidangKoordinatorController::class)->group(function () {
            Route::get('/approval', 'indexApproval')->name('approval'); // Menampilkan tab reschedule
            Route::post('/approval/{id}/proses', 'prosesApproval')->name('proses_approval'); // Terima + Atur Jadwal Manual
        });

        // Menambahkan rute reject jika diperlukan di KoordinatorController
        Route::post('/approval/{id}/reject', [KoordinatorController::class, 'reject'])->name('reject');

        // Penetapan Dosen
        Route::controller(PenetapanController::class)->prefix('penetapan')->name('penetapan.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/detail', 'show')->name('show');
            Route::get('/{id}/download', 'download')->name('download');
            Route::get('/{id}/proses', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::put('/{id}/keputusan', 'updateKeputusan')->name('keputusan');
        });

        Route::get('/download-proposal/{id}', [PenetapanController::class, 'downloadFile'])->name('proposal.download.private');


        // Manajemen User
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/{id}/update-role', 'updateRole')->name('update-role');
        });

        // Manajemen Sidang Utama
        Route::controller(SidangKoordinatorController::class)->prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::get('/api/get-proposal/{mahasiswa_id}', 'getProposalData')->name('api.get-proposal');
            Route::get('/download-ta/{id}', [SidangKoordinatorController::class, 'downloadTA'])->name('download-ta');
            Route::get('/download-khs/{id}', [SidangKoordinatorController::class, 'downloadKHS'])->name('download-khs');
        });

        // Monitoring Administrasi
        Route::controller(AdministrasiKoorController::class)->prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', 'monitoring')->name('index');
            Route::get('/statistik', 'statistik')->name('statistik');
            Route::get('/statistik/detail', 'statistikDetail')->name('statistik.detail');
            Route::get('/kondisi/{id}', 'kondisiDokumen')->name('kondisi');
            Route::get('/cetak-berkas/{id}', 'cetak')->name('cetak');
        });

        // Log Riwayat
        Route::controller(AdminMonitoringController::class)->group(function () {
            Route::get('/log-riwayat', 'indexRiwayat')->name('riwayat.index');
            Route::get('/monitoring/timeline/{id}', 'showTimeline')->name('monitoring.timeline');
        });
    });

    // ====================================================
    // C. AREA DOSEN
    // ====================================================
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {

        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

        // Requests Pembimbing/Penguji
        Route::controller(RequestController::class)->prefix('requests')->name('request.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/respond', 'respond')->name('respond');
        });

        // Proposal
        Route::controller(DosenProposalController::class)->group(function () {
            Route::get('/bimbingan', 'indexBimbingan')->name('bimbingan.index');
            Route::get('/penguji', 'indexPenguji')->name('penguji.index');
            Route::get('/proposal/{id}', 'show')->name('proposal.show');
            Route::get('/proposal/{id}/download', 'downloadProposal')->name('proposal.download');
            Route::get('/proposal/{id}/download-khs', 'downloadKHS')->name('proposal.download.khs');
            Route::put('/proposal/{id}/keputusan', 'updateKeputusan')->name('proposal.keputusan');
        });

        // Bimbingan (Reschedule Bimbingan Dihapus, hanya Chat Manual)
        Route::controller(DosenBimbinganController::class)->group(function () {
            Route::post('/proposal/{id}/jadwal', 'store')->name('proposal.jadwal.store');
            // Route update respon reschedule bimbingan dihapus sesuai permintaan Anda
        });

        // Sidang & Reschedule Sidang (Dosen Upload Surat Tugas)
        Route::controller(SidangDosenController::class)->prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/ajukan-perubahan', 'ajukanPerubahan')->name('ajukan_perubahan'); // Fitur Upload Surat Tugas
        });

        // Validasi Dokumen
        Route::controller(MahasiswaDokumenController::class)->prefix('validasi')->name('validasi.')->group(function () {
            Route::get('/', 'indexDosen')->name('index');
            Route::get('/mahasiswa/{id}', 'showMahasiswaDokumen')->name('mahasiswa');
            Route::get('/dokumen/{id}', 'showValidasiForm')->name('dokumen.show');
            Route::patch('/dokumen/{id}/update', 'updateValidasi')->name('dokumen.update');
        });
    });
});

require __DIR__ . '/auth.php';