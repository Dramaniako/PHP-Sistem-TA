<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenjadwalanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TUController;

Route::get('/', function () {
    return 'Laravel sudah jalan';
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/penjadwalan', [PenjadwalanController::class, 'index'])->name('penjadwalan.index');

    Route::post('/penjadwalan/create', [PenjadwalanController::class, 'storeKetersediaan'])->name('penjadwalan.store');

    Route::patch('/penjadwalan/{id}/status', [PenjadwalanController::class, 'updateStatus'])->name('penjadwalan.updateStatus');
    Route::post('/penjadwalan/{id}/book', [PenjadwalanController::class, 'ajukanBimbingan'])->name('penjadwalan.book');
  
    Route::get('/tu', [TUController::class, 'index']);
    Route::get('/tu', [TUController::class, 'dashboard'])->name('tu.dashboard');
    Route::get('/tu/mahasiswa', [TUController::class, 'mahasiswa'])->name('tu.mahasiswa');
    Route::get('/tu/mahasiswa/{nim}', [TUController::class, 'detail'])
    ->name('tu.mahasiswa.detail');
});

require __DIR__.'/auth.php';
