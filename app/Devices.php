<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $table = 'devices';

    protected $fillable = [
        'user_id', 'device_id',
    ];

    public static function checkID($device_id){
        return Devices::where('device_id','=',$device_id)
                            ->first();

    }
}
