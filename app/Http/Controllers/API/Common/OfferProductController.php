<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\OfferProduct;
use App\Category;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;
class OfferProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OfferProduct::leftJoin('categories as c', 'c.id','=', 'offered_products.category_id')->leftJoin('sub_categories as sc', 'sc.id','=', 'offered_products.sup_sub_category_id')->select(['offered_products.*','c.name as cname','sc.name as scname'])->get();
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'offer_title' => 'required|string|max:191',
            'category_id' => 'required|numeric',
        ]);

         if($request->offer_image){
            $name = time().'.' . explode('/', explode(':', substr($request->offer_image, 0, strpos($request->offer_image, ';')))[1])[1];
            \Image::make($request->offer_image)->save(public_path('uploads/images/offerproduct/').$name);
            $request->merge(['offer_image' => $name]);
        }

        $offerproduct = new OfferProduct();
        $offerproduct->offer_title = $request->offer_title;
        $offerproduct->category_id = $request->category_id;
        $offerproduct->sup_sub_category_id = $request->sup_sub_category_id;
        $offerproduct->offer_image = $request->offer_image;
        $offerproduct->created_at = date('Y-m-d H:i:s');
        $offerproduct->updated_at = date('Y-m-d H:i:s');
        $offerproduct->save();

        
        return [
            'message' => 'created Successfully',
        ];
    }
    
    public function show($id)
    {
        return OfferProduct::findOrFail($id);
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
        $offerproduct = OfferProduct::findOrFail($id);
        $this->validate($request, [
            'offer_title' => 'required|string|max:191',
            'category_id' => 'required|numeric',
        ]);
        $oldImage = $offerproduct->offer_image;
        if($request->offer_image){
            $name = time().'.' . explode('/', explode(':', substr($request->offer_image, 0, strpos($request->offer_image, ';')))[1])[1];
            \Image::make($request->offer_image)->save(public_path('uploads/images/offerproduct/').$name);
            $request->merge(['offer_image' => $name]);
        }
        if(!empty($request->offer_image))
        {
            $request->merge(['offer_image' => $request->offer_image]);
        }
        else{
            $request->merge(['offer_image' => $oldImage]);
        }
        OfferProduct::findOrFail($id)->update(['offer_title' => $request->offer_title,'offer_image'=>$request->offer_image,'category_id'=>$request->category_id,'sup_sub_category_id'=>$request->sup_sub_category_id,'updated_at'=>date('Y-m-d H:i:s')]);
        return [
            'message' => 'Updated Successfully',
        ];
    }

   
    public function destroy($id)
    {
        $offerproduct = OfferProduct::findOrFail($id);
        $offerproduct->delete();
        // delete the subCategory
        return [
            'message' => 'Offer Product Deleted !'
        ];
    }
    public function loadVendor(){
        return Vendor::where('is_active',1)->get();
    }
    public function offerproductlist(){
        return OfferProduct::with('offer_products')->get();
    }
}
