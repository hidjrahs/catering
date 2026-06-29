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
        if (!file_exists($filePath)) {
            $this->generateDummyData();
            return;
        }
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

    private function generateDummyData(): void
    {
        $saveList = [
            ['Beras Putih', ['Supplier Beras 1', '081234567890'], ['Supplier Beras 2', '082134567890']],
            ['Gula Pasir', ['Supplier Gula', '081987654321'], []],
            ['Minyak Goreng', ['Supplier Minyak', '085612345678'], ['Minyak Global', '087812345678']],
            ['Telur Ayam', ['Supplier Telur A', '081345678901'], ['Supplier Telur B', '082345678901']],
            ['Ayam Broiler', ['Supplier Ayam 1', '081456789012'], []],
            ['Daging Sapi', ['Dewa Dewi Malang', '082131781975'], []],
            ['Udang Segar', ['Seafood Pak To', '082124802241'], []],
            ['Wortel', ['Bu Erna Sayur', '081357632047'], []],
            ['Bawang Merah', ['Supplier Bawang', '081298765432'], []],
            ['Kemangi', ['Pak No', '081359388499'], []],
        ];
        foreach ($saveList as $item) {
            $ingredient = Ingredients::firstOrCreate(['name' => $item[0]], [
                'name' => $item[0],
                'unit' => 1000,
                'default_price' => rand(10000, 80000),
                'satuan' => 'gram',
            ]);
            $listSupplier = [];
            if (!empty($item[1][0])) {
                $supp1 = Suppliers::firstOrCreate(['name' => $item[1][0]], [
                    'name' => $item[1][0],
                    'phone' => $item[1][1],
                ]);
                $listSupplier[] = $supp1->id;
            }
            if (!empty($item[2][0])) {
                $supp2 = Suppliers::firstOrCreate(['name' => $item[2][0]], [
                    'name' => $item[2][0],
                    'phone' => $item[2][1],
                ]);
                $listSupplier[] = $supp2->id;
            }
            $resId = $ingredient->id;
            $listSupplier = collect($listSupplier)->map(function ($supplierId) use ($resId) {
                return [
                    'id' => Str::ulid(),
                    'ingredient_id' => $resId,
                    'supplier_id' => $supplierId,
                ];
            })->values()->toArray();
            IngredientsSuppliers::where(['ingredient_id' => $resId])->delete();
            IngredientsSuppliers::insert($listSupplier);
        }
    }
}
