<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_payment_option extends CI_Model {

  private $sd;
  private $max_no; 
  private $trans_code;

  function __construct(){
  	parent::__construct();
  	$this->sd = $this->session->all_userdata();
  	$this->load->database($this->sd['db'], true);
  }

  public function save_credit_card($trans_code,$trans_no,$card_type,$card_no,$amount,$amount_rate,$rate,$month,$bank,$merchant,$acc_no,$date,$ex_date){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "card_type"=>$card_type,
      "card_no"=>$card_no,
      "amount"=>$amount,
      "int_amount"=>$amount_rate,
      "rate"=>$rate,
      "month"=>$month,
      "expire_date"=>$ex_date,
      "bank_id"=>$bank,
      "merchant_id"=>$merchant,  
      "acc_code"=>$acc_no,   
      "date"=>$date   
    );
    $this->db->insert("opt_credit_card_det",$data);
  }

  public function save_credit_note($trans_code,$trans_no,$cn_no,$amount,$balance,$settled){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "cn_no"=>$cn_no,
        //"ddate"=>$ddate,
      "amount"=>$amount,
      "balance"=>$balance,
      "settled"=>$settled
    );
    $this->db->insert("opt_credit_note_det",$data);
  }



  public function save_debit_note($trans_code,$trans_no,$dn_no,$amount,$balance,$settled){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "dn_no"=>$dn_no,
      //"ddate"=>$date,
      "amount"=>$amount,
      "balance"=>$balance,
      "settled"=>$settled
    );
    $this->db->insert("opt_debit_note_det",$data);
  }

  public function save_bank_debit($trans_code,$trans_no,$code,$name,$amount){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "code"=>$code,
      "name"=>$name,
      "amount"=>$amount
    );

    $this->db->insert("opt_bank_debit_det",$data);
  }


  public function save_gift_voucher($trans_code,$trans_no,$vou_no,$issued_date,$amount,$g_code){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "vou_no"=>$vou_no,
      "issued_date"=>$issued_date,
      "code"=>$g_code,
      "amount"=>$amount
    );

    $this->db->insert("opt_gift_voucher_det",$data);
  }

  public function update_gift_serial($trans_code,$trans_no,$serial,$gft_item){
    $data=array(
      "settled_cl"=>$this->sd['cl'],
      "settled_bc"=>$this->sd['branch'],
      "settled_trans_code"=>$trans_code,
      "settled_trans_no"=>$trans_no,
    );
    $this->db->where("serial_no",$serial);
    $this->db->where("item",$gft_item);
    $this->db->update("g_t_serial",$data);
  }

  public function save_advance($trans_code,$trans_no,$advance_no,$ddate,$amount,$balance,$settle){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "advance_no"=>$advance_no,
      "ddate"=>$ddate,
      "amount"=>$amount,
      "balance"=>$balance,
      "settle"=>$settle
    );
    $this->db->insert("opt_advance_pay_det",$data);
  }

  public function save_issue_cheque($trans_code,$trans_no,$bank,$description,$cheque_no,$amount,$cheque_date,$cheque_book){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "bank"=>$bank,
      "description"=>$description,
      "cheque_no"=>$cheque_no,
      "amount"=>$amount,
      "cheque_date"=>$cheque_date,
      "cheque_book"=>$cheque_book
    );
    $this->db->insert("opt_issue_cheque_det",$data);
  }

  public function save_issue_cheque_book_det($realize_date,$amount,$ddate,$trans_code,$trans_no,$chq_no,$chq_book,$payee){
    $data=array(
      "status"=>1,
      "ddate"=>$realize_date,
      "amount"=>$amount,
      "status_date"=>$ddate,
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "payee" =>$payee
    );
    
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("nno", $chq_book);
    $this->db->where("cheque_no", $chq_no);
    $this->db->update("t_cheque_book_det",$data);
  }

  public function save_t_cheque_issued($table,$t_code,$t_no,$bank,$branch,$acc,$chq_no,$amount,$b_date,$status,$to_acc,$ddate){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$t_code,
      "trans_no"=>$t_no,
      "bank"=>$bank,
      "branch"=>$branch,
      "account"=>$acc,
      "cheque_no"=>$chq_no,
      "amount"=>$amount,
      "bank_date"=>$b_date,
      "status"=>$status,
      "issued_to_acc"=>$to_acc,
      "ddate" =>$ddate
    );

    $this->db->insert($table,$data);
  }

  public function update_cheque_trans($table,$bank,$branch,$t_acc,$status,$t_code,$chq_no,$bank_date,$t_no,$cr,$dr,$account){
    $data=array(
      "cl"        => $this->sd['cl'],
      "bc"        => $this->sd['branch'],
      "bank"      => $bank,
      "branch"    => $branch,
      "trans_acc" => $t_acc,
      "status"    => $status,
      "trans_code"=> $t_code,
      "cheque_no" => $chq_no,
      "bank_date" => $bank_date,
      "oc"        => $this->sd['oc'],
      "trans_no"  => $t_no,
      "cr"        => $cr,
      "dr"        => $dr,
      "account"   => $account
    );

    $this->db->insert($table,$data);
  }  


  public function save_priviledge_card($trans_code,$trans_no,$card_no,$available,$redeem){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "card_no"=>$card_no,
      "available"=>$available,
      "redeem"=>$redeem
    );
    $this->db->insert("opt_privilege_card_det",$data);
  }



  public function save_receive_cheque($trans_code,$trans_no,$bank,$branch,$account_no,$cheque_no,$amount,$cheque_date){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "bank"=>$bank,
      "branch"=>$branch,
      "account_no"=>$account_no,
      "cheque_no"=>$cheque_no,
      "amount"=>$amount,
      "cheque_date"=>$cheque_date
    );
    $this->db->insert("opt_receive_cheque_det",$data);
  }

  public function save_post_dated_cheque($trans_code,$trans_no,$bank,$branch,$account_no,$cheque_no,$amount,$cheque_date){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "bank"=>$bank,
      "branch"=>$branch,
      "account_no"=>$account_no,
      "cheque_no"=>$cheque_no,
      "amount"=>$amount,
      "cheque_date"=>$cheque_date
    );
    $this->db->insert("opt_post_dated_cheque_det",$data);
  }


  public function update_cheque_status($bank,$branch,$acc,$chq_no){
    $data=array("status"=>"R");
    $this->db->where("bank",$bank);
    $this->db->where("branch",$branch);
    $this->db->where("account",$acc);
    $this->db->where("cheque_no",$chq_no);
    $this->db->update("t_receipt_temp_cheque_det",$data);
  }

  public function save_t_cheque_received($table,$t_code,$t_no,$bank,$branch,$acc,$chq_no,$amount,$b_date,$status,$from_acc,$ddate){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$t_code,
      "trans_no"=>$t_no,
      "bank"=>$bank,
      "branch"=>$branch,
      "account"=>$acc,
      "cheque_no"=>$chq_no,
      "amount"=>$amount,
      "bank_date"=>$b_date,
      "status"=>$status,
      "received_from_acc"=>$from_acc,
      "ddate" =>$ddate
    );

    $this->db->insert($table,$data);
  }


  public function save_install_payment_trans(){
    if(isset($_POST['install_pay']) && !empty($_POST['install_pay'])){
      $split_install_pay_record = explode(",",$_POST['install_pay']); 
    }else{
      $split_install_pay_record = 2;
    } 

    if($split_install_pay_record != 2){
      for($i = 0; $i < count($split_install_pay_record); $i++){
        $row=explode("~", $split_install_pay_record[$i]);
        if($row[2]!=0){
          $t_ins_schedule[]=array(
           "cl"=>$this->sd['cl'],
           "bc"=>$this->sd['branch'],
           "trans_no"=> $this->max_no,
           "agr_no"=>$this->max_no,
           "ins_no"=>$row[0],
           "due_date"=>$row[1],
           "ins_amount"=>$row[2],
           "ins_paid"=>0,
           "capital_amount"=>$row[3],
           "capital_paid"=>0,
           "int_amount"=>$row[4],
           "int_paid"=>0,
           "penalty_amount"=>0,
           "penalty_paid"=>0,
         );
        }

        $sub_cl=$this->sd['cl'];
        $sub_bc=$this->sd['branch'];


        if($row[2]!="0.00"){
          $this->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],$_POST['type'],$this->max_no,'25',$this->max_no,$row[2],"0",$this->max_no,$row[1],$row[0],$_POST['type'],$sub_cl,$sub_bc); 
        }
        if($row[3]!="0.00"){
          $this->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],$_POST['type'],$this->max_no,'26',$this->max_no,$row[3],"0",$this->max_no,$row[1],$row[0],$_POST['type'],$sub_cl,$sub_bc);
        }
        if($row[4]!="0.00"){
          $this->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],$_POST['type'],$this->max_no,'27',$this->max_no,$row[4],"0",$this->max_no,$row[1],$row[0],$_POST['type'],$sub_cl,$sub_bc);
        }
      }  
    }

    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
      if(isset($t_ins_schedule)){ if(count($t_ins_schedule)){ $this->db->insert_batch("t_ins_schedule", $t_ins_schedule);}}
    }else{
      $this->set_delete();
      if(isset($t_ins_schedule)){ if(count($t_ins_schedule)){ $this->db->insert_batch("t_ins_schedule", $t_ins_schedule);}}
    }  
  }


  public function set_delete(){
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_no", $this->max_no);
    $this->db->delete("t_ins_schedule");
  }

  public function delete_gift_voucher_settled($trans_code,$trans_no){
    $update_data = array(
      'settled_cl' =>"",
      'settled_bc' =>"", 
      'settled_trans_code' =>"",
      'settled_trans_no' =>"" 
    );
    $this->db->where("settled_cl",$this->sd['cl']);
    $this->db->where("settled_bc",$this->sd['branch']);
    $this->db->where("settled_trans_code",$trans_code);
    $this->db->where("settled_trans_no",$trans_no);
    $this->db->update("g_t_serial",$update_data);
  }

  public function update_pd_chq_status($trans_code,$trans_no){
    $sql="SELECT * FROM opt_post_dated_cheque_det
    WHERE trans_code='$trans_code'
    AND trans_no='$trans_no'
    AND cl='".$this->sd['cl']."'
    AND bc='".$this->sd['branch']."'";
    $query=$this->db->query($sql);

    foreach($query->result() as $row){
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("bank",$row->bank);
      $this->db->where("branch",$row->branch);
      $this->db->where("account",$row->account_no);
      $this->db->where("cheque_no",$row->cheque_no);
      $this->db->update("t_receipt_temp_cheque_det",array("status"=>"P"));
    }
  }

  public function delete_all_payments_opt($trans_code,$trans_no){
   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_credit_card_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_credit_note_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_debit_note_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_bank_debit_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_gift_voucher_det");

   $update_data = array(
    'settled_cl' =>"",
    'settled_bc' =>"", 
    'settled_trans_code' =>"",
    'settled_trans_no' =>"" 
  );
   $this->db->where("settled_cl",$this->sd['cl']);
   $this->db->where("settled_bc",$this->sd['branch']);
   $this->db->where("settled_trans_code",$trans_code);
   $this->db->where("settled_trans_no",$trans_no);
   $this->db->update("g_t_serial",$update_data);

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_advance_pay_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_issue_cheque_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_privilege_card_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_receive_cheque_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("opt_post_dated_cheque_det");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("t_cheque_received");

   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete("t_cheque_issued");

   $update_chq_book = array(
    'trans_code'  => 0, 
    'trans_no'    => 0, 
    'status_date' => "", 
    'amount'      => "0.00", 
    'ddate'       => "", 
    'status'      => 0
  );
   $this->db->where('cl', $this->sd['cl']);
   $this->db->where("bc", $this->sd['branch']);
   $this->db->where("trans_code", $trans_code);
   $this->db->where("trans_no", $trans_no);
   $this->db->update("t_cheque_book_det",$update_chq_book);

 }


 public function save_payment_option($max_no,$trans_code){
  $this->trans_code=$trans_code;
  $this->max_no=$max_no;
  if($this->trans_code=="19"){
    $sql_pay="SELECT name FROM m_supplier
    WHERE code='".$_POST['supplier_id']."'";
  }  

  if($this->trans_code=="48"){
    $sql_pay="SELECT description FROM m_account
    WHERE code='".$_POST['0_0']."'";
  }

  if($this->trans_code=="131"){
    $sql_pay="SELECT description FROM m_account
    WHERE code='".$_POST['acc_code']."'";
  }    


  for($x=0;$x<25;$x++){
    if(isset($_POST['type1_'.$x]) && !empty($_POST['type1_'.$x])){ 
     if(isset($_POST['no1_'.$x]) && !empty($_POST['no1_'.$x])){ 
       if(isset($_POST['amount1_'.$x]) && !empty($_POST['amount1_'.$x])){
                //var_dump($_POST['amount_rate1_'.$x]);
         $this->save_credit_card($this->trans_code,$this->max_no,$_POST['type1_'.$x],$_POST['no1_'.$x],$_POST['amount1_'.$x],$_POST['amount_rate1_'.$x],$_POST['rate1_'.$x],$_POST['month1_'.$x],$_POST['bank1_'.$x],$_POST['merchant1_'.$x],$_POST['acc1_'.$x],$_POST['date'],$_POST['exdate_'.$x]);  
       }
     } 
   }

   if(isset($_POST['no2_'.$x]) && !empty($_POST['no2_'.$x])){ 
     if(isset($_POST['amount2_'.$x]) && !empty($_POST['amount2_'.$x])){ 
       if(isset($_POST['balance2_'.$x]) && !empty($_POST['balance2_'.$x])){
        if(isset($_POST['settle2_'.$x]) && !empty($_POST['settle2_'.$x])){
          $this->save_credit_note($this->trans_code,$this->max_no,$_POST['no2_'.$x],$_POST['amount2_'.$x],$_POST['balance2_'.$x],$_POST['settle2_'.$x]);                                      
          $this->load->model('trans_settlement');
          $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['acc_codes'],$_POST['date'],$_POST['t_code_'.$x],$_POST['no2_'.$x],$this->trans_code,$_POST['id'],"0",$_POST['settle2_'.$x]);
        }
      }
    } 
  }

  if(isset($_POST['no3_'.$x]) && !empty($_POST['no3_'.$x])){ 
   if(isset($_POST['amount3_'.$x]) && !empty($_POST['amount3_'.$x])){ 
     if(isset($_POST['balance3_'.$x]) && !empty($_POST['balance3_'.$x])){
      if(isset($_POST['settle3_'.$x]) && !empty($_POST['settle3_'.$x])){
       $this->save_debit_note($this->trans_code,$this->max_no,$_POST['no3_'.$x],$_POST['amount3_'.$x],$_POST['balance3_'.$x],$_POST['settle3_'.$x]);                                      
       $this->load->model('trans_settlement');
       $this->trans_settlement->save_settlement("t_debit_note_trans",$_POST['acc_codes'],$_POST['date'],$_POST['t_code3_'.$x],$_POST['no3_'.$x],$this->trans_code,$_POST['id'],"0",$_POST['settle3_'.$x]);
     }
   }
 } 
}

if(isset($_POST['code4_'.$x]) && !empty($_POST['code4_'.$x])){ 
 if(isset($_POST['amount4_'.$x]) && !empty($_POST['amount4_'.$x])){ 
  $this->save_bank_debit($this->trans_code,$this->max_no,$_POST['code4_'.$x],$_POST['name4_'.$x],$_POST['amount4_'.$x]);
} 
}

if(isset($_POST['type5_'.$x]) && !empty($_POST['type5_'.$x])){ 
  if(isset($_POST['no5_'.$x]) && !empty($_POST['no5_'.$x])){ 
    if(isset($_POST['amount5_'.$x]) && !empty($_POST['amount5_'.$x])){
     $this->save_gift_voucher($this->trans_code,$this->max_no,$_POST['type5_'.$x],$_POST['no5_'.$x],$_POST['amount5_'.$x],$_POST['gcode5_'.$x]);                                        
     $this->update_gift_serial($this->trans_code,$this->max_no,$_POST['type5_'.$x],$_POST['gcode5_'.$x]); 
   }
 } 
}

if(isset($_POST['no6_'.$x]) && !empty($_POST['no6_'.$x])){ 
 if(isset($_POST['amount6_'.$x]) && !empty($_POST['amount6_'.$x])){ 
   if(isset($_POST['balance6_'.$x]) && !empty($_POST['balance6_'.$x])){
    if(isset($_POST['cdate6_'.$x]) && !empty($_POST['cdate6_'.$x])){
     $this->save_advance($this->trans_code,$this->max_no,$_POST['no6_'.$x],$_POST['date6_'.$x],$_POST['amount6_'.$x],$_POST['balance6_'.$x],$_POST['cdate6_'.$x]);
   }
 }
} 
}

if(isset($_POST['bank7_'.$x]) && !empty($_POST['bank7_'.$x])){ 
 if(isset($_POST['chqu7_'.$x]) && !empty($_POST['chqu7_'.$x])){ 
   if(isset($_POST['amount7_'.$x]) && !empty($_POST['amount7_'.$x])){
    if(isset($_POST['cdate7_'.$x]) && !empty($_POST['cdate7_'.$x])){
      $this->save_issue_cheque($this->trans_code,$this->max_no,$_POST['bank7_'.$x],$_POST['des7_'.$x],$_POST['chqu7_'.$x],$_POST['amount7_'.$x],$_POST['cdate7_'.$x],$_POST['chq7_'.$x]);
      if($this->trans_code=="19"){
        $to_acc = $_POST['supplier_id']; 
        $to_payee=$this->db->query($sql_pay)->first_row()->name; 
      }
      if($this->trans_code=="48"){
        $to_acc = $_POST['0_0']; 
        $to_payee=$this->db->query($sql_pay)->first_row()->description;  
      }  

      if($this->trans_code=="131"){
        $to_acc = $_POST['acc_code']; 
        $to_payee=$this->db->query($sql_pay)->first_row()->description;  
      }                            
      $this->save_t_cheque_issued('t_cheque_issued', $this->trans_code,$this->max_no,$_POST['bank7_'.$x],$_POST['bank7_'.$x],$_POST['bank7_'.$x],$_POST['chqu7_'.$x],$_POST['amount7_'.$x],$_POST['cdate7_'.$x],"P",$to_acc,$_POST['date']);

      $this->save_issue_cheque_book_det($_POST['cdate7_'.$x], $_POST['amount7_'.$x],$_POST['date'], $this->trans_code,$this->max_no, $_POST['chqu7_'.$x],$_POST['chq7_'.$x],$to_payee);
                      // $this->trans_settlement->update_cheque_trans("t_cheque_issued","","",$_POST['bank7_'.$x],"P",$this->trans_code,$_POST['chqu7_'.$x],$_POST['cdate7_'.$x],$this->max_no,"account","cr","dr");  
    }
  }
} 
}

if(isset($_POST['type8_'.$x]) && !empty($_POST['type8_'.$x])){ 
  if(isset($_POST['no8_'.$x]) && !empty($_POST['no8_'.$x])){ 
    if(isset($_POST['amount8_'.$x]) && !empty($_POST['amount8_'.$x])){
     $this->save_priviledge_card($this->trans_code,$this->max_no,$_POST['type8_'.$x],$_POST['no8_'.$x],$_POST['amount8_'.$x]); 
     $this->load->model('trans_settlement');
     $this->trans_settlement->save_settlement("t_loyalty_point_trans",$_POST['acc_codes'],$_POST['date'],$this->trans_code,$this->max_no,$this->trans_code,$_POST['id'],"0",$_POST['amount8_'.$x]);

   }
 } 
}

if(isset($_POST['bank9_'.$x]) && !empty($_POST['bank9_'.$x])){ 
 if(isset($_POST['branch9_'.$x]) && !empty($_POST['branch9_'.$x])){ 
   if(isset($_POST['acc9_'.$x]) && !empty($_POST['acc9_'.$x])){
    if(isset($_POST['cheque9_'.$x]) && !empty($_POST['cheque9_'.$x])){
      if(isset($_POST['amount9_'.$x]) && !empty($_POST['amount9_'.$x])){
        $this->save_receive_cheque($this->trans_code,$this->max_no,$_POST['bank9_'.$x],$_POST['branch9_'.$x],$_POST['acc9_'.$x],$_POST['cheque9_'.$x],$_POST['amount9_'.$x],$_POST['date9_'.$x]);
        if($this->trans_code=="16"){
          $from_acc=$_POST['customer'];
        }
        if($this->trans_code=="24"){
          $from_acc=$_POST['customer'];
        }
        if($this->trans_code=="49"){
          $from_acc=$_POST['0_0'];
        }
        if($this->trans_code=="108"){
          $from_acc=$_POST['acc_code'];
        }
        if($this->trans_code=="134"){
          $from_acc=$_POST['acc_code'];
        }
        $this->save_t_cheque_received('t_cheque_received',$this->trans_code,$this->max_no,$_POST['bank9_'.$x],$_POST['branch9_'.$x],$_POST['acc9_'.$x],$_POST['cheque9_'.$x],$_POST['amount9_'.$x],$_POST['date9_'.$x],'P',$from_acc,$_POST['date']);

      }
    }  
  }
} 
}

if(isset($_POST['pdbank9_'.$x]) && !empty($_POST['pdbank9_'.$x])){ 
 if(isset($_POST['pdbranch9_'.$x]) && !empty($_POST['pdbranch9_'.$x])){ 
   if(isset($_POST['pdacc9_'.$x]) && !empty($_POST['pdacc9_'.$x])){
    if(isset($_POST['pdcheque9_'.$x]) && !empty($_POST['pdcheque9_'.$x])){
      if(isset($_POST['pdamount9_'.$x]) && !empty($_POST['pdamount9_'.$x])){
        if($this->trans_code=="16"){
          $from_acc=$_POST['customer'];
        }
        if($this->trans_code=="24"){
          $from_acc=$_POST['customer'];
        }
        if($this->trans_code=="49"){
          $from_acc=$_POST['0_0'];
        }
        if($this->trans_code=="108"){
          $from_acc=$_POST['acc_code'];
        }
        $this->save_post_dated_cheque($this->trans_code,$this->max_no,$_POST['pdbank9_'.$x],$_POST['pdbranch9_'.$x],$_POST['pdacc9_'.$x],$_POST['pdcheque9_'.$x],$_POST['pdamount9_'.$x],$_POST['pddate9_'.$x]);
        $this->update_cheque_status($_POST['pdbank9_'.$x],$_POST['pdbranch9_'.$x],$_POST['pdacc9_'.$x],$_POST['pdcheque9_'.$x]);
        $this->save_t_cheque_received('t_cheque_received',$this->trans_code,$this->max_no,$_POST['pdbank9_'.$x],$_POST['pdbranch9_'.$x],$_POST['pdacc9_'.$x],$_POST['pdcheque9_'.$x],$_POST['pdamount9_'.$x],$_POST['pddate9_'.$x],'P',$from_acc,$_POST['date']);
      }
    }  
  }
} 
}
}

