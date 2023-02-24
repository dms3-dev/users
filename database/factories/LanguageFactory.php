<?php

namespace Mediamouse\UsersDatabase\Factories;

use App\Models\Project;
use Carbon\Carbon;
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
            'iso' => fake()->randomLetter(2),
            'name' => fake()->name,

        ];
    }
}
