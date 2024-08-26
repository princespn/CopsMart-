<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;
class MstPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        return Package::select(['packages.*'])->get();
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
            'name' => 'required|string|max:191'
        ]);

        $package = new Package();
        $package->name = $request->name;
        $package->admin_id = $request->admin_id;
        $package->save();
        return [
            'message' => 'created Successfully',
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
        return Package::findOrFail($id);
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
        $package = Package::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
        ]);
        Package::findOrFail($id)->update(['name' => $request->name]);
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
        $package = Package::findOrFail($id);
        $package->delete();
        // delete the subCategory
        return [
            'message' => 'Package Deleted !'
        ];
    }
}
