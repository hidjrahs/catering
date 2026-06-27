<?php

namespace App\Repository;

use App\Http\Resources\CostControlingResource;
use App\Models\CostEstimationDetail;
use App\Models\CostEstimations;
use App\Models\MenusCatering;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\RincianBiaya;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class  CostControlingRepository
{
    use IconComponent,FormatParse;
    public static function getallData($request)
    {
        $select=[
            'orders.id',
            'customers.name',
            'customers.phone',
            'customers.gender',
            'orders.delivery_date',
            'orders.order_ticket',
            'orders.event_date',
            'orders.total_guest',
            'orders.estimate_price',
            'orders.status',
            'orders.desc',
            'orders.created_at',
            DB::raw('users.name cs_name')
        ];
        $result=Orders::with(['refItem:id,order_id,price'])
                ->leftjoin('customers','customers.id','=','orders.customer_id')
                ->leftjoin('users','users.id','=','orders.created_by');
        $result->select($select);
        if(request('search')){
            $lowerSearch=strtolower(request('search'));
            $where="(lower(customers.name) like '%".$lowerSearch."%'
                or lower(customers.phone) like '%".$lowerSearch."%')";
            $result->whereRaw($where);
        }
        if(request('status')&&request('status')!='-'){
            $result->where(['orders.status'=>strtolower(request('status'))]);
        }

        $date=Carbon::now();
        if(request('date')){
            $date=request('date');
        }
        $start = Carbon::parse($date)->startOfMonth();
        $end   = Carbon::parse($date)->endOfMonth();
        $datetype=request('orders')=='event'?'orders.event_date':'orders.created_at';
        $result->whereBetween($datetype, [$start, $end]);
        if(request('orders')=='event'){
            $result->orderBy('orders.event_date','DESC')->orderBy('customers.name','ASC');
        }else{
            $result->orderBy('orders.created_at','DESC')->orderBy('customers.name','ASC');
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
        $status_list=config('option.order_status');
        $user=Auth()->user();
        return DataTables::of($data)
                    ->editColumn('created_at', function ($data) {
                        if(!$data->created_at){
                            return '';
                        }
                        $tgl=Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d/m/Y H:i:s');
                        return '<span class="text-muted fw-semibold text-muted d-block fs-9">'.$tgl.'</span>';
                    })
                    ->editColumn('status', function ($data) use($status_list) {
                        if(!in_array($data->status,array_keys($status_list))){
                            return '??';
                        }
                        return $status_list[$data->status];
                    })
                    ->editColumn('cs_name', function ($data) {
                        return $data->cs_name??'-';
                    })
                    ->editColumn('delivery_date', function ($data) {
                        if(!$data->delivery_date){
                            return '';
                        }
                        return $tgl=Carbon::createFromFormat('Y-m-d H:i:s', $data->delivery_date)->format('d/m/Y H:i:s');
                    })
                    ->editColumn('event_date', function ($data) {
                        if(!$data->event_date){
                            return '';
                        }
                        return $tgl=Carbon::createFromFormat('Y-m-d H:i:s', $data->event_date)->format('d/m/Y H:i:s');
                    })
                    ->editColumn('total_guest', function ($data) {
                        return $data->total_guest?self::parseQuantity($data->total_guest):'-';
                    })
                    ->editColumn('estimate_price', function ($data) {
                        return $data->estimate_price??'-';
                    })
                    ->addColumn('total_items', function ($data) {
                        return $data->refItem->count(); 
                    })
                    // ->addColumn('est_price', function ($data) {
                    //     return $data->refItem->sum('price');
                    // })
                    ->rawColumns(['created_at','delivery_date','event_date'])
                    // ->removeColumn(['ref_item'])
                    ->addIndexColumn()
                    ->toJson();
    }
    public static function detail($refId,$request){
        $select=[
            'orders.id',
            'orders.customer_id',
            'orders.order_ticket',
            'orders.estimate_price',
            'orders.delivery_date',
            'orders.event_date',
            'orders.total_guest',
            'orders.total_invite',
            'orders.status',
            'orders.desc',
            'orders.event_type',
            'orders.package_type',
            'orders.venue',
            'orders.dp'
        ];
        $result=Orders::where(['orders.id'=>$refId])
                ->with([
                    'customer:id,name,phone,address,vilage_id',
                    'customer.vilage',
                    'customer.vilage.district',
                    'customer.vilage.district.city',
                    'customer.vilage.district.city.province',
                    'rincianbiaya:id,order_id,name,quantity,price',
                    'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price,notes',
                    'refItem.menu:id,name,porsi_standard,selling_price,category_menus_catering_id',
                    'refItem.menu.category:id,name',
                    'refItem.menu.menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
                    'refItem.menu.menuingredients.ingredient:id,name,unit,satuan,default_price',
                    'costestimation:id,order_id,estimated_cost,estimated_selling_price,estimated_margin,cost_structure_id',
                    'costestimation.detail:id,cost_estimation_id,name,fixed,kategori,prosentase,prosentase_price,fixed_price',
                ])
                ->select($select)
                ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        // dd($result->toArray());
        return new CostControlingResource($result);
    }
    public static function verifyOrder($request){
        // dd(['s']);
        $listSave=self::listSave();
        $requsdata=$request->only($listSave);
        $requsdata['estimated_selling_price']=self::quantity($requsdata['estimated_selling_price']);
        $requsdata['estimated_margin']=self::quantity($requsdata['estimated_margin']);
        $requsdata['estimated_cost']=self::quantity($requsdata['estimated_cost']);
        $saveData['status']='approved';
        $saveData['estimate_price']=$requsdata['estimated_selling_price'];
        $userId = Auth::check() ? Auth::id() : 1;
        // dd($request->all());
        // dd($saveData);
        // CostEstimations
        // notes
        if(request('porsi')){
            $porsiUpdate=request('porsi');
            $notesUpdate=request('notes');
            foreach($porsiUpdate as $key=>$val){
                $getRecipe=MenusCatering::where(['id'=>$key])->select(['porsi_standard','selling_price'])->first();
                if($getRecipe){
                    $porsi_standard=$getRecipe->porsi_standard??0;
                    $price=$getRecipe->selling_price??0;
                    $hasil = ($price / $porsi_standard) * $val;
                    $updateData=[
                        'quantity'=>$val,
                        'price'=>$hasil??0,
                        'notes'=>$notesUpdate[$key]??''
                    ]; 
                    OrderItems::where(['order_id'=>$requsdata['id'],'menus_catering_id'=>$key])->update($updateData);   
                }
            }
        }
        $cost_structure_id=null;
        if(in_array('cost_structure_id',array_keys($requsdata))){
            $cost_structure_id=$requsdata['cost_structure_id'];
        }
        $saveCost=[
            'order_id'=>$requsdata['id'],
            'estimated_cost'=>$requsdata['estimated_cost'],
            'estimated_selling_price'=>$requsdata['estimated_selling_price'],
            'estimated_margin'=>$requsdata['estimated_margin'],
            'desc'=>'',
            'verified_by'=>$userId,
            'cost_structure_id'=>$cost_structure_id!='-'?$cost_structure_id:null
        ];
        // dd($saveCost,$requsdata);
        $result=Orders::where(['id'=>$requsdata['id']])->update($saveData);
        $result=CostEstimations::updateOrCreate(['order_id'=>$requsdata['id']],$saveCost);
        if(request('structure')){
            $structure=request('structure');
            $refId=$result->id;
            CostEstimationDetail::where(['cost_estimation_id'=>$refId])->delete();
            $formatted = collect($structure)->map(function ($item) use ($refId) {
                $fixed=false;
                $fixed_price=false;
                $prosentase=false;
                if(in_array('fixed_price',array_keys($item))){
                    $fixed=$item['fixed_price']?true:false;
                    if($item['fixed_price']){
                        $fixed_price=str_replace(',', '.', str_replace('.', '', $item['fixed_price']));
                    }
                }
                if(in_array('prosentase',array_keys($item))){
                    $prosentase=str_replace(',', '.', $item['prosentase']);
                }
                return [
                    'id'=>(string) Str::ulid(),
                    'cost_estimation_id' => $refId,
                    'name'               => $item['name']??'-',
                    'fixed'              => $fixed,
                    'kategori'           => $item['kategori']?strtolower($item['kategori']) :null,
                    'prosentase'         => $prosentase?$prosentase: 0,
                    'prosentase_price'   => $item['prosentase_price']?? null,
                    'fixed_price'        => $fixed_price??null,
                    'fixed_qty'          => $item['fixed_qty']?? null,
                ];
            })->toArray();
            CostEstimationDetail::insert($formatted);
        }
        $rincianOrder=$request->only('rincian');
        if(!in_array('rincian',array_keys($rincianOrder))){
            throw new Exception('rincian biaya masih kosong', 1);
        }
        $rincianOrder= collect($rincianOrder['rincian'])->whereNotNull('name')->whereNotNull('price');
        if($rincianOrder->isEmpty()){
            throw new Exception('rincian biaya masih kosong', 1);
        }
        // dd($rincianOrder->toArray());
        $rincianNotExists=[];
        foreach($rincianOrder->toArray() as $key=>$rincian){
            if($rincian['name']){
                $qty=str_replace(',', '.', str_replace('.', '', $rincian['qty']??0));
                $price=str_replace(',', '.', str_replace('.', '', $rincian['price']??0));
                $rincianID=$key;
                if(!Str::isUlid($rincianID)){
                    $rincianID=(string) Str::ulid();
                }
                $saveRincian=[
                    'id'=>$rincianID,
                    'order_id'=>$requsdata['id'],
                    'quantity'=>(int) $qty,
                    'name'=>$rincian['name']??'',
                    'price'=>(float) $price
                ];
                $result=RincianBiaya::updateOrCreate(['order_id'=>$requsdata['id'],'id'=>$rincianID],$saveRincian);
                $rincianNotExists[]=$result->id;
            }
        }
        if($rincianNotExists&&count($rincianNotExists)>0){
            $cekOtherTodelete=RincianBiaya::where(['order_id'=>$requsdata['id']])
            ->whereNotIn('id',$rincianNotExists)
            ->forceDelete();
        }
        return $result;
    }
    public static function export($refId,$request){
        $select=[
            'orders.id',
            'orders.customer_id',
            'orders.order_ticket',
            'orders.estimate_price',
            'orders.delivery_date',
            'orders.event_date',
            'orders.total_guest',
            'orders.desc',
            'orders.event_type',
            'orders.package_type',
            'orders.venue',
            'orders.created_by',
            'orders.created_at',
        ];
        $order=Orders::where(['orders.id'=>$refId])
            ->with([
                'petugas:id,name',
                'customer:id,name,phone,address,vilage_id',
                'customer.vilage:id,district_id,name',
                'customer.vilage.district:id,city_id,name',
                'customer.vilage.district.city:id,province_id,name',
                'customer.vilage.district.city.province:id,name',
                'rincianbiaya:id,order_id,name,quantity,price',
                'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price',
                'refItem.menu:id,name,porsi_standard,selling_price,category_menus_catering_id',
                'costestimation:id,order_id,estimated_cost,estimated_selling_price,estimated_margin',
                'costestimation.detail:id,cost_estimation_id,name,fixed,kategori,prosentase,prosentase_price,fixed_price,fixed_qty',
            ])
            ->select($select)->first();
        if(!$order){
            throw new Exception('Data Order tidak ditemukan.', 404);
        }
        // dd($order->toArray());
        $disk="report_cost_control";
        $storageReport = Storage::disk($disk);
        $reportname='cost_'.$refId.".pdf";
        if ($storageReport->exists($reportname)) {
            // return url('storage/report_cost_control/'.$reportname);
            $storageReport->delete($reportname);
        }
        $totalrincian = collect($order->rincianbiaya)->sum(function ($item) {
            $qty = (float) $item->quantity;
            if ($qty == 0) {
                $qty = 1;
            }
            return $qty * (float) $item->price;
        });
        $order=$order->toArray();
        unset($order['rincianbiaya']);
        $tglevent=Carbon::parse($order['event_date']);
        $order['event_date']=Carbon::parse($order['event_date'])->translatedFormat('d F Y H:i');
        $order['created_at']=Carbon::parse($order['created_at'])->translatedFormat('d F Y H:i');
        // $order['delivery_date']=Carbon::parse($order['delivery_date'])->translatedFormat('H:i d F Y');
        $order['tgl_event']=$tglevent->locale('id')->translatedFormat('l, d F Y');
        $qr = QrCode::size(50)
            ->eyeColor(0,1, 137, 115, 0, 0, 0)
            ->eyeColor(1,247, 41, 67, 0, 0, 0)
            ->generate('No Order:'.$refId);
        $data=[
            'title' => 'Lila Catering',
            'data' => $order,
            'billing'=>request('type'),
            'qrcode'=>$qr,
            'totalrincian'=>$totalrincian??0
        ];
        $pdf = Pdf::loadView('export_pdf.cost_control', $data);
        $storageReport->put($reportname, $pdf->output());
        if(!$storageReport){
            throw new Exception("failed generate PDF", 1);
        }
        return url('storage/report_cost_control/'.$reportname);
    }
    public static function exportsr($refId,$request){
        $select=[
            'orders.id',
            'orders.customer_id',
            'orders.order_ticket',
            'orders.event_date',
            'orders.created_at',
            'orders.total_invite',
            'orders.created_by',
            'orders.venue',
        ];
        $order=Orders::where(['orders.id'=>$refId])
            ->with([
                'petugas:id,name',
                'customer:id,name,phone,address,vilage_id',
                'customer.vilage:id,district_id,name',
                'customer.vilage.district:id,city_id,name',
                'customer.vilage.district.city:id,province_id,name',
                'customer.vilage.district.city.province:id,name',
                'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price,notes',
                'refItem.menu:id,name,porsi_standard',
                'refItem.menu.menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
                'refItem.menu.menuingredients.ingredient:id,name,unit,satuan,default_price',
            ])
            ->select($select)->first();
        if(!$order){
            throw new Exception('Data Order tidak ditemukan.', 404);
        }
        // dd($order->toArray());
        $disk="report_cost_control";
        $storageReport = Storage::disk($disk);
        $reportname='sr_'.$refId.".pdf";
        if ($storageReport->exists($reportname)) {
            $storageReport->delete($reportname);
        }
        $order=$order->toArray();
        $tglevent=Carbon::parse($order['event_date']);
        $order['event_date']=Carbon::parse($order['event_date'])->translatedFormat('d F Y H:i');
        $order['created_at']=Carbon::parse($order['created_at'])->translatedFormat('d F Y H:i');
        $order['tgl_event']=$tglevent->locale('id')->translatedFormat('l, d F Y');
        // dd($order);
        $data=[
            'title' => 'Lila Catering',
            'data' => $order,
        ];
        $pdf = Pdf::loadView('export_pdf.cost_control_sr', $data);
        $storageReport->put($reportname, $pdf->output());
        if(!$storageReport){
            throw new Exception("failed generate PDF", 1);
        }
        return url('storage/report_cost_control/'.$reportname);
    }
    
    public static function updateOrder($request){
        
        $listSave=self::listSave();
        $requsdata=$request->only($listSave);
        $requsdata['estimated_selling_price']=self::quantity($requsdata['estimated_selling_price']);
        $requsdata['estimated_margin']=self::quantity($requsdata['estimated_margin']);
        $requsdata['estimated_cost']=self::quantity($requsdata['estimated_cost']);
        $saveData['estimate_price']=$requsdata['estimated_selling_price'];
        $userId = Auth::check() ? Auth::id() : 1;
        // dd($saveData);
        // CostEstimations
        if(request('porsi')){
            $porsiUpdate=request('porsi');
            $notes=request('notes');
            foreach($porsiUpdate as $key=>$val){
                $val=self::quantity($val);
                $getRecipe=MenusCatering::where(['id'=>$key])->select(['selling_price','porsi_standard'])->first();
                if($getRecipe){
                    $hasil = ($getRecipe->selling_price / $getRecipe->porsi_standard) * $val;
                    $updateData=[
                        'quantity'=>$val,
                        'price'=>$hasil,
                        'notes'=>$notesUpdate[$key]??''
                    ]; 
                    // $cek=OrderItems::where(['order_id'=>$requsdata['id'],'menus_catering_id'=>$key])->first();
                    // dd($cek->toArray(),$getRecipe->toArray());
                    OrderItems::where(['order_id'=>$requsdata['id'],'menus_catering_id'=>$key])->update($updateData);
                }
            }
        }
        $saveCost=[
            'order_id'=>$requsdata['id'],
            'estimated_cost'=>$requsdata['estimated_cost'],
            'estimated_selling_price'=>$requsdata['estimated_selling_price'],
            'estimated_margin'=>$requsdata['estimated_margin'],
            'desc'=>'',
            'verified_by'=>$userId,
            'cost_structure_id'=>$requsdata['cost_structure_id']!='-'?$requsdata['cost_structure_id']:null
        ];
        // dd($saveCost);
        $result=Orders::where(['id'=>$requsdata['id']])->update($saveData);
        $result=CostEstimations::updateOrCreate(['order_id'=>$requsdata['id']],$saveCost);
        if(request('structure')){
            $structure=request('structure');
            $refId=$result->id;
            CostEstimationDetail::where(['cost_estimation_id'=>$refId])->delete();
            $formatted = collect($structure)->map(function ($item) use ($refId) {
                $fixed=false;
                $fixed_price=false;
                $prosentase=false;
                if(in_array('fixed_price',array_keys($item))){
                    $fixed=$item['fixed_price']?true:false;
                    $fixed_price=str_replace(',', '.', str_replace('.', '', $item['fixed_price']));
                }
                if(in_array('prosentase',array_keys($item))){
                    $prosentase=str_replace(',', '.', $item['prosentase']);
                }
                return [
                    'id'=>(string) Str::ulid(),
                    'cost_estimation_id' => $refId,
                    'name'               => $item['name']??'-',
                    'fixed'              => $fixed,
                    'kategori'           => $item['kategori']?strtolower($item['kategori']) :null,
                    'prosentase'         => $prosentase?$prosentase: 0,
                    'prosentase_price'   => $item['prosentase_price']?? null,
                    'fixed_price'        => $fixed_price?$fixed_price:null,
                    'fixed_qty'          => $item['fixed_qty']?? null,
                ];
            })->toArray();
            CostEstimationDetail::insert($formatted);
        }
        $rincianOrder=$request->only('rincian');
        if(!in_array('rincian',array_keys($rincianOrder))){
            throw new Exception('rincian biaya masih kosong', 1);
        }
        $rincianOrder= collect($rincianOrder['rincian'])->whereNotNull('name')->whereNotNull('price');
        if($rincianOrder->isEmpty()){
            throw new Exception('rincian biaya masih kosong', 1);
        }
        $rincianNotExists=[];
        foreach($rincianOrder->toArray() as $key=>$rincian){
            if($rincian['name']){
                $qty=str_replace(',', '.', str_replace('.', '', $rincian['qty']??0));
                $price=str_replace(',', '.', str_replace('.', '', $rincian['price']??0));
                $rincianID=$key;
                if(!Str::isUlid($rincianID)){
                    $rincianID=(string) Str::ulid();
                }
                $saveRincian=[
                    'id'=>$rincianID,
                    'order_id'=>$requsdata['id'],
                    'quantity'=>(int) $qty,
                    'name'=>$rincian['name']??'',
                    'price'=>(float) $price
                ];
                $result=RincianBiaya::updateOrCreate(['order_id'=>$requsdata['id'],'id'=>$rincianID],$saveRincian);
                $rincianNotExists[]=$result->id;
            }
        }
        // dd(RincianBiaya::where(['order_id'=>$requsdata['id']])->get()->toArray(),$rincianNotExists);
        if($rincianNotExists&&count($rincianNotExists)>0){
            $cekOtherTodelete=RincianBiaya::where(['order_id'=>$requsdata['id']])
            ->whereNotIn('id',$rincianNotExists)
            ->forceDelete();
        }
        return $result;
    }
    private static function replacein($str){
        return str_replace(['.', ','], ['', '.'], $str);
    }
    private static function listSave(){
        return [
            'id',
            'estimated_cost',
            'estimated_margin',
            'estimated_selling_price',
            'cost_structure_id'
        ];
    }
}
