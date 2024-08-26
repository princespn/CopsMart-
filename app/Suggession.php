<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggession extends Model
{
    protected $fillable = [
        'category_id',
        'product_id',
        'layout',
        'position',
        'heading',
        'created_at',
        'updated_at',
        'is_active',
        'admin_id',
    ];
}
