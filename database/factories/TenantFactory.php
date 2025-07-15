<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        $slug = Str::slug($name) . '-' . $this->faker->randomNumber(3);

        return [
            'name' => $name,
            'slug' => $slug,
            'subdomain' => $slug,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'plan' => $this->faker->randomElement(['basic', 'premium', 'enterprise']),
            'max_users' => $this->faker->numberBetween(5, 100),
            'storage_limit' => $this->faker->numberBetween(1073741824, 10737418240), // 1GB to 10GB
            'trial_ends_at' => $this->faker->optional()->dateTimeBetween('now', '+30 days'),
            'subscription_ends_at' => $this->faker->optional()->dateTimeBetween('+30 days', '+365 days'),
            'contact_info' => [
                'email' => $this->faker->companyEmail(),
                'phone' => $this->faker->phoneNumber(),
                'address' => $this->faker->address(),
            ],
        ];
    }
}
