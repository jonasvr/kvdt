<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Events extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'id','user_id', 'summary','start','end','event_id',
    ];

    public function scopeGetAgenda($query,$user_id)
    {
        return $query->where('user_id','=',$user_id)
            ->orderby('start','ASC')
            ->where('start','>=',Carbon::now())
            ->take(3)
            ->get();
    }
}
