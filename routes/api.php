<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Galeri;
use App\Models\Website;
use App\Models\Edukasi;
use App\Models\KawasanKonservasi;
use App\Models\Peraturan;
use App\Models\Masyarakat;
use App\Models\User;
use App\Models\LaporanKonservasi;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\LaporanKonservasiController;
use App\Http\Controllers\API\PenggunaController;
use App\Http\Controllers\API\ProgramController;
use App\Http\Controllers\API\EdukasiController;
use App\Http\Controllers\API\PeraturanController;
use App\Http\Controllers\API\KawasanController;
use App\Http\Controllers\API\GaleriController;
use App\Http\Controllers\API\StandarPelayananController;

Route::middleware('api')->group(function () {
    Route::get('/health', function () {
        return response()->json(['status' => 'ok']);
    });

    // Public content endpoints (mirror web routes, return JSON)
    Route::get('/home', function () {
        $banner = Galeri::where('keygaleri', 'banner')->get();
        $program = Edukasi::where('kategori', 'Program')->orderBy('id', 'desc')->take(4)->get();
        $website = Website::first();
        return response()->json(compact('banner', 'program', 'website'));
    });


    Route::get('/informasi', function () {
        $satwa = Edukasi::where('kategori', 'Satwa')->orderBy('id', 'desc')->get();
        $executive = Edukasi::where('kategori', 'Executive')->orderBy('id', 'desc')->get();
        $peraturan = Peraturan::orderBy('id', 'desc')->get();
        $kawasan = KawasanKonservasi::orderBy('id', 'desc')->first();
        return response()->json(compact('satwa', 'executive', 'peraturan', 'kawasan'));
    });

  
    
});

// Provide CSRF cookie for SPA (if Sanctum not installed)
Route::middleware(['web'])->get('/csrf-cookie', function () {
    Cookie::queue('XSRF-TOKEN', csrf_token(), 120, null, null, false, false);
    return response()->noContent();
});

// Debug endpoint to check authentication status
Route::middleware(['web'])->get('/debug-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
    ]);
});

// Auth APIs (mirror auth.php) - gunakan sesi (middleware web)
Route::middleware(['api'])->group(function () {
    Route::post('/auth/register', [RegisteredUserController::class, 'store'])->name('api.register');
    // API-friendly login (JSON, no redirect)
    Route::post('/auth/login', [AuthenticatedSessionController::class, 'storeApi'])->name('api.login');
    Route::post('/auth/forgot-password', [PasswordResetLinkController::class, 'store'])->name('api.password.email');
    Route::post('/auth/reset-password', [NewPasswordController::class, 'store'])->name('api.password.store');
});

