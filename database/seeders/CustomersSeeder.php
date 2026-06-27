<?php

namespace Database\Seeders;

use App\Models\Customers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kediri = \App\Models\RefCity::where('name', 'Kabupaten Kediri')->first();
        
        if ($kediri) {
            $kediriVilages = \App\Models\RefVilage::whereHas('district', function ($query) use ($kediri) {
                $query->where('city_id', $kediri->id);
            })->pluck('id')->toArray();

            if (!empty($kediriVilages)) {
                Customers::factory()->count(20)->state(function (array $attributes) use ($kediriVilages) {
                    return [
                        'vilage_id' => fake()->randomElement($kediriVilages),
                    ];
                })->create();
            } else {
                $this->command->warn('Belum ada data desa di Kabupaten Kediri.');
            }
        } else {
            $this->command->warn('Kabupaten Kediri tidak ditemukan di tabel ref_city.');
        }
    }
}
