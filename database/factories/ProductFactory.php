<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'image' => 'https://picsum.photos/480/480?random=' . $this->faker->unique()->numberBetween(1, 1000),
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(10000, 500000),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
