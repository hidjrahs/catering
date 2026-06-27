<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customers>
 */
class EmployesFactory extends Factory
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
            'gender'=> $gender
        ];
    }
}
