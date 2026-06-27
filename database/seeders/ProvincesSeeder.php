<?php

namespace Database\Seeders;

use App\Models\RefProvince;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList = [
            ["name" => "Aceh"],
            ["name" => "Sumatera Utara"],
            ["name" => "Sumatera Barat"],
            ["name" => "Riau"],
            ["name" => "Kepulauan Riau"],
            ["name" => "Jambi"],
            ["name" => "Sumatera Selatan"],
            ["name" => "Bangka Belitung"],
            ["name" => "Bengkulu"],
            ["name" => "Lampung"],
            ["name" => "DKI Jakarta"],
            ["name" => "Jawa Barat"],
            ["name" => "Jawa Tengah"],
            ["name" => "DI Yogyakarta"],
            ["name" => "Jawa Timur"],
            ["name" => "Banten"],
            ["name" => "Bali"],
            ["name" => "Nusa Tenggara Barat"],
            ["name" => "Nusa Tenggara Timur"],
            ["name" => "Kalimantan Barat"],
            ["name" => "Kalimantan Tengah"],
            ["name" => "Kalimantan Selatan"],
            ["name" => "Kalimantan Timur"],
            ["name" => "Kalimantan Utara"],
            ["name" => "Sulawesi Utara"],
            ["name" => "Gorontalo"],
            ["name" => "Sulawesi Tengah"],
            ["name" => "Sulawesi Barat"],
            ["name" => "Sulawesi Selatan"],
            ["name" => "Sulawesi Tenggara"],
            ["name" => "Maluku"],
            ["name" => "Maluku Utara"],
            ["name" => "Papua"],
            ["name" => "Papua Tengah"],
            ["name" => "Papua Pegunungan"],
            ["name" => "Papua Selatan"],
            ["name" => "Papua Barat"],
            ["name" => "Papua Barat Daya"],
        ];

        foreach($saveList as $save){
            RefProvince::firstOrCreate(['name'=>$save['name']],$save);
        }
    }
}
