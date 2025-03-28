<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Store::factory(3)->create()->each(function ($store) {
            // Buat 1-2 user untuk setiap store
            User::factory(rand(1, 2))->create([
                'store_id' => $store->id,
            ]);
        });
        Category::factory(5)->create(); // Buat 5 kategori
        Product::factory(20)->create(); // Buat 20 produk
        Order::factory(10)->create()->each(function ($order) {
            // Setiap order akan punya 1-3 item
            OrderItem::factory(rand(1, 3))->create(['order_id' => $order->id]);

            // Load ulang order items untuk memastikan relasi terbaca
            $order->load('orderItems');

            // Hitung total price order
            $order->update([
                'total_price' => $order->orderItems->sum('subtotal'),
            ]);

            // Buat pembayaran jika status order bukan cancelled
            if ($order->status !== 'cancelled') {
                Payment::factory()->create([
                    'order_id' => $order->id,
                    'amount' => $order->total_price,
                ]);
            }
        });
    }
}
