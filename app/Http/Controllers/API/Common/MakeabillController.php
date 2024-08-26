<?php
namespace App\Http\Controllers\API\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StockManagement;
use App\Makeabill;
use App\PurchasePayment;
use App\Stock;
use App\StockFetch;
use App\Purchase;
use App\Category;
use App\SubCategory;
use App\Order;
use App\Product;
use App\Vendor;
use App\User;
use App\ProductImage;
use App\UserAddress;
use App\OrderAddress;
use App\ServiceArea;
use App\OrderStatus;
use App\VendorStatus;
use App\DeliveryStatus;
use App\OrderProduct;
use App\DeliveryCharges;
use App\OrderPayment;
use App\PaymentMethod;
use App\OrderPaymentMethod;
use App\OrderPaymentMethodResponse;
use App\EditOrderProduct;
use App\SaleReport;
use App\PgSetting;
use App\RazorpaySetting;
use Razorpay\Api\Api;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class MakeabillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getfetchdata($id,$userid)
    {
        $query = Makeabill::where('makeabills.is_active',1)->where('makeabills.deleted_at',NULL)->where('makeabills.vendor_id',$id)->where('makeabills.user_id',$userid)->where('makeabills.is_active',1)->join('products','products.id','=','makeabills.product_id')->join('colors','colors.id','=','makeabills.color')->join('sizes','sizes.id','=','makeabills.size')->orderBy('makeabills.id','desc')->select(['makeabills.*','products.name as p_v_name','products.hsn as prohsn','products.hsn','products.category_id','products.sub_category_id','products.sub_sub_category_id','products.mrp','colors.name as color_name','sizes.name as size_name']);
        $data = $query->get();
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $proimg=ProductImage::where('product_id',$key->product_id)->orderBy('id','desc')->limit(1)->first();
                // print_r($proimg);
                // exit;
                if(isset($proimg))
                {
                 //$key->image=$proimg->name;
                }
                $cata=Category::where('id',$key->category_id)->first();
                $key->cata_name=$cata->name;
                $scata=Category::where('id',$key->sub_category_id)->first();
                $key->sub_cata_name=$scata->name;
                $sscata=SubCategory::where('id',$key->sub_sub_category_id)->first();
                $key->sub_sub_cata_name=$sscata->name;
                $key->srno=$i;  
                $dt[]=$key;
                $i++;
            }
        }
            $dt=$data;

        return $dt;
    }

    public function resetmakeabill(Request $request)
    {  
        $this->validate($request, [
            'user_id' => 'required',
            'vendor_id' => 'required',
        ]);
        $mb=Makeabill::where('user_id',$request->user_id)->where('vendor_id',$request->vendor_id)->get();
        foreach($mb as $key)
        {
            $category = Makeabill::findOrFail($key->id);
            $category->delete();
        }
        return 'ok';
    }

    public function addOrder(Request $request)
    {  
         
        $this->validate($request, [
            'user_id' => 'required',
            'vendor_id' => 'required',
            'payment_pending' => 'required',
            'payment_paid' => 'required',
            'final_total' => 'required',
            'transaction_id'=> 'required',
            'other_charges' => 'required',
            'payment_mode' => 'required',
            'total_tgst' => 'required',
            'total_rtgst' => 'required',
            'total_taxable' => 'required',
            
        ]);
        $rtgst=round($request->total_rtgst,2);
        // exit;
        $orderfetch=Order::where('vendor_id', $request->vendor_id)->orderBy('id','desc')->first();
        if(isset($orderfetch))
        {
            $innv=$orderfetch->invoice_no;
            if($innv!='')
            {
            $exp=explode('_',$innv);
            $expp=$exp[1]+1;
            }else
            {
                $expp=1;
            }
        }
        else
        {
            $expp=1;
        }
        $pendingOrderStatus = OrderStatus::where(['code'=>'delivered'])->pluck('id')->first();
        $pendingvStatus = VendorStatus::where(['code'=>'delivered'])->pluck('id')->first();
        $orderData = [
            'user_id' =>  $request->user_id,
            'invoice_no' => $request->vendor_id.'_'.$expp,
            'vendor_id' => $request->vendor_id,
            'amount' => $request->final_total,
            'delivery_charges' => $request->other_charges,
            'delivery_type' => 'Pickup',
            'date' => date('Y-m-d'),
            'payment_status'=>1,
            'order_status_id' => $pendingOrderStatus,
            'vendor_status_id' => $pendingvStatus,
            'status_updated' => date('Y-m-d H:i:s'),
            'itemtotal'=>$request->final_total,
            'total_rtgst'=>round($request->total_rtgst,2),
            'scheduled_delivery_date' => date('Y-m-d H:i:s', strtotime('120 minutes')),
            'makeabill'=>1
        ];
        $order = Order::create($orderData); 
        $ordid= $order->id;
        $usr=User::where('id',$request->vendor_id)->first();
        $address = Vendor::findOrFail($usr->admin_id); 
        $userdetail =  User::findOrFail($request->user_id); 
        OrderAddress::create([
           'order_id' => $ordid,
           'mobile' => $userdetail->mobile,
           'name' => $userdetail->name,
           'address' => $address->address,
           'title' => 'Pickup',
           'pincode' => $userdetail->pincode,
           'state' => $userdetail->state,
           'district' => $userdetail->district,
           'lat' => $address->latitude,
           'long' => $address->longitude,
        ]);
        ////////////////////////////////////////////
        $orderProducts =[];
        $query = Makeabill::where('makeabills.is_active',1)->where('makeabills.vendor_id',$request->vendor_id)->where('makeabills.user_id',$request->user_id)->get();
       // print_r($query);exit;
       $totalccc=0;
        foreach ($query as  $item)
        {
              $datst=[];
              $proo=Stock::where('vendor_id',$request->vendor_id)->where('product_id',$item->product_id)->where('size',$item->size)->where('color',$item->color)->whereRaw('price='.$item->total_price)->whereRaw('purchase_rate='.$item->purchase_rate)->first();
              $prooid=StockFetch::where('vendor_id',$request->vendor_id)->where('product_id',$item->product_id)->where('size',$item->size)->where('color',$item->color)->whereRaw('sales_rate='.$item->total_price)->whereRaw('quantity>='.$item->qty)->whereRaw('purchase_rate='.$item->purchase_rate)->first();
               if(isset($proo) && isset($prooid))
               {
                  $sqty=$proo->sold_qty;
                  $sidc=Stock::findOrFail($proo->id);
                  $sidc->sold_qty=$sqty+$item->qty;
                  $sidc->update();
    
                  $sqtys=$prooid->quantity;
                  $sidcs=StockFetch::findOrFail($prooid->id);
                  $sidcs->quantity=$sqtys-$item->qty;
                  $sidcs->update();
                  $pro=Product::where('id',$item->product_id)->first();
                  $orderProduct =[
                        'order_id' => $ordid,
                        'product_id' => $item->product_id,
                        'name' => $pro->name,
                        'price' => $item->total_price,
                        'hsn' => $pro->hsn,
                        'mrp' => $pro->mrp,
                        'qty' => $item->qty,
                        'size'=>$item->size,
                        'color' =>$item->color,
                        'gst'=>$item->gst,
                        'tgst' =>$item->tgst,
                        'cgst'=>$item->cgst,
                        'sgst' =>$item->sgst,
                        'igst'=>$item->igst,
                        'taxable_rate' =>$item->taxable_rate,
                        'purchase_rate' =>$item->purchase_rate,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    
                  $totalccc +=$item->total_price*$item->qty;
                } 
                else
                {
                     $pro=Product::where('id',$item->product_id)->first();
                     $orderProduct =[
                        'order_id' => $ordid,
                        'product_id' => $item->product_id,
                        'name' => $pro->name,
                        'price' => 0,
                        'mrp' => 0,
                        'hsn' => 0,
                        'qty' => 0,
                        'size'=>0,
                        'color' =>0,
                        'gst'=>0,
                        'tgst' =>0,
                        'cgst'=>0,
                        'sgst' =>0,
                        'igst'=>0,
                        'taxable_rate' =>0,
                        'purchase_rate' =>0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                $orderProducts[] = $orderProduct;
                $vendorProduct = Makeabill::findOrFail($item->id);
                $vendorProduct->delete();
        }
        
        OrderProduct::insert($orderProducts); 
        $totalamtxx=round(($totalccc+$request->other_charges)-$rtgst);
        $orderup = Order::findOrFail($ordid);
        $orderup->amount=$totalamtxx;
        $orderup->update();
        // inserting order items in db
        $orderPaymentId = OrderPayment::create([
            'order_id'=>$ordid,
            'amount' => $totalamtxx,
            'status'=>1,
        ]);
        $poid=$orderPaymentId->id;
        $paymentMethod = PaymentMethod::findOrFail(1);
        $opmData =[
            'order_payment_id' =>$poid,
            'payment_method_id' =>$paymentMethod->id,
            'amount' =>$totalamtxx,
            'status'=>1,
        ];
        $orderPaymentMethodId =  OrderPaymentMethod::create($opmData)->id;
        $q_data['status']= 1;
        $q_data['payment_method_id'] =1;
        $q_data['transaction_uid']= $request->transaction_id;
        $q_data['order_payment_method_id']= $orderPaymentMethodId;
        $q_data['payment_status_id']= 1;
        $q_data['amount']= $totalamtxx;
        $q_data['payment_mode']= $request->payment_mode;
        $q_data['response_msg']= 'SUCCESS';
        $opmr_status = OrderPaymentMethodResponse::create($q_data);
        $this->makesalesreport($ordid);
        
        return [
            'order_id' => $order,
            'payment' => [
                'payment_method' => $paymentMethod,
             ],
             'status'=>200,
            'message' => 'Order created Successfully'
        ];
    }

    public function AddMakeabill(Request $request)
    {     
        $request->merge(['stock_id' => $request->stock_select]); 
        $this->validate($request, [
            'user_id'=> 'required','vendor_id'=> 'required', 'product_id'=> 'required',
            'size'=> 'required','color'=> 'required','stock_id'=> 'required','qty'=> 'required', 'purchase_rate'=> 'required',
            'sales_rate'=> 'required','pg_charges'=> 'required','total_price'=> 'required','gst'=> 'required',
            'sgst'=> 'required','tgst'=> 'required','cgst'=> 'required','igst'=> 'required','taxable_rate'=> 'required','total_amount'=>'required'
        ]);  

        $sql=Makeabill::where('user_id',$request->user_id)->where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('purchase_rate',$request->purchase_rate)->where('sales_rate',$request->sales_rate)->first();
        if(isset($sql))
        {   
            $qty=$sql->qty + $request->qty;  
            $sfrt=StockFetch::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('purchase_rate',$request->purchase_rate)->where('sales_rate',$request->sales_rate)->first();
            $qqty=$sfrt->quantity;
            if($qqty<$qty)
            {
                return [
                    'resid' => 202
                ];
            }
            else
            {
                $sql->qty=$qty;
                $sql->total_amount=round(($sql->sales_rate*$qty),2);
                $sql->update();
                return [
                    'resid' => 200
                ];
            }
           
        }
        else
        {
            $data = $request->only('user_id','vendor_id','product_id','size','color', 'stock_id','qty','purchase_rate','sales_rate','pg_charges','total_price','gst','sgst','tgst','cgst','igst','taxable_rate','total_amount');
            $categoryId = Makeabill::create($data)->id;
        }
        $dataxx=[];

            return [
                'resid' => 200
            ];
        
        
    }

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

    public function deleteorderproduct($id)
    {
        $category = OrderProduct::findOrFail($id);    
        //print_r($category->product_id);exit;
        $category->delete();
              $proo1=Stock::where('product_id',$category->product_id)->where('size',$category->size)->where('color',$category->color)->whereRaw('price='.$category->price)->whereRaw('purchase_rate='.$category->purchase_rate)->first();
              $sqty1=$proo1->sold_qty;
              $sidc1=Stock::findOrFail($proo1->id);
              $sidc1->sold_qty=$sqty1-$category->qty;
              $sidc1->update();

              $prooid1=StockFetch::where('product_id',$category->product_id)->where('size',$category->size)->where('color',$category->color)->whereRaw('sales_rate='.$category->price)->whereRaw('purchase_rate='.$category->purchase_rate)->first();
              $sqtys1=$prooid1->quantity;
              $sidcs1=StockFetch::findOrFail($prooid1->id);
              $sidcs1->quantity=$sqtys1+$category->qty;
              $sidcs1->update();
        $orderid=$category->order_id;
        $order = Order::findOrFail($orderid);
        $orderpro = OrderProduct :: where('order_id',$orderid)->get();
     // echo  count($orderpro);
        $amountt=0;
        foreach($orderpro as $key)
        {
              $amountt +=$key->price*$key->qty;
             
        }
        $amountt=round($amountt);
        $order->is_edit=1;
        $order->before_edit_amount=$order->amount;
        $order->amount=$amountt;
        $order->itemtotal=$amountt;
        $order->after_edit_amount= $amountt;
        $order->update();
        $orderpayment=OrderPayment::where('order_id',$orderid)->first();
        $orderpayment->amount=$amountt;
        $orderpayment->update();
        $orderpaymentmethod=OrderPaymentMethod::where('order_payment_id',$orderpayment->id)->first();
        $orderpaymentmethod->amount=$amountt;
        $orderpaymentmethod->update();
        $orderPaymentMethodResponse=OrderPaymentMethodResponse::where('order_payment_method_id',$orderpaymentmethod->id)->first();
        $orderPaymentMethodResponse->amount=$amountt;
        $orderPaymentMethodResponse->update();
         
        //print_r($product);exit;

    }
   
    public function destroy($id)
    {
        $category = Makeabill::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Item Deleted !'
        ];
    }

    public function orderedit(Request $request,$id)
    {  
        $category = SaleReport::where('order_id',$id);
        $category->delete();
        $order=Order::findOrFail($id);
        $oldbill=$order->amount;
        $query = Makeabill::where('makeabills.is_active',1)->where('makeabills.vendor_id',$request->vendor_id)->where('makeabills.user_id',$request->user_id)->get();
       // print_r($query);exit;
       if(count($query)>0){
        foreach ($query as  $item)
        {
              $datst=[];
              $proo=Stock::where('product_id',$item->product_id)->where('size',$item->size)->where('color',$item->color)->whereRaw('price='.$item->total_price)->whereRaw('purchase_rate='.$item->purchase_rate)->first();
              $prooid=StockFetch::where('product_id',$item->product_id)->where('size',$item->size)->where('color',$item->color)->whereRaw('quantity>='.$item->qty)->whereRaw('sales_rate='.$item->total_price)->whereRaw('purchase_rate='.$item->purchase_rate)->first();
              if(isset($proo) && isset($prooid))
              {
              $sqty=$proo->sold_qty;
              $sidc=Stock::findOrFail($proo->id);
              $sidc->sold_qty=$sqty+$item->qty;
              $sidc->update();

              $sqtys=$prooid->quantity;
              $sidcs=StockFetch::findOrFail($prooid->id);
              $sidcs->quantity=$sqtys-$item->qty;
              $sidcs->update();
              $pro=Product::where('id',$item->product_id)->first();
              $orderProduct =[
                    'order_id' => $id,
                    'product_id' => $item->product_id,
                    'name' => $pro->name,
                    'price' => $item->total_price,
                    'hsn' => $pro->hsn,
                    'mrp' => $pro->mrp,
                    'qty' => $item->qty,
                    'size'=> $item->size,
                    'color' =>$item->color,
                    'gst'=>$item->gst,
                    'tgst' =>$item->tgst,
                    'cgst'=>$item->cgst,
                    'sgst' =>$item->sgst,
                    'igst'=>$item->igst,
                    // 'total_rtgst'=>$item->total_rtgst,
                    'taxable_rate' =>$item->taxable_rate,
                     'purchase_rate' =>$item->purchase_rate,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
              } 
              else
              {
                     $pro=Product::where('id',$item->product_id)->first();
                     $orderProduct =[
                        'order_id' => $ordid,
                        'product_id' => $item->product_id,
                        'name' => $pro->name,
                        'price' => 0,
                        'mrp' => 0,
                         'hsn' => 0,
                        'qty' => 0,
                        'size'=>0,
                        'color' =>0,
                        'gst'=>0,
                        'tgst' =>0,
                        'cgst'=>0,
                        'sgst' =>0,
                        'igst'=>0,
                        // 'total_rtgst'=>0,
                        'taxable_rate' =>0,
                        'purchase_rate' =>0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
              $orderProducts[] = $orderProduct;
              $vendorProduct = Makeabill::findOrFail($item->id);
              $vendorProduct->delete();
        }
        OrderProduct::insert($orderProducts);
        EditOrderProduct::insert($orderProducts);
       }
        $order->total_rtgst=round($request->total_rtgst,2);
        $order->amount=$request->final_total;
        $order->itemtotal=$request->final_total;
        $order->before_edit_amount=$oldbill;
        $order->after_edit_amount=$request->final_total;
        $order->is_edit=1;
        $order->delivery_charges = $request->other_charges;
        if($order->update())
        {
            $orderpayment=OrderPayment::where('order_id',$id)->first();
            $orderpayment->amount=$request->final_total;
            $orderpayment->update();
            $orderpaymentmethod=OrderPaymentMethod::where('order_payment_id',$orderpayment->id)->first();
            $orderpaymentmethod->amount=$request->final_total;
            $orderpaymentmethod->update();
            $orderPaymentMethodResponse=OrderPaymentMethodResponse::where('order_payment_method_id',$orderpaymentmethod->id)->first();
            $orderPaymentMethodResponse->amount=$request->final_total;
            $orderPaymentMethodResponse->payment_mode=$request->payment_mode;
            $orderPaymentMethodResponse->transaction_uid=$request->transaction_id;
            $orderPaymentMethodResponse->update();
            $this->makesalesreport($id);
        return [
            'order_id' => $id,
             'status'=>200,
             'message' => 'Order created Successfully'
        ];
       }
    } 

    public function makesalesreport($vendor_id)
    {  
        $order=Order::where('id',$vendor_id)->get();
        $pur_tax=0;
        $pur_val=0;
        $pur_gst=0;
        $taxable=0;
        $sales_sgst=0;
        $sales_cgst=0;
        $sales_igst=0;
        $sales_gst=0;
        $total_amount=0;
        $other=0;
        $finalamt=0;

        $fma=0;
        //gst
        $gst0=0;
        $cgst0=0;
        $sgst0=0;
        $tax0=0; 
        $tot0=0;

        $gst0_25=0;
        $cgst0_25=0;
        $sgst0_25=0;
        $tax0_25=0; 
        $gst0_25=0;
        
        $gst1=0;
        $cgst1=0;
        $sgst1=0;
        $tax1=0; 
        $tot1=0;

        $gst2=0;
        $cgst2=0;
        $sgst2=0;
        $tax2=0; 
        $tot2=0;

        $gst3=0;
        $cgst3=0;
        $sgst3=0;
        $tax3=0; 
        $tot3=0;
        
        $gst5=0;
        $cgst5=0;
        $sgst5=0;
        $tax5=0; 
        $tot5=0;

        $gst6=0;
        $cgst6=0;
        $sgst6=0;
        $tax6=0; 
        $tot6=0;

        $gst12=0;
        $cgst12=0;
        $sgst12=0;
        $tax12=0; 
        $tot12=0;

        $gst18=0;
        $cgst18=0;
        $sgst18=0;
        $tax18=0; 
        $tot18=0;

        $gst28=0;
        $cgst28=0;
        $sgst28=0;
        $tax28=0; 
        $tot28=0;

        //charges
        $pgdata=0;
        $pgactual=0;
        $pgdeduct=0;
        $remain=0;
        $pgdeldeduct=0;
        $tosettle=0;
        $vendrpro=0;
        $bankchar=0;
        $compchar=0;
        $pratet=0;
        $ptaxt=0;
        $pur_gstt =0;
        $ccc=0;
        foreach($order as $key)
        {  
           $other =$key->delivery_charges;
           $fma=$key->amount;
           if($fma!=0)
           {
           $opp=OrderProduct::where('order_id',$key->id)->get();
           foreach ($opp as $key2)
           {
               $gstp=$key2->gst;
               $ogstp=1+($gstp/100);
               $prate =$key2->purchase_rate;
               $ptax =($prate/$ogstp);
               $pur_gst=($ptax*($gstp/100));

               $pratet +=($key2->purchase_rate*$key2->qty);
               $ptaxt  +=($ptax*$key2->qty);
               $pur_gstt +=($pur_gst*$key2->qty);

               $taxable +=($key2->taxable_rate*$key2->qty);
               $sales_sgst +=($key2->sgst*$key2->qty);
               $sales_cgst +=($key2->cgst*$key2->qty);
               $sales_igst +=($key2->igst*$key2->qty);
               $sales_gst +=($key2->tgst*$key2->qty);

               if($gstp==0)
               {
                    $gst0 +=($key2->tgst*$key2->qty);
                    $cgst0 +=($key2->cgst*$key2->qty);
                    $sgst0 +=($key2->sgst*$key2->qty);
                    $tax0 +=($key2->taxable_rate*$key2->qty); 
                    $tot0 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp=='0.25')
               {
                    $gst0_25 +=($key2->tgst*$key2->qty);
                    $cgst0_25 +=($key2->cgst*$key2->qty);
                    $sgst0_25 +=($key2->sgst*$key2->qty);
                    $tax0_25 +=($key2->taxable_rate*$key2->qty); 
                    $tot0_25 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==1)
               {
                    $gst1 +=($key2->tgst*$key2->qty);
                    $cgst1 +=($key2->cgst*$key2->qty);
                    $sgst1 +=($key2->sgst*$key2->qty);
                    $tax1 +=($key2->taxable_rate*$key2->qty); 
                    $tot1 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==2)
               {
                    $gst2 +=($key2->tgst*$key2->qty);
                    $cgst2 +=($key2->cgst*$key2->qty);
                    $sgst2 +=($key2->sgst*$key2->qty);
                    $tax2 +=($key2->taxable_rate*$key2->qty); 
                    $tot2 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==3)
               {
                    $gst3 +=($key2->tgst*$key2->qty);
                    $cgst3 +=($key2->cgst*$key2->qty);
                    $sgst3 +=($key2->sgst*$key2->qty);
                    $tax3 +=($key2->taxable_rate*$key2->qty); 
                    $tot3 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==5)
               {
                    $gst5 +=($key2->tgst*$key2->qty);
                    $cgst5 +=($key2->cgst*$key2->qty);
                    $sgst5 +=($key2->sgst*$key2->qty);
                    $tax5 +=($key2->taxable_rate*$key2->qty); 
                    $tot5 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==6)
               {
                    $gst6 +=($key2->tgst*$key2->qty);
                    $cgst6 +=($key2->cgst*$key2->qty);
                    $sgst6+=($key2->sgst*$key2->qty);
                    $tax6 +=($key2->taxable_rate*$key2->qty); 
                    $tot6 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==12)
               {
                    $gst12 +=($key2->tgst*$key2->qty);
                    $cgst12 +=($key2->cgst*$key2->qty);
                    $sgst12 +=($key2->sgst*$key2->qty);
                    $tax12 +=($key2->taxable_rate*$key2->qty); 
                    $tot12 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==18)
               {
                    $gst18 +=($key2->tgst*$key2->qty);
                    $cgst18 +=($key2->cgst*$key2->qty);
                    $sgst18 +=($key2->sgst*$key2->qty);
                    $tax18 +=($key2->taxable_rate*$key2->qty); 
                    $tot18 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
               if($gstp==28)
               {
                    $gst28 +=($key2->tgst*$key2->qty);
                    $cgst28 +=($key2->cgst*$key2->qty);
                    $sgst28 +=($key2->sgst*$key2->qty);
                    $tax28 +=($key2->taxable_rate*$key2->qty); 
                    $tot28 +=(($key2->taxable_rate*$key2->qty)+($key2->tgst*$key2->qty));
               }
           }
           $purch_rate=$pratet;
           $purch_tax=$ptaxt;
           $purch_gst=$pur_gstt;
           $total_amount=($taxable+$sales_gst);
           $finalamt=($taxable+$sales_gst+$other);
           $ordp=OrderPayment::where('order_id',$key->id)
           ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
           ->join('payment_methods','order_payment_methods.payment_method_id','=','payment_methods.id')
           ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
           ->select('order_payment_method_responses.*','payment_methods.name as pname')->first();
            $pname=strtoupper($ordp->payment_mode);
            $pgdata=PgSetting::max('pg_charge');
            if($pname=='BANK')
            {
                $pgactual=0;
                $pg_val=0;
                $bank_val=0;
                $company_val=0;
                $pgdeduct=0;
                $pgdeldeduct=0;
                $bankchar=0;
                $compchar=0;
                
            }
            elseif($pname=='UPI' || $pname=='CARD')
            {
                $pgdatax=PgSetting::where('pg_type',$pname)->first();
                $pgactual=$pgdatax->pg_charge;
                $pg_val=$pgdatax->pg_val;
                $bank_val=$pgdatax->bank_val;
                $company_val=$pgdatax->company_val;
                $pgdeduct=($fma-($fma/$pg_val));
                $pgdeldeduct=($other/$pg_val);
                $bankchar=($fma-($fma/$bank_val));
                $compchar=($fma-($fma/$company_val));
            }
            //echo $pgdeldeduct;
            
           $remain=($fma-$pgdeduct);
           $tosettle=($remain-$pgdeldeduct);
           $vendrpro=$tosettle-$purch_rate;
        $orderData = [   
        'user_id'=>$key->user_id,
        'vendor_id'=>$key->vendor_id,
        'order_id'=>$key->id,
        'amount'=>$fma,
        'purchase_taxable'=>$purch_tax,
        'purchase_value'=>$purch_rate,
        'purchase_gst'=>$purch_gst,
        'taxable_value'=>$taxable,
        'sales_sgst'=>$sales_sgst,
        'sales_cgst'=>$sales_cgst,
        'sales_igst'=>$sales_igst,
        'sales_gst'=>$sales_gst,
        'total_amount'=>$total_amount,
        'other_charges'=>$other,
        'final_amount'=>$finalamt,
        'taxable_value_0'=>$tax0,2,
        'gst_0'=>$gst0,
        'cgst_0'=>$cgst0,
        'sgst_0'=>$sgst0,
        'taxable_value_0.25'=>$tax0_25,
        'gst_0.25'=>$gst0_25,
        'cgst_0.25'=>$cgst0_25,
        'sgst_0.25'=>$sgst0_25,
        'taxable_value_1'=>$tax1,
        'gst_1'=>$gst1,
        'cgst_1'=>$cgst1,
        'sgst_1'=>$sgst1,
        'taxable_value_2'=>$tax2,
        'gst_2'=>$gst2,
        'cgst_2'=>$cgst2,
        'sgst_2'=>$sgst2,
        'taxable_value_3'=>$tax3,
        'gst_3'=>$gst3,
        'cgst_3'=>$cgst3,
        'sgst_3'=>$sgst3,
        'taxable_value_5'=>$tax5,
        'gst_5'=>$gst5,
        'cgst_5'=>$cgst5,
        'sgst_5'=>$sgst5,
        'taxable_value_6'=>$tax6,
        'gst_6'=>$gst6,
        'cgst_6'=>$cgst6,
        'sgst_6'=>$sgst6,
        'taxable_value_12'=>$tax12,
        'gst_12'=>$gst12,
        'cgst_12'=>$cgst12,
        'sgst_12'=>$sgst12,
        'taxable_value_18'=>$tax18,
        'gst_18'=>$gst18,
        'cgst_18'=>$cgst18,
        'sgst_18'=>$sgst18,
        'taxable_value_28'=>$tax28,
        'gst_28'=>$gst28,
        'cgst_28'=>$cgst28,
        'sgst_28'=>$sgst28,
        'pg_charges'=>$pgdata,
        'pg_mode'=>$pname,
        'pg_actual_charge'=>$pgactual,
        'pg_deduct'=>$pgdeduct,
        'remain_amount'=>$remain,
        'pg_delivery_charge'=>$pgdeldeduct,
        'to_settle'=>$tosettle,
        'vendor_profit'=>$vendrpro,
        'bank'=>$bankchar,
        'company'=>$compchar,
        ];
        
         $order = SaleReport::create($orderData);
         $ccc=  $ccc+1;
        }
      }

    }

  
}
