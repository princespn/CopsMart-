<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class Package extends Model
{

	use LaravelVueDatatableTrait;

	protected $table='packages';
	public $timestamps = false;
    protected $fillable = [
        'name',
        'admin_id',
    ];
}
