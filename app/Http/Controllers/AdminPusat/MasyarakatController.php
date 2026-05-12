<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masyarakat;
use Illuminate\Support\Str;

class masyarakatController extends Controller
{

    public function index()
    {
        $masyarakat = masyarakat::all();
        return view('admin_pusat.masyarakat', [
            'title' => 'masyarakat',
            'active' => 'Index',
            'masyarakat' => $masyarakat,
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


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        $masyarakat = masyarakat::findOrFail($id);

        $masyarakat->delete();

        return redirect()->back()->with('success', 'masyarakat berhasil dihapus.');
    }
}
