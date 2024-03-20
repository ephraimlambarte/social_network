<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_sender_id' => User::factory(),
            'user_receiver_id' => User::factory(),
            'message' => $this->faker->unique()->text(10),
            'read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
