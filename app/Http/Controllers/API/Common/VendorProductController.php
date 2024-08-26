<?php
namespace App\Http\Middleware;
namespace App\Http\Controllers\API\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VendorProduct;
use App\Vendor;
use App\Makeabill;
use App\User;
use App\ProductImage;
use App\ProductRecomend;
use App\LastSearch;
use App\Product;
use App\ProductReview;
use App\Size;
use App\Stock;
use App\StockFetch;
use App\Color;
use App\AddCart;
use App\Category;
use App\SubCategory;
use App\Brand;
use App\Wishlist;
use Illuminate\Support\Facades\Auth;
use DB;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
class VendorProductController extends Controller
{
    
    //use AuthenticatesUsers;
    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function listByVendor($vendorId)
    {
        return VendorProduct::with('product')->where(['vendor_id'=>$vendorId])->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/


    public function getVendorByCategoryAndLocation(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
        ]);

        $getRange = $this->getCoordinatesRange($request->lat,$request->long, 12000);

        $vendor = DB::table('vendors')
                ->where('vendors.category_id', $request->category_id)
                ->where('vendors.open', 1)
                ->where('vendors.is_active', 1)
                ->select('vendors.*')
                ->whereBetween('latitude', [$getRange['min_lat'], $getRange['max_lat']])
                ->whereBetween('longitude', [$getRange['min_long'], $getRange['max_long']])
                ->first();
        $sub_category=null;
        if(isset($vendor->id)){
            $sub_category= DB::table('vendor_products')
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
            ->where('vendor_products.vendor_id', $vendor->id)
            ->where('sub_categories.category_id', $request->category_id)
            ->where('sub_categories.is_active', 1)
            ->select('sub_categories.*')
            ->groupBy('sub_categories.id')
            ->get();
        }


