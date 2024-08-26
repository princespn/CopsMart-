<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\MarketingBox;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;
class MarketingBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return MarketingBox::leftJoin("vendors as v",\DB::raw("FIND_IN_SET(v.id, marketing_box.vendor_id)"),">",\DB::raw("'0'"))
                            ->groupBy('marketing_box.id')
                            ->get([
                                'marketing_box.*',
                                \DB::raw("GROUP_CONCAT(v.name ORDER BY v.id) as name")
                            ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        return MarketingBox::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'marketing_name' => 'required|string|max:191',
            'vendor_id'      => 'required',
        ]);
        
        if($request->marketing_image){
            $name = time().'.' . explode('/', explode(':', substr($request->marketing_image, 0, strpos($request->marketing_image, ';')))[1])[1];
            \Image::make($request->marketing_image)->save(public_path('uploads/images/marketingbox/').$name);
            $request->merge(['marketing_image' => $name]);
        }
        if(!empty($request->marketing_image))
        {
            $request->merge(['marketing_image' => $request->marketing_image]);
        }
        else{
            $request->merge(['marketing_image' => '']);
        }
        
        $vendor_array = implode(',', $request->vendor_id);
       
        $marketing = new MarketingBox();
        $marketing->marketing_name = $request->marketing_name;
        $marketing->marketing_image = $request->marketing_image;
        $marketing->category_id = $request->category_id;
        $marketing->vendor_id = $vendor_array;
        $marketing->marketing_name = $request->marketing_name;
        $marketing->save();
        return [
            'message' => 'Created Successfully',
        ];
    }
    public function update(Request $request, $id)
    {

        $marketing_box = MarketingBox::findOrFail($id);
        $this->validate($request, [
            'marketing_name' => 'required|string|max:191',
            'vendor_id'      => 'required',
        ]);
        $oldImage = $marketing_box->marketing_image;
        if($request->marketing_image){
            $name = time().'.' . explode('/', explode(':', substr($request->marketing_image, 0, strpos($request->marketing_image, ';')))[1])[1];
            \Image::make($request->marketing_image)->save(public_path('uploads/images/marketingbox/').$name);
            $request->merge(['marketing_image' => $name]);
        }
        if(!empty($request->marketing_image))
        {
            $request->merge(['marketing_image' => $request->marketing_image]);
        }
        else{
            $request->merge(['marketing_image' => $oldImage]);
        }
        
        $vendor_array = implode(',', $request->vendor_id);
       
        MarketingBox::findOrFail($id)->update(['marketing_name' => $request->marketing_name,'marketing_image'=>$request->marketing_image,'category_id'=>$request->category_id,'vendor_id'=>$vendor_array]);
        return [
            'message' => 'Updated Successfully',
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
        $package = MarketingBox::findOrFail($id);
        $package->delete();
        // delete the subCategory
        return [
            'message' => 'Package Deleted !'
        ];
    }
    public function loadVendor(){
        return ['vendor'=>Vendor::where('is_active',1)->get()];
        
    }
    public function loadVendorCat($category_id){
        return ['vendor'=>Vendor::where([['is_active',1],['category_id',$category_id]])->get()];
    }

    public function loadProductCat($category_id){
        return ['vendor'=>Product::where([['is_active',1],['category_id',$category_id]])->get()];
        
    }
    public function marketingboxlist(Request $request){
        $validator = \Validator::make($request->all(), ['latitude'=>'required|numeric','longitude'=>'required|numeric','category_id'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }

        $latitude = $request->latitude;
        $longitude= $request->longitude;
        
        $marketing_array = [];
        if($request->category_id)
        {
            $marketing = MarketingBox::where('category_id',$request->category_id)->get();
        }
        else{
            $marketing = MarketingBox::get();
        }
        
        
        foreach ($marketing as $row) {
            $response['id'] = $row->id;
            $response['marketing_name'] = $row->marketing_name;
            $response['marketing_image'] = $row->marketing_image;
            $response['vendors'] = '';
            $vendor_id = explode(',', $row->vendor_id);
            $vendors = Vendor::selectRaw("id, name,shop_image, address,about_vendor, latitude, longitude,open, service_range, email ,contact_no,return_replacement,super_category_id,category_id,is_active,
                         ( 6371 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$latitude, $longitude, $latitude])
            ->whereIn('id',$vendor_id)
            ->havingRaw('distance <= service_range/1000')
            ->orderBy("distance",'asc')
            ->get();
            $response['vendors'] = $vendors;
            array_push($marketing_array, $response);
            
        }
        return $marketing_array;
    }
}
