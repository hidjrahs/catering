<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait Blameable
{
    public static function bootBlameable()
    {
        static::creating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 1;
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });

        static::updating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 1;
            $model->updated_by = $userId;
        });

        static::deleting(function ($model) {
            $userId = Auth::check() ? Auth::id() : 1;
            $model->deleted_by = $userId;
        });
    }
}