        $data['vendor'] = $vendor;
        $data['subCategories'] = $sub_category;
        return $data;

    }
    
    public function getVendorNewlist(Request $request)
    {
        $nm=$_GET['name'];
        $vendor = DB::table('vendors')
                ->where('app_name','LIKE', $nm)
                // ->where('vendors.open', 1)
                ->where('vendors.is_active', 1)
                ->select('vendors.*')
                ->get();
        // $sub_category=null;
        // echo count($vendor);
        if(count($vendor)>0){
          foreach($vendor as $key){
              $us=DB::table('users')->where('admin_id',$key->id)->first();
              $key->vendor_id=$us->id; 
          }
        }
        // $data['vendor'] = $vendor;
        // $data['subCategories'] = $sub_category;
        return $vendor;

    }


    private function getCoordinatesRange($lat,$long, $diameter){
        // number of km per degree = ~111km (111.32 in google maps, but range varies
        //between 110.567km at the equator and 111.699km at the poles)
        // 1km in degree = 1 / 111.32km = 0.0089
        // 1m in degree = 0.0089 / 1000 = 0.0000089
        $coef = ($diameter/2) * 0.0000089;

        $min_lat = $lat - $coef;
        $max_lat = $lat + $coef;

        // pi / 180 = 0.018
        $min_long = $long - $coef / cos($lat * 0.018);
        $max_long = $long + $coef / cos($lat * 0.018);

        return [
            'min_lat' =>$min_lat,
            'max_lat' =>$max_lat,
            'min_long'=>$min_long,
            'max_long' =>$max_long
        ];
    }
    
    

    public function productsByVendor(Request $request,$vendor)
    {
        $sup_sub_category_id = $request->sub_sub_category_id;
        $sub_category_id = $request->sub_category_id;
        $category_id = $request->category_id;
        $userid = $request->user_id;
        $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        if($sup_sub_category_id=='' && $sub_category_id=='' && $category_id=='')
        {
          return response()->json(['error_message'=>'Please use valid Feilds'], 422);
        }
        $products = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('products.deleted_at',NULL)->where('stock_fetches.quantity','>',0)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);
        $searchValue=$request->pro_name;
         if($searchValue!=''){
            $products->where('products.name', "LIKE", "%$searchValue%");
        }
        $products->orderBy('stock_fetches.quantity', 'desc');
        $dp=[];
        $qparam = [];
        if($sup_sub_category_id){
            array_push($qparam, ['products.sub_sub_category_id','=',$sup_sub_category_id]);
        }
        if($sub_category_id){
            array_push($qparam, ['products.sub_category_id','=',$sub_category_id]);
        }
        if($category_id){
            array_push($qparam, ['products.category_id','=',$category_id]);
        }
        $products = $products->where($qparam)->get();
        $ds=[];   $ddss=[];
        foreach ($products as $key)
        {  
            $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardt)>0)
            {
                $key->is_cart=1;
            }
            else
            {
                $key->is_cart=0;
            }
           // $key->cart=$cardt;
            $size=$key->size;
            $key->rating=4.5;
            $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardtx)>0)
            {
               $key->is_wish=1;
               $key->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $key->is_wish=0;
                $key->is_wish_id=null;
            }
            
            $key->is_request=0;
           
            $key->percentshow=$vendordetails->online;
            $key->salescount=0;
            $sar=explode(',',$size);
            $catt=Category::where('id',$key->category_id)->first();
            $key->category_name=$catt->name;
            $catts=Category::where('id',$key->sub_category_id)->first();
            $key->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
            $key->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$key->brand_id)->first();
            $key->brand_name=$brand->name;
            $sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($cardtx)>0)
                {
                   $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                   $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $key->size_array=$dss;
           // $dss=[];
           // $key->s1=$sar[0];
            $color=$key->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                   $kks->selected_color_index=$cardtxx[0]->color_index; 
                    $kks->selected_color=$cardtxx[0]->color; 
                   $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $key->color_array=$ddss;
            if($key->variable=='')
            {
                    $key->name=$key->name;
            }
            else
            {
                $key->name=$key->name.'-'.$key->variable;;
            }
            //$key->s12=$sarx[0];
            $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$key->stocksize)->where('color',$key->stockcolor)->where('price',$key->sales_rate)->where('is_active',1)->get();
            $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
            $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
            $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
            $dp[]=$key;
        }
        
       // $obj['ProductList'] = $dp;
        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }
    
    //new
    public function productsByVendorNew(Request $request,$vendor)
    {
        $sup_sub_category_id = $request->sub_sub_category_id;
        $sub_category_id = $request->sub_category_id;
        $category_id = $request->category_id;
        $userid = $request->user_id;
        $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        if($sup_sub_category_id=='' && $sub_category_id=='' && $category_id=='')
        {
          return response()->json(['error_message'=>'Please use valid Feilds'], 422);
        }
        $products = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('products.deleted_at',NULL)->where('stock_fetches.quantity','>',0)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);
        $searchValue=$request->pro_name;
         if($searchValue!=''){
            $products->where('products.name', "LIKE", "%$searchValue%");
        }
        $products->orderBy('stock_fetches.quantity', 'desc');
        $dp=[];
        $qparam = [];
        if($sup_sub_category_id){
            array_push($qparam, ['products.sub_sub_category_id','=',$sup_sub_category_id]);
        }
        if($sub_category_id){
            array_push($qparam, ['products.sub_category_id','=',$sub_category_id]);
        }
        if($category_id){
            array_push($qparam, ['products.category_id','=',$category_id]);
        }
        $products = $products->where($qparam)->get();
        $cnt=count($products);
        $total_records_per_page =$_POST['total_cnt'];
        $page_no=$_POST['page_no'];
        $offset = ($page_no-1) * $total_records_per_page;
        if($cnt==0)
        {
           $dp=[]; $ds=[];   $ddss=[];
        }
        else
        {  
            $total_pages=ceil($cnt/$total_records_per_page);
            $productsa = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('products.deleted_at',NULL)->where('stock_fetches.quantity','>',0)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);
            $searchValue=$request->pro_name;
             if($searchValue!=''){
                $productsa->where('products.name', "LIKE", "%$searchValue%");
            }
            $productsa->orderBy('stock_fetches.quantity', 'desc');
            $productsa = $productsa->where($qparam)->offset($offset)->limit($total_records_per_page)->get();
             $ds=[];   $ddss=[];
            foreach ($productsa as $key)
            {  
                $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardt)>0)
                {
                    $key->is_cart=1;
                    $key->cart_id=$cardt[0]->id;
                }
                else
                {
                    $key->is_cart=0;
                   $key->cart_id='';
                }
               // $key->cart=$cardt;
                $size=$key->size;
                $key->rating=4.5;
                $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardtx)>0)
                {
                   $key->is_wish=1;
                   $key->is_wish_id=$cardtx[0]->id;
                }
                else
                {
                    $key->is_wish=0;
                    $key->is_wish_id=null;
                }
                
                $key->is_request=0;
               
                $key->percentshow=$vendordetails->online;
                $key->salescount=0;
                $sar=explode(',',$size);
                $catt=Category::where('id',$key->category_id)->first();
                $key->category_name=$catt->name;
                $catts=Category::where('id',$key->sub_category_id)->first();
                $key->sub_category_name=$catts->name;
                $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
                $key->sub_sub_category_name=$cattss->name;
                $brand=Brand::where('id',$key->brand_id)->first();
                $key->brand_name=$brand->name;
                $sizess = Size::whereIn('id',$sar)->get();
                $dss=[];
                foreach($sizess as $kk)
                {  
                    $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($cardtx)>0)
                    {
                       $kk->selected_size_index=$cardtx[0]->size_index; 
                        $kk->selected_size=$cardtx[0]->size; 
                       $kk->is_cart_qty=$cardtx[0]->qty; 
                    }
                    else
                    {
                        $kk->selected_size_index=null; 
                        $kk->is_cart_qty=0; 
                        $kk->selected_size=0;
                    }
                    $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($skk)>0)
                    {  
                        //if($skk[0]->quantity==$skk[0]->sold_quant)
                        $kk->is_size=1;
                    }
                    else
                    {
                        $kk->is_size=0;
                    }
                    $dss[]=$kk;
                }
                $key->size_array=$dss;
               // $dss=[];
               // $key->s1=$sar[0];
                $color=$key->color;
                $sarx=explode(',',$color);
                $collors= Color::whereIn('id',$sarx)->get();
                $ddss=[];
                foreach($collors as $kks)
                {   
                    $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    if(count($cardtxx)>0)
                    {
                       $kks->selected_color_index=$cardtxx[0]->color_index; 
                        $kks->selected_color=$cardtxx[0]->color; 
                       $kks->is_cart_qty=$cardtxx[0]->qty; 
                    }
                    else
                    {
                        $kks->selected_color_index=null; 
                        $kks->is_cart_qty=0; 
                        $kks->selected_color=0;
                    }
                    $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    //$kks->is_color=count($skks);
                    if(count($skks)>0)
                    {
                        $kks->is_color=1;
                    }
                    else
                    {
                        $kks->is_color=0;
                    }
                    $ddss[]=$kks;
                }
                $key->color_array=$ddss;
                if($key->variable=='')
                {
                        $key->name=$key->name;
                }
                else
                {
                    $key->name=$key->name.'-'.$key->variable;;
                }
                //$key->s12=$sarx[0];
                $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$key->stocksize)->where('color',$key->stockcolor)->where('price',$key->sales_rate)->where('is_active',1)->get();
                $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
                $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
                $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
                $dp[]=$key;
            }
        }
        if(count($dp)>0)
        {  
            $resp['count']=$cnt;
            $resp['total_pages']=$total_pages;
            $resp['data']=$dp;
            
            return $resp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }


    //last search
    public function lastSearch(Request $request,$vendor)
    {   
        $idxds=[];
         $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        $selc=LastSearch::where('vendor_id',$vendor)->where('user_id',$request->user_id)->where('is_active',1)->where('deleted_at',NULL)->orderBy('id','desc')->get();
        if(count($selc)>0)
        {
            foreach($selc as $kc)
            {
                $idxds[]=$kc->product_id;
            }
        }
        
        if(isset($selc))
        {
            $products = Product::where('vendor_id',$vendor)->whereIn('id',$idxds)->where('is_active', 1)->orderBy('name','asc')->get();
            $dp=array();
            $ds=[];   $ddss=[];
            foreach ($products as $key)
            {  
                $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardt)>0)
                {
                    $key->is_cart=1;
                }
                else
                {
                    $key->is_cart=0;
                }
               // $key->cart=$cardt;
                $size=$key->size;
                $key->rating=4.5;
                $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardtx)>0)
                {
                   $key->is_wish=1;
                   $key->is_wish_id=$cardtx[0]->id;
                }
                else
                {
                    $key->is_wish=0;
                    $key->is_wish_id=null;
                }
                
                $key->is_request=0;
                 $key->percentshow=$vendordetails->online;
              
                $key->salescount=0;
                $sar=explode(',',$size);
                $catt=Category::where('id',$key->category_id)->first();
                $key->category_name=$catt->name;
                $catts=Category::where('id',$key->sub_category_id)->first();
                $key->sub_category_name=$catts->name;
                $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
                $key->sub_sub_category_name=$cattss->name;
                $brand=Brand::where('id',$key->brand_id)->first();
                $key->brand_name=$brand->name;
                $sizess = Size::whereIn('id',$sar)->get();
                $dss=[];
                foreach($sizess as $kk)
                {  
                    $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($cardtx)>0)
                    {
                       $kk->selected_size_index=$cardtx[0]->size_index; 
                        $kk->selected_size=$cardtx[0]->size; 
                       $kk->is_cart_qty=$cardtx[0]->qty; 
                    }
                    else
                    {
                        $kk->selected_size_index=null; 
                        $kk->is_cart_qty=0; 
                        $kk->selected_size=0;
                    }
                    $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($skk)>0)
                    {  
                        //if($skk[0]->quantity==$skk[0]->sold_quant)
                        $kk->is_size=1;
                    }
                    else
                    {
                        $kk->is_size=0;
                    }
                    $dss[]=$kk;
                }
                $key->size_array=$dss;
               // $dss=[];
               // $key->s1=$sar[0];
                $color=$key->color;
                $sarx=explode(',',$color);
                $collors= Color::whereIn('id',$sarx)->get();
                $ddss=[];
                foreach($collors as $kks)
                {   
                    $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    if(count($cardtxx)>0)
                    {
                       $kks->selected_color_index=$cardtxx[0]->color_index; 
                        $kks->selected_color=$cardtxx[0]->color; 
                       $kks->is_cart_qty=$cardtxx[0]->qty; 
                    }
                    else
                    {
                        $kks->selected_color_index=null; 
                        $kks->is_cart_qty=0; 
                        $kks->selected_color=0;
                    }
                    $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    //$kks->is_color=count($skks);
                    if(count($skks)>0)
                    {
                        $kks->is_color=1;
                    }
                    else
                    {
                        $kks->is_color=0;
                    }
                    $ddss[]=$kks;
                }
                $key->color_array=$ddss;
                $key->s12=$sarx[0];
                $stockf=StockFetch::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
                if(isset($stockf))
                {    $key->stockfetch_id=$stockf->id;
                     $key->variable=$stockf->variable;
                     $datanewsar= $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                      $key->sizename=Size::where('id',$stockf->size)->first()->name;
                     $key->colorname=Color::where('id',$stockf->color)->first()->name;
                        $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                }
                else
                {
                   $datanewsar= $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->limit(1)->get();
                   if(count($datanewsar)>0){
                      
                       $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                   }else
                   {
                        $qtysdydyd=0;
                   }
                   $key->stockfetch_id='';
                   $key->variable='';
                    $key->sizename=Size::where('id',$sar[0])->first()->name;
                    $key->colorname=Color::where('id',$sarx[0])->first()->name;
                       
                }
                $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
                $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
                $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
              if($qtysdydyd>0)
              {
               $dp[]=$key;
              }
              //  $dp[]=$key;
            }
            
           // $obj['ProductList'] = $dp;
            if(count($dp)>0)
            {
                return $dp;
            }
            else
            {
                $response['msg']='No Data Found';
                return json_encode($response);
            }
       
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }

    
    // Recommended api
    public function getRecommended(Request $request,$vendor)
    {
        $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        $selc=ProductRecomend::where('vendor_id',$vendor)->where('is_active',1)->where('deleted_at',NULL)->first();
        
        if(isset($selc))
        {
            $products = Product::where('vendor_id',$vendor)->whereIn('id', explode(',',$selc->product_id))->where('is_active', 1);
            $searchValue=$_GET['pro_name'];
            if($searchValue!=''){
                $products->where('products.name', "LIKE", "%$searchValue%");
            }
            $products=$products->get();
            
           
            $dp=array();
            $ds=[];   $ddss=[];
            foreach ($products as $key)
            {  
                $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                
                if(count($cardt)>0)
                {
                    $key->is_cart=1;
                }
                else
                {
                    $key->is_cart=0;
                }
                
                  $key->percentshow=$vendordetails->online;
               // $key->cart=$cardt;
                $size=$key->size;
                $key->rating=4.5;
                $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardtx)>0)
                {
                   $key->is_wish=1;
                   $key->is_wish_id=$cardtx[0]->id;
                }
                else
                {
                    $key->is_wish=0;
                    $key->is_wish_id=null;
                }
                
                $key->is_request=0;
               
                
                $key->salescount=0;
                $sar=explode(',',$size);
                $catt=Category::where('id',$key->category_id)->first();
                $key->category_name=$catt->name;
                $catts=Category::where('id',$key->sub_category_id)->first();
                $key->sub_category_name=$catts->name;
                $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
                $key->sub_sub_category_name=$cattss->name;
                $brand=Brand::where('id',$key->brand_id)->first();
                $key->brand_name=$brand->name;
                
                $sizess = Size::whereIn('id',$sar)->get();
                $dss=[];
                foreach($sizess as $kk)
                {  
                    $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($cardtx)>0)
                    {
                       $kk->selected_size_index=$cardtx[0]->size_index; 
                        $kk->selected_size=$cardtx[0]->size; 
                       $kk->is_cart_qty=$cardtx[0]->qty; 
                    }
                    else
                    {
                        $kk->selected_size_index=null; 
                        $kk->is_cart_qty=0; 
                        $kk->selected_size=0;
                    }
                    $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($skk)>0)
                    {  
                        //if($skk[0]->quantity==$skk[0]->sold_quant)
                        $kk->is_size=1;
                    }
                    else
                    {
                        $kk->is_size=0;
                    }
                    $dss[]=$kk;
                }
                $key->size_array=$dss;
                
               // $dss=[];
               // $key->s1=$sar[0];
                $color=$key->color;
                $sarx=explode(',',$color);
                $collors= Color::whereIn('id',$sarx)->get();
                $ddss=[];
                foreach($collors as $kks)
                {   
                    $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    if(count($cardtxx)>0)
                    {
                       $kks->selected_color_index=$cardtxx[0]->color_index; 
                        $kks->selected_color=$cardtxx[0]->color; 
                       $kks->is_cart_qty=$cardtxx[0]->qty; 
                    }
                    else
                    {
                        $kks->selected_color_index=null; 
                        $kks->is_cart_qty=0; 
                        $kks->selected_color=0;
                    }
                    $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    //$kks->is_color=count($skks);
                    if(count($skks)>0)
                    {
                        $kks->is_color=1;
                    }
                    else
                    {
                        $kks->is_color=0;
                    }
                    $ddss[]=$kks;
                }
                $key->color_array=$ddss;
                
                //$key->s12=$sarx[0];
                 $stockf=StockFetch::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
                
                    if(isset($stockf))
                     {    //$key->stockfetch_id=$stockf->id;
                    $key->stockfetch_id='';
                         $key->variable=$stockf->variable;
                         $datanewsar= $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                         $key->sizename=Size::where('id',$stockf->size)->first()->name;
                         $key->colorname=Color::where('id',$stockf->color)->first()->name;
                         $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                    }
                    else
                    {
                       $datanewsar=  $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->limit(1)->get();
                      if(count($datanewsar)>0)
                      {
                          $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                      }else{
                          $qtysdydyd=0;;
                      }
                       $key->stockfetch_id='';
                       $key->variable='';
                       $key->sizename=Size::where('id',$sar[0])->first()->name;
                       $key->colorname=Color::where('id',$sarx[0])->first()->name;
                       
                    }
                    
                  //  print_r($key);exit;
                //exit;
                       $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
                        $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
                        $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
                       // $dp[]=$key;
                if($qtysdydyd>0)
                {
                   $dp[]=$key;
                 }
            }
            
           // $obj['ProductList'] = $dp;
            if(count($dp)>0)
            {
                return $dp;
            }
            else
            {
                $response['msg']='No Data Found';
                return json_encode($response);
            }
       
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }


    //All product api
    public function getAllProduct(Request $request,$vendor)
    {
        $userid = $request->user_id;
        $start_limit= $request->start_limit;
        $end_limit= $request->end_limit;
        $diff=$end_limit- $start_limit;
        $catid=$request->category_id;
        $brand=$request->brand_id;
        $subsubcatid=$request->sortdata;
         $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
       
        $products = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('stock_fetches.quantity','>',0)->where('products.deleted_at',NULL)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);
        $products->skip($start_limit)->take($diff);
        $searchValue=$request->pro_name;
        if($searchValue!=''){
            $products->where('products.name', "LIKE", "%$searchValue%");
        }

        if($catid!=''){
            $scatid=explode(',',$catid);
            $products->whereIn('products.category_id', $scatid);
        }
        if($brand!=''){
            $sbrand=explode(',',$brand);
            $products->whereIn('products.brand_id', $sbrand);
        }
       
        $date=date('Y-m-d 00:00:00');
        if($subsubcatid=='Most Relevant')
        {
            $older= date("Y-m-d 00:00:00",strtotime("-45 day")); 
            $products->whereRaw('stock_fetches.created_at BETWEEN "'.$older.'" AND "'.$date.'"');
            $products->orderBy('stock_fetches.quantity', 'desc');
        }
        elseif($subsubcatid=='New Arrivals')
        {   
            $older= date("Y-m-d 00:00:00",strtotime("-90 day")); 
            $products->whereRaw('stock_fetches.created_at BETWEEN "'.$older.'" AND "'.$date.'"');
            $products->orderBy('stock_fetches.created_at', 'desc');
        }
        elseif($subsubcatid=='Most Popular')
        {
            $products->orderBy('stock_fetches.quantity', 'desc');
        }
        elseif($subsubcatid=='Price (High to Low)')
        {
            $products->orderBy('stock_fetches.sales_rate', 'desc');
        }
        elseif($subsubcatid=='Price (Low to High)')
        {
            $products->orderBy('stock_fetches.sales_rate', 'asc');
        }
        else
        {
            $products->orderBy('stock_fetches.quantity', 'desc');
        }
        
        $products = $products->get();
        $qrs=[];
        

        $dp=array();
        $ds=[];   $ddss=[];
        foreach ($products as $key)
        {  
            
            $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardt)>0)
            {
                $key->is_cart=1;
                 $key->cart_id=$cardt[0]->id;
            }
            else
            {
                $key->is_cart=0;
                $key->cart_id='';
            }
           // $key->cart=$cardt;
            $size=$key->size;$key->rating=4.5;
            $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardtx)>0)
            {
               $key->is_wish=1; $key->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $key->is_wish=0;$key->is_wish_id=null;
            }
              $key->percentshow=$vendordetails->online;
            $key->is_request=0;$key->salescount=0;
            $catt=Category::where('id',$key->category_id)->first();$key->category_name=$catt->name;
            $catts=Category::where('id',$key->sub_category_id)->first();$key->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();$key->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$key->brand_id)->first();$key->brand_name=$brand->name;
            $sar=explode(',',$size);$sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($cardtx)>0)
                {
                   $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                   $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $key->size_array=$dss;
            $color=$key->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                   $kks->selected_color_index=$cardtxx[0]->color_index; 
                    $kks->selected_color=$cardtxx[0]->color; 
                   $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $key->color_array=$ddss;
            //$key->s12=$sarx[0];
            $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$key->stocksize)->where('color',$key->stockcolor)->where('price',$key->sales_rate)->where('is_active',1)->get();
            $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
            $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
            $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
             $dp[]=$key;
        }

        if(count($dp)>0)
        {   
            $response['total_count']=count(Product::where('vendor_id',$vendor)->where('deleted_at',NULL)->get());;
            $response['List']=$dp;
        }
        else
        {
            $response['msg']='No Data Found';
            $response['status']=201;
         
        }
           return json_encode($response);
    }


    //global search api
    public function GlobalSearch(Request $request,$vendor)
    {
        $userid = $request->user_id;
        $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        $products = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->orderBy('stock_fetches.quantity','desc')->where('stock_fetches.quantity','>',0)->where('products.deleted_at',NULL)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);;
        $searchValue=$request->pro_name;
        if($searchValue!=''){
            $products->where('products.name', "LIKE", "%$searchValue%");
        }
        $qparam = [];
        $products = $products->limit(50)->get();
        //print_r($products);
        $dp=array();
        $ds=[];   $ddss=[];
        foreach ($products as $key)
        {  
            $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardt)>0)
            {
                $key->is_cart=1;
                $key->cart_id=$cardt[0]->id;
            }
            else
            {
                $key->is_cart=0;
                $key->cart_id='';
            }

            $size=$key->size;
            $key->rating=4.5;
            $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardtx)>0)
            {
               $key->is_wish=1;
               $key->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $key->is_wish=0;
                $key->is_wish_id=null;
            }
            
            $key->is_request=0;
            $key->percentshow=$vendordetails->online;
            $key->salescount=0;
            $sar=explode(',',$size);
            $catt=Category::where('id',$key->category_id)->first();
            $key->category_name=$catt->name;
            $catts=Category::where('id',$key->sub_category_id)->first();
            $key->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
            $key->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$key->brand_id)->first();
            $key->brand_name=$brand->name;
            $sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($cardtx)>0)
                {
                   $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                   $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $key->size_array=$dss;
           // $dss=[];
           // $key->s1=$sar[0];
            $color=$key->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                   $kks->selected_color_index=$cardtxx[0]->color_index; 
                    $kks->selected_color=$cardtxx[0]->color; 
                   $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $key->color_array=$ddss;
            //$key->s12=$sarx[0];
            // $stockf=StockFetch::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
            // if(isset($stockf))
            // {    
                // $key->stockfetch_id=$stockf->id;
                // $key->variable=$stockf->variable;
                 $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$key->stocksize)->where('color',$key->stockcolor)->where('price',$key->sales_rate)->orderBy('quantity','desc')->get();
            // }
            // else
            // {
            //   $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$sar)->where('color',$sarx)->orderBy('id','desc')->limit(1)->get();
            //   // $key->stockfetch_id='';
            //   // $key->variable='';
            // }
            $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
            $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
            $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
            $dp[]=$key;
        }
        
        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }
    
    public function getPriceData(Request $request)
    {  
        if($request->stockfetch_id!='')
        {
          $stc=StockFetch::where('product_id',$request->product_id)->where('id',$request->stockfetch_id)->first(); 
         // print_r($stc);
          if(isset($stc))
          {
            $stock= Stock::where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('purchase_rate',$stc->purchase_rate)->where('price',$stc->sales_rate)->where('is_active',1)->get();
          }
          else
          {
              $stock= Stock::where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('is_active',1)->orderBy('id','desc')->limit(1)->get();  
          }

        }
        else
        {
          $stock= Stock::where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('is_active',1)->orderBy('id','desc')->limit(1)->get();
        }
        
        $data=[];
       
        if(count($stock)>0)
        {
           foreach($stock as $key)
           {
                $usr=User::where('id',$key->vendor_id)->first();
                $vendordetails=Vendor::where('id',$usr->admin_id)->first();
            $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$request->product_id)->where('color',$request->color)->where('size',$request->size)->where('is_active',1)->get();
            if(count($cardtxx)>0)
            {
                $key->is_cart=1;
                $key->size_index=$cardtxx[0]->size_index;
                $key->color_index=$cardtxx[0]->color_index;
                $key->selected_size=$cardtxx[0]->size;
                $key->selected_color=$cardtxx[0]->color;
                $key->is_cart_qty=$cardtxx[0]->qty;
            }
            else
            {
                $key->is_cart=0;
                $key->size_index=null;
                $key->color_index=null;
                $key->selected_size=0;
                $key->selected_color=0;
                $key->is_cart_qty=0;
            }
              $key->percentshow=$vendordetails->online;
            $data[]=$key;
           }
            return  $data;
            
        }
        else
        {
            $response['msg']='stock not found';
            return json_encode($response);
        }
    }
    
    public function AddCartData(Request $request)
    {
       $userdata=AddCart::where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('user_id',$request->user_id)->where('price',$request->price)->get();
    //   print_r($userdata);
       if(count($userdata)>0){
            return [
            'msg'=>'Already Available In Cart',
            'id' => null
           ];
       }
       else
       {
           $userdata=AddCart::where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('user_id',$request->user_id)->get();
            if(count($userdata)>0){
                $category = AddCart::findOrFail($userdata[0]->id);
                $data=array(
               'price'=>$request->price,
               'color_index'=>$request->color_index,
               'size_index'=>$request->size_index,
               'stockfetch_id'=>$request->stockfetch_id,
               'qty'=>$request->qty,
               );
                $category->update($data);
                    if($category){
                       return [
                        'msg'=>'Added Successfully',
                        'id' => $userdata[0]->id
                       ];
                    }else
                    {
                        return [
                        'msg'=>'failed',
                        'id' => null
                       ];
                    }
            }else {
         $data=array(
           'user_id'=>$request->user_id,
           'product_id'=>$request->product_id,
           'size'=>$request->size,
           'color'=>$request->color,
           'price'=>$request->price,
           'qty'=>$request->qty,
           'color_index'=>$request->color_index,
            'stockfetch_id'=>$request->stockfetch_id,
           'size_index'=>$request->size_index,
           );
           $cat=AddCart::create($data);
            if($cat)
            {
            return [
                'msg'=>'Added Successfully',
                'id' => $cat->id
               ];
            }
            else
            {
                return [
                'msg'=>'failed',
                'id' => null
               ];
            }
       }
       }
    }
    
    
    //new cart
     public function AddCartDatanew(Request $request)
    {
       $userdata=AddCart::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('user_id',$request->user_id)->where('price',$request->price)->get();
       if(count($userdata)>0){
            return [
            'msg'=>'Already Available In Cart',
            'id' => null
           ];
       }
       else
       {
           $userdata=AddCart::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('user_id',$request->user_id)->get();
            if(count($userdata)>0){
                $category = AddCart::findOrFail($userdata[0]->id);
                $data=array(
               'price'=>$request->price,
               'color_index'=>$request->color_index,
               'size_index'=>$request->size_index,
               'stockfetch_id'=>$request->stockfetch_id,
               'qty'=>$request->qty,
               );
                $category->update($data);
                    if($category){
                       return [
                        'msg'=>'Added Successfully',
                        'id' => $userdata[0]->id
                       ];
                    }else
                    {
                        return [
                        'msg'=>'failed',
                        'id' => null
                       ];
                    }
            }else {
         $data=array(
           'user_id'=>$request->user_id,
           'product_id'=>$request->product_id,
           'size'=>$request->size,
           'color'=>$request->color,
           'vendor_id'=>$request->vendor_id,
           'price'=>$request->price,
           'qty'=>$request->qty,
           'color_index'=>$request->color_index,
            'stockfetch_id'=>$request->stockfetch_id,
           'size_index'=>$request->size_index,
           );
           $cat=AddCart::create($data);
            if($cat)
            {
            return [
                'msg'=>'Added Successfully',
                'id' => $cat->id
               ];
            }
            else
            {
                return [
                'msg'=>'failed',
                'id' => null
               ];
            }
       }
       }
    }
    
    public function UpdateCartData(Request $request)
    { 
       $category1 = AddCart::where('product_id',$request->product_id)->where('user_id',$request->user_id)->where('size',$request->size)->where('color',$request->color)->get();
       if(count($category1)>0)
       {
       $category = AddCart::findOrFail($category1[0]->id);
       $data=array(
           'user_id'=>$request->user_id,
           'product_id'=>$request->product_id,
           'size'=>$request->size,
           'color'=>$request->color,
           'price'=>$request->price,
           'color_index'=>$request->color_index,
           'size_index'=>$request->size_index,
           'stockfetch_id'=>$request->stockfetch_id,
           'qty'=>$request->qty,
           );
            $category->update($data);
        if($category)
        {
        return [
            'msg'=>'Updated Successfully'
           ];
        }
        else
        {
            return [
            'msg'=>'failed',
           ];
        }
       }
       else
       {
          $data=array(
           'user_id'=>$request->user_id,
           'product_id'=>$request->product_id,
           'size'=>$request->size,
           'color'=>$request->color,
           'price'=>$request->price,
           'qty'=>$request->qty,
           'color_index'=>$request->color_index,
           'size_index'=>$request->size_index,
           );
        $cat=AddCart::create($data);
        if($cat)
        {
        return [
            'msg'=>'Success',
            'id' => $cat->id
           ];
        }
        else
        {
            return [
            'msg'=>'failed',
            'id' => null
           ];
        }
       }
    }
    
    //updateCartdata
    public function UpdateCartDataNew(Request $request)
    { 
       $category1 = AddCart::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('user_id',$request->user_id)->where('size',$request->size)->where('color',$request->color)->get();
       if(count($category1)>0)
       {
       $category = AddCart::findOrFail($category1[0]->id);
       $data=array(
           'user_id'=>$request->user_id,
           'product_id'=>$request->product_id,
           'size'=>$request->size,
           'color'=>$request->color,
           'price'=>$request->price,
           'color_index'=>$request->color_index,
           'size_index'=>$request->size_index,
           'stockfetch_id'=>$request->stockfetch_id,
           'qty'=>$request->qty,
           );
            $category->update($data);
        if($category)
        {
        return [
            'msg'=>'Updated Successfully'
           ];
        }
        else
        {
            return [
            'msg'=>'failed',
           ];
        }
       }
       else
       {
          $data=array(
           'user_id'=>$request->user_id,
           'product_id'=>$request->product_id,
           'size'=>$request->size,
           'color'=>$request->color,
           'vendor_id'=>$request->vendor_id,
           'price'=>$request->price,
           'qty'=>$request->qty,
           'color_index'=>$request->color_index,
           'size_index'=>$request->size_index,
           );
        $cat=AddCart::create($data);
        if($cat)
        {
        return [
            'msg'=>'Success',
            'id' => $cat->id
           ];
        }
        else
        {
            return [
            'msg'=>'failed',
            'id' => null
           ];
        }
       }
    }
    
    //cartdata
    public function CartProductnew($id)
    {
        $VendorId=$_GET['vendor_id'];
        $post=AddCart::where('vendor_id',$VendorId)->where('user_id',$id)->get();
        $id=[];
        $dp=array();
        
        foreach($post as $key)
        {
            $products = Product::where('id',$key->product_id)->where('products.is_active', 1)->select(['*'])->groupBy('products.id');
            $searchValue=$_GET['pro_name'];
            if($searchValue!=''){ $products->where('products.name', "LIKE", "%$searchValue%"); }
            $products = $products->first();
           
            if(isset($products))
            {
            $usr=User::where('id',$products->vendor_id)->first();
            $vendordetails=Vendor::where('id',$usr->admin_id)->first();
            $catt=Category::where('id',$products->category_id)->first();
            $products->category_name=$catt->name;
            $catts=Category::where('id',$products->sub_category_id)->first();
            $products->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$products->sub_sub_category_id)->first();
            $products->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$products->brand_id)->first();
            $products->brand_name=$brand->name;
            $size=$products->size;
            $products->cart_id=$key->id;
            //  $products->is_cart=1;
            //   $products->is_cart_qty=$key->qty;
            $products->rating=4.5;
            $products->selected_size=$key->size;
            $products->selected_color=$key->color;
            $products->sizename=Size::where('id',$key->size)->first()->name;
            $products->colorname=Color::where('id',$key->color)->first()->name;
            $products->percentshow=$vendordetails->online;
            $stp=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->first();
            if(isset($stp))
            {
                
             $products->stock_remains=$stp->quantity-$stp->sold_qty;
            if($products->stock_remains==0)
             {     
                $stp=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->orderBy('id','desc')->get();
                if(count($stp)>0)
                { 
                     $products->stock_remains=$stp[0]->quantity-$stp[0]->sold_qty;
                }else{
                     $products->stock_remains=0;
                }
               
            }
            }
            else
            {
                 $products->stock_remains=0;
            }
            
            $products->size_index=$key->size_index;
            $products->color_index=$key->color_index;
            $products->price=$key->price;
            $cardtx=Wishlist::where('user_id',$key->user_id)->where('product_id',$key->product_id)->get();
            if(count($cardtx)>0)
            {
               $products->is_wish=1;
               $products->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $products->is_wish=0;
                $products->is_wish_id=null;
            }
            $products->is_request=0;
            $products->is_cart=1;
            $products->delivery_charges=0;
            if($products->stock_remains<$key->qty){
                $key->qty=$products->stock_remains;
            }
            $products->is_cart_qty= $key->qty;
            $products->salescount=0;
            $sar=explode(',',$size);
            $sizess= Size::where('id',$key->size)->get();
             $dss=[];
            foreach($sizess as $kk)
            {
                $skk=Stock::where('product_id',$products->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {
                    $kk->is_size=1;
                    //$kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $products->size_array=$dss;
           // $key->s1=$sar[0];
            $color=$products->color;
            $sarx=explode(',',$color);
          //  $products->color_array= Color::whereIn('id',$sarx)->get();
            $collors= Color::where('id',$key->color)->get();
            $ddss=[];
            foreach($collors as $kks)
            {
                $skks=Stock::where('product_id',$products->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $products->color_array=$ddss;
            //$key->s12=$sarx[0];
             $stockf=StockFetch::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('sales_rate',$key->price)->orderBy('id','desc')->first();
            if(isset($stockf))
            {    $products->stockfetch_id=$stockf->id;
                 $products->variable=$stockf->variable;
                $datanewsar=  $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                 $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
            }
            else
            {
             $datanewsar=   $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->get();
               $products->stockfetch_id='';
               $products->variable='';
               $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
            }
            //echo $qtysdydyd;
            $products->stock_array=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->get();
            $products->product_image=ProductImage::select(['name'])->where('product_id',$products->id)->get();
            $products->review=ProductReview::where('product_id',$products->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
           if($qtysdydyd>0 )
            {
               $dp[]=$products;
             }
            //$dp[]=$products;
            }
        }
        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
        
    }
    //....................//
    public function CartProduct($id)
    {
        $post=AddCart::where('user_id',$id)->get();
        $id=[];
        $dp=array();
        foreach($post as $key)
        {
            $products = Product::where('id',$key->product_id)->where('products.is_active', 1)->select(['*'])->groupBy('products.id');
            $searchValue=$_GET['pro_name'];
            if($searchValue!=''){ $products->where('products.name', "LIKE", "%$searchValue%"); }
            $products = $products->first();
           
            if(isset($products))
            {
            $usr=User::where('id',$products->vendor_id)->first();
            $vendordetails=Vendor::where('id',$usr->admin_id)->first();
            $catt=Category::where('id',$products->category_id)->first();
            $products->category_name=$catt->name;
            $catts=Category::where('id',$products->sub_category_id)->first();
            $products->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$products->sub_sub_category_id)->first();
            $products->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$products->brand_id)->first();
            $products->brand_name=$brand->name;
            $size=$products->size;
            $products->cart_id=$key->id;
            //  $products->is_cart=1;
            //   $products->is_cart_qty=$key->qty;
            $products->rating=4.5;
            $products->selected_size=$key->size;
            $products->selected_color=$key->color;
            $products->sizename=Size::where('id',$key->size)->first()->name;
            $products->colorname=Color::where('id',$key->color)->first()->name;
            $products->percentshow=$vendordetails->online;
            $stp=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->first();
            if(isset($stp))
            {
                
             $products->stock_remains=$stp->quantity-$stp->sold_qty;
            if($products->stock_remains==0)
             {     
                $stp=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->orderBy('id','desc')->get();
                if(count($stp)>0)
                { 
                     $products->stock_remains=$stp[0]->quantity-$stp[0]->sold_qty;
                }else{
                     $products->stock_remains=0;
                }
               
            }
            }
            else
            {
                 $products->stock_remains=0;
            }
            
            $products->size_index=$key->size_index;
            $products->color_index=$key->color_index;
            $products->price=$key->price;
            $cardtx=Wishlist::where('user_id',$key->user_id)->where('product_id',$key->product_id)->get();
            if(count($cardtx)>0)
            {
               $products->is_wish=1;
               $products->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $products->is_wish=0;
                $products->is_wish_id=null;
            }
            $products->is_request=0;
            $products->is_cart=1;
            $products->delivery_charges=0;
            if($products->stock_remains<$key->qty){
                $key->qty=$products->stock_remains;
            }
            $products->is_cart_qty= $key->qty;
            $products->salescount=0;
            $sar=explode(',',$size);
            $sizess= Size::where('id',$key->size)->get();
             $dss=[];
            foreach($sizess as $kk)
            {
                $skk=Stock::where('product_id',$products->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {
                    $kk->is_size=1;
                    //$kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $products->size_array=$dss;
           // $key->s1=$sar[0];
            $color=$products->color;
            $sarx=explode(',',$color);
          //  $products->color_array= Color::whereIn('id',$sarx)->get();
            $collors= Color::where('id',$key->color)->get();
            $ddss=[];
            foreach($collors as $kks)
            {
                $skks=Stock::where('product_id',$products->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $products->color_array=$ddss;
            //$key->s12=$sarx[0];
             $stockf=StockFetch::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('sales_rate',$key->price)->orderBy('id','desc')->first();
            if(isset($stockf))
            {    $products->stockfetch_id=$stockf->id;
                 $products->variable=$stockf->variable;
                $datanewsar=  $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                 $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
            }
            else
            {
             $datanewsar=   $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->get();
               $products->stockfetch_id='';
               $products->variable='';
               $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
            }
            //echo $qtysdydyd;
            $products->stock_array=Stock::where('product_id',$products->id)->where('size',$products->selected_size)->where('color',$products->selected_color)->where('price',$key->price)->get();
            $products->product_image=ProductImage::select(['name'])->where('product_id',$products->id)->get();
            $products->review=ProductReview::where('product_id',$products->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
           if($qtysdydyd>0 )
            {
               $dp[]=$products;
             }
            //$dp[]=$products;
            }
        }
        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
        
    }
    
    public function productsByVendorPagination(Request $request,$vendor)
    {
        $validator = \Validator::make($request->all(), ['sup_sub_category_id'=>'required|numeric','start_limit'=>'required|numeric','end_limit'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
     
        $sup_sub_category_id = $request->sup_sub_category_id;
        $sub_category_id = $request->sub_category_id;
        $co_sub_category_id  = $request->co_sub_category_id;
        $start_limit= $request->start_limit;
        $end_limit= $request->end_limit;

        $products = VendorProduct::with(['product_package'=> function($query) use ($vendor){
              return $query->where('vendor_products.vendor_id',$vendor);
        }])
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->leftjoin('packages','vendor_products.package_id','=','packages.id')
            ->where('products.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
            ->groupBy('vendor_products.product_id');
       
        $qparam = [];
        if($sup_sub_category_id){
            array_push($qparam, ['products.sup_sub_category_id','=',$sup_sub_category_id]);
        }
        if($sub_category_id){
            array_push($qparam, ['products.sub_category_id','=',$sub_category_id]);
        }
        if($co_sub_category_id){
            array_push($qparam, ['products.co_sub_category_id','=',$co_sub_category_id]);
        }
        
        $products = $products->where($qparam)->skip($start_limit)->take($end_limit)->get();
        return $products;
    }

    public function productsByVendorAll($vendor){
       
        $sub_category = isset($_GET['sub_cat']) &&is_numeric($_GET['sub_cat']) ? $_GET['sub_cat'] :null;
        
       // print_r("hi");exit;
        $products = VendorProduct::leftjoin('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('packages','packages.id','=','vendor_products.package_id')
            ->where('products.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->select(['*','vendor_products.id as vendor_product_id','products.id as product_id','products.name as product_name','vendor_products.is_active as is_active', 'vendor_products.is_featured as is_featured','packages.name as package_name'])
            //->groupBy('vendor_products.package_id')
            ->orderBy('products.name');
       
        $qparam = [];
        if($sub_category){
            array_push($qparam, ['products.sub_category_id','=',$sub_category]);
        }
        
        $products = $products->where($qparam)->get();
        return $products;
       
    }

    public function VendorSubCategories($vendorId){
         $sub_category= DB::table('vendor_products')
        ->join('products', 'products.id', '=', 'vendor_products.product_id')
        ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
        ->where('vendor_products.vendor_id', $vendorId)
        ->where('sub_categories.is_active', 1)
        // ->where('sub_categories.category_id', $request->category_id)
        ->select('sub_categories.*')
        ->groupBy('sub_categories.id')
        ->get();
        foreach($sub_category as $key)
        {
            $parent=$key->parent_id;
            $imgcat= DB::table('sub_categories')->where('id', $parent)->select('image')->get();;
            $key->image=$imgcat[0]->image;
            $data[]=$key;
        }
        return $data;
    }

    function productDetailsByVendorProductId($vendor, $productId)
    {
        if($vendor>0){
            $product = DB::table('vendor_products')
            ->join('products', 'products.id', '=', 'vendor_products.product_id')
            ->join('sub_categories', 'sub_categories.id','=', 'products.sub_category_id')
            ->where('sub_categories.is_active', 1)
            ->where('vendor_products.vendor_id', $vendor)
            ->where('vendor_products.id', $productId)
            ->where('products.is_active', 1)
            ->where('vendor_products.is_active', 1)
            ->select(['products.*', 'vendor_products.*', 'vendor_products.id as vendor_product_id','products.image as proimage','products.id as product_id', 'vendor_products.is_active as is_active' ])->first();
            $images = ProductImage::where('package_id', $product->package_id)->where('product_id', $product->product_id)->select('name')->get();
            //print_r($images);
            $cnt= count($images);
                        $ven= Vendor::where('id',$vendor)->first();
            if($ven->fif_nine=='1')
            {
                $product->price=$ven->vprice;
                $product->cost_price=$ven->oprice;
            }
            
            if($cnt==0)
            {
                $product->image=$product->proimage;
            }
            else
            {
                $product->image=$images[0]['name'];
            }
            
            // print_r($product);
            
            return [
                'product' => $product,
                'images' => $images,
            ];
        }
        else{

            $product = Product::where('id',$productId)->first();
            $images = ProductImage::where('product_id', $productId)->select('name')->get();
            
            //$images = ['name' => $product->image];
            return [
                'product' => $product,
                //'images' => array($images),
                'images' => $images,
            ];
        }
        
    }
    
    public function productDetailsByProductId($productId)
    {
        $uid=$_GET['user_id'];
        $sid=$_GET['stockfetch_id'];
        $products = Product::where('products.id',$productId)->where('products.is_active', 1)
            ->select(['products.*'])
            ->groupBy('products.id');
        $products = $products->first();
       // ->join('stock_fetches','products.id','=','stock_fetches.product_id')->where('stock_fetches.id',$sid)
      if($sid!='')
      {
          $stockf=StockFetch::where('product_id',$products['id'])->where('stock_fetches.id',$sid)->first();
          $products['stockfetch_id']=$stockf->id;
          $products['variable']=$stockf->variable;
          $products['stockcolor']=$stockf->color;
          $products['stocksize']=$stockf->size;
          $products['sales_rate']=$stockf->sales_rate;
          $products['stock_array_show']=Stock::where('product_id',$products['id'])->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->where('is_active',1)->get();
      } 
      else
      {
          $products['stockfetch_id']='';
          $products['variable']='';
          $products['stockcolor']=0;
          $products['stocksize']=0;
          $products['sales_rate']=0.00;
          $products['stock_array_show']=[];
      }
       $usr=User::where('id',$products['vendor_id'])->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        $products['percentshow']=$vendordetails->online;
        $size=$products['size'];
        $products['rating']=4.5;
        $cardtx=Wishlist::where('user_id',$uid)->where('product_id',$productId)->get();
        if(count($cardtx)>0)
        {
           $products['is_wish']=1;
           $products['is_wish_id']=$cardtx[0]->id;
        }
        else
        {
            $products['is_wish']=0;
            $products['is_wish_id']=null;
        }
        
        
        
        $cardt=AddCart::where('user_id',$uid)->where('product_id',$productId)->whereNULL('deleted_at')->get();
       
        if(count($cardt)>0)
        {
            // echo "??2";
           $products['is_cart']=1;
           $products['cart_id']=$cardt[0]->id;
           $products['is_cart_qty']=$cardt[0]->qty;
        }
        else
         {// echo "20";
            $products['is_cart']=0;
            $products['cart_id']='';
             $products['is_cart_qty']=0;
        }
            
            $products['is_request']=0;
            // $products['is_cart']=0;
            $products['salescount']=0;
            $catt=Category::where('id',$products['category_id'])->first();
            $products['category_name']=$catt->name;
            $catts=Category::where('id',$products['sub_category_id'])->first();
            $products['sub_category_name']=$catts->name;
            $cattss=SubCategory::where('id',$products['sub_sub_category_id'])->first();
            $products['sub_sub_category_name']=$cattss->name;
            $brand=Brand::where('id',$products['brand_id'])->first();
            $products['brand_name']=$brand->name;
            $sar=explode(',',$size);
            $products['size_array']= Size::whereIn('id',$sar)->get();
           // $key->s1=$sar[0];
            $color=$products['color'];
            $sarx=explode(',',$color);
            $products['color_array']= Color::whereIn('id',$sarx)->get();
            //$key->s12=$sarx[0];
          
            $products['stock_array']=Stock::where('product_id',$products['id'])->whereIn('size',$sar)->whereIn('color',$sarx)->get();
            $products['product_image']=ProductImage::select(['name'])->where('product_id',$products['id'])->get();
            $products['review']=ProductReview::where('product_id',$productId)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
            $dp[]=$products;
        return $dp;

    }
    
    
    public function productBarcode($productId)
    {
        $uid=$_GET['user_id'];
        $sid='';
        $products = Product::where('products.barcode',$productId)->where('products.is_active', 1)
            ->select(['products.*'])
            ->groupBy('products.id');
        $products = $products->first();
       // ->join('stock_fetches','products.id','=','stock_fetches.product_id')->where('stock_fetches.id',$sid)
      if($sid!='')
      {
          $stockf=StockFetch::where('product_id',$products['id'])->where('stock_fetches.id',$sid)->first();
          $products['stockfetch_id']=$stockf->id;
          $products['variable']=$stockf->variable;
          $products['stockcolor']=$stockf->color;
          $products['stocksize']=$stockf->size;
          $products['sales_rate']=$stockf->sales_rate;
          $products['stock_array_show']=Stock::where('product_id',$products['id'])->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->where('is_active',1)->get();
      } 
      else
      {
          $products['stockfetch_id']='';
          $products['variable']='';
          $products['stockcolor']=0;
          $products['stocksize']=0;
          $products['sales_rate']=0.00;
          $products['stock_array_show']=[];
      }
       $usr=User::where('id',$products['vendor_id'])->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        $products['percentshow']=$vendordetails->online;
        $size=$products['size'];
        $products['rating']=4.5;
        $cardtx=Wishlist::where('user_id',$uid)->where('product_id',$productId)->get();
            if(count($cardtx)>0)
            {
               $products['is_wish']=1;
               $products['is_wish_id']=$cardtx[0]->id;
            }
            else
            {
                $products['is_wish']=0;
                $products['is_wish_id']=null;
            }
            
            $products['is_request']=0;
            $products['is_cart']=0;
            $products['salescount']=0;
            $catt=Category::where('id',$products['category_id'])->first();
            $products['category_name']=$catt->name;
            $catts=Category::where('id',$products['sub_category_id'])->first();
            $products['sub_category_name']=$catts->name;
            $cattss=SubCategory::where('id',$products['sub_sub_category_id'])->first();
            $products['sub_sub_category_name']=$cattss->name;
            $brand=Brand::where('id',$products['brand_id'])->first();
            $products['brand_name']=$brand->name;
            $sar=explode(',',$size);
            $products['size_array']= Size::whereIn('id',$sar)->get();
           // $key->s1=$sar[0];
            $color=$products['color'];
            $sarx=explode(',',$color);
            $products['color_array']= Color::whereIn('id',$sarx)->get();
            //$key->s12=$sarx[0];
          
            $products['stock_array']=Stock::where('product_id',$products['id'])->whereIn('size',$sar)->whereIn('color',$sarx)->get();
            $products['product_image']=ProductImage::select(['name'])->where('product_id',$products['id'])->get();
            $products['review']=ProductReview::where('product_id',$productId)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
            $dp[]=$products;
        return $dp;

    }
    
    public function RemoveCart($id)
    {
        $vv= AddCart::where('id',$id)->get();
        if(count($vv)>0){
            $vendorProduct = AddCart::findOrFail($id);
            $rr=$vendorProduct->delete();
            if(isset($rr))
            {
            return [
                'msg' => 'Removed Successfully'
            ];
        }
        }
        else
        {
            return [
            'msg' => 'error' 
          ]; 
        }
   
    }
    
    public function productsByBrand(Request $request,$vendor)
    {
      
        $sup_sub_category_id = $request->brand_id;
        $userid = $request->user_id;
         $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        if($sup_sub_category_id=='')
        {
          return response()->json(['error_message'=>'Please use valid Feilds'], 422);
        }
       // if()
        $products = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('stock_fetches.quantity','>',0)->where('products.deleted_at',NULL)->where('products.brand_id',$sup_sub_category_id)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);
        $products->orderBy('stock_fetches.quantity', 'desc');
        $searchValue=$request->pro_name;
        $obj=[];
        if($searchValue!=''){
            $products->where('products.name', "LIKE", "%$searchValue%");
        }
       
       
        $products = $products->get();
        // print_r($products);exit;
        $dp=array();
        $ds=[];   $ddss=[];
        foreach ($products as $key)
        {  
            $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardt)>0)
            {
                $key->is_cart=1;
            }
            else
            {
                $key->is_cart=0;
            }
           // $key->cart=$cardt;
            $size=$key->size;
            $key->rating=4.5;
            $cardtx=Wishlist::where('user_id',$userid)->where('product_id',$key->id)->get();
            if(count($cardtx)>0)
            {
              $key->is_wish=1;
              $key->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $key->is_wish=0;
              $key->is_wish_id=null;
            }
            
            $key->is_request=0;
            if($key->variable=='')
            {
                    $key->name=$key->name;
            }
            else
            {
                $key->name=$key->name.'-'.$key->variable;;
            }
           
            $key->percentshow=$vendordetails->online;
            $key->salescount=0;
            $sar=explode(',',$size);
            $catt=Category::where('id',$key->category_id)->first();
            $key->category_name=$catt->name;
            $catts=Category::where('id',$key->sub_category_id)->first();
            $key->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
            $key->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$key->brand_id)->first();
            $key->brand_name=$brand->name;
            $sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($cardtx)>0)
                {
                   $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                   $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $key->size_array=$dss;
           // $dss=[];
           // $key->s1=$sar[0];
            $color=$key->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                   $kks->selected_color_index=$cardtxx[0]->color_index; 
                    $kks->selected_color=$cardtxx[0]->color; 
                   $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $key->color_array=$ddss;
            //$key->s12=$sarx[0];
            $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$key->stocksize)->where('color',$key->stockcolor)->where('price',$key->sales_rate)->where('is_active',1)->get();
            $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
            $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
            $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
            $dp[]=$key;
        }
        
       // $obj['ProductList'] = $dp;
        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    } 
    
    
    public function productsByBrandnew(Request $request,$vendor)
    {
      
        $sup_sub_category_id = $request->brand_id;
        $userid = $request->user_id;
         $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        if($sup_sub_category_id=='')
        {
          return response()->json(['error_message'=>'Please use valid Feilds'], 422);
        }
         
        $products = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('stock_fetches.quantity','>',0)->where('products.deleted_at',NULL)->where('products.brand_id',$sup_sub_category_id)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);
        $products->orderBy('stock_fetches.quantity', 'desc');
        $searchValue=$request->pro_name;
        $obj=[];
        if($searchValue!=''){
            $products->where('products.name', "LIKE", "%$searchValue%");
        }
        $products = $products->get();
        $cnt=count($products);
        
        if($cnt==0){
        $dp=array();
        $ds=[];   $ddss=[];}
        else
        {
            $dp=array();
            $ds=[];   $ddss=[];
            $total_records_per_page =$_POST['total_cnt'];
            $page_no=$_POST['page_no'];
            $offset = ($page_no-1) * $total_records_per_page;
            $productss = StockFetch::join('products','stock_fetches.product_id','=','products.id')->join('sizes','stock_fetches.size','=','sizes.id')->join('colors','stock_fetches.color','=','colors.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('stock_fetches.quantity','>',0)->where('products.deleted_at',NULL)->where('products.brand_id',$sup_sub_category_id)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.sales_rate','stock_fetches.id as stockfetch_id','stock_fetches.purchase_rate','stock_fetches.quantity','stock_fetches.variable','sizes.name as sizename','colors.name as colorname']);
            $productss->orderBy('stock_fetches.quantity', 'desc');
            $searchValue=$request->pro_name;
            $obj=[];
            if($searchValue!=''){
                $productss->where('products.name', "LIKE", "%$searchValue%");
            }
            $productss = $productss->offset($offset)->limit($total_records_per_page)->get();
            $total_pages=ceil($cnt/$total_records_per_page);
            
            foreach ($productss as $key)
            {  
                $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardt)>0)
                {
                    $key->is_cart=1;
                    $key->cart_id=$cardt[0]->id;
                }
                else
                {
                    $key->is_cart=0;
                }
               // $key->cart=$cardt;
                $size=$key->size;
                $key->rating=4.5;
                $cardtx=Wishlist::where('user_id',$userid)->where('product_id',$key->id)->get();
                if(count($cardtx)>0)
                {
                  $key->is_wish=1;
                  $key->is_wish_id=$cardtx[0]->id;
                }
                else
                {
                    $key->is_wish=0;
                  $key->is_wish_id=null;
                }
                
                $key->is_request=0;
                if($key->variable=='')
                {
                        $key->name=$key->name;
                }
                else
                {
                    $key->name=$key->name.'-'.$key->variable;;
                }
               
                $key->percentshow=$vendordetails->online;
                $key->salescount=0;
                $sar=explode(',',$size);
                $catt=Category::where('id',$key->category_id)->first();
                $key->category_name=$catt->name;
                $catts=Category::where('id',$key->sub_category_id)->first();
                $key->sub_category_name=$catts->name;
                $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
                $key->sub_sub_category_name=$cattss->name;
                $brand=Brand::where('id',$key->brand_id)->first();
                $key->brand_name=$brand->name;
                $sizess = Size::whereIn('id',$sar)->get();
                $dss=[];
                foreach($sizess as $kk)
                {  
                    $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($cardtx)>0)
                    {
                       $kk->selected_size_index=$cardtx[0]->size_index; 
                        $kk->selected_size=$cardtx[0]->size; 
                       $kk->is_cart_qty=$cardtx[0]->qty; 
                    }
                    else
                    {
                        $kk->selected_size_index=null; 
                        $kk->is_cart_qty=0; 
                        $kk->selected_size=0;
                    }
                    $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($skk)>0)
                    {  
                        //if($skk[0]->quantity==$skk[0]->sold_quant)
                        $kk->is_size=1;
                    }
                    else
                    {
                        $kk->is_size=0;
                    }
                    $dss[]=$kk;
                }
                $key->size_array=$dss;
               // $dss=[];
               // $key->s1=$sar[0];
                $color=$key->color;
                $sarx=explode(',',$color);
                $collors= Color::whereIn('id',$sarx)->get();
                $ddss=[];
                foreach($collors as $kks)
                {   
                    $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    if(count($cardtxx)>0)
                    {
                       $kks->selected_color_index=$cardtxx[0]->color_index; 
                        $kks->selected_color=$cardtxx[0]->color; 
                       $kks->is_cart_qty=$cardtxx[0]->qty; 
                    }
                    else
                    {
                        $kks->selected_color_index=null; 
                        $kks->is_cart_qty=0; 
                        $kks->selected_color=0;
                    }
                    $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    //$kks->is_color=count($skks);
                    if(count($skks)>0)
                    {
                        $kks->is_color=1;
                    }
                    else
                    {
                        $kks->is_color=0;
                    }
                    $ddss[]=$kks;
                }
                $key->color_array=$ddss;
                //$key->s12=$sarx[0];
                $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$key->stocksize)->where('color',$key->stockcolor)->where('price',$key->sales_rate)->where('is_active',1)->get();
                $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
                $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
                $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
                $dp[]=$key;
            }
        
        }
        
       // $obj['ProductList'] = $dp;
        if(count($dp)>0)
        {   $res['count']=$cnt;
            $res['total_pages']=$total_pages;
            $res['data']=$dp;
            return $res;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    } 
    
    public function WishlistProduct($id)
    {  
        
        $post=Wishlist::where('user_id',$id)->orderBy('id','desc')->get();
        $id=[];
        $dp=array();
        foreach($post as $key)
        {  
           $products = Product::where('id',$key->product_id)->where('products.is_active', 1)
            ->select(['*'])
            ->groupBy('products.id');
             $searchValue=$_GET['pro_name'];
            if($searchValue!=''){
                $products->where('products.name', "LIKE", "%$searchValue%");
            }
            $products = $products->first();
            //print_r($products);
            //echo $key->product_id;
            //$posts=AddCart::where('user_id',$id)->get();
            //print_r($posts);exit;
            if(isset($products))
            {
            $usr=User::where('id',$products->vendor_id)->first();
            $vendordetails=Vendor::where('id',$usr->admin_id)->first();
            $products->percentshow=$vendordetails->online;
            $catt=Category::where('id',$products->category_id)->first();
            $products->category_name=$catt->name;
            $catts=Category::where('id',$products->sub_category_id)->first();
            $products->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$products->sub_sub_category_id)->first();
            $products->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$products->brand_id)->first();
            $products->brand_name=$brand->name;
            $products->product_id=$products->id;
            $products->is_wish_id=$key->id;
            $size=$products->size;
            $products->cart_id='';
            $products->rating=4.5;
            $products->selected_size='';
            $products->selected_color='';
            $products->size_index='';
            $products->color_index='';
            $products->price='';
            $cardt=AddCart::where('user_id',$key->user_id)->where('add_carts.product_id',$key->product_id)->get();
            if(count($cardt)>0)
            {
                $products->is_cart=1;
            }
            else
            {
                $products->is_cart=0;
            }
            $products->is_request=0;
            $products->is_wish=1;
            $products->delivery_charges=0;
            $products->is_cart_qty=$key->qty;
            $products->salescount=0;
            $sar=explode(',',$size);
            $sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$key->user_id)->where('product_id',$key->product_id)->where('size',$kk->id)->where('is_active','1')->get();
               
                if(count($cardtx)>0)
                {
                  $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                  $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $products->size_array=$dss;
           // $dss=[];
           // $key->s1=$sar[0];
            $color=$products->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$key->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                  $kks->selected_color_index=$cardtxx[0]->color_index; 
                  $kks->selected_color=$cardtxx[0]->color; 
                  $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $products->color_array=$ddss;
            $stockf=StockFetch::where('product_id',$products->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
            if(isset($stockf))
            {    $products->stockfetch_id=$stockf->id;
                 $products->variable=$stockf->variable;
                 $datanewsar= $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                  $products->sizename=Size::where('id',$stockf->size)->first()->name;
                 $products->colorname=Color::where('id',$stockf->color)->first()->name;
                    $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
            }
            else
            {
                $datanewsar= $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->limit(1)->get();
                if(count($datanewsar)>0)
                      {
                          $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                      }else{
                          $qtysdydyd=0;;
                      }
                $products->sizename=Size::where('id',$sar[0])->first()->name;
                $products->colorname=Color::where('id',$sarx[0])->first()->name;
               $products->stockfetch_id='';
               $products->variable='';
                
            }
            //$products->stock_array_show=
            $products->stock_array=Stock::where('product_id',$products->id)->whereIn('size',$sar)->whereIn('color',$sarx)->orderBy('id','desc')->get();
            $products->product_image=ProductImage::select(['name'])->where('product_id',$products->id)->get();
            $products->review=ProductReview::where('product_id',$products->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
           // $dp[]=$products;
             if($qtysdydyd>0)
            {
               $dp[]=$products;
             }   
            }
        }
        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
        
    }
    
    public function savelastsearch(Request $request)
    {     
        $this->validate($request, [
            'user_id' => 'required',
            'vendor_id'=>'required',
            'product_id'=>'required',
        ]);  
        
        $prome=LastSearch::where('product_id',$request->product_id)->where('vendor_id',$request->vendor_id)->where('user_id',$request->user_id)->first();
        if(isset($prome)){
            return [
            'review_id' => $prome->id,
            'msg'=>'Last Search Added Successfully'
        ];
        }
        else
        {
        $data = $request->only('vendor_id','user_id','product_id');
        $category = LastSearch::create($data);
        if(isset($category))
        return [
            'review_id' => $category->id,
            'msg'=>'Last Search Added Successfully'
        ];
        else
        {
            return [
                'msg'=>'Last Search Not Added'
            ];
        }
    }
    }
    
    public function TrendingProduct(Request $request,$id)
    {
         $profd=Stock::where('vendor_id',$id)->groupBy('product_id')->orderBy('sold_qty','desc')->limit(50)->get();
         $usr=User::where('id',$id)->first();
         $vendordetails=Vendor::where('id',$usr->admin_id)->first();
         $idd=[];
        foreach($profd as $keyx)
        {
            $idd[]=$keyx->product_id;
        }
       
        $products = Product::where('products.vendor_id',$id)->where('products.is_active',1)->whereIn('products.id', $idd);
         $searchValue=$_GET['pro_name'];
            if($searchValue!=''){
                $products->where('products.name', "LIKE", "%$searchValue%");
            }
            $products=$products->get();
        $dp=array();
        $ds=[];   $ddss=[];
        foreach ($products as $key)
        {  
            
            $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardt)>0)
            {
                $key->is_cart=1;
            }
            else
            {
                $key->is_cart=0;
            }
           // $key->cart=$cardt;
            $size=$key->size;$key->rating=4.5;
            $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardtx)>0)
            {
               $key->is_wish=1; $key->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $key->is_wish=0;$key->is_wish_id=null;
            }
            $key->is_request=0;$key->salescount=0;
            $key->percentshow=$vendordetails->online;
            $catt=Category::where('id',$key->category_id)->first();$key->category_name=$catt->name;
            $catts=Category::where('id',$key->sub_category_id)->first();$key->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();$key->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$key->brand_id)->first();$key->brand_name=$brand->name;
            $sar=explode(',',$size);$sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($cardtx)>0)
                {
                   $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                   $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $key->size_array=$dss;
            $color=$key->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                   $kks->selected_color_index=$cardtxx[0]->color_index; 
                    $kks->selected_color=$cardtxx[0]->color; 
                   $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $key->color_array=$ddss;
            //$key->s12=$sarx[0];
             $stockf=StockFetch::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
            if(isset($stockf))
            {    $key->stockfetch_id=$stockf->id;
                 $key->variable=$stockf->variable;
                 $datanewsar=  $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                 $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;
                 $key->sizename=Size::where('id',$stockf->size)->first()->name;
                 $key->colorname=Color::where('id',$stockf->color)->first()->name;
            }
            else
            {
              $datanewsar=  $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->limit(1)->get();
               $key->stockfetch_id='';
               $key->variable='';
               $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
               $key->sizename=Size::where('id',$sar[0])->first()->name;
               $key->colorname=Color::where('id',$sarx[0])->first()->name;
            }
            $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
            $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
            $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
            if($qtysdydyd>0)
            {
               $dp[]=$key;
             }
        }

        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            $response['status']=201;
            return json_encode($response);
        }  
    }
    

    public function Checkproduct(Request $request,$vendor)
    {
        $sup_sub_category_id = $request->sub_sub_category_id;
        $sub_category_id = $request->sub_category_id;
        $category_id = $request->category_id;
        $userid = $request->user_id;
        if($sup_sub_category_id=='' && $sub_category_id=='' && $category_id=='')
        {
          return response()->json(['error_message'=>'Please use valid Feilds'], 422);
        }
        $products = StockFetch::join('products','stock_fetches.product_id','=','products.id')->groupBy('stock_fetches.product_id')->where('stock_fetches.quantity','>',0)->where('products.deleted_at',NULL)->where('stock_fetches.vendor_id',$vendor)->select(['products.*','stock_fetches.variable','stock_fetches.color as stockcolor','stock_fetches.size as stocksize','stock_fetches.id as stockfetch_id','stock_fetches.sales_rate','stock_fetches.purchase_rate','stock_fetches.quantity']);
        $searchValue=$request->pro_name;
         if($searchValue!=''){
            $products->where('products.name', "LIKE", "%$searchValue%");
        }
        
        $products->orderBy('stock_fetches.quantity', 'desc');
        $dp=[];
        $qparam = [];
        if($sup_sub_category_id){
            array_push($qparam, ['products.sub_sub_category_id','=',$sup_sub_category_id]);
        }
        if($sub_category_id){
            array_push($qparam, ['products.sub_category_id','=',$sub_category_id]);
        }
        if($category_id){
            array_push($qparam, ['products.category_id','=',$category_id]);
        }
        $products = $products->where($qparam)->get();
          $ds=[];   $ddss=[];
        foreach ($products as $key)
        {  
            
            $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardt)>0)
            {
                $key->is_cart=1;
            }
            else
            {
                $key->is_cart=0;
            }
           // $key->cart=$cardt;
            $size=$key->size;
            $key->rating=4.5;
            $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardtx)>0)
            {
               $key->is_wish=1;
               $key->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $key->is_wish=0;
                $key->is_wish_id=null;
            }
            
            $key->is_request=0;
           
          
            $key->salescount=0;
            $sar=explode(',',$size);
            $catt=Category::where('id',$key->category_id)->first();
            $key->category_name=$catt->name;
            $catts=Category::where('id',$key->sub_category_id)->first();
            $key->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
            $key->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$key->brand_id)->first();
            $key->brand_name=$brand->name;
            $sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($cardtx)>0)
                {
                   $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                   $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $key->size_array=$dss;
           // $dss=[];
           // $key->s1=$sar[0];
            $color=$key->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                   $kks->selected_color_index=$cardtxx[0]->color_index; 
                    $kks->selected_color=$cardtxx[0]->color; 
                   $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $key->color_array=$ddss;
            if($key->variable=='')
            {
                    $key->name=$key->name;
            }
            else
            {
                $key->name=$key->name.'-'.$key->variable;;
            }
            //$key->s12=$sarx[0];
            $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$key->stocksize)->where('color',$key->stockcolor)->where('price',$key->sales_rate)->where('is_active',1)->get();
            $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
            $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
            $dp[]=$key;
        }
        
       // $obj['ProductList'] = $dp;
        if(count($dp)>0)
        {
            return $dp;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }
    
    
    //last search
    public function lastSearchnew(Request $request,$vendor)
    {   
        $idxds=[];
        $usr=User::where('id',$vendor)->first();
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        $selc=LastSearch::where('vendor_id',$vendor)->where('user_id',$request->user_id)->where('is_active',1)->where('deleted_at',NULL)->orderBy('id','desc')->get();
        if(count($selc)>0)
        {   
             $cnt=count($selc);
             $total_records_per_page =$_POST['total_cnt'];
             $page_no=$_POST['page_no'];
             $offset = ($page_no-1) * $total_records_per_page;
             $total_pages=ceil($cnt/$total_records_per_page);
             $selcs=LastSearch::where('last_search.vendor_id',$vendor)->where('last_search.user_id',$request->user_id)->join('products','products.id','=','last_search.product_id')->where('last_search.is_active',1)->where('last_search.deleted_at',NULL)->orderBy('last_search.id','desc')->offset($offset)->limit($total_records_per_page)->select(['products.*'])->get();
        }
     //   echo $iddd=implode(',',$idxds);
   //  exit;
        if(isset($selcs))
        {
           
            
            //  $productss = Product::where('vendor_id',$vendor)->whereIn('id',$idxds)->where('is_active', 1)->offset($offset)->limit($total_records_per_page)->get();
             $dp=array();
             $ds=[];   $ddss=[];
            foreach ($selcs as $key)
            {  
                $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardt)>0)
                {
                    $key->is_cart=1;
                     $key->cart_id=$cardt[0]->id;
                    
                }
                else
                {
                    $key->is_cart=0;
                    $key->cart_id='';
                }
               // $key->cart=$cardt;
                $size=$key->size;
                $key->rating=4.5;
                $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardtx)>0)
                {
                   $key->is_wish=1;
                   $key->is_wish_id=$cardtx[0]->id;
                }
                else
                {
                    $key->is_wish=0;
                    $key->is_wish_id=null;
                }
                
                $key->is_request=0;
                 $key->percentshow=$vendordetails->online;
              
                $key->salescount=0;
                $sar=explode(',',$size);
                $catt=Category::where('id',$key->category_id)->first();
                $key->category_name=$catt->name;
                $catts=Category::where('id',$key->sub_category_id)->first();
                $key->sub_category_name=$catts->name;
                $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
                $key->sub_sub_category_name=$cattss->name;
                $brand=Brand::where('id',$key->brand_id)->first();
                $key->brand_name=$brand->name;
                $sizess = Size::whereIn('id',$sar)->get();
                $dss=[];
                foreach($sizess as $kk)
                {  
                    $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($cardtx)>0)
                    {
                       $kk->selected_size_index=$cardtx[0]->size_index; 
                        $kk->selected_size=$cardtx[0]->size; 
                       $kk->is_cart_qty=$cardtx[0]->qty; 
                    }
                    else
                    {
                        $kk->selected_size_index=null; 
                        $kk->is_cart_qty=0; 
                        $kk->selected_size=0;
                    }
                    $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($skk)>0)
                    {  
                        //if($skk[0]->quantity==$skk[0]->sold_quant)
                        $kk->is_size=1;
                    }
                    else
                    {
                        $kk->is_size=0;
                    }
                    $dss[]=$kk;
                }
                $key->size_array=$dss;
               // $dss=[];
               // $key->s1=$sar[0];
                $color=$key->color;
                $sarx=explode(',',$color);
                $collors= Color::whereIn('id',$sarx)->get();
                $ddss=[];
                foreach($collors as $kks)
                {   
                    $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    if(count($cardtxx)>0)
                    {
                       $kks->selected_color_index=$cardtxx[0]->color_index; 
                        $kks->selected_color=$cardtxx[0]->color; 
                       $kks->is_cart_qty=$cardtxx[0]->qty; 
                    }
                    else
                    {
                        $kks->selected_color_index=null; 
                        $kks->is_cart_qty=0; 
                        $kks->selected_color=0;
                    }
                    $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    //$kks->is_color=count($skks);
                    if(count($skks)>0)
                    {
                        $kks->is_color=1;
                    }
                    else
                    {
                        $kks->is_color=0;
                    }
                    $ddss[]=$kks;
                }
                $key->color_array=$ddss;
                $key->s12=$sarx[0];
                $stockf=StockFetch::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
                if(isset($stockf))
                {    $key->stockfetch_id=$stockf->id;
                     $key->variable=$stockf->variable;
                     $datanewsar= $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                      $key->sizename=Size::where('id',$stockf->size)->first()->name;
                     $key->colorname=Color::where('id',$stockf->color)->first()->name;
                        $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                }
                else
                {
                   $datanewsar= $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->limit(1)->get();
                   if(count($datanewsar)>0){
                      
                       $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                   }else
                   {
                        $qtysdydyd=0;
                   }
                   $key->stockfetch_id='';
                   $key->variable='';
                    $key->sizename=Size::where('id',$sar[0])->first()->name;
                    $key->colorname=Color::where('id',$sarx[0])->first()->name;
                       
                }
                $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
                $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
                $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
                  if($qtysdydyd>0)
                  {
                   $dp[]=$key;
                   }
              //  $dp[]=$key;
            }
            //echo count($dp);
           // $obj['ProductList'] = $dp;
            if(count($dp)>0)
            {
                 $res['count']=$cnt;
                $res['total_pages']=$total_pages;
                $res['data']=$dp;
                return $res;
            }
            else
            {
                $response['msg']='No Data Found';
                return json_encode($response);
            }
            
      
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }
   
   //trending product
    public function TrendingProductNew(Request $request,$id)
    {
         $profd=Stock::where('vendor_id',$id)->groupBy('product_id')->orderBy('sold_qty','desc')->limit(50)->get();
         $usr=User::where('id',$id)->first();
         $vendordetails=Vendor::where('id',$usr->admin_id)->first();
         $idd=[];
        foreach($profd as $keyx){$idd[]=$keyx->product_id;}
         $total_records_per_page =$_POST['total_cnt'];
            $page_no=$_POST['page_no'];
            $offset = ($page_no-1) * $total_records_per_page;
        $products = Product::where('products.vendor_id',$id)->where('products.is_active',1)->whereIn('products.id', $idd);
        $searchValue=$_GET['pro_name'];
        if($searchValue!=''){
                $products->where('products.name', "LIKE", "%$searchValue%");
        }
        $products=$products->get();
          $cnt=count($products);
          if($cnt==0){
        $dp=array();
        $ds=[];   $ddss=[];}
        else
        {
            $total_pages=ceil($cnt/$total_records_per_page);
        $dp=array();
        $ds=[];   $ddss=[];
         $productss = Product::where('products.vendor_id',$id)->where('products.is_active',1)->whereIn('products.id', $idd);
        $searchValue=$_GET['pro_name'];
        if($searchValue!=''){
                $productss->where('products.name', "LIKE", "%$searchValue%");
        }
        $productss=$productss->offset($offset)->limit($total_records_per_page)->get();
        foreach ($productss as $key)
        {  
            
            $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardt)>0)
            {
                $key->is_cart=1;
                 $key->cart_id=$cardt[0]->id;
            }
            else
            {
                $key->is_cart=0;
                $key->cart_id='';
            }
           // $key->cart=$cardt;
            $size=$key->size;$key->rating=4.5;
            $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
            if(count($cardtx)>0)
            {
               $key->is_wish=1; $key->is_wish_id=$cardtx[0]->id;
            }
            else
            {
                $key->is_wish=0;$key->is_wish_id=null;
            }
            $key->is_request=0;$key->salescount=0;
            $key->percentshow=$vendordetails->online;
            $catt=Category::where('id',$key->category_id)->first();$key->category_name=$catt->name;
            $catts=Category::where('id',$key->sub_category_id)->first();$key->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();$key->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$key->brand_id)->first();$key->brand_name=$brand->name;
            $sar=explode(',',$size);$sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($cardtx)>0)
                {
                   $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                   $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $key->size_array=$dss;
            $color=$key->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                   $kks->selected_color_index=$cardtxx[0]->color_index; 
                    $kks->selected_color=$cardtxx[0]->color; 
                   $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $key->color_array=$ddss;
            //$key->s12=$sarx[0];
             $stockf=StockFetch::where('product_id',$key->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
            //  $stockf  = StockFetch::where('product_id', $key->id)->where('size', $sar[0])->where('color', $sarx[0])->whereRaw('quantity > 0')->orderByRaw('(quantity) desc')->limit(1)->first();
            if(isset($stockf))
            {    $key->stockfetch_id=$stockf->id;
                 $key->variable=$stockf->variable;
                 $datanewsar=  $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                 $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;
                 $key->sizename=Size::where('id',$stockf->size)->first()->name;
                 $key->colorname=Color::where('id',$stockf->color)->first()->name;
            }
            else
            {
              $datanewsar = $key->stock_array_show = Stock::where('product_id', $key->id)->where('size', $sar[0])->where('color', $sarx[0])->whereRaw('quantity - sold_qty > 0')->orderByRaw('(quantity - sold_qty) desc')->limit(1)->get();
               $key->stockfetch_id='';
               $key->variable='';
               $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
               $key->sizename=Size::where('id',$sar[0])->first()->name;
               $key->colorname=Color::where('id',$sarx[0])->first()->name;
            }
            $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->whereRaw('quantity - sold_qty > 0')->orderByRaw('(quantity - sold_qty) desc')->get();
            $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
            $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
            if($qtysdydyd>0)
            {
               $dp[]=$key;
             }
        }
        }
        if(count($dp)>0)
        {
            $res['count']=$cnt;
            $res['total_pages']=$total_pages;
            $res['data']=$dp;
            return $res;
        }
        else
        {
            $response['msg']='No Data Found';
            $response['status']=201;
            return json_encode($response);
        }  
    }
    
    
    //recomended product
    public function getRecommendedNew(Request $request,$vendor)
    {
        $usr=User::where('id',$vendor)->first();
        
        $vendordetails=Vendor::where('id',$usr->admin_id)->first();
        $selc=ProductRecomend::where('vendor_id',$vendor)->where('is_active',1)->where('deleted_at',NULL)->first();
        
        if(isset($selc))
        {
            $products = Product::whereIn('id', explode(',',$selc->product_id))->where('is_active', 1);
            $searchValue=$_GET['pro_name'];
            if($searchValue!=''){
                $products->where('products.name', "LIKE", "%$searchValue%");
            }
            $products=$products->get(); 
              $cnt=count($products);
           
            // $dp=array();
            // $ds=[];   $ddss=[];
            // ec$cnt=count($products);
        // echo $cnt;exit;
         $dp=array();
        if($cnt==0){
        $dp=array();
        $ds=[];   $ddss=[]; 
        // echo "121";exit;
        }
        else
        {   
            // echo "11";exit;
            $total_records_per_page =$_POST['total_cnt'];
             $page_no=$_POST['page_no'];
            $offset = ($page_no-1) * $total_records_per_page;
            $productss = Product::whereIn('id', explode(',',$selc->product_id))->where('is_active', 1);
            $searchValue=$_GET['pro_name'];
            if($searchValue!=''){
                $productss->where('products.name', "LIKE", "%$searchValue%");
            }
            $total_pages=ceil($cnt/$total_records_per_page);
            $productss=$productss->offset($offset)->limit($total_records_per_page)->get();
            // echo json_encode($productss);exit;
            foreach ($productss as $key)
            {  
                
                
                $cardt=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                
                if(count($cardt)>0)
                {
                    $key->is_cart=1;
                     $key->cart_id=$cardt[0]->id;
                     $key->is_cart_qty=1;
                    
                }
                else
                {
                    $key->is_cart=0;
                    $key->cart_id='';
                    $key->is_cart_qty=0;
                }
                
                  $key->percentshow=$vendordetails->online;
               // $key->cart=$cardt;
                $size=$key->size;
                $key->rating=4.5;
                $cardtx=Wishlist::where('user_id',$request->user_id)->where('product_id',$key->id)->get();
                if(count($cardtx)>0)
                {
                   $key->is_wish=1;
                   $key->is_wish_id=$cardtx[0]->id;
                }
                else
                {
                    $key->is_wish=0;
                    $key->is_wish_id=null;
                }
                
                $key->is_request=0;
               
                
                $key->salescount=0;
                $sar=explode(',',$size);
                $catt=Category::where('id',$key->category_id)->first();
                $key->category_name=$catt->name;
                $catts=Category::where('id',$key->sub_category_id)->first();
                $key->sub_category_name=$catts->name;
                $cattss=SubCategory::where('id',$key->sub_sub_category_id)->first();
                $key->sub_sub_category_name=$cattss->name;
                $brand=Brand::where('id',$key->brand_id)->first();
                $key->brand_name=$brand->name;
                
                $sizess = Size::whereIn('id',$sar)->get();

                $dss=[];$dp=[];
                foreach($sizess as $kk)
                {  
                    $cardtx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($cardtx)>0)
                    {
                       $kk->selected_size_index=$cardtx[0]->size_index; 
                        $kk->selected_size=$cardtx[0]->size; 
                       $kk->is_cart_qty=$cardtx[0]->qty; 
                    }
                    else
                    {
                        $kk->selected_size_index=null; 
                        $kk->is_cart_qty=0; 
                        $kk->selected_size=0;
                    }
                    $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                    if(count($skk)>0)
                    {  
                        //if($skk[0]->quantity==$skk[0]->sold_quant)
                        $kk->is_size=1;
                    }
                    else
                    {
                        $kk->is_size=0;
                    }
                    $dss[]=$kk;
                }
                $key->size_array=$dss;
                
               // $dss=[];
               // $key->s1=$sar[0];
                $color=$key->color;
                $sarx=explode(',',$color);
                $collors= Color::whereIn('id',$sarx)->get();
                $ddss=[];$dp=[];
                foreach($collors as $kks)
                {   
                    $cardtxx=AddCart::where('user_id',$request->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    if(count($cardtxx)>0)
                    {
                       $kks->selected_color_index=$cardtxx[0]->color_index; 
                        $kks->selected_color=$cardtxx[0]->color; 
                       $kks->is_cart_qty=$cardtxx[0]->qty; 
                    }
                    else
                    {
                        $kks->selected_color_index=null; 
                        $kks->is_cart_qty=0; 
                        $kks->selected_color=0;
                    }
                    $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                    //$kks->is_color=count($skks);
                    if(count($skks)>0)
                    {
                        $kks->is_color=1;
                    }
                    else
                    {
                        $kks->is_color=0;
                    }
                    $ddss[]=$kks;
                }
                $key->color_array=$ddss;
                
                //$key->s12=$sarx[0];
                 $stockf=StockFetch::where('product_id',$key->id)->orderBy('quantity','desc')->get();
                //   print_r($stockf);
                // $key->stockfetch_id='';
                    if(isset($stockf))
                    {    
                        $ntcmt=count($stockf)-1; 
                        $key->stockfetch_id=$stockf[$ntcmt]->id;
                         $key->variable=$stockf[$ntcmt]->variable;
                         $datanewsar= $key->stock_array_show=Stock::where('product_id',$key->id)->where('size',$stockf[$ntcmt]->size)->where('color',$stockf[$ntcmt]->color)->selectRaw('*, (quantity - sold_qty) as total_difference')
    ->orderBy('total_difference', 'desc')->get();
                        //   print_r($datanewsar);
                         $key->sizename=Size::where('id',$stockf[$ntcmt]->size)->first()->name;
                         $key->colorname=Color::where('id',$stockf[$ntcmt]->color)->first()->name;
                         $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                    }
                    else
                    {
                       $datanewsar = $key->stock_array_show = Stock::where('product_id', $key->id)
    ->whereIn('size', $sar)
    ->whereIn('color', $sarx)
    ->selectRaw('*, (quantity - sold_qty) as total_difference')
    ->orderBy('total_difference', 'desc')
    ->limit(1)
    ->sum('total_difference');
                      if(count($datanewsar)>0)
                      {
                          $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                      }else{
                          $qtysdydyd=0;;
                      }
                       $key->stockfetch_id='';
                       $key->variable='';
                       $key->sizename=Size::where('id',$sar[0])->first()->name;
                       $key->colorname=Color::where('id',$sarx[0])->first()->name;
                       
                    }
                    // echo $qtysdydyd.'/'.$key->id.'..0';
                  //  print_r($key);exit;
                //exit;
                
                       $key->stock_array=Stock::where('product_id',$key->id)->whereIn('size',$sar)->whereIn('color',$sarx)->where('is_active',1)->get();
                        $key->product_image=ProductImage::select(['name'])->where('product_id',$key->id)->get();
                        $key->review=ProductReview::where('product_id',$key->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
                       // $dp[]=$key;
                       
                    //   echo $qtysdydyd;
                if($qtysdydyd > 0)
                { 
                    // echo $key->id;
                   $ds[]=$key;
                 }
            }
            
            $dp=$ds;
             
            // count($dp);exit;
            // $obj['ProductList'] = $dp;
            if(count($dp)>0)
            {
                $res['count']=$cnt;
                $res['total_pages']=$total_pages;
                $res['data']=$dp;
                return $res;
            }
            else
            {
                $response['msg']='No Data Found';
                return json_encode($response);
            }
        }
       
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
    }
    
    //wishlist new
     public function WishlistProductNew(Request $request,$id)
    {  
        $total_records_per_page =$_POST['total_cnt'];
        $page_no=$_POST['page_no'];
        $offset = ($page_no-1) * $total_records_per_page;
        $post=Wishlist::where('user_id',$id)->orderBy('id','desc')->get();
        $cnt=count($post);
        $total_pages=ceil($cnt/$total_records_per_page);
        $posts=Wishlist::where('user_id',$id)->orderBy('id','desc')->groupBy('product_id')->offset($offset)->limit($total_records_per_page)->get();
        
        $id=[];
        $dp=array();
        
        foreach($posts as $key)
        {  
           $products = Product::where('id',$key->product_id)->where('products.is_active', 1)
            ->select(['*'])
            ->groupBy('products.id');
             $searchValue=$_GET['pro_name'];
            if($searchValue!=''){
                $products->where('products.name', "LIKE", "%$searchValue%");
            }
            $products = $products->first();
            //print_r($products);
            //echo $key->product_id;
            //$posts=AddCart::where('user_id',$id)->get();
            //print_r($posts);exit;
            if(isset($products))
            {
            $usr=User::where('id',$products->vendor_id)->first();
            $vendordetails=Vendor::where('id',$usr->admin_id)->first();
            $products->percentshow=$vendordetails->online;
            $catt=Category::where('id',$products->category_id)->first();
            $products->category_name=$catt->name;
            $products->wish_id=$key->id;
            $catts=Category::where('id',$products->sub_category_id)->first();
            $products->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$products->sub_sub_category_id)->first();
            $products->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$products->brand_id)->first();
            $products->brand_name=$brand->name;
            $products->product_id=$products->id;
            $products->is_wish_id=$key->id;
            $size=$products->size;
            
            $products->rating=4.5;
            $products->selected_size='';
            $products->selected_color='';
            $products->size_index='';
            $products->color_index='';
            $products->price='';
            $cardt=AddCart::where('user_id',$key->user_id)->where('add_carts.product_id',$key->product_id)->get();
            if(count($cardt)>0)
            {
                $products->is_cart=1;
                $products->cart_id=$cardt[0]->id;
            }
            else
            {
                $products->is_cart=0;
                $products->cart_id='';
            }
            $products->is_request=0;
            $products->is_wish=1;
            $products->delivery_charges=0;
            $products->is_cart_qty=$key->qty;
            $products->salescount=0;
            $sar=explode(',',$size);
            $sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$key->user_id)->where('product_id',$key->product_id)->where('size',$kk->id)->where('is_active','1')->get();
               
                if(count($cardtx)>0)
                {
                  $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                  $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $products->size_array=$dss;
           // $dss=[];
           // $key->s1=$sar[0];
            $color=$products->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$key->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                  $kks->selected_color_index=$cardtxx[0]->color_index; 
                  $kks->selected_color=$cardtxx[0]->color; 
                  $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $products->color_array=$ddss;
            $stockf=StockFetch::where('product_id',$products->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
            if(isset($stockf))
            {    $products->stockfetch_id=$stockf->id;
                 $products->variable=$stockf->variable;
                 $datanewsar= $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                 $products->sizename=Size::where('id',$stockf->size)->first()->name;
                 $products->colorname=Color::where('id',$stockf->color)->first()->name;
                 $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
            }
            else
            {
                $datanewsar= $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->limit(1)->get();
                if(count($datanewsar)>0)
                      {
                          $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                      }else{
                          $qtysdydyd=0;;
                      }
                $products->sizename=Size::where('id',$sar[0])->first()->name;
                $products->colorname=Color::where('id',$sarx[0])->first()->name;
               $products->stockfetch_id='';
               $products->variable='';
                
            }
            //$products->stock_array_show=
            $products->stock_array=Stock::where('product_id',$products->id)->whereIn('size',$sar)->whereIn('color',$sarx)->orderBy('id','desc')->get();
            $products->product_image=ProductImage::select(['name'])->where('product_id',$products->id)->get();
            $products->review=ProductReview::where('product_id',$products->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
           // $dp[]=$products;
            //  if($qtysdydyd>0)
            // {
               $dp[]=$products;
            //  }   
            }
        }
       
        if(count($dp)>0)
        {
            $res['count']=$cnt;
                $res['total_pages']=$total_pages;
                $res['data']=$dp;
                return $res;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
        
    }
    
    
    //seva
     //wishlist new
     public function WishlistProductNewSeva(Request $request,$id)
    {  
        $total_records_per_page =$_POST['total_cnt'];
        $page_no=$_POST['page_no'];
        $vendor_id=$_POST['vendor_id'];
        $offset = ($page_no-1) * $total_records_per_page;
        $post=Wishlist::where('vendor_id',$vendor_id)->where('user_id',$id)->orderBy('id','desc')->get();
        $cnt=count($post);
        $total_pages=ceil($cnt/$total_records_per_page);
        $posts=Wishlist::where('vendor_id',$vendor_id)->where('user_id',$id)->orderBy('id','desc')->groupBy('product_id')->offset($offset)->limit($total_records_per_page)->get();
        
        $id=[];
        $dp=array();
        
        foreach($posts as $key)
        {  
           $products = Product::where('id',$key->product_id)->where('products.is_active', 1)
            ->select(['*'])
            ->groupBy('products.id');
             $searchValue=$_GET['pro_name'];
            if($searchValue!=''){
                $products->where('products.name', "LIKE", "%$searchValue%");
            }
            $products = $products->first();
            //print_r($products);
            //echo $key->product_id;
            //$posts=AddCart::where('user_id',$id)->get();
            //print_r($posts);exit;
            if(isset($products))
            {
            $usr=User::where('id',$products->vendor_id)->first();
            $vendordetails=Vendor::where('id',$usr->admin_id)->first();
            $products->percentshow=$vendordetails->online;
            $catt=Category::where('id',$products->category_id)->first();
            $products->category_name=$catt->name;
            $products->wish_id=$key->id;
            $catts=Category::where('id',$products->sub_category_id)->first();
            $products->sub_category_name=$catts->name;
            $cattss=SubCategory::where('id',$products->sub_sub_category_id)->first();
            $products->sub_sub_category_name=$cattss->name;
            $brand=Brand::where('id',$products->brand_id)->first();
            $products->brand_name=$brand->name;
            $products->product_id=$products->id;
            $products->is_wish_id=$key->id;
            $size=$products->size;
            
            $products->rating=4.5;
            $products->selected_size='';
            $products->selected_color='';
            $products->size_index='';
            $products->color_index='';
            $products->price='';
            $cardt=AddCart::where('user_id',$key->user_id)->where('add_carts.product_id',$key->product_id)->get();
            if(count($cardt)>0)
            {
                $products->is_cart=1;
                $products->cart_id=$cardt[0]->id;
            }
            else
            {
                $products->is_cart=0;
                $products->cart_id='';
            }
            $products->is_request=0;
            $products->is_wish=1;
            $products->delivery_charges=0;
            $products->is_cart_qty=$key->qty;
            $products->salescount=0;
            $sar=explode(',',$size);
            $sizess = Size::whereIn('id',$sar)->get();
            $dss=[];
            foreach($sizess as $kk)
            {  
                $cardtx=AddCart::where('user_id',$key->user_id)->where('product_id',$key->product_id)->where('size',$kk->id)->where('is_active','1')->get();
               
                if(count($cardtx)>0)
                {
                  $kk->selected_size_index=$cardtx[0]->size_index; 
                    $kk->selected_size=$cardtx[0]->size; 
                  $kk->is_cart_qty=$cardtx[0]->qty; 
                }
                else
                {
                    $kk->selected_size_index=null; 
                    $kk->is_cart_qty=0; 
                    $kk->selected_size=0;
                }
                $skk=Stock::where('product_id',$key->id)->where('size',$kk->id)->where('is_active',1)->get();
                if(count($skk)>0)
                {  
                    //if($skk[0]->quantity==$skk[0]->sold_quant)
                    $kk->is_size=1;
                }
                else
                {
                    $kk->is_size=0;
                }
                $dss[]=$kk;
            }
            $products->size_array=$dss;
           // $dss=[];
           // $key->s1=$sar[0];
            $color=$products->color;
            $sarx=explode(',',$color);
            $collors= Color::whereIn('id',$sarx)->get();
            $ddss=[];
            foreach($collors as $kks)
            {   
                $cardtxx=AddCart::where('user_id',$key->user_id)->where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                if(count($cardtxx)>0)
                {
                  $kks->selected_color_index=$cardtxx[0]->color_index; 
                  $kks->selected_color=$cardtxx[0]->color; 
                  $kks->is_cart_qty=$cardtxx[0]->qty; 
                }
                else
                {
                    $kks->selected_color_index=null; 
                    $kks->is_cart_qty=0; 
                    $kks->selected_color=0;
                }
                $skks=Stock::where('product_id',$key->id)->where('color',$kks->id)->where('is_active',1)->get();
                //$kks->is_color=count($skks);
                if(count($skks)>0)
                {
                    $kks->is_color=1;
                }
                else
                {
                    $kks->is_color=0;
                }
                $ddss[]=$kks;
            }
            $products->color_array=$ddss;
            $stockf=StockFetch::where('product_id',$products->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->first();
            if(isset($stockf))
            {    $products->stockfetch_id=$stockf->id;
                 $products->variable=$stockf->variable;
                 $datanewsar= $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$stockf->size)->where('color',$stockf->color)->where('price',$stockf->sales_rate)->get();
                 $products->sizename=Size::where('id',$stockf->size)->first()->name;
                 $products->colorname=Color::where('id',$stockf->color)->first()->name;
                 $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
            }
            else
            {
                $datanewsar= $products->stock_array_show=Stock::where('product_id',$products->id)->where('size',$sar[0])->where('color',$sarx[0])->orderBy('id','desc')->limit(1)->get();
                if(count($datanewsar)>0)
                      {
                          $qtysdydyd=$datanewsar[0]->quantity-$datanewsar[0]->sold_qty;;
                      }else{
                          $qtysdydyd=0;;
                      }
                $products->sizename=Size::where('id',$sar[0])->first()->name;
                $products->colorname=Color::where('id',$sarx[0])->first()->name;
               $products->stockfetch_id='';
               $products->variable='';
                
            }
            //$products->stock_array_show=
            $products->stock_array=Stock::where('product_id',$products->id)->whereIn('size',$sar)->whereIn('color',$sarx)->orderBy('id','desc')->get();
            $products->product_image=ProductImage::select(['name'])->where('product_id',$products->id)->get();
            $products->review=ProductReview::where('product_id',$products->id)->join('users','users.id','=','product_review.user_id')->select(['product_review.*','users.name as user_name'])->get();
           // $dp[]=$products;
            //  if($qtysdydyd>0)
            // {
               $dp[]=$products;
            //  }   
            }
        }
       
        if(count($dp)>0)
        {
            $res['count']=$cnt;
                $res['total_pages']=$total_pages;
                $res['data']=$dp;
                return $res;
        }
        else
        {
            $response['msg']='No Data Found';
            return json_encode($response);
        }
        
    }
    
    
    public function AddMakeabillApp(Request $request)
    {    
        $userid=$request->user_id;
        $user=User::where('id',$userid)->first();
        $request['vendor_id']=$user->vendor_id;
        $post=AddCart::where('user_id',$userid)->get();
        // print_r($post);exit;
        if(count($post)>0)
        {
            foreach($post as $key) 
            {
                $request['product_id']=$key->product_id;
                $request['color']=$key->color;
                $request['size']=$key->size;
                $request['qty']=$key->qty;
                $request['pg_charges']=0.0;
                if($key->stockfetch_id!='' || $key->stockfetch_id!='0')
                {
                  $stf=StockFetch::where('id',$key->stockfetch_id)->first();
                }else
                {
                   $stf=StockFetch::where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('sales_rate',$key->price)->where('quantity','>', '0')->first(); 
                }
                
                $request['purchase_rate']=$stf->purchase_rate;
                $request['sales_rate']=$stf->sales_rate;
                $request['total_price']=$stf->sales_rate;
                $stfc=Stock::where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('price',$request->sales_rate)->where('purchase_rate',$request->purchase_rate)->first();
                //print_r($stfc);exit;
                $request['stock_id']=$stfc->id;
                $request['gst']=$stfc->gst;
                $request['sgst']=$stfc->sgst;
                $request['tgst']=$stfc->tgst;
                $request['igst']=$stfc->igst;
                $request['cgst']=$stfc->cgst;
                $request['taxable_rate']=$stfc->taxable_rate;
                $request['total_amount']=round(($stf->sales_rate*$key->qty),2);
                $sql=Makeabill::where('user_id',$request->user_id)->where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('purchase_rate',$request->purchase_rate)->where('sales_rate',$request->sales_rate)->first();
                if(isset($sql))
                {   
                    $qty=$sql->qty + $request->qty;  
                    $sfrt=StockFetch::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('purchase_rate',$request->purchase_rate)->where('sales_rate',$request->sales_rate)->first();
                    $qqty=$sfrt->quantity;
                    if($qqty<$qty)
                    {
                    }
                    else
                    {
                        $sql->qty=$qty;
                        $sql->total_amount=round(($sql->sales_rate*$qty),2);
                       $categoryId= $sql->update();
                    }
                }
                else
                {
                    $data = $request->only('user_id','vendor_id','product_id','size','color', 'stock_id','qty','purchase_rate','sales_rate','pg_charges','total_price','gst','sgst','tgst','cgst','igst','taxable_rate','total_amount');
                    $categoryId = Makeabill::create($data)->id;
                }
                $vendorProduct = AddCart::findOrFail($key->id);
                $rr=$vendorProduct->delete();
            }
            if($categoryId)
            {
                $response['msg']='Transfered Successful';
                $response['resid']='200';
        }else
            {
                $response['msg']='Error Occururd';
                $response['resid']='202';
        }
        }
        else
        {
            $response['msg']='Cart is empty';
            $response['resid']='202';
         }
        return json_encode($response);
        
    }
}
