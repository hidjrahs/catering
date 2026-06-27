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
class RefProvince extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'province';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('province')     // nama log (kategori)
            ->logOnly(['name']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Province';
                break;

            case 'updated':
                $activity->description = 'updated a Province';
                break;

            case 'deleted':
                $activity->description = 'deleted a Province';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
