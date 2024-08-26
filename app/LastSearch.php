<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastSearch extends Model
{
	protected $table='last_search';
	public $timestamps = true;
    protected $fillable = [
        'product_id',
        'user_id',
        'vendor_id',
        'is_active',
        'created_at',
        'deleted_at',
    ];
}
