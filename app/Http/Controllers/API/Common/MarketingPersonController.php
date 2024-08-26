<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utilities\SmsUtility;
use App\MarketingPerson;
use App\Coupon;
use App\MarketingAppVersionHash;
use App\VerificationCode;
class MarketingPersonController extends Controller
{
    var $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'mobile' => 'required|numeric|digits:10',
        'driving_license_no' => 'sometimes|nullable',
        'aadhar_no' => 'sometimes|nullable|numeric|min:12',
        'is_active' => 'sometimes|nullable|boolean'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MarketingPerson::get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $data = $request->only('name', 'mobile', 'email', 'aadhar_no', 'is_active', 'driving_license_no');
        return  ['success' => MarketingPerson::create($data) ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MarketingPerson::findOrFail($id);
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
        $marketingPerson = MarketingPerson::findOrFail($id);
        $this->validate($request, $this->rules);
        $data = $request->only('name', 'mobile', 'email', 'aadhar_no', 'is_active', 'driving_license_no');
        return  ['success' => $marketingPerson->update($data) ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marketingPerson = MarketingPerson::findOrFail($id);
        return  ['success' => $marketingPerson->delete() ];
    }


    public function logon(Request $request){
        $this->validate($request, [
            'mobile' => 'required|numeric|min:5',
            'app_version' => 'sometimes|numeric',
        ]);
        $otp = rand(10000,99999);
        // $otp = 12345;
        $message = 'Your one time password to use in JUSTALO is '.$otp;
        $appVersion = isset($request->app_version) ? $request->app_version : 1;

        $hash = MarketingAppVersionHash::where('version_code', $appVersion)->first();

        if($hash){
            $message.= ' '.$hash->hash;
        }
        $data = [
            'mobile' => $request->mobile,
            'code' => $otp
        ];
        $id = VerificationCode::create($data)->id;
        $sms = new SmsUtility;
        $sms->sendmessage([$request->mobile], $message, 1);
        $id = VerificationCode::create($data)->id;
        return [
            'verification_id'=>$id,
            'message' => 'OTP generated successfully'
        ];
    }

    public function verifyLogonOTP(Request $request){
        $this->validate($request, [
            'verification_id' => 'required|numeric',
            'otp' => 'required|numeric|min:5',
            'device_token' => 'sometimes|string'
        ]);
        $verification = VerificationCode::where(['id' => $request->verification_id, 'code'=>$request->otp])->first();
        $response =[];
        $response['success'] = false;
        if($verification){
            $response["registered"] = false;
            $response['success'] = true;
            $marketingPerson = MarketingPerson::where(['mobile'=>$verification->mobile, 'is_active'=> 1])->first();
            if($marketingPerson){
                if($request->only('device_token'))
                    MarketingPersonDeviceToken::updateOrCreate(['marketing_person_id' =>$marketingPerson->id, 'token' => $request->device_token]);
                $response['marketing_person'] = $marketingPerson;
                $response['coupon'] = Coupon::where('marketing_person_id', $marketingPerson->id)->first();
                $response['registered'] = true;
            }
        }
        return $response;
    }

    public function couponByMarkeingPerson($marketingPersonId){
        return Coupon::where('marketing_person_id', $marketingPersonId)->where('is_active', 1)->get();
    }


public function getCouponByCategory($categoryId){
        $qparam = [];
        if($categoryId>0){
            array_push($qparam, ['category_id','=',$categoryId]);
            array_push($qparam, ['is_active','=',1]);
        }else{
            array_push($qparam, ['is_active','=',1]);
        }
        return Coupon::where($qparam)->get();
    }




}
