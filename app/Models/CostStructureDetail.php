<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CostStructureDetail extends Model
{
    use HasUlids;
    protected $table = 'cost_structure_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cost_structure_id',
        'name',
        'kategori',
        'fixed',
        'prosentase',
        'prosentase_price',
        'fixed_price',
        'fixed_qty',
    ];
}
