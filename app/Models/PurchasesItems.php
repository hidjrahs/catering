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
class PurchasesItems extends Model
{
    // ,SoftDeletes,Blameable
    use HasFactory,HasUlids, LogsActivity;
    protected $table = 'purchase_items';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'purchase_id',
        'ingredient_id',
        'quantity',
        'price',
        'supplier_id'
    ];
    public function suppliers() {
        return $this->belongsTo(Suppliers::class,'supplier_id','id');
    }
    public function ingredient() {
        return $this->belongsTo(Ingredients::class,'ingredient_id','id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('purchase_items')     // nama log (kategori)
            ->logOnly(['purchase_id','ingredient_id']) // hanya atribut ini
            ->logOnlyDirty()         // hanya saat ada perubahan nilai
            ->dontSubmitEmptyLogs(); // jangan buat log jika tidak ada yang berubah
    }

    // Ubah deskripsi berdasarkan event (created/updated/deleted)
    public function tapActivity(Activity $activity, string $eventName)
    {
        switch ($eventName) {
            case 'created':
                $activity->description = 'created a Purchase Items';
                break;

            case 'updated':
                $activity->description = 'updated a Purchase Items';
                break;

            case 'deleted':
                $activity->description = 'deleted a Purchase Items';
                break;

            default:
                $activity->description = $eventName;
                break;
        }
    }
}
