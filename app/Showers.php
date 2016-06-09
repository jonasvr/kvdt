<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Showers extends Model
{
    protected $table = 'showers';

    protected $fillable = [
        'device_id', 'state',
    ];

    /**
     * @param $query
     * @param $koten_id
     * @return mixed
     */
    public function scopeShowerByKot($query,$koten_id){
        return $query->where('koten_id','=',$koten_id)
            ->join('devices','devices.id','=','showers.device_id');
    }
}
