<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
class EmployeeEducations extends Model
{
    use HasFactory,HasUlids, LogsActivity;
    protected $table = 'employee_educations';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'employee_id',
        'education_level',
        'school_name',
        'city',
        'major',
        'year_start',
        'year_graduated'
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('employee_educations')     // nama log (kategori)
            ->logOnly(['employee_id','education_level']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Employee Educations';
                break;

            case 'updated':
                $activity->description = 'updated a Employee Educations';
                break;

            case 'deleted':
                $activity->description = 'deleted a Employee Educations';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
