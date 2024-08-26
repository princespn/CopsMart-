<?php

namespace App\Http\Controllers\API\Common;
date_default_timezone_set('Asia/Kolkata');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Vendor;
use App\Product;
use App\OrderProduct;
use App\TmpPurchase;
use App\Purchase;
use App\DeliveryPersonWallet;
use App\MarketingPersonWallet;
use App\VendorWallet;
use App\FranchiseeReport;
use App\OrderPayment;
use App\FranchiseeWallet;
use App\SaleReport;
use App\PgSetting;
use Carbon\Carbon;
use DB;
class OrderReportController extends Controller
{
    private $rules =[
        'from' => 'required',
        'to' =>' required',
    ];

    var $ruless =[
        'amount' => 'required|numeric',
        'description' => 'required|string',
        'is_collectable' => 'required|boolean',
        'is_adjustment' => 'required|boolean',
        'vendor_id' => 'required|numeric',
    ];

    var $fields = [
        'description',
        'amount',
        'is_collectable',
        'vendor_id',
        'is_adjustment',
];
    function gstreport(Request $request)
    {   
        // $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        // $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
         $data=Order::where('orders.vendor_id',$request->vendor_id)->where('orders.deleted_at',NULL)->whereBetween('orders.date', [$request->from, $request->to])->get();
       
        $gst0=0;
        $tax0=0; 
        $tot0=0;

        $gst0_25=0;
        $tax0_25=0; 
        $gst0_25=0;
        
        $gst1=0;
        $tax1=0; 
        $tot1=0;

        $gst2=0;
        $tax2=0; 
        $tot2=0;

        $gst3=0;
        $tax3=0; 
        $tot3=0;
        
        $gst5=0;
        $tax5=0; 
        $tot5=0;

        $gst6=0;
        $tax6=0; 
        $tot6=0;

        $gst12=0;
        $tax12=0; 
        $tot12=0;

        $gst18=0;
        $tax18=0; 
        $tot18=0;

        $gst28=0;
        $tax28=0; 
        $tot28=0;

        foreach($data as $key2)
        {  
            $data2=OrderProduct::where('order_id',$key2->id)->where('deleted_at',NULL)->get();
            foreach($data2 as $key)
            {
            if($key->gst==0)
            {
                $gst0 += round($key->tgst*$key->qty,2);
                $tax0 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==0.25)
            {
                $gst0_25 += round($key->tgst*$key->qty,2);
                $tax0_25 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==1)
            {
                $gst1 += round($key->tgst*$key->qty,2);
                $tax1 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==2)
            {
                $gst2 += round($key->tgst*$key->qty,2);
                $tax2 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==3)
            {
                $gst3 += round($key->tgst*$key->qty,2);
                $tax3 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==5)
            {
                $gst5 += round($key->tgst*$key->qty,2);
                $tax5 += round($key->taxable_rate*$key->qty,2); 
            }

            elseif($key->gst==6)
            {
                $gst6 += round($key->tgst*$key->qty,2);
                $tax6 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==12)
            {
                $gst12 += round($key->tgst*$key->qty,2);
                $tax12 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==18)
            {
                $gst18 += round($key->tgst*$key->qty,2);
                $tax18 += round($key->taxable_rate*$key->qty,2); 
            }
            elseif($key->gst==28)
            {
                $gst28 += round($key->tgst*$key->qty,2);
                $tax28 += round($key->taxable_rate*$key->qty,2); 
            }
        }
        }
         $gst[]=array(
            'gst'=>round($gst0,2),
            'tax'=>round($tax0,2),
            'tot'=>round($gst0+$tax0,2),
            'gstp'=>0);
            
            $gst[]=array(
            'gst'=>$gst0_25,
            'tax'=>round($tax0_25,2),
            'tot'=>round($gst0_25+$tax0_25,2),'gstp'=>0.25);
            
            $gst[]=array(
            'gst'=>round($gst1,2),
            'tax'=>round($tax1,2),
            'tot'=>round($gst1+$tax1,2),'gstp'=>1);
            $gst[]=array(
            'gst'=>round($gst2,3),
            'tax'=>round($tax2,3),
            'tot'=>round($gst2+$tax2,3),'gstp'=>2);
            $gst[]=array(
            'gst'=>round($gst3,3),
            'tax'=>round($tax3,3),
            'tot'=>round($gst3+$tax3,3),'gstp'=>3);
            $gst[]=array(
            'gst'=>round($gst5,3),
            'tax'=>round($tax5,3),
            'tot'=>round($gst5+$tax5,3),'gstp'=>5);
            $gst[]=array(
            'gst'=>round($gst6,3),
            'tax'=>round($tax6,3),
            'tot'=>round($gst6+$tax6,3),'gstp'=>6);
            $gst[]=array(
            'gst'=>round($gst12,3),
            'tax'=>round($tax12,3),
            'tot'=>round($gst12+$tax12,3),'gstp'=>12);
            $gst[]=array(
            'gst'=>round($gst18,3),
            'tax'=>round($tax18,3),
            'tot'=>round($gst18+$tax18,3),'gstp'=>18);
            $gst[]=array(
            'gst'=>round($gst28,3),
            'tax'=>round($tax28,3),
            'tot'=>round($gst28+$tax28,3),'gstp'=>28);
            
        return $gst;
    }
    
    
    function salereportnew(Request $request)
    {   

        $request->from = Carbon::parse($request->from . ' 00:00:00');
        $request->to = Carbon::parse($request->to . ' 23:59:59');
       $data = Product::where('vendor_id',$request->vendor_id)->where('is_active','1')->where('name','!=',NULL)->get();
        return $data;
    }

