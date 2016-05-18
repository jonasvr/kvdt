<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class calendarList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'calendar_id','calendar_name', 'follow',
    ];

}
