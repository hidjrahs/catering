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
class Employes extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'location',
        'gender',
        'national_id',
        'status',
        'work_since',
        'division',
        'birth_place_date',
        'height_cm',
        'weight_kg',
        'religion',
        'user_id'
    ];
    public function educations()
    {
        return $this->hasMany(EmployeeEducations::class, 'employee_id');
    }

    public function families()
    {
        return $this->hasMany(EmployeeFamilies::class, 'employee_id');
    }

    public function emergencies()
    {
        return $this->hasMany(EmployeeEmergencies::class, 'employee_id');
    }
    public function users()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('employes')     // nama log (kategori)
            ->logOnly(['name','phone']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Employer';
                break;

            case 'updated':
                $activity->description = 'updated a Employer';
                break;

            case 'deleted':
                $activity->description = 'deleted a Employer';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
