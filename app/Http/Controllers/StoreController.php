<?php

namespace App\Http\Controllers;

use App\Enums\BusinessType;
use App\Http\Requests\Store\StoreStoreRequest;
use App\Http\Requests\Store\UpdateStoreRequest;
use App\Models\Store;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\Store\StoreResource;
use App\Models\User;
use App\Services\EnumService;

class StoreController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = intval($request->query('per_page', 10));

            if ($perPage < 1) {
                $perPage = 10;
            } elseif ($perPage > 100) {
                $perPage = 100;
            }

            $stores = Store::with('users')->paginate($perPage);
            return $this->successResponse(
                new PaginationResource(StoreResource::collection($stores)),
                'Daftar toko berhasil diambil'
            );
        } catch (Exception $e) {
            Log::error('Gagal mengambil toko: ' . $e->getMessage());
            return $this->errorResponse('Gagal mengambil toko', 500);
        }
    }

    public function assignUserToStore(Request $request, $storeId): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $store = Store::findOrFail($storeId);
            $user = User::findOrFail($validated['user_id']);

            $user->store_id = $store->id; // Assign store ke user
            $user->save();

            return $this->successResponse(null, "User berhasil ditambahkan ke store");
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Store atau user tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal menambahkan user ke store: " . $e->getMessage());
            return $this->errorResponse("Gagal menambahkan user ke store", 500);
        }
    }


    public function store(StoreStoreRequest $request): JsonResponse
    {
        try {
            $store = Store::create($request->validated());
            return $this->successResponse(new StoreResource($store), 'Toko berhasil ditambahkan', 201);
        } catch (Exception $e) {
            Log::error('Gagal menambahkan toko: ' . $e->getMessage());
            return $this->errorResponse('Gagal menambahkan toko', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $store = Store::with('users')->findOrFail($id);
            return $this->successResponse(new StoreResource($store), 'Detail toko berhasil diambil');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Toko dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal mengambil toko dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal mengambil toko', 500);
        }
    }

    public function update(UpdateStoreRequest $request, $id): JsonResponse
    {
        try {
            $store = Store::findOrFail($id);
            $store->update($request->validated());
            return $this->successResponse(new StoreResource($store), 'Toko berhasil diperbarui');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Toko dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal memperbarui toko: " . $e->getMessage());
            return $this->errorResponse('Gagal memperbarui toko', 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $store = Store::findOrFail($id);
            $store->delete();
            return $this->successResponse(null, 'Toko berhasil dihapus', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Toko dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal menghapus toko dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal menghapus toko', 500);
        }
    }

    public function getBusinessTypes()
    {
        // return response()->json(EnumService::getBusinessTypes());
        return response()->json(BusinessType::cases());
    }
}
