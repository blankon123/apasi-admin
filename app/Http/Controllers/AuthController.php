<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        try {
            return Auth::attempt($request->only('username', 'password')) ? response()->json(Auth::user(), 200) : response("Akun tidak ditemukan", 422);
        } catch (\Throwable $th) {
            return response("Pastikan Username dan Password Terisi " . $th->getMessage(), 422);
        };
    }

    public function logout(Request $request)
    {
        return Auth::logout();
    }
}