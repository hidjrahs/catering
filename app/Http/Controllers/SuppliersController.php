<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Supplier/Pemasok',
            'title-content'=>'Daftar Supplier/Pemasok',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.suppliers.store'),
            'method'=>'POST',
            'button-submit'=>'Simpan Data Supplier',
            'button-submit-wait'=>'Proses Simpan Data Supplier',
            'action-update'=>route('web.suppliers.store'),
            'method-update'=>'PUT',
            'button-update'=>'Update Data Supplier',
            'button-update-wait'=>'Proses Update Data Supplier',
        ];
        return view('suppliers.index',compact('config','data'));
    }
}
