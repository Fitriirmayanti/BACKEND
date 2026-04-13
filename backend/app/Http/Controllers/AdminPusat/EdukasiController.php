<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edukasi;
use App\Models\Galeri;
use Illuminate\Support\Str;

class EdukasiController extends Controller
{

    public function index()
    {
        $edukasi = Edukasi::where('kategori', '!=', 'Program')->get();

        return view('admin_pusat.edukasi.index', [
            'title' => 'Edukasi',
            'active' => 'Index',
            'edukasi' => $edukasi,
            'user' => auth()->user(),
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'kategori'    => 'required|string',
            'deskripsi'    => 'required|string',
            'foto'         => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'galeri.*'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Simpan foto
        $fotoName = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('uploads/edukasi'), $fotoName);

        $keygaleri = Str::random(8);

        // Slug unik
        $slug = Str::slug($validated['judul']);
        if (Edukasi::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(5);
        }

        Edukasi::create([
            'judul'         => $validated['judul'],
            'slug'         => $slug,
            'deskripsi'    => $validated['deskripsi'],
            'kategori'  => $validated['kategori'],
            'foto'         => $fotoName,
            'keygaleri'    => $keygaleri,
        ]);

        if ($request->hasFile('galeri')) {
            foreach ($request->file('galeri') as $galeriFile) {
                $galeriName = uniqid() . '.' . $galeriFile->extension();
                $galeriFile->move(public_path('uploads/galeri'), $galeriName);

                Galeri::create([
                    'judul' => $validated['judul'],
                    'keterangan' => $validated['judul'],
                    'gambar'    => $galeriName,
                    'keygaleri'    => $keygaleri,
                ]);
            }
        }

        return redirect()->route('admin_pusat.edukasi.index')
            ->with('success', 'Edukasi berhasil ditambahkan.');
    }



    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $edukasi = Edukasi::findOrFail($id);
        $galeri = Galeri::where('keygaleri', $edukasi->keygaleri)->get();

        return view('admin_pusat.edukasi.edit', [
            'title' => 'Edukasi',
            'active' => 'Edit',
            'edukasi' => $edukasi,
            'galeri' => $galeri,
            'user' => auth()->user(),
        ]);
    }



    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'kategori'    => 'required|string',
            'deskripsi'    => 'required|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $edukasi = Edukasi::findOrFail($id);

        // Slug unik
        $slug = Str::slug($validated['judul']);
        $cekSlug = Edukasi::where('slug', $slug)
            ->where('id', '!=', $edukasi->id)
            ->exists();

        if ($cekSlug) {
            $slug .= '-' . Str::random(5);
        }

        // Handle foto baru
        if ($request->hasFile('foto')) {
            $oldPath = public_path('uploads/edukasi/' . $edukasi->foto);
            if (file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            $fotoName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads/edukasi'), $fotoName);

            $validated['foto'] = $fotoName;
        } else {
            $validated['foto'] = $edukasi->foto;
        }

        // Update edukasi
        $edukasi->update([
            'judul'         => $validated['judul'],
            'slug'         => $slug,
            'kategori'  => $validated['kategori'],
            'deskripsi'    => $validated['deskripsi'],
            'foto'         => $validated['foto'],
        ]);

        return redirect()->back()
            ->with('success', 'Edukasi berhasil diperbarui.');
    }



    public function destroy(string $id)
    {
        $edukasi = Edukasi::findOrFail($id);

        if ($edukasi->foto && file_exists(public_path('uploads/edukasi/' . $edukasi->foto))) {
            unlink(public_path('uploads/edukasi/' . $edukasi->foto));
        }

        $galeriItems = Galeri::where('keygaleri', $edukasi->keygaleri)->get();
        foreach ($galeriItems as $galeri) {
            if ($galeri->gambar && file_exists(public_path('uploads/galeri/' . $galeri->gambar))) {
                unlink(public_path('uploads/galeri/' . $galeri->gambar));
            }
            $galeri->delete();
        }

        $edukasi->delete();

        return redirect()->route('admin_pusat.edukasi.index')->with('success', 'Data edukasi berhasil dihapus.');
    }
}
