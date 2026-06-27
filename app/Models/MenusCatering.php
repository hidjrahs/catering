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
class MenusCatering extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'menus_catering';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'desc',
        'selling_price',
        'category_menus_catering_id',
        'porsi_standard',
        'is_active',
    ];
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
    public function refImage()
    {
        return $this->hasMany('App\Models\MenusCateringTumb', 'menus_catering_id', 'id');
    }
    public function menuingredients()
    {
        return $this->hasMany('App\Models\MenusCateringIngredients', 'menus_catering_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\CategoryMenusCatering', 'category_menus_catering_id', 'id');
    }
    public function packet()
    {
        return $this->hasMany('App\Models\PacketMenusCatering', 'menus_catering_id', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('menus_catering')     // nama log (kategori)
            ->logOnly(['name','selling_price','category_menus_catering_id']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Menu Catering';
                break;

            case 'updated':
                $activity->description = 'updated a Menu Catering';
                break;

            case 'deleted':
                $activity->description = 'deleted a Menu Catering';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
    
}
