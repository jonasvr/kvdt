<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mails extends Model
{
    protected $fillable = [
        'user_id', 'mail','name',
    ];

    public static function getAll($user_id){
        return Mails::where('user_id','=',$user_id)->get();
    }
}
