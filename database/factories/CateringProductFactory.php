<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CateringProduct>
 */
class CateringProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100, 1000),
            'image' => $this->faker->imageUrl(640, 480),
            'sku' => Str::random(5)
            ];
    }
}
