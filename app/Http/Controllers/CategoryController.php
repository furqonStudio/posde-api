<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CategoryRequest;
use Illuminate\Database\QueryException;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PaginationResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends BaseController
{
    public function index(): JsonResponse
    {
        try {
            $categories = Category::paginate(10);
            return $this->successResponse(
                new PaginationResource(CategoryResource::collection($categories)),
                'Daftar kategori berhasil diambil'
            );
        } catch (Exception $e) {
            Log::error('Gagal mengambil kategori: ' . $e->getMessage());
            return $this->errorResponse('Gagal mengambil kategori', 500);
        }
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());
            return $this->successResponse(new CategoryResource($category), 'Kategori berhasil ditambahkan', 201);
        } catch (Exception $e) {
            Log::error('Gagal menambahkan kategori: ' . $e->getMessage());
            return $this->errorResponse('Gagal menambahkan kategori', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            return $this->successResponse(new CategoryResource($category), 'Detail kategori berhasil diambil');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Kategori dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal mengambil kategori dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal mengambil kategori', 500);
        }
    }

    public function update(CategoryRequest $request,  $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            if ($category->name === $request->input('name')) {
                return $this->errorResponse('Tidak ada perubahan data', 422);
            }

            $category->update($request->validated());
            return $this->successResponse(new CategoryResource($category), 'Kategori berhasil diperbarui');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Kategori dengan ID ' . $id . ' tidak ditemukan', 404);
        } catch (Exception $e) {
            Log::error("Gagal memperbarui kategori: " . $e->getMessage());
            return $this->errorResponse('Gagal memperbarui kategori', 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

            $category->delete();
            return $this->successResponse(null, 'Kategori berhasil dihapus', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Kategori dengan ID ' . $id . ' tidak ditemukan', 404);
        } catch (Exception $e) {
            Log::error("Gagal menghapus kategori dengan ID {$id}: " . $e->getMessage());
            return $this->errorResponse('Gagal menghapus kategori', 500);
        }
    }
}
