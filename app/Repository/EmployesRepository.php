<?php

namespace App\Repository;

use App\Models\EmployeeContracts;
use App\Models\EmployeeEducations;
use App\Models\EmployeeEmergencies;
use App\Models\EmployeeFamilies;
use App\Models\Employes;
use App\Models\User;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class  EmployesRepository
{
    use IconComponent;
    public static function getallData($request)
    {
        $select=[
            'employes.id',
            'employes.name',
            'employes.gender',
            'employes.phone',
            'employes.address',
            'employes.location',
            'employes.national_id',
            'employes.division',
            'employes.birth_place_date',
            'employes.work_since',
            'employes.religion',
            'employes.status',
            'employes.created_at',
            DB::raw('users.name cs_name'),
        ];
        $result=Employes::leftjoin('users','users.id','=','employes.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(employes.name) like '%".$lowerSearch."%'
                or lower(employes.phone) like '%".$lowerSearch."%'
                or lower(employes.address) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('orders')){
            $result->orderBy(request('orders'),request('order_option'));
            if(request('orders')!='employes.name'){
                $result->orderBy('employes.name','ASC');
            }
        }else{
            $result->orderBy('employes.created_at','DESC')->orderBy('employes.name','ASC');
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
        $select=[
            'employes.id',
            'employes.name',
            'employes.phone',
            'employes.address',
            'employes.location',
            'employes.gender',
            'employes.national_id',
            'employes.status',
            'employes.work_since',
            'employes.division',
            'employes.birth_place_date',
            'employes.height_cm',
            'employes.weight_kg',
            'employes.religion',
            'employes.user_id'
        ];
        $result=Employes::where(['id'=>$refId])
            ->with([
                'educations:id,employee_id,education_level,school_name,city,major,year_start,year_graduated',
                'families:id,employee_id,name,relation,birth_place_date,gender,education',
                'emergencies:id,employee_id,name,relation,address,phone',
                'users:id,name,email'
            ])
            ->select($select)
            ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        return $result;
    }
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        if(request('username')&&request('email')&&request('password')){
            $saveUser=[
                'name'=>request('username'),
                'email'=>request('email'),
                'password'=>request('password'),
            ];
            $User=User::create($saveUser);
            $saveData['user_id']=$User->id;
        }
        $result=Employes::create($saveData);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        if(request('contract_end')&&request('interview_result')){
            $saveContract=$request->only(['contract_end','interview_result']);
            $saveContract['employee_id']=$result->id;
            $resultContract=EmployeeContracts::create($saveContract);
        }
        if(request('educations')){
            $listEdu=$request->get('educations',true);
            $filteredEducations = collect($listEdu)
            ->filter(function ($item) {
                return collect($item)->every(fn($v) => !is_null($v) && $v !== '');
            })
            ->map(function ($edu) use($result,$now) {
                return array_merge($edu, [
                    'id'=>(string) Str::ulid(),
                    'employee_id' => $result->id,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            })
            ->values();
            if($filteredEducations->count()){
                $saveEducations=$filteredEducations->toArray();
                // dd($saveEducations);
                $resultEducations=EmployeeEducations::insert($saveEducations);
            }
        }
        if(request('families')){
            $listFam=$request->get('families',true);
            $filteredFamilies = collect($listFam)
            ->filter(function ($item) {
                return collect($item)->every(fn($v) => !is_null($v) && $v !== '');
            })
            ->map(function ($fam) use($result,$now) {
                return array_merge($fam, [
                    'id'=>(string) Str::ulid(),
                    'employee_id' => $result->id,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            })
            ->values();
            if($filteredFamilies->count()){
                $saveFamilies=$filteredFamilies->toArray();
                // dd($saveFamilies);
                $resultFamilies=EmployeeFamilies::insert($saveFamilies);
            }
        }
        if(request('emergencies')){
            $listEme=$request->get('emergencies',true);
            $filteredEmergencies = collect($listEme)
            ->filter(function ($item) {
                return collect($item)->every(fn($v) => !is_null($v) && $v !== '');
            })
            ->map(function ($fam) use($result,$now) {
                return array_merge($fam, [
                    'id'=>(string) Str::ulid(),
                    'employee_id' => $result->id,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            })
            ->values();
            if($filteredEmergencies->count()){
                $saveEmergencies=$filteredEmergencies->toArray();
                // dd($saveFamilies);
                $resultEmergencies=EmployeeEmergencies::insert($saveEmergencies);
            }
        }
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        if(request('username')&&request('email')&&request('password')){
            $saveUser=[
                'name'=>request('username'),
                'email'=>request('email'),
                'password'=>request('password'),
            ];
            $userId=request('user_id');
            if(str::isUuid($userId)){
                if(request('password')=='{{config("option.pass_default")}}'){
                    unset($saveUser['password']);
                }
                User::where(['id'=>$userId])->update($saveUser);
            }else{
                $User=User::create($saveUser);
                $saveData['user_id']=$User->id;
            }
            
        }
        // dd($request->all());
        // dd($saveData);
        $result=Employes::where(['id'=>$refId])->update($saveData);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        if(request('contract_end')&&request('interview_result')){
            $saveContract=$request->only(['contract_end','interview_result']);
            $resultContract=EmployeeContracts::updateOrCreate(['employee_id'=>$refId],$saveContract);
        }
        if(request('educations')){
            $listEdu=$request->get('educations',true);
            $filteredEducations = collect($listEdu)
            ->filter(function ($item) {
                return collect($item)->every(fn($v) => !is_null($v) && $v !== '');
            })
            ->map(function ($edu,$key) use($refId,$now) {
                if(Str::isUuid($key ?? '')){
                    $key=(string) Str::ulid();
                }
                return array_merge($edu, [
                    'id'=>$key,
                    'employee_id' => $refId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            })
            ->values();
            if($filteredEducations->count()){
                $saveEducations=$filteredEducations->filter(fn($item) => Str::isUlid($item['id'] ?? ''))->toArray();
                $keys = collect($filteredEducations->first())->keys()->reject(fn($key) => $key === 'id')->toArray();
                $resultEducations=EmployeeEducations::upsert($saveEducations,['id','employee_id'],$keys);
            }
        }
        if(request('families')){
            $listFam=$request->get('families',true);
            $filteredFamilies = collect($listFam)
            ->filter(function ($item) {
                return collect($item)->every(fn($v) => !is_null($v) && $v !== '');
            })
            ->map(function ($fam,$key) use($refId,$now) {
                if(Str::isUuid($key ?? '')){
                    $key=(string) Str::ulid();
                }
                return array_merge($fam, [
                    'id'=>$key,
                    'employee_id' => $refId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            })
            ->values();
            if($filteredFamilies->count()){
                $saveFamilies=$filteredFamilies->filter(fn($item) => Str::isUlid($item['id'] ?? ''))->toArray();
                $keys = collect($filteredFamilies->first())->keys()->reject(fn($key) => $key === 'id')->toArray();
                $resultFamilies=EmployeeFamilies::upsert($saveFamilies,['id','employee_id'],$keys);
            }
        }
        if(request('emergencies')){
            $listEme=$request->get('emergencies',true);
            $filteredEmergencies = collect($listEme)
            ->filter(function ($item) {
                return collect($item)->every(fn($v) => !is_null($v) && $v !== '');
            })
            ->map(function ($fam,$key) use($refId,$now) {
                if(Str::isUuid($key ?? '')){
                    $key=(string) Str::ulid();
                }
                return array_merge($fam, [
                    'id'=>$key,
                    'employee_id' => $refId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            })
            ->values();
            if($filteredEmergencies->count()){
                $saveEmergencies=$filteredEmergencies->filter(fn($item) => Str::isUlid($item['id'] ?? ''))->toArray();
                $keys = collect($filteredEmergencies->first())->keys()->reject(fn($key) => $key === 'id')->toArray();
                $resultEmergencies=EmployeeEmergencies::upsert($saveEmergencies,['id','employee_id'],$keys);
            }
        }
        return $result;
    }
    public static function delete($request){
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        $customerData=Employes::whereIn('id',$refId['data']);
        $customerData->update(['deleted_by'=>$userId]);
        $result=$customerData->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'name',
            'phone',
            'address',
            'location',
            'gender',
            'national_id',
            'status',
            'work_since',
            'division',
            'birth_place_date',
            'height_cm',
            'weight_kg',
            'religion',
        ];
    }
}