if(isset($_POST['installment']) && !empty($_POST['installment'])){ 
  $this->save_install_payment_trans();
}

}


public function save_install_payment($table_name,$code,$date,$trans_code,$trans_no,$ins_trans_code,$sub_trans_no,$dr,$cr,$agr_no,$due_date,$ins_no,$sub_trans_code,$sub_cl,$sub_bc){
  $data=array(
    "cl"=>$this->sd['cl'],
    "bc"=>$this->sd['branch'],
    "sub_cl"=>$sub_cl,
    "sub_bc"=>$sub_bc,
    "ddate"=>$date,
    "acc_code"=>$code,
    "trans_code"=>$trans_code,
    "trans_no"=>$trans_no,
    "sub_trans_code"=>$sub_trans_code,
    "sub_trans_no"=>$sub_trans_no,
    "dr"=>$dr,
    "cr"=>$cr,
    "agr_no"=>$agr_no,
    "due_date"=>$due_date,
    "ins_no"=>$ins_no,
    "ins_trans_code"=>$ins_trans_code
  );
  $this->db->insert($table_name,$data);
}



public function delete_settlement($table_name,$trans_code,$trans_no){
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("trans_code",$trans_code);
  $this->db->where("trans_no",$trans_no);
  $this->db->delete($table_name);
}


