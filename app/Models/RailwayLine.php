<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RailwayLine extends Model
{
    protected $fillable = [
        'name',
        'color',
        'sort_order',
    ];
}
