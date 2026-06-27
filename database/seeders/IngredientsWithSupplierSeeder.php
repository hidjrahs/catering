<?php

namespace Database\Seeders;

use App\Models\Ingredients;
use App\Models\IngredientsSuppliers;
use App\Models\Suppliers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Excel as Exc;
use Illuminate\Support\Str;

class IngredientsWithSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/public/BAHAN SUPPLIER.xls');
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();
        $rows = Excel::toCollection(null, $filePath, null, Exc::XLS)->get( array_search('bahan_supplier', $sheetNames) );
        $startIndex = $rows->search(fn ($row) => isset($row[0]) && str_contains(strtoupper($row[0]), 'NAMA BARANG'));
        if ($startIndex === false) return;
        $data = $rows->slice($startIndex + 1)->filter(fn ($r) => !empty($r[1]));
        // dd($data);
        foreach ($data as $r) {
            $bahan = trim($r[0] ?? '');
            if (empty($bahan)) continue;
            $saveIngred=['name'=>$bahan];
            $Ingredients = Ingredients::firstOrCreate(['name'=>$saveIngred['name']],$saveIngred);
            $listSupplier=[];
            $supp1 =trim($r[1] ?? '');
            $nosupp1 =trim($r[2] ?? '');
            $supp2 =trim($r[3] ?? '');
            $nosupp2 =trim($r[4] ?? '');
            if($supp1){
                $cekSupp=Suppliers::whereRaw("LOWER(suppliers.name) LIKE ?", ['%' . strtolower($supp1) . '%'])->select(['id'])->first();
                if($cekSupp){
                    $listSupplier[]=$cekSupp->id;
                }else{
                    $supplierSave=Suppliers::create(['name'=>$supp1,'phone'=>$nosupp1]);
                    $listSupplier[]=$supplierSave->id;
                }
            }
            if($supp2){
                $cekSupp2=Suppliers::whereRaw("LOWER(suppliers.name) LIKE ?", ['%' . strtolower($supp2) . '%'])->select(['id'])->first();
                if($cekSupp2){
                    $listSupplier[]=$cekSupp2->id;
                }else{
                    $supplierSave=Suppliers::create(['name'=>$supp2,'phone'=>$nosupp2]);
                    $listSupplier[]=$supplierSave->id;
                }
            }
            $resId=$Ingredients->id;
            $listSupplier = collect($listSupplier)->map(function ($supplierId) use ($resId) {
                return [
                    'id'=>Str::ulid(),
                    'ingredient_id' => $resId,
                    'supplier_id' => $supplierId,
                ];
            })->values()->toArray();
            IngredientsSuppliers::where(['ingredient_id'=>$resId])->delete();
            IngredientsSuppliers::insert($listSupplier);
            // dd($supp1,$nosupp1,$supp2,$nosupp2);
        }
    }
}
