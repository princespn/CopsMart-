<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{

	protected $table='product_packages';
	public $timestamps = false;
    protected $fillable = [
        'package_id',
        'product_id',
        'image',
        'size',
    ];
}
