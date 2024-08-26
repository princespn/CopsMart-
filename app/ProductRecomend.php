<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRecomend extends Model
{
	protected $table='product_recomend';
	public $timestamps = true;
    protected $fillable = [
        'vendor_id',
        'product_id',
        'is_active',
        'created_at',
        'deleted_at',
    ];
}
