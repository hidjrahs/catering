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
class MenusCateringIngredients extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'menus_catering_ingredients';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'menus_catering_id',
        'ingredient_id',
        'ingredient_label',
        'quantity'
    ];
    public function ingredient()
    {
        return $this->belongsTo('App\Models\Ingredients', 'ingredient_id', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('menus_catering_ingredients')     // nama log (kategori)
            ->logOnly(['menus_catering_id','ingredient_id','ingredient_label']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Menu Catering Ingredients';
                break;

            case 'updated':
                $activity->description = 'updated a Menu Catering Ingredients';
                break;

            case 'deleted':
                $activity->description = 'deleted a Menu Catering Ingredients';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
    
}
