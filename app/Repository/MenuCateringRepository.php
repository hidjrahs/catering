<?php

namespace App\Repository;

use App\Http\Resources\MenuCateringDetailResource;
use App\Http\Resources\MenuCateringSelectResource;
use App\Models\CategoryMenusCatering;
use App\Models\ImportBerkas;
use App\Models\MenusCatering;
use App\Models\MenusCateringIngredients;
use App\Models\PacketMenusCatering;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;

class  MenuCateringRepository
{
    use IconComponent,FormatParse;
    public static function getallData($request)
    {
        $select=[
            'menus_catering.id',
            'menus_catering.name',
            'menus_catering.created_at',
            'menus_catering.selling_price',
            'menus_catering.porsi_standard',
            DB::raw('users.name cs_name, category_menus_catering.name category_menu'),
        ];
        $result=MenusCatering::with(['refImage'])
            ->leftJoin('category_menus_catering','category_menus_catering.id','=','menus_catering.category_menus_catering_id')
            ->leftjoin('users','users.id','=','category_menus_catering.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(menus_catering.name) like '%".$lowerSearch."%'
                    or lower(menus_catering.selling_price) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('orders')){
            $result->orderBy(request('orders'),request('order_option'));
            if(request('orders')!='menus_catering.name'){
                $result->orderBy('menus_catering.name','ASC');
            }
        }else{
            $result->orderBy('menus_catering.created_at','DESC')
                    ->orderBy('category_menus_catering.name','ASC')
                    ->orderBy('menus_catering.name','ASC');
        }
        if(in_array(request('device'),['web','stealth'])){
            return self::datatableWeb($result);
        }
        return self::datatableMobile($result);
    }

    private static function datatableMobile($data){
        return DataTables::of($data)
            ->editColumn('created_at', function ($data) {
                $tgl=Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d/m/Y H:i:s');
                return $tgl;
            })
            ->addIndexColumn()
            ->toJson();
    }

    private static function datatableWeb($data){
        $user=Auth()->user();
        return DataTables::of($data)
                    ->editColumn('created_at', function ($data) {
                        if(!$data->created_at){
                            return '';
                        }
                        $tgl=Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d/m/Y H:i:s');
                        return '<span class="text-muted fw-semibold text-muted d-block fs-7">'.$tgl.'</span>';
                    })
                    ->editColumn('cs_name', function ($data) {
                        return $data->cs_name??'-';
                    })
                    ->editColumn('selling_price', function ($data) {
                        return $data->selling_price?self::parseQuantity($data->selling_price):'-';
                    })
                    ->editColumn('porsi_standard', function ($data) {
                        return $data->porsi_standard?self::parseQuantity($data->porsi_standard):'-';
                    })
                    ->rawColumns(['created_at'])
                    // ->removeColumn(['ref_berkas','sku','is_valid'])
                    ->addIndexColumn()
                    ->toJson();
    }

