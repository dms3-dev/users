<?php

namespace Mediamouse\Users\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Exception;
use Mediamouse\Users\Models\Language;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    protected $model = Language::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        $max_diff = rand(0, 86400 * 1825);
        $created_at = time() - $max_diff;
        $updated_at = $created_at + (rand(0,3) == 1 ? rand(0, $max_diff) : 0);

        if($updated_at < $created_at) throw new Exception("Updated at is smaller then created at");
        if($updated_at > time()) throw new Exception("updated_at is larger then the current time");

        return [
            'created_at' => $created_at,
            'updated_at' => $updated_at,

            'iso' => fake()->unique()->languageCode(),
            'name' => fake()->languageCode() . '_name',
            'sort' => fake()->randomNumber(),
        ];
    }
}
