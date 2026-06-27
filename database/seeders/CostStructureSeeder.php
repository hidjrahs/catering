<?php

namespace Database\Seeders;

use App\Models\CostStructure;
use App\Models\CostStructureDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList=[
            [
                'name'=>'Cost Template Default',
                'is_active'=>true,
                'desc'=>null,
                'detail'=>[
                    [
                        'kategori'=>'Food Cost Extra',
                        'fixed'=>true,
                        'prosentase'=>null,
                        'name'=>'-',
                    ],
                    [
                        'name'=>'Dapur',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'0',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'Office',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'0',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LPG',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'0',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LISTRIK',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'0',
                        'fixed'=>false,
                    ]
                ]
            ],
            [
                'name'=>'Cost Template Sabendino',
                'is_active'=>true,
                'desc'=>null,
                'detail'=>[
                    [
                        'kategori'=>'Food Cost Extra',
                        'fixed'=>true,
                        'prosentase'=>null,
                        'name'=>'-',
                    ],
                    [
                        'name'=>'Dapur',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'1',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'Office',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'1.5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LPG',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'2',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LISTRIK',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'0.5',
                        'fixed'=>false,
                    ]
                ]
            ],
            [
                'name'=>'Cost Template Ramadhan',
                'is_active'=>true,
                'desc'=>null,
                'detail'=>[
                    [
                        'kategori'=>'Food Cost Extra',
                        'fixed'=>true,
                        'prosentase'=>null,
                        'name'=>'-',
                    ],
                    [
                        'name'=>'Lapangan ',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'8',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'Dapur',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'6',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'Office',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LPG',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'3.5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LISTRIK',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'1',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'RUMAH TANGGA PERUSAHAAN',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'1',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'ANGGARAN PROMOSI',
                        'kategori'=>'Extra',
                        'prosentase'=>'5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'ARMADA',
                        'kategori'=>'Extra',
                        'prosentase'=>'4',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LOHJINAWI',
                        'kategori'=>'Extra',
                        'prosentase'=>'12',
                        'fixed'=>false,
                    ],
                ]
            ],
            [
                'name'=>'Cost Template Bukber',
                'is_active'=>true,
                'desc'=>null,
                'detail'=>[
                    [
                        'kategori'=>'Food Cost Extra',
                        'fixed'=>true,
                        'prosentase'=>null,
                        'name'=>'-',
                    ],
                    [
                        'name'=>'Lapangan ',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'8',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'Dapur',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'8',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'Office',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'2.5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LPG',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'3.5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LISTRIK',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'1',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'RUMAH TANGGA PERUSAHAAN',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'1',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'ANGGARAN PROMOSI',
                        'kategori'=>'Extra',
                        'prosentase'=>'3',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'ARMADA',
                        'kategori'=>'Extra',
                        'prosentase'=>'4',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LOHJINAWI',
                        'kategori'=>'Extra',
                        'prosentase'=>'12',
                        'fixed'=>false,
                    ],
                ]
            ],
            [
                'name'=>'Cost Template Hampers',
                'is_active'=>true,
                'desc'=>null,
                'detail'=>[
                    [
                        'kategori'=>'Food Cost Extra',
                        'fixed'=>true,
                        'prosentase'=>null,
                        'name'=>'-',
                    ],
                    [
                        'name'=>'Dapur',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'8',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'Office',
                        'kategori'=>'TENAGA KERJA',
                        'prosentase'=>'2.5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LPG',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'3.5',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'LISTRIK',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'1',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'RUMAH TANGGA PERUSAHAAN',
                        'kategori'=>'BAHAN BAKAR',
                        'prosentase'=>'1',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'ANGGARAN PROMOSI',
                        'kategori'=>'Extra',
                        'prosentase'=>'3',
                        'fixed'=>false,
                    ],
                    [
                        'name'=>'ARMADA',
                        'kategori'=>'Extra',
                        'prosentase'=>'4',
                        'fixed'=>false,
                    ]
                ]
            ],
        ];
        foreach($saveList as $save){
            $saveCS=COllect($save)->only(['name','is_active','desc'])->toArray();
            $ref=CostStructure::firstOrCreate(['name'=>$save['name']],$saveCS);
            $collectionStructured = collect($save['detail'])->map(fn($i) => $i + ['cost_structure_id' => $ref->id]);
            $listName=$collectionStructured->pluck('name')->toArray();
            CostStructureDetail::where(['cost_structure_id'=>$ref->id])->whereNotIn('name',$listName)->delete();
            CostStructureDetail::upsert($collectionStructured->toArray(),['cost_structure_id','name'],['kategori', 'prosentase']);
        }
    }
}
