<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'business_type' => $this->faker->randomElement(['Retail', 'Wholesale', 'Service']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
