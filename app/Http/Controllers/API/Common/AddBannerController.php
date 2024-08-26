<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Brand;
use App\TopBanner;
use App\BottomBanner;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class AddBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   
    
    public function index(Request $request)
    {
        // return Category::latest()->paginate(10);
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');

        $query = Category::dataTableQuery($column, $orderBy, $searchValue);
        $data = $query->paginate($length);

        return new DataTableCollectionResource($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddTopBanner(Request $request)
    {     
        // print_r($request->all());
        // exit;
        $this->validate($request, [
            'type' => 'required|string|max:200',
            'till_date' => 'required',
            'images' => 'required',
            'title' => 'required',
        ]);
        if($request->type=='Product')
        {
            $this->validate($request, [
                'product_id' => 'required',
            ]);
        }
        elseif($request->type=='Category')
        {

           // echo "Aaa";
            $this->validate($request, [
                'category_id' => 'required',
            //     'sub_category_id' => 'required',
            //     'sub_sub_category_id' => 'required',
             ]);
        }
        elseif($request->type=='Brand')
        {
            $this->validate($request, [
                'brand_id' => 'required',
            ]);
        }

        if($request->images){
            $name = time().'.' . explode('/', explode(':', substr($request->images, 0, strpos($request->images, ';')))[1])[1];
            \Image::make($request->images)->save(public_path('uploads/images/banner/').$name);
            $request->merge(['image' => $name]);
        }
        $request->merge(['is_active' =>1]);
        $data = $request->only('vendor_id','title','type','product_id','category_id','sub_category_id','sub_sub_category_id','brand_id','image','till_date','is_active');
        $categoryId = TopBanner::create($data)->id;
        return [
            'category' => $categoryId
        ];
    }


    public function AddBottomBanner(Request $request)
    {     
        // print_r($request->all());
        // exit;
        $this->validate($request, [
            'type' => 'required|string|max:200',
            'till_date' => 'required',
            'images' => 'required',
            'title' => 'required',
        ]);
        if($request->type=='Product')
        {
            $this->validate($request, [
                'product_id' => 'required',
            ]);
        }
        elseif($request->type=='Category')
        {

            echo "Aaa";
            $this->validate($request, [
                'category_id' => 'required',
                // 'sub_category_id' => 'required',
                // 'sub_sub_category_id' => 'required',
            ]);
        }
        elseif($request->type=='Brand')
        {
            $this->validate($request, [
                'brand_id' => 'required',
            ]);
        }

        if($request->images){
            $name = time().'.' . explode('/', explode(':', substr($request->images, 0, strpos($request->images, ';')))[1])[1];
            \Image::make($request->images)->save(public_path('uploads/images/banner/').$name);
            $request->merge(['image' => $name]);
        }
        $request->merge(['is_active' =>1]);
        $data = $request->only('vendor_id','title','type','product_id','category_id','sub_category_id','sub_sub_category_id','brand_id','image','till_date','is_active');
        $categoryId = BottomBanner::create($data)->id;
        return [
            'category' => $categoryId
        ];
    }

    public function TopBannerData($id)
    {
       return $categoryId = TopBanner::where('vendor_id',$id)->get();
    }

    public function BottomBannerData($id)
    {
       return $categoryId = BottomBanner::where('vendor_id',$id)->get();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Brand::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateTopBanner(Request $request, $id)
    {
        $category = TopBanner::findOrFail($id);
        // $this->validate($request, [
        //     'name' => 'required|string|max:191',
        // ]);

        $oldImage = $category->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/banner/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/banner/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
        $data =  $data = $request->only('vendor_id','title','type','product_id','category_id','sub_category_id','sub_sub_category_id','brand_id','image','till_date','is_active');
        $category->update($data);
        return [
            'message' => 'Updated Successfully'
        ];
    }

    public function UpdateBottomBanner(Request $request, $id)
    {
        $category = BottomBanner::findOrFail($id);
        // $this->validate($request, [
        //     'name' => 'required|string|max:191',
        // ]);

        $oldImage = $category->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/banner/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/banner/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
        $data =  $data = $request->only('vendor_id','title','type','product_id','category_id','sub_category_id','sub_sub_category_id','brand_id','image','till_date','is_active');
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
        $category = Brand::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }
    

    public function deleteBottombanner($id)
    {
        $category = BottomBanner::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Banner Deleted !'
        ];
    }

    public function deleteTopbanner($id)
    {
        $category = TopBanner::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Banner Deleted !'
        ];
    }

  
}
