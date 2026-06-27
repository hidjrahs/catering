<?php

namespace App\Http\Controllers;

use App\Repository\CategoryMenuRepository;
use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class PurchasingController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Purchasing',
            'title-content'=>'Daftar Purchasing',
            'title-icon'=>self::MenuList('purchase','me-2'),
            'title-icon2'=>self::MenuList('dashboard','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'button-submit'=>'Proses Purchasing',
            'button-update'=>'Update Puchasing',
            'action'=>route('web.purchasing.store'),
            'method'=>'POST',
            'action_batch'=>route('web.purchasing.batch'),
            'method_batch'=>'POST',
            'title-page-icon'=>self::MenuList('purchase','me-2'),
        ];
        $data['order_status']=Collect(config('option.order_status'))->except(['pending', 'cancelled'])->toArray();
        // dd($data['order_status']);
        return view('purchasing.index',compact('config','data'));
    }
}
