<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockManagement extends Model
{ 
    use SoftDeletes;
    protected $fillable=[ 
                           'vendor_id', 
                           'product_id',
                           'size',
                           'color', 
                           'stock',
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
                           'deleted_at',
                           'p_taxable_rate',
                           'p_tgst',
                           'p_sgst',
                           'p_cgst',
                           'p_isgt',
                           'p_total_taxable',
                           'p_total_amount',
                           'total_taxable'
                        ];

    public function addresses(){
        return $this->hasMany('App\UserAddress');
    }
}
