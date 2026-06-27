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
class OrderItems extends Model
{
    use HasFactory,HasUlids, LogsActivity,SoftDeletes,Blameable;
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'menus_catering_id',
        'quantity',
        'price',
        'notes'
    ];

    public function menu()
    {
        return $this->belongsTo('App\Models\MenusCatering', 'menus_catering_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('order_items')     // nama log (kategori)
            ->logOnly(['order_id','menus_catering_id']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Order item';
                break;

            case 'updated':
                $activity->description = 'updated a Order item';
                break;

            case 'deleted':
                $activity->description = 'deleted a Order item';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
