<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class EmployesController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        $data=[];
        $config=[
            'title'=>'Karyawan',
            'title-content'=>'Daftar Karyawan',
            'title-icon'=>self::MenuList('data-master','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request),
            'title-page-icon'=>$this->generateList('edit-baru'),
            'action'=>route('web.employes.store'),
            'method'=>'POST',
            'button-submit'=>'Simpan Data Karyawan',
            'button-submit-wait'=>'Proses Simpan Data Karyawan',
            'action-update'=>route('web.employes.store'),
            'method-update'=>'PUT',
            'button-update'=>'Update Data Karyawan',
            'button-update-wait'=>'Proses Update Data Karyawan',
        ];
        $data['education_level']=config('option.education_level');
        return view('employes.index',compact('config','data'));
    }
}