    function gstrebatereport(Request $request)
    {   
        /* $data=Order::where('orders.vendor_id',$request->vendor_id)
         ->where('orders.deleted_at',NULL)
         ->whereBetween('orders.date', [$request->from, $request->to])
         ->leftJoin('users', 'users.id', '=', 'orders.user_id')
         ->get();

         $product_data = [];

         foreach($data as $value){
            $product_data[]=OrderProduct::where('order_products.order_id',$value->id)
                ->get();
         }*/
       
         
        
            
        // return $data;
        // ///////////////////////////

        //   $order=Order::where('id',$vendor_id)->get();

        //   $order=Order::where('orders.vendor_id',$request->vendor_id)
        //     ->where('orders.deleted_at',NULL)
        //     ->whereBetween('orders.date', [$request->from, $request->to])
        //     ->leftJoin('users', 'users.id', '=', 'orders.user_id')
        //     ->select('orders.*','users.id as user_id', 'users.bukkle_no as bukkle_no','users.bukkle_no as bukkle_no','users.bukkle_no as bukkle_no','users.bukkle_no as bukkle_no','users.bukkle_no as bukkle_no',)
        //     ->get();

        $data=SaleReport::join('orders','orders.id','=','sale_reports.order_id')
                ->join('users',  'users.id' ,'=', 'sale_reports.user_id')
                ->where('sale_reports.vendor_id',$request->vendor_id)
                ->where('orders.vendor_id',$request->vendor_id)
                ->where('orders.deleted_at',NULL)
                ->whereBetween('orders.date', [$request->from, $request->to])
                ->select(['sale_reports.*','users.bukkle_no as bukkle_no', 'users.employee_post as employee_post', 'users.name as name', 'users.card_no as card_no','orders.total_rtgst','orders.invoice_no','orders.date','orders.created_at'])
                ->get();
           
           

            // $orderData[] = [ 
            // 'order_id'=>$key->id,
            // 'bukkle_no'=>$key->bukkle_no,
            // 'employee_post'=>$key->employee_post,
            // 'name'=>$key->name,
            // 'card_no'=>$key->card_no,
            // 'invoice_no'=>$key->invoice_no,
            // 'date'=>$key->date,
            // 'taxable'=>round($taxable, 2),
            // 'total_gst'=>$total_amount,
            // 'total_gst_rebate'=>round($total_amount/2, 2),
            // 'finalamt'=>$finalamt,
            // 'pay_mode'=>$pname,
            // 'pg_charge'=>$pgactual,
            // 'other_charge'=>$other,
            // 'to_settle'=>round($tosettle,2),


            // ];
            
            
        

        return $data;
    }
    

    function hsnreport(Request $request)
    {  
       $arr=[];
       $dt=[];
       $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
       $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
       $product=Product::where('vendor_id',$request->vendor_id)->where('deleted_at',NULL)->where('hsn','!=',NULL)->groupBy('hsn')->get();
        foreach($product as $key)
        {  
          $hsn=$key->hsn;
          $qty=0;
          $tax=0;
          $igst=0;
          $cgst=0;
          $sgst=0;
         
          $prod=OrderProduct::where('hsn',$key->hsn)->where('deleted_at',NULL)->whereBetween('created_at', [$request->from, $request->to])->get();
          if(count($prod)>0)
          {
            foreach($prod as $keey)
            { 
                $hsn=$key->hsn;
                $qty +=$keey->qty;
                $tax += round($keey->taxable_rate*$keey->qty,2); 
                $cgst += round($keey->cgst*$keey->qty,2); 
                $sgst += round($keey->sgst*$keey->qty,2); 
                $igst += round($keey->igst*$keey->qty,2);
            }
            $arr['hsn']=$hsn;
            $arr['qty']=$qty;
            $arr['uqc']='OTH-OTHERS';
            $arr['description']='';
            $arr['tax']=round($tax,2);
            $arr['cgst']=round($cgst,2);
            $arr['sgst']=round($sgst,2);
            $arr['igst']=round($igst,2);
            $arr['total']=round(($tax+$cgst+$sgst),2);
            $arr['chess']=0;
            $dt[]=$arr;
          }
        }
        return $dt;
    }
    
    
    function hsnnewreport(Request $request)
    {  
       $arr=[];
       $dt=[];
        $request->from = Carbon::parse($request->from . ' 00:00:00');
        $request->to = Carbon::parse($request->to . ' 23:59:59');
       $product=Product::where('vendor_id',$request->vendor_id)->where('deleted_at',NULL)->where('hsn','!=',NULL)->groupBy('hsn')->get();
        // foreach($product as $key)
        // {  
        //   $hsn=$key->hsn;
        //   $qty=0;
        //   $tax=0;
        //   $igst=0;
        //   $cgst=0;
        //   $sgst=0;
         
          //$prod=OrderProduct::whereBetween('created_at', [$request->from, $request->to])->sum(\DB::raw('taxable_rate * qty as taxabletotal'))->get();
          
          $prod = OrderProduct::select(
                \DB::raw('SUM(price*qty) as tam'),
                \DB::raw('SUM(taxable_rate*qty) as ttx'),
                \DB::raw('SUM(qty) as tqt'),
                \DB::raw('SUM(sgst*qty) as tsqst'),
                \DB::raw('SUM(cgst*qty) as tcqst'),
                \DB::raw('SUM(igst*qty) as tiqst'),
                'order_products.*'
            )
            ->whereBetween('created_at', [$request->from,$request->to])
            ->whereNull('order_products.deleted_at')
            ->whereNotIn('qty', [0])
            ->groupBy('hsn')
            ->get();
           // echo count($prod);
          if(count($prod)>0)
          {
            foreach($prod as $keey)
            {  
                
              
                // $hsn=;
                // $qty +=$keey->qty;
                // $tax += round($keey->taxable_rate*$keey->qty,2); 
                // $cgst += round($keey->cgst*$keey->qty,2); 
                // $sgst += round($keey->sgst*$keey->qty,2); 
                // $igst += round($keey->igst*$keey->qty,2);

            $arr['hsn']=$keey->hsn;
            $arr['qty']=$keey->tqt;
            $arr['uqc']='OTH-OTHERS';
            $arr['description']='';
            $arr['tax']=round($keey->ttx,2);
            $arr['cgst']=round($keey->tcqst,2);
            $arr['sgst']=round($keey->tsqst,2);
            $arr['igst']=round($keey->tiqst,2);
            $arr['total']=round($keey->tam,2);
            $arr['chess']=0;
            $dt[]=$arr;
            }
          }
        // }
        return $dt;
    }

