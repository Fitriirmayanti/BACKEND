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

            $file->move(
                public_path('uploads/galeri'),
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

            // hapus gambar lama
            if (
                $data->gambar &&
                file_exists(public_path('uploads/galeri/' . $data->gambar))
            ) {
                unlink(public_path('uploads/galeri/' . $data->gambar));
            }

            $file = $request->file('gambar');

            $gambarName =
                Str::uuid()
                . '.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/galeri'),
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

        // hapus gambar
        if (
            $data->gambar &&
            file_exists(public_path('uploads/galeri/' . $data->gambar))
        ) {
            unlink(public_path('uploads/galeri/' . $data->gambar));
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Galeri berhasil dihapus'
        ]);
    }
}