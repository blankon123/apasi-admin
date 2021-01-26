<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            response("Akun tidak ditemukan", 422);
        }
        return [
            "token" => $user->createToken($request->device_name)->plainTextToken,
            "user" => $user,
        ];
    }

    public function logout(Request $request)
    {
        return Auth::logout();
    }
}