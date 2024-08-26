<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationCode extends Model
{
    protected $fillable = [
        'mobile', 'code'
     ];

    protected $dates = ['deleted_at'];
}
