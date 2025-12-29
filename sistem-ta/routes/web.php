<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenjadwalanController;
use Illuminate\Support\Facades\Route;

//Manajemen Profile 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', [PageController::class, 'welcome']);
Route::get('/about', [PageController::class, 'about']);

Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
//

Route::get('/', function () {
    return view('welcome');
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
});

require __DIR__.'/auth.php';
