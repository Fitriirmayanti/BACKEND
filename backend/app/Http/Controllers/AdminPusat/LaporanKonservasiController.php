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
        //
    }


    public function show(string $id)
    {
        $laporan = LaporanKonservasi::with('user')->find($id);
        if (!$laporan) {
            return redirect()->route('admin_pusat.laporanKonservasi.index')->with('error', 'Laporan tidak ditemukan.');
        }

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
