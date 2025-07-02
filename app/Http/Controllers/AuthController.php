<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

        $userData = [
            
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        
        ];

        $user = User::create($userData);
        $token = $user->createToken('forumapp')->plainTextToken;

        return response ([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login (LoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 422);
        }

        $token = $user->createToken('transaksi-umkm')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }
    public function forgotPassword(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json(['message' => 'Link reset password dikirim ke email.']);
    }

    return response()->json(['message' => __($status)], 400);

}
}