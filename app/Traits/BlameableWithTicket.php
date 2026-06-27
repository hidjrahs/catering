<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait BlameableWithTicket
{
    public static function bootBlameableWithTicket()
    {
        static::creating(function ($model) {
            $prefix = "LL";
            $date   = date('ymdhi');
            $random = strtoupper(Str::random(3));

            $userId = Auth::check() ? Auth::id() : 1;
            $model->order_ticket = "{$prefix}-{$date}-{$random}";
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
