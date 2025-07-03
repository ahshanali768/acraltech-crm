<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $campaignTypes = [
            'Auto Insurance Premium',
            'Home Insurance Coverage',
            'Medicare Advantage',
            'Obamacare Enrollment',
            'U65 Health Insurance',
            'Final Expense Insurance',
            'SSDI Disability',
            'Credit Repair Service',
            'Debt Settlement',
            'Tax Debt Relief',
            'Student Loan Forgiveness',
            'Pest Control Service',
            'Home Warranty Protection',
            'Flight Booking Service'
        ];

        return [
            'campaign_name' => fake()->randomElement($campaignTypes) . ' - ' . fake()->words(2, true),
            'commission_inr' => fake()->numberBetween(500, 5000),
            'did' => fake()->numerify('###-###-####'),
            'payout_usd' => fake()->numberBetween(50, 500),
            'status' => fake()->randomElement(['active', 'paused', 'completed', 'draft']),
        ];
    }

    /**
     * Indicate that the campaign is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the campaign is paused.
     */
    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paused',
        ]);
    }

    /**
     * Create a high-value campaign.
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'commission_inr' => fake()->numberBetween(3000, 8000),
            'payout_usd' => fake()->numberBetween(300, 800),
        ]);
    }

    /**
     * Create campaign for specific service type.
     */
    public function forService(string $service): static
    {
        $serviceNames = [
            'insurance' => [
                'Auto Insurance Premium Lead Generation',
                'Home Insurance Coverage Campaign',
                'Medicare Advantage Enrollment',
                'Final Expense Life Insurance'
            ],
            'financial' => [
                'Credit Repair Service Campaign',
                'Debt Settlement Program',
                'Tax Debt Relief Service',
                'Student Loan Assistance'
            ],
            'home' => [
                'Pest Control Service Campaign',
                'Home Warranty Protection Plan',
                'Flight Booking Service'
            ]
        ];

        $names = $serviceNames[$service] ?? $serviceNames['insurance'];
        
        return $this->state(fn (array $attributes) => [
            'campaign_name' => fake()->randomElement($names),
        ]);
    }
}
