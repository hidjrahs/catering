<?php

namespace Database\Seeders;

use App\Models\RefDistrict;
use App\Models\RefVilage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VilagessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList = [
            ["name" => "Gurah"],
            ["name" => "Sukorejo"],
            ["name" => "Kerkep"],
            ["name" => "Ngasem"],
            ["name" => "Bangkok"],
            ["name" => "Turus"],
            ["name" => "Gabru"],
            ["name" => "Banyuanyar"],
            ["name" => "Wonojoyo"],
            ["name" => "Bogem"],
            ["name" => "Kranggan"],
            ["name" => "Tiru Kidul"],
            ["name" => "Gempolan"],
            ["name" => "Gayam"],
            ["name" => "Adan-Adan"],
            ["name" => "Tambak Rejo"],
            ["name" => "Blimbing"],
            ["name" => "Nglumbang"],
            ["name" => "Besuk"],
            ["name" => "Sumber Cangkring"],
            ["name" => "Tiru Lor"],
        ];
        $getIDKabKediri=RefDistrict::where(['name'=>'Gurah'])->select(['id'])->first();
        if($getIDKabKediri){
            foreach($saveList as $save){
                $save['district_id']=$getIDKabKediri->id;
                RefVilage::firstOrCreate(['name'=>$save['name']],$save);
            }
        }
    }
}
