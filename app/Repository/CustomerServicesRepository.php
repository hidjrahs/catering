<?php

namespace App\Repository;

use App\Http\Resources\CustomerServiceResource;
use App\Models\Customers;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\PacketCatering;
use App\Models\RincianBiaya;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class  CustomerServicesRepository
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
            'orders.total_invite',
            'orders.status',
            'orders.estimate_price',
            'orders.desc',
            'orders.event_type',
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
                    ->editColumn('event_type', function ($data) {
                        return implode(', ', array_map('ucfirst', explode(',', $data->event_type)));
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
                    ->editColumn('total_invite', function ($data) {
                        return $data->total_invite?self::parseQuantity($data->total_invite):'-';
                    })
                    ->addColumn('total_items', function ($data) {
                        return $data->refItem->count(); 
                    })
                    ->addColumn('est_price', function ($data) {
                        return self::numberformat($data->estimate_price);
                    })
                    ->rawColumns(['created_at','delivery_date','event_date','estimate_price'])
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
            'orders.event_time',
            'orders.total_guest',
            'orders.total_invite',
            'orders.desc',
            'orders.desc_extra',
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
                    'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price',
                    'refItem.menu:id,name,porsi_standard,selling_price,category_menus_catering_id',
                    'refItem.menu.category:id,name,is_quantity',
                    'rincianbiaya:id,order_id,name,quantity,price'
                ])
                ->select($select)
                ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        return new CustomerServiceResource($result);
    }
    public static function exportrincian($refId,$request){
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
            'orders.dp'
        ];
        $order=Orders::where(['orders.id'=>$refId])
            ->with([
                'petugas:id,name',
                'customer:id,name,phone,address,vilage_id',
                'customer.vilage:id,district_id,name',
                'customer.vilage.district:id,city_id,name',
                'customer.vilage.district.city:id,province_id,name',
                'customer.vilage.district.city.province:id,name',
                'rincianbiaya:id,order_id,name,quantity,price'
            ])
            ->select($select)->first();
        if(!$order){
            throw new Exception('Data Order tidak ditemukan.', 404);
        }
        // dd($order->toArray());
        $disk="report_order";
        $storageReport = Storage::disk($disk);
        $reportname='rincian_'.$refId.".pdf";
        if ($storageReport->exists($reportname)) {
            $storageReport->delete($reportname);
        }
        $order=$order->toArray();
        $tglevent=Carbon::parse($order['event_date']);
        $order['event_date']=Carbon::parse($order['event_date'])->translatedFormat('d F Y H:i');
        $order['created_at']=Carbon::parse($order['created_at'])->translatedFormat('d F Y H:i');
        // $order['delivery_date']=Carbon::parse($order['delivery_date'])->translatedFormat('H:i d F Y');
        $order['tgl_event']=$tglevent->locale('id')->translatedFormat('l, d F Y');
        $data=[
            'title' => 'Lila Catering',
            'data' => $order,
            'billing'=>request('type')
        ];
        $pdf = Pdf::loadView('export_pdf.rincian_biaya', $data);
        $storageReport->put($reportname, $pdf->output());
        if(!$storageReport){
            throw new Exception("failed generate PDF", 1);
        }
        return url('storage/report_order/'.$reportname);
    }
    public static function export($refId,$request){
        $select=[
            'orders.id',
            'orders.customer_id',
            'orders.order_ticket',
            'orders.estimate_price',
            'orders.delivery_date',
            'orders.event_date',
            'orders.event_time',
            'orders.total_guest',
            'orders.total_invite',
            'orders.desc',
            'orders.desc_extra',
            'orders.event_type',
            'orders.package_type',
            'orders.venue',
            'orders.created_by',
            'orders.created_at',
            'orders.updated_at',
        ];
        $order=Orders::where(['orders.id'=>$refId])
            ->with([
                'petugas:id,name',
                'customer:id,name,phone,address,vilage_id',
                'customer.vilage:id,district_id,name',
                'customer.vilage.district:id,city_id,name',
                'customer.vilage.district.city:id,province_id,name',
                'customer.vilage.district.city.province:id,name',
                'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price',
                'refItem.menu:id,name,porsi_standard,selling_price,category_menus_catering_id',
                'refItem.menu.category:id,name',
            ])
            ->select($select)->first();
        if(!$order){
            throw new Exception('Data Order tidak ditemukan.', 404);
        }
        // dd($order->toArray());
        $disk="report_order";
        $storageReport = Storage::disk($disk);
        $reportname='order_'.$refId.".pdf";
        if ($storageReport->exists($reportname)) {
            // return url('storage/report_order/'.$reportname);
            $storageReport->delete($reportname);
        }
        $order=$order->toArray();
        $order['ref_item']=collect($order['ref_item'])->groupBy('menu.category.name')->toArray();
        // dd($order['ref_item']);
        // $grouped = collect($data['ref_item'])
        //         ->groupBy('menu.category.name');
        // $order['event_date']=Carbon::parse($order['event_date'])->translatedFormat('d F Y H:i');
        // $order['created_at']=Carbon::parse($order['created_at'])->translatedFormat('d F Y H:i');
        $tglindo=Carbon::parse($order['updated_at']);
        // $tglindo=Carbon::parse($order['created_at']);
        $tglevent=Carbon::parse($order['event_date']);
        $timevent=Carbon::parse($order['event_time']);
        $order['tgl_indo']=$tglindo->locale('id')->translatedFormat('l, d F Y');
        $order['tgl_event']=$tglevent->locale('id')->translatedFormat('l, d F Y');
        $order['keterangan']='';
        $order['event_time']=$timevent->locale('id')->translatedFormat('H:i');
        
        if($order['package_type']){
            // $order['package_type']
            $list=explode(',',$order['package_type']);
            $packet=PacketCatering::select(['name'])->pluck('name')->toArray();
            $dataA=collect($list)->diff($packet)->values()->toArray();
            $dataB = collect($list)->intersect($packet)->values()->toArray();
            $order['package_type']=join(',',$dataA);
            $order['keterangan']=join(',',$dataB);
        }
        // dd($order);
        // $order['delivery_date']=Carbon::parse($order['delivery_date'])->translatedFormat('H:i d F Y');
        $qr = QrCode::size(50)
            ->eyeColor(0,1, 137, 115, 0, 0, 0)
            ->eyeColor(1,247, 41, 67, 0, 0, 0)
            ->generate('No Order:'.$refId);
            // ->geo('-7.811685782362479','112.06533428280544'); 
        $data=[
            'title' => 'Lila Catering',
            'data' => $order,
            'billing'=>request('type'),
            'qrcode'=>$qr,
            'internal'=>request('internal')??false
        ];
        $pdf = Pdf::loadView('export_pdf.customer_services', $data);
        $storageReport->put($reportname, $pdf->output());
        if(!$storageReport){
            throw new Exception("failed generate PDF", 1);
        }
        return url('storage/report_order/'.$reportname);
    }
    public static function search($request){
        $lowerSearch=strtolower(request('q'));
        $where="(lower(customers.name) like '%".$lowerSearch."%'
                or lower(customers.phone) like '%".$lowerSearch."%'
                or lower(customers.address) like '%".$lowerSearch."%')";
        $q = $request->get('q');
        $result = Customers::query()
            ->when($q, fn($query) => $query->whereRaw($where))
            ->select(['id','name','address','phone'])
            ->limit(20)
            ->get();
        return $result;
    }
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $cekCustomer=Customers::where(['id'=>$saveData['customer_id']])->select(['id'])->first();
        if(!$cekCustomer){
            $customers=[
                'name'=>$saveData['customer_id'],
                'phone'=>request('phone')??'-',
                'vilage_id'=>request('vilage_id')??null,
                'address'=>request('address')??null
            ];
            $saveCustomer=Customers::create($customers);
            $saveData['customer_id']=$saveCustomer->id;
        }
        if(in_array('event_type',array_keys($saveData))){
            $saveData['event_type']=join(',',$saveData['event_type']);
        }
        if(in_array('package_type',array_keys($saveData))){
            $saveData['package_type']=join(',',$saveData['package_type']);
        }
        $saveData['total_guest']=self::quantity($saveData['total_guest']);
        $saveData['total_invite']=self::quantity($saveData['total_invite']??0);
        $saveData['estimate_price']=$saveData['estimate_price'];
        $saveData['dp']=str_replace(',', '.', str_replace('.', '', $saveData['dp']??0));
        // dd($saveData);
        $result=Orders::create($saveData);
        $itemOrder=$request->only('item');
        if(!in_array('item',array_keys($itemOrder))){
            throw new Exception('order menu masih kosong', 1);
        }
        foreach($itemOrder['item'] as $item){
            $price = $item['price'];
            $porsi_standard = self::quantity($item['porsi_standard']);
            $quantity = self::quantity($item['quantity']);
            if($item['is_request']!='1'){
                $quantity=$saveData['total_guest'];
            }
            $hasil = ($price / $porsi_standard) * $quantity;
            $saveItem=[
                'order_id'=>$result->id,
                'menus_catering_id'=>$item['menus_catering_id'],
                'quantity'=>$quantity,
                'price'=>$hasil,
                'notes'=>$item['notes']??'',
            ];
            OrderItems::create($saveItem);
        }
        $rincianOrder=$request->only('rincian');
        if(!in_array('rincian',array_keys($rincianOrder))){
            throw new Exception('rincian biaya masih kosong', 1);
        }
        $rincianOrder= collect($rincianOrder['rincian'])->whereNotNull('name')->whereNotNull('price');
        if($rincianOrder->isEmpty()){
            throw new Exception('rincian biaya masih kosong', 1);
        }
        foreach($rincianOrder->toArray() as $rincian){
            if($rincian['name']){
                $qty=str_replace(',', '.', str_replace('.', '', $rincian['qty']??0));
                $price=str_replace(',', '.', str_replace('.', '', $rincian['price']??0));
                $saveRincian=[
                    'order_id'=>$result->id,
                    'quantity'=>(int) $qty,
                    'name'=>$rincian['name']??'',
                    'price'=>(float) $price
                ];
                RincianBiaya::create($saveRincian);
            }
            
        }
        // dd(['zzzz',$rincianOrder]);
        $result['export']=self::export($result->id,$request);
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $cekCustomer=Customers::where(['id'=>$saveData['customer_id']])->select(['id'])->first();
        if(!$cekCustomer){
            $customers=[
                'name'=>$saveData['customer_id'],
                'phone'=>request('phone')??'-',
                'vilage_id'=>request('vilage_id')??null,
                'address'=>request('address')??null
            ];
            $saveCustomer=Customers::create($customers);
            $saveData['customer_id']=$saveCustomer->id;
        }
        if(in_array('event_type',array_keys($saveData))){
            $saveData['event_type']=join(',',$saveData['event_type']);
        }
        if(in_array('package_type',array_keys($saveData))){
            $saveData['package_type']=join(',',$saveData['package_type']);
        }
        $saveData['total_guest']=self::quantity($saveData['total_guest']);
        $saveData['total_invite']=self::quantity($saveData['total_invite']??0);
        $saveData['dp']=str_replace(',', '.', str_replace('.', '', $saveData['dp']??0));
        $result=Orders::where(['id'=>$refId])->update($saveData);        
        $itemOrder=$request->only('item');
        if(!in_array('item',array_keys($itemOrder))){
            throw new Exception('order menu masih kosong', 1);
        }
        $itemNotExists=[];
        foreach($itemOrder['item'] as $item){
            $price = self::quantity($item['price']);
            $porsi_standard = self::quantity($item['porsi_standard']);
            $quantity = self::quantity($item['quantity']);
            if($item['is_request']!='1'){
                $quantity=$saveData['total_guest'];
            }
            $hasil = ($price / $porsi_standard) * $quantity;
            $saveItem=[
                'order_id'=>$refId,
                'menus_catering_id'=>$item['menus_catering_id'],
                'quantity'=>$quantity,
                'price'=>$hasil,
                'notes'=>$item['notes']??'',
            ];
            $itemNotExists[]=$item['menus_catering_id'];
            OrderItems::updateOrCreate(['order_id'=>$refId,'menus_catering_id'=>$item['menus_catering_id']],$saveItem);
        }
        if($itemNotExists&&count($itemNotExists)>0){
            $cekOtherTodelete=OrderItems::where(['order_id'=>$refId])
            ->whereNotIn('menus_catering_id',$itemNotExists)
            ->delete();
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
                    'order_id'=>$refId,
                    'quantity'=>(int) $qty,
                    'name'=>$rincian['name']??'',
                    'price'=>(float) $price
                ];
                $rincianNotExists[]=$rincianID;
                RincianBiaya::updateOrCreate(['order_id'=>$refId,'id'=>$rincianID],$saveRincian);
            }
        }
        if($rincianNotExists&&count($rincianNotExists)>0){
            $cekOtherTodelete=RincianBiaya::where(['order_id'=>$refId])
            ->whereNotIn('id',$rincianNotExists)
            ->forceDelete();
        }
        return $result;
    }
    public static function delete($request){
        $refId=$request->only('data');
        $userId = Auth::check() ? Auth::id() : 1;
        $OrderData=Orders::whereIn('id',$refId['data']);
        $OrderData->update(['deleted_by'=>$userId]);
        $result=$OrderData->delete();
        return $result;
    }
    private static function listSave(){
        return [
            'customer_id',
            'estimate_price',
            'delivery_date',
            'event_date',
            'event_time',
            'total_guest',
            'status',
            'desc',
            'desc_extra',
            'event_type',
            'package_type',
            'venue',
            'total_invite',
            'dp'
        ];
    }
}
