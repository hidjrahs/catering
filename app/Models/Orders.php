<?php

namespace App\Models;

use App\Traits\BlameableWithTicket;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
class Orders extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,BlameableWithTicket;
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'customer_id',
        'estimate_price',
        'delivery_date',
        'event_date',
        'event_time',
        'total_guest',
        'status',
        'desc',
        'desc_extra',
        'event_type',
        'package_type',
        'venue',
        'total_invite',
        'dp'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('orders')     // nama log (kategori)
            ->logOnly(['customer_id','order_date']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Order';
                break;

            case 'updated':
                $activity->description = 'updated a Order';
                break;

            case 'deleted':
                $activity->description = 'deleted a Order';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
    public function refItem()
    {
        return $this->hasMany('App\Models\OrderItems', 'order_id', 'id');
    }
    public function rincianbiaya()
    {
        return $this->hasMany('App\Models\RincianBiaya', 'order_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customers', 'customer_id', 'id');
    }
    public function petugas()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }
    public function costestimation()
    {
        return $this->hasOne('App\Models\CostEstimations', 'order_id', 'id');
    }
    public function purchases()
    {
        return $this->hasOne('App\Models\Purchases', 'order_id', 'id');
    }
}
