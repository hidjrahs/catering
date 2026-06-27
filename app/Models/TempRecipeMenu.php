<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TempRecipeMenu extends Model
{
    use HasFactory,HasUlids;
    protected $table = 'temp_recipe_menu';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'import_berkas_id',
        'recipe_name',
        'category',
        'paket',
        'portion_standard'
    ];

    public function ingredients()
    {
        return $this->hasMany(TempIngredientsMenu::class,'temp_recipe_menu_id');
    }
}
