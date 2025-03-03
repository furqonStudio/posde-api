<?php

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => ['id', 'name', 'category' => ['id', 'name'], 'price', 'created_at', 'updated_at']
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

    public function test_can_get_a_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Detail produk berhasil diambil',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ],
                'price' => $product->price,
                'created_at' => $product->created_at->toDateTimeString(),
                'updated_at' => $product->updated_at->toDateTimeString(),
            ]
        ]);
    }

    public function test_returns_404_if_product_not_found()
    {
        $response = $this->getJson('/api/products/999');

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Produk dengan ID 999 tidak ditemukan'
        ]);
    }

    public function test_can_create_a_product()
    {
        $category = Category::factory()->create();
        $data = ['name' => 'New Product', 'category_id' => $category->id, 'price' => 1000, 'stock' => 3];
        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)->assertJson([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => ['name' => 'New Product']
        ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function test_requires_name_and_category_id_to_create_a_product()
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['name', 'category_id']);
    }

    public function test_can_update_a_product()
    {
        $product = Product::factory()->create();
        $data = ['name' => 'Updated Product', 'category_id' => $product->category_id, 'price' => 1500, 'stock' => 3];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => ['name' => 'Updated Product']
        ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function test_update_product_not_found()
    {
        $product = Product::factory()->create();
        $data = ['name' => 'Updated Product', 'category_id' => $product->category_id, 'price' => 1500, 'stock' => 3];

        $response = $this->putJson('/api/products/999', $data);

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Produk dengan ID 999 tidak ditemukan'
        ]);
    }

    public function test_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_delete_product_not_found()
    {
        $response = $this->deleteJson('/api/products/999');

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Produk dengan ID 999 tidak ditemukan'
        ]);
    }
}
