<?php

namespace App\Http\Controllers;

use App\Repository\CategoryMenuRepository;
use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class ManagementStokController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Manajement Stok',
            'title-content'=>'Daftar Manajement Stok',
            'title-icon'=>self::MenuList('stok','me-2'),
            'title-icon2'=>self::MenuList('dashboard','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'button-submit'=>'',
            'action'=>'',
            'method'=>'POST',
            'title-page-icon'=>'',
        ];
        return view('management_stok.index',compact('config','data'));
    }
}
