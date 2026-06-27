<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
class MenusCateringTumb extends Model
{
    use HasFactory,HasUlids, LogsActivity,Blameable;
    protected $table = 'menus_catering_tumb';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'menus_catering_id',
        'filename',
        'path',
        'path_original',
        'disk'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('menus_catering_tumb')     // nama log (kategori)
            ->logOnly(['menus_catering_id','filename']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Menu Catering Image';
                break;

            case 'updated':
                $activity->description = 'updated a Menu Catering Image';
                break;

            case 'deleted':
                $activity->description = 'deleted a Menu Catering Image';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
