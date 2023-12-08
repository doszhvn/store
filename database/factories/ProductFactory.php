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
            'description' => $this->faker->text(),
            'image' => 'https://via.placeholder.com/640x480.png/00bbaa?text=product',
            'quantity' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->numberBetween(100000, 999999),
            'category_id' => Category::get()->random()->id,
        ];
    }
}
