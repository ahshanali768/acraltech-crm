<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();        return [
            'user_id' => User::factory(),
            'campaign_id' => Campaign::factory(),
            'name' => $firstName . ' ' . $lastName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => fake()->phoneNumber(),
            'did_number' => fake()->numerify('###-###-####'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip' => fake()->postcode(),
            'email' => fake()->unique()->safeEmail(),
            'agent_name' => fake()->name(),
            'verifier_name' => fake()->name(),
            'contact_info' => fake()->email() . ' | ' . fake()->phoneNumber(),
            'notes' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'contacted', 'qualified']),
        ];
    }

    /**
     * Indicate that the lead is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the lead is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the lead is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Create a lead for a specific campaign type.
     */
    public function forCampaignType(string $type): static
    {
        $campaigns = [
            'auto_insurance' => 'Auto Insurance Premium Lead',
            'home_insurance' => 'Home Insurance Coverage',
            'medicare' => 'Medicare Advantage Plan',
            'credit_repair' => 'Credit Score Improvement',
            'debt_settlement' => 'Debt Relief Program',
            'pest_control' => 'Pest Control Service',
        ];

        return $this->state(fn (array $attributes) => [
            'campaign' => $campaigns[$type] ?? fake()->words(3, true),
        ]);
    }
}
