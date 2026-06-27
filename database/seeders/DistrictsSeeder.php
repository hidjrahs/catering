<?php

namespace Database\Seeders;

use App\Models\RefCity;
use App\Models\RefDistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList = [
            ["name" => "Badas"],
            ["name" => "Banyakan"],
            ["name" => "Gampengrejo"],
            ["name" => "Grogol"],
            ["name" => "Gurah"],
            ["name" => "Kandangan"],
            ["name" => "Kandat"],
            ["name" => "Kayen Kidul"],
            ["name" => "Kepung"],
            ["name" => "Kras"],
            ["name" => "Kunjang"],
            ["name" => "Mojo"],
            ["name" => "Ngadiluwih"],
            ["name" => "Ngancar"],
            ["name" => "Ngasem"],
            ["name" => "Pagu"],
            ["name" => "Papar"],
            ["name" => "Pare"],
            ["name" => "Plemahan"],
            ["name" => "Plosoklaten"],
            ["name" => "Puncu"],
            ["name" => "Purwoasri"],
            ["name" => "Ringinrejo"],
            ["name" => "Semen"],
            ["name" => "Tarokan"],
            ["name" => "Wates"],
        ];
        $getIDKabKediri=RefCity::where(['name'=>'Kabupaten Kediri'])->select(['id'])->first();
        if($getIDKabKediri){
            foreach($saveList as $save){
                $save['city_id']=$getIDKabKediri->id;
                RefDistrict::firstOrCreate(['name'=>$save['name']],$save);
            }
        }
    }
}
