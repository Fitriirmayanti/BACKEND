<?php

use App\Http\Controllers\AdminLapangan\DashboardController;
use App\Http\Controllers\AdminLapangan\LaporanKonservasiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin_lapangan'])->group(function () {

   Route::get('/admin-lapangan', [DashboardController::class, 'index'])
       ->name('admin_lapangan');

   // Route::resource('/admin-lapangan/laporanKonservasi', LaporanKonservasiController::class)
      //  ->names('admin_lapangan.laporanKonservasi');

});