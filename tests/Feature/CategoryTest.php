<?php

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_categories()
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at']
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

    public function test_can_get_a_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Detail kategori berhasil diambil',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'created_at' => $category->created_at->toDateTimeString(),
                'updated_at' => $category->updated_at->toDateTimeString(),
            ]
        ]);
    }

    public function test_returns_404_if_category_not_found()
    {
        $response = $this->getJson('/api/categories/999');

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Kategori dengan ID 999 tidak ditemukan'
        ]);
    }

    public function test_can_create_a_category()
    {
        $data = ['name' => 'New Category'];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(201)->assertJson([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => ['name' => 'New Category']
        ]);

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_requires_name_to_create_a_category()
    {
        $response = $this->postJson('/api/categories', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_name_must_be_at_least_3_characters()
    {
        $response = $this->postJson('/api/categories', ['name' => 'AB']);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_name_must_not_exceed_255_characters()
    {
        $response = $this->postJson('/api/categories', ['name' => str_repeat('A', 256)]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_name_must_be_unique()
    {
        Category::factory()->create(['name' => 'Existing Category']);

        $response = $this->postJson('/api/categories', ['name' => 'Existing Category']);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_can_update_a_category()
    {
        $category = Category::factory()->create();
        $data = ['name' => 'Updated Category'];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => ['name' => 'Updated Category']
        ]);

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_update_category_not_found()
    {
        $response = $this->putJson('/api/categories/999', ['name' => 'Updated']);

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Kategori dengan ID 999 tidak ditemukan'
        ]);
    }

    public function test_update_requires_name()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", []);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_update_name_must_be_at_least_3_characters()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", ['name' => 'AB']);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_update_name_must_not_exceed_255_characters()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", ['name' => str_repeat('A', 256)]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_update_name_must_be_unique()
    {
        Category::factory()->create(['name' => 'Existing Category']);
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", ['name' => 'Existing Category']);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_can_delete_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_delete_category_not_found()
    {
        $response = $this->deleteJson('/api/categories/999');

        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'Kategori dengan ID 999 tidak ditemukan'
        ]);
    }
}
