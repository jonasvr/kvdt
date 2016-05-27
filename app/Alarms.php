<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;


class Alarms extends Model
{
    protected $fillable = [
        'eventId', 'calendarId', 'start','end','alarmDate','alarmTime','summary',
    ];

    public function nextAlarm($user_id){
        $time = Carbon::now();
        $time = $time->toTimeString();

        $alarm = $this->where('user_id', '=', $user_id)
                        ->orderby('alarmDate','ASC')
                        ->orderby('alarmTime', 'DESC')
                        ->where('alarmTime', '<',$time)
                        ->where('alarmDate', '=', Carbon::today())
                        ->first();
        return $alarm;
    }

    public function checkID($alarm_id){
        return $this->where('alarm_id','=',$alarm_id)
                            ->first();
    }

    public function getAlarm($alarm_id){
        return $this->where('event_id','=', $alarm_id)
                       ->where('user_id', '=', Auth::user()->id)
                       ->first();;
    }
}
