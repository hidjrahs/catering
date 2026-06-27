<?php

namespace App\Repository;

use App\Http\Resources\CostControlingResource;
use App\Models\Orders;
use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class  ManagementStokRepository
{
    use IconComponent,FormatParse;
    public static function getallData($request)
    {
        $result=[];
        // $select=[
        //     'orders.id',
        //     'customers.name',
        //     'customers.phone',
        //     'customers.gender',
        //     'orders.delivery_date',
        //     'orders.order_ticket',
        //     'orders.event_date',
        //     'orders.total_guest',
        //     'orders.estimate_price',
        //     'orders.status',
        //     'orders.desc',
        //     'orders.created_at',
        //     DB::raw('users.name cs_name')
        // ];
        // $result=Orders::with(['refItem:id,order_id,price'])
        //         ->whereNotIn('orders.status',['pending','cancelled'])
        //         ->leftjoin('customers','customers.id','=','orders.customer_id')
        //         ->leftjoin('users','users.id','=','orders.created_by');
        // $result->select($select);
        // if(request('search')){
        //     $lowerSearch=strtolower(request('search'));
        //     $where="(lower(customers.name) like '%".$lowerSearch."%'
        //         or lower(customers.phone) like '%".$lowerSearch."%')";
        //     $result->whereRaw($where);
        // }
        // if(request('status')&&request('status')!='-'){
        //     $result->where(['orders.status'=>strtolower(request('status'))]);
        // }
        // if(request('orders')){
        //     $result->orderBy(request('orders'),request('order_option'));
        //     if(request('orders')!='customers.name'){
        //         $result->orderBy('customers.name','ASC');
        //     }
        // }else{
        //     $result->orderBy('orders.created_at','DESC')->orderBy('customers.name','ASC');
        // }
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
    
}
