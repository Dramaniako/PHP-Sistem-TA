<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PenetapanController;

Route::get('/penetapan', [PenetapanController::class, 'index']);
// Jalur untuk menerima data kiriman (POST) dari form
Route::post('/penetapan/simpan', [App\Http\Controllers\PenetapanController::class, 'store'])->name('penetapan.store');

Route::get('/review', function () {
    return view('review');
});

Route::get('/', function () {
    return view('welcome');
});
