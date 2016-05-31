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

    public static function nextAlarm($user_id){
        $time = Carbon::now();
        $time = $time->toTimeString();

        $alarm = Alarms::where('user_id', '=', $user_id)
                        ->orderby('alarmDate','ASC')
                        ->orderby('alarmTime', 'DESC')
                        ->where('alarmTime', '<',$time)
                        ->where('alarmDate', '=', Carbon::today())
                        ->first();
        return $alarm;
    }

    public static function checkID($alarm_id){
        return Alarms::where('id','=',$alarm_id)
                            ->first();
    }

    public static function getAlarm($alarm_id){
        return Alarms::where('event_id','=', $alarm_id)
                       ->where('user_id', '=', Auth::user()->id)
                       ->first();;
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
}
