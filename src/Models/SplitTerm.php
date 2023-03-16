<?php

namespace micros\splitname\Models;

use Illuminate\Database\Eloquent\Model;

class SplitTerm extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term', 'type', 'gender', 'canonical'
    ];
}
