<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\UserPermission;
use App\Models\MenuPermission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class PenggunaCOntroller extends Controller
{

    public function index()
    {
        $pengguna = User::where('role', 'admin_lapangan')->get();

        return view('admin_pusat.pengguna', [
            'title' => 'Pengguna',
            'active' => 'Index',
            'pengguna' => $pengguna,
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
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_lapangan',
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
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
        $user = User::findOrFail($id);

        $data = $request->only(['name', 'username', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui');
    }




    public function destroy(string $id)
    {
        $pengguna = User::findOrFail($id);

        $pengguna->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
