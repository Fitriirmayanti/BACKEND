<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
   // ✅ INDEX
    public function index()
    {
        $data = User::whereIn('role', [
            'admin_pusat',
            'admin_lapangan'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ✅ SHOW
    public function show($id)
    {
        $data = User::find($id);

        // ❌ user tidak ditemukan
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ✅ STORE
    public function store(Request $request)
    {
        // ✅ validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin_pusat,admin_lapangan',
        ]);

        // ✅ simpan user
        $data = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $data = User::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin_pusat,admin_lapangan',
        ]);

        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->role = $request->role;

        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);
        }

        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diupdate',
            'data' => $data
        ]);
    }

    // ✅ DELETE
    public function destroy($id)
    {
        $data = User::find($id);

        // ❌ user tidak ditemukan
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus'
        ]);
    }
}