    function purchasegst(Request $request)
    {   
        // $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        // $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
        $data=Purchase::where('vendor_id',$request->vendor_id)->where('deleted_at',NULL)->whereBetween('invoice_date', [$request->from, $request->to])->get();
       
        $gst0=0;
        $tax0=0; 
        $tot0=0;

        $gst0_25=0;
        $tax0_25=0; 
        $gst0_25=0;
        
        $gst1=0;
        $tax1=0; 
        $tot1=0;

        $gst2=0;
        $tax2=0; 
        $tot2=0;

        $gst3=0;
        $tax3=0; 
        $tot3=0;
        
        $gst5=0;
        $tax5=0; 
        $tot5=0;

        $gst6=0;
        $tax6=0; 
        $tot6=0;

        $gst12=0;
        $tax12=0; 
        $tot12=0;

        $gst18=0;
        $tax18=0; 
        $tot18=0;

        $gst28=0;
        $tax28=0; 
        $tot28=0;
        
        if(count($data)>0) {
        foreach($data as $key2)
        {  
            $id[]=$key2->tmp_purchase_id;
        }
        
        foreach($id as $idd)
        {
            $iddd[]=$idd;
        }
           $idx=explode(',',implode(',',$iddd));

            $data2=TmpPurchase::whereIn('id',$idx)->where('deleted_at',NULL)->get();
            foreach($data2 as $key)
            {
                if($key->gst==0)
                {
                    $gst0 += round($key->p_tgst*$key->qty,2);
                    $tax0 += round($key->p_total_taxable,2); 
                }
                elseif($key->gst==0.25)
                {
                    $gst0_25 += round($key->p_tgst*$key->qty,2);
                    $tax0_25 +=  round($key->p_total_taxable,2); 
                }
                elseif($key->gst==1)
                {
                    $gst1 += round($key->p_tgst*$key->qty,2);;
                    $tax1 +=  round($key->p_total_taxable,2); 
                }
                elseif($key->gst==2)
                {
                    $gst2 +=round($key->p_tgst*$key->qty,2);
                    $tax2 +=  round($key->p_total_taxable,2); 
                }
                elseif($key->gst==3)
                {
                    $gst3 +=round($key->p_tgst*$key->qty,2);
                    $tax3 += round($key->p_total_taxable,2); 
                }
                elseif($key->gst==5)
                {
                    $gst5 += round($key->p_tgst*$key->qty,2);
                    $tax5 +=  round($key->p_total_taxable,2); 
                }

                elseif($key->gst==6)
                {
                    $gst6 += round($key->p_tgst*$key->qty,2);
                    $tax6 +=  round($key->p_total_taxable,2); 
                }
                elseif($key->gst==12)
                {
                    $gst12 += round($key->p_tgst*$key->qty,2);
                    $tax12 += round($key->p_total_taxable,2); 
                }
                elseif($key->gst==18)
                {
                    $gst18 += round($key->p_tgst*$key->qty,2);
                    $tax18 +=  round($key->p_total_taxable,2);  
                }
                elseif($key->gst==28)
                {
                    $gst28 +=round($key->p_tgst*$key->qty,2);
                    $tax28 +=  round($key->p_total_taxable,2); 
                }
            }
        }
         $gst[]=array(
            'gst'=>round($gst0,2),
            'tax'=>round($tax0,2),
            'tot'=>round($gst0+$tax0,2),
            'gstp'=>0);
            
            $gst[]=array(
            'gst'=>$gst0_25,
            'tax'=>round($tax0_25,2),
            'tot'=>round($gst0_25+$tax0_25,2),'gstp'=>0.25);
            
            $gst[]=array(
            'gst'=>round($gst1,2),
            'tax'=>round($tax1,2),
            'tot'=>round($gst1+$tax1,2),'gstp'=>1);
            $gst[]=array(
            'gst'=>round($gst2,3),
            'tax'=>round($tax2,3),
            'tot'=>round($gst2+$tax2,3),'gstp'=>2);
            $gst[]=array(
            'gst'=>round($gst3,3),
            'tax'=>round($tax3,3),
            'tot'=>round($gst3+$tax3,3),'gstp'=>3);
            $gst[]=array(
            'gst'=>round($gst5,3),
            'tax'=>round($tax5,3),
            'tot'=>round($gst5+$tax5,3),'gstp'=>5);
            $gst[]=array(
            'gst'=>round($gst6,3),
            'tax'=>round($tax6,3),
            'tot'=>round($gst6+$tax6,3),'gstp'=>6);
            $gst[]=array(
            'gst'=>round($gst12,3),
            'tax'=>round($tax12,3),
            'tot'=>round($gst12+$tax12,3),'gstp'=>12);
            $gst[]=array(
            'gst'=>round($gst18,3),
            'tax'=>round($tax18,3),
            'tot'=>round($gst18+$tax18,3),'gstp'=>18);
            $gst[]=array(
            'gst'=>round($gst28,3),
            'tax'=>round($tax28,3),
            'tot'=>round($gst28+$tax28,3),'gstp'=>28);
            
        return $gst;
    }

