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
class CostEstimations extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'cost_estimations';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'estimated_cost',
        'estimated_selling_price',
        'estimated_margin',
        'desc',
        'verified_by',
        'cost_structure_id'
    ];
    public function detail()
    {
        return $this->hasMany(CostEstimationDetail::class, 'cost_estimation_id', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('cost_estimations')     // nama log (kategori)
            ->logOnly(['order_id','verified_by']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Cost Estimations';
                break;

            case 'updated':
                $activity->description = 'updated a Cost Estimations';
                break;

            case 'deleted':
                $activity->description = 'deleted a Cost Estimations';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
