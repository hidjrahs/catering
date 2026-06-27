<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\ManagementStokRepository;
use App\Traits\Validators;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ManagementStokApiController extends Controller
{
    use Validators;
    protected $ManagementStokRepository;
    public function __construct(ManagementStokRepository $ManagementStokRepository)
    {
        $this->ManagementStokRepository = $ManagementStokRepository;
    }
    public function index(Request $request){
        try {
            $result=$this->ManagementStokRepository->getallData($request);
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
    public function verify(Request $request){
        $rule=$this->listValidation();
        $validator = $this->validateCheck($request->all(),$rule);
        if($validator->fails()){
            return  $this->errorResponse('Failed Request',false,$validator->errors()->all(),422);
        }
        DB::beginTransaction();
        try {
            $result=$this->ManagementStokRepository->verifyOrder($request);
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
    
    public function show($refId,Request $request){
        try {
            $result=$this->ManagementStokRepository->detail($refId,$request);
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
            'id'=> 'required',
            'estimate_price'=> 'required'
        ];
    }
}
