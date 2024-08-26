<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Brand;
use App\Product;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function allCategory($a){
        return Brand::where('is_active',1)->get();
    }

    public function allCategory_vendor($a){
        return Brand::where('deleted_at',NULL)->get();
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
    
    public function brandId($a){
         $brand=Brand::where('deleted_at',NULL);
          $searchValue=$_GET['pro_name'];
        if($searchValue!=''){
            $brand->where('name', "LIKE", "%$searchValue%");
        }
        $data=$brand->get();
        $dt=[];
        foreach($data as $key)
        {
            $key->count=count(Product::where('brand_id',$key->id)->get());
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
    
    
    public function NewBrandList(Request $request,$a){
         
         $searchValue=$_POST['pro_name'];
         $total_records_per_page =$_POST['total_cnt'];
         $page_no=$_POST['page_no'];
         $offset = ($page_no-1) * $total_records_per_page;
         //
        $brand=Brand::where('deleted_at',NULL);
        if($searchValue!=''){
            $brand->where('name', "LIKE", "%$searchValue%");
        }
        $data=$brand->get();
        $cnt=count($data);
        $total_pages=round($cnt/$total_records_per_page);
        
        if($cnt>0)
        {   
            $brandx=Brand::where('deleted_at',NULL);
            if($searchValue!=''){
                $brandx->where('name', "LIKE", "%$searchValue%");
            }
            $datas=$brandx->offset($offset)->limit($total_records_per_page)->get();
            $nd=[];
            $dt=[];
            foreach($datas as $key)
            {
                $key->count=count(Product::where('brand_id',$key->id)->get());
                $dt[]=$key;
            }
            
            $nd['count']=$cnt;
            $nd['total_pages']=$total_pages;
            $nd['data']=$dt;
            if(count($dt)>0)
            {
              return $nd;
            }
            else
            {
                return ['msg'=>'No Data Found'];
            }
        }else
        {
             return ['msg'=>'No Data Found'];
        }
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
            'image'=>'required',
        ]);
        $slabRules = [];

        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/brand/').$name);
            $request->merge(['image' => $name]);
        }
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name','image','vendor_id');
        $categoryId = Brand::create($data)->id;
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
        return Brand::findOrFail($id);
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
        $category = Brand::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
        ]);

        $oldImage = $category->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/brand/').$name);
            $request->merge(['image' => $name]);
            $oldImage = public_path('uploads/images/brand/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
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
        $category = Brand::findOrFail($id);
        $product=Product::where('brand_id',$id)->get();
        if(count($product)>0)
        {
            return [
                'status'=>202,
                'message' => "Can't Delete Brand Alloted To Product!"
            ];
        }
        else
        {
            $category->delete();
            // delete the category
            return [
                'message' => 'Brand Deleted !'
            ];
        }
       // $category->delete();
        // delete the category
        // return [
        //     'message' => 'Category Deleted !'
        // ];
    }

  
}
