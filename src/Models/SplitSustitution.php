<?php

namespace Micros\Splitname\Models;

use Illuminate\Database\Eloquent\Model;

class SplitSustitution extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'origin', 'rule'
    ];
}