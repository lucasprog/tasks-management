<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TasksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->catchPhrase(),
            "description" => fake()->realText(150),
            "active" => true,
            "warn_me" => true,
            "starts_at" => fake()->dateTimeBetween('today','+1 days'),
            "user_id" => function() {
                return \App\Models\User::where('email','test@example.com')->first()->id;
            }
        ];
    }
}
