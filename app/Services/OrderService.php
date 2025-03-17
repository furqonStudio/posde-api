<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $totalPrice = 0;

            $order = Order::create([
                'total_price' => 0,
                'status' => 'pending'
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Stock untuk produk {$product->name} tidak mencukupi.");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $order->update(['total_price' => $totalPrice]);

            if (!isset($data['payment']) || !isset($data['payment']['method'])) {
                throw new Exception("Metode pembayaran diperlukan.");
            }

            $method = $data['payment']['method'];
            $paidAmount = $data['payment']['paid_amount'] ?? 0;
            $change = 0;
            $status = 'pending';

            if ($method === 'cash') {
                $paidAmount = $data['payment']['paid_amount'] ?? $totalPrice;
                $change = max($paidAmount - $totalPrice, 0);
                $status = 'paid';

                $order->update(['status' => 'completed']);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $method,
                'amount' => $totalPrice,
                'paid_amount' => $paidAmount,
                'change' => $change,
                'status' => $status,
            ]);

            return $order->load('orderItems.product', 'payment');
        });
    }
}
