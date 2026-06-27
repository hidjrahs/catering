<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenusSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            EmployesSeeder::class,
            EmployesEducationSeeder::class,
            EmployesContractSeeder::class,
            IngredientsWithSupplierSeeder::class,
            SuppliersSeeder::class,
            IngredientsSeeder::class,
            PacketCateringSeeder::class,
            CategoryMenuCateringSeeder::class,
            MenuCateringSeeder::class,
            ProvincesSeeder::class,
            CitiesSeeder::class,
            DistrictsSeeder::class,
            VilagessSeeder::class,
            JawaTimurWilayahSeeder::class, // Seeder custom wilayah Jatim dari Laravolt
            CustomersSeeder::class,
            CostStructureSeeder::class,
        ]);
    }
}
