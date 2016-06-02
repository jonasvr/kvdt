<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class calendarList extends Model
{
    protected $table = 'calendar_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'calendar_id', 'follow',
    ];

    public function scopeGetCalendar($query,$calendar_id)
    {
        return $query
            ->where('calendar_id', '=', $calendar_id)
            ->where('user_id', '=', Auth::user()->id)
            ->first();
    }

}
