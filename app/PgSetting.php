<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PgSetting extends Model
{   
    use SoftDeletes;
    protected $fillable=[
        'vendor_id',
        'pg_type',
        'pg_charge',
        'bank',
        'company',
        'to_show',
        'pg_val',
        'bank_val',
        'company_val',
        'toshow_val',
        'is_active',
        'is_deleted',
        'crerated_at',
        'updated_at',
        'deleted_at',
    ];     
    protected $dates = ['deleted_at'];
}