    function dayreport(Request $request)
    {
        $data=Order::where('orders.vendor_id',$request->vendor_id)->where('orders.deleted_at',NULL)->whereBetween('orders.date', [$request->from, $request->to])->get();
        $cnt=count($data);
        $tax=0;
        $gst=0;
        $trgst=0;
        $subt=0;
        $other=0;
        $final=0;
        $bank=0;
        $online=0;
        $ormat=0;
        foreach($data as $key2)
        {   
            if($key2->amount!=0)
            {
            $other +=$key2->delivery_charges;
            $ormat +=$key2->amount;
            $trgst +=$key2->total_rtgst;
            $data2=OrderProduct::where('order_id',$key2->id)->where('deleted_at',NULL)->get();
            foreach($data2 as $key)
            {
                $gst +=round($key->tgst*$key->qty,2);
                $tax += round($key->taxable_rate*$key->qty,2); 
            }

            $ordp=OrderPayment::where('order_id',$key2->id)
            ->join('order_payment_methods','order_payment_methods.order_payment_id','=','order_payments.id')
            ->join('payment_methods','order_payment_methods.payment_method_id','=','payment_methods.id')
            ->join('order_payment_method_responses','order_payment_method_responses.order_payment_method_id','=','order_payment_methods.id')
            ->select('order_payment_method_responses.*')->first();
            $pname=strtoupper($ordp->payment_mode);
            if($pname=='BANK')
            {
               $bank +=$key2->amount;
            }
            elseif($pname=='UPI' || $pname=='CARD')
            {
                $online +=$key2->amount;
            }
          }
        }
        $subt= round($tax+$gst,2);
        $final=round(($subt+$other)-($trgst),2);
  
        $gst=array(
            'count'=>$cnt,
            'tax'=>round($tax,2),
            'gst'=>round($gst,2),
            'subtotal'=>$subt,
            'other'=>$other,
            'final'=>$final,
            'bank'=>$bank,
            'amount'=>$ormat,
            'online'=>$online,
            'trgst'=>$trgst
        );
        $ggst[]=$gst;
        return $ggst;
    }

