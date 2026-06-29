<?php

namespace App\Http\Controllers;

use App\Exports\RecipeFormatExport;
use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class MenuCateringController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Menu',
            'title-content'=>'Daftar Menu',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.menus_catering.store'),
            'method'=>'POST',
            'button-submit'=>'Simpan Menu',
            'button-submit-wait'=>'Proses Simpan Menu',
            'action-update'=>route('web.menus_catering.store'),
            'method-update'=>'PUT',
            'button-update'=>'Update Menu',
            'button-update-wait'=>'Proses Update Menu',
        ];
        return view('menus_catering.index',compact('config','data'));
    }
    public function import(Request $request){
        $data=[];
        $config=[
            'title'=>'Import Resep Menu',
            'title-content'=>'Preview Excel Resep Menu',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.menus_catering.store_batch'),
            'method'=>'POST',
            'button-submit'=>'Upload Excel',
            'download-template'=>route('menus_catering.generate'),
            // 'download-template'=>route('web.menus_catering.generate'),
            'button-submit-wait'=>'Proses Import Excel Resep',
            'action-import'=>route('web.menus_catering.recipe'),
            'button-import'=>'Import Preview Excel',
            // 'action-update'=>route('web.menus_catering.store'),
            // 'method-update'=>'PUT',
            // 'button-update'=>'Update Menu',
            // 'button-update-wait'=>'Proses Update Menu',
        ];
        return view('menus_catering.import',compact('config','data'));
    }

    public function generate(Request $request){
        return Excel::download(new RecipeFormatExport, 'template_resep.xlsx');
    }
}
