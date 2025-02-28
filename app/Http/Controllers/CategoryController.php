<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Exception;

class CategoryController extends BaseController
{
    public function index(): JsonResponse
    {
        try {
            $categories = Category::all();
            return $this->successResponse(CategoryResource::collection($categories), 'Daftar kategori berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal mengambil kategori', 500);
        }
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());
            return $this->successResponse(new CategoryResource($category), 'Kategori berhasil ditambahkan', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menambahkan kategori', 500);
        }
    }

    public function show(Category $category): JsonResponse
    {
        return $this->successResponse(new CategoryResource($category), 'Detail kategori berhasil diambil');
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());
            return $this->successResponse(new CategoryResource($category), 'Kategori berhasil diperbarui');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal memperbarui kategori', 500);
        }
    }

    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();
            return $this->successResponse(null, 'Kategori berhasil dihapus', 204);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus kategori', 500);
        }
    }
}
