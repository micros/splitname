<?php

namespace Micros\Splitname\Models;

use Illuminate\Database\Eloquent\Model;

class SplitSample extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term', 'type'
    ];
    /**
     * Enables microseconds.
     */
    protected $dateFormat = 'Y-m-d H:i:s.u';
}
