<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peraturan;
use Illuminate\Support\Str;

class PeraturanController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = Peraturan::orderBy('id', 'desc')->get();

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
        $data = Peraturan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Peraturan tidak ditemukan'
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
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500',
            'tahun' => 'required|integer',
            'nomor' => 'required',
            'file' => 'required|mimes:pdf|max:5120',
        ]);

        $fileName = null;

        // upload file
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $fileName =
                Str::slug($request->nama)
                . '-' .
                time()
                . '.' .
                $file->getClientOriginalExtension();

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/peraturan';

            // 🔥 pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // 🔥 upload file
            $file->move(
                $destination,
                $fileName
            );
        }

        $data = Peraturan::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tahun' => $request->tahun,
            'nomor' => $request->nomor,
            'file' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Peraturan berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $data = Peraturan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Peraturan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500',
            'tahun' => 'required|integer',
            'nomor' => 'required',
            'file' => 'nullable|mimes:pdf|max:5120',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tahun' => $request->tahun,
            'nomor' => $request->nomor,
        ];

        // upload file baru
        if ($request->hasFile('file')) {

            // 🔥 path upload cPanel
            $destination = '/home/codg6743/public_html/uploads/peraturan';

            // 🔥 pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            // 🔥 hapus file lama
            if (
                $data->file &&
                file_exists($destination . '/' . $data->file)
            ) {
                unlink($destination . '/' . $data->file);
            }

            $file = $request->file('file');

            $fileName =
                Str::slug($request->nama)
                . '-' .
                time()
                . '.' .
                $file->getClientOriginalExtension();

            // 🔥 upload file baru
            $file->move(
                $destination,
                $fileName
            );

            $updateData['file'] = $fileName;
        }

        $data->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Peraturan berhasil diupdate',
            'data' => $data
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $data = Peraturan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Peraturan tidak ditemukan'
            ], 404);
        }

        // 🔥 path upload cPanel
        $destination = '/home/codg6743/public_html/uploads/peraturan';

        // 🔥 hapus file
        if (
            $data->file &&
            file_exists($destination . '/' . $data->file)
        ) {
            unlink($destination . '/' . $data->file);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peraturan berhasil dihapus'
        ]);
    }
}