Route::middleware(['api'])->group(function () {
    Route::get('/auth/verify-email', EmailVerificationPromptController::class)->name('api.verification.notice');
    Route::get('/auth/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('api.verification.verify');
    Route::post('/auth/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('api.verification.send');
    Route::post('/auth/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.logout');
});

// =====================================================
// LAPORAN KONSERVASI
// =====================================================

Route::middleware('auth:sanctum')->group(function () {

    // 🔥 SEMUA ROLE BISA AKSES
    Route::get('/laporan-konservasi', [LaporanKonservasiController::class, 'index']);
    Route::get('/laporan-konservasi/{id}', [LaporanKonservasiController::class, 'show']);

    // =====================================================
    // ADMIN LAPANGAN
    // =====================================================

    Route::middleware('role:admin_lapangan')->group(function () {

        Route::post('/laporan-konservasi', [LaporanKonservasiController::class, 'store']);

        Route::put('/laporan-konservasi/{id}', [LaporanKonservasiController::class, 'update']);

        Route::delete('/laporan-konservasi/{id}', [LaporanKonservasiController::class, 'destroy']);
    });

    // =====================================================
    // ADMIN PUSAT
    // =====================================================

    Route::middleware('role:admin_pusat')->group(function () {

        // =========================
        // UPDATE STATUS LAPORAN
        // =========================

        Route::put('/laporan-konservasi/{id}/status', [LaporanKonservasiController::class, 'updateStatus']);

        // ===============================
        // CRUD PENGGUNA
        // ===============================

        Route::get('/pengguna', [PenggunaController::class, 'index']);
        Route::get('/pengguna/{id}', [PenggunaController::class, 'show']);
        Route::post('/pengguna', [PenggunaController::class, 'store']);
        Route::put('/pengguna/{id}', [PenggunaController::class, 'update']);
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy']);

        // =========================
        // CRUD PROGRAM
        // =========================

        Route::get('/program', [ProgramController::class, 'index']);
        Route::post('/program', [ProgramController::class, 'store']);
        Route::get('/program/{id}', [ProgramController::class, 'show']);
        Route::put('/program/{id}', [ProgramController::class, 'update']);
        Route::delete('/program/{id}', [ProgramController::class, 'destroy']);

        // =========================
        // CRUD EDUKASI
        // =========================

        Route::get('/edukasi', [EdukasiController::class, 'index']);
        Route::post('/edukasi', [EdukasiController::class, 'store']);
        Route::get('/edukasi/{id}', [EdukasiController::class, 'show']);
        Route::put('/edukasi/{id}', [EdukasiController::class, 'update']);
        Route::delete('/edukasi/{id}', [EdukasiController::class, 'destroy']);

        // =========================
        // CRUD PERATURAN
        // =========================

        Route::get('/peraturan', [PeraturanController::class, 'index']);
        Route::post('/peraturan', [PeraturanController::class, 'store']);
        Route::get('/peraturan/{id}', [PeraturanController::class, 'show']);
        Route::put('/peraturan/{id}', [PeraturanController::class, 'update']);
        Route::delete('/peraturan/{id}', [PeraturanController::class, 'destroy']);

        // =========================
        // CRUD KAWASAN
        // =========================

        Route::get('/kawasan', [KawasanController::class, 'index']);
        Route::post('/kawasan', [KawasanController::class, 'store']);
        Route::get('/kawasan/{id}', [KawasanController::class, 'show']);
        Route::put('/kawasan/{id}', [KawasanController::class, 'update']);
        Route::delete('/kawasan/{id}', [KawasanController::class, 'destroy']);

        // =========================
        // CRUD GALERI
        // =========================

        Route::get('/galeri', [GaleriController::class, 'index']);
        Route::post('/galeri', [GaleriController::class, 'store']);
        Route::get('/galeri/{id}', [GaleriController::class, 'show']);
        Route::put('/galeri/{id}', [GaleriController::class, 'update']);
        Route::delete('/galeri/{id}', [GaleriController::class, 'destroy']);

        // =========================
        // VIEW STANDAR PELAYANAN
        // =========================

        Route::get('/standar-pelayanan', [StandarPelayananController::class, 'index']);
    });
});


// =========================
// MASYARAKAT KIRIM SARAN
// =========================

Route::post('/standar-pelayanan', [StandarPelayananController::class, 'store']);

// =========================
// PROGRAM PUBLIK
// =========================

Route::get('/program', [ProgramController::class, 'index']);
Route::get('/program/{id}', [ProgramController::class, 'show']);

// =========================
// EDUKASI PUBLIK
// =========================

Route::get('/edukasi', [EdukasiController::class, 'index']);
Route::get('/edukasi/{id}', [EdukasiController::class, 'show']);

// =========================
// PERATURAN PUBLIK
// =========================

Route::get('/peraturan', [PeraturanController::class, 'index']);
Route::get('/peraturan/{id}', [PeraturanController::class, 'show']);

// =========================
// KAWASAN PUBLIK
// =========================

Route::get('/kawasan', [KawasanController::class, 'index']);
Route::get('/kawasan/{id}', [KawasanController::class, 'show']);

// =========================
// GALERI PUBLIK
// =========================

Route::get('/galeri', [GaleriController::class, 'index']);
Route::get('/galeri/{id}', [GaleriController::class, 'show']);


// admin_lapangan APIs (read-only mirrors), protected with same middleware + web session
//Route::middleware(['auth:sanctum', 'role:admin_lapangan'])->group(function () {
  
    Route::get('/admin_lapangan/dashboard', function () {

        $user = auth()->user();
                    if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }
        $laporan = LaporanKonservasi::where('pengirim', $user->id)->get();
        
        $laporanDisetujui = LaporanKonservasi::where('status', 1)
            ->where('pengirim', $user->id)
            ->count();

        $laporanDitolak = LaporanKonservasi::where('status', 2)
            ->where('pengirim', $user->id)
            ->count();

        // hitung jumlah laporan per daerah
        $laporanPerDaerah = LaporanKonservasi::select('daerahLokasi', \DB::raw('COUNT(*) as total'))
            ->where('pengirim', $user->id)
            ->groupBy('daerahLokasi')
            ->pluck('total', 'daerahLokasi');

        return response()->json([
            'laporan' => $laporan,
            'laporanDisetujui' => $laporanDisetujui,
            'laporanDitolak' => $laporanDitolak,
            'laporanPerDaerah' => $laporanPerDaerah,
            'user' => $user,
        ]);
    })->name('admin_lapangan.dashboard');

   // Route::get('/admin_lapangan/laporanKonservasi', function (Request $request) {
       // $user = auth()->user();
       // $daerahFilter = $request->query('daerah');
        
      //  $laporanQuery = LaporanKonservasi::where('pengirim', $user->id);
      //  if ($daerahFilter) {
      //      $laporanQuery->where('daerahLokasi', $daerahFilter);
      //  }
     //   $laporan = $laporanQuery->orderBy('created_at', 'desc')->get();

    //    $daerah = LaporanKonservasi::select('daerahLokasi')
        //    ->where('pengirim', $user->id)
        //    ->distinct()
        //    ->pluck('daerahLokasi');

       // return response()->json([
       //     'laporan' => $laporan,
       //     'daerah' => $daerah,
       // ]);
   // })->name('admin_lapangan.laporanKonservasi.index');

    //Route::get('/admin_lapangan/laporanKonservasi/{id}', function ($id) {
     //   $user = auth()->user();
     //   $laporan = LaporanKonservasi::with('user')->where('pengirim', $user->id)->find($id);
  
      //  if (!$laporan) {
      //      return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
       // }

   //     return response()->json($laporan);
   // })->name('admin_lapangan.laporanKonservasi.show');


    
  // Route::post('/admin_lapangan/laporanKonservasi', function (Request $request) {

            //$user = auth()->user();

            // Debug log
        //    \Log::info('=== DEBUGGING LAPORAN SUBMISSION ===');
        //    \Log::info('Request files:', $request->allFiles());

       //     if ($request->hasFile('suratTugas')) {
       //         $files = $request->file('suratTugas');
       //         $count = is_array($files) ? count($files) : 1;
        //        \Log::info('suratTugas files count: ' . $count);
       //     }

        //    if ($request->hasFile('fotoSebelum')) {
        //        $files = $request->file('fotoSebelum');
        //        $count = is_array($files) ? count($files) : 1;
        //        \Log::info('fotoSebelum files count: ' . $count);
        //    }

        //    if ($request->hasFile('fotoSetelah')) {
        //        $files = $request->file('fotoSetelah');
        //        $count = is_array($files) ? count($files) : 1;
        //        \Log::info('fotoSetelah files count: ' . $count);
        //    }

        //    $request->validate([
        //        'judulLaporan'   => 'required|string|max:255',
        //        'jenisKegiatan'  => 'required|string|max:255',
        //        'tanggalMulai'   => 'required|date',
        //        'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
        //        'keterangan'     => 'nullable|string',
        //        'daerahLokasi'   => 'required|string|max:255',
        ////        'kabupaten'      => 'required|string|max:255',
        //        'kecamatan'      => 'required|string|max:255',
        //        'latitude'       => 'required',
        //        'longitude'      => 'required',
        //        'luasArea'       => 'required|numeric|min:0',
        //        'suratTugas'     => 'required',
        //        'fotoSebelum'    => 'required',
         //       'fotoSetelah'    => 'required',
        //    ]);

        //    // Additional validation for files
         //   if ($request->hasFile('suratTugas')) {
         ////       $files = $request->file('suratTugas');
         //       if (!is_array($files)) $files = [$files];
//
         //       foreach ($files as $file) {
         //           if (
         //               !$file->isValid() ||
         //               !in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp', 'pdf']) ||
         //               $file->getSize() > 2048000
         //           ) {
         //               throw new \Exception('Invalid file for suratTugas');
         //           }
         //       }
         //   }

         //   if ($request->hasFile('fotoSebelum')) {
         //       $files = $request->file('fotoSebelum');
         //       if (!is_array($files)) $files = [$files];

          //      foreach ($files as $file) {
          //          if (
          //              !$file->isValid() ||
          //              !in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp', 'pdf']) ||
          //              $file->getSize() > 2048000
          //          ) {
           //             throw new \Exception('Invalid file for fotoSebelum');
           //         }
           //     }
           // }
//
           // if ($request->hasFile('fotoSetelah')) {
           //     $files = $request->file('fotoSetelah');
           //     if (!is_array($files)) $files = [$files];
//
           //     foreach ($files as $file) {
           //         if (
           //             !$file->isValid() ||
           //             !in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp', 'pdf']) ||
           //             $file->getSize() > 2048000
           //         ) {
           //             throw new \Exception('Invalid file for fotoSetelah');
           //         }
           //     }
          //  }

            // Create upload directory if it doesn't exist
         //   $uploadPath = public_path('uploads/laporan');
          //  if (!file_exists($uploadPath)) {
         //       mkdir($uploadPath, 0777, true);
         //   }

        //    // Handle multiple file uploads
       //     $suratTugasNames = [];
       //     $fotoSebelumNames = [];
       //     $fotoSetelahNames = [];

            // Upload Surat Tugas
       //     $files = $request->file('suratTugas');
       //     if (!is_array($files)) $files = [$files];

       //     foreach ($files as $index => $file) {
       //         $fileName = time() . '_surat_' . $index . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        //        $file->move($uploadPath, $fileName);
        //        $suratTugasNames[] = $fileName;
       //     }
////
            // Upload Foto Sebelum
        //    $files = $request->file('fotoSebelum');
       //     if (!is_array($files)) $files = [$files];
//
        //    foreach ($files as $index => $file) {
        //        $fileName = time() . '_sebelum_' . $index . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
         //       $file->move($uploadPath, $fileName);
        //        $fotoSebelumNames[] = $fileName;
        //    }
//
            // Upload Foto Setelah
         //   $files = $request->file('fotoSetelah');
        //    if (!is_array($files)) $files = [$files];

        //    foreach ($files as $index => $file) {
        //        $fileName = time() . '_setelah_' . $index . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        //        $file->move($uploadPath, $fileName);
        //        $fotoSetelahNames[] = $fileName;
        //    }

       //     $laporan = LaporanKonservasi::create([
       //         'pengirim'       => $user->id,
       //         'judulLaporan'   => $request->judulLaporan,
       //         'jenisKegiatan'  => $request->jenisKegiatan,
       //         'tanggalMulai'   => $request->tanggalMulai,
       //         'tanggalSelesai' => $request->tanggalSelesai,
       //         'keterangan'     => $request->keterangan,
        //        'daerahLokasi'   => $request->daerahLokasi,
        //        'kabupaten'      => $request->kabupaten,
       //        'kecamatan'      => $request->kecamatan,
       //         'latitude'       => $request->latitude,
        //        'longitude'      => $request->longitude,
      //          'luasArea'       => $request->luasArea,
       //         'suratTugas'     => json_encode($suratTugasNames),
       //         'fotoSebelum'    => json_encode($fotoSebelumNames),
       //         'fotoSetelah'    => json_encode($fotoSetelahNames),
      //          'status'         => 0,
       //     ]);
//
     //       return response()->json($laporan, 201);

    //    })->name('admin_lapangan.laporanKonservasi.store');

    //Route::match(['PUT', 'POST'], '/admin_lapangan/laporanKonservasi/{id}', function (Request $request, $id) {
        
     //   $user = auth()->user();
      //  $laporan = LaporanKonservasi::where('pengirim', $user->id)->findOrFail($id);

      //  $request->validate([
      //   'judulLaporan'   => 'required|string|max:255',
      //   'jenisKegiatan'  => 'required|string|max:255',
     //    'tanggalMulai'   => 'required|date',
     //    'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
      //   'keterangan'     => 'nullable|string',
      //   'daerahLokasi'   => 'required|string|max:255',
      //   'kabupaten'      => 'required|string|max:255',
      //   'kecamatan'      => 'required|string|max:255',
     //    'latitude'       => 'required',
     //    'longitude'      => 'required',
      //   'luasArea'       => 'required|numeric|min:0',

     //    'suratTugas'  => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
    //     'fotoSebelum' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
     //    'fotoSetelah' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
   //  ]);

        // Create upload directory if it doesn't exist
     //    $uploadPath = public_path('uploads/laporan');
     //    if (!file_exists($uploadPath)) {
     //        mkdir($uploadPath, 0777, true);
     //    }

        // Handle file uploads if provided
     //    $updateData = [
     //        'judulLaporan'   => $request->judulLaporan,
     //        'jenisKegiatan'  => $request->jenisKegiatan,
     //        'tanggalMulai'   => $request->tanggalMulai,
     //        'tanggalSelesai' => $request->tanggalSelesai,
     //        'keterangan'     => $request->keterangan,
     //        'daerahLokasi'   => $request->daerahLokasi,
     //        'kabupaten'      => $request->kabupaten,
     //        'kecamatan'      => $request->kecamatan,
     //        'latitude'       => $request->latitude,
     //        'longitude'      => $request->longitude,
     //        'luasArea'       => $request->luasArea,
     //    ];
// 
        // Handle upload file (single file, bukan array lagi)
     //    foreach (['suratTugas', 'fotoSebelum', 'fotoSetelah'] as $field) {

      //       if ($request->file($field)) {

                // 🔥 Ambil file lama (bisa string atau array lama)
      //           $oldData = $laporan->$field;

      //           if ($oldData) {
      //               $oldFiles = is_array(json_decode($oldData, true))
      //                   ? json_decode($oldData, true)
       //                  : [$oldData];
// 
       //              foreach ($oldFiles as $oldFileName) {
       //                  $oldFile = public_path('uploads/laporan/' . $oldFileName);
       //                  if (file_exists($oldFile)) {
       //                      unlink($oldFile);
       //                  }
       //              }
       //          }

       //          // 🔥 Upload file baru
       //          $file = $request->file($field);
// 
         //        $newName = time() . '_' . $field . '_' . \Str::random(5) . '.' . $file->getClientOriginalExtension();

         //        $file->move($uploadPath, $newName);

         //        // 🔥 Simpan sebagai string (bukan array lagi)
         //        $updateData[$field] = $newName;
           //  }
       //  }

       //  $laporan->update($updateData);

       //  return response()->json($laporan);

    //})->name('admin_lapangan.laporanKonservasi.update');

    // Route::delete('/admin_lapangan/laporanKonservasi/{id}', function ($id) {
     //    $user = auth()->user();
    //     $laporan = LaporanKonservasi::where('pengirim', $user->id)->findOrFail($id);

    //     $laporan->delete();

     //    return response()->json(['message' => 'Laporan berhasil dihapus']);
   //  })->name('admin_lapangan.laporanKonservasi.destroy');

    // Profile endpoints
    Route::get('/profile', function () {
        return response()->json(auth()->user());
    });

    

    

    Route::put('/profile', function (Request $request) {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nohp' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['name', 'username', 'email', 'nohp']));
        return response()->json(['message' => 'Profil berhasil diperbarui', 'user' => $user]);
    });

    Route::put('/password', function (Request $request) {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password berhasil diperbarui'
        ]);
    });
