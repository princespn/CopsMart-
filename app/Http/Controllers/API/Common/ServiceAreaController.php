<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ServiceArea;

class ServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ServiceArea::get();
    }

    public function service_area_test()
    {   
         $id=$_GET['ad'];//exit;
        return ServiceArea::join('users','users.service_id','=','service_areas.id')->where('users.id',$id)->select('service_areas.*')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required|min:2',
            'range' => 'required|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        $data = $request->only('name', 'range', 'latitude', 'longitude');
        return [
            'success' => ServiceArea::create($data) ? true : false
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
        return ServiceArea::findOrFail($id);
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
        $serviceArea = ServiceArea::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|min:2',
            'range' => 'required|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_active' => 'required|boolean'
        ]);
        $data = $request->only('name', 'range', 'latitude', 'longitude', 'is_active');
        return [
            'success' =>$serviceArea->update($data) ? true : false
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
        $serviceArea = ServiceArea::findOrFail($id);
        return [
            'success' => $serviceArea->delete()
        ];
    }
}
