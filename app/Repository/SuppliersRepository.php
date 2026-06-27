<?php

namespace App\Repository;

use App\Models\IngredientsSuppliers;
use App\Models\Suppliers;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class  SuppliersRepository
{
    use IconComponent;
    public static function getallData($request)
    {
        $select=[
            'suppliers.id',
            'suppliers.name',
            'suppliers.phone',
            'suppliers.address',
            'suppliers.desc',
            'suppliers.penanggung_jawab',
            'suppliers.created_at',
            DB::raw('users.name cs_name'),
        ];
        $result=Suppliers::leftjoin('users','users.id','=','suppliers.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(suppliers.name) like '%".$lowerSearch."%'
                or lower(suppliers.phone) like '%".$lowerSearch."%'
                or lower(suppliers.penanggung_jawab) like '%".$lowerSearch."%'
                or lower(suppliers.desc) like '%".$lowerSearch."%'
                or lower(suppliers.address) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('orders')){
            $result->orderBy(request('orders'),request('order_option'));
            if(request('orders')!='suppliers.name'){
                $result->orderBy('suppliers.name','ASC');
            }
        }else{
            $result->orderBy('suppliers.created_at','DESC')->orderBy('suppliers.name','ASC');
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
                    ->rawColumns(['created_at'])
                    // ->removeColumn(['ref_berkas','sku','is_valid'])
                    ->addIndexColumn()
                    ->toJson();
    }

    public static function detail($refId,$request){
        $result=Suppliers::where(['id'=>$refId])->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        return $result;
    }
    public static function search($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(suppliers.name) like '%".$lowerSearch."%'
                or lower(suppliers.phone) like '%".$lowerSearch."%'
                or lower(suppliers.penanggung_jawab) like '%".$lowerSearch."%'
                or lower(suppliers.desc) like '%".$lowerSearch."%'
                or lower(suppliers.address) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $ref=false;
        $supp=[];
        if(request('refid')){
            $supplierList=IngredientsSuppliers::where(['ingredient_id'=>request('refid')])->select(['supplier_id'])->get();
            if($supplierList->count()>0){
                $ref=true;
                $supp=$supplierList->pluck('supplier_id');
            }
        }
        $result = Suppliers::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->when($ref, fn($query) => $query->whereIn('id',$supp))
            ->select(['id','name','penanggung_jawab','phone'])
            ->limit(20)
            ->get();
        return $result;
    }
    public static function store($request,$directpush=false){
        $listSave=self::listSave();
        if(!$directpush){
            $saveData=$request->only($listSave);
        }else{
            $saveData=$request;
        }
        $result=Suppliers::create($saveData);
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $result=Suppliers::where(['id'=>$refId])->update($saveData);
        return $result;
    }
    public static function delete($request){
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        $customerData=Suppliers::whereIn('id',$refId['data']);
        $customerData->update(['deleted_by'=>$userId]);
        $result=$customerData->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'name',
            'phone',
            'address',
            'penanggung_jawab',
            'desc',
        ];
    }
}
