<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mails extends Model
{
    protected $table = 'mails';

    protected $fillable = [
        'user_id', 'mail','name',
    ];

    public function scopeGetAll($query,$user_id){
        return $query->where('user_id','=',$user_id)->get();
    }
}
