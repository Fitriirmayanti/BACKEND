<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Masyarakat;
use App\Models\Website;
use App\Models\Edukasi;
use App\Models\KawasanKonservasi;
use App\Models\Peraturan;

class HomeController extends Controller
{
    public function index()
    {
        $banner = Galeri::where('keygaleri', 'banner')->get();
        $edukasi = Edukasi::where('kategori', 'Program')->orderBy('id', 'desc')->take(4)->get();
        $website = Website::firstOrFail();

        //return view('index', [
           // 'title' => $website->nama,
           // 'active' => 'home',
           // 'banner' => $banner,
           // 'edukasi' => $edukasi,
        //]);
        return response()->json([
        'message' => 'API SIKOMA aktif'
        ]);

    }



    public function edukasi(Request $request)
    {
        $filterKategori = $request->query('kategori');
        if ($filterKategori) {
            $kategori = $filterKategori;
        } else {
            $kategori = 'Program';
        }

        $edukasi = Edukasi::where('kategori', $kategori)->orderBy('id', 'desc')->get();
        return view('edukasi', [
            'title' => 'Program',
            'active' => 'edukasi',
            'edukasi' => $edukasi,
            'kategori' => $kategori,
        ]);
    }



    public function detailEdukasi($slug =  null)
    {
        $edukasi = Edukasi::where('slug', $slug)->first();
        if (!$edukasi) {
            return redirect()->to('/edukasi')->with('error', 'edukasi tidak terdaftar!');
        }

        $galeri = Galeri::where('keygaleri', $edukasi->keygaleri)->get();

        return view('detailedukasi', [
            'title' => $edukasi->judul,
            'active' => 'edukasi',
            'edukasi' => $edukasi,
            'galeri' => $galeri,
            'foto' => $edukasi->foto,
        ]);
    }



    public function informasi()
    {
        $satwa = Edukasi::where('kategori', 'Satwa')->orderBy('id', 'desc')->get();
        $executive = Edukasi::where('kategori', 'Executive')->orderBy('id', 'desc')->get();
        $peraturan = Peraturan::orderBy('id', 'desc')->get();
        $kawasan = KawasanKonservasi::orderBy('id', 'desc')->first();

        return view('informasi', [
            'title' => 'Informasi',
            'active' => 'informasi',
            'satwa' => $satwa,
            'executive' => $executive,
            'peraturan' => $peraturan,
            'kawasan' => $kawasan,
        ]);
    }




    public function standarPelayanan()
    {
        $standarPelayanan = Edukasi::where('kategori', 'Standar Pelayanan')->orderBy('id', 'desc')->get();
        $website = Website::first();
        
        return view('standarPelayanan', [
            'title' => 'Standar Pelayanan',
            'active' => 'standar-pelayanan',
            'standarPelayanan' => $standarPelayanan,
            'website' => $website,
        ]);
    }




    public function simpanPesan(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'nohp'       => 'required|string|max:20',
            'judul'       => 'required|string',
            'pesan'    => 'required|string',
        ]);

        masyarakat::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'nohp'       => $request->nohp,
            'negara'       => $request->judul,
            'pesan'    => $request->pesan,
        ]);

        return redirect()->back()->with('success', 'Pesan anda telah terkirim.');
    }
}
