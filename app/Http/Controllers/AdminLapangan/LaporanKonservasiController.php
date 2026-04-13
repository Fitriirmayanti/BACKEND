<?php

namespace App\Http\Controllers\AdminLapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKonservasi;
use Illuminate\Support\Str;

class LaporanKonservasiController extends Controller
{

    public function index(Request $request)
    {
        $daerahFilter = $request->query('daerah');
        $laporanQuery = LaporanKonservasi::where('pengirim', auth()->user()->id);
        if ($daerahFilter) {
            $laporanQuery->where('daerahLokasi', $daerahFilter);
        }
        $laporan = $laporanQuery->get();

        $daerah = LaporanKonservasi::select('daerahLokasi')
            ->distinct()
            ->pluck('daerahLokasi');

        return view('admin_lapangan.laporan.index', [
            'title' => 'Laporan Konservasi',
            'active' => 'Index',
            'laporan' => $laporan,
            'daerah' => $daerah,
            'user' => auth()->user(),
        ]);
    }


    public function create()
    {
        return view('admin_lapangan.laporan.tambah', [
            'title' => 'Laporan Konservasi',
            'active' => 'Tambah',
            'user' => auth()->user(),
        ]);
    }


    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'judulLaporan'   => 'required|string|max:255',
            'jenisKegiatan'  => 'required|string|max:255',
            'tanggalMulai'   => 'required|date',
            'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
            'keterangan'     => 'nullable|string',

            'daerahLokasi'   => 'required|string|max:255',
            'kabupaten'      => 'required|string|max:255',
            'kecamatan'      => 'required|string|max:255',
            'latitude'       => 'required',
            'longitude'      => 'required',

            'suratTugas'     => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'fotoSebelum'    => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'fotoSetelah'    => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'luasArea'       => 'required|numeric|min:0',
        ]);

        // 2. Upload file
        $uploadPath = public_path('uploads/laporan');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $suratTugas   = $request->file('suratTugas');
        $fotoSebelum  = $request->file('fotoSebelum');
        $fotoSetelah  = $request->file('fotoSetelah');

        $suratTugasName  = time() . '_surat_' . Str::random(5) . '.' . $suratTugas->getClientOriginalExtension();
        $fotoSebelumName = time() . '_sebelum_' . Str::random(5) . '.' . $fotoSebelum->getClientOriginalExtension();
        $fotoSetelahName = time() . '_setelah_' . Str::random(5) . '.' . $fotoSetelah->getClientOriginalExtension();

        $suratTugas->move($uploadPath, $suratTugasName);
        $fotoSebelum->move($uploadPath, $fotoSebelumName);
        $fotoSetelah->move($uploadPath, $fotoSetelahName);

        // 3. Simpan ke database
        LaporanKonservasi::create([
            'pengirim'       => auth()->id(),
            'judulLaporan'   => $request->judulLaporan,
            'jenisKegiatan'  => $request->jenisKegiatan,
            'tanggalMulai'   => $request->tanggalMulai,
            'tanggalSelesai' => $request->tanggalSelesai,
            'keterangan'     => $request->keterangan,

            'daerahLokasi'   => $request->daerahLokasi,
            'kabupaten'      => $request->kabupaten,
            'kecamatan'      => $request->kecamatan,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,

            'suratTugas'     => $suratTugasName,
            'fotoSebelum'    => $fotoSebelumName,
            'fotoSetelah'    => $fotoSetelahName,
            'luasArea'       => $request->luasArea,

            'status'         => 0, // default pending
        ]);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('admin_lapangan.laporanKonservasi.index')
            ->with('success', 'Laporan berhasil disimpan dan menunggu persetujuan.');
    }


    public function show(string $id)
    {
        $laporan = LaporanKonservasi::with('user')->find($id);
        if (!$laporan) {
            return redirect()->route('admin_lapangan.laporanKonservasi.index')->with('error', 'Laporan tidak ditemukan.');
        }

        return view('admin_lapangan.laporan.detail', [
            'title' => 'Laporan Konservasi',
            'active' => 'Detail',
            'laporan' => $laporan,
            'user' => auth()->user(),
        ]);
    }


    public function edit(string $id)
    {
        $laporan = LaporanKonservasi::findOrFail($id);

        return view('admin_lapangan.laporan.edit', [
            'title'   => 'Edit Laporan Konservasi',
            'active'  => 'Edit',
            'laporan' => $laporan,
            'user'    => auth()->user(),
        ]);
    }



    public function update(Request $request, string $id)
    {
        $laporan = LaporanKonservasi::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'judulLaporan'   => 'required|string|max:255',
            'jenisKegiatan'  => 'required|string|max:255',
            'tanggalMulai'   => 'required|date',
            'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
            'keterangan'     => 'nullable|string',

            'daerahLokasi'   => 'required|string|max:255',
            'kabupaten'      => 'required|string|max:255',
            'kecamatan'      => 'required|string|max:255',
            'latitude'       => 'required',
            'longitude'      => 'required',

            'luasArea'       => 'required|numeric|min:0',
            'suratTugas'     => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'fotoSebelum'    => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'fotoSetelah'    => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        // 2. Path upload
        $uploadPath = public_path('uploads/laporan');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // 3. Handle file baru jika ada
        foreach (['suratTugas', 'fotoSebelum', 'fotoSetelah'] as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama kalau ada
                $oldFile = public_path('uploads/laporan/' . $laporan->$field);
                if ($laporan->$field && file_exists($oldFile)) {
                    unlink($oldFile);
                }

                // Simpan file baru
                $file = $request->file($field);
                $newName = time() . '_' . $field . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $newName);
                $laporan->$field = $newName;
            }
        }

        // 4. Update field lainnya
        $laporan->update([
            'judulLaporan'   => $request->judulLaporan,
            'jenisKegiatan'  => $request->jenisKegiatan,
            'tanggalMulai'   => $request->tanggalMulai,
            'tanggalSelesai' => $request->tanggalSelesai,
            'keterangan'     => $request->keterangan,

            'daerahLokasi'   => $request->daerahLokasi,
            'kabupaten'      => $request->kabupaten,
            'kecamatan'      => $request->kecamatan,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,

            'luasArea'       => $request->luasArea,
        ]);

        // 5. Redirect sukses
        return redirect()->route('admin_lapangan.laporanKonservasi.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }


    public function destroy(string $id)
    {
        $laporan = LaporanKonservasi::findOrFail($id);

        $files = [
            $laporan->suratTugas,
            $laporan->fotoSebelum,
            $laporan->fotoSetelah,
        ];

        foreach ($files as $file) {
            if ($file) {
                $path = public_path('uploads/laporan/' . $file);
                if (file_exists($path) && is_file($path)) {
                    unlink($path);
                }
            }
        }

        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan dan file terkait berhasil dihapus.');
    }



    public function setujui($id)
    {
        $laporan = LaporanKonservasi::findOrFail($id);
        $laporan->status = 1;
        $laporan->save();

        return redirect()->back()->with('success', 'Laporan telah disetujui.');
    }



    public function tolak($id)
    {
        $laporan = LaporanKonservasi::findOrFail($id);
        $laporan->status = 2;
        $laporan->save();

        return redirect()->back()->with('success', 'Laporan telah ditolak.');
    }
}
