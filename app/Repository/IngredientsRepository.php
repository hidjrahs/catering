<?php

namespace App\Repository;

use App\Http\Resources\IngredientsResource;
use App\Models\Ingredients;
use App\Models\IngredientsSuppliers;
use App\Models\Suppliers;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class  IngredientsRepository
{
    use IconComponent,FormatParse;
    public static function getallData($request)
    {
        $select=[
            'ingredients.id',
            'ingredients.name',
            'ingredients.unit',
            'ingredients.default_price',
            'ingredients.created_at',
            'ingredients.satuan',
            DB::raw('users.name cs_name'),
        ];
        $result=Ingredients::leftjoin('users','users.id','=','ingredients.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(ingredients.name) like '%".$lowerSearch."%'
                or lower(ingredients.unit) like '%".$lowerSearch."%'
                or lower(ingredients.default_price) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('orders')){
            $result->orderBy(request('orders'),request('order_option'));
            if(request('orders')!='ingredients.name'){
                $result->orderBy('ingredients.name','ASC');
            }
        }else{
            $result->orderBy('ingredients.created_at','DESC')->orderBy('ingredients.name','ASC');
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
                    ->editColumn('default_price', function ($data) {
                        return self::parseQuantity($data->default_price);
                    })
                    ->editColumn('unit', function ($data) {
                        return self::parseQuantity($data->unit);
                    })
                    ->rawColumns(['created_at'])
                    // ->removeColumn(['ref_berkas','sku','is_valid'])
                    ->addIndexColumn()
                    ->toJson();
    }

    public static function detail($refId,$request){
        $result=Ingredients::where(['id'=>$refId])
            ->with([
                'ref_supplier:id,ingredient_id,supplier_id',
                'ref_supplier.supplier:id,name,phone,penanggung_jawab'
            ])
            ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        $result=collect($result)
            ->put('default_price', (float) $result->default_price)
            ->put('ref_supplier',collect($result->ref_supplier)
                    ->map(function ($item) {
                        if(!$item)return null;
                        return [
                            'id' => $item->supplier->id,
                            'name' => $item->supplier->name,
                            'phone' => $item->supplier->phone,
                            'penanggung_jawab' => $item->supplier->penanggung_jawab,
                        ];
                    })
                    ->values()??null
                );
        return $result;
    }
    public static function search($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(ingredients.name) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $result = Ingredients::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->select(['id','name','unit','default_price','satuan'])
            ->limit(20)
            ->get();
        return IngredientsResource::collection($result);
    }
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $saveData['default_price']=self::quantity($saveData['default_price']);
        $saveData['unit']=self::quantity($saveData['unit']);
        $result=Ingredients::create($saveData);
        if(request('supplier_id')){
            $listSupplier=[];
            foreach(request('supplier_id') as $suppid){
                if(Str::isUlid($suppid)){
                    $cekSupp=Suppliers::where(['id'=>$suppid])->select(['id'])->first();
                    if($cekSupp){
                        $listSupplier[]=$suppid;
                    }
                }else{
                    $supplierSave=Suppliers::create(['name'=>$suppid]);
                    $listSupplier[]=$supplierSave->id;
                }
            }
            $resId=$result->id;
            $listSupplier = collect($listSupplier)->map(function ($supplierId) use ($resId) {
                return [
                    'id'=>Str::ulid(),
                    'ingredient_id' => $resId,
                    'supplier_id' => $supplierId,
                ];
            })->values()->toArray();
            IngredientsSuppliers::where(['ingredient_id'=>$result->id])->delete();
            IngredientsSuppliers::insert($listSupplier);
        }
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $saveData['default_price']=self::quantity($saveData['default_price']);
        $saveData['unit']=self::quantity($saveData['unit']);
        $result=Ingredients::where(['id'=>$refId])->update($saveData);
        if(request('supplier_id')){
            $listSupplier=[];
            foreach(request('supplier_id') as $suppid){
                if(Str::isUlid($suppid)){
                    $cekSupp=Suppliers::where(['id'=>$suppid])->select(['id'])->first();
                    if($cekSupp){
                        $listSupplier[]=$suppid;
                    }
                }else{
                    $supplierSave=Suppliers::create(['name'=>$suppid]);
                    $listSupplier[]=$supplierSave->id;
                }
            }
            $listSupplier = collect($listSupplier)->map(function ($supplierId) use ($refId) {
                return [
                    'id'=>Str::ulid(),
                    'ingredient_id' => $refId,
                    'supplier_id' => $supplierId,
                ];
            })->values()->toArray();
            IngredientsSuppliers::where(['ingredient_id'=>$refId])->delete();
            IngredientsSuppliers::insert($listSupplier);
        }
        return $result;
    }
    public static function delete($request){
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        $customerData=Ingredients::whereIn('id',$refId['data']);
        $customerData->update(['deleted_by'=>$userId]);
        $result=$customerData->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'name',
            'unit',
            'satuan',
            'default_price',
        ];
    }
}
