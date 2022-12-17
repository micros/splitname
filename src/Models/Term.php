<?php

namespace Micrositios\Names\App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type'
    ];
}
