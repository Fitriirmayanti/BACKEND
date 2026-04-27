<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKonservasi;

class LaporanKonservasiController extends Controller
{

    // ✅ INDEX (role aware)
    public function index(Request $request)
    {
        $user = $request->user();

        $query = LaporanKonservasi::with('user');

        // 🔥 role logic
        if ($user->role === 'admin_lapangan') {
            $query->where('pengirim', $user->id);
        }

        $data = $query->latest()->get();

        // 🔥 TAMBAHAN: status_text
        $data->transform(function ($item) {

            $item->status_text = match ($item->status) {
                0 => 'pending',
                1 => 'disetujui',
                2 => 'ditolak',
                default => 'unknown'
            };

            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    // ✅ STORE (SUDAH SESUAI FILE & DB)
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin_lapangan') {
            return response()->json([
                'message' => 'Hanya admin lapangan'
            ], 403);
        }

        $request->validate([
            'judulLaporan' => 'required',
            'jenisKegiatan' => 'required',
            'tanggalMulai' => 'required|date',
            'tanggalSelesai' => 'required|date',
            'daerahLokasi' => 'required',
            'keterangan' => 'required',

            'suratTugas' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf,doc,docx',
            'fotoSebelum' => 'required|image|mimes:jpeg,png,jpg,webp',
            'fotoSetelah' => 'required|image|mimes:jpeg,png,jpg,webp',
        ]);

        // 🔥 folder upload (sama kayak controller lama)
        if (!file_exists(public_path('uploads/laporan'))) {
            mkdir(public_path('uploads/laporan'), 0777, true);
        }

        $data = [
            'judulLaporan' => $request->judulLaporan,
            'jenisKegiatan' => $request->jenisKegiatan,
            'tanggalMulai' => $request->tanggalMulai,
            'tanggalSelesai' => $request->tanggalSelesai,
            'daerahLokasi' => $request->daerahLokasi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'luasArea' => $request->luasArea,
            'keterangan' => $request->keterangan,
            'lokasiKegiatan' => $request->lokasiKegiatan,
            'status' => 0,
            'pengirim' => $user->id,
        ];

        // 🔥 upload surat
        if ($request->file('suratTugas')) {
            $file = time().'_'.$request->file('suratTugas')->getClientOriginalName();
            $request->file('suratTugas')->move(public_path('uploads/laporan'), $file);
            $data['suratTugas'] = $file;
        }

        // 🔥 foto sebelum
        if ($request->file('fotoSebelum')) {
            $file = time().'_'.$request->file('fotoSebelum')->getClientOriginalName();
            $request->file('fotoSebelum')->move(public_path('uploads/laporan'), $file);
            $data['fotoSebelum'] = $file;
        }

        // 🔥 foto setelah
        if ($request->file('fotoSetelah')) {
            $file =  time().'_'.$request->file('fotoSetelah')->getClientOriginalName();
            $request->file('fotoSetelah')->move(public_path('uploads/laporan'), $file);
            $data['fotoSetelah'] = $file;
        }

       $laporan = LaporanKonservasi::create($data);

        // 🔥 TAMBAHAN: status_text
        $laporan->status_text = match ($laporan->status) {
            0 => 'pending',
            1 => 'disetujui',
            2 => 'ditolak',
            default => 'unknown'
        };

        return response()->json([
            'success' => true,
            'message' => 'Berhasil disimpan',
            'data' => $laporan
        ], 201);
    }


   // ✅ SHOW (ROLE SAFE + RAPI)
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $data = LaporanKonservasi::with('user')->find($id);

        // ❌ data tidak ditemukan
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // 🔐 admin lapangan hanya boleh lihat miliknya
        if ($user->role === 'admin_lapangan' && $data->pengirim != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak punya akses'
            ], 403);
        }

        // 🔥 TAMBAHAN: status_text
        $data->status_text = match ($data->status) {
            0 => 'pending',
            1 => 'disetujui',
            2 => 'ditolak',
            default => 'unknown'
        };

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        // 🔐 hanya admin lapangan
        if ($user->role !== 'admin_lapangan') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin lapangan'
            ], 403);
        }

        $data = LaporanKonservasi::find($id);

        // ❌ data tidak ditemukan
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // 🔐 hanya pemilik laporan
        if ($data->pengirim != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak punya akses'
            ], 403);
        }

        // ❌ tidak boleh edit jika sudah disetujui
        if ($data->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan sudah disetujui dan tidak bisa diubah'
            ], 400);
        }

        // ✅ VALIDASI
        $validated = $request->validate([
            'judulLaporan' => 'required',
            'jenisKegiatan' => 'required',
            'tanggalMulai' => 'required|date',
            'tanggalSelesai' => 'required|date',
            'daerahLokasi' => 'required',
            'keterangan' => 'required',

            'suratTugas' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf,doc,docx',
            'fotoSebelum' => 'required|image|mimes:jpeg,png,jpg,webp',
            'fotoSetelah' => 'required|image|mimes:jpeg,png,jpg,webp',
        ]);

        // 🔥 update data utama
        $data->update([
            'judulLaporan' => $validated['judulLaporan'],
            'jenisKegiatan' => $validated['jenisKegiatan'],
            'tanggalMulai' => $validated['tanggalMulai'],
            'tanggalSelesai' => $validated['tanggalSelesai'],
            'daerahLokasi' => $validated['daerahLokasi'],
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'lokasiKegiatan' => $request->lokasiKegiatan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'luasArea' => $request->luasArea,
            'keterangan' => $validated['keterangan'],
        ]);

        // 🔥 folder upload
        if (!file_exists(public_path('uploads/laporan'))) {
            mkdir(public_path('uploads/laporan'), 0777, true);
        }

        // 🔥 helper
        $deleteOldFile = function ($filename) {
            $path = public_path('uploads/laporan/' . $filename);
            if ($filename && file_exists($path)) {
                unlink($path);
            }
        };

        $uploadFile = function ($file) {
            $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/laporan'), $filename);
            return $filename;
        };

        // 🔥 update file
        if ($request->file('suratTugas')) {
            $deleteOldFile($data->suratTugas);
            $data->suratTugas = $uploadFile($request->file('suratTugas'));
        }

        if ($request->file('fotoSebelum')) {
            $deleteOldFile($data->fotoSebelum);
            $data->fotoSebelum = $uploadFile($request->file('fotoSebelum'));
        }

        if ($request->file('fotoSetelah')) {
            $deleteOldFile($data->fotoSetelah);
            $data->fotoSetelah = $uploadFile($request->file('fotoSetelah'));
        }

        // 🔥 reset ke pending jika sebelumnya ditolak
        if ($data->status == 2) {
            $data->status = 0;
        }

        $data->save();

        // 🔥 status_text
        $data->status_text = match ($data->status) {
            0 => 'pending',
            1 => 'disetujui',
            2 => 'ditolak',
            default => 'unknown'
        };

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diupdate',
            'data' => $data
        ]);
    }



    // ✅ DELETE (FINAL + VALIDASI STATUS)
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $data = LaporanKonservasi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan'
            ], 404);
        }

        // 🔐 hanya pemilik (admin lapangan)
        if ($user->role === 'admin_lapangan' && $data->pengirim != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak punya akses'
            ], 403);
        }

        // ❌ tidak boleh hapus jika sudah disetujui
        if ($data->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan sudah disetujui dan tidak bisa dihapus'
            ], 400);
        }

        // 🔥 hapus file
        foreach (['suratTugas', 'fotoSebelum', 'fotoSetelah'] as $field) {
            if ($data->$field) {
                $path = public_path('uploads/laporan/' . $data->$field);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
      
            ]);
    }


    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();

        // 🔐 hanya admin pusat
        if ($user->role !== 'admin_pusat') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin pusat'
            ], 403);
        }

        $data = LaporanKonservasi::find($id);

        // ❌ data tidak ditemukan
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // ✅ VALIDASI
        $validated = $request->validate([
            'status' => 'required|in:0,1,2'
        ]);

        // 🔥 update status
        $data->status = $validated['status'];
        $data->save();

        // 🔥 TAMBAHAN: status_text
        $data->status_text = match ($data->status) {
            0 => 'pending',
            1 => 'disetujui',
            2 => 'ditolak',
            default => 'unknown'
        };

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate',
            'data' => $data
        ]);
    }
}