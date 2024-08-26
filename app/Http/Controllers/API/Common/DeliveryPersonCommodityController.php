<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DeliveryPersonCommodity;
class DeliveryPersonCommodityController extends Controller
{

    var $rules = [
        'commodity_type_id' => 'required|string',
        'delivery_person_id' => 'required|email',
    ];

    var $fields = [ 'commodity_type_id' , 'delivery_person_id'];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($deliveryPersonId)
    {
        return DeliveryPersonCommodity::with('commodity')->where('delivery_person_id', $deliveryPersonId)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $deliveryPersonId)
    {
        $this->validate($request, [
            'commodity_type_id' => 'required|numeric|unique:delivery_person_commodities,commodity_type_id,NULL,NULL,delivery_person_id,' . $deliveryPersonId
        ]);
        return [
            'success' => DeliveryPersonCommodity::create(
                [
                    'delivery_person_id' => $deliveryPersonId,
                    'commodity_type_id' => $request->commodity_type_id
                ]
            )
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($deliveryPersonId, $id)
    {
        $commodity = DeliveryPersonCommodity::where('delivery_person_id', $deliveryPersonId)->findOrFail($id);
        return [
            'success' => $commodity->delete(),
        ];
    }
}
