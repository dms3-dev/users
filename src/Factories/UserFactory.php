<?php

namespace Mediamouse\Users\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Exception;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserStatus;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Models\Language;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Mediamouse\Users\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

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

            'email_verified_at' => Carbon::now(),
            'phone_verified_at' => Carbon::now(),
            'username' => fake()->company(),
            'name' => fake()->name(),
            'email' => fake()->email(),
            'language_iso' => fake()->randomElement(Language::query()->where('status', LanguageStatus::ACTIVE->value)->pluck('iso')),
            'role' => UserRole::SA->value,
            'two_factor' => fake()->randomElement(UserTwoFactor::cases())->value,
            'status' => fake()->randomElement(UserStatus::cases())->value,

        ];
    }
}
