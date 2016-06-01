<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $table = 'devices';

    protected $fillable = [
        'user_id', 'device_id',
    ];

    public function scopeCheckID($query,$device_id){
        return $query->where('device_id','=',$device_id)
                            ->first();

    }
}
