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

    /**
     * @return mixed
     */
    public function users(){
        return $this->hasMany('App\User')->withTimestamps();
    }

    /**
     * @param $query
     * @param $kot_id
     * @return mixed
     */
    public function scopeFindId($query,$kot_id)
    {
        return $query->where('kot_id','=',$kot_id)->first();
    }

    /**
     * @param $query
     * @param $user_id
     * @return mixed
     */
    public function scopeIsAdmin($query,$user_id)
    {
        return $query->where('user_id','=',$user_id)->first();
    }
}
