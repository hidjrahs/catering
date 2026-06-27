<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\PacketMenuRepository;
use App\Traits\Validators;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PacketMenuApiController extends Controller
{
    use Validators;
    protected $PacketMenuRepository;
    public function __construct(PacketMenuRepository $PacketMenuRepository)
    {
        $this->PacketMenuRepository = $PacketMenuRepository;
    }
    public function index(Request $request){
        try {
            $result=$this->PacketMenuRepository->getallData($request);
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
            $result=$this->PacketMenuRepository->store($request);
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
            $result=$this->PacketMenuRepository->update($refId,$request);
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
            $result=$this->PacketMenuRepository->delete($request);
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
            $result=$this->PacketMenuRepository->detail($refId,$request);
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
            $result=$this->PacketMenuRepository->search($request);
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
            $result=$this->PacketMenuRepository->search_all($request);
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
