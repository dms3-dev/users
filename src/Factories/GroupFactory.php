<?php

namespace Mediamouse\Users\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Exception;
use Mediamouse\Users\Models\Group;

/**
 * @extends Factory<Group>
 */
class GroupFactory extends Factory
{
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'key' => fake()->randomLetter() . fake()->randomNumber() . fake()->randomLetter(),
            'name' => fake()->unique()->word(),
        ];
    }
}
