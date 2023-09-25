<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $value = $this->faker->randomFloat(2, 0, 1_000);

        return [
            'value' => $value,
            'date' => $this->faker->dateTimeBetween('now', 'now'),
            'commission' => round($value * 8.5 / 100, 2),
            'seller_id' => Seller::factory(),
        ];
    }
}
