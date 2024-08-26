<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePayment extends Model
{
    protected $fillable = [
        'vendor_id',
        'invoice_no',
        'payment_mode',
        'payment_status',
        'amount',
        'new_balance',
        'transaction_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active'
    ];

      /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function marketingPerson(){
        return $this->belongsTo('App\MarketingPerson');
    }
}
