<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarms extends Model
{
    protected $fillable = [
        'eventId', 'calendarId', 'start','end','alarmDate','alarmTime','summary',
    ];
}
