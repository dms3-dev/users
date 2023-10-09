<?php

namespace Mediamouse\Users\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediamouse\Users\Enums\LoginAttemptStatus;


class LoginAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'status' => fake()->randomElement(LoginAttemptStatus::cases()),
            'ip' => fake()->ipv4,
            'token' => fake()->randomNumber(6)
        ];
    }
}
