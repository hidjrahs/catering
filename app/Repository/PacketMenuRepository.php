<?php

namespace App\Repository;

use App\Models\PacketCatering;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class  PacketMenuRepository
{
    use IconComponent,FormatParse;
    public static function getAllCategory(){
        $result=PacketCatering::select(['packet_catering.id','packet_catering.name'])
            // ->active()
            ->get()
            ->toArray();
        // dd(['asasd']);
        return $result;
    }
    public static function getallData($request)
    {
        $select=[
            'packet_catering.id',
            'packet_catering.name',
            'packet_catering.created_at',
            'packet_catering.is_active',
            DB::raw('users.name cs_name'),
        ];
        $result=PacketCatering::leftjoin('users','users.id','=','packet_catering.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(packet_catering.name) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('orders')){
            $result->orderBy(request('orders'),request('order_option'));
            if(request('orders')!='packet_catering.name'){
                $result->orderBy('packet_catering.name','ASC');
            }
        }else{
            $result->orderBy('packet_catering.created_at','DESC')->orderBy('packet_catering.name','ASC');
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
                    ->addColumn('pathfile', function ($data) {
                        return $data->pathfile??false;
                    })
                    ->rawColumns(['created_at'])
                    // ->removeColumn(['ref_berkas','sku','is_valid'])
                    ->addIndexColumn()
                    ->toJson();
    }

    public static function detail($refId,$request){
        $result=PacketCatering::where(['id'=>$refId])
            ->select(['id','name','is_active'])
            ->first();
        if(!$result){
            throw new Exception('Data Paket Menu tidak ditemukan.', 404);
        }
        return $result;
    }
    public static function search_all($request){
        $result = PacketCatering::query()
            ->active()
            ->select(['id','name'])
            ->get();
        return $result;
    }
    public static function search($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(packet_catering.name) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $result = PacketCatering::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->active()
            ->select(['id','name'])
            ->limit(20)
            ->get();
        return $result;
    }
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        if(!request('is_active')){
            $saveData['is_active']=false;
        }
        // dd($saveData);
        $result=PacketCatering::create($saveData);
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        if(!request('is_active')){
            $saveData['is_active']=false;
        }
        $result=PacketCatering::where(['id'=>$refId])->update($saveData);
        return $result;
    }
    public static function delete($request){
        $refId=$request->only('data');
        // dd(['sss']);
        $userId = Auth::check() ? Auth::id() : 1;
        $data=PacketCatering::whereIn('id',$refId['data']);
        $data->update(['deleted_by'=>$userId]);
        $result=$data->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'name',
            'is_active'
        ];
    }
}
