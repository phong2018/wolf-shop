<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word, // Generates a random word for the item name
            'sell_in' => $this->faker->numberBetween(1, 30), // Generates a random sell_in value between 1 and 30
            'quality' => $this->faker->numberBetween(0, 100), // Generates a random quality value between 0 and 100
            'img_url' => $this->faker->imageUrl(), // Generates a random image URL
            'img_url_public_id' => $this->faker->uuid, // Generates a random UUID for the image public ID
            'data' => json_encode($this->faker->randomElements(['foo' => 'bar', 'baz' => 'qux'], 2)), // Generates random JSON data
        ];
    }
}
