<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Customer/Pelanggan',
            'title-content'=>'Daftar Customer/Pelanggan',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.customers.store'),
            'method'=>'POST',
            'button-submit'=>'Simpan Data Customer',
            'button-submit-wait'=>'Proses Simpan Data Customer',
            'action-update'=>route('web.customers.store'),
            'method-update'=>'PUT',
            'button-update'=>'Update Data Customer',
            'button-update-wait'=>'Proses Update Data Customer',
        ];
        return view('customers.index',compact('config','data'));
    }
}
