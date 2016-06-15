<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','mailAlias','email','name','koten_id'
    ];

    public function getCalendars()
    {
        return $this
            ->hasMany('App\calendarList', 'user_id')
            ->where('follow', '=', 1);
    }

    public function getDevices()
    {
        return $this
            ->hasMany('App\Devices','user_id')->get();
    }

    /**
     * link between users en koten
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function koten(){
        return $this->belongsTo('App\Koten','koten_id')->withTimestamps();
    }


    /**
     * @param $query
     * @param $user_id
     * @return mixed ->name
     */
    public function scopeGetInfo($query,$user_id)
    {
        return $query->where('id', '=' , $user_id)->first();
    }
}
