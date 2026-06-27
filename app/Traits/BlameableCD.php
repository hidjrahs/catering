<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait BlameableCD
{
    public static function bootBlameable()
    {
        static::creating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 1;
            $model->created_by = $userId;
        });

        static::deleting(function ($model) {
            $userId = Auth::check() ? Auth::id() : 1;
            $model->deleted_by = $userId;
        });
    }
}
