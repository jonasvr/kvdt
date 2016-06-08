<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Koten extends Model
{
    protected $table = 'kotens';

    protected $fillable = [
        'user_id', 'kot_id','street', 'city','name',
    ];

    /**
     * link between devices en koten
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devices(){
        return $this->belongsToMany('App\Devices')->withTimestamps();
    }

    public function users(){
        return $this->hasMany('App\User')->withTimestamps();
    }

    public function scopeFindId($query,$kot_id)
    {
        return $query->where('kot_id','=',$kot_id)->first();
    }
}