public function load(){
  $table="";
  switch($_POST['trans_code']){
    case '4':
    $table='t_cash_sales_sum';
    break;  
    case '5':
    $table='t_credit_sales_sum';
    break;
    case '24':
    $table='t_advance_sum';
    break;
    case '19':
    $table='t_voucher_sum';
    break;

    case '16':
    $table='t_receipt';
    break;

    default:$table="";
    break;   
  }

  $this->db->select(array(
    't_ins_schedule.ins_no',
    't_ins_schedule.due_date',
    't_ins_schedule.ins_amount',
    't_ins_schedule.capital_amount',
    't_ins_schedule.int_amount'  
  ));

  $this->db->from('t_ins_schedule');
  $this->db->where('t_ins_schedule.cl',$this->sd['cl'] );
  $this->db->where('t_ins_schedule.bc',$this->sd['branch'] );
  $this->db->where('t_ins_schedule.trans_no',$_POST['id']);

  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['ins_schedule']=$query->result();
  }else{
    $a['ins_schedule']=2;
  }

  $cl=$this->sd['cl'];
  $branch=$this->sd['branch'];
        // $sql="SELECT t.cl, t.bc, t.trans_code AS trans_code_no, t.trans_no, s.amount, SUM(t.dr) - SUM(t.cr) as balance, t_trans_code.`description` ,s.memo, t_trans_code.`code`
        //       FROM `t_credit_note_trans` t 
        //       JOIN `t_credit_note` s ON t.trans_no = s.nno 
        //       JOIN t_trans_code ON t_trans_code.`code`=t.sub_trans_code
        //       WHERE (t.trans_code='17') AND t.acc_code IN (SELECT cus_id FROM $table WHERE cl='$cl' AND bc='$branch' AND nno='$_POST[id]') GROUP BY trans_no, t.trans_code HAVING balance>0 LIMIT 10";
  
        // $query=$this->db->query($sql);      

        // if($query->num_rows()>0){
        //   $a['credit_note_det']=$query->result();
        // }else{
        //   $a['credit_note_det']=2;
        // }


        // $sql2="SELECT t.cl, t.bc, t.trans_code AS trans_code_no, t.trans_no, s.amount, SUM(t.dr) - SUM(t.cr) balance, t_trans_code.`description` ,s.memo, t_trans_code.`code`
        //       FROM `t_debit_note_trans` t 
        //       JOIN `t_debit_note` s ON t.trans_no = s.nno 
        //       JOIN t_trans_code ON t_trans_code.`code`=t.sub_trans_code
        //       WHERE (t.trans_code='18') AND t.acc_code IN (SELECT cus_id FROM $table WHERE cl='$cl' AND bc='$branch' AND nno='$_POST[id]') GROUP BY trans_no, t.trans_code HAVING balance>0 LIMIT 10";
  
        // $query2=$this->db->query($sql2);      

        // if($query2->num_rows()>0){
        //   $a['debit_note_det']=$query2->result();
        // }else{
        //   $a['debit_note_det']=2;
        // }


  $this->db->select(array(
    'opt_advance_pay_det.advance_no',
    'opt_advance_pay_det.ddate',
    'opt_advance_pay_det.amount',
    'opt_advance_pay_det.balance',
    'opt_advance_pay_det.settle'  
  ));

  $this->db->from('opt_advance_pay_det');
  $this->db->where('opt_advance_pay_det.cl',$this->sd['cl'] );
  $this->db->where('opt_advance_pay_det.bc',$this->sd['branch'] );
  $this->db->where('opt_advance_pay_det.trans_no',$_POST['id']);
  $this->db->where('opt_advance_pay_det.trans_code',$_POST['trans_code']);
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_advance_pay_det']=$query->result();
  }else{
    $a['opt_advance_pay_det']=2;
  }


  $this->db->select(array(
    'opt_bank_debit_det.code',
    'opt_bank_debit_det.name',
    'opt_bank_debit_det.amount'
  ));

  $this->db->from('opt_bank_debit_det');
  $this->db->where('opt_bank_debit_det.cl',$this->sd['cl'] );
  $this->db->where('opt_bank_debit_det.bc',$this->sd['branch'] );
  $this->db->where('opt_bank_debit_det.trans_no',$_POST['id']);
  $this->db->where('opt_bank_debit_det.trans_code',$_POST['trans_code']);
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_bank_debit_det']=$query->result();
  }else{
    $a['opt_bank_debit_det']=2;
  }


  $this->db->select(array(
    'opt_credit_card_det.card_type',
    'opt_credit_card_det.card_no',
    'opt_credit_card_det.amount',
    'opt_credit_card_det.bank_id',
    'opt_credit_card_det.month',
    'opt_credit_card_det.rate',
    'opt_credit_card_det.int_amount',
    'opt_credit_card_det.merchant_id',
    'opt_credit_card_det.acc_code',
    'm_bank.description'
  ));

  $this->db->from('opt_credit_card_det');
  $this->db->join('m_bank', 'm_bank.code = opt_credit_card_det.bank_id');
  $this->db->where('opt_credit_card_det.cl',$this->sd['cl'] );
  $this->db->where('opt_credit_card_det.bc',$this->sd['branch'] );
  $this->db->where('opt_credit_card_det.trans_no',$_POST['id']);
  $this->db->where('opt_credit_card_det.trans_code',$_POST['trans_code']);
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_credit_card_det']=$query->result();
  }else{
    $a['opt_credit_card_det']=2;
  }


  $this->db->select(array(
    'opt_credit_note_det.bc',
    'opt_credit_note_det.cl',
    'opt_credit_note_det.cn_no',
    'opt_credit_note_det.ddate',
    'opt_credit_note_det.amount',
    'opt_credit_note_det.balance',
    'opt_credit_note_det.settled',
    't_credit_note.memo',
    't_trans_code.description',
    't_credit_note_trans.trans_code'   
  ));

  $this->db->from('opt_credit_note_det');
  $this->db->join('t_credit_note', 't_credit_note.nno = opt_credit_note_det.cn_no');
  $this->db->join('t_credit_note_trans', 't_credit_note_trans.acc_code = t_credit_note.code');
  $this->db->join('t_trans_code', 't_trans_code.code = t_credit_note_trans.sub_trans_code');
  $this->db->where('opt_credit_note_det.cl',$this->sd['cl'] );
  $this->db->where('opt_credit_note_det.bc',$this->sd['branch'] );
  $this->db->where('opt_credit_note_det.trans_no',$_POST['id']);
  $this->db->where('opt_credit_note_det.trans_code',$_POST['trans_code']);
  $this->db->group_by('opt_credit_note_det.cn_no'); 
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_credit_note_det']=$query->result();
  }else{
    $a['opt_credit_note_det']=2;
  }


  $this->db->select(array(
    'opt_debit_note_det.bc',
    'opt_debit_note_det.cl',
    'opt_debit_note_det.dn_no',
    'opt_debit_note_det.ddate',
    'opt_debit_note_det.amount',
    'opt_debit_note_det.balance',
    'opt_debit_note_det.settled',
    't_debit_note.memo',
    't_trans_code.description',
    't_debit_note_trans.trans_code'   
  ));

  $this->db->from('opt_debit_note_det');
  $this->db->join('t_debit_note', 't_debit_note.nno = opt_debit_note_det.dn_no');
  $this->db->join('t_debit_note_trans', 't_debit_note_trans.acc_code = t_debit_note.code');
  $this->db->join('t_trans_code', 't_trans_code.code = t_debit_note_trans.sub_trans_code');
  $this->db->where('opt_debit_note_det.cl',$this->sd['cl'] );
  $this->db->where('opt_debit_note_det.bc',$this->sd['branch'] );
  $this->db->where('opt_debit_note_det.trans_no',$_POST['id']);
  $this->db->where('opt_debit_note_det.trans_code',$_POST['trans_code']);
  $this->db->group_by('opt_debit_note_det.dn_no'); 
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_debit_note_det']=$query->result();
  }else{
    $a['opt_debit_note_det']=2;
  }

  $this->db->select(array(
    'opt_gift_voucher_det.vou_no',
    'opt_gift_voucher_det.code',
    'g_m_gift_voucher.description',
    'opt_gift_voucher_det.issued_date',
    'opt_gift_voucher_det.amount'
  ));

  $this->db->from('opt_gift_voucher_det');
  $this->db->join('g_m_gift_voucher', 'g_m_gift_voucher.code = opt_gift_voucher_det.code');
  $this->db->where('opt_gift_voucher_det.cl',$this->sd['cl'] );
  $this->db->where('opt_gift_voucher_det.bc',$this->sd['branch'] );
  $this->db->where('opt_gift_voucher_det.trans_no',$_POST['id']);
  $this->db->where('opt_gift_voucher_det.trans_code',$_POST['trans_code']); 
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_gift_voucher_det']=$query->result();
  }else{
    $a['opt_gift_voucher_det']=2;
  }

  $this->db->select(array(
    'opt_issue_cheque_det.bank',
    'opt_issue_cheque_det.description',
    'opt_issue_cheque_det.cheque_no',
    'opt_issue_cheque_det.amount',
    'opt_issue_cheque_det.cheque_book',  
    'opt_issue_cheque_det.cheque_date AS chq_date'
  ));

  $this->db->from('opt_issue_cheque_det');
  $this->db->where('opt_issue_cheque_det.cl',$this->sd['cl'] );
  $this->db->where('opt_issue_cheque_det.bc',$this->sd['branch'] );
  $this->db->where('opt_issue_cheque_det.trans_no',$_POST['id']);
  $this->db->where('opt_issue_cheque_det.trans_code',$_POST['trans_code']); 
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_issue_cheque_det']=$query->result();
  }else{
    $a['opt_issue_cheque_det']=2;
  }


  $this->db->select(array(
    'opt_receive_cheque_det.bank',
    'opt_receive_cheque_det.branch',
    'opt_receive_cheque_det.account_no',
    'opt_receive_cheque_det.cheque_no',
    'opt_receive_cheque_det.amount',
    'opt_receive_cheque_det.cheque_date'  
  ));

  $this->db->from('opt_receive_cheque_det');
  $this->db->where('opt_receive_cheque_det.cl',$this->sd['cl'] );
  $this->db->where('opt_receive_cheque_det.bc',$this->sd['branch'] );
  $this->db->where('opt_receive_cheque_det.trans_no',$_POST['id']);
  $this->db->where('opt_receive_cheque_det.trans_code',$_POST['trans_code']); 
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_receive_cheque_det']=$query->result();
  }else{
    $a['opt_receive_cheque_det']=2;
  }


  $this->db->select(array(
    'opt_post_dated_cheque_det.bank',
    'opt_post_dated_cheque_det.branch',
    'opt_post_dated_cheque_det.account_no',
    'opt_post_dated_cheque_det.cheque_no',
    'opt_post_dated_cheque_det.amount',
    'opt_post_dated_cheque_det.cheque_date'  
  ));

  $this->db->from('opt_post_dated_cheque_det');
  $this->db->where('opt_post_dated_cheque_det.cl',$this->sd['cl'] );
  $this->db->where('opt_post_dated_cheque_det.bc',$this->sd['branch'] );
  $this->db->where('opt_post_dated_cheque_det.trans_no',$_POST['id']);
  $this->db->where('opt_post_dated_cheque_det.trans_code',$_POST['trans_code']); 
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_post_dated_cheque_det']=$query->result();
  }else{
    $a['opt_post_dated_cheque_det']=2;
  }


  $this->db->select(array(
    'opt_privilege_card_det.card_no',
    'opt_privilege_card_det.available',
    'opt_privilege_card_det.redeem'
  ));

  $this->db->from('opt_privilege_card_det');
  $this->db->where('opt_privilege_card_det.cl',$this->sd['cl'] );
  $this->db->where('opt_privilege_card_det.bc',$this->sd['branch'] );
  $this->db->where('opt_privilege_card_det.trans_no',$_POST['id']);
  $this->db->where('opt_privilege_card_det.trans_code',$_POST['trans_code']); 
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['opt_privilege_card_det']=$query->result();
  }else{
    $a['opt_privilege_card_det']=2;
  }

  echo json_encode($a);
}


