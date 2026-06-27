<?php

namespace App\Http\Controllers;

use App\Repository\CategoryMenuRepository;
use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class CostControlingController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Cost Controling',
            'title-content'=>'Cost Controling Order',
            'title-icon'=>self::MenuList('finance','me-2'),
            'title-icon2'=>self::MenuList('dashboard','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'button-submit'=>'Proses Verifikasi Order',
            'button-update'=>'Update Cost Order',
            'action'=>route('web.cost_controling.verify'),
            'method'=>'POST',
            'title-page-icon'=>self::MenuList('finance','me-2'),
        ];
        $data['order_status']=config('option.order_status');
        // dd($data['order_status']);
        return view('cost_controling.index',compact('config','data'));
    }
}
