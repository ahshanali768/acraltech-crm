<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'message' => fake()->paragraph(),
        ];
    }

    /**
     * Create a message between specific users.
     */
    public function between(User $sender, User $receiver): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
        ]);
    }

    /**
     * Create a short message.
     */
    public function short(): static
    {
        return $this->state(fn (array $attributes) => [
            'message' => fake()->sentence(),
        ]);
    }

    /**
     * Create a business-related message.
     */
    public function business(): static
    {
        $businessMessages = [
            'Lead follow-up: Customer interested in auto insurance quote.',
            'Campaign update: Medicare enrollment numbers are exceeding targets.',
            'Quality check: Please review the latest pest control leads.',
            'Commission update: Your earnings for this month have been processed.',
            'System alert: New high-priority leads available for assignment.'
        ];

        return $this->state(fn (array $attributes) => [
            'message' => fake()->randomElement($businessMessages),
        ]);
    }
}
