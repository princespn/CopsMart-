<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
class OfferProduct extends Model
{

	use LaravelVueDatatableTrait;

	protected $table='offered_products';
	public $timestamps = false;
    protected $fillable = [
        'offer_title','offer_image','category_id','sup_sub_category_id'
    ];

    public function offer_products(){
        return $this->hasMany('App\Product','sup_sub_category_id','sup_sub_category_id')
        			->leftjoin('vendor_products','vendor_products.product_id','=','products.id')
                    ->leftjoin('packages','vendor_products.package_id','=','packages.id')
                    ->select('products.*','packages.name as package_name','vendor_products.offer_price','vendor_products.price','vendor_products.mrp','vendor_products.cost_price')
                    ->where('vendor_products.offer_status','1');
    }


}