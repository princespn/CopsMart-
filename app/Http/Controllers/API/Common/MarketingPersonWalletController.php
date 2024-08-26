<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MarketingPersonWallet;
use DB;
class MarketingPersonWalletController extends Controller
{
    var $rules =[
        'amount' => 'required|numeric',
        'description' => 'required|string',
        'is_collectable' => 'required|boolean',
        'is_adjustment' => 'required|boolean',
        'marketing_person_id' => 'required|numeric',
    ];

    var $fields = [
                    'description',
                    'amount',
                    'is_collectable',
                    'marketing_person_id',
                    'is_adjustment',
                    'description',
    ];

    function getWalletTransaction($marketingPersonId){
        return MarketingPersonWallet::where('marketing_person_id', $marketingPersonId)->orderBy('created_at','DESC')->get();
    }

    function getWalletBalance($marketingPersonId){
        return DB::table('marketing_person_wallets')->where('marketing_person_id', $marketingPersonId)->selectRaw('SUM(amount) as total')->first()->total;
    }

    function getCollectableBalance($marketingPersonId){
        return DB::table('marketing_person_wallets')->where(['marketing_person_id' => $marketingPersonId, 'is_collectable' => true])->selectRaw('SUM(amount) as total')->first()->total;
    }

    function getBalance($marketingPersonId){
        $balance = $this->getWalletBalance($marketingPersonId);
        $collectable = $this->getCollectableBalance($marketingPersonId);

        return [
            'balance' => $balance,
            'collectable_balance' => $collectable,
            'total_payable' => $balance - $collectable,
        ];
    }

    function addWalletTransaction(Request $request, $marketingPersonId){
        $this->validate($request, $this->rules);
        return [
            'success' => MarketingPersonWallet::create($request->only($this->fields))
        ];
    }
}
