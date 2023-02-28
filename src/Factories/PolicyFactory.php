<?php

namespace Mediamouse\Users\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediamouse\Users\Models\Policy;

/**
 * @extends Factory<Policy>
 */
class PolicyFactory extends Factory
{
    protected $model = Policy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'policy' => fake()->unique()->colorName(),
            'name' => fake()->unique()->firstName,
        ];
    }
}