    public static function detail($refId,$request){
        $select=['menus_catering.id',
            'menus_catering.name',
            'menus_catering.desc',
            'menus_catering.selling_price',
            'menus_catering.category_menus_catering_id',
            'menus_catering.porsi_standard',
            'menus_catering.is_active'
        ];
        $result=MenusCatering::where(['id'=>$refId])
            ->select($select)
            ->with([
                'category:id,name',
                'packet:id,packet_catering_id,menus_catering_id',
                'packet.packet_name:id,name',
                'menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
                'menuingredients.ingredient:id,name,unit'
            ])
            ->first();
        if(!$result){
            throw new Exception('Data Menu Catering tidak ditemukan.', 404);
        }
        // dd($result);
        return new MenuCateringDetailResource($result);
    }
    public static function select($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(menus_catering.name) like '%".$lowerSearch."%'
                or lower(category_menus_catering.name) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $paket=request('packetid');
        $select=[
            DB::raw('distinct category_menus_catering.name category_name'),
            'menus_catering.id',
            'menus_catering.name',
            'menus_catering.porsi_standard',
            'menus_catering.selling_price'
        ];
        $result = MenusCatering::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->select($select)
            ->active()
            ->leftJoin('category_menus_catering','category_menus_catering.id','=','menus_catering.category_menus_catering_id')
            ->leftJoin('packet_menus_catering','packet_menus_catering.menus_catering_id','=','menus_catering.id')
            ->when($paket, fn($query) => $query->whereIn('packet_menus_catering.packet_catering_id',explode(',',$paket)))
            ->limit(20)
            ->get();
        return MenuCateringSelectResource::collection($result);
    }
    public static function search($request){
        $category=request('category');
        $search=request('search');
        $paket=request('paket');
        if($category==='all'){
             $category=null;
        }
        $page = request('page')??1; // halaman yang diinginkan
        $perPage = 21;
        $where="(lower(menus_catering.name) like '%".$search."%')";
        $result=MenusCatering::select([
            DB::raw('distinct category_menus_catering.name as category_menu'),
            'category_menus_catering.is_quantity',
            'menus_catering.id',
            'menus_catering.name',
            'menus_catering.selling_price',
            'menus_catering.porsi_standard'])
        ->when($category, fn($query) => $query->where(['category_menus_catering.id'=>$category]))
        ->when($search, fn($query) => $query->whereRaw($where))
        ->where(['is_active'=>True])
        ->leftJoin('category_menus_catering','category_menus_catering.id','=','menus_catering.category_menus_catering_id')
        ->leftJoin('packet_menus_catering','packet_menus_catering.menus_catering_id','=','menus_catering.id')
        ->when($paket, fn($query) => $query->whereIn('packet_menus_catering.packet_catering_id',explode(',',$paket)))
        ->orderBy('menus_catering.name')
        ->skip(($page - 1) * $perPage)
        ->take($perPage)
        ->get();
        $result=Collect($result)->transform(function($res){
            $res->label=self::makeLabelFromName($res->name);
            $res->icon=self::getCategoryIcon($res->category_menu);
            // $res->selling_price=self::parseQuantity($res->selling_price);
            $res->porsi_standard=self::parseQuantity($res->porsi_standard);
            return $res;
        })->toArray();
        return $result;
    }
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        // $file = $request->file('file_berkas');
        // dd('x',$saveData,$request->all());
        // item
        // $disk="category_menus";
        // if($file){
        //     if(!in_array(strtolower($file->getClientOriginalExtension()),['jpeg','png','jpg','webp'])){
        //         throw new Exception("format image/foto harus berjenis *.webp, *.jpg, *.jpeg, *.png", 1);
        //     }
        //     if(!in_array(strtolower($file->getClientMimeType()),['image/jpeg','image/png','image/jpg','image/webp'])&&!in_array(request('device'),['mobile'])){
        //         throw new Exception("format mimeType: ".$file->getClientMimeType().". Format harus image/foto jenis *.webp, *.jpg, *.jpeg, *.png", 1);
        //     }
        //     $filename=Carbon::now()->format('YmdHis').".".$file->getClientOriginalExtension();
        //     $saveData['filename']=$file->getClientOriginalName();
        //     $saveData['path']=$filename;
        //     $saveData['disk']=$disk;
        // }
        $saveData['porsi_standard']=self::quantity($saveData['porsi_standard']);
        $saveData['selling_price']=(int) str_replace('.', '', $saveData['selling_price']);
        $saveData['is_active']=request('is_active')??False;
        $result=MenusCatering::create($saveData);
        $itemMenu=$request->only('item');
        if(!in_array('item',array_keys($itemMenu))){
            throw new Exception('Barang/Bahan Baku masih kosong', 1);
        }
        $packetMenu=$request->only('packet_catering_id');
        if(!in_array('packet_catering_id',array_keys($packetMenu))){
            throw new Exception('Paket Menu belum di pilih', 1);
        }
        $savePacket = collect($packetMenu['packet_catering_id'])
            ->map(fn($pid) => [
                'id'=>(string) Str::ulid(), 
                'menus_catering_id' => $result->id,
                'packet_catering_id' => $pid,
            ])
            ->values()
            ->toArray();
        PacketMenusCatering::insert($savePacket);
        foreach($itemMenu['item'] as $item){
            // $item['type']=='label';
            $saveItem=[
                'menus_catering_id'=>$result->id,
                'ingredient_id'=>$item['type']!='label'?$item['ingredient_id']:null,
                'ingredient_label'=>$item['type']=='label'?$item['ingredient_id']:null,
                'quantity'=>$item['type']=='label'?null:$item['quantity']
            ];
            MenusCateringIngredients::create($saveItem);
        }
        return $result;
        // if($file){
        //     $upload = Storage::disk($disk);
        //     $filepath=Image::read($file)
        //             ->scaleDown(800)
        //             ->encodeByExtension($file->getClientOriginalExtension(),["quality"=> 60]);
        //     $pathname=$result['id']."/".$filename;
        //     if (!$upload->put($pathname, $filepath)) {
        //         throw new Exception("gagal upload", 1);
        //     }
        // }
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        // $file = $request->file('file_berkas');
        // dd('x',$saveData,$request->all());
        // item
        // $disk="category_menus";
        // if($file){
        //     if(!in_array(strtolower($file->getClientOriginalExtension()),['jpeg','png','jpg','webp'])){
        //         throw new Exception("format image/foto harus berjenis *.webp, *.jpg, *.jpeg, *.png", 1);
        //     }
        //     if(!in_array(strtolower($file->getClientMimeType()),['image/jpeg','image/png','image/jpg','image/webp'])&&!in_array(request('device'),['mobile'])){
        //         throw new Exception("format mimeType: ".$file->getClientMimeType().". Format harus image/foto jenis *.webp, *.jpg, *.jpeg, *.png", 1);
        //     }
        //     $filename=Carbon::now()->format('YmdHis').".".$file->getClientOriginalExtension();
        //     $saveData['filename']=$file->getClientOriginalName();
        //     $saveData['path']=$filename;
        //     $saveData['disk']=$disk;
        // }
        $saveData['porsi_standard']=self::quantity($saveData['porsi_standard']);
        $saveData['selling_price']=self::quantity($saveData['selling_price']);
        // dd($saveData);
        $saveData['is_active']=request('is_active')??False;
        $result=MenusCatering::where(['id'=>$refId])->update($saveData);
        $packetMenu=$request->only('packet_catering_id');
        if(!in_array('packet_catering_id',array_keys($packetMenu))){
            throw new Exception('Paket Menu belum di pilih', 1);
        }
        if($packetMenu['packet_catering_id']){
            PacketMenusCatering::where(['menus_catering_id'=>$refId])->delete();
            $savePacket = collect($packetMenu['packet_catering_id'])
                ->map(fn($pid) => [
                    'id'=>(string) Str::ulid(), 
                    'menus_catering_id' => $refId,
                    'packet_catering_id' => $pid,
                ])
                ->values()
                ->toArray();
            PacketMenusCatering::insert($savePacket);
        }
        $itemMenu=$request->only('item');
        if(!in_array('item',array_keys($itemMenu))){
            MenusCateringIngredients::where(['menus_catering_id'=>$refId])->forceDelete();
            return true;
        }
        $listExistItem=array_keys($itemMenu['item']);
        $cekUlid= collect($listExistItem)->filter(function ($id) {
            return preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $id);
        })->values()->toArray(); 
        MenusCateringIngredients::whereNotIn('id',$cekUlid)
                ->where(['menus_catering_id'=>$refId])
                ->forceDelete();
        foreach($itemMenu['item'] as $key=>$item){
            $saveItem=[
                'menus_catering_id'=>$refId,
                'ingredient_id'=>$item['type']!='label'?$item['ingredient_id']:null,
                'ingredient_label'=>$item['type']=='label'?$item['ingredient_id']:null,
                'quantity'=>$item['type']=='label'?null:self::quantityFloat($item['quantity'])
            ];
            MenusCateringIngredients::updateOrCreate(['id'=>$key],$saveItem);
        }
        return $result;
        // if($file){
        //     $upload = Storage::disk($disk);
        //     $filepath=Image::read($file)
        //             ->scaleDown(800)
        //             ->encodeByExtension($file->getClientOriginalExtension(),["quality"=> 60]);
        //     $pathname=$result['id']."/".$filename;
        //     if (!$upload->put($pathname, $filepath)) {
        //         throw new Exception("gagal upload", 1);
        //     }
        // }
        return $result;
    }
    private static function clearImageOld($refId,$upload){
        $cekImageOld=CategoryMenusCatering::where(['id'=>$refId])->select(['id','path'])->first();
        if($cekImageOld){
            $direktoryOld=$refId;
            if ($upload->exists($direktoryOld)) {
                $upload->deleteDirectory($direktoryOld);
            }
        }
        return $cekImageOld;
    }
    public static function delete($request){
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        $customerData=MenusCatering::whereIn('id',$refId['data']);
        $customerData->update(['deleted_by'=>$userId]);
        $result=$customerData->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'name',
            'desc',
            'selling_price',
            'category_menus_catering_id',
            'porsi_standard'
        ];
    }
}
