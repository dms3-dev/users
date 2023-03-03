<?php

namespace Mediamouse\UsersDatabase\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediamouse\Users\Enums\LanguageStatus;
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
        $iso = fake()->unique()->languageCode();
        return [
            'iso' => $iso,
            'name' => $iso . 'name',
            'status' => fake()->randomElement(LanguageStatus::cases()),
        ];
    }
}
