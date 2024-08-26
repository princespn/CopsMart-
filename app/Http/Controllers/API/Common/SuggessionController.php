<?php

namespace App\Http\Controllers\API\common;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Suggession;
class SuggessionController extends Controller
{
    var $rules =[
        'category_id' => 'required|numeric',
        'product_id' => 'required',
        'layout' => 'required|numeric',
        'position' => 'required|numeric',
        'is_active' => 'sometimes|nullable|boolean',
    ];

    var $fields = [
        'category_id',
        'product_id',
        'layout',
        'position',
        'is_active',
        'admin_id'
    ];

    public function index(){
        return Suggession::leftJoin('categories', 'categories.id','=', 'suggessions.category_id')
        ->leftJoin("products as v",\DB::raw("FIND_IN_SET(v.id, suggessions.product_id)"),">",\DB::raw("'0'"))
        ->select(['suggessions.*','categories.name as category_name',\DB::raw("GROUP_CONCAT(v.name ORDER BY v.id) as product_name")])
        ->groupBy('suggessions.id')
        ->get();
    }


    public function update(Request $request, $id){
        $coupon = Suggession::findOrFail($id);
        $vendor_array = implode(',', $request->product_id);
       
        $coupon->update(['heading'=>$request->heading,'category_id'=>$request->category_id,'product_id'=>$vendor_array,'layout'=>$request->layout,'position'=>$request->position,'updated_at'=>date('Y-m-d H:i:s'),'is_active'=>$request->is_active]);
        return [
            'message' => 'Updated Successfully',
        ];
        //return ['success' => $coupon->update($request->only($this->fields))];
    }

    public function store(Request $request){

        $this->validate($request, $this->rules);

        $product = implode(',', $request->product_id);
        $sugg = new Suggession();
        $sugg->category_id = $request->category_id;
        $sugg->product_id = $product;
        $sugg->heading = $request->heading;
        $sugg->layout = $request->layout;
        $sugg->admin_id = $request->admin_id;
        $sugg->position = $request->position;
        $sugg->created_at = date('Y-m-d H:i:s');
        $sugg->is_active = $request->is_active;
        $sugg->save();
        return [
            'message' => 'Created Successfully',
        ];
        //return ['success' => Coupon::create($request->only($this->fields))];
    }

    public function destroy($id){
        $sugg = Suggession::findOrFail($id);
        return ['success' => $sugg->delete()];
    }

    public function show($id){
        return Suggession::findOrFail($id);
    }
}
