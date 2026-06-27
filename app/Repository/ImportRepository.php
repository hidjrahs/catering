<?php

namespace App\Repository;

use App\Imports\RecipeMenuImport;
use App\Jobs\GenerateImportExcel;
use App\Jobs\GenerateRecipe;
use App\Models\CategoryMenusCatering;
use App\Models\ImportBerkas;
use App\Models\Ingredients;
use App\Models\MenusCatering;
use App\Models\MenusCateringIngredients;
use App\Models\PacketCatering;
use App\Models\PacketMenusCatering;
use App\Models\TempRecipeMenu;
use App\Traits\FormatParse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class  ImportRepository
{
    use FormatParse;
    public static function getallDataPreview($request)
    {
        $result=[];
        if(request('id_temp')){
            $select=[
                'temp_recipe_menu.id',
                'temp_recipe_menu.import_berkas_id',
                'temp_recipe_menu.recipe_name',
                'temp_recipe_menu.category',
                'temp_recipe_menu.paket',
                'temp_recipe_menu.portion_standard',
                DB::raw('menus_catering.id id_menu')
            ];
            $result=TempRecipeMenu::select($select)
                    ->with(['ingredients:id,temp_recipe_menu_id,ingredient_name,qty,satuan,unit,price_per_unit'])
                    ->leftJoin('menus_catering','menus_catering.name','=',DB::raw('temp_recipe_menu.recipe_name and menus_catering.deleted_at is null'))
                    ->where(['import_berkas_id'=>request('id_temp')]);
        }
        // echo $result->toSql();
        // dd(['aa']);
        return self::datatablePreview($result);
    }
    public static function getallData($request)
    {
        $result=ImportBerkas::select(['id','source',
                'filename',
                'path',
                'disk',
                'is_preview',
                'is_previewed',
                'is_process',
                'is_done',
                'desc',
                'created_at'])
            ->whereRaw('(is_done is null or is_done="0")')
            ->where(['source'=>'recipe_menu'])
            ->orderBy('import_berkas.created_at','DESC');
        // $user=Auth()->user();
        // $userId= $user->id;
        // $result->whereRaw("import_berkas.created_by='".$userId."'");
        return self::datatableWeb($result);
    }

    private static function datatableWeb($data){
        return DataTables::of($data)
                ->editColumn('created_at', function ($data) {
                    if(!$data->created_at){
                        return '';
                    }
                    $tgl=Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d/m/Y H:i:s');
                    $tgl=explode(' ',$tgl);
                    return '<span class="mb-1 fs-6">Tgl: '.$tgl[0].'</span>'.
                    '<span class="fw-semibold d-block fs-7">Jam: '.$tgl[1].'</span>';
                })
                ->addColumn('action', function ($data){
                    $res = '<a href="javascript:;" data-process="'.$data->id.'" class="btn btn-secondary btn-sm" title="Proses Preview Excel?"><i class="fas fa-file-alt "></i></a>';                    
                    if($data->is_preview=='1'){
                        $res='<a href="javascript:;" data-id="'.$data->id.'" class="btn btn-warning  btn-sm" title="Prosess Validasi."><span class="spinner-border spinner-border-sm"></span></a>';
                        if($data->is_previewed=='1'){
                            $res = '<a href="javascript:;" data-preview="'.$data->id.'" class="btn btn-light-success  btn-sm" data-append-title="'.$data->filename.'" title="Preview Excel."><i class="fas fa-search"></i></a>
                            <a href="javascript:;" data-delete="'.$data->id.'" class="btn btn-danger btn-sm" title="Hapus Excel?"><i class="fas fa-trash-alt "></i></a>';
                        }
                    }
                    return $res;
                })
                ->rawColumns(['created_at','action'])
                ->removeColumn([
                    'path',
                    'disk',
                    'is_preview',
                    'is_previewed',
                    'is_process',
                    'is_done',
                    'desc'])
                ->addIndexColumn()
                ->toJson();
    }
    private static function datatablePreview($data){
        return DataTables::of($data)
            ->editColumn('paket',function($data){
                return collect(explode(',', $data->paket))
                    ->map(fn($item) => '<span class="badge py-1 px-2 badge-light-success">'
                        . e(trim($item)) .
                    '</span>')
                    ->implode(' '); 
            })
            ->editColumn('id_menu',function($data){
                return $data->id_menu?'Exists':'New';
            })
            ->addColumn('total',function($data){
                $total = collect($data->ingredients)
                    ->sum(function ($item) {
                        $qty = (float) $item['qty'];
                        $unit = (float) $item['unit'];
                        $price = (float) $item['price_per_unit'];
                        return $unit > 0 ? ($qty / $unit) * $price : 0;
                    });
                return number_format($total, 0, ',', '.');
            })
            ->rawColumns(['paket'])
            ->addIndexColumn()
            ->toJson();
    }
    public static function checkBatch($refId,$request,$type){
        $result=ImportBerkas::select(['is_done'])->where(['id'=>$refId])->first();
        if(!$result){
            if($type=='recipe_menu'){
                $str='Recipe menu Temp tidak ditemukan';
            }
            throw new Exception($str, 1);
        }else{
            return ['is_done'=>(!$result->is_done)?false:true];
        }
        return false;
    }
    public static function storeBatch($request){
        $file = $request->file('file');
        $result=true;
        if(!$file){
            throw new Exception('Berkas Excel tidak di temukan', 1);
        }
        $disk="import_temp";
        $upload = Storage::disk($disk);
        if(!in_array(strtolower($file->getClientOriginalExtension()),['xls','xlsx'])){
            throw new Exception("File yang di gunakan harus berjenis Excel *.xls, *.xlsx", 1);
        }
        $filename=Carbon::now()->format('YmdHis').".".$file->getClientOriginalExtension();
        $source='recipe_menu';
        $pathname=$source.'/'.$filename;
        if (!$upload->put($pathname, File::get($file))) {
            throw new Exception("gagal upload", 1);
        }
        $saveBerkasTemp=[
            'source'=>$source,
            'filename'=>$file->getClientOriginalName(),
            'path'=>$filename,
            'disk'=>$disk,
            'is_preview'=>false
        ];
        $result=ImportBerkas::create($saveBerkasTemp);
         if(request('is_preview')){
            self::QueueJobCall($result,$source,$filename,$disk);
        }   
        return $result;
    }
    public static function QueueJobCall($saveData,$source,$filename,$disk){
        ImportBerkas::where(['id'=>$saveData->id])->update(['is_preview'=>true]);
        // $readExcel=self::readingExcelToTemp($saveData->id,$source,$filename,$disk);
        GenerateImportExcel::dispatch([
            'id'=>$saveData->id,
            'source'=>$source,
            'filename'=>$filename,
            'disk'=>$disk,
        ])->onQueue('import_temp');
        return true;
    }
    public static function QueueJobRecipe($idTemp,$user){
        ImportBerkas::where(['id'=>$idTemp])->update(['is_process'=>true]);
        GenerateRecipe::dispatch(['id'=>$idTemp,'user'=>$user])->onQueue('import_temp');
        return true;
    }
    public static function readingExcelToTemp($refId,$source,$filename,$disk){
        $storage = Storage::disk($disk);
        $cekpathName=$storage->exists($source.'/'.$filename);
        if(!$cekpathName){
            throw new Exception("File gagal ter upload", 1);
        }
        $pathName=$storage->path($source.'/'.$filename);
        $updateData=['is_previewed'=>true];
        $cekData=collect([]);
        if($source=='recipe_menu'){
            TempRecipeMenu::where(['import_berkas_id'=>$refId])->delete();
            $masterData=[];
            $readExcel=Excel::import(new RecipeMenuImport($refId,$masterData), $pathName);
            $cekData=TempRecipeMenu::select(['import_berkas_id'])->where(['import_berkas_id'=>$refId])->get();
        }
        if(!$cekData->count()){
            $updateData['desc']="Data Excel Kosong atau Format tidak sesuai [Pastikan Format sudah sesuai]";
        }
        // dd($cekData);
        $result=ImportBerkas::where(['id'=>$refId])->update($updateData);
        return $result;
    }
    public static function deleteimportTemp($refId,$request,$type='recipe_menu'){
        $berkas=ImportBerkas::where(['id'=>$refId])->first();
        if($berkas){
            $disk="import_temp";
            $upload = Storage::disk($disk);
            $filename=$berkas->source.'/'.$berkas->path;
            if ($upload->exists($filename)) {
                $upload->delete($filename);
            }
        }
        $result=true;
        ImportBerkas::where(['id'=>$refId])->delete();
        if($type=='rtlh'){
            $result=TempRecipeMenu::where(['import_berkas_id'=>$refId])->delete();
        }
        return $result;
    }
    public static function importBatch($request,$type=''){
        $requestData=$request->only(['idTemp']);
        $user=Auth()->user();
        if($type=='recipe_menu'){
            // return self::importRecipe($requestData['idTemp'],$user);
            return self::QueueJobRecipe($requestData['idTemp'],$user,$none_draft=request('none_draft'));
        }
    }
    public static function importRecipe($refId,$user){
        try{
            $select=[
                "temp_recipe_menu.id",
                "temp_recipe_menu.import_berkas_id",
                "temp_recipe_menu.recipe_name",
                "temp_recipe_menu.category",
                "temp_recipe_menu.paket",
                "temp_recipe_menu.portion_standard"
            ];
            $getData=TempRecipeMenu::select($select)
                ->with(['ingredients:id,temp_recipe_menu_id,ingredient_name,qty,satuan,unit,price_per_unit'])
                ->where(['import_berkas_id'=>$refId])
                ->get();
            if(!$getData->count()){
                throw new Exception("Data Temp Resep Menu tidak ditemukan.", 1);
            }
            $now=Carbon::now()->format('Y-m-d h:i:s'); 
            foreach($getData as $item){
                $menucatering=$item->only(['recipe_name','category','paket','portion_standard']);
                $cekMenu=MenusCatering::whereRaw("lower(name) like '".strtolower($menucatering['recipe_name'])."'")
                    ->select(['id'])
                    ->first();
                $cekCategoryMenu=CategoryMenusCatering::whereRaw("lower(name) like '%".strtolower($menucatering['category'])."%'")
                    ->select(['id'])
                    ->first();
                // dd($cekCategoryMenu);
                if($cekCategoryMenu){
                    $menucatering['category']=$cekCategoryMenu->id;
                }else{
                    $createCategory=CategoryMenusCatering::create(['name'=>$menucatering['category']]);
                    $menucatering['category']=$createCategory->id;
                }
                $total = collect($item->ingredients)
                    ->sum(function ($item) {
                        $qty = (float) $item['qty'];
                        $unit = (float) $item['unit'];
                        $price = (float) $item['price_per_unit'];
                        return $unit > 0 ? ($qty / $unit) * $price : 0;
                    });
                $menuId=false;
                if($cekMenu){
                    $updateMenuData=[
                        'desc'=>'-',
                        'selling_price'=>$total,
                        'category_menus_catering_id'=>$menucatering['category'],
                        'porsi_standard'=>$menucatering['portion_standard'],
                        'is_active'=>true,
                    ];
                    MenusCatering::where(['id'=>$cekMenu->id])->update($updateMenuData);
                    $menuId=$cekMenu->id;
                }else{
                    $saveMenuData=[
                        'name'=>$menucatering['recipe_name'],
                        'desc'=>'-',
                        'selling_price'=>$total,
                        'category_menus_catering_id'=>$menucatering['category'],
                        'porsi_standard'=>$menucatering['portion_standard'],
                        'is_active'=>true,
                    ];
                    $saveMenu=MenusCatering::create($saveMenuData);
                    $menuId=$saveMenu->id;
                }
                if($menuId){
                    $paket=explode(',',$menucatering['paket']);
                    $listPaket=[];
                    $cekIfnull=collect($paket)->filter()->isEmpty();
                    if(!$cekIfnull){
                        foreach($paket as $pkt){
                            $cekPaket=PacketCatering::whereRaw("lower(name) like '%".strtolower($pkt)."%'")
                                ->select(['id'])
                                ->first();
                            if(!$cekPaket){
                                $cekPaket=PacketCatering::create(['name'=>$pkt,'is_active'=>true]);
                            }
                            $listPaket[]=$cekPaket->id;
                        }
                    }else{
                        $listPaket=PacketCatering::select(['id'])->get()->pluck('id')->toArray();
                    }
                    // dd($listPaket,$cekIfnull);
                    $collectPaket = collect($listPaket)->map(fn($nkey) => [
                        'id'=>(string) Str::ulid(),
                        'menus_catering_id' => $menuId,
                        'packet_catering_id' => $nkey,
                    ])->values()->toArray();
                    // dd($collectPaket);
                    PacketMenusCatering::upsert($collectPaket, ['menus_catering_id','packet_catering_id'], ['menus_catering_id','packet_catering_id']);
                    PacketMenusCatering::where(['menus_catering_id'=>$menuId])->whereNotIn('packet_catering_id',$listPaket)->delete();
                    $ingredients=$item->ingredients;
                    MenusCateringIngredients::where(['menus_catering_id'=>$menuId])->delete();//forceDelete
                    foreach($ingredients as $ing){
                        $ing=$ing->only(["ingredient_name","qty","satuan","unit","price_per_unit"]);
                        $isAllEmptyExceptName = collect($ing)
                            ->except('ingredient_name')
                            ->every(fn($value) => empty($value));
                        if(!$isAllEmptyExceptName){
                            // $cekIngredient=Ingredients::where("lower(name) like '%".strtolower($ing['ingredient_name'])."%'")
                            $whereC=[];
                            // =[
                            //     'ingredients.unit'=>$ing['unit'],
                            //     'ingredients.default_price'=>$ing['price_per_unit'],
                            // ];
                            $cekIngredient=Ingredients::where($whereC)
                                // ->whereRaw("LOWER(ingredients.name) LIKE ?", ['%' . strtolower($ing['ingredient_name']) . '%'])
                                ->whereRaw("LOWER(ingredients.name) LIKE ?", [strtolower($ing['ingredient_name'])])
                                ->select(['ingredients.id'])
                                ->first();
                            if(!$cekIngredient){
                                $cekIngredient=Ingredients::create([
                                    'name'=>$ing['ingredient_name'],
                                    'unit'=>$ing['unit'],
                                    'default_price'=>$ing['price_per_unit'],
                                    'satuan'=>$ing['satuan']
                                ]);
                            }else{
                                Ingredients::where(['id'=>$cekIngredient->id])->update([
                                    'name'=>$ing['ingredient_name'],
                                    'unit'=>$ing['unit'],
                                    'default_price'=>$ing['price_per_unit'],
                                    'satuan'=>$ing['satuan']
                                ]);
                            }
                            // ingredient menu input
                            $saveIngredient=[
                                'menus_catering_id'=>$menuId,
                                'ingredient_id'=>$cekIngredient->id,
                                'quantity'=>str_replace(',', '.', $ing['qty'])
                            ];
                        }else{
                            $saveIngredient=[
                                'menus_catering_id'=>$menuId,
                                'ingredient_label'=>$ing['ingredient_name'],
                                'quantity'=>$ing['qty']
                            ];
                        }
                        MenusCateringIngredients::create($saveIngredient);
                    }
                    // dd($menuId,$ingredients);
                }
                // dd($menucatering,$cekMenu);
            }
            ImportBerkas::where(['id'=>$refId])->update(['is_done'=>true,'done_at'=>$now]);
            TempRecipeMenu::where(['import_berkas_id'=>$refId])->delete();
            return true;
        } catch (\Throwable $th) {
            log::error($th->getMessage());
            return false;
        }
    }
    
}
