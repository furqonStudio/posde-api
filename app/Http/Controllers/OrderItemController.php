<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return response()->json(OrderItem::with('product', 'order')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Stock not enough'], 400);
        }

        $subtotal = $product->price * $request->quantity;

        $orderItem = OrderItem::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'subtotal' => $subtotal,
        ]);

        // Kurangi stok produk
        $product->decrement('stock', $request->quantity);

        return response()->json($orderItem, 201);
    }

    public function show(OrderItem $orderItem)
    {
        return response()->json($orderItem->load('product', 'order'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = Product::findOrFail($orderItem->product_id);

        // Mengembalikan stok lama sebelum mengurangi stok baru
        $product->increment('stock', $orderItem->quantity);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Stock not enough'], 400);
        }

        $subtotal = $product->price * $request->quantity;

        $orderItem->update([
            'quantity' => $request->quantity,
            'subtotal' => $subtotal,
        ]);

        // Kurangi stok sesuai jumlah baru
        $product->decrement('stock', $request->quantity);

        return response()->json($orderItem);
    }

    public function destroy(OrderItem $orderItem)
    {
        // Mengembalikan stok saat item dihapus
        $product = Product::findOrFail($orderItem->product_id);
        $product->increment('stock', $orderItem->quantity);

        $orderItem->delete();
        return response()->json(null, 204);
    }
}
