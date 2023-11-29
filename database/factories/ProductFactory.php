<?php

namespace Database\Factories;

use App\Models\Category;
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
    public function definition()
    {
        return [
            'name' => $this->faker->domainName(),
            'quantity' => $this->faker->numberBetween(0, 1),
            'price' => $this->faker->numberBetween(100000, 999999),
            'category_id' => Category::get()->random()->id,
        ];
    }
}
