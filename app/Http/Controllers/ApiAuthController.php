<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    // Fungsi untuk cek login
    public function checkAuth(Request $request)
    {
        // Ambil token dari header Authorization
        $token = $request->bearerToken();

        // Cari user berdasarkan token
        $user = User::where('remember_token', $token)->first();

        // Jika user ditemukan, user sedang login
        if ($user) {
            return response()->json([
                'message' => 'User is logged in',
                'user' => $user,
            ], 200);
        }

        // Jika token tidak valid atau user tidak ditemukan
        return response()->json([
            'message' => 'User is not logged in or token is invalid'
        ], 401);
    }


    // Fungsi Register (Signup)
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // Password harus cocok dengan password_confirmation
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'remember_token' => Str::random(80), // Generate API token
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    // Fungsi Login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();

        // Periksa apakah user ada dan password cocok
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        // Generate token baru
        $token = Str::random(80);
        $user->update(['remember_token' => $token]);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token, // Token dikembalikan untuk autentikasi
            'user' => $user,
        ]);
    }

    // Fungsi Logout
    public function logout(Request $request)
    {
        // Ambil token dari header Authorization (Bearer Token)
        $token = $request->bearerToken();

        // Cari user berdasarkan token
        $user = User::where('remember_token', $token)->first();

        // Jika user ditemukan, hapus token
        if ($user) {
            $user->update(['remember_token' => null]);

            return response()->json([
                'message' => 'Logout successful'
            ]);
        }

        // Jika token tidak valid
        return response()->json([
            'message' => 'Invalid token'
        ], 401);
    }
}
