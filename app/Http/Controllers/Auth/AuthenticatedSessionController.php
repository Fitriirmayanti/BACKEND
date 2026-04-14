<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Jika request dari API (expects JSON), kembalikan JSON agar tidak redirect (hindari CORS)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login berhasil',
                'role' => $user->role,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        }

        if ($user->role === 'admin_lapangan') {
        return redirect()->route('admin_lapangan');
        } elseif ($user->role === 'admin_pusat') {
        return redirect()->route('admin_pusat');
        }


        // Jika role tidak dikenali, logout dan kembali ke login dengan error
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors(['email' => 'Akun Anda tidak memiliki akses.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        return response()->json([
            'status' => 200,
            'message' => 'Logout berhasil',
            'data' => null
        ]);
    }

    /**
     * Handle API login and always return JSON without redirects.
     */
   public function storeApi(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Login gagal'
            ], 401);
        }

        // 🔥 INI KUNCINYA (BUAT TOKEN)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'token' => $token,
                'role' => $user->role,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]
        ]);
    }

}
