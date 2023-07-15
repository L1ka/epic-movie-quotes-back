<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => json_encode([
                'en' => fake()->sentence(10),
                'ka' => fake('ka_GE')->realText(10, true)
            ]),
            'director' =>  json_encode([
                'en' => fake()->sentence(10),
                'ka' => fake('ka_GE')->realText(10, true)
            ]),
           'discription' => json_encode([
                'en' => fake()->sentence(30),
                'ka' => fake('ka_GE')->realText(30, true)
            ]),
            'image' => fake()->imageUrl(200, 200, 'animals', true),
            'year' => fake()->year(),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
