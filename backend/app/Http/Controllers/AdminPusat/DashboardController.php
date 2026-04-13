<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Edukasi;
use App\Models\LaporanKonservasi;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = Customer::count();

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

        return view('admin_pusat.dashboard', [
            'title' => 'admin_pusat',
            'active' => 'Dashboard admin_pusat',
            'customer' => $customer,
            'laporanTerakhir' => $laporanTerakhir,
            'laporanDisetujui' => $laporanDisetujui,
            'laporanTahunan' => $laporanTahunan,
            'daerah' => $daerah,
            'user' => auth()->user(),
        ]);
    }
}
