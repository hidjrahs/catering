<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'email' => 'required|string',
                'password' => 'required|string'
            ]);
            if($validator->fails()){
                return  $this->errorResponse('Failed Request',false,$validator->errors()->all(),422);
            }
            $credentials = $validator->validated();
            $auth=Auth()->guard('web');
            if(!$token =$auth->attempt($credentials)) {
                return  $this->errorResponse('Email/Username dan Password tidak sesuai.',false,[],401);
            }
            $request->session()->regenerate();
            // Auth::logoutOtherDevices($credentials['password']);
            DB::table('sessions')
                ->where('user_id', auth()->id())
                ->where('id', '!=', session()->getId())
                ->delete();
            $data=['direct'=>route('home')];
            return  $this->successResponse('Proses Sign in berhasil.',$data);
        } catch (\Throwable $th) {
            self::logout($request);
            Log::error($th);
            return  $this->errorResponse($th->getMessage(),false,null,500);
        }
    }
    public function logout(Request $request)
    {
        if(request('type')=='api'){
            $auth=Auth()->guard('api');
            $removeToken=$auth->invalidate();
            if($removeToken) {
                return  $this->successResponse('Logout Berhasil!');
            }
        }else{
            $auth=Auth()->guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect(route('login'));
        }
    }
    public function refresh(Request $request)
    {
        $auth=Auth()->guard('api');
        $data=['payload'=>$this->respondWithToken($auth->refresh(),$auth)];
        return $this->successResponse('Refresh Token.',$data);
    }
    protected function respondWithToken($token,$auth)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL() * 60
        ];
    }
}
