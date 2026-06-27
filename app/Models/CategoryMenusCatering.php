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

class CategoryMenusCatering extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'category_menus_catering';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'filename',
        'path',
        'disk',
        'is_quantity'
    ];

    public function menucatering()
    {
        return $this->hasMany('App\Models\MenusCatering', 'category_menus_catering_id', 'id');
    }
    public function activeMenus()
    {
        return $this->hasMany(MenusCatering::class)->where('is_active', true);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('category_menus_catering')     // nama log (kategori)
            ->logOnly(['name','filename']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Category Menu';
                break;

            case 'updated':
                $activity->description = 'updated a Category Menu';
                break;

            case 'deleted':
                $activity->description = 'deleted a Category Menu';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }

    public function getPathfileAttribute()
    {
        $filePath=false;
        if($this->path){
            $filePath=url('storage/category_menus').'/'.$this->id."/".$this->path;
        }
        return $filePath;
    }
}
