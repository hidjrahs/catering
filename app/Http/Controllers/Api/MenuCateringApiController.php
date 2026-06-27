<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\ImportRepository;
use App\Repository\MenuCateringRepository;
use App\Traits\Validators;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\RecipeFormatExport;
use Maatwebsite\Excel\Facades\Excel;

class MenuCateringApiController extends Controller
{
    use Validators;
    protected $MenuCateringRepository;
    public function __construct(MenuCateringRepository $MenuCateringRepository)
    {
        $this->MenuCateringRepository = $MenuCateringRepository;
    }
    public function index(Request $request){
        try {
            $result=$this->MenuCateringRepository->getallData($request);
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
            $result=$this->MenuCateringRepository->store($request);
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
    public function destroy_batch($refId,Request $request,ImportRepository $ImportRepository){
        DB::beginTransaction();
        try {
            $result=$ImportRepository->deleteimportTemp($refId,$request);
            if(!$result){
                throw new Exception('Batch tidak ditemukan.', 1);
            }
            DB::commit();
            return  $this->successResponse('Data berhasil di proses.',['data'=>$result]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function list_batch_preview(Request $request,ImportRepository $ImportRepository){
        try {
            $result=$ImportRepository->getallDataPreview($request);
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
    public function check_batch($refId,Request $request,ImportRepository $ImportRepository){
        DB::beginTransaction();
        try {
            $result=$ImportRepository->checkBatch($refId,$request,'rtlh');
            if(!$result){
                throw new Exception('Batch tidak ditemukan.', 1);
            }
            DB::commit();
            return  $this->successResponse('Data berhasil di proses.',['data'=>$result]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function list_batch(Request $request,ImportRepository $ImportRepository){
        try {
            $result=$ImportRepository->getallData($request);
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
    public function recipe(Request $request){
        $rule=['idTemp'=>'required'];
        $validator = $this->validateCheck($request->all(),$rule);
        if($validator->fails()){
            return  $this->errorResponse('Failed Request',false,$validator->errors()->all(),422);
        }
        DB::beginTransaction();
        try {
            $result=ImportRepository::importBatch($request,'recipe_menu');
            if(!$result){
                throw new Exception('Data gagal di simpan.', 1);
            }
            DB::commit();
            return  $this->successResponse('Data berhasil di simpan.',['data'=>$result]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function store_batch(Request $request){
        $rule=['file'=>'required|mimes:xlsx,xls'];
        $validator = $this->validateCheck($request->all(),$rule);
        if($validator->fails()){
            return  $this->errorResponse('Failed Request',false,$validator->errors()->all(),422);
        }
        DB::beginTransaction();
        try {
            $result=ImportRepository::storeBatch($request);
            if(!$result){
                throw new Exception('Data gagal di simpan.', 1);
            }
            DB::commit();
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
            $result=$this->MenuCateringRepository->update($refId,$request);
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
            $result=$this->MenuCateringRepository->delete($request);
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
            $result=$this->MenuCateringRepository->detail($refId,$request);
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
    public function select(Request $request){
        try {
            $result=$this->MenuCateringRepository->select($request);
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
            $result=$this->MenuCateringRepository->search($request);
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
    
    public function generate(Request $request){
        return Excel::download(new RecipeFormatExport, 'template_resep.xlsx');
    }
    private function listValidation(){
        return [
            'name'=> 'required',
            // 'file_berkas'=>'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];
    }
}
