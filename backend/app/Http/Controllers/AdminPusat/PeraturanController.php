<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Peraturan;
use Illuminate\Support\Str;

class PeraturanController extends Controller
{

    public function index()
    {
        $peraturan = Peraturan::all();
        return view('admin_pusat.peraturan', [
            'title' => 'Peraturan',
            'active' => 'Index',
            'peraturan' => $peraturan,
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
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500',
            'tahun'     => 'required|integer',
            'nomor'     => 'required|string|max:100',
            'file'      => 'required|mimes:pdf|max:5120',
        ]);

        $file     = $request->file('file');
        $fileName = Str::slug($request->nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/peraturan'), $fileName);

        Peraturan::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tahun'     => $request->tahun,
            'nomor'     => $request->nomor,
            'file'      => $fileName,
        ]);

        return redirect()->back()->with('success', 'Peraturan berhasil ditambahkan.');
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
        $peraturan = Peraturan::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500',
            'tahun'     => 'required|integer',
            'nomor'     => 'required|string|max:100',
            'file'      => 'nullable|mimes:pdf|max:5120',
        ]);

        $peraturan->nama      = $request->nama;
        $peraturan->deskripsi = $request->deskripsi;
        $peraturan->tahun     = $request->tahun;
        $peraturan->nomor     = $request->nomor;

        if ($request->hasFile('file')) {
            $oldFile = public_path('uploads/peraturan/' . $peraturan->file);
            if ($peraturan->file && file_exists($oldFile)) {
                unlink($oldFile);
            }

            $file = $request->file('file');
            $filename = Str::slug($request->nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/peraturan'), $filename);

            $peraturan->file = $filename;
        }

        $peraturan->save();

        return redirect()->back()->with('success', 'Peraturan berhasil diperbarui.');
    }



    public function destroy(string $id)
    {
        $peraturan = Peraturan::findOrFail($id);

        $gambarPath = public_path('uploads/peraturan/' . $peraturan->file);
        if ($peraturan->file && file_exists($gambarPath)) {
            unlink($gambarPath);
        }

        $peraturan->delete();

        return redirect()->back()->with('success', 'Peraturan berhasil dihapus.');
    }
}
