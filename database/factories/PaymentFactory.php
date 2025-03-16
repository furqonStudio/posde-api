<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(50, 1000);
        $method = fake()->randomElement(['cash', 'cashless']);


        $paidAmount = $method === 'cash'
            ? fake()->numberBetween($amount, $amount + 500)
            : $amount;

        $change = $method === 'cash' ? max(0, $paidAmount - $amount) : 0;

        return [
            'order_id' => Order::factory(),
            'amount' => $amount,
            'paid_amount' => $paidAmount,
            'change' => $change,
            'status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'method' => $method,
        ];
    }
}
