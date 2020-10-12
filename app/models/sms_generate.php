<?php if (!defined('BASEPATH'))
exit('No direct script access allowed');

class sms_generate extends CI_Model {

  private $sd;

  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
  }

  public function base_details() {
   $a['type'] = 'SMS';
   return $a;
 }

 public function sendSmsForMessage(){

   $url= 'https://digitalreachapi.dialog.lk/refresh_token.php';         
          // DATA JASON ENCODED
   $data       = array("u_name" => "seethagroupapi_01", "passwd" => "seethagroupapi_011234");
   $data_json  = json_encode($data);

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);

   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // DATA ARRAY
   curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $response  = curl_exec($ch);
   $result = json_decode($response, true);

   if ($response === false) 
    $response = curl_error($ch);
  $result=$result['access_token'];
  $this->sendSms($result); 
  curl_close($ch);  

}
public function sendSms($result){
  // echo($result);
  $url= 'https://digitalreachapi.dialog.lk/camp_req.php';
        // DATA JASON ENCODED
  $data       = array(
    "msisdn" => "94778936829", 
    "channel" => "1",
    "mt_port" => "SeethaGroup",
    "s_time" => "2020-09-21 12:33:00",
    "e_time" => "2020-10-21 12:33:00",
    "msg" => "Dear Valued Customer, We Are Writing To LetYou Know About Our Recent Changes ",
    "callback_url" => "https://124.43.23.70:8080/seethal/index.php/main/smsgatewaycallback"
    );

  $data_json  = json_encode($data);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:'.$result));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // DATA ARRAY
  curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response  = curl_exec($ch);

  if ($response === false) 
    $response = curl_error($ch);
  echo stripslashes($response);
  curl_close($ch);       

}

}