// });

// admin_pusat APIs (read-only mirrors), protected with same middleware + web session
//Route::middleware(['auth:sanctum', 'role:admin_pusat'])->group(function () {
  


    Route::get('/admin_pusat/dashboard', function () {
        $masyarakat = masyarakat::count();
        $awal = date('Y-m-01');
        $akhir = date('Y-m-d');

        $laporanTerakhir = LaporanKonservasi::whereBetween('created_at', [$awal, $akhir])->count();
        $laporanDisetujui = LaporanKonservasi::where('status', 1)
            ->whereBetween('created_at', [$awal, $akhir])
            ->count();

        $laporanTahunan = LaporanKonservasi::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $daerah = LaporanKonservasi::select('daerahLokasi')
            ->distinct()
            ->pluck('daerahLokasi');

        return response()->json([
            'masyarakat' => $masyarakat,
            'laporanTerakhir' => $laporanTerakhir,
            'laporanDisetujui' => $laporanDisetujui,
            'laporanTahunan' => $laporanTahunan,
            'daerah' => $daerah,
            'user' => auth()->user(),
        ]);
    })->name('admin_pusat.dashboard');


    Route::middleware(['auth:sanctum'])->group(function () {

    
 });

 
    Route::get('/admin_pusat/masyarakat', function () {
        return response()->json(masyarakat::orderBy('id', 'desc')->get());
    })->name('admin_pusat.masyarakat.index');

    Route::delete('/admin_pusat/masyarakat/{id}', function ($id) {
        $masyarakat = masyarakat::findOrFail($id);
        $masyarakat->delete();

        return response()->json(['message' => 'masyarakat berhasil dihapus']);
    })->name('admin_pusat.masyarakat.destroy');

    

    Route::get('/admin_pusat/website', function () {
        $website = Website::first();
        if (!$website) {
            // Create default website data if not exists
            $website = Website::create([
                'nama' => 'SIKOMA',
                'deskripsi' => 'Sistem Informasi Konservasi',
                'keyword' => 'sikoma',
                'alamat' => '',
                'telepon' => '',
                'email' => '',
                'facebook' => '',
                'instagram' => '',
                'wa' => '',
                'gmaps' => '',
                'jambuka' => '',
                'visi' => '',
                'misi' => '',
                'icon' => '',
                'logo' => '',
                'struktur' => ''
            ]);
        }
        return response()->json($website);
    })->name('admin_pusat.website.index');

    // Profile endpoints for admin_pusat
    Route::get('/admin_pusat/profile', function () {
        return response()->json(auth()->user());
    });

    Route::put('/admin_pusat/profile', function (Request $request) {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nohp' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['name', 'username', 'email', 'nohp']));

        return response()->json(['message' => 'Profil berhasil diperbarui', 'user' => $user]);
    });

    Route::put('/admin_pusat/password', function (Request $request) {
        $user = auth()->user();
        
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama tidak cocok'], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Password berhasil diperbarui']);
    });

    Route::post('/admin_pusat/website', function (Request $request) {
        $website = Website::first();
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'keyword' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'wa' => 'nullable|string',
            'gmaps' => 'nullable|string',
            'jambuka' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,ico,webp|max:1024',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'struktur' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            if ($website && $website->icon && file_exists(public_path('img/' . $website->icon))) {
                unlink(public_path('img/' . $website->icon));
            }
            $iconName = 'icon.' . $request->icon->extension();
            $request->icon->move(public_path('img'), $iconName);
            $validated['icon'] = $iconName;
        } else if ($website) {
            $validated['icon'] = $website->icon;
        } else {
            $validated['icon'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($website && $website->logo && file_exists(public_path('img/' . $website->logo))) {
                unlink(public_path('img/' . $website->logo));
            }
            $logoName = 'logo.' . $request->logo->extension();
            $request->logo->move(public_path('img'), $logoName);
            $validated['logo'] = $logoName;
        } else if ($website) {
            $validated['logo'] = $website->logo;
        } else {
            $validated['logo'] = null;
        }

        if ($request->hasFile('struktur')) {
            if ($website && $website->struktur && file_exists(public_path('img/' . $website->struktur))) {
                unlink(public_path('img/' . $website->struktur));
            }
            $strukturName = 'struktur.' . $request->struktur->extension();
            $request->struktur->move(public_path('img'), $strukturName);
            $validated['struktur'] = $strukturName;
        } else if ($website) {
            $validated['struktur'] = $website->struktur;
        } else {
            $validated['struktur'] = null;
        }

        if ($website) {
            $website->update($validated);
        } else {
            $website = Website::create($validated);
        }

        return response()->json($website);
    })->name('admin_pusat.website.update');

    
    

   
    
    

 // });
    

