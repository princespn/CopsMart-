<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\AdminCategory;

class AdminSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoryId)
    {
        return AdminCategory::where('parent_id', $categoryId)->get();
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
            \Image::make($request->image)->save(public_path('uploads/images/admin_sub_category/').$name);
            $request->merge(['image' => $name]);
        }
        $category = new AdminCategory();
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->image = $request->image;
        $category->save();
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
        return AdminCategory::find($id);
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

        $category = AdminCategory::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'parent_id' => 'required|numeric'
        ]);
        $oldImage = $category->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/admin_sub_category/').$name);
            $request->merge(['image' => $name]);
        }
        if(!empty($request->image))
        {
            $request->merge(['image' => $request->image]);
        }
        else{
            $request->merge(['image' => $oldImage]);
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
        $slab = AdminCategory::findOrFail($id);
        return ['success' => $slab->delete() ] ;
    }
}
