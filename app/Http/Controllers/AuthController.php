<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle login request (API token-based, not web view)
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Buat token Sanctum
        $token = $user->createToken('transaksi-umkm')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Optional: Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout berhasil.'], 200);
    }
}
