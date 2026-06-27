<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class PacketMenuController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Paket Menu',
            'title-content'=>'Daftar Paket Menu',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.packet_menus.store'),
            'method'=>'POST',
            'button-submit'=>'Simpan Paket Menu',
            'button-submit-wait'=>'Proses Simpan Paket Menu',
            'action-update'=>route('web.packet_menus.store'),
            'method-update'=>'PUT',
            'button-update'=>'Update Paket Menu',
            'button-update-wait'=>'Proses Update Paket Menu',
        ];
        return view('packet_menus.index',compact('config','data'));
    }
}
