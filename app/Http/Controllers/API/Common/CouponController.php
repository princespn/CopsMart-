<?php

namespace App\Http\Controllers\API\common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Coupon;
class CouponController extends Controller
{
    var $rules =[
        'name' => 'required|string|unique:coupons,name',
        'discount_value' => 'required|numeric',
        'ocean_discount' => 'required|numeric',
        'min_order_amount' => 'required|numeric',
        'max_discount_amount' => 'required|numeric',
        'category_id' => 'required|numeric',
        'vendor_id' => 'required',
        'per_user_limit' => 'sometimes|nullable|numeric',
        'is_active' => 'sometimes|nullable|boolean',
    ];

    var $fields = [
        'name',
        'discount_value',
        'category_id',
        'vendor_id',
        'min_order_amount',
        'max_discount_amount',
        'per_user_limit',
        'ocean_discount',
        'is_active'
    ];

    public function verifyAndGet(Request $request){
        
        $validator = \Validator::make($request->all(), ['name'=>'required']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        $coupon = Coupon::where(['name' => $request->name, 'is_active' => 1 , 'category_id'=>$request->category_id])->first();
         //$coupon->vendor_id;
         if($coupon)
         {
              $vendor=$coupon->vendor_id;
              $vendor=explode(',',$vendor);
              if (in_array($request->vendor_id, $vendor))
              {
                  $coupon=$coupon;
              }
              else
              {
                  $coupon='';
              }
         }
        return [
            'valid' => $coupon ? true : false,
            'message' => $coupon ? 'Applied successfully' : 'No coupon found',
            'coupon' => $coupon
        ];
    }

    public function index(){
        return Coupon::leftJoin('categories', 'categories.id','=', 'coupons.category_id')
        ->leftJoin("vendors as v",\DB::raw("FIND_IN_SET(v.id, coupons.vendor_id)"),">",\DB::raw("'0'"))
        ->select(['coupons.*','categories.name as category_name',\DB::raw("GROUP_CONCAT(v.name ORDER BY v.id) as vendor_name")])
        ->groupBy('coupons.id')
        ->get();
    }


    public function update(Request $request, $id){
        $coupon = Coupon::findOrFail($id);
        $this->rules['name'] = $this->rules['name'] . ',' .$id.',id';
        $this->validate($request, $this->rules);
        $vendor_array = implode(',', $request->vendor_id);
       
        $coupon->update(['is_active' => $request->is_active,'name' => $request->name,'category_id'=>$request->category_id,'vendor_id'=>$vendor_array,'discount_value'=>$request->discount_value,'ocean_discount'=>$request->ocean_discount,'min_order_amount'=>$request->min_order_amount,'max_discount_amount'=>$request->max_discount_amount]);
        return [
            'message' => 'Updated Successfully',
        ];
        //return ['success' => $coupon->update($request->only($this->fields))];
    }

    public function store(Request $request){

        $this->validate($request, $this->rules);

        $vendor_array = implode(',', $request->vendor_id);
        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->category_id = $request->category_id;
        $coupon->vendor_id = $vendor_array;
        $coupon->discount_value = $request->discount_value;
        $coupon->ocean_discount = $request->ocean_discount;
        $coupon->min_order_amount = $request->min_order_amount;
        $coupon->max_discount_amount = $request->max_discount_amount;
        $coupon->save();
        return [
            'message' => 'Created Successfully',
        ];
        //return ['success' => Coupon::create($request->only($this->fields))];
    }

    public function destroy($id){
        $coupon = Coupon::findOrFail($id);
        return ['success' => $coupon->delete()];
    }

    public function show($id){
        return Coupon::findOrFail($id);
    }
}
