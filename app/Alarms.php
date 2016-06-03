<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;


class Alarms extends Model
{
    protected $table = 'alarms';
    
    protected $fillable = [
        'eventId', 'calendarId', 'start','end','alarmDate','alarmTime','summary',
    ];

    public function scopeNextAlarm($query,$user_id){
        $now = Carbon::now();
        $time = $now->toTimeString();
        $date = $now->toDateSTring();
        $alarm = $query->where('user_id','=',$user_id)
            ->orderby('alarmDate','ASC')
            ->orderby('alarmTime', 'DESC')
            ->where('alarmTime', '>',$time)
            ->where('alarmDate', '>=',$date)
            ->first();

        return $alarm;
    }

    public function scopeGetAlarm($query,$alarm_id){
        return $query
            ->where('event_id','=', $alarm_id)
            ->where('user_id', '=', Auth::user()->id)
            ->first();
    }

    public function scopeCheckUser($query,$user_id)
    {
        return $query->where('user_id','=',$user_id);
    }

    public function scopeToday($query)
    {
        return $query->where('alarmDate', '>=' , carbon::today());
    }

    public function scopeCheckEvent($query,$id)
    {
        return $query->where('event_id','=',$id);
    }

    public  function scopeCheckID($query,$alarm_id){
        return $query->where('id','=',$alarm_id)
            ->first();
    }
}
