<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price'=> $this->faker->randomFloat(2,10000,999999),
            'quantity'=> $this->faker->randomNumber(5, true),
            'category_id'=> $this->faker->randomNumber(1, true),
        ];
    }
}
