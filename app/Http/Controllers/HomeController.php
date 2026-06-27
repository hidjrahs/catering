<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use IconComponent;
    public function index(Request $request){
        return redirect()->route('customer_service');
        // direct sesuai role kecuali admin/owner
        $data=[];
        $config=[
            'title'=>'Halaman Dashboard',
            'title-content'=>'Halaman Dashboard',
            'title-icon'=>self::MenuList('data-dashboard','me-2'),
            'menu-sidebar'=>MenuRepository::getAllSideBar($request)
        ];
        return view('home.index',compact('config','data'));
    }
    public function refresh_csrf(Request $request){
        session()->regenerateToken();
        return response()->json(['token' => csrf_token()]);
    }
}
