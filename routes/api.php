<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function(){
    Route::get('/', function () {
        return [''];
    });
});
