<?php

namespace App\Models;

use App\Traits\BlameableCD;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
class ImportBerkas extends Model
{
    use HasFactory,HasUlids, LogsActivity,BlameableCD;
    protected $table = 'import_berkas';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $appends = array('pathfile');
    public $incrementing = false;

    protected $fillable = [
        'source',
        'filename',
        'path',
        'disk',
        'is_preview',
        'is_previewed',
        'is_process',
        'is_done',
        'desc',
    ];
    public function getPathfileAttribute()
    {
        $filePath=url('storage/'.$this->disk)."/".$this->path;
        return $filePath;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('suppliers')     // nama log (kategori)
            ->logOnly(['source','filename']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'import excel temp';
                break;

            case 'updated':
                $activity->description = 'modify import exccel temp';
                break;

            case 'deleted':
                $activity->description = 'deleted import excel temp';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
