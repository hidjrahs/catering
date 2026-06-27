<?php

namespace Database\Seeders;

use App\Models\CategoryMenusCatering;
use Illuminate\Database\Seeder;

class CategoryMenuCateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList=[
            ['name'=>'SUP / SOUP','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'CHICKEN / AYAM','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'FISH / IKAN','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'SEAFOOD','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'DAGING / BEEF','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'SAYURAN / VEGETABLE','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'NASI GORENG / FRIED RICE','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'MIE / NOODLES','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'DESSERT','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'ANEKA PUDING','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'BEVERAGE','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'KUE MINI','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'HAPPY MENU','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'MENU HEMAT','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'SAMBEL GORENG','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'ANEKA PASTA','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'STATION','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'MINI BUFFET','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'JAPANESE FOOD','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'MANDARIAN FOOD','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'ARABIAN FOOD','filename'=>'','path'=>'','disk'=>''],
            ['name'=>'INDONESIAN FOOD','filename'=>'','path'=>'','disk'=>''],
        ];
        foreach($saveList as $save){
            CategoryMenusCatering::firstOrCreate(['name'=>$save['name']],$save);
        }
    }
}
