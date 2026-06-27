<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request){
        if (Auth::check()) {
            return redirect('home');
        }else{
            $config=['title'=>'Sign In','loginApi'=>route('web.login')."?type=web"];
            $data=['method'=>'POST'];
            return view('authentication.sign-in',compact('config','data'));
            // return view('authentication.login-in',compact('config','data'));
        }
    }
}
