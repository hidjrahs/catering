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
class Ingredients extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'ingredients';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'unit',
        'default_price',
        'satuan'
        // 'supplier_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ingredients')     // nama log (kategori)
            ->logOnly(['name','unit']) // hanya atribut ini supplier_id
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Ingredients';
                break;

            case 'updated':
                $activity->description = 'updated a Ingredients';
                break;

            case 'deleted':
                $activity->description = 'deleted a Ingredients';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
    public function ref_supplier()
    {
        return $this->hasMany('App\Models\IngredientsSuppliers', 'ingredient_id', 'id');
    }
}
