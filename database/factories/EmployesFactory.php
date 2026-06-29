<?php

namespace Database\Factories;

use App\Models\Employes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employes>
 */
class EmployesFactory extends Factory
{
    protected $model = Employes::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['L', 'P']);
        $birthDate = fake()->dateTimeBetween('-45 years', '-18 years');
        $location = fake()->randomElement(['office', 'kitchen', 'gudang']);
        $division = fake()->randomElement(['Chef', 'Kasir', 'Gudang', 'Office', 'Waiter', 'Delivery', 'Quality Control']);
        $status = fake()->randomElement(['Kawin', 'Belum Kawin', 'Cerai', 'Janda', 'Duda']);
        $religion = fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);

        return [
            'name' => fake()->name($gender === 'L' ? 'male' : 'female'),
            'phone' => fake()->unique()->numerify('08##########'),
            'address' => fake()->address(),
            'location' => $location,
            'gender' => $gender,
            'national_id' => fake()->numerify('################'),
            'status' => $status,
            'work_since' => fake()->numberBetween(2015, 2025),
            'division' => $division,
            'birth_place_date' => fake()->city().' / '.$birthDate->format('d-m-Y'),
            'height_cm' => fake()->numberBetween(150, 185),
            'weight_kg' => fake()->numberBetween(50, 85),
            'religion' => $religion,
            'user_id' => null,
        ];
    }
}
