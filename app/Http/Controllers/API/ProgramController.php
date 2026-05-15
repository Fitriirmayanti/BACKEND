<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edukasi;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = Edukasi::where('kategori', 'Program')
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
        $data = Edukasi::where('kategori', 'Program')
            ->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Program tidak ditemukan'
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoName = null;

        // upload foto
        if ($request->hasFile('foto')) {

            $fotoName = time() . '_' . $request->foto->getClientOriginalName();

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/edukasi';

            // 🔥 pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // 🔥 upload foto
            $request->foto->move(
                $destination,
                $fotoName
            );
        }


        $data = Edukasi::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoName,
            'kategori' => 'Program',
            'keygaleri' => Str::random(8),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Program berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $data = Edukasi::where('kategori', 'Program')
            ->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Program tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $updateData = [
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'deskripsi' => $request->deskripsi,
        ];

        // upload foto baru
        if ($request->hasFile('foto')) {

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/edukasi';

            // 🔥 pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // 🔥 hapus foto lama
            if (
                $data->foto &&
                file_exists($destination . '/' . $data->foto)
            ) {
                unlink($destination . '/' . $data->foto);
            }

            $fotoName = time() . '_' . $request->foto->getClientOriginalName();

            // 🔥 upload foto baru
            $request->foto->move(
                $destination,
                $fotoName
            );

            $updateData['foto'] = $fotoName;
        }

        $data->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Program berhasil diupdate',
            'data' => $data
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $data = Edukasi::where('kategori', 'Program')
            ->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Program tidak ditemukan'
            ], 404);
        }

       // 🔥 path upload cPanel
        $destination = '/home/codg6743/public_html/uploads/edukasi';

        // 🔥 hapus foto
        if (
            $data->foto &&
            file_exists($destination . '/' . $data->foto)
        ) {
            unlink($destination . '/' . $data->foto);
        }


        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Program berhasil dihapus'
        ]);
    }
}