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
        return[
            'value' => $this->faker->randomFloat(2, 0, 1_000_000),
            'date' => $this->faker->dateTimeBetween('now', 'now'),
            'seller_id' => Seller::factory(),
        ];
    }
}
