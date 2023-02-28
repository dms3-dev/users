<?php

namespace Mediamouse\Users\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Models\Policy;
use Mediamouse\Users\Models\Privilege;

/**
 * @extends Factory<Privilege>
 */
class PrivilegeFactory extends Factory
{
    protected $model = Privilege::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'privilege' => fake()->randomElement(PolicyPrivilege::cases())->value,
        ];
    }
}
