<?php

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_orders()
    {
        Order::factory()->count(5)->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => ['id', 'status', 'total_price', 'created_at']
                ],
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                    'next_page_url',
                    'prev_page_url'
                ]
            ]
        ]);
    }

    public function test_can_get_a_single_order()
    {
        $order = Order::factory()->create();

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Detail pesanan berhasil diambil',
            'data' => [
                'id' => $order->id,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'created_at' => $order->created_at->toDateTimeString(),
            ]
        ]);
    }

    public function test_returns_404_if_order_not_found()
    {
        $response = $this->getJson('/api/orders/999');

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Pesanan dengan ID 999 tidak ditemukan'
        ]);
    }

    public function test_can_create_an_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 10000]);

        $data = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ];

        $response = $this->postJson('/api/orders', $data);

        $response->assertStatus(201)->assertJson([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
        ]);
    }

    public function test_requires_items_to_create_an_order()
    {
        $response = $this->postJson('/api/orders', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['items']);
    }

    public function test_requires_valid_product_id_in_items()
    {
        $response = $this->postJson('/api/orders', [
            'items' => [['product_id' => 999, 'quantity' => 1]]
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['items.0.product_id']);
    }

    public function test_quantity_must_be_at_least_one()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/orders', [
            'items' => [['product_id' => $product->id, 'quantity' => 0]]
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['items.0.quantity']);
    }

    public function test_can_update_an_order()
    {
        $order = Order::factory()->create();
        $data = ['status' => 'completed'];

        $response = $this->putJson("/api/orders/{$order->id}", $data);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Pesanan berhasil diperbarui',
            'data' => ['status' => 'completed']
        ]);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'completed']);
    }

    public function test_update_order_not_found()
    {
        $response = $this->putJson('/api/orders/999', ['status' => 'completed']);

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Pesanan dengan ID 999 tidak ditemukan'
        ]);
    }

    public function test_requires_status_to_update_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->putJson("/api/orders/{$order->id}", []);

        $response->assertStatus(422)->assertJsonValidationErrors(['status']);
    }

    public function test_can_delete_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus'
        ]);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_delete_order_not_found()
    {
        $response = $this->deleteJson('/api/orders/999');

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Pesanan dengan ID 999 tidak ditemukan'
        ]);
    }
}