public function load_credit_note(){
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $customer=$_POST['customer'];
  $sql="SELECT t.cl, t.bc, s.nno, `t_trans_code`.`description` as type, t.trans_code AS t_code , s.memo as description, s.amount ,SUM(t.dr)-SUM(t.cr) balance 
  FROM `t_credit_note_trans` t
  JOIN `t_credit_note` s ON t.trans_no=s.nno AND t.sub_bc=s.bc AND t.sub_cl=s.cl
  JOIN `t_trans_code` ON `t_trans_code`.`code`= t.`sub_trans_code`

  WHERE t.acc_code='$customer'
  AND s.bc='$bc' AND s.cl='$cl'
  GROUP BY s.nno,t.sub_cl,t.sub_bc 
  HAVING balance>0  
  LIMIT 10 

  UNION ALL 

  SELECT t.cl, t.bc, s.nno, `t_trans_code`.`description` as type, t.trans_code AS t_code , s.memo as description, s.settle_balance AS amount ,SUM(t.dr)-SUM(t.cr) balance 
  FROM `t_cus_settlement` t
  JOIN `t_receipt` s ON t.trans_no=s.nno AND t.sub_bc=s.bc AND t.sub_cl=s.cl
  JOIN `t_trans_code` ON `t_trans_code`.`code`= t.`sub_trans_code`

  WHERE t.acc_code='$customer'
  AND s.bc='$bc' AND s.cl='$cl'
  GROUP BY s.nno,t.sub_cl,t.sub_bc 
  HAVING balance>0 
  LIMIT 10 

  ";

  $query = $this->db->query($sql);

  $a = "<table style='width : 100%' cellpadding='0'>";

  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th' style='width:40px;'>No</th>";
  $a .= "<th class='tb_head_th' style='width:125px;'>Type</th>";
  $a .= "<th class='tb_head_th' style='width:125px;'>Description</th>";
  $a .= "<th class='tb_head_th' style='width:100px;'>Amount</th>";
  $a .= "<th class='tb_head_th' style='width:100px;'>Balance</th>";
  $a .= "<th class='tb_head_th' style='width:100px;'>Settle</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";

  $a .= "</tr>";
  $c=0;

  foreach ($query->result() as $r){         
    $a .= "<tr class='cl'>";
    $a .= "<td>
    <input type='hidden' name='cl_".$c."' id='cl_".$c."' value='".$r->cl."'/>
    <input type='hidden' name='bc_".$c."' id='bc_".$c."' value='".$r->bc."'/>
    <input type='text' class='g_input_txt' id='no2_".$c."' name='no2_".$c."' value='".$r->nno."' /></td>";
    $a .= "<td><input type='text' class='g_input_txt' id='type_".$c."' name='type_".$c."' value='".$r->type."' /></td>";
    $a .= "<td><input type='text' class='g_input_txt' id='des_".$c."' name='des_".$c."' value='".$r->description."' /><input type='hidden' name='t_code_".$c."' id='t_code_".$c."' value='".$r->t_code."'/></td>";
    $a .= "<td><input type='text' class='g_input_amo cn_amount' id='amount2_".$c."' name='amount2_".$c."' value='".$r->amount."' /></td>";
    $a .= "<td><input type='text' class='g_input_amo' id='balance2_".$c."' name='balance2_".$c."' value='".$r->balance."' /></td>";
    $a .= "<td><input type='text' class='g_input_amo cn_settle' id='settle2_".$c."' name='settle2_".$c."'/></td>";
      //$a .= "<td>" . $r->total . "</td>";
      //$a .= "<td>" . $r->cost . "</td>";
    $a .= "</tr>";
    $c=$c+1;
      // $tot=$r->total;
      // $tot_bal=$r->tot_bal;
  }

  $a.="<tr style=background:#f2f2f2;>";
  $a.="<td></td>";
  $a.="<td></td>";
  $a.="<td><b>Total</b></td>";
  $a.="<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total' name='total' /></td>";
  $a.="<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total_bal' name='total_bal'/></td>";
  $a.="<td></td>";
  $a.="</tr>";


  $a .= "</table>";
  echo $a;
}



