<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $table = 'devices';

    protected $fillable = [
        'user_id', 'device_id','device_type','name',
    ];

    public function scopeCheckID($query,$device_id){
        return $query->where('device_id','=',$device_id)
                            ->first();

    }

    /**
     * link between devices en koten
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function koten(){
        return $this->belongsToMany('App\Koten')->withTimestamps();
    }
}
