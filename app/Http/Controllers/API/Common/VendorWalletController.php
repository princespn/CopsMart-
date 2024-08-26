<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VendorWallet;
use DB;
class VendorWalletController extends Controller
{
    var $rules =[
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
                    'description',
    ];

    function getWalletTransaction($vendorId){
        return VendorWallet::where('vendor_id', $vendorId)->orderBy('created_at','DESC')->get();
    }

    function getWalletBalance($vendorId){
        return DB::table('vendor_wallets')->where('vendor_id', $vendorId)->selectRaw('SUM(amount) as total')->first()->total;
    }

    function getCollectableBalance($vendorId){
        return DB::table('vendor_wallets')->where(['vendor_id' => $vendorId, 'is_collectable' => true])->selectRaw('SUM(amount) as total')->first()->total;
    }

    function getBalance($vendorId){
        $balance = $this->getWalletBalance($vendorId);
        $collectable = $this->getCollectableBalance($vendorId);

        return [
            'balance' => $balance,
            'collectable_balance' => $collectable,
            'total_payable' => $balance - $collectable,
        ];
    }

    function addWalletTransaction(Request $request, $vendorId){
        $this->validate($request, $this->rules);
        
        $data=$request->only($this->fields);
        $datav=VendorWallet::where('vendor_id',$data['vendor_id'])->orderBy('id','DESC')->get();
        if(count($datav)>0)
        {
           $newb=  $datav[0]->new_balance;
           $amt=-($data['amount']);
           $newbal=  $newb-$amt;
          $data['new_balance']=$newbal;
          $array=array(
              'amount'=>$data['amount'],
              'description'=>$data['description'],
              'is_collectable'=>$data['is_collectable'],
              'is_adjustment'=>$data['is_adjustment'],
               'vendor_id'=>$data['vendor_id'],
              'new_balance'=>$newbal,
              );
          // exit;
          
        }
        return [
            'success' => VendorWallet::create($array)
         ];
        //  print_r($datav);
        //  exit;
        
    }
}
