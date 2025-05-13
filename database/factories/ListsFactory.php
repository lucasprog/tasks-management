<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ListsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item' => fake()->catchPhrase(),
            'done' => false,
            'tasks_id' => function() {
                return \App\Models\Tasks::factory()->create()->id;
            }
        ];
    }
}
