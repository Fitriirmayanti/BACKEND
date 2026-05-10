<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edukasi;
use Illuminate\Support\Str;

class EdukasiController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = Edukasi::where('kategori', '!=', 'Program')
            ->orderBy('id', 'desc')
            ->get();

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
        $data = Edukasi::where('kategori', '!=', 'Program')
            ->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Edukasi tidak ditemukan'
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
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:Satwa,Executive',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoName = null;

        // upload foto
        if ($request->hasFile('foto')) {

            $fotoName = time() . '.' . $request->foto->extension();

            $request->foto->move(
                public_path('uploads/edukasi'),
                $fotoName
            );
        }

        // slug unik
        $slug = Str::slug($request->judul);

        if (Edukasi::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(5);
        }

        $data = Edukasi::create([
            'judul' => $request->judul,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'foto' => $fotoName,
            'keygaleri' => Str::random(8),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Edukasi berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $data = Edukasi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Edukasi tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:Satwa,Executive',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // slug unik
        $slug = Str::slug($request->judul);

        $cekSlug = Edukasi::where('slug', $slug)
            ->where('id', '!=', $id)
            ->exists();

        if ($cekSlug) {
            $slug .= '-' . Str::random(5);
        }

        $updateData = [
            'judul' => $request->judul,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ];

        // upload foto baru
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if (
                $data->foto &&
                file_exists(public_path('uploads/edukasi/' . $data->foto))
            ) {
                unlink(public_path('uploads/edukasi/' . $data->foto));
            }

            $fotoName = time() . '.' . $request->foto->extension();

            $request->foto->move(
                public_path('uploads/edukasi'),
                $fotoName
            );

            $updateData['foto'] = $fotoName;
        }

        $data->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Edukasi berhasil diupdate',
            'data' => $data
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $data = Edukasi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Edukasi tidak ditemukan'
            ], 404);
        }

        // hapus foto
        if (
            $data->foto &&
            file_exists(public_path('uploads/edukasi/' . $data->foto))
        ) {
            unlink(public_path('uploads/edukasi/' . $data->foto));
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Edukasi berhasil dihapus'
        ]);
    }
}