    function overallreport(Request $request)
    {
        $data=Order::join('sale_reports','sale_reports.order_id','=','orders.id')->where('orders.vendor_id',$request->vendor_id)->where('orders.deleted_at',NULL)->whereBetween('orders.date', [$request->from, $request->to])->select(['orders.*','sale_reports.*'])->get();
        $cnt=count($data);
        $rtgst=0;$tax=0;$gst=0;$subt=0;$other=0;$final=0;$bank=0;$online=0;$ormat=0;$oncount=0;$countercount=0;$sbab=[];$bnkcnt=0;$upicnt=0;$cardcnt=0;$totbank=0;$totcard=0;$totupi=0;$pgupi=0;$pgbank=0;$pgcard=0;$pg_dupi=0;$pg_dbank=0;$pg_dcard=0;$setbank=0;$setcard=0;$purch=0;
        $setupi=0;
        $ncard=PgSetting::where('vendor_id',$request->vendor_id)->where('pg_type','CARD')->first()->pg_charge;
        $nupi=PgSetting::where('vendor_id',$request->vendor_id)->where('pg_type','UPI')->first()->pg_charge;;
        $ncard='CARD ('.$ncard.' %)';
        $nupi='UPI ('.$nupi.' %)';
       // exit;
        foreach($data as $key2)
        {   
            if($key2->amount!=0)
            {   
                $other +=$key2->delivery_charges;
                $ormat +=$key2->amount;
                $gst +=round($key2->sales_gst,2);
                $tax += round($key2->taxable_value,2); 
                $pname=strtoupper($key2->pg_mode);
                $purch +=$key2->purchase_value;
                $rtgst +=$key2->total_rtgst;
                if($pname=='BANK')
                {
                  //$nbank='BANK ('.$key2->pg_charges.' %)';
                  $bank +=$key2->amount;$bnkcnt +=1;$totbank +=$key2->amount;$pgbank +=$key2->pg_deduct;$pg_dbank +=$key2->pg_delivery_charge;$setbank +=$key2->to_settle;$countercount += 1;
                }
                elseif($pname=='UPI')
                {  
                    //$nupi='UPI ('.$key2->pg_charges.' %)';
                    $upicnt +=1;$online +=$key2->amount;$totupi +=$key2->amount;$pgupi +=$key2->pg_deduct;$pg_dupi +=$key2->pg_delivery_charge;;$setupi +=$key2->to_settle;$oncount+= 1;
                }
                elseif($pname=='CARD')
                {
                    //$ncard='CARD ('.$key2->pg_charges.' %)';
                    $cardcnt +=1;$online +=$key2->amount;$totcard +=$key2->amount;$pgcard +=$key2->pg_deduct;$pg_dcard +=$key2->pg_delivery_charge;;$setcard +=$key2->to_settle;$oncount+= 1;
                }
                
            }
        }
        $subt= round($tax+$gst,2);$final=round($subt+$other,2);
        
        
        //purchase
        $ptot=0;$pgst=0;$pother=0;
        $purchasecnt=Purchase::where('vendor_id',$request->vendor_id)->where('deleted_at',NULL)->whereBetween('invoice_date', [$request->from, $request->to])->groupBy('invoice_no')->get();;
        $purchase=Purchase::where('vendor_id',$request->vendor_id)->where('deleted_at',NULL)->whereBetween('invoice_date', [$request->from, $request->to])->get();;
        $id=[];
        $iddd=[];
        if(count($purchase)>0) 
        {
            foreach($purchase as $keyx)
            {  
                $id[]=$keyx->tmp_purchase_id;
                $pother +=$keyx->other_charges;
            }
            foreach($id as $idd)
            {
                $iddd[]=$idd;
            }
               $idx=explode(',',implode(',',$iddd));
            $data2n=TmpPurchase::whereIn('id',$idx)->where('deleted_at',NULL)->get();
            foreach($data2n as $keyc)
            {
                $ptot +=round($keyc->p_total_taxable,3); 
                $pgst +=round($keyc->p_tgst*$keyc->qty,3);
            }
        }
        $subtt=round(($ptot+$pgst),2);
        $purchasearr[]=array(
            'total_invoice'=>count($purchasecnt),
            'taxable_rate'=>round($ptot,2),
            'gst'=>round($pgst,2),
            'subtotal'=>$subtt,
            'other'=>$pother,
            'final'=>round(($subtt+$pother),2),
        );
        //Array creation
        //purchase end
        
        $gst=array(
            'count'=>$cnt,
            'tax'=>round($tax,2),
            'gst'=>round($gst,2),
            'subtotal'=>$subt,
            'rtgst'=>round($rtgst,2),
            'other'=>$other,
            'final'=>$final,
            'bank'=>$bank,
            'amount'=>$ormat,
            'online'=>$online,
            'oncount'=> $oncount,
            'countercount'=> $countercount,
            'purchase'=>$purch,
        );
        $sbab[]=array(
            'payment_type'=>'Bank (0%)','total_payments'=>$bnkcnt,'total_amount'=>$totbank,'pg_charge'=>$pgbank,'pg_del_charge'=>$pg_dbank,'settle'=>$setbank,
        );
        $sbab[]=array(
            'payment_type'=>$nupi,'total_payments'=>$upicnt,'total_amount'=>$totupi,'pg_charge'=>$pgupi,'pg_del_charge'=>$pg_dupi,'settle'=>$setupi,
        );
        $sbab[]=array(
            'payment_type'=>$ncard,'total_payments'=>$cardcnt,'total_amount'=>$totcard,'pg_charge'=>$pgcard,'pg_del_charge'=>$pg_dcard,'settle'=>$setcard,
        );
        $gsts[]=$gst;
        $ggst=['sales_data'=>$gsts,'sales_bank'=>$sbab,'purchase'=>$purchasearr];
        return $ggst;
    }
    
    
    public function PurchaseDaY(Request $request)
    {
        $ptot=0;$pgst=0;
        $pother=0;
        $purchasecnt=Purchase::where('vendor_id',$request->vendor_id)->where('deleted_at',NULL)->whereBetween('invoice_date', [$request->from, $request->to])->groupBy('invoice_no')->get();;
        $purchase=Purchase::where('vendor_id',$request->vendor_id)->where('deleted_at',NULL)->whereBetween('invoice_date', [$request->from, $request->to])->get();;
        $id=[];
        $iddd=[];
        if(count($purchase)>0) 
        {
            foreach($purchase as $keyx)
            {  
                $id[]=$keyx->tmp_purchase_id;
                $pother +=$keyx->other_charges;
            }
            foreach($id as $idd)
            {
                $iddd[]=$idd;
            }
               $idx=explode(',',implode(',',$iddd));
            $data2n=TmpPurchase::whereIn('id',$idx)->where('deleted_at',NULL)->get();
            foreach($data2n as $keyc)
            {
                $ptot +=round($keyc->p_total_taxable,3); 
                $pgst +=round($keyc->p_tgst*$keyc->qty,3);
            }
          
        }
        $subtt=round(($ptot+$pgst),2);
        $purchasearr[]=array(
            'total_invoice'=>count($purchasecnt),
            'taxable_rate'=>round($ptot,2),
            'gst'=>round($pgst,2),
            'subtotal'=>$subtt,
            'other'=>$pother,
            'final'=>round(($subtt+$pother),2),
        );
        
         return $ggst=['purchase'=>$purchasearr];
    }
    
