<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chairs extends Model
{
    protected $table = 'chairs';

    protected $fillable = [
        'device_id', 'time',
    ];

    public function scopeShowerByUser($query,$user_id){
        return $query->join('devices','devices.id','=','chairs.device_id')
        ->where('user_id','=',$user_id);

    }
}
