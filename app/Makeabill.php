<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Makeabill extends Model
{ 
    use SoftDeletes;
    protected $fillable=[ 
                           'user_id', 
                           'vendor_id', 
                           'product_id',
                           'size',
                           'color', 
                           'stock_id',
                           'qty',
                           'purchase_rate',
                           'sales_rate',
                           'pg_charges',
                           'total_price',
                           'gst',
                           'sgst',
                           'tgst',
                           'cgst',
                           'igst',
                           'taxable_rate',
                           'total_amount',
                           'is_active',
                           'is_deleted',
                           'created_at',
                           'updated_at',
                           'deleted_at'
                        ];

    public function addresses(){
        return $this->hasMany('App\UserAddress');
    }
}
