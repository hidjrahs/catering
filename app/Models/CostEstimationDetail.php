<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CostEstimationDetail extends Model
{
    use HasUlids;
    protected $table = 'cost_estimation_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cost_estimation_id',
        'name',
        'fixed',
        'kategori',
        'prosentase',
        'prosentase_price',
        'fixed_price',
        'fixed_qty'
    ];
}
