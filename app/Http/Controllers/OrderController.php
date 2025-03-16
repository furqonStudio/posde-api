<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaginationResource;
use App\Services\OrderService;
use Exception;

class OrderController extends BaseController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        try {
            $orders = Order::with('orderItems.product')->paginate(10);
            return $this->successResponse(
                new PaginationResource(OrderResource::collection($orders)),
                'Daftar pesanan berhasil diambil'
            );
        } catch (Exception $e) {
            Log::error('Gagal mengambil pesanan: ' . $e->getMessage());
            return $this->errorResponse('Gagal mengambil pesanan', 500);
        }
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder($request->validated());
            return $this->successResponse(new OrderResource($order->load('orderItems.product')), 'Pesanan berhasil dibuat', 201);
        } catch (Exception $e) {
            Log::error('Gagal membuat pesanan: ' . $e->getMessage());
            return $this->errorResponse('Gagal membuat pesanan', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $order = Order::with('orderItems.product')->with('payment')->findOrFail($id);
            return $this->successResponse(new OrderResource($order), 'Detail pesanan berhasil diambil');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Pesanan dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal mengambil pesanan dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal mengambil pesanan', 500);
        }
    }

    public function update(UpdateOrderRequest $request, $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);

            $validatedData = $request->validated();

            $order->update($validatedData);

            return $this->successResponse(new OrderResource($order), 'Pesanan berhasil diperbarui');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Pesanan dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal memperbarui pesanan: " . $e->getMessage());
            return $this->errorResponse('Gagal memperbarui pesanan', 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return $this->successResponse(null, 'Pesanan berhasil dihapus', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse("Pesanan dengan ID $id tidak ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Gagal menghapus pesanan dengan ID $id: " . $e->getMessage());
            return $this->errorResponse('Gagal menghapus pesanan', 500);
        }
    }
}
