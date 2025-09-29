<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['upcoming', 'ongoing', 'completed'];
        
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('now', '+2 months'),
            'time' => fake()->time('H:i'),
            'location' => fake()->address(),
            'status' => fake()->randomElement($statuses),
        ];
    }
} 