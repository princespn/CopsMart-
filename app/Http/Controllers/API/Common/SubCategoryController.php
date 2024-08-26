<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SubCategory;
use App\Category;
use App\Product;
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

    public function subCategoryPost(Request $request)
    {   
        $query= SubCategory::leftJoin('categories as c', 'c.id','=', 'sub_categories.super_category_id')->leftJoin('categories', 'categories.id','=', 'sub_categories.category_id')->select(['sub_categories.*','sub_categories.id as id', 'categories.name as category_name','c.name as super_category','sub_categories.name as name'])->where('sub_categories.parent_id','0');
        $qparam = [];
        if($request->sub_category_id!='' && $request->sub_category_id!='All'){
            array_push($qparam, ['sub_categories.category_id','=',$request->sub_category_id]);
        }
        if($request->category_id!='' && $request->category_id!='All'){
            array_push($qparam, ['sub_categories.super_category_id','=',$request->category_id]);
        }
        
        // if($request->sub_category_id!='' && $request->sub_category_id!='All'){
        //     $query->where('category_id',$request->sub_category_id);
        // }
        // if($request->category_id!='' && $request->sub_category_id!='All'){
        //     $query->where('super_category_id',$request->category_id);
        // }
        // print_r($qparam);exit;
        return $query->where($qparam)->get();
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
            'super_category_id' => 'required|numeric',
            'is_active' => 'boolean',
            'image'=>'required'
        ]);
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/sub_category/').$name);
            $request->merge(['image' => $name]);
        }
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name','admin_id','vendor_id','super_category_id','category_id', 'is_active', 'image');
        $subcategoryId = SubCategory::create($data)->id;
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
            'name' => 'required|string|max:191',
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
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
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
        $product=Product::where('sub_sub_category_id',$id)->get();
        if(count($product)>0)
        {
            return [
                'status'=>202,
                'message' => "Can't Delete Category Alloted To Product !"
            ];
        }
        else
        {
            $subCategory->delete();
            // delete the category
            return [
                'message' => 'SubCategory Deleted !'
            ];
        }
    
     
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

        $validator = \Validator::make($request->all(), ['category_id'=>'required|numeric','vendor_id'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        //$start_limit= $request->start_limit;
        //$end_limit= $request->end_limit;
        
        return SubCategory::with('subcategory')->leftJoin('products as p', 'p.sup_sub_category_id','=', 'sub_categories.id')->leftJoin('vendor_products as vp', 'vp.product_id','=', 'p.id')->select(['sub_categories.name','sub_categories.*','p.product_type as product_type'])->distinct('sub_categories.name')->where([['sub_categories.category_id',$request->category_id],['sub_categories.is_active',1],['sub_categories.parent_id',0],['vp.vendor_id',$request->vendor_id]])->get();
    }
    public function vendorSubCategoryPagination(Request $request){

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
    public function SubCatget($cataid)
    {
       $data = Category::where('parent_id',$cataid)->where('deleted_at', '=', Null);
        $searchValue=$_GET['pro_name'];
        if($searchValue!=''){
            $data->where('name', "LIKE", "%$searchValue%");
        }
       $data=$data->get();
       $dbdata=[];
       foreach ($data as $key)
       {  
           $key->count=count(Product::where('sub_category_id',$key->id)->get());
           $dt=SubCategory::where('category_id',$key->id)->get();
           $key->sub_sub_categories=$dt;
           $dbdata[]=$key;
       }
       return $dbdata;
       
    }
    public function sub_sub_category($cataid)
    {

           $dt=SubCategory::where('category_id',$cataid)->where('deleted_at', '=', Null)->get();
           
       return $dt;
       
    }
    public function Getsubcata($subcat)
    {
       $sub= SubCategory::leftJoin('categories as c', 'c.id','=', 'sub_categories.super_category_id')
       ->leftJoin('categories', 'categories.id','=', 'sub_categories.category_id')
       ->select(['sub_categories.*','sub_categories.id as id', 'categories.name as category_name','c.name as super_category','sub_categories.name as name'])
       ->where('sub_categories.parent_id','0')->where('sub_categories.deleted_at', '=', Null)->where('sub_categories.category_id',$subcat);
         $searchValue=$_GET['pro_name'];
        if($searchValue!=''){
            $sub->where('sub_categories.name', "LIKE", "%$searchValue%");
        }
       
       
    $sub= $sub->get(); 
    $dbdata=[];
       foreach ($sub as $key)
       {  
           $key->count=count(Product::where('sub_category_id',$key->id)->get());
           $dbdata[]=$key;
       }
       return $dbdata;
        
    }
    
    
    
    public function NewGetsubcata(Request $request,$subcat)
    {
            $sub= SubCategory::leftJoin('categories as c', 'c.id','=', 'sub_categories.super_category_id')
            ->leftJoin('categories', 'categories.id','=', 'sub_categories.category_id')
            ->select(['sub_categories.*','sub_categories.id as id', 'categories.name as category_name','c.name as super_category','sub_categories.name as name'])
            ->where('sub_categories.parent_id','0')->where('sub_categories.deleted_at', '=', Null)->where('sub_categories.category_id',$subcat);
            $searchValue=$_POST['pro_name'];
            $total_records_per_page =$_POST['total_cnt'];
            $page_no=$_POST['page_no'];
            $offset = ($page_no-1) * $total_records_per_page;
            if($searchValue!=''){
            $sub->where('sub_categories.name', "LIKE", "%$searchValue%");
            }
            $sub= $sub->get(); 
            $dbdata=[];
            $cnt=count($sub);
            if($cnt>0)
            {   
                $total_pages=ceil($cnt/$total_records_per_page);
                $subx= SubCategory::leftJoin('categories as c', 'c.id','=', 'sub_categories.super_category_id')
                ->leftJoin('categories', 'categories.id','=', 'sub_categories.category_id')
                ->select(['sub_categories.*','sub_categories.id as id', 'categories.name as category_name','c.name as super_category','sub_categories.name as name'])
                ->where('sub_categories.parent_id','0')->where('sub_categories.deleted_at', '=', Null)->where('sub_categories.category_id',$subcat);
                $searchValue=$_POST['pro_name'];
                if($searchValue!=''){
                $subx->where('sub_categories.name', "LIKE", "%$searchValue%");
                }
                $subx= $subx->offset($offset)->limit($total_records_per_page)->get(); 
                $dbdatax=[];
                foreach ($subx as $key)
                {  
                    $key->count=count(Product::where('sub_category_id',$key->id)->get());
                    $dbdatax[]=$key;
                }
               $dbdata['count']=$cnt;
               $dbdata['total_pages']=$total_pages;
               $dbdata['data']=$dbdatax;
            }
            
            return $dbdata;
        
    }
}
