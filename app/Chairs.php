<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chairs extends Model
{
    protected $table = 'chairs';

    protected $fillable = [
        'device_id', 'time','alert'
    ];

    public function scopeShowerByUser($query,$user_id){
        return $query->join('devices','devices.id','=','chairs.device_id')
            ->where('user_id','=',$user_id);
    }

    public function scopeCheckAlert($query,$user_id)
    {
        return $query->join('devices','devices.id','=','chairs.device_id')
            ->where('devices.user_id','=',$user_id)
            ->where('chairs.alert','=','1');
    }
}
