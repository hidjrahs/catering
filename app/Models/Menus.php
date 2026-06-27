<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    use HasFactory;
    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // public $timestamps = false;

    public function permissions()
    {
        // return $this->hasMany(Permission::class,'menu_id','id');
        return $this->hasMany('App\Models\Permission', 'menu_id', 'id')->where(['guard_name'=>'web']);
    }
}
