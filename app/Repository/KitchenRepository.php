<?php

namespace App\Repository;

use App\Http\Resources\KitchenResource;
use App\Models\Orders;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class  KitchenRepository
{
    use IconComponent,FormatParse;
    public static function getallData($request)
    {
        $select=[
            'orders.id',
            'orders.delivery_date',
            'orders.order_ticket',
            'orders.event_date',
            'orders.desc',
            'orders.created_at',
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
            'orders.order_ticket',
            'orders.delivery_date',
            'orders.event_date',
        ];
        $result=Orders::where(['orders.id'=>$refId])
                ->with([
                    'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price',
                    'refItem.menu:id,name,porsi_standard,selling_price,category_menus_catering_id',
                    'refItem.menu.category:id,name',
                    'refItem.menu.menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
                    'refItem.menu.menuingredients.ingredient:id,name,unit,satuan,default_price',
                ])
                ->select($select)
                ->first();
        if(!$result){
            throw new Exception('Data Customer tidak ditemukan.', 404);
        }
        return new KitchenResource($result);
    }
    public static function export($refId,$request){
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
                'refItem.menu:id,name',
                'refItem.menu.menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
                'refItem.menu.menuingredients.ingredient:id,name,unit,satuan,default_price',
            ])
            ->select($select)->first();
        if(!$order){
            throw new Exception('Data Order tidak ditemukan.', 404);
        }
        // dd($order->toArray());
        $disk="report_kitchen";
        $storageReport = Storage::disk($disk);
        $reportname='cost_'.$refId.".pdf";
        if ($storageReport->exists($reportname)) {
            $storageReport->delete($reportname);
        }
        $order=$order->toArray();
        $tglevent=Carbon::parse($order['event_date']);
        $order['event_date']=Carbon::parse($order['event_date'])->translatedFormat('d F Y H:i');
        $order['created_at']=Carbon::parse($order['created_at'])->translatedFormat('d F Y H:i');
        $order['tgl_event']=$tglevent->locale('id')->translatedFormat('l, d F Y');
        $data=[
            'title' => 'Lila Catering',
            'data' => $order,
        ];
        $pdf = Pdf::loadView('export_pdf.kitchen_task', $data);
        $storageReport->put($reportname, $pdf->output());
        if(!$storageReport){
            throw new Exception("failed generate PDF", 1);
        }
        return url('storage/report_kitchen/'.$reportname);
    }
}
