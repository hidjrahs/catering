<?php

namespace App\Http\Controllers;

use App\Repository\CategoryMenuRepository;
use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

use App\Models\Orders;
use Exception;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class KitchenController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Kitchen Monitor',
            'title-content'=>'Kitchen Monitor',
            'title-icon'=>self::MenuList('kitchen','me-2'),
            'title-icon2'=>self::MenuList('dashboard','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'button-submit'=>'',
            'action'=>'',
            'method'=>'POST',
            'title-page-icon'=>'',
        ];
        $data['order_status']=config('option.order_status');
        return view('kitchen.index',compact('config','data'));
    }
    // public function test(Request $request){
    //     $refId="01kerstbrr2eqabqzxs5ybs3z2";
    //     $select=[
    //         'orders.id',
    //         'orders.order_ticket',
    //         'orders.event_date',
    //         'orders.created_at',
    //     ];
    //     $order=Orders::where(['orders.id'=>$refId])
    //         ->with([
    //             'refItem:id,order_id,menus_catering_id,custom_menu,quantity,price',
    //             'refItem.menu:id,name',
    //             'refItem.menu.menuingredients:id,menus_catering_id,ingredient_id,ingredient_label,quantity',
    //             'refItem.menu.menuingredients.ingredient:id,name,unit,satuan,default_price',
    //         ])
    //         ->select($select)->first();
    //     if(!$order){
    //         throw new Exception('Data Order tidak ditemukan.', 404);
    //     }
    //     // dd($order->toArray());
    //     $disk="report_kitchen";
    //     $storageReport = Storage::disk($disk);
    //     $reportname='cost_'.$refId.".pdf";
    //     if ($storageReport->exists($reportname)) {
    //         $storageReport->delete($reportname);
    //     }
    //     $order=$order->toArray();
    //     $order['event_date']=Carbon::parse($order['event_date'])->translatedFormat('d F Y H:i');
    //     $order['created_at']=Carbon::parse($order['created_at'])->translatedFormat('d F Y H:i');
    //     $qr = QrCode::size(50)
    //         ->eyeColor(0,1, 137, 115, 0, 0, 0)
    //         ->eyeColor(1,247, 41, 67, 0, 0, 0)
    //         ->generate('No Order:'.$refId);
    //     $title='Lila Catering';
    //     $data=$order;
    //     $billing=request('type');
    //     $qrcode=$qr;
    //     return view('export_pdf.kitchen_task',compact('data','title','billing','qrcode'));
    // }
}