    public function SaleDaY(Request $request)
    {
        
        
        $data=Order::join('sale_reports','sale_reports.order_id','=','orders.id')->where('orders.vendor_id',$request->vendor_id)->where('orders.deleted_at',NULL)->whereBetween('orders.date', [$request->from, $request->to])->select(['orders.*','sale_reports.*'])->get();
        $cnt=count($data);
        $tax=0;$gst=0;$subt=0;$other=0;$final=0;$bank=0;$online=0;$ormat=0;$oncount=0;$countercount=0;$sbab=[];$bnkcnt=0;$upicnt=0;$cardcnt=0;$totbank=0;$totcard=0;$totupi=0;$pgupi=0;$pgbank=0;$pgcard=0;$pg_dupi=0;$pg_dbank=0;$pg_dcard=0;$setbank=0;$setcard=0;$purch=0;
        $setupi=0;
        $ncard=PgSetting::where('vendor_id',$request->vendor_id)->where('pg_type','CARD')->first()->pg_charge;
        $nupi=PgSetting::where('vendor_id',$request->vendor_id)->where('pg_type','UPI')->first()->pg_charge;;
        $ncard='CARD ('.$ncard.' %)';
        $nupi='UPI ('.$nupi.' %)';
       // exit;
        foreach($data as $key2)
        {   
            if($key2->amount!=0)
            {   
                $other +=$key2->delivery_charges;
                $ormat +=$key2->amount;
                $gst +=round($key2->sales_gst,2);
                $tax += round($key2->taxable_value,2); 
                $pname=strtoupper($key2->pg_mode);
                $purch +=$key2->purchase_value;
                if($pname=='BANK')
                {
                  //$nbank='BANK ('.$key2->pg_charges.' %)';
                  $bank +=$key2->amount;$bnkcnt +=1;$totbank +=$key2->amount;$pgbank +=$key2->pg_deduct;$pg_dbank +=$key2->pg_delivery_charge;$setbank +=$key2->to_settle;$countercount += 1;
                }
                elseif($pname=='UPI')
                {  
                    //$nupi='UPI ('.$key2->pg_charges.' %)';
                    $upicnt +=1;$online +=$key2->amount;$totupi +=$key2->amount;$pgupi +=$key2->pg_deduct;$pg_dupi +=$key2->pg_delivery_charge;;$setupi +=$key2->to_settle;$oncount+= 1;
                }
                elseif($pname=='CARD')
                {
                    //$ncard='CARD ('.$key2->pg_charges.' %)';
                    $cardcnt +=1;$online +=$key2->amount;$totcard +=$key2->amount;$pgcard +=$key2->pg_deduct;$pg_dcard +=$key2->pg_delivery_charge;;$setcard +=$key2->to_settle;$oncount+= 1;
                }
                
            }
        }
        $subt= round($tax+$gst,2);$final=round($subt+$other,2);
        $gst=array(
            'count'=>$cnt,
            'tax'=>round($tax,2),
            'gst'=>round($gst,2),
            'subtotal'=>$subt,
            'other'=>$other,
            'final'=>$final,
            'bank'=>$bank,
            'amount'=>$ormat,
            'online'=>$online,
            'oncount'=> $oncount,
            'countercount'=> $countercount,
            'purchase'=>$purch,
        );
        
         $gsts[]=$gst;
         return $ggst=['sales_data'=>$gsts];
    }


    function orderReport(Request $request)
    {
        $this->rules['type'] = 'sometimes|nullable|numeric';
        $this->validate($request, $this->rules);
        $id=$request->admin_id;
        $vendors=Vendor::where('admin_id',$id)->select('id')->get();
        $key ='';
        foreach($vendors as $k){$key .="'$k->id'".',';}
        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);

