<?php

namespace App\Imports;

use App\Models\TempIngredientsMenu;
use App\Models\TempRecipeMenu;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RecipeMenuImport implements ToCollection,WithChunkReading,WithHeadingRow
{
    use RemembersRowNumber;
    protected $refId;
    protected $masterData;
    public function  __construct($refId,$masterData=null)
    {
        $this->refId= $refId;
        $this->masterData= $masterData;
    }
    public function collection(Collection $rows)
    {
        try {
            $currentRecipe = null;
            $emptyCount = 0;
            $maxEmpty=5;
            foreach ($rows as $row) {
                if ($row->filter()->isEmpty()) {
                    $emptyCount++;
                    if ($emptyCount > $maxEmpty) break; 
                    continue;
                }
                if (!empty($row['recipe_name'])&&!empty($row['portion_standard'])) {
                    $currentRecipe = TempRecipeMenu::create([
                            'import_berkas_id'=>$this->refId,
                            'recipe_name' => $row['recipe_name'],
                            'category' => $row['category'],
                            'paket' => $row['paket'],
                            'portion_standard' => $row['portion_standard'] ?? 0,
                    ]);
                }
                if ($currentRecipe && !empty($row['ingredient_name'])) {
                    // print($row).'</br>';
                    // dd($currentRecipe);
                    TempIngredientsMenu::create([
                        'temp_recipe_menu_id' => $currentRecipe->id,
                        'ingredient_name' => $row['ingredient_name'],
                        'qty' => $row['qty'] ?? null,
                        'satuan' => $row['satuan'] ?? null,
                        'unit' => $row['unit'] ?? null,
                        'price_per_unit' => $row['price_per_unit'] ?? null
                    ]);
                }
            }
            // dd(['ss']);
            return true;
        } catch (\Throwable $th) {
            // dd($th);
            Log::error($th);
            return false;
        }
        
    }
    // public function model(Array $row)
    // {
        // dd($row);
        // 'import_berkas_id',
        // 'recipe_name',
        // 'category',
        // 'paket',
        // 'portion_standard'
        // 'temp_recipe_menu_id',
        // 'ingredient_name',
        // 'qty',
        // 'satuan',
        // 'unit',             
        // 'price_per_unit'
        
        // // dd($saveData->toArray());
        // return new TempRTLH($saveData->toArray());
    // }

    public function chunkSize(): int
    {
        return 100;
    }
    public function headingRow(): int
    {
        return 1;
    }
}