public function load_debit_note(){
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $customer=$_POST['customer'];
  $sql="SELECT t.cl, t.bc, s.nno, `t_trans_code`.`description` AS type, `t_trans_code`.`code` AS t_code ,s.memo AS description, s.amount ,SUM(t.dr)-SUM(t.cr) balance 
  FROM `t_debit_note_trans` t
  JOIN `t_debit_note` s ON t.trans_no=s.nno AND t.sub_bc=s.bc AND t.sub_cl=s.cl
  JOIN `t_trans_code` ON `t_trans_code`.`code`= t.`sub_trans_code`

  WHERE t.acc_code='$customer'
  AND s.bc='$bc' AND s.cl='$cl'
  GROUP BY s.nno,t.sub_cl,t.sub_bc 
  HAVING balance>0 
  LIMIT 10 ";

  $query = $this->db->query($sql);

  $a = "<table style='width : 100%' cellpadding='0'>";

  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th' style='width:40px;'>No</th>";
  $a .= "<th class='tb_head_th' style='width:125px;'>Type</th>";
  $a .= "<th class='tb_head_th' style='width:125px;'>Description</th>";
  $a .= "<th class='tb_head_th' style='width:100px;'>Amount</th>";
  $a .= "<th class='tb_head_th' style='width:100px;'>Balance</th>";
  $a .= "<th class='tb_head_th' style='width:100px;'>Settle</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "</tr>";

  $c=0;

  foreach ($query->result() as $r){  
    $a .= "<tr class='cl'>";
    $a .= "<td>
    <input type='hidden' name='cl3_".$c."' id='cl3_".$c."' value='".$r->cl."'/>
    <input type='hidden' name='bc3_".$c."' id='bc3_".$c."' value='".$r->bc."'/>
    <input type='text' class='g_input_txt' id='no3_".$c."' name='no3_".$c."' value='".$r->nno."' /></td>";
    $a .= "<td><input type='text' class='g_input_txt' id='type3_".$c."' name='type3_".$c."' value='".$r->type."' /></td>";
    $a .= "<td><input type='text' class='g_input_txt' id='des3_".$c."' name='des3_".$c."' value='".$r->description."' /><input type='hidden' name='t_code3_".$c."' id='t_code3_".$c."' value='".$r->t_code."'/></td>";
    $a .= "<td><input type='text' class='g_input_amo dn_amount' id='amount3_".$c."' name='amount3_".$c."' value='".$r->amount."' /></td>";
    $a .= "<td><input type='text' class='g_input_amo' id='balance3_".$c."' name='balance3_".$c."' value='".$r->balance."' /></td>";
    $a .= "<td><input type='text' class='g_input_amo dn_settle' id='settle3_".$c."' name='settle3_".$c."'/></td>";
      //$a .= "<td>" . $r->qty . "</td>";
      //$a .= "<td>" . $r->cost . "</td>";
    $a .= "</tr>";
    $c=$c+1;

  } 

  $a.="<tr style=background:#f2f2f2;>";
  $a.="<td></td>";
  $a.="<td></td>";
  $a.="<td><b>Total</b></td>";
  $a.="<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total_d' name='total_d' /></td>";
  $a.="<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total_d_bal' name='total_d_bal'/></td>";
  $a.="<td></td>";
  $a.="</tr>";

  $a .= "</table>";
  echo $a;
}




