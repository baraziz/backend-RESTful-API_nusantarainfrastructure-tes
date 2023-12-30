<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'isbn' => fake()->randomNumber(6, true) . fake()->randomNumber(7, true),
            'title' => fake()->sentence(),
            'subtitle' => fake()->sentence(13),
            'author' => fake()->name(),
            'published' => fake()->date() . " " . fake()->time(),
            'publisher' => fake()->company(),
            'pages' => fake()->numberBetween(37, 800),
            'description' => fake()->paragraph(variableNbSentences: true),
            'website' => fake()->url()
        ];
    }
}
