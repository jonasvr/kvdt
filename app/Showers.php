<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Showers extends Model
{
    protected $table = 'showers';

    protected $fillable = [
        'device_id', 'state',
    ];
}
