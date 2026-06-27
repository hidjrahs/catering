<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\CostStructureRepository;
use App\Traits\Validators;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CostStructureApiController extends Controller
{
    use Validators;
    protected $CostStructureRepository;
    public function __construct(CostStructureRepository $CostStructureRepository)
    {
        $this->CostStructureRepository = $CostStructureRepository;
    }
    public function index(Request $request){
        try {
            $result=$this->CostStructureRepository->getallData($request);
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
            $result=$this->CostStructureRepository->store($request);
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
            $result=$this->CostStructureRepository->update($refId,$request);
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
            $result=$this->CostStructureRepository->delete($request);
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
            $result=$this->CostStructureRepository->detail($refId,$request);
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
    public function search(Request $request){
        try {
            $result=$this->CostStructureRepository->search($request);
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
    public function search_all(Request $request){
        try {
            $result=$this->CostStructureRepository->search_all($request);
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
            'name'=> 'required',
            // 'file_berkas'=>'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];
    }
}
