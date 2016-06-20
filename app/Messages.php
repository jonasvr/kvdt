<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'user_id', 'title', 'message',
    ];

    public function scopeGetAll($query,$user_id){
        return $query->where('user_id','=',$user_id)->get();
    }
}
