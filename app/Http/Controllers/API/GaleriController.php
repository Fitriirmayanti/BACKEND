<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = Galeri::orderBy('id', 'desc')->get();

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
        $data = Galeri::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri tidak ditemukan'
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
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'keygaleri' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gambarName = null;

        // upload gambar
        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            $gambarName =
                Str::slug($request->judul)
                . '-' .
                time()
                . '.' .
                $file->getClientOriginalExtension();

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/galeri';

            // 🔥 pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // 🔥 upload gambar
            $file->move(
                $destination,
                $gambarName
            );
        }

        $data = Galeri::create([
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'gambar' => $gambarName,
            'keygaleri' => $request->keygaleri,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Galeri berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $data = Galeri::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $updateData = [
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
        ];

      // upload gambar baru
        if ($request->hasFile('gambar')) {

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/galeri';

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

            $file = $request->file('gambar');

            $gambarName =
                Str::uuid()
                . '.' .
                $file->getClientOriginalExtension();

            // 🔥 upload gambar baru
            $file->move(
                $destination,
                $gambarName
            );

            $updateData['gambar'] = $gambarName;
        }


        $data->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Galeri berhasil diupdate',
            'data' => $data
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $data = Galeri::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri tidak ditemukan'
            ], 404);
        }

        // 🔥 path upload cPanel
        $destination = '/home/codg6743/public_html/uploads/galeri';

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
            'message' => 'Galeri berhasil dihapus'
        ]);
    }
}