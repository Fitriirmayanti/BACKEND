<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use Illuminate\Support\Str;

class GaleriController extends Controller
{

    public function index()
    {
        $galeri = Galeri::where('keygaleri', 'banner')->get();
        return view('admin_pusat.galeri', [
            'title' => 'Galeri',
            'active' => 'Banner',
            'galeri' => $galeri,
            'user' => auth()->user(),
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string|max:100',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $file = $request->file('gambar');
        $namaFile = Str::slug($request->judul) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/galeri'), $namaFile);

        $keygaleri = $request->keygaleri ?? 'banner';

        Galeri::create([
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'gambar' => $namaFile,
            'keygaleri' => $keygaleri
        ]);

        return redirect()->back()->with('success', 'Galeri berhasil ditambahkan.');
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
        $galeri = Galeri::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $galeri->judul = $request->judul;
        $galeri->keterangan = $request->keterangan;

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar && file_exists(public_path('uploads/galeri/' . $galeri->gambar))) {
                unlink(public_path('uploads/galeri/' . $galeri->gambar));
            }

            $file = $request->file('gambar');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galeri'), $filename);
            $galeri->gambar = $filename;
        }

        $galeri->save();

        return redirect()->back()->with('success', 'Galeri berhasil diperbarui.');
    }


    public function destroy(string $id)
    {
        $galeri = Galeri::findOrFail($id);

        $gambarPath = public_path('uploads/galeri/' . $galeri->gambar);
        if ($galeri->gambar && file_exists($gambarPath)) {
            unlink($gambarPath);
        }

        $galeri->delete();

        return redirect()->back()->with('success', 'Galeri berhasil dihapus.');
    }
}
