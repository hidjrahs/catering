<?php

namespace Database\Seeders;

use App\Models\PacketCatering;
use Illuminate\Database\Seeder;

class PacketCateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList=[
            ['name'=>'Paket Standard','is_active'=>true],
            ['name'=>'Paket Reguler','is_active'=>true],
            ['name'=>'Paket Spesial','is_active'=>true],
            ['name'=>'Paket Premium','is_active'=>true],
            ['name'=>'Paket Platinum','is_active'=>true],
            ['name'=>'Paket Emerald','is_active'=>true],
        ];
        foreach($saveList as $save){
            PacketCatering::firstOrCreate(['name'=>$save['name']],$save);
        }
    }
}
