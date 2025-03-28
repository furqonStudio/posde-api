<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PaginationResource;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends BaseController
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

            $categories = Category::with('products')->paginate($perPage);

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
            $category = Category::with('products')->findOrFail($id);
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
