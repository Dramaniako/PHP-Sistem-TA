<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TUController;

Route::get('/', function () {
    return 'Laravel sudah jalan';
});

Route::get('/tu', [TUController::class, 'index']);
Route::get('/tu', [TUController::class, 'dashboard'])->name('tu.dashboard');
Route::get('/tu/mahasiswa', [TUController::class, 'mahasiswa'])->name('tu.mahasiswa');
Route::get('/tu/mahasiswa/{nim}', [TUController::class, 'detail'])
    ->name('tu.mahasiswa.detail');
