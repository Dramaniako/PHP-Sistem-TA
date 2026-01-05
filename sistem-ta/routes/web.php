<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenjadwalanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TUController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\DokumenController;


//Route::get('/', function () {
    //return 'Laravel sudah jalan';
//});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/penjadwalan', [PenjadwalanController::class, 'index'])->name('penjadwalan.index');

    Route::post('/penjadwalan/create', [PenjadwalanController::class, 'storeKetersediaan'])->name('penjadwalan.store');

    Route::patch('/penjadwalan/{id}/status', [PenjadwalanController::class, 'updateStatus'])->name('penjadwalan.updateStatus');
    Route::post('/penjadwalan/{id}/book', [PenjadwalanController::class, 'ajukanBimbingan'])->name('penjadwalan.book');
  
    //Route::get('/tu', [TUController::class, 'index']);
    //Route::get('/tu', [TUController::class, 'dashboard'])->name('tu.dashboard');
    //Route::get('/tu/mahasiswa', [TUController::class, 'mahasiswa'])->name('tu.mahasiswa');
    //Route::get('/tu/mahasiswa/{nim}', [TUController::class, 'detail'])
        //->name('tu.mahasiswa.detail');

    //Route::get('/', [ValidasiController::class, 'index']);
    //Route::get('/validasi/detail', [ValidasiController::class, 'detail']);
    //Route::post('/validasi/proses', [ValidasiController::class, 'updateStatus']);
    //Route::post('/validasi/simpan', [ValidasiController::class, 'simpanFinal']);
    //Route::get('/validasi/reset', [ValidasiController::class, 'reset']);

    //Route::get('/dokumen-ta', [DokumenController::class, 'index'])
        //->name('dokumen.index');
    //Route::get('/dokumen-ta/upload', [DokumenController::class, 'upload'])
        //->name('dokumen.upload');
});


//Route::get('/tu', [TUController::class, 'dashboard']);
//Route::get('/tu/mahasiswa', [TUController::class, 'mahasiswa']);
//Route::get('/tu/mahasiswa/{nim}', [TUController::class, 'detail']);

//Route::get('/dosen', [ValidasiController::class, 'index']);
//Route::get('/dosen/validasi/detail', [ValidasiController::class, 'detail']);
//Route::post('/dosen/validasi/setuju/{id}', [ValidasiController::class, 'setujui']);
//Route::post('/dosen/validasi/tolak/{id}', [ValidasiController::class, 'tolak']);

//Route::get('/mahasiswa', [DokumenController::class, 'index']);
//Route::get('/mahasiswa/upload', [DokumenController::class, 'upload']);
//Route::post('/mahasiswa/upload', [DokumenController::class, 'store'])
    //->name('mahasiswa.store');

Route::get('/mahasiswa', [DokumenController::class, 'index'])
    ->name('mahasiswa.index');

Route::get('/mahasiswa/upload', [DokumenController::class, 'upload'])
    ->name('mahasiswa.upload');

Route::post('/mahasiswa/upload', [DokumenController::class, 'store'])
    ->name('mahasiswa.store');

Route::middleware(['auth'])->group(function () {

    Route::get('/dosen', [ValidasiController::class, 'index']);
    Route::get('/dosen/validasi/detail', [ValidasiController::class, 'detail']);

    Route::post('/dosen/setujui', [ValidasiController::class, 'setujui']);
    Route::post('/dosen/tolak', [ValidasiController::class, 'tolak']);

});

/*
|--------------------------------------------------------------------------
| ROUTE TU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:tu'])->group(function () {

    Route::get('/tu', [TUController::class, 'dashboard']);
    Route::get('/tu/mahasiswa', [TUController::class, 'mahasiswa']);
    Route::get('/tu/mahasiswa/detail', [TUController::class, 'detail']);

});




require __DIR__.'/auth.php';
