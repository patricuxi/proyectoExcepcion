<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'name','genre','origin_country','distributor','episodes'
    ];
}
