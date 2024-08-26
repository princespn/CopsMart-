<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RazorpaySetting extends Model
{
    protected $fillable= [
        'name',
        'rkey',
        'rsecret',
        'rnum',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
