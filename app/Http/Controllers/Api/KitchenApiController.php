<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\KitchenRepository;
use App\Traits\Validators;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class KitchenApiController extends Controller
{
    use Validators;
    protected $KitchenRepository;
    public function __construct(KitchenRepository $KitchenRepository)
    {
        $this->KitchenRepository = $KitchenRepository;
    }
    public function index(Request $request){
        try {
            $result=$this->KitchenRepository->getallData($request);
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
            $result=$this->KitchenRepository->export($refId,$request);
            if(!$result){
                throw new Exception('Data tidak ditemukan.', 1);
            }
            return  $this->successResponse('Data di temukan.',['data'=>$result]);
        } catch (\Throwable $th) {
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function show($refId,Request $request){
        try {
            $result=$this->KitchenRepository->detail($refId,$request);
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
}
