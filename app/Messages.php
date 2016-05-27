<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message',
    ];

    public static function getAll($user_id){
        return Messages::where('user_id','=',$user_id)->get();
    }
}
