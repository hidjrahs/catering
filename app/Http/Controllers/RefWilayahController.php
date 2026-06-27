<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class RefWilayahController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Ref. Wilayah',
            'title-content'=>'Daftar Referensi Wilayah',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->MenuList('data-master','me-2'),
            'action'=>route('web.ref_wilayah.store'),
            'method'=>'POST',
            // 'button-submit'=>'Simpan Referensi Wilayah',
            // 'button-submit-wait'=>'Proses Simpan Referensi Wilayah',
            // 'action-update'=>route('web.employes.store'),
            // 'method-update'=>'PUT',
            // 'button-update'=>'Update Referensi Wilayah',
            // 'button-update-wait'=>'Proses Update Referensi Wilayah',
        ];
        return view('ref_wilayah.index',compact('config','data'));
    }
}
