<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Koten extends Model
{
    protected $table = 'kotens';

    protected $fillable = [
        'user_id', 'kot_id','street', 'city','name',
    ];
}
