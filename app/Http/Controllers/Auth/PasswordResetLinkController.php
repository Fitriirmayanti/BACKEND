<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = \Password::sendResetLink(
            $request->only('email')
        );

        if ($status === \Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 200,
                'message' => 'Link reset password berhasil dikirim ke email'
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Email tidak ditemukan'
        ], 400);
    }
}
