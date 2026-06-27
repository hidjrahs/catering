<?php

namespace Database\Seeders;

use App\Models\RefCity;
use App\Models\RefProvince;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList = [
            // Kabupaten
            ["name" => "Kabupaten Bangkalan"],
            ["name" => "Kabupaten Banyuwangi"],
            ["name" => "Kabupaten Blitar"],
            ["name" => "Kabupaten Bojonegoro"],
            ["name" => "Kabupaten Bondowoso"],
            ["name" => "Kabupaten Gresik"],
            ["name" => "Kabupaten Jember"],
            ["name" => "Kabupaten Jombang"],
            ["name" => "Kabupaten Kediri"],
            ["name" => "Kabupaten Lamongan"],
            ["name" => "Kabupaten Lumajang"],
            ["name" => "Kabupaten Madiun"],
            ["name" => "Kabupaten Magetan"],
            ["name" => "Kabupaten Malang"],
            ["name" => "Kabupaten Mojokerto"],
            ["name" => "Kabupaten Nganjuk"],
            ["name" => "Kabupaten Ngawi"],
            ["name" => "Kabupaten Pacitan"],
            ["name" => "Kabupaten Pamekasan"],
            ["name" => "Kabupaten Pasuruan"],
            ["name" => "Kabupaten Ponorogo"],
            ["name" => "Kabupaten Probolinggo"],
            ["name" => "Kabupaten Sampang"],
            ["name" => "Kabupaten Sidoarjo"],
            ["name" => "Kabupaten Situbondo"],
            ["name" => "Kabupaten Sumenep"],
            ["name" => "Kabupaten Trenggalek"],
            ["name" => "Kabupaten Tuban"],
            ["name" => "Kabupaten Tulungagung"],

            // Kota
            ["name" => "Kota Batu"],
            ["name" => "Kota Blitar"],
            ["name" => "Kota Kediri"],
            ["name" => "Kota Madiun"],
            ["name" => "Kota Malang"],
            ["name" => "Kota Mojokerto"],
            ["name" => "Kota Pasuruan"],
            ["name" => "Kota Probolinggo"],
            ["name" => "Kota Surabaya"],
        ];
        $getIDJatim=RefProvince::where(['name'=>'Jawa Timur'])->select(['id'])->first();
        if($getIDJatim){
            foreach($saveList as $save){
                $save['province_id']=$getIDJatim->id;
                RefCity::firstOrCreate(['name'=>$save['name']],$save);
            }
        }
    }
}
