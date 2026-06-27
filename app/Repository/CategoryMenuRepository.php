<?php

namespace App\Repository;

use App\Models\CategoryMenusCatering;
use App\Models\Ingredients;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class  CategoryMenuRepository
{
    use IconComponent,FormatParse;
    public static function getAllCategory($request,$min=true){
        if(request('paket')||$min){
            $paketIds=explode(',',request('paket'));
            $result=CategoryMenusCatering::select(['category_menus_catering.id'])
                ->withCount([
                    'activeMenus'=> function ($query) use ($paketIds) {
                        if(request('paket')){
                            $query->whereHas('packet', function ($sub) use ($paketIds) {
                                $sub->whereIn('packet_catering_id', $paketIds);
                            });
                        }
                    }
                ])
                ->get();
        }else{
            $result=CategoryMenusCatering::select(['category_menus_catering.id','category_menus_catering.name'])
                ->withCount('activeMenus')
                ->get();
            $result=Collect($result)->transform(function($res){
                $res->label=self::makeLabelFromName($res->name);
                $res->icon=self::getCategoryIcon($res->name);
                return $res;
            })->toArray();
        }
        return $result;
    }
    public static function getallData($request)
    {
        $select=[
            'category_menus_catering.id',
            'category_menus_catering.name',
            'category_menus_catering.created_at',
            'category_menus_catering.path',
            'category_menus_catering.filename',
            DB::raw('users.name cs_name'),
        ];
        $result=CategoryMenusCatering::leftjoin('users','users.id','=','category_menus_catering.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(category_menus_catering.name) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('orders')){
            $result->orderBy(request('orders'),request('order_option'));
            if(request('orders')!='category_menus_catering.name'){
                $result->orderBy('category_menus_catering.name','ASC');
            }
        }else{
            $result->orderBy('category_menus_catering.created_at','DESC')->orderBy('category_menus_catering.name','ASC');
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
                    ->addColumn('icon', function ($data) {
                        return self::getCategoryIcon($data->name);
                    })
                    ->rawColumns(['created_at'])
                    // ->removeColumn(['ref_berkas','sku','is_valid'])
                    ->addIndexColumn()
                    ->toJson();
    }

    public static function detail($refId,$request){
        $result=CategoryMenusCatering::where(['id'=>$refId])
            ->select(['id','name','path','filename','is_quantity'])
            ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        $result->pathfile=$result->pathfile;
        return $result;
    }
    public static function search($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(category_menus_catering.name) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $result = CategoryMenusCatering::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->select(['id','name'])
            ->limit(20)
            ->get();
        return $result;
    }
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $file = $request->file('file_berkas');
        $disk="category_menus";
        if($file){
            if(!in_array(strtolower($file->getClientOriginalExtension()),['jpeg','png','jpg','webp'])){
                throw new Exception("format image/foto harus berjenis *.webp, *.jpg, *.jpeg, *.png", 1);
            }
            if(!in_array(strtolower($file->getClientMimeType()),['image/jpeg','image/png','image/jpg','image/webp'])&&!in_array(request('device'),['mobile'])){
                throw new Exception("format mimeType: ".$file->getClientMimeType().". Format harus image/foto jenis *.webp, *.jpg, *.jpeg, *.png", 1);
            }
            $filename=Carbon::now()->format('YmdHis').".".$file->getClientOriginalExtension();
            $saveData['filename']=$file->getClientOriginalName();
            $saveData['path']=$filename;
            $saveData['disk']=$disk;
        }
        $result=CategoryMenusCatering::create($saveData);
        if($file){
            $upload = Storage::disk($disk);
            $filepath=Image::read($file)
                    ->scaleDown(800)
                    ->encodeByExtension($file->getClientOriginalExtension(),["quality"=> 60]);
            $pathname=$result['id']."/".$filename;
            if (!$upload->put($pathname, $filepath)) {
                throw new Exception("gagal upload", 1);
            }
        }
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $file = $request->file('file_berkas');
        $disk="category_menus";
        $upload = Storage::disk($disk);
        if($file){
            if(!in_array(strtolower($file->getClientOriginalExtension()),['jpeg','png','jpg','webp'])){
                throw new Exception("format image/foto harus berjenis *.webp, *.jpg, *.jpeg, *.png", 1);
            }
            if(!in_array(strtolower($file->getClientMimeType()),['image/jpeg','image/png','image/jpg','image/webp'])&&!in_array(request('device'),['mobile'])){
                throw new Exception("format mimeType: ".$file->getClientMimeType().". Format harus image/foto jenis *.webp, *.jpg, *.jpeg, *.png", 1);
            }
            $filename=Carbon::now()->format('YmdHis').".".$file->getClientOriginalExtension();
            $saveData['filename']=$file->getClientOriginalName();
            $saveData['path']=$filename;
            self::clearImageOld($refId,$upload);
        }else{
            if(!request('has_image')){
                self::clearImageOld($refId,$upload);
                $saveData['filename']=null;
                $saveData['path']=null;
                $saveData['disk']=null;
            }
        }
        $result=CategoryMenusCatering::where(['id'=>$refId])->update($saveData);
        if($file){
            $filepath=Image::read($file)
                    ->scaleDown(800)
                    ->encodeByExtension($file->getClientOriginalExtension(),["quality"=> 60]);
            $pathname=$refId."/".$filename;
            if (!$upload->put($pathname, $filepath)) {
                throw new Exception("gagal upload", 1);
            }
        }
        return $result;
    }
    private static function clearImageOld($refId,$upload){
        $cekImageOld=CategoryMenusCatering::where(['id'=>$refId])->select(['id','path'])->first();
        if($cekImageOld){
            $direktoryOld=$refId;
            if ($upload->exists($direktoryOld)) {
                $upload->deleteDirectory($direktoryOld);
            }
        }
        return $cekImageOld;
    }
    public static function delete($request){
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        $customerData=CategoryMenusCatering::whereIn('id',$refId['data']);
        $customerData->update(['deleted_by'=>$userId]);
        $result=$customerData->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'name',
            'filename',
            'path',
            'disk',
            'is_quantity'
        ];
    }
}
