<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    // Menyimpan atau memperbarui data toko
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:stores,email',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'business_type' => 'required|string|max:100', // Validasi untuk tipe usaha
        ]);

        $store = Store::updateOrCreate(
            ['email' => $validated['email']], // Cari berdasarkan email
            $validated // Jika ditemukan, perbarui; jika tidak, buat baru
        );

        // return $this->successResponse(new PaginationResource())
    }

    // Menampilkan data toko berdasarkan email
    public function show($email)
    {
        $store = Store::where('email', $email)->first();

        if (!$store) {
            return response()->json(['message' => 'Store not found!'], 404);
        }

        return response()->json($store);
    }

    // Menampilkan semua data toko
    public function index()
    {
        $stores = Store::all();

        return response()->json($stores);
    }

    // Menghapus data toko
    public function destroy($email)
    {
        $store = Store::where('email', $email)->first();

        if (!$store) {
            return response()->json(['message' => 'Store not found!'], 404);
        }

        $store->delete();

        return response()->json(['message' => 'Store deleted successfully!']);
    }
}
