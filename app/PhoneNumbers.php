<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumbers extends Model
{
    protected $fillable = [
        'user_id', 'nr', 'name',
    ];
}
