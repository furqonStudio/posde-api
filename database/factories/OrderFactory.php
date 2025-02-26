<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'total_price' => 0, // Akan dihitung dari order items
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}
