<?php

namespace App\Utilities;

class FirebaseCloudMessagingUtility {

    private $title;

    //notification message
    private $message;
    private $image;

//if $notiid =1 for delivery 2= vendor 3 customer 

    //initializing values in this constructor
    function __construct($title='', $message='', $image ='') {
         $this->title = $title;
         $this->message = $message;
         $this->image = $image;

        // if($this->image ==''){
        //     $this->image = 'https://feedu.in/logo.png';
        // }
    }

    //getting the push notification
    public function getPush() {
        $res = array();
        $res['title'] = $this->title;
        $res['body'] = $this->message;
        $res['sound'] = 'default';
        if($this->image != '')
            $res['image'] = $this->image;
        return $res;
    }

    public function send($registration_ids,$notiid) {
        $message = $this->getPush();
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
           // 'notification' => $message
        );
        return $this->sendPushNotification($fields,$notiid);
    }

    public function sendData($registration_ids, $data = [],$notiid){
        $message = $this->getPush();
      
        $message = array_merge($message, $data);
        //     
        foreach ($registration_ids as $key => $reg) {
            $this->push_notification_android($reg,$message,$notiid);
        }
        // return $this->sendPushNotification($fields);
    }

    /*
    * This function will make the actuall curl request to firebase server
    * and then the message is sent
    */
    private function sendPushNotification($fields,$notiid) {

        //firebase server url to send the curl request
        $url = 'https://fcm.googleapis.com/fcm/send';
    //   if($notiid==1)
    //     {
    //         $key="Authorization: key=AAAAungMAvc:APA91bHK9SPsTXKDhGY6ji9ynhXx5chxyxxUfCFOz2feukOuLVVC9lgNlHzO5isL1LGedy-w9Niov3shK8lYZs6qyZn4w9koZ-82YoNlhnia-xjWgnXAGBV1vq2FbNcbNbFEUjh81nXA";
    //     }
    //     elseif($notiid==2)
    //     {
    //         $key="Authorization: key=AAAAungMAvc:AAAAq8v40FM:APA91bG9ILbnE2yJu5zXmKxJxfi1C96eZJl8iAEEaDQGjHcMccyDBc9CHpzU2tbALW2lzX0H8nRKbDW8vCbAwpKuQXYZytbkEg7Ik6oGjbJiD1QRknm_eaZ9FzfNTRW6BI3dXMQY8FMd";
    //     }
    //     elseif($notiid==3)
    //     {
    //         $key="Authorization: key=AAAAwRLqOnI:APA91bGmhh01nJ9n0MIRaeTbxNeAltMx9_TIaCSk8hBPaNyHDSVtjeAM0D8Qy_GlgN7Y3SdnKu50QIuWuUq4RrP5nD4vftcX5jS-aDLc8Ava4HdfnieeDaK1xfYDiHtutGjVqkCzYdwP";
    //     }
    
    $key="Authorization: key=AAAA-JLzNpg:APA91bHJzFDVDmPZaHQh8usjkzEPmO86GRwS9EtvSemRy8oushCD0bhH67_2DMW3NrG75JWwupJl_VTbxgUcl3ty7CBNEJu6qAbZMKMNj5UgTr39eSzpCHpnv4doLbInc4ZQtqnDq5_8";
        //building headers for the request
        $headers = array(
            $key,
            'Content-Type: application/json'
        );

        // $headers = array(
        //     'Authorization: key= AAAAmUH96IE:APA91bENTicwfGyXds_aTD_VoAXXOmbM5eP_hek9Q0g-vqbQ1cGSIOWMqKoZ6Eap9tZKpNyE3ANa5uG2lycF4QIxL1bshNpOWZEDeGMbifpQ2yOOnuLj08SZCJQ_1IACxoO4XriZiD6E',
        //     'Content-Type: application/json'
        // );
        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        //var_dump($fields);exit;

        //finally executing the curl request
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);

        //and return the result
        return $result;
        /* result = {"multicast_id":8800448952945951127,"success":1,"failure":0,"canonical_ids":0
        ,"results":[{"message_id":"0:1542866431476424%95cc4e50f9fd7ecd"}]}[] */
    }

    function push_notification_android($registrationIds,$message,$notiid){
        
        $fields = array
        (
            'to'=> $registrationIds,
            //'notification'    => $message,
            'data'  => $message
        );
        
        
        // if($notiid==1)
        // {
        //     $key="Authorization: key=AAAAungMAvc:APA91bHK9SPsTXKDhGY6ji9ynhXx5chxyxxUfCFOz2feukOuLVVC9lgNlHzO5isL1LGedy-w9Niov3shK8lYZs6qyZn4w9koZ-82YoNlhnia-xjWgnXAGBV1vq2FbNcbNbFEUjh81nXA";
        // }
        // elseif($notiid==2)
        // {
        //     $key="Authorization: key=AAAAq8v40FM:APA91bG9ILbnE2yJu5zXmKxJxfi1C96eZJl8iAEEaDQGjHcMccyDBc9CHpzU2tbALW2lzX0H8nRKbDW8vCbAwpKuQXYZytbkEg7Ik6oGjbJiD1QRknm_eaZ9FzfNTRW6BI3dXMQY8FMd";
        // }
        // elseif($notiid==3)
        // {
        //     $key="Authorization: key=AAAAwRLqOnI:APA91bGmhh01nJ9n0MIRaeTbxNeAltMx9_TIaCSk8hBPaNyHDSVtjeAM0D8Qy_GlgN7Y3SdnKu50QIuWuUq4RrP5nD4vftcX5jS-aDLc8Ava4HdfnieeDaK1xfYDiHtutGjVqkCzYdwP";
        // }
        
        $key="Authorization: key=AAAA-JLzNpg:APA91bHJzFDVDmPZaHQh8usjkzEPmO86GRwS9EtvSemRy8oushCD0bhH67_2DMW3NrG75JWwupJl_VTbxgUcl3ty7CBNEJu6qAbZMKMNj5UgTr39eSzpCHpnv4doLbInc4ZQtqnDq5_8";
        $headers = array
                (
                    $key,
                    'Content-Type: application/json'
                );
            #Send Reponse To FireBase Server
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch);
            //print_r($result);exit;
            #Echo Result Of FireBase Server
           // echo $result;
            if ($result === FALSE) {
                die('FCM Send Error:' . curl_error($ch));
            }
            // curl_close($ch);
            return $result;
            curl_close( $ch );
    }
}
?>
