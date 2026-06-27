<?php

namespace App\Http\Controllers;

use App\Repository\CategoryMenuRepository;
use App\Repository\CustomerServicesRepository;
use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;


class CustomerServicesController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Customer Service',
            'title-content'=>'Menu Customer Service',
            'title-icon'=>self::MenuList('cart','me-2'),
            'title-icon2'=>self::MenuList('dashboard','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'button-submit'=>'Proses Event Order',
            'action'=>route('web.customer_service.store'),
            'method'=>'POST',
            'title-page-icon'=>self::MenuList('cart','me-2'),
        ];
        // dd($config['menu-sidebar']);
        $data['category']=CategoryMenuRepository::getAllCategory($request,false);
        $data['menuCatering']=[];
        $data['category_total'] = collect($data['category'])->sum(fn ($item) => (int)($item['active_menus_count'] ?? 0));
        // dd($data['category'],$data['category_total']);
        $data['package_type']=config('option.package_type');
        $data['event_type']=config('option.event_type');
        return view('customer_service.index',compact('config','data'));
    }

    public function index_v2(Request $request){
        $data=[];
        $data['category']=CategoryMenuRepository::getAllCategory($request,false);
        $data['package_type']=config('option.package_type');
        $data['event_type']=config('option.event_type');
        return view('customer_service_v2.index', compact('data'));
    }

    // public function cek_export(Request $request){
    //     // $title='Lila Catering';
    //     // $body='Ini contoh PDF di Laravel 12.';
    //     // return view('export_pdf.contoh',compact('title','body'));
    //    return CustomerServicesRepository::export('1',$request);
    // }
    public function list_orders(Request $request){
        $data=[];
        $config=[
            'title'=>'CS - Daftar Order',
            'title-content'=>'Riwayat Event Order',
            'title-icon'=>self::MenuList('cart','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'button-submit'=>'Update Event Order',
            'action'=>'',
            'method'=>'POST',
            'title-page-icon'=>self::MenuList('cart','me-2'),
        ];
        $data['package_type']=config('option.package_type');
        $data['event_type']=config('option.event_type');
        return view('customer_service.list_orders',compact('config','data'));
    }
}
