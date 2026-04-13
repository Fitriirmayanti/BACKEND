<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

//Route::get('/', [HomeController::class, 'index']);
Route::get('/', function () {
    return response()->json([
        'status' => 'API RUNNING'
    ]);
});

Route::get('/edukasi', [HomeController::class, 'edukasi'])->name('edukasi');
Route::get('/edukasi/{slug}', [HomeController::class, 'detailEdukasi'])->name('edukasi.detail');

Route::get('/informasi', [HomeController::class, 'informasi'])->name('informasi');
Route::get('/standar-pelayanan', [HomeController::class, 'standarPelayanan'])->name('standarPelayanan');

Route::post('/simpanPesan', [HomeController::class, 'simpanPesan'])->name('simpanPesan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/hapusCache', function () {
    Artisan::call('optimize:clear');
    return 'Cache cleared!';
});

require __DIR__ . '/auth.php';

require __DIR__ . '/admin_lapangan.php';
require __DIR__ . '/admin_pusat.php';