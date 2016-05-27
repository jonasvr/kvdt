<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $fillable = [
        'user_id', 'device_id',
    ];

    public function checkID($device_id){
        return $this->where('device_id','=',$device_id)
                            ->first();

    }
}
