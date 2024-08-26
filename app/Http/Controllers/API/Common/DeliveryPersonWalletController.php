<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DeliveryPersonWallet;
use DB;
class DeliveryPersonWalletController extends Controller
{
    var $rules =[
        'amount' => 'required|numeric',
        'description' => 'required|string',
        'is_collectable' => 'required|boolean',
        'is_adjustment' => 'required|boolean',
        'delivery_person_id' => 'required|numeric',
    ];

    var $fields = [
                    'description',
                    'amount',
                    'delivery_charges_for_cust',
                    'is_collectable',
                    'delivery_person_id',
                    'is_adjustment',
                    'description',
    ];

    function getWalletTransaction($deliveryPersonId){
        return DeliveryPersonWallet::where('delivery_person_id', $deliveryPersonId)->orderBy('created_at','DESC')->get();
    }

    function getWalletBalance($deliveryPersonId){
        return DB::table('delivery_person_wallets')->where('delivery_person_id', $deliveryPersonId)->selectRaw('SUM(amount) as total')->first()->total;
    }

    function getPayableWalletBalance($deliveryPersonId){
        return DB::table('delivery_person_wallets')->where(['delivery_person_id' => $deliveryPersonId, 'is_collectable' => 0])->selectRaw('SUM(delivery_charges_for_cust) as total')->first()->total;
    }

    function getCollectableBalance($deliveryPersonId){
        return DB::table('delivery_person_wallets')->where(['delivery_person_id' => $deliveryPersonId, 'is_collectable' => 1])->selectRaw('SUM(amount) as total')->first()->total;
    }

    function getBalance($deliveryPersonId){
        $balance = $this->getWalletBalance($deliveryPersonId);
        $collectable = $this->getCollectableBalance($deliveryPersonId);
        $payable = $this->getPayableWalletBalance($deliveryPersonId);
        // dd($collectable);

        return [
            'balance' => $balance,
            'collectable_balance' => $collectable,
            'total_payable' => $payable,
        ];
    }

    function addWalletTransaction(Request $request, $deliveryPersonId){
        $this->validate($request, $this->rules);
        
       $data=$request->only($this->fields);
        $datav=DeliveryPersonWallet::where('delivery_person_id',$deliveryPersonId)->where('is_collectable',$request->is_collectable)->orderBy('id','DESC')->get();
        $newb=  $datav[0]->new_balance;
        if($request->is_collectable==0)
        {  
           $amt=-($request->amount);
           $newbal=  $newb-$amt;
           $data['new_balance']=$newbal;
           $data['delivery_charges_for_cust'] = $request->amount;
           $data['amount'] = 0; 
        }
        else
        {
           
           $amt=-($request->amount);
           $newbal=  $newb-$amt;
           $data['new_balance']=$newbal;
        }
         

        return [
            'success' => DeliveryPersonWallet::create($data)
        ];
    }
}
