<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TmpPurchase;
use App\PurchasePayment;
use App\Stock;
use App\StockFetch;
use App\Purchase;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        // return Category::latest()->paginate(10);
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        $query = Size::dataTableQuery($column, $orderBy, $searchValue);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function getpurchaseData(Request $request,$id)
    {
        // return Category::latest()->paginate(10);
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        $query = Purchase::where('purchases.vendor_id',$id)->join('purchase_vendors','purchases.p_vendor_id','=','purchase_vendors.id')->groupBy('purchases.invoice_no')->orderBy('purchases.id','desc')->select(['purchases.*','purchase_vendors.name as p_v_name']);
        $data = $query->paginate($length);
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $vv=PurchasePayment::where('vendor_id',$id)->where('invoice_no',$key->invoice_no)->get();
                // print_r($vv);
                $calamt =0;
                $calother =0;
                $od='';
                $pendingg=0;
                foreach($vv as $price)
                { 
                   // print_r($price);
                    $calamt +=$price->total_amount;
                    $calother +=$price->other_charges;
                    $pendingg =$price->pending;
                }
                $key->srno=$i;  
                $od=$key->created_at; 
                $key->order_date=date('d-m-Y H:i A',strtotime($od));   
                $key->invoice_total=$calamt-$pendingg-$calother; 
                if($pendingg==0)
                {
                    $key->payment_status='NA';
                }
                else
                {
                    $key->payment_status='Pending';
                }
                $key->pendingmt=$pendingg;
                $key->other=$calother; 
                $dt[]=$key;
                $i++;
            }
        }
            $dt=$data;

        return new DataTableCollectionResource($dt);
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
           'vendor_id'=> 'required', 'invoice_no'=> 'required','invoice_date'=> 'required','product_id'=> 'required',
            'size'=> 'required','color'=> 'required','stock'=> 'required','qty'=> 'required', 'purchase_rate'=> 'required',
            'sales_rate'=> 'required','pg_charges'=> 'required','total_price'=> 'required','gst'=> 'required',
            'sgst'=> 'required','tgst'=> 'required','cgst'=> 'required','igst'=> 'required','taxable_rate'=> 'required','total_amount'=>'required'
        ]);  
        $data = $request->only('vendor_id','invoice_no','invoice_date','product_id','size','color', 'stock','qty','purchase_rate','sales_rate','pg_charges','total_price','gst','sgst','tgst','cgst','igst','taxable_rate','total_amount');
        $categoryId = TmpPurchase::create($data)->id;
        return [
            'category' => $categoryId
        ];
    }

    public function AddPurchase(Request $request)
    {     
        $this->validate($request, [
           'vendor_id'=> 'required', 'p_vendor_id'=> 'required','invoice_no'=> 'required','invoice_date'=> 'required','product_id'=> 'required',
            'size'=> 'required','color'=> 'required','stock'=> 'required','qty'=> 'required', 'purchase_rate'=> 'required',
            'sales_rate'=> 'required','pg_charges'=> 'required','total_price'=> 'required','gst'=> 'required',
            'sgst'=> 'required','tgst'=> 'required','cgst'=> 'required','igst'=> 'required','taxable_rate'=> 'required','total_amount'=>'required'
        ]);

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
        // echo $ptaxval+ $ptgst;
        //exit;




        $data = $request->only('p_total_amount','p_taxable_rate','p_total_taxable','p_tgst','p_sgst','p_cgst','vendor_id','invoice_no','p_vendor_id','invoice_date','product_id','size','color', 'stock','qty','purchase_rate','sales_rate','pg_charges','total_price','gst','sgst','tgst','cgst','igst','taxable_rate','total_amount');
        $categoryId = TmpPurchase::create($data)->id;
        return [
            'category' => $categoryId
        ];
    }

    public function resettmppurchase(Request $request)
    {  
        $this->validate($request, ['vendor_id'=> 'required', 'p_vendor_id'=> 'required','invoice_no'=> 'required',]);
        $category = TmpPurchase::where('vendor_id',$request->vendor_id)->where('p_vendor_id',$request->p_vendor_id)->where('invoice_no',$request->invoice_no)->get();
            foreach($category as $key)
            {
                $categoryx = TmpPurchase::findOrFail($key->id);
                $categoryx->delete();
            }
            // $category->delete();
            // // delete the category
            return [
                'message' => 'Purchase List Deleted !'
            ];
       
    }

    public function deletetmppurchase($id)
    {  
        $category = TmpPurchase::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Purchase Deleted !'
        ];
       
    } 

    public function addpurchasedata(Request $request)
    {
        //exit;        
        $gettmp=TmpPurchase::where('vendor_id',$request->vendor_id)->where('p_vendor_id',$request->p_vendor_id)->where('invoice_no',$request->invoice_no)->where('is_active','1')->get();
        $cnt= count($gettmp);
        //echo $cnt;exit;
        if($cnt==0)
        {
           return ["msg"=>"Please Check Vendor and invoice No."];
        }
        else
        {
            foreach($gettmp as $key)
            {
                $id[]=$key->id;
            }
            // $request->final_total;
            $request['tmp_purchase_id']=implode(',',$id);
            //echo strlen(($request['tmp_purchase_id']));exit;
            $request['final_amount']=$request->final_total;
            $request['taxable_rate']=$request->total_taxable;
            $request['total_gst']=round($request->total_tgst,2);
            $request['pending_amount']=$request->payment_pending;
            $request['pending']=$request->pending;
            $request['paid_amount']=$request->payment_paid;
            //print_r($request->all());exit;
            $this->validate($request, [ 
                'vendor_id'=>'required',  
                'p_vendor_id'=>'required',  
                'invoice_no'=>'required',
                'invoice_date'=>'required', 
                'tmp_purchase_id'=>'required', 
                'other_charges'=>'required',
                'final_amount'=>'required', 
                'taxable_rate'=>'required',
                'total_gst'=>'required', 
                'paid_amount'=>'required',
                'pending_amount'=>'required',]);
            $data = $request->only('pending','vendor_id','p_vendor_id','invoice_no','invoice_date','tmp_purchase_id','other_charges','final_amount', 'taxable_rate','total_gst','paid_amount','pending_amount');
            $purid = Purchase::create($data)->id;
            $request['purchase_id']=$purid;
            $request['paid_amount']=$request->payment_paid;
            $request['status']=$request->payment_status;
            $request['remaining_amount']=$request->payment_pending;
            $request['tansaction_id']=$request->payment_trans;
            $request['payment_mode']=$request->payment_mode;
            $request['total_amount']=$request->final_total;;
            $request['pending']=$request->pending;
            $request['other_charges']=$request->other_charges;;
            $this->validate($request, [ 
                'vendor_id'=>'required',  
                'p_vendor_id'=>'required',  
                'invoice_no'=>'required',
                'invoice_date'=>'required', 
                'purchase_id'=>'required', 
                'payment_date'=>'required',
                'status'=>'required', 
                'paid_amount'=>'required',
                'remaining_amount'=>'required', 
                'tansaction_id'=>'required',
                'payment_mode'=>'required',
                'total_amount'=>'required']);
            $data2 = $request->only('pending','vendor_id','other_charges','p_vendor_id','invoice_no','invoice_date','purchase_id','payment_date','status','total_amount','paid_amount','remaining_amount','tansaction_id','payment_mode');
            $purid = PurchasePayment::create($data2)->id;
            foreach($gettmp as $key)
            {  
                $key->id=trim($key->id);
                $key->qty=trim($key->qty);
                $key->gst=trim($key->gst);
                $key->cgst=trim($key->cgst);
                $key->sgst=trim($key->sgst);
                $key->igst=trim($key->igst);
                $key->tgst=trim($key->tgst);
                $key->purchase_rate=trim($key->purchase_rate);
                $key->taxable_rate=trim($key->taxable_rate);
                $dataxx=array();
                $dsfu=array();
                $productxx = TmpPurchase::findOrFail($key->id);
                $stock=Stock::whereRaw('vendor_id='.$request->vendor_id)->whereRaw('product_id='.$key->product_id)->whereRaw('size='.$key->size)->whereRaw('color='.$key->color)->whereRaw('price='.$key->total_price)->whereRaw('purchase_rate='.$key->purchase_rate)->first();
                if(isset($stock))
                {
                    $productn = Stock::findOrFail($stock->id);
                    $dataxx['tmp_purchase_id'] = $key->id;
                    $dataxx['quantity'] = $stock->quantity + $key->qty;
                    $dataxx['old_quantity'] = $stock->quantity;
                    $dataxx['gst'] = $key->gst;
                    $dataxx['cgst'] = $key->cgst;
                    $dataxx['sgst'] = $key->sgst;
                    $dataxx['igst'] = $key->igst;
                    $dataxx['tgst'] = $key->tgst;
                    $dataxx['taxable_rate'] = $key->taxable_rate;
                    $dataxx['purchase_rate'] = $key->purchase_rate;
                    $productn->update($dataxx);
                }
                else
                {   
                    $product = new Stock();
                    $product->vendor_id = $request->vendor_id;
                    $product->tmp_purchase_id = $key->id;
                    $product->product_id = $key->product_id;
                    $product->quantity = $key->qty;
                    $product->size = $key->size;
                    $product->color = $key->color;
                    $product->price = $key->total_price;
                    $product->gst = $key->gst;
                    $product->cgst = $key->cgst;
                    $product->sgst = $key->sgst;
                    $product->igst = $key->igst;
                    $product->tgst = $key->tgst;
                    $product->taxable_rate = $key->taxable_rate;
                    $product->purchase_rate = $key->purchase_rate;
                    $product->save();
                }
                //stockfetch
                $stockxn=StockFetch::whereRaw('vendor_id='.$request->vendor_id)->whereRaw('product_id='.$key->product_id)->whereRaw('size='.$key->size)->whereRaw('color='.$key->color)->whereRaw('sales_rate='.$key->total_price)->whereRaw('purchase_rate='.$key->purchase_rate)->first();
                if(isset($stockxn))
                {
                    $sfu = StockFetch::findOrFail($stockxn->id);
                    $dsfu['quantity'] = $stockxn->quantity + $key->qty;
                    $sfu->update($dsfu);
                }
                else
                {   
                    $scon=$check1=StockFetch::whereRaw('vendor_id='.$request->vendor_id)->whereRaw('product_id='.$key->product_id)->whereRaw('quantity='.'0')->whereRaw('size='.'0')->whereRaw('color='.'0')->orderBy('id','desc')->first();
                    if(isset($scon))
                    {
                        $sfun = StockFetch::findOrFail($scon->id);
                        $dsfu['quantity'] = $scon->quantity + $key->qty;
                        $dsfu['size'] = $key->size;
                        $dsfu['color'] =  $key->color;
                        $dsfu['sales_rate'] = $key->total_price;
                        $dsfu['purchase_rate'] = $key->purchase_rate;
                        $sfun->update($dsfu);
                    }
                    else
                    {
                        $sf = new StockFetch();
                        $check1=StockFetch::whereRaw('vendor_id='.$request->vendor_id)->whereRaw('product_id='.$key->product_id)->whereRaw('size='.$key->size)->whereRaw('color='.$key->color)->orderBy('id','desc')->first();
                        if(isset($check1))
                        {   
                            if($check1->variable==''){$check1->variable=1;}
                            $sf->variable=$check1->variable+1;
                        }                  
                        $sf->vendor_id = $request->vendor_id;
                        $sf->product_id = $key->product_id;
                        $sf->quantity = $key->qty;
                        $sf->size = $key->size;
                        $sf->color = $key->color;
                        $sf->sales_rate = $key->total_price;
                        $sf->purchase_rate = $key->purchase_rate;
                        $sf->save();
                    }
                }
                $dataxxx['is_active']=0;
                $productxx->update($dataxxx);
            }
            
            return [
                'msg'=>'Added Successfully',
            ];
        }

    }
    
    public function loadtmppurchase($idx)
    {
        $id=$_GET['ad'];
        $inv=$_GET['inv'];
        
        //

        return $categoryId = TmpPurchase::join('products', 'tmp_purchases.product_id','=', 'products.id')->join('colors', 'tmp_purchases.color','=', 'colors.id')->join('sizes', 'tmp_purchases.size','=', 'sizes.id')
                             ->where('tmp_purchases.vendor_id',$id)->where('tmp_purchases.p_vendor_id',$idx)->where('tmp_purchases.invoice_no',$inv)
                             ->select('tmp_purchases.*','products.name','products.mrp','products.hsn','sizes.name as size_name','colors.name as color_name')->get();
    }

    public function getpendingamount($idx)
    {
        $id=$_GET['ad'];
        $inv=$_GET['inv'];
        $purchase=Purchase::where('p_vendor_id',$idx)->where('vendor_id',$id)->where('invoice_no',$inv)->orderBy('id','desc')->first();
        // print_r($purchase);
        if(isset($purchase)>0)
        {
            return $purchase->pending_amount;
        }
        else
        {
            return 0;
        }
    } 

    public function getotheramount($idx)
    {
        $id=$_GET['ad'];
        $inv=$_GET['inv'];
        $purchase=PurchasePayment::where('p_vendor_id',$idx)->where('vendor_id',$id)->where('invoice_no',$inv)->orderBy('id','desc')->get();
        // print_r($purchase);
        if(count($purchase)>0)
        {  
           
            $calother =0;
            foreach($purchase as $price)
            { 
                $calother +=$price->other_charges;
            }
            return $calother;
        }
        else
        {
            return 0;
        }
    }

    public function getpurchasepayment($idx)
    {
        $id=$_GET['ad'];
        $inv=$_GET['inv'];
        $purchase=PurchasePayment::where('p_vendor_id',$idx)->where('vendor_id',$id)->where('invoice_no',$inv)->orderBy('id','desc')->get();
        // print_r($purchase);
        if(isset($purchase)>0)
        {
            return $purchase;
        }
        else
        {
            return 0;
        }
    }

    public function AddPurchasePayment(Request $request)
    {
        // print_r($request->all());exit;
        $this->validate($request, [ 
            'vendor_id'=>'required',  
            'p_vendor_id'=>'required',  
            'invoice_no'=>'required',
            'invoice_date'=>'required', 
            'purchase_id'=>'required', 
            'payment_date'=>'required',
            'payment_status'=>'required', 
            'payment_paid'=>'required',
            'pending'=>'required', 
            'payment_trans'=>'required',
            'payment_mode'=>'required',
            'total_amount'=>'required']);
            $product = new PurchasePayment();
            $product->pending=$request->pending;
            $product->vendor_id=$request->vendor_id;
            $product->other_charges=$request->other_charges;
            $product->p_vendor_id=$request->p_vendor_id;
            $product->invoice_date=$request->invoice_date;
            $product->invoice_no=$request->invoice_no;
            $product->purchase_id=$request->purchase_id;
            $product->payment_date=$request->payment_date;
            $product->status=$request->payment_status;
            $product->paid_amount=$request->payment_paid;
            $product->total_amount=$request->total_amount;
            $product->remaining_amount=$request->pending;
            $product->payment_mode=$request->payment_mode;
            $product->tansaction_id=$request->payment_trans;
            $product->save();
            
            $purchase=Purchase::FindOrFail($request->purchase_id);
            $datax['pending_amount'] = $request->pending;
            $purchase->update($datax);
        return [
            'message' => 'Updated Successfully'
        ];
        // $data2 = $request->only('pending','vendor_id','other_charges','p_vendor_id','invoice_no','invoice_date','purchase_id','payment_date','status','total_amount','paid_amount','remaining_amount','tansaction_id','payment_mode');
        // $purid = PurchasePayment::create($data2)->id;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       return $query = Purchase::where('vendor_id',$id)->get();
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
        $category = Purchase::findOrFail($id);
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
        $category = Purchase::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }

  
}
