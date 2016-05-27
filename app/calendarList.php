<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class calendarList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'calendar_id', 'follow',
    ];

    public function getCalendar($calendar_id){
        return $this->where('calendar_id', '=', $calendar_id)
                                ->where('user_id', '=', Auth::user()->id)
                                ->first();
    }

}
