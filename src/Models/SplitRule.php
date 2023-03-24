<?php

namespace Micros\Splitname\Models;

use Illuminate\Database\Eloquent\Model;

class SplitRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rule', 'distribution'
    ];
    /**
     * Enables microseconds.
     */
    protected $dateFormat = 'Y-m-d H:i:s.u';
}
