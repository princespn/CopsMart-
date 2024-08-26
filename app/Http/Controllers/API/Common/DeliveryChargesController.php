<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\DeliveryCharges;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;
class DeliveryChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return DeliveryCharges::get('delivery_charges.*');
    }

    public function getdeliverycharge($id)
    { 
        return DeliveryCharges::where('vendor_id',$id)->get('delivery_charges.*');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        return DeliveryCharges::findOrFail($id);
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
            'pincode' => 'required',
            'type' => 'required',
            'delivery_charge' => 'required',
            'vendor_id' => 'required',
        ]);
        $de=DeliveryCharges::where('vendor_id',$request->vendor_id)->where('pincode',$request->pincode)->where('type',$request->type)->get();
        if(count($de)>0) 
        {
            return [
                'resid' => 201,
                'message' => 'Charges Already Assign !',
            ];
        }
        else
        {
            $delivery_charges = new DeliveryCharges();
            $delivery_charges->vendor_id = $request->vendor_id;
            $delivery_charges->pincode = $request->pincode;
            $delivery_charges->delivery_charge = $request->delivery_charge;
            $delivery_charges->type = $request->type;
            $delivery_charges->save();
            return [
                'message' => 'Created Successfully',
            ];
        }
    }

    public function getdeliverycharges($id)
    {
       $de=DeliveryCharges::where('vendor_id',$id)->get();
       $hdelivery=0;
       $picdelivery=0;
       $pdelivery=0;
       if(count($de)>0)
       {  
           foreach($de as $key)
           {
               if($key->type=='Home')
               {
                   $hdelivery=$key->delivery_charge;
               }
               elseif($key->type=='Police Station')
               {
                   $pdelivery=$key->delivery_charge;
               }
               elseif($key->type=='Pickup')
               {
                   $picdelivery=$key->delivery_charge;
               }
           }
       }
       return $ar= array('hdelivery'=>$hdelivery, 'pdelivery'=>$pdelivery,'picdelivery'=>$picdelivery);
    }

    public function storeDeliveryCharges(Request $request)
    {  
         
        $this->validate($request, [
            'delivery_home' => 'required',
            'delivery_police' => 'required',
            'delivery_pickup' => 'required',
            'vendor_id' => 'required',
        ]);
        $hdelivery=$request->delivery_home;
        $pdelivery=$request->delivery_police;
        $picdelivery=$request->delivery_pickup;
        $vid=$request->vendor_id;
        $hd=DeliveryCharges::where('type','Home')->where('vendor_id',$vid)->first();
        if(isset($hd))
        {
            DeliveryCharges::findOrFail($hd->id)->update(['delivery_charge' => $hdelivery]);
        }
        else
        {
            $delivery_charges = new DeliveryCharges();
            $delivery_charges->vendor_id = $vid;
            $delivery_charges->type = 'Home';
            $delivery_charges->delivery_charge = $hdelivery;
            $delivery_charges->save();
        }


        $pd=DeliveryCharges::where('type','Police Station')->where('vendor_id',$vid)->first();
        if(isset($pd))
        {
            DeliveryCharges::findOrFail($pd->id)->update(['delivery_charge' => $pdelivery]);
        }
        else
        {
            $delivery_charges = new DeliveryCharges();
            $delivery_charges->vendor_id = $vid;
            $delivery_charges->type = 'Police Station';
            $delivery_charges->delivery_charge = $pdelivery;
            $delivery_charges->save();
        }


        $picd=DeliveryCharges::where('type','Pickup')->where('vendor_id',$vid)->first();
        if(isset($picd))
        {
            DeliveryCharges::findOrFail($picd->id)->update(['delivery_charge' => $picdelivery]);
        }
        else
        {
            $delivery_charges = new DeliveryCharges();
            $delivery_charges->vendor_id = $vid;
            $delivery_charges->type = 'Pickup';
            $delivery_charges->delivery_charge = $picdelivery;
            $delivery_charges->save();
        }

        //$delivery_charges = DeliveryCharges::findOrFail($id);
        //
        
       // print_r($request->all());

    }

    public function update(Request $request, $id)
    {

        $delivery_charges = DeliveryCharges::findOrFail($id);
        $this->validate($request, [
            'type' => 'required',
            'pincode' => 'required',
            'delivery_charge' => 'required',
            'vendor_id' => 'required',
        ]);
        DeliveryCharges::findOrFail($id)->update(['type' => $request->type,'pincode'=>$request->pincode,'delivery_charge'=>$request->delivery_charge,'vendor_id'=>$request->vendor_id]);
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
        $delivery_charges = DeliveryCharges::findOrFail($id);
        $delivery_charges->delete();
        // delete the subCategory
        return [
            'message' => 'Delivery Charges Deleted !'
        ];
    }
}
