<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CategoryDeliverySlab;

class CategorySlabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoryId)
    { 
        
        return CategoryDeliverySlab::where('category_id', $categoryId)->get();
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
        $rules = $this->getCategorySlabValidationRules($request, $categoryId);
        $this->validate($request, $rules);
        $this->getCategorySlabValidationRules($request, $categoryId);
        $data = $request->only('category_id', 'limit_start', 'limit_end', 'charges', 'type');
        return [
            'created' => CategoryDeliverySlab::create($data)
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
        return CategoryDeliverySlab::find($id);
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
        $rules = $this->getCategorySlabValidationRules($request, $categoryId);

        $rules['limit_end'] = 'required|numeric|unique:category_delivery_slabs,limit_end,null,id<>'.$categoryId.',category_id,' . $id;
        $this->validate($request, $rules);
        $slab = CategoryDeliverySlab::findOrFail($id);
        $data = $request->only('category_id', 'limit_start', 'limit_end', 'charges', 'type');
        return [
            'updated' => $slab->update($data)
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryId,$id)
    {
        $slab = CategoryDeliverySlab::findOrFail($id);
        return ['success' => $slab->delete() ] ;
    }

    private function getCategorySlabValidationRules(Request $request, $id){
        $rules =[];
        $rules['limit_start'] = 'required|numeric';
        $rules['limit_end'] = 'required|numeric|unique:category_delivery_slabs,limit_end,NULL,NULL,category_id,' . $id;
        $rules['category_id'] = 'required|numeric';
        $rules['type'] = 'required|numeric';
        $rules['charges'] = 'required|numeric';

        return $rules;
    }
}
