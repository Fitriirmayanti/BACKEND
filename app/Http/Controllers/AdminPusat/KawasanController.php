<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KawasanKonservasi;
use Illuminate\Support\Str;

class KawasanController extends Controller
{

    public function index()
    {
        $kawasan = KawasanKonservasi::first();

        return view('admin_pusat.kawasan', [
            'title' => 'Kawasan',
            'active' => 'Index',
            'kawasan' => $kawasan,
            'user' => auth()->user(),
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'luasKawasan'   => 'required|string|max:255',
            'jenisKawasan'  => 'required|string|max:255',
            'kondisi'       => 'required|string|max:255',
            'alamat'        => 'required|string',
            'status'        => 'required|string',
            'deskripsi'     => 'required|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $kawasan = KawasanKonservasi::findOrFail($id);

        // Handle foto baru
        if ($request->hasFile('foto')) {
            // hapus foto lama
            $oldPath = public_path('img/' . $kawasan->gambar);
            if (file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            $fotoName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('img'), $fotoName);

            $validated['gambar'] = $fotoName;
        } else {
            $validated['gambar'] = $kawasan->gambar;
        }

        // Update data kawasan
        $kawasan->update([
            'luasKawasan'   => $validated['luasKawasan'],
            'jenisKawasan'  => $validated['jenisKawasan'],
            'kondisi'       => $validated['kondisi'],
            'alamat'        => $validated['alamat'],
            'status'        => $validated['status'],
            'deskripsi'     => $validated['deskripsi'],
            'gambar'        => $validated['gambar'],
        ]);

        return redirect()->back()->with('success', 'Kawasan berhasil diperbarui.');
    }




    public function destroy(string $id)
    {
        //
    }
}
