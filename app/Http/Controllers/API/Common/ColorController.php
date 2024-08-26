<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Color;
use App\Size;
use App\Stock;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function GetActive($a){
        return Color::where('is_active',1)->get();
    }
     
   



    public function GetColorResult(Request $request)
    {   
        $sar=explode(',',$request->size);
        return Color::whereIn('id',$sar)->where('is_active',1)->get();
    }

    public function GetStockResult(Request $request)
    {   
        $result= Stock::where('stocks.vendor_id',$request->vendor_id)
        ->where('stocks.product_id',$request->product_id)
        ->where('stocks.color',$request->color)->where('stocks.size',$request->size)->where('stocks.is_active',1)->orderBy('stocks.id','desc')->limit(1)->select(['stocks.*'])->get();
        $data=[];
        if(count($result)>0)
        {  
            foreach($result as $key)
            {
                $key->qtyy=$key->quantity - $key->sold_qty;
                $data[]=$key;
            }
            $data= $data[0];
        }
        return $data;
    }


    public function AttributeVendor($a){
        return Color::where('vendor_id',$a)->where('is_active',1)->where('type','attribute')->get();
    }

    public function index(Request $request)
    {
        // return Category::latest()->paginate(10);
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        $query = Color::dataTableQuery($column, $orderBy, $searchValue);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'vendor_id' => 'required',
        ]); 
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]); 
        $data = $request->only('name','vendor_id','is_active');
        $categoryId = Color::create($data)->id;
        return [
            'category' => $categoryId
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
       return $query = Color::get();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Color::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'vendor_id' => 'required',
        ]);
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name','vendor_id','is_active');
        $category->update($data);
        return [
            'message' => 'Updated Successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Color::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }

  
}
