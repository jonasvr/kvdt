<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumbers extends Model
{
    protected $table = 'phone_numbers';

    protected $fillable = [
        'user_id', 'nr', 'name',
    ];

    public function scopeGetAll($query,$user_id){
        return $query->where('user_id','=',$user_id)->get();
    }
}
