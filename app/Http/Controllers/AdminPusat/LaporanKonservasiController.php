<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\LaporanKonservasi;
use Illuminate\Support\Str;

class LaporanKonservasiController extends Controller
{

    public function index(Request $request)
    {
        $daerahFilter = $request->query('daerah');
        $laporanQuery = LaporanKonservasi::with('user');

        if ($daerahFilter) {
            $laporanQuery->where('daerahLokasi', $daerahFilter);
        }

        $laporan = $laporanQuery->get();

        $daerah = LaporanKonservasi::select('daerahLokasi')
            ->distinct()
            ->pluck('daerahLokasi');

        // 🔥 INI TAMBAHAN UNTUK API
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $laporan,
                'daerah' => $daerah
            ]);
        }

        // 🔥 INI TETAP UNTUK WEB (JANGAN DIHAPUS)
        return view('admin_pusat.laporan.index', [
            'title' => 'Laporan Konservasi',
            'active' => 'Index',
            'laporan' => $laporan,
            'daerah' => $daerah,
            'user' => auth()->user(),
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        dd($request->input('keterangan'));
        $request->validate([
            'judulLaporan' => 'required',
            'jenisKegiatan' => 'required',
            'tanggalMulai' => 'required|date',
            'tanggalSelesai' => 'required|date',
            'daerahLokasi' => 'required',
            'keterangan' => 'required',
            'suratTugas' => 'required|file|mimes:jpeg,png,jpg,webp,pdf,doc,docx',
        ]);

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
            'keterangan' => $request->input('keterangan'),
            'status' => 0,
            'pengirim' => auth()->id() ?? 1, // 🔥 FIX DI SINI
        ];

        if ($request->hasFile('suratTugas')) {
            $file = time().'_'.$request->file('suratTugas')->getClientOriginalName();
            $request->file('suratTugas')->move(public_path('uploads/laporan'), $file);
            $data['suratTugas'] = $file;
        }

        if ($request->hasFile('fotoSebelum')) {
            $file = time().'_'.$request->file('fotoSebelum')->getClientOriginalName();
            $request->file('fotoSebelum')->move(public_path('uploads/laporan'), $file);
            $data['fotoSebelum'] = $file;
        }

        if ($request->hasFile('fotoSetelah')) {
            $file = time().'_'.$request->file('fotoSetelah')->getClientOriginalName();
            $request->file('fotoSetelah')->move(public_path('uploads/laporan'), $file);
            $data['fotoSetelah'] = $file;
        }

        $laporan = LaporanKonservasi::create($data);

        return response()->json([
            'message' => 'Berhasil disimpan',
            'data' => $laporan
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $laporan = LaporanKonservasi::with('user')->find($id);

        // 🔥 HANDLE KALAU DATA TIDAK ADA
        if (!$laporan) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }

            return redirect()
                ->route('admin_pusat.laporanKonservasi.index')
                ->with('error', 'Laporan tidak ditemukan.');
        }

        // 🔥 MODE API
        if ($request->expectsJson()) {
            return response()->json($laporan);
        }

        // 🔥 MODE WEB (TETAP DIPAKAI)
        return view('admin_pusat.laporan.detail', [
            'title' => 'Laporan Konservasi',
            'active' => 'Detail',
            'laporan' => $laporan,
            'user' => auth()->user(),
        ]);
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(Request $request, string $id)
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

        // 🔥 MODE API
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Laporan berhasil dihapus'
            ]);
        }

        // 🔥 MODE WEB
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
