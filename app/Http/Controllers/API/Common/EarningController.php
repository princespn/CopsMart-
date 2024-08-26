<?php

namespace App\Http\Controllers\API\common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Earning;
class EarningController extends Controller
{
    var $rules =[
        'order_id' => 'required|numeric',
        'earning' => 'required|numeric',
        'is_active' => 'sometimes|nullable|boolean',
    ];

    var $fields = [
        'order_id',
        'earning',
        'is_active',
        'admin_id'
    ];

    public function index(){
        return Earning::select("*")->get();
    }

    public function update(Request $request, $id){
        $coupon = Earning::findOrFail($id);
        $this->validate($request, $this->rules);

        $coupon->update(['is_active' => $request->is_active,'order_id' => $request->order_id,'earning'=>$request->earning]);
        return [
            'message' => 'Updated Successfully',
        ];
        //return ['success' => $coupon->update($request->only($this->fields))];
    }

    public function store(Request $request){

        $this->validate($request, $this->rules);
        $coupon = new Earning();
        $coupon->order_id = $request->order_id;
        $coupon->admin_id = $request->admin_id;
        $coupon->earning = $request->earning;
        $coupon->is_active = $request->is_active;
        $coupon->save();
        return [
            'message' => 'Created Successfully',
        ];
        //return ['success' => Coupon::create($request->only($this->fields))];
    }

    public function destroy($id){
        $coupon = Earning::findOrFail($id);
        return ['success' => $coupon->delete()];
    }

    public function show($id){
        return Earning::findOrFail($id);
    }
}
