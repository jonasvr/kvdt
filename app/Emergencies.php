<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emergencies extends Model
{
    protected $fillable = [
        'id', 'alarm_id','MailOrSms','contact_id','message_id',
    ];
}
