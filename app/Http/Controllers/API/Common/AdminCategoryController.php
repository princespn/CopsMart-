<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdminCategory;
use App\CategoryDeliverySlab;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function allCategory(){
        return DB::table('admin_categories as c')->select("c.*",
                  \DB::raw("(SELECT count(*) FROM admin_categories
                          WHERE parent_id = c.id
                        ) as count"))->where('c.parent_id','0')->get();
    }

    public function category_by_id(Request $request){
        return AdminCategory::where('parent_id',$request->category_id)->where('is_active',1)->get();
    }
    public function activeCategory(){
        return AdminCategory::with('sub_category')->where([['is_active',1],['parent_id',0]])->get();
    }
    public function index(Request $request)
    {
        // return Category::latest()->paginate(10);
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');

        $query = AdminCategory::dataTableQuery($column, $orderBy, $searchValue);
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
            'image' => 'sometimes',
            
        ]);
        $slabRules = [];

        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/admin_category/').$name);
            $request->merge(['image' => $name]);
        }
        $data = $request->only('name', 'image');
        $categoryId = AdminCategory::create($data)->id;

        if(isset($request->subcategory_name)){
            if(!empty($request->subcategory_name)){
                for ($index=0;$index<count($request->subcategory_name);$index++) {
                    $category_save = new AdminCategory();
                    $category_save->parent_id = $categoryId;
                    $category_save->name = $request->subcategory_name[$index];
                    $category_save->save();
                }
            }
        }


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
        return AdminCategory::findOrFail($id);
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
        $category = AdminCategory::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
        ]);

        $oldImage = $category->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/admin_category/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/admin_category/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
        $data = $request->only('name', 'image','is_active');
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
        $category = AdminCategory::findOrFail($id);
        DB::table('admin_categories')->where('parent_id', $category->id)->delete();
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }

    
}
