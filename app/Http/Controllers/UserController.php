<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
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

            $users = User::paginate($perPage);

            return $this->successResponse(
                new PaginationResource(UserResource::collection($users)),
                'Daftar pengguna berhasil diambil'
            );
        } catch (Exception $e) {
            Log::error('Gagal mengambil pengguna: ' . $e->getMessage());
            return $this->errorResponse('Gagal mengambil pengguna', 500);
        }
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = bcrypt($validatedData['password']);

            $user = User::create($validatedData);

            return $this->successResponse(new UserResource($user), 'Pengguna berhasil ditambahkan', 201);
        } catch (Exception $e) {
            Log::error('Gagal menambahkan pengguna: ' . $e->getMessage());
            return $this->errorResponse('Gagal menambahkan pengguna', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            return $this->successResponse(new UserResource($user), 'Detail pengguna berhasil diambil');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Pengguna dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal mengambil pengguna dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal mengambil pengguna', 500);
        }
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validated();
            if (isset($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }

            $user->update($validatedData);

            return $this->successResponse(new UserResource($user), 'Pengguna berhasil diperbarui');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Pengguna dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal memperbarui pengguna: " . $e->getMessage());
            return $this->errorResponse('Gagal memperbarui pengguna', 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return $this->successResponse(null, 'Pengguna berhasil dihapus', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Pengguna dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal menghapus pengguna dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal menghapus pengguna', 500);
        }
    }
}
