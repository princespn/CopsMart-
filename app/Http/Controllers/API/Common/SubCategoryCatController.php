<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SubCategory;
use App\CoSubCategory;

class SubCategoryCatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoryId)
    {
        return SubCategory::with('co_subcategory')->where('parent_id', $categoryId)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($categoryId)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $categoryId)
    {

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'parent_id' => 'required|numeric',
        ]);

         if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/category/').$name);
            $request->merge(['image' => $name]);
        }

        $category = new SubCategory();
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->image = $request->image;
        //$category->co_sub_category = implode(',',$request->co_sub_category_name);
        $category->save();

        foreach ($request->co_sub_category_name as $key => $value) {
            if($value=='')
                continue;
            $co_subcategory =  new CoSubCategory();
            $co_subcategory->super_sub_category_id =  $request->parent_id;
            $co_subcategory->sub_category_id =  $category->id;
            $co_subcategory->co_sub_category_name =  $value;
            $co_subcategory->save();
        }
        

        return [
            'message' => 'created Successfully',
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
        return SubCategory::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$categoryId, $id)
    {

        $category = SubCategory::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'parent_id' => 'required|numeric'
        ]);
        $oldImage = $category->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/sub_category/').$name);
            $request->merge(['image' => $name]);
        }
        if(!empty($request->image))
        {
            $request->merge(['image' => $request->image]);
        }
        else{
            $request->merge(['image' => $oldImage]);
        }

        $co_subcategory =  new CoSubCategory();
        $co_subcategory->where('sub_category_id',$request->id)->delete();

        foreach ($request->co_sub_category_name as $key => $value) {
            if($value=='')
                continue;
            $co_subcategory =  new CoSubCategory();
            $co_subcategory->super_sub_category_id =  $request->parent_id;
            $co_subcategory->sub_category_id =  $request->id;
            $co_subcategory->co_sub_category_name =  $value;
            $co_subcategory->save();
        }

        $data = $request->only('parent_id', 'name', 'image');
        if($category->update($data)){
            return [
                'message' => 'Updated Successfully',
                'image' => $request->image
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryId,$id)
    {
        $slab = SubCategory::findOrFail($id);
        return ['success' => $slab->delete() ] ;
    }

    private function getCategorySlabValidationRules(Request $request, $id){
        $rules =[];
        $rules['name'] = 'required';
        $rules['parent_id'] = 'required';
        return $rules;
    }
}
