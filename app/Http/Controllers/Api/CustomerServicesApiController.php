<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\CustomerServicesRepository;
use App\Traits\Validators;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CustomerServicesApiController extends Controller
{
    use Validators;
    protected $CustomerServicesRepository;
    public function __construct(CustomerServicesRepository $CustomerServicesRepository)
    {
        $this->CustomerServicesRepository = $CustomerServicesRepository;
    }
    public function index(Request $request){
        try {
            $result=$this->CustomerServicesRepository->getallData($request);
            if(!$result){
                throw new Exception('Data tidak ditemukan.', 1);
            }
            if(in_array(request('device'),['web','stealth','mobile'])){
                return $result;
            }
            return  $this->successResponse('Data di temukan.',['data'=>$result]);
        } catch (\Throwable $th) {
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function store(Request $request){
        $rule=$this->listValidation();
        $validator = $this->validateCheck($request->all(),$rule);
        if($validator->fails()){
            return  $this->errorResponse('Failed Request',false,$validator->errors()->all(),422);
        }
        DB::beginTransaction();
        try {
            $result=$this->CustomerServicesRepository->store($request);
            if(!$result){
                throw new Exception('Data gagal di simpan.', 1);
            }
            DB::commit();
            if(in_array(request('device'),['web','stealth','mobile'])){
                return $result;
            }
            return  $this->successResponse('Data berhasil di simpan.',['data'=>$result]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function update($refId,Request $request){
        $rule=$this->listValidation();
        $validator = $this->validateCheck($request->all(),$rule);
        if($validator->fails()){
            return  $this->errorResponse('Failed Request',false,$validator->errors()->all(),422);
        }
        DB::beginTransaction();
        try {
            $result=$this->CustomerServicesRepository->update($refId,$request);
            if(!$result){
                throw new Exception('Data gagal di udpate.', 1);
            }
            DB::commit();
            if(in_array(request('device'),['web','stealth','mobile'])){
                return $result;
            }
            return  $this->successResponse('Data berhasil di udpate.',['data'=>$result]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function destroy(Request $request){
        DB::beginTransaction();
        try {
            $result=$this->CustomerServicesRepository->delete($request);
            if(!$result){
                throw new Exception('Data gagal di hapus.', 1);
            }
            DB::commit();
            if(in_array(request('device'),['web','stealth','mobile'])){
                return $result;
            }
            return  $this->successResponse('Data gagal di hapus.',['data'=>$result]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function show($refId,Request $request){
        try {
            $result=$this->CustomerServicesRepository->detail($refId,$request);
            if(!$result){
                throw new Exception('Data tidak ditemukan.', 1);
            }
            if(in_array(request('device'),['web','stealth','mobile'])){
                return $result;
            }
            return  $this->successResponse('Data di temukan.',['data'=>$result]);
        } catch (\Throwable $th) {
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function export($refId,Request $request){
        try {
            $result=$this->CustomerServicesRepository->export($refId,$request);
            if(!$result){
                throw new Exception('Data tidak ditemukan.', 1);
            }
            return  $this->successResponse('Data di temukan.',['data'=>$result]);
        } catch (\Throwable $th) {
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function exportrincian($refId,Request $request){
        try {
            $result=$this->CustomerServicesRepository->exportrincian($refId,$request);
            if(!$result){
                throw new Exception('Data tidak ditemukan.', 1);
            }
            return  $this->successResponse('Data di temukan.',['data'=>$result]);
        } catch (\Throwable $th) {
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function search(Request $request){
        try {
            $result=$this->CustomerServicesRepository->search($request);
            if(!$result){
                throw new Exception('Data tidak ditemukan.', 1);
            }
            if(in_array(request('device'),['web','stealth','mobile'])){
                return $result;
            }
            return  $this->successResponse('Data di temukan.',['data'=>$result]);
        } catch (\Throwable $th) {
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    private function listValidation(){
        return [
            'customer_id'=> 'required',
            'delivery_date'=> 'required',
            'event_date'=> 'required',
            'total_guest'=> 'required'
        ];
    }
}
