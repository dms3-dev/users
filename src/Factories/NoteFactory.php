<?php

namespace Mediamouse\Users\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediamouse\Users\Enums\NoteStatus;
use Mediamouse\Users\Enums\NoteType;
use Mediamouse\Users\Models\Note;

class NoteFactory extends Factory
{
    protected $model = Note::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(NoteType::cases())->value,
            'milestone_at' => fake()->dateTime,
            'status' => fake()->randomElement(NoteStatus::cases())->value,
            'content' => fake()->text,
        ];
    }
}
