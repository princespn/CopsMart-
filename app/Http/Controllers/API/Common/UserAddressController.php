<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserAddress;
use App\ServiceArea;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userAddress($id)
    {
         
         $id= UserAddress::where(['user_id'=>$id, 'is_active'=>1])->get();
        if(count($id)>0)
        {
             return $id;
        }
        else
        {
            return ['status'=>201,'msg'=>'No data found'];
        }
    }
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'user_id' => 'required|numeric',
            'name' => 'string|max:191',
            'address' => 'required|max:191',
            'mobile' => 'required|numeric',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'pincode' => 'required|numeric',
            'district' => 'required|numeric',
            'state' => 'required|numeric',
            'title' => 'required|numeric',
        ]);
        $data = $request->only('name', 'address', 'mobile', 'lat', 'long', 'pincode', 'district', 'state','title','user_id');
       return [ 'success' => UserAddress::create($data) ? true : false];
    }
    public function saveUsAddress(Request $request)
    {
       
         $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'pincode' => 'required',
            'district' => 'required',
            'state' => 'required',
            'title' => 'required',
             'is_default' => 'required',
        ]);
        $data = $request->only('name', 'address', 'mobile', 'lat', 'long', 'pincode', 'district', 'state','title','user_id','is_default');
       return [ 'success' => UserAddress::create($data) ? true : false];
    }
    
    public function DeleteAddress($id)
    {
         $address = UserAddress::findOrfail($id);
        return  [ 'success' => $address->delete() ];
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return UserAddress::findOrfail($id);
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
        $address = UserAddress::findOrfail($id);
        $this->validate($request, [
            'name' => 'string|max:191',
            'address' => 'required|max:191',
            'mobile' => 'required|numeric',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'pincode' => 'required|numeric',
            'district' => 'required|numeric',
            'state' => 'required|numeric',
            'title' => 'required|numeric',
        ]);
        $data = $request->only('name', 'address', 'mobile', 'lat', 'long', 'pincode', 'district', 'state','title');
        return ['success' =>  $address->update($data)];
    }
    
    public function AddressupUpdate(Request $request, $id)
    {
         $address = UserAddress::findOrfail($id);
        //  print_r($address);
        //  exit;
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'pincode' => 'required',
            'district' => 'required',
            'state' => 'required',
            'title' => 'required',
        ]);
        $data = $request->only('name', 'address', 'mobile', 'lat', 'long', 'pincode', 'district', 'state','title');
        return ['success' =>  $address->update($data)];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = UserAddress::findOrfail($id);
        return  [ 'success' => $address->delete() ];
    }

    public function setDefault($id){
        $user_address = UserAddress::findOrFail($id);
        if($user_address)
            UserAddress::where(['user_id'=>$user_address->user_id])->update(['is_default'=>0]);
        $user_address->is_default = true;
        return [
            'success' => $user_address->save()
        ];
    }

    public function getDefault($user_id){
        $user_address = UserAddress::where(['user_id' => $user_id, 'is_default'=>1])->first();
        if(!$user_address){
            $user_address = UserAddress::where('user_id',$user_id)->first();
        }
        // print_r($user_address);
        if(isset($user_address))
        {
           return $user_address;
            $response['resid']='200';
        }
        else
        
        {  
             $response['msg']='No Data Found';
            $response['resid']='201';
            return  $response;
            
           
         }
         
       
    }
    
     public function getDefaultt($user_id){
        $user_address = UserAddress::where(['user_id' => $user_id, 'is_default'=>1])->first();
        if(!$user_address){
            $user_address = UserAddress::where('user_id',$user_id)->first();
        }
        print_r($user_address);
        if(isset($user_address))
        {
            $response['user'][]=$user_address;
            $response['resid']='200';
        }
        else
        
        {  
             $response['user']=[];
            $response['resid']='201';

        }
         return $response;
       
    }
    
    public function saveUserAddress(Request $request){
        $validator = \Validator::make($request->all(), ['row_id'=>'required|numeric','user_id'=>'required|numeric','address'=>'required','landmark'=>'required','latitude'=>'required|numeric','longitute'=>'required|numeric']);
        if ($validator->fails()) {
           foreach($validator->errors()->toArray() as $errorvalue){
                foreach ($errorvalue as $key => $value) {
                    return response()->json(['error_message'=>$value], 422);
                }
            }
        }

        $user_address = UserAddress::where(['id' => $request->row_id])->first();
        $latitude = $request->latitude;
        $longitude= $request->longitute;
        $get_distance = ServiceArea::selectRaw("*,
                             ( 6371 * acos( cos( radians(?) ) *
                               cos( radians( latitude ) )
                               * cos( radians( longitude) - radians(?)
                               ) + sin( radians(?) ) *
                               sin( radians( latitude ) ) )
                             ) AS distance", [$latitude, $longitude, $latitude])
                //->having('distance','<=','range/1000')
                ->orderBy('distance')
                ->first();
        //print_r($get_distance);exit;
        if(empty($get_distance))
        {
            $service_area_id = 0;
        }
        else{
           $service_area_id = $get_distance->id; 
        }
            
        
        //print_r($service_area_id);exit;
        if(!$user_address)
        {  
            $userAddress = new UserAddress();
            $userAddress->user_id = $request->user_id;
            $userAddress->address = $request->address;
            $userAddress->landmark = $request->landmark;
            $userAddress->lat = $request->latitude;
            $userAddress->long = $request->longitute;
            $userAddress->service_area_id = $service_area_id;
            $userAddress->save();
            $message = "save successfully";
        }
        else{
            UserAddress::where(['id'=>$request->row_id])->update(['address'=>$request->address,'landmark'=>$request->landmark,'lat'=>$request->latitude,'long'=>$request->longitute,'service_area_id'=>$service_area_id]);
            $message = "updated successfully";
        }

        return [
            'success' => $message
        ];
    }

    public function getUserAddress($id){
        if(!empty($id))
        {
            $user_address = UserAddress::where(['user_id' => $request->user_id])->get();
            return [
                'success' => $user_address
            ];
        }
        else{
            return [
                'success' => 'Required user id'
            ];
        }
    }
}
