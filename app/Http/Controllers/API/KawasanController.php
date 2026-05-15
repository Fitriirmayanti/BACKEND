<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KawasanKonservasi;

class KawasanController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = KawasanKonservasi::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // =========================
    // SHOW
    // =========================
    public function show($id)
    {
        $data = KawasanKonservasi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Kawasan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'nullable|string',
            'luasKawasan' => 'nullable|numeric|min:0',
            'jenisKawasan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kondisi' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gambarName = null;

        // upload gambar
        if ($request->hasFile('gambar')) {

            $gambarName =
                time() . '_' .
                $request->gambar->getClientOriginalName();

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/kawasan';

            // 🔥 pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // 🔥 upload gambar
            $request->gambar->move(
                $destination,
                $gambarName
            );
        }


        $data = KawasanKonservasi::create([
            'deskripsi' => $request->deskripsi,
            'luasKawasan' => $request->luasKawasan,
            'jenisKawasan' => $request->jenisKawasan,
            'alamat' => $request->alamat,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
            'gambar' => $gambarName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kawasan berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $data = KawasanKonservasi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Kawasan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'deskripsi' => 'nullable|string',
            'luasKawasan' => 'nullable|numeric|min:0',
            'jenisKawasan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kondisi' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $updateData = [
            'deskripsi' => $request->deskripsi,
            'luasKawasan' => $request->luasKawasan,
            'jenisKawasan' => $request->jenisKawasan,
            'alamat' => $request->alamat,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
        ];

        // upload gambar baru
        if ($request->hasFile('gambar')) {

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/kawasan';

            // 🔥 pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // 🔥 hapus gambar lama
            if (
                $data->gambar &&
                file_exists($destination . '/' . $data->gambar)
            ) {
                unlink($destination . '/' . $data->gambar);
            }

            $gambarName =
                time() . '_' .
                $request->gambar->getClientOriginalName();

            // 🔥 upload gambar baru
            $request->gambar->move(
                $destination,
                $gambarName
            );

            $updateData['gambar'] = $gambarName;
        }

        $data->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Kawasan berhasil diupdate',
            'data' => $data
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $data = KawasanKonservasi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Kawasan tidak ditemukan'
            ], 404);
        }

        // 🔥 path upload cPanel
        $destination = '/home/codg6743/public_html/uploads/kawasan';

        // 🔥 hapus gambar
        if (
            $data->gambar &&
            file_exists($destination . '/' . $data->gambar)
        ) {
            unlink($destination . '/' . $data->gambar);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kawasan berhasil dihapus'
        ]);
    }
}