<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminProductPackage extends Model
{

	protected $table='admin_product_packages';
	public $timestamps = false;
    protected $fillable = [
        'package_id',
        'product_id'
    ];
}
