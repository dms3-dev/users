<?php

namespace Mediamouse\Users\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Models\LoginAttempt;
use Mediamouse\Users\Models\User;


class LoginAttemptFactory extends Factory
{
    protected $model = LoginAttempt::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => Carbon::now()->subSeconds(rand(0,86400)),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'status' => fake()->randomElement(LoginAttemptStatus::cases()),
            'ip' => fake()->ipv4,
            'token' => fake()->randomNumber(6)
        ];
    }
}
