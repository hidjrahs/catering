<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class CategoryMenuController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Kategori Menu',
            'title-content'=>'Daftar Kategori Menu',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.category_menus.store'),
            'method'=>'POST',
            'button-submit'=>'Simpan Kategori Menu',
            'button-submit-wait'=>'Proses Simpan Kategori Menu',
            'action-update'=>route('web.category_menus.store'),
            'method-update'=>'PUT',
            'button-update'=>'Update Kategori Menu',
            'button-update-wait'=>'Proses Update Kategori Menu',
        ];
        return view('category_menus.index',compact('config','data'));
    }
}
