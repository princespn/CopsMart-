<?php
namespace App\Utilities;

class SmsUtility {

    private $key = 'key';

    public function sendMessage( $to, $msg, $type = 0 ){
        // type = 0 prmotional and 1 = transactional
        // gateway route 1 = promotional , 4 = Transactional

        if($type == 1){
            $tmpid =1207161520451035511;
        }elseif($type == 2){
            $tmpid =1207161546374731133;
        }
        // echo $tmpid;
        // exit;
        $routeId = 6;
        $msg = urlencode($msg);
        $list= '';
        $total = count($to);
        $total--;
        foreach ($to as $key => $no) {
            $list .=  $key == $total ? $no : $no .',';
        }

        return $this->callSmsApi($list, $msg, $routeId,$tmpid);
    }

    private function callSmsApi($to, $msg, $routeId,$tmp){
        
        //echo "http://whysms.in/app/smsapi/index.php?key=56048A336760BF&campaign=10241&routeid=".$routeId."&type=text&contacts=".$to."&senderid=OCNOTP&msg=".$msg."&template_id=".$tmp;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://whysms.in/app/smsapi/index.php?key=56048A336760BF&campaign=10241&routeid=".$routeId."&type=text&contacts=".$to."&senderid=OCNOTP&msg=".$msg."&template_id=".$tmp);
        //curl_setopt($ch, CURLOPT_URL, "http://sms.cluesys.com/api/sendhttp.php?authkey=14033Adfa7rHB5dbad2bd&mobiles=$to&message=$msg&sender=OCNOTP&route=63&country=91");
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //$body = '{}';
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

         $response = curl_exec($ch);
        //dd($response);
        $err = curl_error($ch);
        if ($err) {
			//echo "cURL Error #:" . $err;
			return false;
		} else {
            return $response = json_decode($response);
            // if(isset($response->type) && $response->type == 'success')
			//     return true;
		}

    }

    public function forgetpassword($no,$randno)
    {     
          $from = 'IMTRON';
          //Submit to server
          $message='One Time Password to verify a user is '.$randno.' Team Rupaid - Mechatron Techgear Pvt. Ltd.';
          $ch = curl_init();
          curl_setopt($ch,CURLOPT_URL, "http://bhashsms.com/api/sendmsg.php");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, "user=TEAM_MHOURZ&pass=MECHATRON&sender=IMTRON&phone=".$no."&text=".$message."&priority=ndnd&stype=normal");
          $response = curl_exec($ch);
           return $response = json_decode($response);
    }


}
