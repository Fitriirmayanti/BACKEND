<?php

use App\Http\Controllers\AdminPusat\CustomerController;
use App\Http\Controllers\AdminPusat\DashboardController;
use App\Http\Controllers\AdminPusat\EdukasiController;
use App\Http\Controllers\AdminPusat\GaleriController;
use App\Http\Controllers\AdminPusat\KawasanController;
use App\Http\Controllers\AdminPusat\LaporanKonservasiController;
//use App\Http\Controllers\AdminPusat\PenggunaController;
use App\Http\Controllers\AdminPusat\PeraturanController;
use App\Http\Controllers\AdminPusat\ProgramController;
use App\Http\Controllers\AdminPusat\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin_pusat'])->group(function () {

    Route::get('/admin_pusat', [DashboardController::class, 'index'])
        ->name('admin_pusat');

    //Route::resource('/admin_pusat/galeri', GaleriController::class)
        //->names('admin_pusat.galeri');

    //Route::resource('/admin_pusat/customer', CustomerController::class)
      //  ->names('admin_pusat.customer');

    //Route::resource('/admin_pusat/pengguna', PenggunaController::class)
       // ->names('admin_pusat.pengguna');

    //Route::resource('/admin_pusat/program', ProgramController::class)
       // ->names('admin_pusat.program');

    //Route::resource('/admin_pusat/edukasi', EdukasiController::class)
       // ->names('admin_pusat.edukasi');

   // Route::resource('/admin_pusat/kawasan', KawasanController::class)
      //  ->names('admin_pusat.kawasan');

    //Route::resource('/admin_pusat/peraturan', PeraturanController::class)
      //  ->names('admin_pusat.peraturan');

    //Route::resource('/admin_pusat/laporanKonservasi', LaporanKonservasiController::class)
       // ->names('admin_pusat.laporanKonservasi');

    //Route::get('/admin_pusat/laporanKonservasi/setujui/{id}', 
      //  [LaporanKonservasiController::class, 'setujui'])
      //  ->name('admin_pusat.laporanKonservasi.setujui');

    //Route::get('/admin_pusat/laporanKonservasi/tolak/{id}', 
       // [LaporanKonservasiController::class, 'tolak'])
       // ->name('admin_pusat.laporanKonservasi.tolak');

    //Route::get('/admin_pusat/website', [WebsiteController::class, 'index'])
      //  ->name('admin_pusat.website');

    //Route::put('/admin_pusat/website/update', [WebsiteController::class, 'update'])
        //->name('admin_pusat.website.update');
});