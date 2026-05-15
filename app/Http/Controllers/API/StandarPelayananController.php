<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StandarPelayanan;
use Illuminate\Http\Request;

class StandarPelayananController extends Controller
{
    // =========================
    // MASYARAKAT KIRIM SARAN
    // =========================

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'nomor_hp' => 'required|string|max:20',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        $data = StandarPelayanan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'judul' => $request->judul,
            'pesan' => $request->pesan,
        ]);

        return response()->json([
            'message' => 'Saran berhasil dikirim',
            'data' => $data
        ], 201);
    }

    // =========================
    // ADMIN PUSAT LIHAT SARAN
    // =========================

    public function index()
    {
        $data = StandarPelayanan::latest()->get();

        return response()->json([
            'message' => 'Data standar pelayanan',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = StandarPelayanan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}