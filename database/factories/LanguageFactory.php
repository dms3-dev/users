<?php

namespace Mediamouse\UsersDatabase\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediamouse\Users\Models\Language;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'iso' => fake()->randomLetter() . fake()->randomLetter(),
            'name' => fake()->name,

        ];
    }
}
