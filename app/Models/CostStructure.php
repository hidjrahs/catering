<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CostStructure extends Model
{
    use HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'cost_structure';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'is_active',
        'desc'
    ];
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
    public function detail()
    {
        return $this->hasMany(CostStructureDetail::class, 'cost_structure_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('cost_structure')     // nama log (kategori)
            ->logOnly(['name','created_by']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Cost Structure';
                break;

            case 'updated':
                $activity->description = 'updated a Cost Structure';
                break;

            case 'deleted':
                $activity->description = 'deleted a Cost Structure';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
