<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\LeadSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<model-property<Customer>, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'person_in_charge_id' => User::query()->inRandomOrder()->value('id'),
            'lead_source_id' => LeadSource::query()->inRandomOrder()->value('id'),
        ];
    }
}
