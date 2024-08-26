<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Attribute;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function allCategory($a){
        return Attribute::get();
    }

    public function AttributeVendor($a){
        return Attribute::where('is_active',1)->where('type','attribute')->get();
    }

    public function TagsVendor($a){
        return Attribute::where('is_active',1)->where('type','tag')->get();
    }
    

    public function allCategoryid(){
        $id=$_GET['ad'];
        if($id=='1')
        {
        return DB::table('categories as c')->select("c.*",
                  \DB::raw("(SELECT count(*) FROM categories
                          WHERE parent_id = c.id
                        ) as count"))->where('c.parent_id','0')->get();
        }
        else
        {   
            $user=User::where('id',$id)->first();
            $cat=$user->category_id;
            if($cat=='0'){
                return DB::table('categories as c')->select("c.*",
                \DB::raw("(SELECT count(*) FROM categories
                        WHERE parent_id = c.id
                      ) as count"))->where('c.parent_id','0')->get();
            }
            else
            {
                return DB::table('categories as c')->select("c.*",
                  \DB::raw("(SELECT count(*) FROM categories
                          WHERE parent_id = c.id
                        ) as count"))->whereIn('id', [$cat])->where('c.parent_id','0')->get();
            }
            
        }
    }


    public function category_by_id(Request $request){
        return Category::where('parent_id',$request->category_id)->where('is_active',1)->get();
    }
    public function activeCategory(){
        return Category::with('category')->where([['is_active',1],['parent_id',0]])->orderBy('rank','ASC')->get();
    }
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
    public function store(Request $request)
    {     
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'type' => 'required',
        ]);  
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name','type','vendor_id');
        $categoryId = Attribute::create($data)->id;
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
        return Attribute::findOrFail($id);
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
        $category = Attribute::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'type' => 'required|string|max:191',
        ]);
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name','type','vendor_id','is_active');
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
        $category = Attribute::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }

  
}
