<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TempIngredientsMenu extends Model
{
    use HasFactory,HasUlids;
    protected $table = 'temp_ingredients_menu';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'temp_recipe_menu_id',
        'ingredient_name',
        'qty',
        'satuan',
        'unit',             
        'price_per_unit'
    ];
}
