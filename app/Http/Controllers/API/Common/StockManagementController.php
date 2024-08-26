<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StockManagement;
use App\PurchasePayment;
use App\Stock;
use App\StockFetch;
use App\Purchase;
use App\TmpPurchase;
use App\Product;
use App\Size;
use App\Color;
use App\Category;
use App\SubCategory;
use App\ProductImage;
use App\OrderProduct;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class StockManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function StockColorFetch(Request $request)
    {
        $sar=explode(',',$request->color);
        $color= Color::whereIn('id',$sar)->where('is_active',1)->get();

        $saze=explode(',',$request->size);
        $size= Size::whereIn('id',$saze)->where('is_active',1)->get();
       //  $size[0]->id;
        // $color[0]->id;
        $data=StockFetch::where('stock_fetches.is_active',1)->where('stock_fetches.product_id',$request->product_id)->where('stock_fetches.vendor_id',$request->vendor_id)->where('stock_fetches.size',$size[0]->id)->where('stock_fetches.color',$color[0]->id)->whereNotIn('stock_fetches.quantity', ['0'])->join('products','products.id','=','stock_fetches.product_id')->orderBy('stock_fetches.quantity','asc')->select(['products.name as product_name','stock_fetches.*'])->get();
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

        return array(
            "size"=>$size,
            "color"=>$color,
            "product"=>$dt,
        );
    }

    public function getstockmanagedata(Request $request,$id,$filter)
    { 
        //echo $filter;exit;
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $searchValue = $request->input('search');
        $query = StockFetch::join('products','stock_fetches.product_id','=','products.id')->groupBy('products.id','stock_fetches.purchase_rate','stock_fetches.sales_rate','stock_fetches.size','stock_fetches.color')->where('products.deleted_at',NULL)->where('stock_fetches.vendor_id',$id)->select(['products.name as p_v_name','products.category_id','products.sub_category_id','products.sub_sub_category_id','products.id as pro_id','stock_fetches.*']);
        if($searchValue!=''){
            $query->where('products.name', "LIKE", "%$searchValue%");
        }
        if($filter=='Stock High to Low')
        {
            $query->orderBy('stock_fetches.quantity', 'desc');
        }
        elseif($filter=='Stock Low to High')
        {
            $query->orderBy('stock_fetches.quantity', 'asc');
        }
        elseif($filter=='Product Name Ascending')
        {
            $query->orderBy('products.name', 'asc');
        }
        else
        {
            $query->orderBy('stock_fetches.id', 'asc');
        }
      //  return $query->toSql();
        $data = $query->paginate($length);
        if(count($data)>0)
        {
            $dt=[];
            $cdx=[];
            $i=1;
            $newarr=array();
            foreach($data as $key)
            {   
                $dtx=[];
                $proimg=ProductImage::where('product_id',$key->pro_id)->orderBy('id','desc')->limit(1)->first();
                if(isset($proimg))
                {
                $img=$proimg->name;
                }else{$img='';}
                $sscname='';
                $cname='';
                $scname='';
                $cata=Category::where('id',$key->category_id)->first();
                if(isset($cata))
                {
                $cname=$cata->name;
                }
                //echo $key->sub_category_id;
                $scata=Category::where('id',$key->sub_category_id)->first();
               // print_r($scata->name);
                if(isset($scata))
                {
                    $scname=$scata->name;
                }
                $sscata=SubCategory::where('id',$key->sub_sub_category_id)->first();
                
                if(isset($sscata))
                {
                    $sscname=$sscata->name;
                }
                $cna=Color::where('id',$key->color)->first();
                $sna=Size::where('id',$key->size)->first();
                if($key->size==0 &&  $key->color==0)
                {
                  $key->s_c_name='-';
                }
                else
                {
                    $key->s_c_name=$sna->name.'/'.$cna->name;
                }
                if($key->variable=='')
                {
                    $key->p_v_name=$key->p_v_name;
                }
                else
                {
                    $key->p_v_name=$key->p_v_name.'-'.$key->variable;;
                }
                $key->image=$img;
                $key->cata_name=$cname;
                $key->sub_cata_name=$scname;
                $key->sub_sub_cata_name=$sscname;
                $key->stock=$key->quantity;
                $key->srno=$i;
                 $i++;

            }   
            
        }
        return new DataTableCollectionResource($data);
    }

    //abcdefghijklmnopqrstuvwxyz
   public function getstockshowdata(Request $request,$id,$filter)
    { 
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $searchValue = $request->input('search');
        $query = StockFetch::join('products','stock_fetches.product_id','=','products.id')->groupBy('products.id')->where('products.deleted_at',NULL)->where('stock_fetches.vendor_id',$id)->select(['products.name as p_v_name','products.hsn as hsncode','products.id as pro_id','stock_fetches.*']);
        if($searchValue!=''){
            $query->where('products.name', "LIKE", "%$searchValue%");
        }
        if($filter=='Stock High to Low')
        {
            $query->orderBy('stock_fetches.quantity', 'desc');
        }
        elseif($filter=='Stock Low to High')
        {
            $query->orderBy('stock_fetches.quantity', 'asc');
        }
        elseif($filter=='Product Name Ascending')
        {
            $query->orderBy('products.name', 'asc');
        }
        else
        {
            $query->orderBy('stock_fetches.id', 'asc');
        }
        $data = $query->paginate($length);
        if(count($data)>0)
        {
            $dt=[];
            $cdx=[];
            $i=1;
            $newarr=array();
            foreach($data as $key)
            {   
                $orderpoint=OrderProduct::join('orders','orders.id','=','order_products.order_id')->where('orders.vendor_id',$id)->where('order_products.deleted_at',NULL)->whereRaw('order_products.product_id='.$key->pro_id)->whereRaw('order_products.size='.$key->size)->whereRaw('order_products.color='.$key->color)->whereRaw('order_products.price ='.$key->sales_rate)->whereRaw('order_products.purchase_rate ='.$key->purchase_rate)->sum('qty');
                $key->orderpoint=$orderpoint;
                $StockCount=StockManagement::where('vendor_id',$id)->where('product_id',$key->pro_id)->where('size',$key->size)->where('color',$key->color)->whereRaw('purchase_rate ='.$key->purchase_rate)->whereRaw('sales_rate ='.$key->sales_rate)->sum('qty');
                $key->stockaddedcount=$StockCount;
                //echo $key->pro_id; echo $key->purchase_rate;echo $key->sales_rate;
                // $Purchaset=Stock::where('tmp_purchase_id','!=',0)->where('vendor_id',$id)->where('product_id',$key->pro_id)->where('size',$key->size)->where('color',$key->color)->whereRaw('purchase_rate = '.$key->purchase_rate)->whereRaw('price = '.$key->sales_rate)->first();
                // // print_r($Purchaset);
                $key->purchaseaddedcount=0;
                $pppp=TmpPurchase::where('is_active',0)->where('vendor_id',$id)->whereRaw('product_id='.$key->pro_id)->whereRaw('size='.$key->size)->whereRaw('color='.$key->color)->whereRaw('purchase_rate = '.$key->purchase_rate)->whereRaw('sales_rate = '.$key->sales_rate)->selectRaw('SUM(qty) as qty')->first();;
                // print_r($pppp);exit;
                if($pppp->qty!='')
                {
                $key->purchaseaddedcount=$pppp->qty;
                }
                else
                {
                     $key->purchaseaddedcount=0;
                }
                $cna=Color::where('id',$key->color)->first();
                $sna=Size::where('id',$key->size)->first();
                if($key->size==0 &&  $key->color==0)
                {
                  $key->s_c_name='-';
                }
                else
                {
                    $key->s_c_name=$sna->name.'/'.$cna->name;
                }
                if($key->variable=='')
                {
                    $key->p_v_name=$key->p_v_name;
                }
                else
                {
                    $key->p_v_name=$key->p_v_name.'-'.$key->variable;;
                }
                $key->sales_rate=round($key->sales_rate,2);
                $key->stock=$key->quantity;
                $key->srno=$i;
                $i++;

            }   
            
        }
        return new DataTableCollectionResource($data);
    }
    
    public function newGetSelstockdata($id,$vendor)
    {
        $result= StockFetch::where('vendor_id',$vendor)->where('id',$id)->where('is_active',1)->orderBy('id','asc')->limit(1)->get();
        $data=[];
        if(count($result)>0)
        {  
            foreach($result as $key)
            {   
                $stck=Stock::where('vendor_id',$vendor)->where('product_id',$key->product_id)->where('vendor_id',$key->vendor_id)->where('size',$key->size)->where('color',$key->color)->whereRaw('price='.$key->sales_rate)->whereRaw('purchase_rate='.$key->purchase_rate)->get();
                $stck[0]->stockk=$stck[0]->quantity - $stck[0]->sold_qty;
                $data[]=$stck[0];
            }
            $data= $data[0];
        }
        return $data;
    }

    public function GetStockData(Request $request)
    {   
        $result= Stock::where('vendor_id',$request->vendor_id)
        ->where('product_id',$request->product_id)
        ->where('color',$request->color)->where('size',$request->size)->where('is_active',1)->orderBy('id','desc')->limit(1)->get();
        $data=[];
        if(count($result)>0)
        {  
            foreach($result as $key)
            {
                $data[]=$key;
            }
            $data= $data[0];
        }
        return $data;
    }

    public function GetSelstockdata(Request $request)
    {   
        $result= Stock::where('vendor_id',$request->vendor_id)
        ->where('product_id',$request->product_id)
        ->where('color',$request->color)->where('size',$request->size)->where('is_active',1)->orderBy('id','asc')->limit(1)->get();
        $data=[];
        if(count($result)>0)
        {  
            foreach($result as $key)
            {  
                $key->stockk=$key->quantity - $key->sold_qty;
                $data[]=$key;
            }
            $data= $data[0];
        }
        return $data;
    }

    public function GetonlyStockData(Request $request)
    {   
        $result= Stock::where('vendor_id',$request->vendor_id)
        ->where('product_id',$request->product_id)
        ->where('color',$request->color)->where('size',$request->size)->where('is_active',1)->orderBy('id','desc')->get();
        $data=0;
        if(count($result)>0)
        {   $qtyy=0;
            foreach($result as $key)
            {
                $qtyy +=$key->quantity-$key->sold_qty;
            }
            $data=  $qtyy;
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function AddStockData(Request $request)
    {     
        $this->validate($request, [
           'vendor_id'=> 'required', 'product_id'=> 'required',
            'size'=> 'required','color'=> 'required','stock'=> 'required','qty'=> 'required', 'purchase_rate'=> 'required',
            'sales_rate'=> 'required','pg_charges'=> 'required','total_price'=> 'required','gst'=> 'required',
            'sgst'=> 'required','tgst'=> 'required','cgst'=> 'required','igst'=> 'required','taxable_rate'=> 'required','total_amount'=>'required'
        ]);  
        // $stock=Stock::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('price',$request->total_price)->first();
        // print_r($stockxn);
        // exit;
        $ttax=($request->taxable_rate*$request->qty);
        $request->merge(['total_taxable' => $ttax]);
        $ttgst=1+($request->gst/100);
        $ptaxval=($request->purchase_rate/$ttgst);
        $request->merge(['p_taxable_rate' => $ptaxval]);
        $ptottx=$ptaxval*$request->qty;
        $request->merge(['p_total_taxable' => $ptottx]);
        $ptgst=$ptaxval*($request->gst/100);
        $request->merge(['p_tgst' => $ptgst]);
        $pcgst=$psgst=$ptgst/2;
        $request->merge(['p_sgst' => $psgst]);
        $request->merge(['p_cgst' => $pcgst]);
        $request->merge(['p_total_amount' => $request->purchase_rate*$request->qty]);
        $data = $request->only('p_total_amount','p_taxable_rate','p_total_taxable','p_tgst','p_sgst','p_cgst','vendor_id','product_id','size','color', 'stock','qty','purchase_rate','sales_rate','pg_charges','total_price','gst','sgst','tgst','cgst','igst','taxable_rate','total_amount');
        $categoryId = StockManagement::create($data)->id;
        if(isset($categoryId))
        {  
            $stock=Stock::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('price',$request->total_price)->where('purchase_rate',$request->purchase_rate)->first();
            if(isset($stock))
            {
                $product = Stock::findOrFail($stock->id);
                $dataxx['quantity'] = $stock->quantity + $request->qty;
                $dataxx['old_quantity'] = $stock->quantity;
                $dataxx['gst'] = $request->gst;
                $dataxx['cgst'] = $request->cgst;
                $dataxx['sgst'] = $request->sgst;
                $dataxx['purchase_rate'] = $request->purchase_rate;
                $dataxx['igst'] = $request->igst;
                $dataxx['tgst'] = $request->tgst;
                $dataxx['taxable_rate'] = $request->taxable_rate;
                $product->update($dataxx);
            }
            else
            {   
                $product = new Stock();
                $product->vendor_id = $request->vendor_id;
                $product->product_id = $request->product_id;
                $product->quantity = $request->qty;
                $product->size = $request->size;
                $product->color = $request->color;
                $product->purchase_rate = $request->purchase_rate;
                $product->price = $request->total_price;
                $product->gst = $request->gst;
                $product->cgst = $request->cgst;
                $product->sgst = $request->sgst;
                $product->igst = $request->igst;
                $product->tgst = $request->tgst;
                $product->taxable_rate = $request->taxable_rate;
                $product->save();
            }
                $stockxn=StockFetch::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('sales_rate',$request->total_price)->where('purchase_rate',$request->purchase_rate)->first(); 
                if(isset($stockxn))
                {
                    $sfu = StockFetch::findOrFail($stockxn->id);
                    $dsfu['quantity'] = $stockxn->quantity + $request->qty;
                    $sfu->update($dsfu);
                }
                else
                {   
                    $scon=$check1=StockFetch::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('quantity',0)->where('size',0)->where('color',0)->orderBy('id','desc')->first();
                    if(isset($scon))
                    {
                        $sfun = StockFetch::findOrFail($scon->id);
                        $dsfu['quantity'] = $scon->quantity + $request->qty;
                        $dsfu['size'] = $request->size;
                        $dsfu['color'] =  $request->color;
                        $dsfu['sales_rate'] = $request->total_price;
                        $dsfu['purchase_rate'] = $request->purchase_rate;
                        $sfun->update($dsfu);
                    }
                    else
                    {
                        $sf = new StockFetch();
                        $check1=StockFetch::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->orderBy('id','desc')->first();
                        if(isset($check1))
                        {   
                            if($check1->variable==''){$check1->variable=1;}
                            $sf->variable=$check1->variable+1;
                        }                  
                        $sf->vendor_id = $request->vendor_id;
                        $sf->product_id = $request->product_id;
                        $sf->quantity = $request->qty;
                        $sf->size = $request->size;
                        $sf->color = $request->color;
                        $sf->sales_rate = $request->total_price;
                        $sf->purchase_rate = $request->purchase_rate;
                        $sf->save();
                    }
                }
            return [
                'category' => $categoryId
            ];
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Size::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'vendor_id' => 'required',
        ]);

        $data = $request->only('name','vendor_id','is_active');
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
        $category = Size::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }

  
}