public function get_payment_option(){
  $code=0;
  switch ($_POST['code']) {
    case "t_credit_sales_sum":
    $code=5;
    break;
    case "t_cash_sales_sum":
    $code=4;
    break;
    case "t_voucher":
    $code=1;
    break;
    case "t_receipt":
    $code=2;
    break;  
  }

  $this->db->where("code",$code);
  $data['result']=$this->db->get("r_payment_option")->result();
  echo json_encode($data);
}


public function load_credit_card_type(){
  $this->db->like('card_type', $_GET['q']);
  $query = $this->db->select(array('card_type'))->get('m_credit_card_type');
  $abc = "";
  foreach($query->result() as $r){
    $abc .= $r->card_type;
    $abc .= "\n";
  }
  echo $abc;
}

public function get_due_date(){
  $trans_date=$_POST['trans_date'];
  $num_of_days=(int)$_POST['num_of_days'];
  $num_of_installment=(int)$_POST['num_of_installment'];
  $dd = explode("~~", $_POST['installment_num']);
  $due_dates = array();

  for ($num = 0 ; ($num < count($dd)-1) ; $num++){        
    $installment_num = $dd[$num];
    if($num_of_installment==1){
      $end_date=date('Y-m-d',(strtotime($trans_date)) + (86400 * $num_of_days));
      $due_dates[$num] = $end_date;
    }else{
      $end_date=date('Y-m-d', strtotime("+ ".$installment_num." months", strtotime($trans_date)));
      $due_dates[$num] = $end_date;
    }
  }
  $a['a'] =  $due_dates;      
  echo json_encode($a);
}

public function get_due_date2(){
  $trans_date=$_POST['trans_date'];
  $num_of_days=(int)$_POST['num_of_days'];
  $num_of_installment=(int)$_POST['num_of_installment'];
  $num_of_remain_days=$num_of_days%$num_of_installment;
  $num_of_days_per_installment=floor($num_of_days/$num_of_installment);
  $dd = explode("~~", $_POST['installment_num']);
  $due_dates = array();

  for ($num = 0 ; ($num < count($dd)-1) ; $num++){        
    $installment_num = $dd[$num];
    if($num_of_installment==1){
      $end_date=date('Y-m-d',(strtotime($trans_date)) + (86400 * $num_of_days));
      $due_dates[$num] = $end_date;
    }else{
              //$end_date=date('Y-m-d', strtotime("+ ".$installment_num." months", strtotime($trans_date)));
      $end_date=date('Y-m-d',(strtotime($trans_date)) + (86400 * ($num_of_days_per_installment*($num+1))));
      $due_dates[$num] = $end_date;
              //$num_of_days_per_installment=$num_of_days_per_installment+$num_of_days_per_installment;
    }
  }
  $a['a'] =  $due_dates;      
  echo json_encode($a);
}

