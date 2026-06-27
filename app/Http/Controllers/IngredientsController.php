<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Data Barang/Bahan Baku',
            'title-content'=>'Daftar Barang/Bahan Baku',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.ingredients.store'),
            'method'=>'POST',
            'button-submit'=>'Simpan Data Bahan',
            'button-submit-wait'=>'Proses Simpan Data Bahan',
            'action-update'=>route('web.ingredients.store'),
            'method-update'=>'PUT',
            'button-update'=>'Update Data Bahan',
            'button-update-wait'=>'Proses Update Data Bahan',
        ];
        $data['satuan']=config('option.units_default');
        return view('ingredients.index',compact('config','data'));
    }
}
