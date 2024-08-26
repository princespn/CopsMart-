<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class MarketingBox extends Model
{

	use LaravelVueDatatableTrait;

	protected $table='marketing_box';
	public $timestamps = false;
    protected $fillable = [
        'marketing_name','marketing_image','category_id','vendor_id'
    ];
}