public function get_points(){
  $query=$this->db->query("
    SELECT loyalty_card_no,loyalty_balance FROM m_customer WHERE code='".$_POST['customer']."' ");
  $data['points_res']=$query->first_row();
  echo json_encode($data);
}

public function get_points3(){
  $query=$this->db->query("
    SELECT loyalty_card_no,ROUND((red_point*loyalty_balance)*red_rs,2) as loyalty_balance FROM m_customer 
    JOIN `t_loyalty_card` c ON c.`card_no`=m_customer.`loyalty_card_no`
    JOIN `t_loyalty_card_category` cc ON cc.`code`=c.`category`
    WHERE loyalty_card_no='".$_POST['type']."' ");
  $data['points_res']=$query->first_row();
  echo json_encode($data);
}

public function get_department_pv_rate(){

  $sql="SELECT * FROM `t_loyalty_card` i
  JOIN `t_loyalty_card_category` c ON i.`category`=c.`code`
  WHERE card_no='".$_POST['code']."'";

  $query=$this->db->query($sql)->result();
  foreach ($query as $value) {
    $earnrs=$value->earn_rs;
    $earnpt=$value->earn_point;
  }
  $point=($_POST['total']/$earnrs)*$earnpt;
  echo $point;
  
}

public function bank_rate(){

  if ($_POST['search'] == 'Key Word: code, name') {
    $_POST['search'] = "";
  }

  $sql="SELECT bank_id,
  month,
  rate,
  acc_no,
  description,
  merchant_id,
  terminal_id 
  FROM r_credit_card_rate 
  JOIN m_bank  ON m_bank.`code`=r_credit_card_rate.`bank_id`
  WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND (bank_id LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%')
  AND is_inactive='0'
  group by bank_id ,month,cl,bc,terminal_id
  ORDER BY bank_id asc
  ";
  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Bank Id</th>";
  $a .= "<th class='tb_head_th'>Description</th>";
  $a .= "<th class='tb_head_th'>Account</th>";
  $a .= "<th class='tb_head_th'style='text-align:right;'>Month</th>";
  $a .= "<th class='tb_head_th'style='text-align:right;'>Rate</th>";
  $a .= "<th class='tb_head_th'style='text-align:right;'>Terminal ID</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";

  $a .= "</tr>";
  foreach ($query->result() as $r) {
    $a .= "<tr class='cl'>";
    $a .= "<td>".$r->bank_id."</td>";
    $a .= "<td>".$r->description."</td>";   
    $a .= "<td>".$r->acc_no."</td>";       
    $a .= "<td style='text-align:right;'>".$r->month."</td>";
    $a .= "<td style='text-align:right;'>".$r->rate."</td>";
    $a .= "<td style='display:none;'>".$r->merchant_id."</td>";
    $a .= "<td style='text-align:right;'>".$r->terminal_id."</td>";
    $a .= "</tr>";
  }
  $a .= "</table>";

  echo $a;          
}

public function load_gift(){

  if ($_POST['search'] == 'Key Word: code, name') {
    $_POST['search'] = "";
  }

  $sql="SELECT  s.`serial_no`, 
  m.`description`,
  m.`code`,
  s.`out_date`,
  s.`max_price`
  FROM g_t_serial s
  JOIN g_m_gift_voucher m ON m.`code` = s.`item`
  WHERE s.`available` = '0' 
  AND s.settled_trans_no='0'
  AND (serial_no LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%')
  LIMIT 25";

  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Number</th>";
  $a .= "<th class='tb_head_th'>Description</th>";
  $a .= "<th class='tb_head_th' style='text-align:right;'>Issue Date</th>";
  $a .= "<th class='tb_head_th' style='text-align:right;'>Amount</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "</tr>";
  foreach ($query->result() as $r) {
    $a .= "<tr class='cl'>";
    $a .= "<td>".$r->serial_no."</td>";
    $a .= "<td>".$r->description."</td>";   
    $a .= "<td style='text-align:right;'>".$r->out_date."</td>";
    $a .= "<td style='text-align:right;'>".$r->max_price."</td>";
    $a .= "<td style='display:none;'>".$r->code."</td>";
    $a .= "</tr>";
  }
  $a .= "</table>";

  echo $a;          
}

public function issue_chku(){

  if ($_POST['search'] == 'Key Word: code, name') {
    $_POST['search'] = "";
  }

  $sql="SELECT code,
  description 
  FROM m_account 
  WHERE is_bank_acc='1' AND (code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%')

  ";
  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Bank Id</th>";
  $a .= "<th class='tb_head_th'>Description</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";

  $a .= "</tr>";
  foreach ($query->result() as $r) {
    $a .= "<tr class='cl'>";
    $a .= "<td >" . $r->code . "</td>";
    $a .= "<td> " . $r->description . "</td>";         
    $a .= "</tr>";
  }
  $a .= "</table>";

  echo $a;          
}

public function receive_chku(){

  if ($_POST['search'] == 'Key Word: code, name') {
    $_POST['search'] = "";
  }

  $sql="SELECT `m_bank`.`code` AS bank_code,
  m_bank.`description` AS `desc`,
  m_bank_branch.`code` AS branch_code,
  m_bank_branch.`description` 
  FROM `m_bank` 
  JOIN `m_bank_branch` ON `m_bank`.`code`=`m_bank_branch`.`bank` 
  WHERE `m_bank_branch`.`code` like '%$_POST[search]%' OR m_bank.`description` like'%$_POST[search]%' 
  OR m_bank_branch.`description` like '%$_POST[search]%'
  LIMIT 25";

  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Bank Code</th>";
  $a .= "<th class='tb_head_th'>Bank Name</th>";
  $a .= "<th class='tb_head_th'>Branch Code</th>";
  $a .= "<th class='tb_head_th'>Branch Name</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";

  $a .= "</tr>";
  foreach ($query->result() as $r) {
    $a .= "<tr class='cl'>";
    $a .= "<td >" . $r->bank_code . "</td>";
    $a .= "<td> " . $r->desc . "</td>"; 
    $a .= "<td >" . $r->branch_code . "</td>";
    $a .= "<td> " . $r->description . "</td>";              
    $a .= "</tr>";
  }
  $a .= "</table>";

  echo $a;          
}


public function auto_com_bank(){
  $this->db->like('code', $_GET['q']);
  $this->db->or_like("m_bank".'.description', $_GET['q']);
  $query = $this->db->select(array('code', "m_bank".'.description'))

  ->get("m_bank");
  $abc = "";
  foreach($query->result() as $r){
    $abc .= $r->code."|".$r->description;  
    $abc .= "\n";
  }

  echo $abc;
} 


public function chq_book(){
  if ($_POST['search'] == 'Key Word: code, name') {
    $_POST['search'] = "";
  }

  $sql="SELECT  s.`nno`,
  s.`account`,
  a.`description`,
  MIN(d.`cheque_no`) as cheque_no
  FROM t_cheque_book_sum s
  JOIN t_cheque_book_det d ON d.`cl` = s.`cl` AND d.`bc` = s.`bc` AND d.`nno` = s.`nno`
  JOIN m_account a ON a.`code` = s.`account`
  WHERE d.`status` = 0
  AND s.`status`='1'
  AND s.`cl` ='".$this->sd['cl']."' 
  AND s.bc='".$this->sd['branch']."'
  AND (s.`account` like '%$_POST[search]%' OR s.`nno` like'%$_POST[search]%' OR a.`description` like'%$_POST[search]%') 
  GROUP BY s.`nno`,s.cl,s.bc
  ORDER BY s.nno ASC
  LIMIT 25";

  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Cheque Book No</th>";
  $a .= "<th class='tb_head_th'>Bank Account</th>";
  $a .= "<th class='tb_head_th'>Account Name</th>";
  $a .= "<th class='tb_head_th'>Next Cheque No</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";

  $a .= "</tr>";
  foreach ($query->result() as $r) {
    $a .= "<tr class='cl'>";
    $a .= "<td>".trim($r->nno)."</td>";
    $a .= "<td>".trim($r->account)."</td>"; 
    $a .= "<td>".trim($r->description)."</td>";
    $a .= "<td>".trim($r->cheque_no)."</td>";              
    $a .= "</tr>";
  }
  $a .= "</table>";

  echo $a;    
} 


public function pending_chqs(){
 if ($_POST['search'] == 'Key Word: code, name') {
  $_POST['search'] = "";
}

$sql="SELECT  s.`nno`,
d.`cheque_no`
FROM t_cheque_book_sum s
JOIN t_cheque_book_det d ON d.`cl` = s.`cl` AND d.`bc` = s.`bc` AND d.`nno` = s.`nno`
WHERE d.`status` = 0
AND s.`status`='1'
AND s.`nno`='".$_POST['chq_book_no']."'
AND s.`cl` ='".$this->sd['cl']."' 
AND s.bc='".$this->sd['branch']."'
AND (d.`cheque_no` like '%$_POST[search]%' OR s.`nno` like'%$_POST[search]%') 
GROUP BY s.`nno`,s.cl,s.bc,d.`cheque_no`
ORDER BY d.cheque_no ASC
LIMIT 25";

$query = $this->db->query($sql);
$a = "<table id='item_list' style='width : 100%' >";
$a .= "<thead><tr>";
$a .= "<th class='tb_head_th'>Pending Cheque No</th>";

$a .= "</thead></tr>";
$a .= "<tr class='cl'>";
$a .= "<td>&nbsp;</td>";
$a .= "<td>&nbsp;</td>";

$a .= "</tr>";
foreach ($query->result() as $r) {
  $a .= "<tr class='cl'>";
  $a .= "<td>".trim($r->cheque_no)."</td>";              
  $a .= "</tr>";
}
$a .= "</table>";
echo $a;    
}

public function post_dated_cheques(){
  if ($_POST['search'] == 'Key Word: code, name') {
    $_POST['search'] = "";
  }
  if(isset($_POST['customer'])){
    if($_POST['customer']!=""){
      $cus=" AND s.customer ='".$_POST['customer']."' ";
    }else{
      $cus="";
    }
  }else{
    $cus="";
  }  
  $sql="SELECT t.bank,t.branch,t.account,t.cheque_no,t.amount,t.realize_date,b.description 
  FROM t_receipt_temp_cheque_det t
  JOIN t_receipt_temp_cheque_sum s ON s.cl=t.cl AND s.bc=t.bc AND s.nno=t.nno 
  JOIN m_bank_branch b ON b.code = t.branch
  WHERE s.cl='".$this->sd['cl']."'
  AND s.bc='".$this->sd['branch']."'
  AND t.realize_date <= '".$_POST['date']."'
  AND t.status='P' $cus
  AND (b.description like '%$_POST[search]%' OR t.account like'%$_POST[search]%'
  OR t.cheque_no like'%$_POST[search]%') ";

  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Bank</th>";
  $a .= "<th class='tb_head_th'>Account</th>";
  $a .= "<th class='tb_head_th'>Cheque No</th>";
  $a .= "<th class='tb_head_th'>Amount</th>";
  $a .= "<th class='tb_head_th'>Realize Date</th>";

  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "</tr>";

  foreach ($query->result() as $r){
    $a .= "<tr class='cl'>";
    $a .= "<td style='display:none;'>".trim($r->bank)."</td>";  
    $a .= "<td style='display:none;'>".trim($r->branch)."</td>";  
    $a .= "<td style='display:none;'>".trim($r->account)."</td>";  
    $a .= "<td style='display:none;'>".trim($r->cheque_no)."</td>";  
    $a .= "<td style='display:none;'>".trim($r->amount)."</td>"; 
    $a .= "<td style='display:none;'>".trim($r->realize_date)."</td>"; 
    $a .= "<td style='text-align:left;'>".trim($r->description)."</td>"; 
    $a .= "<td style='text-align:right;'>".trim($r->account)."</td>"; 
    $a .= "<td style='text-align:right;'>".trim($r->cheque_no)."</td>"; 
    $a .= "<td style='text-align:right;'>".trim($r->amount)."</td>"; 
    $a .= "<td style='text-align:right;'>".trim($r->realize_date)."</td>";             
    $a .= "</tr>";
  }
  $a .= "</table>";
  echo $a;    






}

public function card_type(){

  if ($_POST['search'] == 'Key Word: code, name') {
    $_POST['search'] = "";
  }

  $sql="SELECT card_type
  FROM m_credit_card_type 
  WHERE (card_type LIKE '%$_POST[search]%')

  ";
  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Card Type</th>";


  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";  
  $a .= "</tr>";
  foreach ($query->result() as $r) {
    $a .= "<tr class='cl'>";
    $a .= "<td>".$r->card_type."</td>";
    $a .= "</tr>";
  }
  $a .= "</table>";

  echo $a;          
} 

public function is_chq_already_issued(){
  $status=1;
  if($_POST['hid'] != "0" || $_POST['hid'] != ""){
    return $status;
  }
  for($x=0; $x<10; $x++){
    if(isset($_POST['bank7_'.$x]) && !empty($_POST['bank7_'.$x])){ 
      if(isset($_POST['chqu7_'.$x]) && !empty($_POST['chqu7_'.$x])){ 
       if(isset($_POST['amount7_'.$x]) && !empty($_POST['amount7_'.$x])){
        if(isset($_POST['cdate7_'.$x]) && !empty($_POST['cdate7_'.$x])){
          $sql="SELECT t.description,i.trans_no FROM `t_cheque_issued` i
          JOIN t_trans_code t ON t.`code` = i.`trans_code`
          WHERE i.`account`='".$_POST['bank7_'.$x]."'
          AND i.`cheque_no`='".$_POST['chqu7_'.$x]."'";
          $query=$this->db->query($sql);
          if($query->num_rows()>0){
            $status="This Cheque no already issued in '".$query->row()->description."' No '".$query->row()->trans_no."'.";
          }
        }
      }
    }
  }
}
return $status;
} 

public function check_cheque_no(){
  $chq_no=$_POST['chq'];
  $bank=$_POST['bank'];
  $branch=$_POST['branch'];

  $sql="SELECT cheque_no
  FROM t_cheque_received
  WHERE bank='$bank'
  AND branch='$branch'
  AND cheque_no='$chq_no'
  LIMIT 1";

  $query = $this->db->query($sql);

  if($query->num_rows()>0){
    $result="Cheque number (".$chq_no.") all ready received";
  }else{
    $result=1;
  }
  echo $result;
}

public function check_issue_cheque_no(){
  $chq_no=$_POST['chq'];
  $bank=$_POST['bank'];

  $sql="SELECT cheque_no
  FROM t_cheque_issued
  WHERE bank='$bank'
  AND cheque_no='$chq_no'
  LIMIT 1";

  $query = $this->db->query($sql);

  if($query->num_rows()>0){
    $result="Cheque number (".$chq_no.") all ready issued";
  }else{
    $result=1;
  }
  echo $result;
}

}
