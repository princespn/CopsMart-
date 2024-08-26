<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Product;
use App\User;
use App\CategoryDeliverySlab;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function allCategory(){
        $id=$_GET['a'];
        $dsc=[];
        $data= DB::table('categories as c')->where('c.deleted_at', '=', Null)->where('c.parent_id','0')->whereNotIn('c.id',[0])->get();
        foreach($data as $key)
        {
            $dt1=Category::where('deleted_at',NULL)->where('parent_id',$key->id)->get();
            $dt=count($dt1);
            $key->count=$dt;
            $dsc[]=$key;
        }
        return $dsc;
    }

    public function dataindex(Request $request,$id)
    {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        $query = DB::table('categories as c')->select("c.*",
        \DB::raw("(SELECT count(*) FROM categories
               WHERE parent_id = c.id
             ) as count"))->where('c.parent_id','0')->whereNotIn('c.id',[0]);
       if($searchValue!=''){
           $query->where('categories.name', "LIKE", "%$searchValue%");
       }
       $data = $query->paginate($length);
       $dt=[];
       if(count($data)>0){
           $dt=[];
           $i=1;
           foreach($data as $key)
           {   
               $key->srno=$i;
               $dt[]=$key;
               $i++;
           }
       }
           $dt=$data;
       return new DataTableCollectionResource($dt);
    }
    

    public function supercategory($id){
        return DB::table('categories as c')->select("c.*",
        \DB::raw("(SELECT count(*) FROM categories
                WHERE parent_id = c.id and deleted_at = Null
              ) as count"))->where('c.parent_id','0')->where('c.deleted_at', '=', Null)->where('c.is_active',1)->get();
    }
    public function supersubcategory($id){
        return DB::table('categories as c')->select("c.*")->where('c.deleted_at', '=', Null)->where('c.parent_id',$id)->where('c.is_active',1)->get();
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
    
    
    public function activeCatCategory($id){
         $cat=Category::with('category')->where([['is_active',1],['deleted_at',NULL],['parent_id',0]]);
           $searchValue=$_GET['pro_name'];
        if($searchValue!=''){
            $cat->where('name', "LIKE", "%$searchValue%");
        }
       $cat=$cat->get();
       
        $dt=[];
        foreach($cat as $key)
        {
            $key->count=count(Product::where('category_id',$key->id)->get());
            $dt[]=$key;
        }
        if(count($dt)>0)
        {
          return $dt;
        }
        else
        {
            return ['msg'=>'No Data Found'];
        }
    }
    
    
    public function index(Request $request)
    {
        // return Category::latest()->paginate(10);
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');

        $query = Category::where('vendor_id','4');
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
            //'marketing_commission_percentage' => 'sometimes|nullable|numeric',
            // 'discount_percentage' => 'sometimes|nullable|numeric',
            // 'rank' => 'required|numeric',
            // 'commodity_type_id' => 'required|numeric',
            'image'=>'required',
        ]);
        $slabRules = [];

        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/category/').$name);
            $request->merge(['image' => $name]);
        }
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name','vendor_id','image');
        $categoryId = Category::create($data)->id;
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
        return Category::findOrFail($id);
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
        $category = Category::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',

        ]);

        $oldImage = $category->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/category/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/category/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name', 'image', 'vendor_id','is_active');
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
        $category = Category::findOrFail($id);
        $product=Product::where('category_id',$id)->get();
        if(count($product)>0)
        {
            return [
                'status'=>202,
                'message' => "Can't Delete Category Alloted To Product !"
            ];
        }
        else
        {
            $category->delete();
            // delete the category
            return [
                'message' => 'Category Deleted !'
            ];
        }
       
    }
}
