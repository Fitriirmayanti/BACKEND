<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KawasanKonservasi;
use App\Models\LaporanKonservasi;
use App\Models\Masyarakat;
use App\Models\StandarPelayanan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // =====================================================
    // DASHBOARD ADMIN PUSAT
    // =====================================================

    public function adminPusat()
    {
        $awal = date('Y-m-01');
        $akhir = date('Y-m-d');

        // total masyarakat
        $masyarakat = Masyarakat::count();

        // laporan bulan ini
        $laporanTerakhir = LaporanKonservasi::whereBetween('created_at', [$awal, $akhir])
            ->count();

        // laporan disetujui
        $laporanDisetujui = LaporanKonservasi::where('status', 1)
            ->whereBetween('created_at', [$awal, $akhir])
            ->count();

        // total feedback
        $feedback = StandarPelayanan::count();

        // grafik laporan tahunan
        $laporanTahunan = [];

        for ($i = 1; $i <= 12; $i++) {

            $laporanTahunan[] = LaporanKonservasi::whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        $daerah = KawasanKonservasi::select('jenisKawasan')
                ->distinct()
                ->get();

        return response()->json([
            'message' => 'Dashboard admin pusat berhasil dimuat',

            'data' => [
                'masyarakat' => $masyarakat,
                'laporanTerakhir' => $laporanTerakhir,
                'laporanDisetujui' => $laporanDisetujui,
                'feedback' => $feedback,
                'laporanTahunan' => $laporanTahunan,
                'daerah' => $daerah,
                'user' => auth()->user(),
            ]
        ]);
    }

    // =====================================================
    // DASHBOARD ADMIN LAPANGAN
    // =====================================================

    public function adminLapangan(Request $request)
    {
        $user = $request->user();

        // jumlah laporan milik user
        $jumlahLaporan = LaporanKonservasi::where('pengirim', $user->id)
            ->count();

        // laporan diterima
        $diterima = LaporanKonservasi::where('pengirim', $user->id)
            ->where('status', 1)
            ->count();

        // laporan ditolak
        $ditolak = LaporanKonservasi::where('pengirim', $user->id)
            ->where('status', 2)
            ->count();

        // grafik laporan bulanan
        $laporanBulanan = [];

        for ($i = 1; $i <= 12; $i++) {

            $laporanBulanan[] = LaporanKonservasi::where('pengirim', $user->id)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        return response()->json([
            'message' => 'Dashboard admin lapangan berhasil dimuat',

            'data' => [
                'jumlahLaporan' => $jumlahLaporan,
                'diterima' => $diterima,
                'ditolak' => $ditolak,
                'laporanBulanan' => $laporanBulanan,
                'user' => $user,
            ]
        ]);
    }
}