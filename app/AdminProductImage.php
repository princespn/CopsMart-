<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminProductImage extends Model
{
    protected $fillable = [
        'name',
        'product_id'
    ];
}
