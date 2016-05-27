<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumbers extends Model
{
    protected $fillable = [
        'user_id', 'nr', 'name',
    ];

    public static function getAll($user_id){
        return PhoneNumbers::where('user_id','=',$user_id)->get();
    }
}
