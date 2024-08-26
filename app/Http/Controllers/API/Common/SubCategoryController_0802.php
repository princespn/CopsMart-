<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SubCategory;
use App\Category;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SubCategory::leftJoin('categories as c', 'c.id','=', 'sub_categories.super_category_id')->leftJoin('categories', 'categories.id','=', 'sub_categories.category_id')->select(['sub_categories.*','sub_categories.id as id', 'categories.name as category_name','c.name as super_category','sub_categories.name as name'])->where('sub_categories.parent_id','0')->get();
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
            'name' => 'required|string|max:191|unique:sub_categories,name',
            'category_id' => 'required|numeric',
            'is_active' => 'boolean'
        ]);
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/sub_category/').$name);
            $request->merge(['image' => $name]);
        }
        $data = $request->only('name','super_category_id','category_id', 'is_active', 'image');

        $subcategoryId = SubCategory::create($data)->id;
        if(isset($request->subcategory_name)){
            if(!empty($request->subcategory_name)){
                for ($index=0;$index<count($request->subcategory_name);$index++) {
                    $subcategory_save = new SubCategory();
                    $subcategory_save->parent_id = $subcategoryId;
                    $subcategory_save->name = $request->subcategory_name[$index];
                    $subcategory_save->save();
                }
            }
        }

        return [
            'subcategory' => $subcategoryId
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
        return SubCategory::findOrFail($id);
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
        $subCategory = SubCategory::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:sub_categories,name'. ',' .$id.',id',
            'category_id' => 'required|numeric',
            'is_active' => 'boolean'
        ]);


        $oldImage = $subCategory->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/sub_category/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/sub_category/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
        $data = $request->only('name', 'category_id', 'is_active', 'image');

        return [
            'message' => 'Updated Successfully',
            'success' =>  $subCategory->update($data)
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
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();
        // delete the subCategory
        return [
            'message' => 'SubCategory Deleted !'
        ];
    }
    public function sub_category(Request $request){
        $subcategory = new Category();
        $subcategory = Category::where([['is_active',1],['parent_id',$request->category_id],])->get();
        return ['sub_category'=>$subcategory];
    }
    public function activeSubCategory(Request $request){

        $validator = \Validator::make($request->all(), ['category_id'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        return SubCategory::with('subcategory')->where([['is_active',1],['parent_id',0],['category_id',$request->category_id]])->get();
    }
    
    public function vendorSubCategory(Request $request){

        $validator = \Validator::make($request->all(), ['category_id'=>'required|numeric','vendor_id'=>'required|numeric','start_limit'=>'required|numeric','end_limit'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
       $start_limit= $request->start_limit;
       $end_limit= $request->end_limit;
        
        return SubCategory::with('subcategory')->leftJoin('products as p', 'p.sup_sub_category_id','=', 'sub_categories.id')->leftJoin('vendor_products as vp', 'vp.product_id','=', 'p.id')->select(['sub_categories.name','sub_categories.*'])->distinct('sub_categories.name')->where([['sub_categories.category_id',$request->category_id],['sub_categories.is_active',1],['sub_categories.parent_id',0],['vp.vendor_id',$request->vendor_id]])->skip($start_limit)->take($end_limit)->get();
    }
}
