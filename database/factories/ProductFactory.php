<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'color' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Yellow']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'fuel_type' => $this->faker->randomElement(['Petrol', 'Diesel', 'Electric']),
            'engine_type' => $this->faker->randomElement(['V6', 'V8', 'I4']),
        ];
    }
}
