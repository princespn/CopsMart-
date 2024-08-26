<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class DeliveryTiming extends Model
{

	use LaravelVueDatatableTrait;

	protected $table='delivery_timings';
	//public $timestamps = false;
    protected $fillable = ['delivery_person_id','date','start_time','end_time','time','created_at','updated_at','deleted_at','is_active'];
}
