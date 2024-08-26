<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VerificationCode;
use App\Utilities\SmsUtility;

class VerificationCodeController extends Controller
{
    function resendCode($id){
        $verification = VerificationCode::findOrFail($id);
        $message = 'Your one time password to use in JUSTALO is '.$verification->code;
       
        $sms = new SmsUtility;
        $sms->sendmessage([$verification->mobile], $message, 1);
        return [
            'verification_id'=>$id,
            'message' => 'OTP resent successfully'
        ];
    }
}
