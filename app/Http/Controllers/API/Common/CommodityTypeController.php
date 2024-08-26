<?php

namespace App\Http\Controllers\API\common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommodityType;
class CommodityTypeController extends Controller
{
    var $rules =[
        'name' => 'required|string|unique:commodity_types,name',
        'priority' => 'required|numeric',
        'description' => 'sometimes|nullable|string',
        'is_active' => 'sometimes|nullable|boolean',
    ];

    var $fields = [
        'name',
        'description',
        'priority',
        'is_active',
        'admin_id'
    ];
    public function index(){
        return CommodityType::get();
    }

    public function update(Request $request, $id){
        $coupon = CommodityType::findOrFail($id);
        $this->rules['name'] = $this->rules['name'] . ',' .$id.',id';
        $this->validate($request, $this->rules);
        return ['success' => $coupon->update($request->only($this->fields))];
    }

    public function store(Request $request){
        $this->validate($request, $this->rules);
        return ['success' => CommodityType::create($request->only($this->fields))];
    }

    public function destroy($id){
        $coupon = CommodityType::findOrFail($id);
        return ['success' => $coupon->delete()];
    }

    public function show($id){
        return CommodityType::findOrFail($id);
    }
}
