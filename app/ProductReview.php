<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
	protected $table='product_review';
	public $timestamps = true;
    protected $fillable = [
        'product_id',
        'order_id',
        'user_id',
        'vendor_id',
        'star',
        'review',
        'is_active',
        'created_at',
        'deleted_at',
    ];
}
