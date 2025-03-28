<?php

namespace Database\Factories;

use App\Enums\BusinessType;
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
            'address' => $this->faker->address,
            'business_type' => collect(BusinessType::cases())
                ->random()->value,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
