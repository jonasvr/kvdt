<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplyKotens extends Model
{
    protected $table = 'apply_kotens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kot_id', 'user_id'
    ];

    /**
     * @param $query
     * @param $kot_id
     * @return mixed
     */
    public function scopeGetApplies($query,$kot_id)
    {
        return $query->where('kot_id', '=', $kot_id)->get();
    }
}
