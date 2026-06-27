<?php

namespace App\Repository;

use App\Http\Resources\CostControlingResource;
use App\Http\Resources\PurchasingResource;
use App\Models\Orders;
use App\Models\Purchases;
use App\Models\PurchasesItems;
use App\Models\Suppliers;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class  PurchasingRepository
{
    use IconComponent,FormatParse;
    public static function getallDataBatch($request)
    {
        $result=[];
        if(request('batch_order')&&request('batch_range')){
            $batch_range=explode(' - ',request('batch_range'));
            $order=request('batch_order')=='event'?'event_date':'created_at';
            if(count($batch_range)===2){
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
                ->leftjoin('users','users.id','=','orders.created_by')
                ->whereBetween('orders.created_at', [$batch_range[0] . ' 00:00:00',$batch_range[1] . ' 23:59:59'])
                ->whereNotIn('orders.status',['pending','cancelled'])
                ->where(['orders.status'=>'purchased'])
                ->select($select)
                ->orderBy('orders.'.$order,'DESC')
                ->orderBy('customers.name','ASC');
                // dd($batch_range);
            }
        }
        if(in_array(request('device'),['web','stealth'])){
            return self::datatableWeb($result);
        }
        return self::datatableMobile($result);   
    }
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
                ->whereNotIn('orders.status',['pending','cancelled'])
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
                        return $data->estimate_price?self::parseQuantity($data->estimate_price):'-';
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
    public static function store($request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $saveData['user_id']=Auth::check() ? Auth::id() : 1;
        $saveData['order_id']=$request->get('id',true);
        $result=Purchases::create($saveData);
        Orders::where(['id'=>$saveData['order_id']])->update(['status'=>'purchased']);
        $itemMenu=$request->only('ingredients');
        if(!in_array('ingredients',array_keys($itemMenu))){
            throw new Exception('Barang/Bahan Baku masih kosong', 1);
        }
        // dd(['sas']);
        $listSupplier=collect($itemMenu['ingredients'])
            ->pluck('supplier_id')
            ->filter()
            ->unique()
            ->filter(function ($id) {
                return strlen($id) !== 26; 
            })
            ->values()
            ->toArray();
        foreach($itemMenu['ingredients'] as $key=>$item){
            $supplier=null;
            if(in_array('supplier_id',array_keys($item))){
                $supplier=$item['supplier_id'];
                if(in_array($item['supplier_id'],$listSupplier)){
                    $saveSupplier=Suppliers::create(['name'=>$item['supplier_id']]);
                    $supplier=$saveSupplier->id;
                }
            }
            $saveItem=[
                'purchase_id'=>$result->id,
                'ingredient_id'=>$key,
                'quantity'=>$item['quantity'],
                'price'=>$item['price'],
                'supplier_id'=>$supplier
            ];
            // dd($saveItem);
            PurchasesItems::create($saveItem);
        }
        return $result;
    }
    public static function update($refId,$request){
        $listSave=self::listSave();
        $saveData=$request->only($listSave);
        $saveData['user_id']=Auth::check() ? Auth::id() : 1;
        $saveData['order_id']=$request->get('id',true);
        $result=Purchases::where(['id'=>$refId])->update($saveData);
        $itemMenu=$request->only('ingredients');
        if(!in_array('ingredients',array_keys($itemMenu))){
            throw new Exception('Barang/Bahan Baku masih kosong', 1);
        }
        // dd($itemMenu['ingredients']);
        $listSupplier=collect($itemMenu['ingredients'])
            ->pluck('supplier_id')
            ->filter()
            ->unique()
            ->filter(function ($id) {
                return strlen($id) !== 26; 
            })
            ->values()
            ->toArray();
        PurchasesItems::where(['purchase_id'=>$refId])->delete();
        foreach($itemMenu['ingredients'] as $key=>$item){
            $supplier=null;
            if(in_array('supplier_id',array_keys($item))){
                $supplier=$item['supplier_id'];
                if(in_array($item['supplier_id'],$listSupplier)){
                    $saveSupplier=Suppliers::create(['name'=>$item['supplier_id']]);
                    $supplier=$saveSupplier->id;
                }
            }
            $saveItem=[
                'purchase_id'=>$refId,
                'ingredient_id'=>$key,
                'quantity'=>$item['quantity'],
                'price'=>$item['price'],
                'supplier_id'=>$supplier
            ];
            // dd($saveItem);
            PurchasesItems::create($saveItem);
        }
        return $result;
    }
    public static function exportBatch($request){
        if(request('batch_order')&&request('batch_range')){
            $batch_range=explode(' - ',request('batch_range'));
            $order=request('batch_order')=='event'?'event_date':'created_at';
            if(count($batch_range)===2){
                $select=[
                    'orders.id',
                    'customers.name',
                    'customers.phone',
                    'customers.gender',
                    'orders.order_ticket',
                ];
                $resultData=Orders::with([
                    // 'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price,notes',
                    // 'refItem.menu:id,name,porsi_standard,selling_price,category_menus_catering_id',
                    // 'refItem.menu.category:id,name',
                    // 'refItem.menu.menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
                    // 'refItem.menu.menuingredients.ingredient:id,name,unit,satuan,default_price',
                    'purchases:id,order_id,purchase_date',
                    'purchases.item:id,purchase_id,ingredient_id,supplier_id,quantity,price',
                    'purchases.item.suppliers:id,name,phone,address,penanggung_jawab',
                    'purchases.item.ingredient:id,name,unit,satuan,default_price'
                ])
                ->leftjoin('customers','customers.id','=','orders.customer_id')
                ->whereBetween('orders.created_at', [$batch_range[0] . ' 00:00:00',$batch_range[1] . ' 23:59:59'])
                ->whereNotIn('orders.status',['pending','cancelled'])
                ->where(['orders.status'=>'purchased'])
                ->select($select)
                ->orderBy('orders.'.$order,'DESC')
                ->orderBy('customers.name','ASC')
                ->get();
                $ingredients = collect($resultData->toArray())
                    ->pluck('purchases.item')
                    ->flatten(1);
                $order = $ingredients->groupBy('suppliers.id')
                    ->map(function ($supplierGroup) {
                        $supplier = $supplierGroup->first()['suppliers'];
                        return [
                            'supplier_id' => data_get($supplier, 'id'),
                            'supplier' => $supplier,
                            'duplicates' => $supplierGroup->groupBy('ingredient_id')
                                            // ->filter(function ($ingredientGroup) {return $ingredientGroup->count() > 1;})
                                            ->map(function ($ingredientGroup) {
                                                $first = $ingredientGroup->first();
                                                return [
                                                    'ingredient_id' => $first['ingredient_id'],
                                                    'ingredient' => $first['ingredient'],
                                                    'quantity' => $ingredientGroup->sum(function ($item) {
                                                        return (float) $item['quantity'];
                                                    }),
                                                    'price' => $ingredientGroup->sum(function ($item) {
                                                        return (float) $item['price'];
                                                    }),
                                                    'duplicate_count' => $ingredientGroup->count(),
                                                    'items' => $ingredientGroup->values()->toArray(),
                                                ];
                                            })->values()->toArray(),
                        ];
                    })->values()->toArray();
                    // ->filter(function ($supplier) {return count($supplier['duplicates']) > 0;})
                // dd($order);
                $disk="report_purchasing";
                $storageReport = Storage::disk($disk);
                $rangeName=str_replace(' ','_',request('batch_range'));
                $reportname='batch_'.$rangeName.".pdf";
                if ($storageReport->exists($reportname)) {
                    $storageReport->delete($reportname);
                }
                $data=[
                    'title' => 'Lila Catering',
                    'data' => $order,
                    'range'=>$rangeName,
                ];
                $pdf = Pdf::loadView('export_pdf.purchasing_batch', $data);
                $storageReport->put($reportname, $pdf->output());
                if(!$storageReport){
                    throw new Exception("failed generate PDF", 1);
                }
                return url('storage/report_purchasing/'.$reportname);
            }
        }
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
                'purchases:id,order_id,purchase_date',
                'purchases.item:id,purchase_id,ingredient_id,supplier_id,quantity,price',
                'purchases.item.ingredient:id,name,unit,default_price',
                'purchases.item.suppliers:id,name,phone,address,penanggung_jawab',
            ])
            ->select($select)->first();
        if(!$order){
            throw new Exception('Data Order tidak ditemukan.', 404);
        }
        // dd($order->toArray());
        $disk="report_purchasing";
        $storageReport = Storage::disk($disk);
        $reportname='purchase_'.$refId.".pdf";
        if ($storageReport->exists($reportname)) {
            // return url('storage/report_purchasing/'.$reportname);
            $storageReport->delete($reportname);
        }
        $order=$order->toArray();
        $tglevent=Carbon::parse($order['event_date']);
        $order['tgl_event']=$tglevent->locale('id')->translatedFormat('l, d F Y');
        $order['delivery_date']=Carbon::parse($order['delivery_date'])->translatedFormat('H:i d F Y');
        $order['order_ticket']=$order['order_ticket'];
        $order['purchases']=collect($order['purchases']['item'])
            ->groupBy('supplier_id')
            ->map(function ($items) {
                return [
                    'supplier' => $items->first()['suppliers'],
                    'item' => $items->values()
                ];
            })
            ->toArray();
        // dd($order);
        $qr = QrCode::size(50)
            ->eyeColor(0,1, 137, 115, 0, 0, 0)
            ->eyeColor(1,247, 41, 67, 0, 0, 0)
            ->generate('No Order:'.$refId);
        $data=[
            'title' => 'Lila Catering',
            'data' => $order,
            'billing'=>request('type'),
            'qrcode'=>$qr
        ];
        $pdf = Pdf::loadView('export_pdf.purchasing', $data);
        $storageReport->put($reportname, $pdf->output());
        if(!$storageReport){
            throw new Exception("failed generate PDF", 1);
        }
        return url('storage/report_purchasing/'.$reportname);
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
            'orders.status',
            'orders.desc',
            'orders.event_type',
            'orders.package_type',
            'orders.venue'
        ];
        $result=Orders::where(['orders.id'=>$refId])
                ->with([
                    'customer:id,name,phone,address,vilage_id',
                    'customer.vilage',
                    'customer.vilage.district',
                    'customer.vilage.district.city',
                    'customer.vilage.district.city.province',
                    'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price,notes',
                    'refItem.menu:id,name,porsi_standard,selling_price,category_menus_catering_id',
                    'refItem.menu.category:id,name',
                    'refItem.menu.menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
                    'refItem.menu.menuingredients.ingredient:id,name,unit,satuan,default_price',
                    'refItem.menu.menuingredients.ingredient.ref_supplier:ingredient_id,supplier_id',
                    'refItem.menu.menuingredients.ingredient.ref_supplier.supplier:id,name,phone',
                    'purchases:id,order_id,purchase_date',
                    'purchases.item:id,purchase_id,ingredient_id,supplier_id',
                    'purchases.item.suppliers:id,name,phone',
                ])
                ->select($select)
                ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        // dd($result->toArray());
        return new PurchasingResource($result);
    }
    private static function listSave(){
        return [
            'order_id',
            'user_id',
            'purchase_date'
        ];
    }
}
