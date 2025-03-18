<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Exception;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;

class ProductController extends BaseController
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

            $products = Product::with('category')->paginate($perPage);
            return $this->successResponse(
                new PaginationResource(ProductResource::collection($products)),
                'Daftar produk berhasil diambil'
            );
        } catch (Exception $e) {
            Log::error('Gagal mengambil produk: ' . $e->getMessage());
            return $this->errorResponse('Gagal mengambil produk', 500);
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = Product::create($request->validated());
            return $this->successResponse(new ProductResource($product), 'Produk berhasil ditambahkan', 201);
        } catch (Exception $e) {
            Log::error('Gagal menambahkan produk: ' . $e->getMessage());
            return $this->errorResponse('Gagal menambahkan produk', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $product = Product::with('category')->findOrFail($id);
            return $this->successResponse(new ProductResource($product), 'Detail produk berhasil diambil');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Produk dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal mengambil produk dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal mengambil produk', 500);
        }
    }

    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->validated());
            return $this->successResponse(new ProductResource($product), 'Produk berhasil diperbarui');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Produk dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal memperbarui produk: " . $e->getMessage());
            return $this->errorResponse('Gagal memperbarui produk', 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return $this->successResponse(null, 'Produk berhasil dihapus', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Produk dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal menghapus produk dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal menghapus produk', 500);
        }
    }
}