        $query = Order::with(['user','status','vendorStatus','deliveryStatus'])->whereBetween('created_at', [$request->from, $request->to]);
        if(is_numeric($request->type)){
            $query->where('order_status_id', $request->type);
        }
        if($id==1)
        {  
            return $query->get();
        }
        else
        {   
            $key= rtrim($key,",");
            $query->whereRaw("vendor_id IN ($key)");
            return $query->get();
        }
       // return $query->get();
    }

    function salereport(Request $request)
    {   
       
        $data=SaleReport::join('orders','orders.id','=','sale_reports.order_id')->join('users', 'orders.user_id', '=', 'users.id')->where('sale_reports.vendor_id',$request->vendor_id)->where('orders.vendor_id',$request->vendor_id)->where('orders.deleted_at',NULL)->whereBetween('orders.date', [$request->from, $request->to])->select(['sale_reports.*','users.name', 'orders.total_rtgst','orders.invoice_no','orders.date','orders.created_at']);

        if(isset($request->payment_mode))
        {
            $data->where('sale_reports.pg_mode',$request->payment_mode);
        }
        if(isset($request->user_id))
        {
            $data->where('sale_reports.user_id',$request->user_id['id']);
        }
      return  $data->get();
    }
    
    function productwisereport(Request $request)
    {   
       
        $data=SaleReport::join('orders','orders.id','=','sale_reports.order_id')->join('users','users.id','=','sale_reports.user_id')->join('order_products','order_products.order_id','=','sale_reports.order_id')->where('sale_reports.vendor_id',$request->vendor_id)->where('orders.vendor_id',$request->vendor_id)->where('orders.deleted_at',NULL)->whereBetween('orders.date', [$request->from, $request->to])->select(['sale_reports.*','orders.invoice_no','orders.date','users.name as username']);
        $data->where('order_products.product_id',$request->product_id['id'])->groupBy('sale_reports.order_id');
        $data=$data->get();
        $dataor=[];
        foreach ($data as $key)
        {
            $orpro=OrderProduct::where('order_id',$key->order_id)->get();
             $qty=OrderProduct::where('order_id',$key->order_id)->where('product_id',$request->product_id['id'])->sum('qty');
            $key->qty=$qty;
            $count=count($orpro);
            if($count>1)
            {
                $proname=$request->product_id['name'].' ('.($count-1).'+ items )';
            }
            else
            {
                $proname=$request->product_id['name'];
            }
            $key->proname=$proname;
            $dataor[]=$key;
        }
       return $dataor;
    }


    function profitreport(Request $request)
    {   
        $data=SaleReport::join('orders','orders.id','=','sale_reports.order_id')->where('sale_reports.vendor_id',$request->vendor_id)->where('orders.vendor_id',$request->vendor_id)->where('orders.deleted_at',NULL)->whereBetween('orders.date', [$request->from, $request->to])->select(['sale_reports.*','orders.total_rtgst','orders.invoice_no','orders.date','orders.created_at']);
        return  $data->get();
    }
    

    
    


    function dailyreport(Request $request, $PersonId){
         $type=$request->type;
         $reportof=$request->reportof;
         
         $query = DB::table('orders')
        ->join('users', 'user_id','=', 'users.id')
        ->join('order_statuses', 'order_status_id','=', 'order_statuses.id')
        ->where('payment_status', 1)
         ->where('order_status_id', 9)
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*','order_statuses.name as order_status','users.name', 'users.email', 'users.mobile');
        //$query2= DB::table('orders');
       
        switch ($reportof) {
            case 'delivery':
                $query->whereRaw("delivery_person_id = $PersonId ");
                $queryx="select sum(amount) as amount from `orders` where `order_status_id`='9'";
                $queryx .=" AND delivery_person_id = $PersonId ";
                $queryvend="select new_balance  from `delivery_person_wallets` where `is_collectable`='1' AND delivery_person_id = $PersonId AND created_at > now() - interval 1 week ORDER BY `id` DESC LIMIT 1";
                $querycount="SELECT `new_balance` FROM `delivery_person_wallets` where `is_collectable`='0'  AND delivery_person_id='$PersonId' AND created_at > now() - interval 1 week ORDER BY `id` DESC LIMIT 1";
                break;
            case 'vendor':
                $query->whereRaw("vendor_id = $PersonId ");
                 //$query2->whereRaw("vendor_id = $PersonId ");
                  $queryx="select sum(cost_price_amount) as amount from `orders` where `order_status_id`='9'";
                 $queryx .=" AND vendor_id = $PersonId ";
                 $queryvend="select new_balance  from `vendor_wallets` where vendor_id = $PersonId AND created_at > now() - interval 1 week ORDER BY `id` DESC LIMIT 1";
                 $querycount="select *  from `orders` where vendor_id = $PersonId AND delivery_status_id='3' AND status_updated > now() - interval 1 week ORDER BY `id` DESC ";
                break;

            default:

                break;
        }
        switch ($type) {
            case 'weekly':
                $query->whereRaw("status_updated > now() - interval 1 week");
                 //$query2->whereRaw("status_updated > now() - interval 1 week");
                 $queryx .="AND status_updated > now() - interval 1 week";
                break;
            case 'monthly':
                $d1=date('Y-m-01 00:00:00');
                $d2=date('Y-m-d H:i:s');
                $query->whereRaw("status_updated BETWEEN '$d1' AND '$d2'");
               // $query2->whereRaw("status_updated BETWEEN '$d1' AND '$d2'");
                $queryx .=" AND status_updated BETWEEN '$d1' AND '$d2'";
            case 'daily' :  
                $d1=date('Y-m-d 00:00:00');
                $d2=date('Y-m-d H:i:s');
                $query->whereRaw("status_updated BETWEEN '$d1' AND '$d2'");
               // $query2->whereRaw("status_updated BETWEEN '$d1' AND '$d2'");
                $queryx .=" AND status_updated BETWEEN '$d1' AND '$d2'";
                break;
            default:
                break;
        }
        //echo $queryvend;
          $query2=DB::select($queryx);
          $query21=DB::select($queryvend);
          $data=$query->get(); 
          $dataqq=DB::select($querycount);
         if(count($dataqq)>0)
         {
            if($reportof=='vendor')
          {
             $ordt=count($dataqq);
          }
          else
          {
            $ordt=$dataqq[0]->new_balance ;  
          } 
         }
         else
         {
            $ordt=0.00; 
         }
          
          
        if(count($data)>0)
        {
         foreach($data as $key)
          {   
            
            $key->status_updated=date('Y-m-d',strtotime($key->status_updated));
            $dataa[]=$key;
            
        }
          $data2['order']= $dataa;
          $data2['count']=count($dataa);
          $data2['total']=$query2[0]->amount;
          $data2['vendor_total']=$query21[0]->new_balance;
          $data2['order_total']=$ordt;
        }
        else
        {
          $data2['order']=[];
          $data2['count']=0;
          $data2['total']=0;
          $data2['vendor_total']=0;
          $data2['order_total']=0;
        }

           return $data2;
    }



    function itemWiseBWDates(Request $request){
        $this->rules['product_id'] = 'required|numeric';
        $this->validate($request, $this->rules);

        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
       

        
        if(isset($_GET['ad']))
        {
            $id=$_GET['ad'];
            if($id!=1)
            {  
                $vendors=Vendor::where('admin_id',$id)->select('id')->get();
                $key ='';
                foreach($vendors as $k){$key .="'$k->id'".',';}
                $key= rtrim($key,",");
                $query->whereRaw("vendor_id IN ($key)");
                $query = OrderProduct::leftJoin('orders','orders.id','=','order_products.order_id')->leftJoin('vendors','vendors.id','=','orders.vendor_id')->whereRaw("vendors.vendor_id IN ($key)")->where('product_id', $request->product_id)->whereBetween('created_at', [$request->from, $request->to]);
            }
            else{
                $query = OrderProduct::where('product_id', $request->product_id)->whereBetween('created_at', [$request->from, $request->to]);
            }
        }
        else
        {   
               $query = OrderProduct::where('product_id', $request->product_id)->whereBetween('created_at', [$request->from, $request->to]);
        }
        return $query->get();
    }

    function profitBWDates(Request $request){
        $this->validate($request, $this->rules);
        $id=$request->admin_id;

        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
        $query = OrderProduct::whereBetween('orders.created_at', [$request->from, $request->to]);
        $query->selectRaw('SUM( cost_price * qty) as vendor_total , order_products.*, orders.*, users.name as user_name')->join('orders', 'orders.id', '=', 'order_products.order_id')->where('order_status_id', 9);
        $query->join('users', 'orders.user_id', '=', 'users.id')->groupBy('order_products.order_id');
        return $query->get();
    }

    function franchisee_wallet(Request $request,$vendorId)
    {
        $this->validate($request, $this->rules);
        $id=$request->admin_id;
        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
        $query = FranchiseeReport::whereBetween('franchisee_reports.created_at', [$request->from, $request->to]);
        $query->select(['franchisee_reports.*','order_products.*', 'orders.*', 'users.name as user_name','franchisee_reports.created_at as created'])
        ->join('order_products', 'order_products.order_id', '=', 'franchisee_reports.order_id')
        ->join('orders', 'orders.id', '=', 'franchisee_reports.order_id')
        ->where('orders.order_status_id', 9)->where('franchisee_reports.franchisee_id',$vendorId);
        $query->join('users', 'franchisee_reports.user_id', '=', 'users.id');
        return $query->get();
    }
     
    function franch_wallet(Request $request,$vendorId)
    {
        $this->validate($request, $this->rules);
        $id=$request->admin_id;
        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
        $query = FranchiseeWallet::whereBetween('franchisee_wallets.created_at', [$request->from, $request->to]);
        $query->select(['franchisee_wallets.*'])->where('franchisee_wallets.franchisee_id',$vendorId);
        return $query->get();
    }
    


    function getWalletBalance($tbl,$vendorId){
        return DB::table($tbl)->where('franchisee_id', $vendorId)->selectRaw('new_balance as total')->orderBy('id','desc')->limit(1)->first()->total;
    }
    function getCollectableBalance($tbl,$vendorId){
        return DB::table('franchisee_reports')->where(['franchisee_id' => $vendorId, 'is_collectable' => true])->selectRaw('new_balance as total')->orderBy('id','desc')->limit(1)->first()->total;
    }
    function getBalanceFran($vendorId){
        $balance = $this->getWalletBalance('franchisee_reports',$vendorId);
        $collectable = $this->getCollectableBalance('franchisee_reports',$vendorId);

        return [
            'balance' => $balance,
            'collectable_balance' => $collectable,
            'total_payable' => $balance - $collectable,
        ];
    }

    function getBalanceFranch($vendorId){
        $balance = $this->getWalletBalance('franchisee_wallets',$vendorId);
        $collectable = $this->getCollectableBalance('franchisee_wallets',$vendorId);

        return [
            'balance' => $balance,
            'collectable_balance' => $collectable,
            'total_payable' => $balance - $collectable,
        ];
    }

    function addWalletTransaction(Request $request, $vendorId)
    {
        $this->validate($request, $this->ruless);
        
        $data=$request->only($this->fields);
        $datav=FranchiseeWallet::where('franchisee_id',$data['vendor_id'])->orderBy('id','DESC')->get();
        if(count($datav)>0)
        {
           $newb=  $datav[0]->new_balance;
           $amt=$data['amount'];
           $newbal=  $newb-$amt;
           $data['new_balance']=$newbal;
           $array=array(
              'amount'=>$data['amount'],
              'description'=>$data['description'],
              'is_collectable'=>0,
              'is_adjustable'=>1,
              'franchisee_id'=>$data['vendor_id'],
              'new_balance'=>$newbal,
              );
          // exit;
          
        }
        return [
            'success' => FranchiseeWallet::create($array)
         ];
        //  print_r($datav);
        //  exit;
        
    }

    function deliveryWalletBWDates(Request $request, $deliveryPersonId)
    {
        $this->validate($request, $this->rules);
 

        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
        return DeliveryPersonWallet::where('delivery_person_id', $deliveryPersonId)->whereBetween('created_at', [$request->from, $request->to])->orderBy('created_at','DESC')->get();
    }

    function marketingWalletBWDates(Request $request, $marketingPersonId){
        $this->validate($request, $this->rules);


        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
        return MarketingPersonWallet::where('marketing_person_id', $marketingPersonId)->whereBetween('created_at', [$request->from, $request->to])->orderBy('created_at','DESC')->get();
    }

    function vendorWalletBWDates(Request $request, $vendorId){
        $this->validate($request, $this->rules);


        $request->from = Carbon::parse($request->from . ' 00:00:00')->subMinutes(72);
        $request->to = Carbon::parse($request->to . ' 23:59:59')->subMinutes(72);
        return  VendorWallet::where('vendor_id', $vendorId)->whereBetween('created_at', [$request->from, $request->to])->orderBy('created_at','DESC')->get();
    }

}
