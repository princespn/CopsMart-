<?php
namespace App\Http\Controllers\API\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImage;
use App\ProductRecomend;
use App\VendorProduct;
use App\Package;
use App\Category;
use App\Size;
use App\Color;
use App\Stock;
use App\Order;
use App\OrderProduct;
use App\StockFetch;
use App\Suggession;
use App\SubCategory;
use App\ProductPackage;
use App\ServiceArea;
use App\CoSubCategory;
use App\Vendor;
use App\User;
use App\ProductReview;
use DB;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::latest()->with('subCategory')->paginate(10);
    }

    public function all()
    {   
        $id=$_GET['ad'];
        return Product::where('vendor_id',$id)->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'description' => 'required|max:300',
            'attributex' => 'required',
            'attributesub' => 'required',
            'category_id' => 'required',
            'sub_sub_category_id' => 'required',
            'sub_category_id' => 'required',
            'barcode' => 'required|string|max:150|regex:/^[A-Za-z0-9\-\s]+$/',
            'maxqty'=>'required|numeric',
            'brand_id'=>'required',
            'weight'=>'required',
            'tags'=>'required',
            'mrp'=>'required|numeric',
            'hsn' => 'required|numeric',
            'gst' => 'required|numeric',
            'images' => 'required',
        ]);
       
         $arb=explode(',',$request->attributesub);
         $arb1=array_unique($arb);
         $aarb=implode(',',$arb1);

         $arbx=explode(',',$request->attributex);
         $arbx1=array_unique($arbx);
         $aarbx=implode(',',$arbx1);

            $arbxx=explode(',',$request->tags);
            $arbxx1=array_unique($arbxx);
            $vendor_array = implode(',',$arbxx1);;
            $product = new Product();
            $product->vendor_id = $request->vendor_id;
            $product->name = ucwords($request->name);
            $product->description = ucwords($request->description);
            $product->category_id = $request->category_id;
            $product->sub_sub_category_id = $request->sub_sub_category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->barcode = $request->barcode;
            $product->hsn = $request->hsn;
            $product->gst = $request->gst;
            $product->maxqty = $request->maxqty;
            $product->brand_id = $request->brand_id;
            $product->weight = $request->weight;

            $product->item_code = $request->item_code;
            $product->cpc_price = $request->cpc_price;
            $product->box_qty = $request->box_qty;
            $product->supplier_company = $request->supplier_company;

            $product->other = ucwords($request->other);
            $product->tags = $vendor_array ;
            $product->size = $aarbx;
            $product->mrp = $request->mrp;
            $product->hide = $request->hide;
            $product->color = $aarb;
            $product->save();
            $productId = $product->id;
            $productImages = [];
            if(isset($request->images)):
            foreach ($request->images as  $image) {
                if($image->extension() == 'png' || $image->extension() == 'jpg' || $image->extension() == 'jpeg'){
                    //$image->file('thumb');
                    // $image->move("/uploads/images/product", $image->hashname());
                    $image->store('/images/product', ['disk' => 'uploads']);
                    $productImages[] = [
                        'name' => $image->hashname(),
                        'product_id' => $productId,
                    ];
                   
                }
            }
            
            ProductImage::insert($productImages);
            

            //Product::findOrFail($productId)->update(['image' => $productImages[0]['name']]);
        endif;
        
        $stock = new StockFetch();
        $stock->vendor_id = $request->vendor_id;
        $stock->product_id = $productId;
        $stock->quantity = 0;
        $stock->save();
        return [
            'message' => 'created Successfully',
        ];

    }


    public function productcsv(Request $request)
    {
        $file = $request->file('csv');
        // File Details 
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        $location = 'uploads';
        // Upload file
        $file->move(public_path($location),$filename);
  
        // Import CSV to Database
        $filepath = public_path($location."/".$filename);
        
        $file = fopen($filepath,"r");
        $importData_arr = array();
        $i = 0;
  
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) 
            {
               $num = count($filedata );
               for ($c=0; $c < $num; $c++) {
                  $importData_arr[$i][] = $filedata [$c];
               }
               $i++;
            }
            fclose($file);
         //   print_r($importData_arr);exit;
            if(count($importData_arr)>0)
            {
                for($i=1; $i < count($importData_arr);$i++)
                {           
                    $arb=explode(',',$importData_arr[$i][15]);
                    $arb1=array_unique($arb);
                    $aarb=implode(',',$arb1);
           
                    $arbx=explode(',',$importData_arr[$i][14]);
                    $arbx1=array_unique($arbx);
                    $aarbx=implode(',',$arbx1);
           
                    $arbxx=explode(',',$importData_arr[$i][13]);
                    $arbxx1=array_unique($arbxx);
                    $vendor_array = implode(',',$arbxx1);;
                            
                    $product = new Product();
                    $product->vendor_id = $request->vendor_id;
                    $product->name = ucwords($importData_arr[$i][1]);
                    $product->description = ucwords($importData_arr[$i][2]);
                    $product->category_id = $importData_arr[$i][3];
                    $product->sub_sub_category_id = $importData_arr[$i][5];
                    $product->sub_category_id = $importData_arr[$i][4];
                    $product->barcode = $importData_arr[$i][6];
                    $product->hsn = $importData_arr[$i][7];
                    $product->gst = $importData_arr[$i][9];
                    $product->maxqty = $importData_arr[$i][10];
                    $product->brand_id = $importData_arr[$i][17];
                    $product->weight = $importData_arr[$i][11];
                    $product->other = ucwords($importData_arr[$i][12]);
                    $product->tags = $vendor_array ;
                    $product->size = $aarbx;
                    $product->mrp = $importData_arr[$i][8];
                    $product->hide = $importData_arr[$i][16];
                    $product->box_qty = $importData_arr[$i][18];
                    $product->item_code = $importData_arr[$i][19];
                    $product->cpc_price = $importData_arr[$i][20];
                    $product->supplier_company = $importData_arr[$i][21];
                    $product->color = $aarb;
                    $pp=$product->save();
                }
            }

            if($pp){
                $ddtc= [
                    'message' => 'Products Added Successfully',
                    'resid'=>200
                ];
            }else{
                $ddtc=  [
                    'message' => 'Error Occured',
                    'resid'=>202
                ];
            }
           return json_encode($ddtc);
    }


    // update

    public function update(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'description' => 'required|max:300',
            'attributex' => 'required',
            'attributesub' => 'required',
            'category_id' => 'required|numeric',
            'sub_sub_category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'barcode' => 'required|string|max:150|regex:/^[A-Za-z0-9\-\s]+$/',
            'maxqty'=>'required',
            'brand_id'=>'required',
            'weight'=>'required',
            'tags'=>'required',
            'mrp'=>'required|numeric',
            'hsn' => 'required|numeric',
            'gst' => 'required|numeric',
            // 'images' => 'required',
            'is_active'=>'required'
           // 'commodity_type_id' => 'required|numeric',
        ]);
        
      if(isset($request->pimage1)){
        $name = 'pimage1_'.$id.'_'.$request->vendor_id.'_'.time().'.' . explode('/', explode(':', substr($request->pimage1, 0, strpos($request->pimage1, ';')))[1])[1];
        \Image::make($request->pimage1)->save(public_path('uploads/images/product/').$name);
        // $request->merge(['image' => $name]);
        $productxx1 = ProductImage::where('product_id',$id)->where('name',$request->image1)->first();
        if(isset($productxx1))
        {
            $datax1['name']=$name;
            $productxx1->update($datax1) ;
        }
        else
        {  
            $productImages = [
                'name' => $name,
                'product_id' => $id,
            ];
            ProductImage::insert($productImages);
        }
        }

        if(isset($request->pimage2)){
        $name = 'pimage2_'.$id.'_'.$request->vendor_id.'_'.time().'.' . explode('/', explode(':', substr($request->pimage2, 0, strpos($request->pimage2, ';')))[1])[1];
        \Image::make($request->pimage2)->save(public_path('uploads/images/product/').$name);
        // $request->merge(['image' => $name]);
        $productxx2 = ProductImage::where('product_id',$id)->where('name',$request->image2)->first();
        if(isset($productxx2))
        {
            $datax2['name']=$name;
            $productxx2->update($datax2) ;
        }
        else
        {  
            $productImages = [
                'name' => $name,
                'product_id' => $id,
            ];
            ProductImage::insert($productImages);
        }
        }

        if(isset($request->pimage3)){
        $name = 'pimage3_'.$id.'_'.$request->vendor_id.'_'.time().'.' . explode('/', explode(':', substr($request->pimage3, 0, strpos($request->pimage3, ';')))[1])[1];
        \Image::make($request->pimage3)->save(public_path('uploads/images/product/').$name);
        // $request->merge(['image' => $name]);
        $productxx3 = ProductImage::where('product_id',$id)->where('name',$request->image3)->first();
        if(isset($productxx3))
        {
            $datax3['name']=$name;
            $productxx3->update($datax3) ;
        }
        else
        {  
            $productImages = [
                'name' => $name,
                'product_id' => $id,
            ];
            ProductImage::insert($productImages);
        }
        }

        if(isset($request->pimage4)){
        $name = 'pimage4_'.$id.'_'.$request->vendor_id.'_'.time().'.' . explode('/', explode(':', substr($request->pimage4, 0, strpos($request->pimage4, ';')))[1])[1];
        \Image::make($request->pimage4)->save(public_path('uploads/images/product/').$name);
        // $request->merge(['image' => $name]);
        $productxx4 = ProductImage::where('product_id',$id)->where('name',$request->image4)->first();
        if(isset($productxx4))
        {
            $datax4['name']=$name;
            $productxx4->update($datax4) ;
        }
        else
        {  
            $productImages = [
                'name' => $name,
                'product_id' => $id,
            ];
            ProductImage::insert($productImages);
        }
        }

        if(isset($request->pimage5)){
        $name = 'pimage5_'.$id.'_'.$request->vendor_id.'_'.time().'.' . explode('/', explode(':', substr($request->pimage5, 0, strpos($request->pimage5, ';')))[1])[1];
        \Image::make($request->pimage5)->save(public_path('uploads/images/product/').$name);
        // $request->merge(['image' => $name]);
        $productxx5 = ProductImage::where('product_id',$id)->where('name',$request->image5)->first();
        if(isset($productxx5))
        {
            $dataxx['name']=$name;
            $productxx5->update($dataxx) ;
        }
        else
        {  
            $productImages = [
                'name' => $name,
                'product_id' => $id,
            ];
            ProductImage::insert($productImages);
        }
        // exit;
        }
        $arb=explode(',',$request->attributesub);
        $arb1=array_unique($arb);
        $aarb=implode(',',$arb1);
       // $request->merge(['color' => $aarb]);
     //   echo $request->color; exit;
        $arbx=explode(',',$request->attributex);
        $arbx1=array_unique($arbx);
        $aarbx=implode(',',$arbx1);
        //$request->merge(['size' => $aarbx]);
        $arbxx=explode(',',$request->tags);
        $arbxx1=array_unique($arbxx);
        $vendor_array = implode(',',$arbxx1);;
        // $request->merge(['tags' => $vendor_array]);

        // $request->merge(['name' => ucwords($request->name)]);
        // $request->merge(['description' => ucwords($request->description)]);
        // $request->merge(['other' => ucwords($request->other)]);
       // print_r($request->all());exit;
        // $data = $request->only('vendor_id', 'name','description', 'category_id', 'sub_sub_category_id','sub_category_id','barcode',
        // 'hsn', 'gst', 'maxqty', 'brand_id','weight','mrp','other','tags','size','color','is_active','is_featured');
        
        $product->vendor_id = $request->vendor_id;
        $product->name = ucwords($request->name);
        $product->description = ucwords($request->description);
        $product->category_id = $request->category_id;
        $product->sub_sub_category_id = $request->sub_sub_category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->vendor_id = $request->vendor_id;
        $product->barcode = $request->barcode;
        $product->hsn = $request->hsn;
        $product->gst = $request->gst;
        $product->maxqty = $request->maxqty;
        $product->brand_id = $request->brand_id;
        $product->weight = $request->weight;
        $product->mrp = $request->mrp;
        $product->hide = $request->hide;
        $product->other = ucwords($request->other);
        $product->tags =  $vendor_array;
        $product->size = $aarbx;
        $product->color = $aarb;
        $product->is_active = $request->is_active;
        $product->is_featured = $request->is_featured;
        $product->item_code = $request->item_code;
        $product->cpc_price = $request->cpc_price;
        $product->box_qty = $request->box_qty;
        $product->supplier_company = $request->supplier_company;

        // $product->save();
       


        if($product->update()){
            return [
                'message' => 'Updated Successfully',
                'images' => '',
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $data=[];
        $product= Product::where('products.id',$id)
        ->leftJoin("attributes as v",\DB::raw("FIND_IN_SET(v.id, products.tags)"),">",\DB::raw("'0'"))
        ->leftJoin("sizes as s",\DB::raw("FIND_IN_SET(s.id, products.size)"),">",\DB::raw("'0'"))
        ->leftJoin("colors as c",\DB::raw("FIND_IN_SET(c.id, products.color)"),">",\DB::raw("'0'"))
        ->select(['products.*',\DB::raw('GROUP_CONCAT(v.name ORDER BY v.id) as tag_name'),\DB::raw('GROUP_CONCAT(s.name ORDER BY s.id) as size_name'),\DB::raw('GROUP_CONCAT(c.name ORDER BY c.id) as color_name')])->get();
        foreach($product as $key)
        {   
            $key->image1='';
            $key->image2='';
            $key->image3='';
            $key->image4='';
            $key->image5='';
            $proimage=ProductImage::where('product_id',$key->id)->orderBy('id','desc')->limit(5)->get();
            // print_r($proimage);
            // exit;
            $i=count($proimage);
            foreach($proimage as $keyx)
            {    $ke='';
               //exit;
                $ke=$keyx->name;
                if($i==1){ $key->image1=$ke; }if($i==2){ $key->image2=$ke; }if($i==3){ $key->image3=$ke; }
                if($i==4){ $key->image4=$ke; }if($i==5){ $key->image5=$ke; } 
                 $i--;
            }
            // echo $key->image1;exit;
            $data[]=$key;
            $data=$data[0];
        }
        return $data;
    }

    public function getproduct($id)
    {   
        $data=[];
        $product= Product::where('products.id',$id)->leftJoin('brands','products.brand_id','=','brands.id')
        ->leftJoin("attributes as v",\DB::raw("FIND_IN_SET(v.id, products.tags)"),">",\DB::raw("'0'"))
        ->leftJoin("sizes as s",\DB::raw("FIND_IN_SET(s.id, products.size)"),">",\DB::raw("'0'"))
        ->leftJoin("colors as c",\DB::raw("FIND_IN_SET(c.id, products.color)"),">",\DB::raw("'0'"))
        ->select(['products.*','brands.name as brandname',\DB::raw('GROUP_CONCAT(v.name ORDER BY v.id) as tag_name'),\DB::raw('GROUP_CONCAT(s.name ORDER BY s.id) as size_name'),\DB::raw('GROUP_CONCAT(c.name ORDER BY c.id) as color_name')])->get();
        foreach($product as $key)
        {   
            $key->image1='';
            $key->image2='';
            $key->image3='';
            $key->image4='';
            $key->image5='';
            $proimage=ProductImage::where('product_id',$key->id)->orderBy('id','desc')->limit(5)->get();
            // print_r($proimage);
            // exit;
            $i=count($proimage);
            foreach($proimage as $keyx)
            {    $ke='';
               //exit;
                $ke=$keyx->name;
                if($i==1){ $key->image1=$ke; }if($i==2){ $key->image2=$ke; }if($i==3){ $key->image3=$ke; }
                if($i==4){ $key->image4=$ke; }if($i==5){ $key->image5=$ke; } 
                 $i--;
            }
            $category=Category::where('id',$key->category_id)->first();
            $scategory=Category::where('id',$key->sub_category_id)->first();
            $sscategory=SubCategory::where('id',$key->sub_sub_category_id)->first();

            $key->category=$category->name;
            $key->subcategory=$scategory->name;
            $key->subsubcategory=$sscategory->name;
            // echo $key->image1;exit;
            $data[]=$key;
            $data=$data[0];
            $stock=StockFetch::where('product_id',$id)->sum('quantity');
            $prorev=ProductReview::where('product_id',$id)->get();
        }
        return array('product'=>$data,'stock'=>$stock,'product_count'=>count($prorev));
    }
    
    public function orderproductview(Request $request,$id)
    {  
      $length = $request->input('length');
      $column = $request->input('column'); //Index
      $orderBy = $request->input('dir', 'asc');
      $searchValue = $request->input('search');
      $query=OrderProduct::join('products','products.id','=','order_products.product_id')->join('orders','orders.id','=','order_products.order_id')->join('users','orders.user_id','=','users.id')->where('order_products.product_id',$id)->where('order_products.deleted_at',NULL)->select(['orders.invoice_no as inv_no','orders.date as inv_date','users.name as customer','orders.amount as amount','orders.id']);
      if($searchValue!=''){
        $query->where('products.name', "LIKE", "%$searchValue%");
       }
       $data = $query->paginate($length);
       return new DataTableCollectionResource($data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        // delete the product
        return [
            'message' => 'Product Deleted !'
        ];
    }

    function dataTable(Request $request,$id,$cat,$scat,$sscat){
        
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        
        $query = Product::orderBy('products.id', 'DESC')
        ->select(['products.*']);
        if($searchValue!=''){
            $query->where('products.name', "LIKE", "%$searchValue%");
        }
        $qparam = [];
        if($cat!='' && $cat!='All'){
            array_push($qparam, ['products.category_id','=',$cat]);
        }
        if($scat!='' && $scat!='All'){
            array_push($qparam, ['products.sub_category_id','=',$scat]);
        }

        if($sscat!='' && $sscat!='All'){
            array_push($qparam, ['products.sub_sub_category_id','=',$sscat]);
        }
        // print_r($qparam);
        $query=$query->where($qparam);
        
        $data = $query->paginate($length);
       
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $key->srno=$i;
                $sizee=explode(',',$key->size);
                $sc=Size::WhereIn('id',$sizee)->select('name')->get();
                $ds=[];
                foreach($sc as $sssc)
                {
                    $ds[]=$sssc->name;
                }
                $key->sizename=implode(',',$ds);

                $colo=explode(',',$key->color);
                $scx=Color::WhereIn('id',$colo)->select('name')->get();
                $dsxx=[];
                foreach($scx as $ssscx)
                {
                    $dsxx[]=$ssscx->name;
                }
                $key->colorname=implode(',',$dsxx);
                $image=ProductImage::select(['name'])->where('product_id',$key->id)->first();
                if(isset($image))
                {
                $key->image=$image->name;
                }
                $dt[]=$key;
                $i++;
            }
            $dt=$data;
            return new DataTableCollectionResource($dt);
        }
        else
        {
            return new DataTableCollectionResource($data);
        }
        
    }


    function productlist($id)
    {
        return Product::where('deleted_at',NULL)->get();
    }

    function package(Request $request){
        $product_package = new ProductPackage();
        $product_package = $product_package->where('product_id',$request->product_id)->get();

        return ['product_package'=>$product_package];
    }

    public function category(Request $request)
    {
        $id=$_GET['ad'];
        if($id=='1')
        {
        $category = new Category();
        $category = Category::where([['is_active',1],['parent_id','!=',0]])->get();
        }
        else
        {   
            $user=User::where('id',$id)->first();
            $cat=$user->category_id;
            if($cat=='0'){
                $category = new Category();
                $category = Category::where([['is_active',1],['parent_id','!=',0]])->get();
            }
            else
            {
                $category = new Category();
                $category = Category::whereIn('parent_id', [$cat])->where([['is_active',1],['parent_id','!=',0]])->get();
            }
            
        }
       
        return ['pro_category'=>$category];
    }

    public function getbarcodeDetails($id,$aa)
    {
       return Product::where('products.barcode',$aa)->get();
    }

    public function getproDetails($id,$aa)
    {
       return Product::where('products.name', "LIKE", "%$aa%")->get();
  
    }

    public function getproDetailsNew($id)
    {  
        $datasne=[];
        $pro=Product::get();
       foreach($pro as $key)
       {
        
        $nmdcv=$key->name;
        $key->name=$key->name.'/'.$key->barcode; $sar=explode(',',$key->color);$saze=explode(',',$key->size);
        $color= Color::whereIn('id',$sar)->where('is_active',1)->get();$size= Size::whereIn('id',$saze)->where('is_active',1)->get();$key->sizesx=$size;$key->colorsx=$color;
        $data=StockFetch::where('stock_fetches.is_active',1)->where('stock_fetches.product_id',$key->id)->where('stock_fetches.vendor_id',$id)->whereNotIn('stock_fetches.quantity', ['0'])->orderBy('stock_fetches.quantity','asc')->select(['stock_fetches.*'])->get();
        // print_r( $data);exit;
        $dt=[];$st=0;
       if(count($data)>0)
       {
            foreach($data as $keys)
            {   
                if($keys->variable==''){ $keys->name=$key->name;}else{$keys->name=$nmdcv.'-'.$keys->variable; }
                $st=0;
                $ndtv=Stock::where('size',$keys->size)->where('stocks.vendor_id',$id)->where('color',$keys->color)->where('product_id',$keys->product_id)->where('purchase_rate',$keys->purchase_rate)->where('price',$keys->sales_rate)->get();
                if(count($ndtv)==0){$st=0;}else
                {
                if(count($ndtv)>1)
                {
                    $st=$ndtv[0]->quantity - $ndtv[0]->sold_qty;    if($st==0){$st=$ndtv[1]->quantity - $ndtv[1]->sold_qty;}
                }
                else
                {
                    $st=$ndtv[0]->quantity - $ndtv[0]->sold_qty;  
                }
                }
                $keys->mstock=$st;$keys->stock_details=$ndtv;$dt[]=$keys;
            }
       }
          $key->product=$dt;
          if($st>0){ $datasne[]=$key;}
       }

       return $datasne;
  
    }

    public function franscategory(Request $request){
        $category = new Category();
        $category = Category::where([['is_active',1],['parent_id','=',0]])->get();
        return ['pro_category'=>$category];
    }

    public function sup_sub_category(Request $request){
        $subcategory = new SubCategory();
        $subcategory = SubCategory::where([['is_active',1],['category_id',$request->category_id],['parent_id',0]])->get();
        return ['sup_sub_category'=>$subcategory];
    }

    public function sub_category(Request $request){
        
        $subcategory = new SubCategory();
        $subcategory = SubCategory::where([['is_active',1],['parent_id',$request->sub_category_id]])->get();
        return ['sub_category'=>$subcategory];
    }

    public function co_sub_category(Request $request)
    {        
        //$subcategory = new CoSubCategory;();
        $cosubcategory = CoSubCategory::where([['sub_category_id',$request->co_sub_category_id]])->get();
        return ['co_sub_category'=>$cosubcategory];
    }
    
    public function loadProductCat($category_id){
        return ['product'=>Product::where([['is_active',1],['category_id',$category_id]])->get()];
        
    }

    public function category_by_id(Request $request){
        //get cat from product
        $Product = new Product();
        $category_id = $Product->pluck('category_id');
        return Category::where([['parent_id',$request->category_id]])->where('is_active',1)->get();
    }

    public function package_list(Request $request){
        return ['package'=>Package::get()];
    }

    public function activeProducts(Request $request){
        
        $validator = \Validator::make($request->all(), ['category_id'=>'required|numeric','sup_sub_category_id'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }
        return Product::with('packages')->where([['category_id',$request->category_id],['sup_sub_category_id',$request->sup_sub_category_id],['is_active',1]])->get();
    }
    
    public function FetchLayout(Request $request)
    {   
         $latitude=$request->latitude;
         $longitude=$request->longitude;
         $pos=$request->position;
         $sarea=Vendor::get();
         $sugg=Suggession::where('is_active',1)->where('position',$pos)->get();
        //  print_r($sugg);
        //  exit;
         if(count($sugg)>0)
         {
        foreach($sugg as $sug)
        {
        
         $sg=explode(',',$sug->product_id);
       //  print_r($sarea);exit;
         foreach($sarea as $key)
         {
              $range=$key->service_range; 
             $get_distance = DB::select("select id, ( 3959 * acos( cos( radians('$latitude') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians( latitude ) ) ) ) AS distance FROM  vendors HAVING distance <=$range/1000");
             if(count($get_distance)>0)
             {
                 $idx=$get_distance;
             }
         }
        
       
        foreach ($idx as $key)
        {  
           $sareaxx=Vendor::where('category_id',$sug->category_id)->where('id',$key->id)->get(); 
           if(count($sareaxx)>0)
           {  $resp['products']=[];
              foreach($sg as $pro)
              { 
                //$vendorpro=VendorProduct::where('vendor_id',$sareaxx[0]->id)->where('product_id',$pro)->where('is_active',1)->groupBy('product_id')->get();  
                
                    $vendorpro = DB::table('vendor_products')->where('vendor_products.vendor_id',$sareaxx[0]->id)
                    ->where('vendor_products.product_id',$pro)->where('vendor_products.is_active',1)
                    ->join('products', 'vendor_products.product_id','=', 'products.id')
                    ->join('product_images', 'vendor_products.product_id','=', 'product_images.product_id')
                    ->join('packages', 'vendor_products.package_id','=', 'packages.id')
                    ->groupBy('vendor_products.product_id')
                    ->select('vendor_products.*','vendor_products.id as vidd','products.*','packages.name as pkgname','products.sup_sub_category_id','products.name', 'products.long_description','products.description','products.name','product_images.name as image')->get();
                //   print_r($vendorpro[0]);
                //   exit;
                
                 if(count($vendorpro)>0)
                 {   
                     $ven= Vendor::where('id',$vendorpro[0]->vendor_id)->first();
                    //print_r($ven);//exit;
                    if($ven->fif_nine=='1')
                    {
                        $vprice=$ven->vprice;
                    }
                    else
                    {
                        $vprice=$vendorpro[0]->price;
                    }
                     $resp['layout']=$sug->layout;   
                     $resp['heading']=$sug->heading;   
                     $resp['position']=$sug->position;  
                     $resp['image']=$vendorpro[0]->image;
                     $resp['sup_sub_category_id']=$vendorpro[0]->sup_sub_category_id;
                     $resp['vendor_id']=$vendorpro[0]->vendor_id;
                     $data['sup_sub_category_id']=$vendorpro[0]->sup_sub_category_id;
                     $data['vendor_id']=$vendorpro[0]->vendor_id;
                     $data['category_id']=$vendorpro[0]->category_id;
                     $data['id']=$vendorpro[0]->id;
                     $data['description']=$vendorpro[0]->description;
                     $data['long_description']=$vendorpro[0]->long_description;
                     $data['vendor_product_id']=$vendorpro[0]->vidd;
                     $data['product_id']=$vendorpro[0]->product_id;
                     $data['vendor_status']=$sareaxx[0]->is_active;
                     $data['package']=$vendorpro[0]->package_id;
                     $data['package_id']=$vendorpro[0]->package_id;
                     $data['package_name']=$vendorpro[0]->pkgname;
                     $data['product_type']=$vendorpro[0]->product_type;
                     $data['product_name']=$vendorpro[0]->name;
                     $data['price']=$vprice;
                     $data['mrp']=$vendorpro[0]->mrp;
                     $data['cost_price']=$vendorpro[0]->cost_price;
                     $data['offer_price']=$vendorpro[0]->offer_price;
                     $data['offer_status']=$vendorpro[0]->offer_status;
                      $data['daily_needs']=$vendorpro[0]->daily_needs;
                     $data['is_active']=$vendorpro[0]->is_active;
                     
                     $data['name']=$vendorpro[0]->name;
                     $data['image']=$vendorpro[0]->image;
                     $data['long_description']=$vendorpro[0]->long_description;
                     $data['description']=$vendorpro[0]->description;
                     $resp['products'][]=$data;
                     
                 }
                 
              }
              if(count($resp['products'])>0)
              {
               return $response[]=$resp; 
              }
              else
              {
                  return json_encode (json_decode ("{}"));
              }
           }
            
        
        }

          
            
        }
         }
         else
         {
            return json_encode (json_decode ("{}"));
         }
           
    }

    public function GetproductData(Request $request)
    {
       $data=StockFetch::where('stock_fetches.is_active',1)->where('stock_fetches.product_id',$request->product_id)->where('stock_fetches.vendor_id',$request->vendor_id)->where('stock_fetches.size',$request->size)->where('stock_fetches.color',$request->color)->join('products','products.id','=','stock_fetches.product_id')->orderBy('stock_fetches.id','desc')->select(['products.name as product_name','stock_fetches.*'])->get();
       $dt=[];
       if(count($data)>0)
       {
            foreach($data as $key)
            {   
                if($key->variable=='')
                {
                $key->name=$key->product_name;
                }
                else
                {
                    $key->name=$key->product_name.'-'.$key->variable;
                }
                $dt[]=$key;
            }
       }
     return $dt;
    }
    
    /*public function getcountstock($request)
    {  
       $st=0;
       $dtt=StockFetch::where('id',$request)->first();
       $data=Stock::where('size',$dtt->size)->where('color',$dtt->color)->where('product_id',$dtt->product_id)->where('purchase_rate',$dtt->purchase_rate)->where('price',$dtt->sales_rate)->get();
       dd($data);
       
       //if(count($data)>1)
       if(count($data[0]->quantity)>=1)
       {
        $st=$data[0]->quantity - $data[0]->sold_qty;
       }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
       // if($st==0)
       else if(count($data[1]->quantity)>=1)
        {
            $st=$data[1]->quantity - $data[1]->sold_qty;
       // }
       }
       else
       {
         $st=$data[0]->quantity - $data[0]->sold_qty;  
       }
     return $st;
    }*/

    public function getcountstock($request)
    {  
       $st=0;
       $dtt=StockFetch::where('id',$request)->first();
       $data=Stock::where('size',$dtt->size)->where('color',$dtt->color)->where('product_id',$dtt->product_id)->where('purchase_rate',$dtt->purchase_rate)->where('price',$dtt->sales_rate)->get();
       if(count($data)>1)
       {
        $st=$data[0]->quantity - $data[0]->sold_qty;
        if($st==0)
        {
            $st=$data[1]->quantity - $data[1]->sold_qty;
        }
       }
       else
       {
         $st=$data[0]->quantity - $data[0]->sold_qty;  
       }
     return $st;
    }

    public function recomendproduct(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'vendor_id' => 'required',
            'is_active' => 'required',
        ]);
        $product=new ProductRecomend();
        $vendor_array = implode(',',$request->product_id);;
        $product->vendor_id = $request->vendor_id;
        $product->product_id = $vendor_array;
        $product->is_active = $request->is_active;
        $product->save();
        return [
            'message' => 'Recomended Product Created Successfully !'
        ];
    }

    public function productrecomended($id)
    {
        $product= ProductRecomend::where('product_recomend.deleted_at',NULL)->where('product_recomend.vendor_id',$id)->join("products as v",\DB::raw("FIND_IN_SET(v.id, product_recomend.product_id)"),">",\DB::raw("'0'"))
        ->select(['product_recomend.*',\DB::raw("GROUP_CONCAT(v.name ORDER BY v.id) as product_name")])
        ->get();
        if($product[0]->product_id==NULL)
        {
          return json_encode(array()); 
        }
        else
        {
            return   $product;
        }
    }

    public function updaterecomendproduct(Request $request,$id)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'vendor_id' => 'required',
            'is_active' => 'required',
        ]);
        $product = ProductReComend::findOrFail($id);;
        $vendor_array = implode(',',$request->product_id);;
        $product->vendor_id = $request->vendor_id;
        $product->product_id = $vendor_array;
        $product->is_active = $request->is_active;
        // $product->save();
        $product->update();
        return [
            'message' => 'Recomended Product Updated Successfully !'
        ];
    }

    
    
}
