<?php

namespace Database\Factories;

use App\Models\RefVilage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customers>
 */
class CustomersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender=fake()->randomElement([1, 2]);
        return [
            'name' => fake()->name($gender==1?'male':'female'),
            'phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            'gender'=> $gender,
            'vilage_id'=> RefVilage::inRandomOrder()->value('id')
        ];
    }
}
