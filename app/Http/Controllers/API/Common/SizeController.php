<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Size;
use App\Product;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function allCategory($a){
        return Size::get();
    }

    public function GetSizeResult(Request $request)
    {   
        $sar=explode(',',$request->size);
        return Size::whereIn('id',$sar)->where('is_active',1)->get();
    }
    

    public function GetActive($a){
        return Size::where('is_active',1)->get();
    }

    public function AttributeVendor($a){
        return Size::where('vendor_id',$a)->where('is_active',1)->where('type','attribute')->get();
    }

    public function index(Request $request)
    {
        // return Category::latest()->paginate(10);
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        $query = Size::dataTableQuery($column, $orderBy, $searchValue);
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
        $categoryId = Size::create($data)->id;
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
       
       return $query = Size::get();

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
        $category = Size::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'vendor_id' => 'required',
        ]);

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
        $category = Size::findOrFail($id);
        
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }

  
}
