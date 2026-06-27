<?php

namespace App\Repository;

use App\Models\Customers;
use App\Models\RefCity;
use App\Models\RefDistrict;
use App\Models\RefProvince;
use App\Models\RefVilage;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class  RefWIlayahRepository
{
    use IconComponent;
    public static function getallData($request)
    {
        $type=request('type')??'provinces';
        if($type=='provinces'){
            $select=[
                'province.id',
                'province.name',
                DB::raw('users.name cs_name'),
            ];
            $result=RefProvince::leftjoin('users','users.id','=','province.created_by');
            $result->select($select);
            if(request('search')){
                $lowerSearch=strtolower(request('search'));
                $where="(lower(province.name) like '%".$lowerSearch."%')";
                $result->whereRaw($where);
            }
            if(request('orders')){
                $result->orderBy(request('orders'),request('order_option'));
                if(request('orders')!='province.name'){
                    $result->orderBy('province.name','ASC');
                }
            }else{
                $result->orderBy('province.created_at','DESC')->orderBy('province.name','ASC');
            }
        }
        if($type=='cities'){
            $select=[
                'city.id',
                'city.name',
                DB::raw('users.name cs_name,
                    province.name provinci_name'),
            ];
            $result=RefCity::leftjoin('users','users.id','=','city.created_by')
                    ->leftJoin('province','province.id','=','city.province_id');
            $result->select($select);
            if(request('search')){
                $lowerSearch=strtolower(request('search'));
                $where="(lower(city.name) like '%".$lowerSearch."%')";
                $result->whereRaw($where);
            }
            if(request('orders')){
                $result->orderBy(request('orders'),request('order_option'));
                if(request('orders')!='city.name'){
                    $result->orderBy('city.name','ASC');
                }
            }else{
                $result->orderBy('city.created_at','DESC')->orderBy('city.name','ASC');
            }
        }
        if($type=='districts'){
            $select=[
                'district.id',
                'district.name',
                DB::raw('users.name cs_name,
                    city.name city_name,
                    province.name provinci_name'),
            ];
            $result=RefDistrict::leftjoin('users','users.id','=','district.created_by')
                    ->leftJoin('city','city.id','=','district.city_id')
                    ->leftJoin('province','province.id','=','city.province_id');
            $result->select($select);
            if(request('search')){
                $lowerSearch=strtolower(request('search'));
                $where="(lower(district.name) like '%".$lowerSearch."%')";
                $result->whereRaw($where);
            }
            if(request('orders')){
                $result->orderBy(request('orders'),request('order_option'));
                if(request('orders')!='district.name'){
                    $result->orderBy('district.name','ASC');
                }
            }else{
                $result->orderBy('district.created_at','DESC')->orderBy('district.name','ASC');
            }
        }
        if($type=='vilages'){
            $select=[
                'vilage.id',
                'vilage.name',
                DB::raw('users.name cs_name,
                    district.name district_name,
                    city.name city_name,
                    province.name provinci_name'),
            ];
            $result=RefVilage::leftjoin('users','users.id','=','vilage.created_by')
                    ->leftJoin('district','district.id','=','vilage.district_id')
                    ->leftJoin('city','city.id','=','district.city_id')
                    ->leftJoin('province','province.id','=','city.province_id');
            $result->select($select);
            if(request('search')){
                $lowerSearch=strtolower(request('search'));
                $where="(lower(vilage.name) like '%".$lowerSearch."%')";
                $result->whereRaw($where);
            }
            if(request('orders')){
                $result->orderBy(request('orders'),request('order_option'));
                if(request('orders')!='vilage.name'){
                    $result->orderBy('vilage.name','ASC');
                }
            }else{
                $result->orderBy('vilage.created_at','DESC')->orderBy('vilage.name','ASC');
            }
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
        $result=false;
        if(request('type')=='province'){
            $result=RefProvince::select(['id','name'])->where(['id'=>$refId])->first();
            if(!$result){
                throw new Exception('Referensi Provinsi tidak ditemukan.', 404);
            }
        }
        if(request('type')=='cities'){
            $result=RefCity::select(['city.id','city.name','city.province_id'])
                ->where(['city.id'=>$refId])
                ->with('province:id,name')
                ->first();
            if(!$result){
                throw new Exception('Referensi Kota/Kab tidak ditemukan.', 404);
            }
            $result->province_id=$result->province;
            unset($result->province);
        }
        if(request('type')=='districts'){
            $result=RefDistrict::select(['district.id','district.name','district.city_id'])
                ->with(['city:id,name,province_id','city.province:id,name'])
                ->where(['district.id'=>$refId])
                ->first();
            if(!$result){
                throw new Exception('Referensi Kecamatan tidak ditemukan.', 404);
            }
            $result->cities_id=$result->city;
            $result->province_id=$result->city->province;
            unset($result->city_id);
            unset($result->cities_id->province_id);
            unset($result->cities_id->province);
            unset($result->city);
        }
        if(request('type')=='vilages'){
            $result=RefVilage::select(['vilage.id','vilage.name','vilage.district_id'])
                ->with(['district:id,name,city_id','district.city:id,name,province_id','district.city.province:id,name'])
                ->where(['vilage.id'=>$refId])
                ->first();
            if(!$result){
                throw new Exception('Referensi Desa/Kelurahan tidak ditemukan.', 404);
            }
            $result->districts_id=$result->district;
            $result->cities_id=$result->district->city;
            $result->province_id=$result->cities_id->province;

            unset($result->cities_id->province_id);
            unset($result->cities_id->province);
            unset($result->district->city_id);
            unset($result->district->city);
            unset($result->district);
        }
        return $result;
    }
    public static function districtVilage($request){
        $result=false;
        if(request('refId')){
            $lowerSearch=strtolower(request('q'));
            $where="(lower(vilage.name) like '%".$lowerSearch."%'
                or lower(district.name) like '%".$lowerSearch."%')";
            $q = $request->get('q');
            $result = RefVilage::query()
                ->when($q, fn($query) => $query->whereRaw($where))
                ->where(['district.city_id'=>request('refId')])
                ->join('district','district.id','=','vilage.district_id')
                ->select(['vilage.id','vilage.name',DB::raw('district.name district')])
                ->limit(20)
                ->get();
        }
        
        return $result;
    }
    public static function provinceCity($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(city.name) like '%".$lowerSearch."%'
            or lower(province.name) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $result = RefCity::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->join('province','province.id','=','city.province_id')
            ->select(['city.id','city.name',DB::raw('province.name province')])
            ->limit(20)
            ->get();
        return $result;
    }
    public static function search($request){
        $result=false;
        $lowerSearch=strtolower(request('q'));
        $q = $request->get('q');
        if(request('type')=='province'){
            $where="(lower(province.name) like '%".$lowerSearch."%')";
            $result = RefProvince::query()
                ->when($q, fn($query) => $query->whereRaw($where))
                ->select(['id','name'])
                ->limit(20)
                ->get();
        }
        if(request('type')=='laravolt_province'){
            $existingNames = RefProvince::pluck('name')->toArray();
            $where="(lower(name) like '%".$lowerSearch."%')";
            $result = DB::table('indonesia_provinces')
                ->when($q, fn($query) => $query->whereRaw($where))
                ->whereNotIn('name', $existingNames)
                ->select(['id','name'])
                ->limit(20)
                ->get();
        }
        if(request('type')=='cities'&&request('refId')){
            $where="(lower(city.name) like '%".$lowerSearch."%')";
            $result = RefCity::query()
                ->when($q, fn($query) => $query->whereRaw($where))
                ->where(['province_id'=>request('refId')])
                ->select(['id','name'])
                ->limit(20)
                ->get();
        }
        if(request('type')=='districts'&&request('refId')){
            $where="(lower(district.name) like '%".$lowerSearch."%')";
            $result = RefDistrict::query()
                ->when($q, fn($query) => $query->whereRaw($where))
                ->where(['city_id'=>request('refId')])
                ->select(['id','name'])
                ->limit(20)
                ->get();
        }
        if(request('type')=='laravolt_cities'&&request('refId')){
            $localProv = RefProvince::find(request('refId'));
            if($localProv) {
                $laravoltProv = DB::table('indonesia_provinces')->where('name', strtoupper($localProv->name))->first();
                if($laravoltProv) {
                    $existingNames = RefCity::where('province_id', request('refId'))->pluck('name')->toArray();
                    $where="(lower(name) like '%".$lowerSearch."%')";
                    $result = DB::table('indonesia_cities')
                        ->where('province_code', $laravoltProv->code)
                        ->when($q, fn($query) => $query->whereRaw($where))
                        ->whereNotIn('name', $existingNames)
                        ->select(['id','name'])
                        ->limit(20)
                        ->get();
                }
            }
        }
        if(request('type')=='laravolt_districts'&&request('refId')){
            $localCity = RefCity::with('province')->find(request('refId'));
            if($localCity && $localCity->province) {
                $laravoltProv = DB::table('indonesia_provinces')->where('name', strtoupper($localCity->province->name))->first();
                if ($laravoltProv) {
                    $laravoltCity = DB::table('indonesia_cities')
                        ->where('province_code', $laravoltProv->code)
                        ->where('name', strtoupper($localCity->name))
                        ->first();
                    if($laravoltCity) {
                        $existingNames = RefDistrict::where('city_id', request('refId'))->pluck('name')->toArray();
                        $where="(lower(name) like '%".$lowerSearch."%')";
                        $result = DB::table('indonesia_districts')
                            ->where('city_code', $laravoltCity->code)
                            ->when($q, fn($query) => $query->whereRaw($where))
                            ->whereNotIn('name', $existingNames)
                            ->select(['id','name'])
                            ->limit(20)
                            ->get();
                    }
                }
            }
        }
        if(request('type')=='laravolt_vilages'&&request('refId')){
            $localDistrict = RefDistrict::with('city.province')->find(request('refId'));
            if($localDistrict && $localDistrict->city && $localDistrict->city->province) {
                $laravoltProv = DB::table('indonesia_provinces')->where('name', strtoupper($localDistrict->city->province->name))->first();
                if ($laravoltProv) {
                    $laravoltCity = DB::table('indonesia_cities')
                        ->where('province_code', $laravoltProv->code)
                        ->where('name', strtoupper($localDistrict->city->name))
                        ->first();
                    if($laravoltCity) {
                        $laravoltDistrict = DB::table('indonesia_districts')
                            ->where('city_code', $laravoltCity->code)
                            ->where('name', strtoupper($localDistrict->name))
                            ->first();
                        
                        if($laravoltDistrict) {
                            $existingNames = RefVilage::where('district_id', request('refId'))->pluck('name')->toArray();
                            $where="(lower(name) like '%".$lowerSearch."%')";
                            $result = DB::table('indonesia_villages')
                                ->where('district_code', $laravoltDistrict->code)
                                ->when($q, fn($query) => $query->whereRaw($where))
                                ->whereNotIn('name', $existingNames)
                                ->select(['id','name'])
                                ->limit(20)
                                ->get();
                        }
                    }
                }
            }
        }
        // if(!$result){
        //     dd($request->all());
        // }
        return $result;
    }
    public static function store($request){
        $result=false;
        if(request('type')=='province'){
            $listSave=['name'];
            $saveData=$request->only($listSave);
            $result=RefProvince::create($saveData);
        }
        if(request('type')=='cities'){
            $listSave=['name','province_id'];
            $saveData=$request->only($listSave);
            $result=RefCity::create($saveData);
        }
        if(request('type')=='districts'){
            $listSave=['name','cities_id'];
            $saveData=$request->only($listSave);
            $saveData['city_id']=$saveData['cities_id'];
            unset($saveData['cities_id']);
            $result=RefDistrict::create($saveData);
        }
        if(request('type')=='vilages'){
            $listSave=['name','districts_id'];
            $saveData=$request->only($listSave);
            $saveData['district_id']=$saveData['districts_id'];
            unset($saveData['districts_id']);
            $result=RefVilage::create($saveData);
        }
        if(!$result){
            dd($request->all());
        }
        return $result;
    }
    public static function update($refId,$request){
        $result=false;
        if(request('type')=='province'){
            $listSave=['name'];
            $saveData=$request->only($listSave);
            $result=RefProvince::where(['id'=>$refId])->update($saveData);
        };
        if(request('type')=='cities'){
            $listSave=['name','province_id'];
            $saveData=$request->only($listSave);
            $result=RefCity::where(['id'=>$refId])->update($saveData);
        };
        if(request('type')=='districts'){
            $listSave=['name','city_id'];
            $saveData=$request->only($listSave);
            $saveData['city_id']=$request->get('cities_id',true);
            $result=RefDistrict::where(['id'=>$refId])->update($saveData);
        };
        if(request('type')=='vilages'){
            $listSave=['name','district_id'];
            $saveData=$request->only($listSave);
            $saveData['district_id']=$request->get('districts_id',true);
            $result=RefVilage::where(['id'=>$refId])->update($saveData);
        };
        
        return $result;
    }
    public static function delete($request){
        $result=false;
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        if(request('type')=='province'){
            $deleteData=RefProvince::whereIn('id',$refId['data']);
            $deleteData->update(['deleted_by'=>$userId]);
            $result=$deleteData->delete();
        }
        if(request('type')=='cities'){
            $deleteData=RefCity::whereIn('id',$refId['data']);
            $deleteData->update(['deleted_by'=>$userId]);
            $result=$deleteData->delete();
        }
        if(request('type')=='districts'){
            $deleteData=RefDistrict::whereIn('id',$refId['data']);
            $deleteData->update(['deleted_by'=>$userId]);
            $result=$deleteData->delete();
        }
        if(request('type')=='vilages'){
            $deleteData=RefVilage::whereIn('id',$refId['data']);
            $deleteData->update(['deleted_by'=>$userId]);
            $result=$deleteData->delete();
        }
        // if(!$result){
        //     dd($request->all());
        // }
        return $result;
    }
}
