<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => fake()->sentence(15),
            'price' => fake()->numberBetween(2500, 12000),
            'discount_percent' => fake()->randomElement([0, 5, 10, 15, 20]),
            'rating' => fake()->randomFloat(1, 3.5, 5.0),
            'sizes' => ['S', 'M', 'L', 'XL'],
            'is_active' => true,
            'image' => 'images/products/default.jpg',
        ];
    }
}
