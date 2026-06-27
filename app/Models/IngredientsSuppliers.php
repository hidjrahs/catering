<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
class IngredientsSuppliers extends Model
{
    use HasFactory,HasUlids;
    protected $table = 'ingredients_supplier';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'ingredient_id',
        'supplier_id'
    ];

    public function supplier()
    {
        return $this->hasOne('App\Models\Suppliers', 'id', 'supplier_id');
    }

    public function ingredient()
    {
        return $this->hasOne('App\Models\Ingredients', 'id', 'ingredient_id');
    }
}
