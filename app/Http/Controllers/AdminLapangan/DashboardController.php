<?php

namespace App\Http\Controllers\AdminLapangan;

use App\Http\Controllers\Controller;
use App\Models\LaporanKonservasi;

class DashboardController extends Controller
{
    public function index()
    {
        $laporan = LaporanKonservasi::where('pengirim', auth()->user()->id)->get();

        $laporanDisetujui = LaporanKonservasi::where('status', 1)
            ->where('pengirim', auth()->user()->id)
            ->count();

        $laporanDitolak = LaporanKonservasi::where('status', 2)
            ->where('pengirim', auth()->user()->id)
            ->count();

        // hitung jumlah laporan per daerah
        $laporanPerDaerah = LaporanKonservasi::select('daerahLokasi', \DB::raw('COUNT(*) as total'))
            ->where('pengirim', auth()->user()->id)
            ->groupBy('daerahLokasi')
            ->pluck('total', 'daerahLokasi');

        return view('admin_lapangan.dashboard', [
            'title' => 'Dashboard admin_lapangan',
            'active' => 'Dashboard',
            'laporan' => $laporan,
            'laporanDisetujui' => $laporanDisetujui,
            'laporanDitolak' => $laporanDitolak,
            'laporanPerDaerah' => $laporanPerDaerah,
            'user' => auth()->user(),
        ]);
    }
}
