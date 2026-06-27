<?php

namespace App\Repository;

use App\Http\Resources\CustomerResource;
use App\Models\Customers;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\select;

class  CustomersRepository
{
    use IconComponent;
    public static function getallData($request)
    {
        $select=[
            'customers.id',
            'customers.name',
            'customers.gender',
            'customers.phone',
            'customers.address',
            'customers.location',
            'customers.created_at',
            'customers.vilage_id',
            DB::raw('users.name cs_name'),
        ];
        $result=Customers::with(['vilage:id,name,district_id',
                'vilage.district:id,name,city_id',
                'vilage.district.city:id,name,province_id',
                'vilage.district.city.province:id,name'])
            ->leftjoin('users','users.id','=','customers.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(customers.name) like '%".$lowerSearch."%'
                or lower(customers.phone) like '%".$lowerSearch."%'
                or lower(customers.address) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('orders')){
            $result->orderBy(request('orders'),request('order_option'));
            if(request('orders')!='customers.name'){
                $result->orderBy('customers.name','ASC');
            }
        }else{
            $result->orderBy('customers.created_at','DESC')->orderBy('customers.name','ASC');
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
                    ->editColumn('ref_wilayah', function ($data) {
                        if(!$data->vilage){
                            return '';
                        }
                        $vilage=$data->vilage->name;
                        $district=$data->vilage->district->name;
                        $city=$data->vilage->district->city->name;
                        return '<div class="d-flex align-items-center">
                            <div class="d-flex justify-content-start flex-column">
                                <span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'.$vilage.'/ Kec. '.$district.'</span>
                                <span class="text-muted fw-semibold text-muted d-block fs-7">'.$city.'</span>
                            </div>
                        </div>';
                        
                    })
                    ->editColumn('cs_name', function ($data) {
                        return $data->cs_name??'-';
                    })
                    ->rawColumns(['created_at','ref_wilayah'])
                    // ->removeColumn(['vilage'])
                    ->addIndexColumn()
                    ->toJson();
    }

    public static function detail($refId,$request){
        $select=['customers.id',
                'customers.name',
                'customers.phone',
                'customers.address',
                'customers.location',
                'customers.gender',
                'customers.created_at',
                'customers.vilage_id'];
        $result=Customers::where(['customers.id'=>$refId])
            ->with(['vilage:id,name,district_id',
                'vilage.district:id,name,city_id',
                'vilage.district.city:id,name,province_id',
                'vilage.district.city.province:id,name'])
            ->select($select)
            ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        if($result->vilage_id){
            $result->vilage_id=$result->vilage;
            $result->vilage_id->district_id=$result->vilage_id->district;
            $result->city_id=$result->vilage_id->district_id->city;
            unset($result->city_id->province_id);
            unset($result->vilage_id->district_id->city_id);
            unset($result->vilage_id->district_id->city);
            unset($result->vilage_id->district);
        }
        unset($result->vilage);
        return $result;
    }
    public static function search($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(customers.name) like '%".$lowerSearch."%'
                or lower(customers.phone) like '%".$lowerSearch."%'
                or lower(customers.address) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $result = Customers::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->with(['vilage:id,name,district_id',
                'vilage.district:id,name,city_id',
                'vilage.district.city:id,name,province_id',
                'vilage.district.city.province:id,name'])
            ->select(['customers.id','customers.name','customers.address','customers.phone','customers.vilage_id'])
            ->limit(20)
            ->get();
        return CustomerResource::collection($result);
    }
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $result=Customers::create($saveData);
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $result=Customers::where(['id'=>$refId])->update($saveData);
        return $result;
    }
    public static function delete($request){
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        $customerData=Customers::whereIn('id',$refId['data']);
        $customerData->update(['deleted_by'=>$userId]);
        $result=$customerData->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'name',
            'phone',
            'address',
            'gender',
            'location',
            'vilage_id'
        ];
    }
}
