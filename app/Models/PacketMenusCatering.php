<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PacketMenusCatering extends Model
{
    use HasFactory,HasUlids;
    protected $table = 'packet_menus_catering';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'packet_catering_id',
        'menus_catering_id'
    ];

    public function packet_name()
    {
        return $this->belongsTo('App\Models\PacketCatering', 'packet_catering_id', 'id');
    }

}
