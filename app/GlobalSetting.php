<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $fillable =[
        'admin_contact_numbers',
        'customer_support_email',
        'orders_email',
        'vendor_support_email',
        'delivery_support_email',
        'marketing_support_email',
        'customer_app_min_version',
        'vendor_app_min_version',
        'delivery_app_min_version',
        'marketing_app_min_version',
    ];
}
