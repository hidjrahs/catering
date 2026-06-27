<?php
namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait Validators{
    public function validateCheck($request,$rule,$message=[]){
        if(!count($message)){
            $message=[
                'required' => 'params :attribute is required.',
            ];
        }
        return Validator::make($request,$rule,$message);
    }
}