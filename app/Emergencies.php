<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emergencies extends Model
{
    protected $table = 'emergencies';

    protected $fillable = [
        'id', 'alarm_id','MailOrSms','contact_id','message_id',
    ];

    public static function exist($alarm_id){
        return Emergencies::where('alarm_id','=',$alarm_id)->first();
    }
}
