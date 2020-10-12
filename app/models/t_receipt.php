<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_receipt extends CI_Model {
  
  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';

  public $interest=Array();
  
  function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_receipt'];
    $this->load->model("t_payment_option");
    $this->load->model('user_permissions');

  }
  
  public function base_details(){
   $this->load->model("utility");
   $a['max_no']=$this->utility->get_max_no("t_receipt","nno");
   $a['type']='RECEIPT'; 
   return $a;
 }
 
 public function PDF_report(){
  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $r_detail['branch']=$this->db->get('m_branch')->result();
  
  $invoice_number= $this->utility->invoice_format($_POST['qno']);
  $session_array = array(
   $this->sd['cl'],
   $this->sd['branch'],
   $invoice_number
   );
  $r_detail['session'] = $session_array;
  $r_detail['duplicate'] = $_POST['is_duplicate'];

  $this->db->where("code",$_POST['sales_type']);
  $query= $this->db->get('t_trans_code'); 
  if ($query->num_rows() > 0){
    foreach ($query->result() as $row){
      $r_detail['r_type']= $row->description;       
    }
  } 

  $ssql=" SELECT card_no, ddate, opt_credit_card_det.amount   
  FROM opt_credit_card_det
  JOIN t_receipt ON t_receipt.nno = opt_credit_card_det.`trans_no` 
  AND t_receipt.cl = opt_credit_card_det.cl
  AND t_receipt.bc = opt_credit_card_det.bc
  WHERE trans_code = '16' AND trans_no = '".$_POST['qno']."' AND t_receipt.cl='".$this->sd['cl']."' AND t_receipt.bc = '".$this->sd['branch']."'
  ";
  $query = $this->db->query($ssql);
  $r_detail['credit_card'] = $this->db->query($ssql)->result();

  $sql_chq=" SELECT cheque_date,m_bank.description as bank,m_bank_branch.description as branch,account_no,cheque_no,amount   
  FROM opt_receive_cheque_det
  JOIN t_receipt ON t_receipt.nno = opt_receive_cheque_det.`trans_no` 
  JOIN m_bank ON m_bank.code = opt_receive_cheque_det.`bank` 
  JOIN m_bank_branch ON m_bank_branch.code = opt_receive_cheque_det.`branch` 
  AND t_receipt.cl = opt_receive_cheque_det.cl
  AND t_receipt.bc = opt_receive_cheque_det.bc
  WHERE trans_code = '16' AND trans_no = '".$_POST['qno']."' AND t_receipt.cl='".$this->sd['cl']."' AND t_receipt.bc = '".$this->sd['branch']."'
  ";
  $query = $this->db->query($sql_chq);
  $r_detail['cheque'] = $this->db->query($sql_chq)->result();

  $sql_chq=" SELECT cheque_date,m_bank.description as bank,m_bank_branch.description as branch,account_no,cheque_no,amount   
  FROM opt_post_dated_cheque_det
  JOIN t_receipt ON t_receipt.nno = opt_post_dated_cheque_det.`trans_no` 
  JOIN m_bank ON m_bank.code = opt_post_dated_cheque_det.`bank` 
  JOIN m_bank_branch ON m_bank_branch.code = opt_post_dated_cheque_det.`branch` 
  AND t_receipt.cl = opt_post_dated_cheque_det.cl
  AND t_receipt.bc = opt_post_dated_cheque_det.bc
  WHERE trans_code = '16' AND trans_no = '".$_POST['qno']."' AND t_receipt.cl='".$this->sd['cl']."' AND t_receipt.bc = '".$this->sd['branch']."'";
  $query = $this->db->query($sql_chq);
  $r_detail['pdcheque'] = $this->db->query($sql_chq)->result();


  $sql_chq=" SELECT *
  FROM `opt_gift_voucher_det`
  WHERE trans_code='16' and  trans_no = '".$_POST['qno']."' 
  AND opt_gift_voucher_det.cl='".$this->sd['cl']."' AND opt_gift_voucher_det.bc = '".$this->sd['branch']."'";
  $query = $this->db->query($sql_chq);
  $r_detail['giftvoucher'] = $this->db->query($sql_chq)->result();

  $r_detail['type']=$_POST['type'];        
  $r_detail['dt']=$_POST['dt'];
  $r_detail['qno']=$_POST['qno'];
  $r_detail['num']=$_POST['recivied'];

  $sql="SELECT pay_cash,pay_receive_chq,pay_ccard
  FROM t_receipt
  WHERE cl='".$this->sd['cl']."'
  AND bc='".$this->sd['branch']."'
  AND nno='".$_POST['qno']."'";
  $r_detail['pay_cash'] = $this->db->query($sql)->row()->pay_cash;
  $r_detail['pay_chq'] = $this->db->query($sql)->row()->pay_receive_chq;
  $r_detail['pay_card'] = $this->db->query($sql)->row()->pay_ccard;

  $num =$_POST['recivied'];
  
  $this->utility->num_in_letter($num);
  
  $r_detail['rec']=convertNum($num);;
  

  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];

  $this->db->select(array('nic as code','name'));
  $this->db->where("code",$_POST['cus_id']);
  $r_detail['customer']=$this->db->get('m_customer')->result();

  $this->db->select(array('name'));
  $this->db->where("code",$_POST['salesp_id']);
  $query=$this->db->get('m_employee');
  
  foreach ($query->result() as $row){
    $r_detail['employee']= $row->name;
  }


  $this->db->select(array('`t_receipt.balance`-`t_receipt.payment` as bal' , 'balance', 'memo','pay_cnote'));
  $this->db->from('t_receipt');
      //$this->db->join('m_item','m_item.code=t_credit_sales_det.code');
  $this->db->where('t_receipt.cl',$this->sd['cl'] );
  $this->db->where('t_receipt.bc',$this->sd['branch']);
  $this->db->where('t_receipt.nno',$_POST['qno']);
  $r_detail['items']=$this->db->get()->result();



  $this->db->select(array('t_receipt.oc', 's_users.discription', 'action_date'));
  $this->db->from('t_receipt');
  $this->db->join('s_users', 't_receipt.oc=s_users.cCode');
  $this->db->where('t_receipt.cl', $this->sd['cl']);
  $this->db->where('t_receipt.bc', $this->sd['branch']);
  $this->db->where('t_receipt.nno', $_POST['qno']);
  $r_detail['user'] = $this->db->get()->result();


  $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
}

public function validation(){
  $status=1;
  
  $this->max_no=$this->utility->get_max_no("t_receipt","nno");

  $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_receipt');
  if($check_is_delete!=1){
    return "Receipt already deleted";
  }
  $customer_validation = $this->validation->check_is_customer($_POST['customer']);
  if ($customer_validation != 1){
    return "Please enter valid customer";
  }
  $rep_validation= $this->validation->check_is_employer($_POST['rep']);    
  if ($rep_validation != 1) {
    return "Please enter valid rep";
  }

  $multi_payment=$this->validation->check_multi_payment();
  if ($multi_payment != 1) {
    return $multi_payment;
  }
  
  $payment_calculation_status=$this->validation->payment_calculation('net','6_','net_val','balance2');
  if($payment_calculation_status!=1){
    return $payment_calculation_status;               
  }
  
  $check_valid_dr_no=$this->validation->check_valid_trans_no2('customer','t_code_','no2_','cl_','bc_');
  if($check_valid_dr_no!=1){
    return $check_valid_dr_no;
  }
  $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle2('customer','t_code_','no2_','settle2_','cl_','bc_');
  if($check_valid_trans_settle_status!=1){
    return $check_valid_trans_settle_status; 
  }
        //Payment option debit note
  $check_valid_dr_no2=$this->validation->check_valid_trans_no2('customer','t_code3_','no3_','cl3_','bc3_');
  if($check_valid_dr_no2!=1){
    return $check_valid_dr_no2;
  }
  $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle2('customer','t_code3_','no3_','settle3_','cl3_','bc3_');
  if($check_valid_trans_settle_status2!=1){
    return $check_valid_trans_settle_status2; 
  }
  $payment_option_validation = $this->validation->payment_option_calculation();                                             
  if($payment_option_validation != 1){
    return $payment_option_validation;
  }

  $check_zero_value=$this->validation->empty_net_value($_POST['net_val']);
  if($check_zero_value!=1){
    return $check_zero_value;
  } 

      /*$account_update=$this->account_update(0);
        if($account_update!=1){
            return "Invalid account entries";
          }   */
          return $status;
        }


        public function save(){
          $this->db->trans_begin();
          $this->get_interest_calculation();
          error_reporting(E_ALL); 
          function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        //  throw new Exception($errMsg); 
          }
          set_error_handler('exceptionThrower'); 
          try { 

            
            $this->load->model("utility");
            $validation_status=$this->validation();
            if($validation_status==1){
              $_POST['type']=16;
              $_POST['acc_codes']=$_POST['customer'];   



              if(! isset($_POST['payment_option'])){ $_POST['payment_option'] = 0; }
              if(! isset($_POST['is_multi_branch'])){ $_POST['is_multi_branch'] = 0; }
              $t_receipt=array(
               "cl"=>$this->sd['cl'],
               "bc"=>$this->sd['branch'],
               "nno"=>$this->max_no,
               "ddate"=>$_POST['date'],
               "is_multi_payment"=>$_POST['payment_option'],
               "is_multi_branch"=>$_POST['is_multi_branch'],
               "ref_no"=>$_POST['ref_no'],
               "cus_acc"=>$_POST['customer'],
               "payment"=>$_POST['net'],
               "memo"=>$_POST['memo'],
               "rep"=>$_POST['rep'],
               "previlliage_card_no"=>$_POST['hid_pc_type'],
               "pay_cash"=>$_POST['hid_cash'],
               "pay_issue_chq"=>$_POST['hid_cheque_issue'],
               "pay_receive_chq"=>$_POST['hid_cheque_recieve'],
               "pay_post_dated_chq"=>$_POST['pdchq'],
               "pay_ccard"=>$_POST['hid_credit_card'],
               "pay_cnote"=>$_POST['hid_credit_note'],
               "pay_dnote"=>$_POST['hid_debit_note'],
               "pay_bank_debit"=>$_POST['hid_bank_debit'],
               "pay_discount"=>$_POST['hid_discount'],
               "pay_advance"=>$_POST['hid_advance'],
               "pay_gift_voucher"=>$_POST['hid_gv'],
               "pay_credit"=>$_POST['hid_credit'],
               "pay_privi_card"=>$_POST['hid_pc'],
               "oc"=>$this->sd['oc'],
               "post"=>"",
               "post_by"=>"",
               "post_date"=>"",
               "settle_balance"=>$_POST['balance2'],
               "settle_amount"=>$_POST['net_val'],
               "balance"=>$_POST['balance'],
               "receipt_balance"=>$_POST['balance2'],
               );

              for($x = 0; $x<300; $x++){
               if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
                 if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){
                   $t_receipt_temp[]=array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "to_cl"=>$_POST['cl0_'.$x],
                    "to_bc"=>$_POST['bc0_'.$x],
                    "nno"=>$this->max_no,
                    "trans_code"=>$_POST['trans_code'.$x],
                    "trans_no"=>$_POST['2_'.$x],
                    "date"=>$_POST['3_'.$x],
                    "description"=>$_POST['descrip_'.$x],
                    "amount"=>$_POST['4_'.$x],
                    "balance"=>$_POST['5_'.$x],
                    "payment"=>$_POST['6_'.$x],
                    "order_num"=>$x,
                    "is_install"=>$_POST['is_install_'.$x],
                    "is_penalty"=>$_POST['is_penalty_'.$x]
                    ); 
                 }
               }
             }

             if($_POST['hid'] == "0" || $_POST['hid'] == ""){
              if($this->user_permissions->is_add('t_receipt')){
                $account_update=$this->account_update(0);
                if($account_update==1){
                  $this->db->insert($this->mtb,  $t_receipt);
                  if(count($t_receipt_temp)){$this->db->insert_batch("t_receipt_temp",$t_receipt_temp);}
                  $this->load->model('trans_settlement');
                  for($x = 0; $x<300; $x++){
                    if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
                      if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){  

                        if($_POST['trans_code'.$x]=="5"){
                          $this->trans_settlement->save_settlement_multi("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],16,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);  
                        }else if($_POST['trans_code'.$x]=="18"){
                          $this->trans_settlement->save_settlement_multi("t_debit_note_trans",$_POST['customer'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],16,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);   
                        }else if($_POST['trans_code'.$x]=="47"){
                          $this->trans_settlement->t_penalty_trance($_POST['2_'.$x],$_POST['date'],$_POST['customer'],"47",$_POST['2_'.$x],"16",$this->max_no,"0.00",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);
                          $this->save_penalty_trance($_POST['cl0_'.$x],$_POST['bc0_'.$x],$_POST['2_'.$x],$_POST['date'],$_POST['customer'],$_POST['6_'.$x]);      
                        }else if($_POST['trans_code'.$x]=="1"){
                          $this->trans_settlement->save_settlement_multi("t_opening_bal_trans",$_POST['customer'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],16,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);   
                        }          
                      }

                      if($_POST['is_install_'.$x]=='1'){
                        $this->load->model("t_payment_option");
                        $this->save_install_payment_trans($_POST['cl0_'.$x],$_POST['bc0_'.$x],$_POST['2_'.$x],$_POST['customer'],$_POST['6_'.$x]);
                      }
                    }
                  }
                  
                  /* customer over payment save */
                  $get_balance=(double)$_POST['balance2'];
                  if($get_balance>0){
                    $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],16,$this->max_no,16,$this->max_no,$get_balance,"0");  
                  }

                  $this->load->model('t_payment_option');
                  $this->t_payment_option->save_payment_option($this->max_no,16);

                  $this->utility->update_credit_sale_balance($_POST['customer']);
                  $this->utility->update_debit_note_balance($_POST['customer']);
                  $this->utility->update_credit_note_balance($_POST['customer']);

                  $this->account_update(1);
                  $this->utility->save_logger("SAVE",16,$this->max_no,$this->mod);
                  
                  echo $this->db->trans_commit()."@".$this->max_no;
                }else{
                  echo "Invalid account entries";
                  $this->db->trans_commit();
                }
              }else{
                $this->db->trans_commit();
                echo "No permission to save records";
              }  
            }else{
              if($this->user_permissions->is_edit('t_receipt')){
                $status=$this->trans_cancellation->receipt_update_status($this->max_no);
                if($status=="OK"){
                  $account_update=$this->account_update(0);
                  if($account_update==1){

                    $this->set_delete();
                    $this->load->model('trans_settlement');
                    $this->trans_settlement->delete_penalty_trans($this->max_no,"16");

                    $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","16",$this->max_no);   
                    $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","16",$this->max_no);
                    $this->trans_settlement->delete_settlement_sub("t_opening_bal_trans","16",$this->max_no);


                    for($x = 0; $x<300; $x++){
                      if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
                        if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){ 
                          if($_POST['trans_code'.$x]=="5"){
                            $this->trans_settlement->save_settlement_multi("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],16,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);  
                          }else if($_POST['trans_code'.$x]=="18"){
                              //var_dump("expression");//
                            $this->trans_settlement->save_settlement_multi("t_debit_note_trans",$_POST['customer'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],16,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);   
                          }else if($_POST['trans_code'.$x]=="47"){
                            $this->trans_settlement->t_penalty_trance($_POST['2_'.$x],$_POST['date'],$_POST['customer'],"47",$_POST['2_'.$x],"16",$this->max_no,"0.00",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);
                          }else if($_POST['trans_code'.$x]=="1"){
                            $this->trans_settlement->save_settlement_multi("t_opening_bal_trans",$_POST['customer'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],16,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);   
                          }  

                          if($_POST['is_install_'.$x]=='1'){
                            $this->load->model("t_payment_option");
                            $this->save_install_payment_trans($_POST['cl0_'.$x],$_POST['bc0_'.$x],$_POST['2_'.$x],$_POST['customer'],$_POST['6_'.$x]);
                          }       
                        }
                      }
                    }

                    $this->load->model('t_payment_option');
                    $this->t_payment_option->delete_settlement("t_ins_trans",$_POST['type'],$this->max_no);
                    $this->t_payment_option->delete_all_payments_opt(16,$this->max_no);

                     /* $this->load->model('trans_settlement');
                      $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","16",$this->max_no);   
                      $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","16",$this->max_no);
*/
                      $this->t_payment_option->save_payment_option($this->max_no,16);

                      $this->db->where('nno',$_POST['hid']);
                      $this->db->where('cl',$this->sd['cl']);
                      $this->db->where('bc',$this->sd['branch']);
                      $this->db->update($this->mtb, $t_receipt);

                      
                      if(count($t_receipt_temp)){$this->db->insert_batch("t_receipt_temp",$t_receipt_temp);}

                      /* customer over payment save */
                      $get_balance=(double)$_POST['balance2'];
                      $this->trans_settlement->delete_settlement("t_cus_settlement",16,$this->max_no);
                      if($get_balance>0){
                        $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],16,$this->max_no,16,$this->max_no,$get_balance,"0");  
                      }

                      $this->utility->update_credit_sale_balance($_POST['customer']);
                      $this->utility->update_debit_note_balance($_POST['customer']);
                      $this->utility->update_credit_note_balance($_POST['customer']);

                      $this->account_update(1);

                      $this->utility->save_logger("EDIT",16,$this->max_no,$this->mod);

                      echo $this->db->trans_commit();
                    }else{
                      echo "Invalid account entries";
                      $this->db->trans_commit();
                    }
                  }else{
                    echo $status;
                    echo $this->db->trans_commit();
                  }
                }else{
                  $this->db->trans_commit();
                  echo "No permission to edit records";
                }
              } 
              
            }else{
              echo $validation_status;
              $this->db->trans_commit();
            }
          }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
          }
        }

        public function account_update($condition){

          $this->db->where("trans_no", $this->max_no);
          $this->db->where("trans_code", 16);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->delete("t_check_double_entry");

          $sql="SELECT name FROM m_customer
          WHERE `code`='".$_POST['customer']."' ";

          $cus_name=$this->db->query($sql)->first_row()->name;

      /*if($condition==1){
        if ($_POST['hid'] != "0" || $_POST['hid'] != "") {
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_code',16);
            $this->db->where('trans_no',$this->max_no);
            $this->db->delete('t_account_trans');
        }
      }*/


      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

      }else{
        if($condition=="1"){
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('trans_code',16);
          $this->db->where('trans_no',$this->max_no);
          $this->db->delete('t_account_trans');
        }
      }

      $config = array(
        "ddate" => $_POST['date'],
        "trans_code"=>16,
        "trans_no"=>$this->max_no,
        "op_acc"=>0,
        "reconcile"=>0,
        "cheque_no"=>0,
        "narration"=>"",
        "ref_no" => $_POST['ref_no']
        );
      
      $des = $_POST['memo'];
      $this->load->model('account');
      $this->account->set_data($config);

      $total_amount=(double)$_POST['net']-(double)$_POST['discount'];
      
      $this->account->set_value2($des,  $total_amount, "cr", $_POST['customer'],$condition);

      if(isset($_POST['cash']) && !empty($_POST['cash']) && $_POST['cash']>0){
        $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
        $this->account->set_value2($cus_name, $_POST['cash'], "dr", $acc_code,$condition);    
      }

      if(isset($_POST['discount']) && !empty($_POST['discount'])  && $_POST['discount']>0){
        $this->account->set_value2('Issued Discount', $_POST['discount'], "cr", $_POST['customer'],$condition);   
        $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
        $this->account->set_value2('Issued Discount', $_POST['discount'], "dr", $acc_code,$condition); 
      }

      if(isset($_POST['cheque_recieve']) && !empty($_POST['cheque_recieve']) && $_POST['cheque_recieve']>0){
        $acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
        $this->account->set_value2($cus_name, $_POST['cheque_recieve'], "dr", $acc_code,$condition);    
      }

      if(isset($_POST['pdchq']) && !empty($_POST['pdchq']) && $_POST['pdchq']>0){
        $acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
        $this->account->set_value2($cus_name, $_POST['pdchq'], "dr", $acc_code,$condition);    
      }

    /*if(isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card']>0){
      $acc_code = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
      $this->account->set_value2($des, $_POST['credit_card'], "dr", $acc_code,$condition);    
    }*/

    if(isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card']>0){
      for($x = 0; $x<25; $x++){
        if(isset($_POST['type1_'.$x]) && isset($_POST['amount1_'.$x]) && isset($_POST['bank1_'.$x]) && isset($_POST['no1_'.$x])){
          if(!empty($_POST['type1_'.$x]) && !empty($_POST['amount1_'.$x]) && !empty($_POST['bank1_'.$x]) && !empty($_POST['no1_'.$x])){
            $acc_code = $_POST['acc1_'.$x];
            $this->account->set_value2($des, $_POST['amount1_'.$x], "dr", $acc_code,$condition);    
            
            $acc_code_dr = $this->utility->get_default_acc('CREDIT_CARD_INTEREST');
            $this->account->set_value2('Merchant commission', $_POST['amount_rate1_'.$x], "dr", $acc_code_dr,$condition);    

            $acc_code_cr = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
            $this->account->set_value2('Merchant commission', $_POST['amount_rate1_'.$x], "cr", $acc_code_cr,$condition); 
            
          }
        }
      }  
    }

    if(count($this->interest)){
      foreach($this->interest as $row){
        if($row['int_amount']>0){
          $acc_code = $this->utility->get_default_acc('INTEREST_SUSPENCE');
          $this->account->set_value2("Interest Suspence - (".$row['inv_no'].")", $row['int_amount'], "dr", $acc_code,$condition); 

          $acc_code = $this->utility->get_default_acc('INTEREST_INCOME');
          $this->account->set_value2("Interest Income - (".$row['inv_no'].")", $row['int_amount'], "cr", $acc_code,$condition); 
        }
      }
    }



    // if(isset($_POST['credit']) && !empty($_POST['credit'])){
    //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
    //   $this->account->set_value2($des, $_POST['credit'], "dr", $acc_code,$condition);    
    // }

    // if(isset($_POST['cheque_issue']) && !empty($_POST['cheque_issue'])){
    //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
    //   $this->account->set_value2($des, $_POST['cheque_issue'], "dr", $acc_code,$condition);    
    // }


    if(isset($_POST['credit_note']) && !empty($_POST['credit_note']) && $_POST['credit_note'] >0){
      $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
      $this->account->set_value2($des, $_POST['credit_note'], "dr", $acc_code,$condition);    
    }

    // if(isset($_POST['debit_note']) && !empty($_POST['debit_note'])){
    //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
    //   $this->account->set_value2($des, $_POST['debit_note'], "dr", $acc_code,$condition);    
    // }


    if(isset($_POST['gv']) && !empty($_POST['gv'])){
      $acc_code = $this->utility->get_default_acc('GIFT_VOUCHER_IN_HAND');
      $this->account->set_value2($des, $_POST['gv'], "dr", $acc_code,$condition);    
    }

    // if(isset($_POST['pc']) && !empty($_POST['pc'])){
    //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
    //   $this->account->set_value2($des, $_POST['pc'], "dr", $acc_code,$condition);    
    // }
    
    // if(isset($_POST['installment']) && !empty($_POST['installment'])){
    //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
    //   $this->account->set_value2($des, $_POST['installment'], "dr", $acc_code,$condition);    
    // } 


    
    if($condition==0){
      $query = $this->db->query("
       SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
       FROM `t_check_double_entry` t
       LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
       WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='16'  AND t.`trans_no` ='" . $this->max_no . "' AND 
       a.`is_control_acc`='0'");

      if($query->row()->ok=="0"){
        $this->db->where("trans_no", $_POST['hid']);
        $this->db->where("trans_code", 16);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      }else{
        return "1";
      }
    }
  }  

  private function set_delete(){
    $this->db->where('sub_trans_no',$_POST['hid']);
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
    $this->db->where("sub_trans_code",16);
    $this->db->delete("t_cus_settlement");

    $this->db->where('nno',$_POST['hid']);
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
    $this->db->delete("t_receipt_temp");

    $this->db->where('sub_trans_no',$_POST['hid']);
    $this->db->where("sub_trans_code",16);
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
    $this->db->delete("t_ins_trans");

    $this->db->where('nno',$_POST['hid']);
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
    $this->db->delete("t_receipt_install_temp");
    
    $this->load->model('t_payment_option');
    $this->t_payment_option->update_pd_chq_status(16, $_POST['hid']);

    $this->db->query("UPDATE t_ins_schedule i, (SELECT  sub_cl, sub_bc, trans_no, ins_no
      , SUM(CASE WHEN ins_trans_code='25' THEN cr END) AS ins_paid
      , SUM(CASE WHEN ins_trans_code='26' THEN cr END) AS capital_paid
      , SUM(CASE WHEN ins_trans_code='27' THEN cr END) AS int_paid
      FROM t_ins_trans WHERE trans_no='$_POST[hid]' GROUP BY sub_cl, sub_bc, trans_no, ins_no) it
      SET i.ins_paid=it.ins_paid, i.capital_paid=it.capital_paid, i.int_paid=it.int_paid
      WHERE i.agr_no=it.trans_no AND i.ins_no=it.ins_no AND (i.cl=it.sub_cl) AND (i.bc=it.sub_bc)");
  }

  
  public function check_code(){
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    echo $this->db->get($this->mtb)->num_rows;
  }
  

  public function load(){
    $this->db->select(array(
      't_receipt.cus_acc as cus_acc' ,
      'm_customer.name as cus_name',
      't_receipt.memo',
      't_receipt.rep',
      'm_employee.name as emp_name',
      't_receipt.payment',
      't_receipt.ref_no',
      't_receipt.ddate',
      't_receipt.payment',
      't_receipt.is_multi_payment',
      't_receipt.is_multi_branch',
      't_receipt.is_cancel' ,
      't_receipt.settle_balance',
      't_receipt.settle_amount',
      't_receipt.balance',
      't_receipt.pay_cash' ,
      't_receipt.pay_issue_chq',
      't_receipt.pay_receive_chq',
      't_receipt.pay_post_dated_chq',
      't_receipt.pay_ccard' ,
      't_receipt.pay_cnote' ,
      't_receipt.pay_dnote' ,
      't_receipt.pay_bank_debit' ,
      't_receipt.pay_advance' ,
      't_receipt.pay_discount' ,
      't_receipt.pay_gift_voucher' ,
      't_receipt.pay_credit' ,
      't_receipt.pay_privi_card'
      ));

    $this->db->from('t_receipt');
    $this->db->join('m_customer','m_customer.code=t_receipt.cus_acc');
    $this->db->join('m_employee','m_employee.code=t_receipt.rep');
    $this->db->where('t_receipt.cl',$this->sd['cl'] );
    $this->db->where('t_receipt.bc',$this->sd['branch'] );
    $this->db->where('t_receipt.nno',$_POST['id']);
    $query=$this->db->get();
    
    $x=0;
    if($query->num_rows()>0){
      $a['sum']=$query->result();
    }else{
      $x=2;
    }

    $id=$_POST['id'];
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];

    $this->db->select(array(
      't_receipt_temp.to_cl' ,
      't_receipt_temp.to_bc',
      't_receipt_temp.trans_code',
      't_receipt_temp.trans_no',
      't_receipt_temp.date',
      't_receipt_temp.amount',
      't_receipt_temp.balance',
      't_receipt_temp.payment',
      't_receipt_temp.description as des1',
      't_trans_code.description',
      't_receipt_temp.is_install',
      't_receipt_temp.is_penalty'
      ));


    $this->db->from('t_receipt_temp');
    $this->db->join('t_trans_code','t_trans_code.code=t_receipt_temp.trans_code');
    $this->db->where('t_receipt_temp.cl',$this->sd['cl'] );
    $this->db->where('t_receipt_temp.bc',$this->sd['branch'] );
    $this->db->where('t_receipt_temp.nno',$_POST['id']);
    $this->db->order_by("t_receipt_temp.order_num", "asc"); 
    $query=$this->db->get();       


    if($query->num_rows()>0){
      $a['det']=$query->result();
    }else{
      $x=2;
    }

    $this->db->select(array(
      't_receipt_install_temp.cl' ,
      't_receipt_install_temp.bc',
      't_receipt_install_temp.nno',
      't_receipt_install_temp.ins_no',
      't_receipt_install_temp.due_date',
      't_receipt_install_temp.capital',
      't_receipt_install_temp.capital_paid',
      't_receipt_install_temp.interest',
      't_receipt_install_temp.interest_paid',
      't_receipt_install_temp.balance',
      't_receipt_install_temp.install_paid'
      ));


    $this->db->from('t_receipt_install_temp');
    $this->db->where('t_receipt_install_temp.cl',$this->sd['cl'] );
    $this->db->where('t_receipt_install_temp.bc',$this->sd['branch'] );
    $this->db->where('t_receipt_install_temp.nno',$_POST['id']);
    $query=$this->db->get(); 

    if($query->num_rows()>0){
      $a['install_det']=$query->result();
    }else{
      $a['install_det']=2;
    }

    if($x==0){
      echo json_encode($a);
    }else{
      echo json_encode($x);
    }

  }
  public function account_update_delete(){
    $sql="INSERT INTO t_account_trans (cl,bc,ddate,trans_code,trans_no
    , `acc_code`
    ,description,cr_amount,dr_amount, op_acc,reconcile,oc,ref_no,cheque_no
    ,`is_control_acc`,`narration`)

    SELECT * FROM (SELECT cl,bc,ddate,trans_code,trans_no
    , `acc_code`
    ,'Canceled Transaction',dr_amount AS cr_amount,cr_amount AS dr_amount, op_acc,reconcile,oc,ref_no,cheque_no
    ,`is_control_acc`,`narration` FROM t_account_trans
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
    AND trans_code='16' AND trans_no='".$_POST['trans_no']."' ) t";

    $result = $this->db->query($sql);
  }  
  
  public function delete(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
      if($this->user_permissions->is_delete('t_receipt')){

        $status=$this->trans_cancellation->receipt_update_status();
        if($status=="OK"){

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('sub_trans_no',$_POST['trans_no']);
          $this->db->where('sub_trans_code',16);
          $this->db->delete('t_debit_note_trans');

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('sub_trans_no',$_POST['trans_no']);
          $this->db->where('sub_trans_code',16);
          $this->db->delete('t_opening_bal_trans');

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('sub_trans_no',$_POST['trans_no']);
          $this->db->where('sub_trans_code',16);
          $this->db->delete('t_cus_settlement');

          $this->db->where('sub_trans_no',$_POST['trans_no']);
          $this->db->where("sub_trans_code",16);
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->delete("t_ins_trans");

          $this->db->where('nno',$_POST['trans_no']);
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->delete("t_receipt_install_temp");

          $this->db->query("UPDATE t_ins_schedule i, (SELECT  sub_cl, sub_bc, trans_no, ins_no
            , SUM(CASE WHEN ins_trans_code='25' THEN cr END) AS ins_paid
            , SUM(CASE WHEN ins_trans_code='26' THEN cr END) AS capital_paid
            , SUM(CASE WHEN ins_trans_code='27' THEN cr END) AS int_paid
            FROM t_ins_trans WHERE trans_no='$_POST[trans_no]' GROUP BY sub_cl, sub_bc, trans_no, ins_no) it
            SET i.ins_paid=it.ins_paid, i.capital_paid=it.capital_paid, i.int_paid=it.int_paid
            WHERE i.agr_no=it.trans_no AND i.ins_no=it.ins_no AND (i.cl=it.sub_cl) AND (i.bc=it.sub_bc)");
          

          $data=array('is_cancel'=>'1');
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('nno',$_POST['trans_no']);
          $this->db->update('t_receipt',$data);

          $this->load->model('trans_settlement');
          $this->trans_settlement->delete_penalty_trans($_POST['trans_no'],"16");

          $this->utility->save_logger("CANCEL",16,$_POST['trans_no'],$this->mod);

          $sql="SELECT cus_acc FROM t_receipt WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
          $cus_id=$this->db->query($sql)->first_row()->cus_acc;

          $this->utility->update_credit_sale_balance($cus_id);
          $this->utility->update_debit_note_balance($cus_id);
          $this->utility->update_credit_note_balance($cus_id);

          $this->account_update_delete();

          $this->db->query("INSERT INTO t_cheque_received_cancel SELECT * FROM t_cheque_received WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND trans_code='16' AND trans_no ='".$_POST['trans_no']."'");
          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("trans_code",16);
          $this->db->where("trans_no",$_POST['trans_no']);
          $this->db->delete("t_cheque_received");   

          $this->load->model('t_payment_option');
          $this->t_payment_option->delete_gift_voucher_settled(16, $_POST['trans_no']);
          $this->t_payment_option->update_pd_chq_status(16, $_POST['trans_no']);

          echo $this->db->trans_commit();
        }else{
          echo $status;
        }
      }else{
        $this->db->trans_commit();
        echo "No permission to delete records";
      }  
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo $e->getMessage()." - Operation fail please contact admin"; 
    }  
  }
  


  public function get_next_no(){
    if(isset($_POST['hid'])){
      if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
        $field="nno";
        $this->db->select_max($field);
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);    
        return $this->db->get($this->mtb)->first_row()->$field+1;
      }else{
        return $_POST['hid'];  
      }
    }else{
      $field="nno";
      $this->db->select_max($field);
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);    
      return $this->db->get($this->mtb)->first_row()->$field+1;
    }
  }


  public function load_customer_details(){

    $inv_no=$_POST['inv_no'];
    $acc_code=$_POST['customer_id'];
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];

     /* $sql="SELECT t.* , tt.description FROM (
            SELECT s.cl, s.bc, 47 AS `type`,1 as order_num, s.nno AS trans_no, 'DEFAULT INTEREST' AS memo 
            ,MIN(s.ddate) AS ddate, SUM(dr) AS amount, SUM(dr)-SUM(cr) AS  balance FROM t_penalty_trance s 
            WHERE cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') GROUP BY s.cl, s.bc, s.nno HAVING SUM(dr)-SUM(cr) > 0   
            UNION ALL 

            SELECT s.cl, s.bc, s.type, 2 as order_num, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
            FROM t_credit_sales_sum s  WHERE cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') AND (is_install=0) HAVING balance > 0
            UNION ALL
            SELECT i.cl, i.bc, '5' AS TYPE,3 as order_num, i.trans_no, 'Installment Base' AS memo, s.ddate, SUM(ins_amount + penalty_amount) AS amount  
            , SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid)) AS balance 
            FROM t_ins_schedule i 
            INNER JOIN (SELECT cus_id, ddate, nno, net_amount, is_install FROM t_credit_sales_sum WHERE cl='$cl' AND bc='$bc') s ON s.nno=i.trans_no
            WHERE  cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') AND (is_install=1)
            GROUP BY i.cl, i.bc, i.trans_no, s.ddate
            HAVING   SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid))>0
            UNION ALL 
            SELECT s.cl, s.bc, s.type,4 as order_num,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
            FROM t_debit_note s 
            INNER JOIN (SELECT t.sub_cl AS cl,t.sub_bc AS bc,t.trans_code,t.trans_no,t.acc_code
                    FROM t_debit_note_trans t 
                    WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
                    GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code) t 
            ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
            AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' 
            HAVING balance > 0) t 
            JOIN t_trans_code tt ON tt.code = t.type
            ORDER BY t.order_num,t.ddate , t.trans_no";   */ 

            if($inv_no!=""){
              $sql="SELECT t.* , tt.description FROM (
              SELECT s.cl, s.bc, 47 AS `type`,1 as order_num, s.nno AS trans_no, 'DEFAULT INTEREST' AS memo 
              ,MIN(s.ddate) AS ddate, SUM(dr) AS amount, SUM(dr)-SUM(cr) AS  balance FROM t_penalty_trance s 
              WHERE cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') AND s.nno='$inv_no' GROUP BY s.cl, s.bc, s.nno HAVING SUM(dr)-SUM(cr) > 0   
              UNION ALL 

              SELECT s.cl, s.bc, s.type, 2 as order_num, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
              FROM t_credit_sales_sum s  WHERE cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') AND s.nno='$inv_no' AND (is_install=0) AND is_cancel='0' HAVING balance > 0
              UNION ALL
              SELECT i.cl, i.bc, '5' AS TYPE,3 as order_num, i.trans_no, 'Installment Base' AS memo, s.ddate, SUM(ins_amount + penalty_amount) AS amount  
              , SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid)) AS balance 
              FROM t_ins_schedule i 
              INNER JOIN (SELECT cus_id, ddate, nno, net_amount, is_install FROM t_credit_sales_sum WHERE cl='$cl' AND bc='$bc' AND is_cancel='0') s ON s.nno=i.trans_no
              WHERE  cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') AND (is_install=1) 
              GROUP BY i.cl, i.bc, i.trans_no, s.ddate
              HAVING   SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid))>0
              UNION ALL 
              SELECT tr.cl, 
              tr.bc, 
              1 AS TYPE,
              6 AS order_num,
              tr.trans_no AS trans_no, 
              'Records from opening balance' AS memo, 
              s.date AS ddate, 
              SUM(tr.dr) AS amount,
              SUM(tr.dr)-SUM(tr.cr) AS balance  
              FROM t_opening_bal_trans tr 
              JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc
              WHERE tr.acc_code='$acc_code' AND tr.sub_cl='$cl' AND tr.sub_bc='$bc'
              GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code,s.no
              HAVING balance >0
              LIMIT 1
              UNION ALL
              SELECT s.cl, s.bc, s.type,4 as order_num,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
              FROM t_debit_note s 
              INNER JOIN (SELECT t.sub_cl AS cl,t.sub_bc AS bc,t.trans_code,t.trans_no,t.acc_code
              FROM t_debit_note_trans t 
              WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
              GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code) t 
              ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
              AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' AND t.trans_no='$inv_no'
              HAVING balance > 0) t 
              JOIN t_trans_code tt ON tt.code = t.type
              ORDER BY t.order_num,t.ddate , t.trans_no"; 
            }else{
              $sql="SELECT t.* , tt.description FROM (
              SELECT s.cl, s.bc, 47 AS `type`,1 as order_num, s.nno AS trans_no, 'DEFAULT INTEREST' AS memo 
              ,MIN(s.ddate) AS ddate, SUM(dr) AS amount, SUM(dr)-SUM(cr) AS  balance FROM t_penalty_trance s 
              WHERE cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') GROUP BY s.cl, s.bc, s.nno HAVING SUM(dr)-SUM(cr) > 0   
              UNION ALL 

              SELECT s.cl, s.bc, s.type, 2 as order_num, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
              FROM t_credit_sales_sum s  WHERE cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') AND (is_install=0) AND is_cancel='0' HAVING balance > 0
              UNION ALL
              SELECT i.cl, i.bc, '5' AS TYPE,3 as order_num, i.trans_no, 'Installment Base' AS memo, s.ddate, SUM(ins_amount + penalty_amount) AS amount  
              , SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid)) AS balance 
              FROM t_ins_schedule i 
              INNER JOIN (SELECT cus_id, ddate, nno, net_amount, is_install FROM t_credit_sales_sum WHERE cl='$cl' AND bc='$bc' AND is_cancel='0') s ON s.nno=i.trans_no
              WHERE  cus_id='$acc_code' AND (cl='$cl') AND (bc='$bc') AND (is_install=1)
              GROUP BY i.cl, i.bc, i.trans_no, s.ddate
              HAVING   SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid))>0
              UNION ALL 
              SELECT tr.cl, 
              tr.bc, 
              1 AS TYPE,
              6 AS order_num,
              tr.trans_no AS trans_no, 
              'Records from opening balance' AS memo, 
              s.date AS ddate, 
              SUM(tr.dr) AS amount,
              SUM(tr.dr)-SUM(tr.cr) AS balance  
              FROM t_opening_bal_trans tr 
              JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc
              WHERE tr.acc_code='$acc_code' AND tr.sub_cl='$cl' AND tr.sub_bc='$bc'
              GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code,s.no
              HAVING balance >0
              LIMIT 1
              UNION ALL
              SELECT s.cl, s.bc, s.type,4 as order_num,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
              FROM t_debit_note s 
              INNER JOIN (SELECT t.sub_cl AS cl,t.sub_bc AS bc,t.trans_code,t.trans_no,t.acc_code
              FROM t_debit_note_trans t 
              WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
              GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code) t 
              ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
              AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' 
              HAVING balance > 0) t 
              JOIN t_trans_code tt ON tt.code = t.type
              ORDER BY t.order_num,t.ddate , t.trans_no"; 
            }
            

            $query=$this->db->query($sql);   

            if($query->num_rows()>0){
              $a['det']=$query->result();
              echo json_encode($a);
            }else{
              echo json_encode($a['det']=2);
            }
          }

          public function load_customer_details_all(){
            $acc_code=$_POST['customer_id'];
            $cl=$this->sd['cl'];
            $bc=$this->sd['branch'];
            // $sql="SELECT tc.`description`, d.* FROM( 
            //   SELECT s.cl, s.bc, s.type, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
            //   FROM t_credit_sales_sum s  JOIN (
            //           SELECT t.sub_cl AS cl,t.sub_bc AS bc,
            //             t.trans_code,
            //             t.trans_no,
            //             t.acc_code
            //           FROM t_cus_settlement t
            //           WHERE t.acc_code='$acc_code' AND t.trans_code='5'
            //           GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
            
            //         )t 
            //   ON t.trans_no=s.nno AND  t.acc_code=s.cus_id WHERE s.cus_id='$acc_code' AND t.trans_code='5' 
            //   AND s.bc =t.bc AND s.cl = t.cl  HAVING balance > 0
            //   UNION ALL 
            //   SELECT s.cl, s.bc, s.type,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
            //   FROM t_debit_note s INNER JOIN (
            //           SELECT t.sub_cl AS cl,t.sub_bc AS bc,
            //             t.trans_code,
            //             t.trans_no,
            //             t.acc_code
            //           FROM t_debit_note_trans t
            //           WHERE t.acc_code='$acc_code'
            //           GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
            
            //         ) t 
            //   ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
            //   AND s.bc =t.bc AND s.cl = t.cl  HAVING balance > 0
            // ) d 
            // INNER JOIN t_trans_code tc ON tc.`code` = d.type";

            /* $sql="  SELECT * FROM (
                            SELECT s.cl, s.bc, 47 AS `type`,1 as order_num, s.nno AS trans_no, 'DEFAULT (Penalty Charges)' AS memo 
                            ,MIN(s.ddate) AS ddate, SUM(dr) AS amount, SUM(dr)-SUM(cr) AS  balance FROM t_penalty_trance s 
                            WHERE cus_id='$acc_code'  GROUP BY s.cl, s.bc, s.nno HAVING SUM(dr)-SUM(cr) > 0   
                            UNION ALL 


                            SELECT s.cl, s.bc, s.type, 2 as order_num,s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
                            FROM t_credit_sales_sum s  WHERE cus_id='$acc_code'  AND (is_install=0) HAVING balance > 0
                            UNION ALL
                            SELECT i.cl, i.bc, '5' AS TYPE,3 as order_num, i.trans_no, 'Installment Base' AS memo, s.ddate, SUM(ins_amount + penalty_amount) AS amount  
                            , SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid)) AS balance 
                            FROM t_ins_schedule i 
                            INNER JOIN (SELECT cus_id, ddate, nno, net_amount, is_install FROM t_credit_sales_sum ) s ON s.nno=i.trans_no
                            WHERE  cus_id='$acc_code' AND (is_install=1)
                            GROUP BY i.cl, i.bc, i.trans_no, s.ddate
                            HAVING   SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid))>0
                            UNION ALL 
                            SELECT s.cl, s.bc, s.type,4 as order_num,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
                            FROM t_debit_note s 
                            INNER JOIN (SELECT t.sub_cl AS cl,t.sub_bc AS bc,t.trans_code,t.trans_no,t.acc_code
                                    FROM t_debit_note_trans t 
                                    WHERE t.acc_code='$acc_code' 
                                    GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code) t 
                            ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
                            AND s.bc =t.bc AND s.cl = t.cl HAVING balance > 0) t 
                            ORDER BY t.order_num,t.ddate , t.trans_no";   */
                            
                            $sql="  SELECT t.* , tt.description  FROM (
                            SELECT s.cl, s.bc, 47 AS `type`,1 as order_num, s.nno AS trans_no, 'DEFAULT (Penalty Charges)' AS memo 
                            ,MIN(s.ddate) AS ddate, SUM(dr) AS amount, SUM(dr)-SUM(cr) AS  balance FROM t_penalty_trance s 
                            WHERE cus_id='$acc_code'  GROUP BY s.cl, s.bc, s.nno HAVING SUM(dr)-SUM(cr) > 0   
                            UNION ALL 


                            SELECT s.cl, s.bc, s.type, 2 as order_num,s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
                            FROM t_credit_sales_sum s  WHERE cus_id='$acc_code'  AND (is_install=0) HAVING balance > 0
                            UNION ALL
                            SELECT i.cl, i.bc, '5' AS TYPE,3 as order_num, i.trans_no, 'Installment Base' AS memo, s.ddate, SUM(ins_amount + penalty_amount) AS amount  
                            , SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid)) AS balance 
                            FROM t_ins_schedule i 
                            INNER JOIN (SELECT cus_id, ddate, nno, net_amount, is_install FROM t_credit_sales_sum ) s ON s.nno=i.trans_no
                            WHERE  cus_id='$acc_code' AND (is_install=1)
                            GROUP BY i.cl, i.bc, i.trans_no, s.ddate
                            HAVING   SUM((ins_amount + penalty_amount)- (ins_paid+penalty_paid))>0
                            UNION ALL 
                            SELECT  tr.cl, 
                            tr.bc, 
                            1 AS TYPE,
                            6 AS order_num,
                            tr.trans_no AS trans_no, 
                            'Records from opening balance' AS memo, 
                            s.date AS ddate, 
                            SUM(tr.dr) AS amount,
                            SUM(tr.dr)-SUM(tr.cr) AS balance  
                            FROM t_opening_bal_trans tr 
                            JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc
                            WHERE tr.acc_code='$acc_code'
                            GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code,s.no
                            HAVING balance >0
                            LIMIT 1
                            UNION ALL 
                            SELECT s.cl, s.bc, s.type,4 as order_num,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
                            FROM t_debit_note s 
                            INNER JOIN (SELECT t.sub_cl AS cl,t.sub_bc AS bc,t.trans_code,t.trans_no,t.acc_code
                            FROM t_debit_note_trans t 
                            WHERE t.acc_code='$acc_code' 
                            GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code) t 
                            ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
                            AND s.bc =t.bc AND s.cl = t.cl HAVING balance > 0) t 
                            JOIN t_trans_code tt ON tt.code = t.type
                            ORDER BY t.order_num,t.ddate , t.trans_no";   

                            $query=$this->db->query($sql);  

                            if($query->num_rows()>0){
                              $a['det']=$query->result();
                              echo json_encode($a);
                            }else{
                              echo json_encode($a['det']=2);
                            }
                            
                          }


                          function load_customer_balance(){
                            $acc_code=$_POST['customer_id']; 
                            $cl=$this->sd['cl'];
                            $bc=$this->sd['branch'];
                            $sql="SELECT SUM(balance) AS balance FROM(
                            SELECT SUM(balance) AS balance FROM t_credit_sales_sum WHERE t_credit_sales_sum.cus_id='$acc_code' AND cl='$cl' AND bc='$bc'  
                            UNION ALL
                            SELECT SUM(dr)-SUM(cr) AS balance FROM t_opening_bal_trans WHERE acc_code='$acc_code' AND cl='$cl' AND bc='$bc' 
                            UNION ALL 
                            SELECT SUM(balance) AS balance FROM t_debit_note WHERE t_debit_note.code='$acc_code' AND cl='$cl' AND bc='$bc' 
                            ) AS a";       
                            echo $this->db->query($sql)->row()->balance;
                          } 


                          function load_customer_balance_all(){
                            $acc_code=$_POST['customer_id']; 
                            $cl=$this->sd['cl'];
                            $bc=$this->sd['branch'];
                            $sql="SELECT SUM(balance) AS balance FROM(
                            SELECT SUM(balance) AS balance FROM t_credit_sales_sum WHERE t_credit_sales_sum.cus_id='$acc_code'   
                            UNION ALL 
                            SELECT SUM(dr)-SUM(cr) AS balance FROM t_opening_bal_trans WHERE acc_code='$acc_code' AND cl='$cl' AND bc='$bc'  
                            UNION ALL 
                            SELECT SUM(balance) AS balance FROM t_debit_note WHERE t_debit_note.code='$acc_code' 
                            ) AS a";       

                            echo $this->db->query($sql)->row()->balance;
                          } 

                          public function get_payment_option(){
                            $this->db->where("code",$_POST['code']);
                            $data['result']=$this->db->get("r_payment_option")->result();
                            echo json_encode($data);
                          } 


                          public function get_max_no(){
                            $field="nno";
                            $this->db->select_max($field);
                            $this->db->where("cl",$this->sd['cl']);
                            $this->db->where("bc",$this->sd['branch']);    
                            echo $this->db->get($this->mtb)->first_row()->$field+1;
                          }

                          public function install_payment_shedule(){
                           $cl=$_POST['cl'];
                           $bc=$_POST['bc'];
                           $no=$_POST['no']; 
                           $paid=(double)$_POST['paid'];

                           $html="<table style='width:100%;border:1px solid #fff' >
                           <thead style='background:#69F;color:#fff;font-weight:bold;'><tr>
                            <td>Install No</td>
                            <td>Due Date</td>
                            <td>Capital</td>
                            <td style='width:100px'>Capital Paid</td>
                            <td>Interest</td>
                            <td style='width:100px'>Interest Paid</td>
                            <td style='width:100px'>Interest Discount</td>
                            <td style='width:100px'>Balance</td>
                            <td style='width:100px'>Installment Paid</td></tr></thead>";
                            $html .=""; 

                            $total_capital=0;
                            $total_capital_paid=0;
                            $total_interest=0;
                            $total_interest_paid=0;
                            $total_installment=0;
                            $total_installment_paid=0;
                            $total_balance=0;
                            $total_paid=0;
                            $x=0;
                            $balance=$paid;

                            if($_POST['hid']!=0){
                              $sql="SELECT * FROM t_receipt_install_temp WHERE sub_cl='$cl' AND sub_bc='$bc' AND sub_nno=$no AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['hid']."'";
                              $query=$this->db->query($sql);
                              foreach($query->result() as $row){

                                $total_capital=$row->capital+$total_capital;
                                $total_capital_paid=$row->capital_paid+$total_capital_paid;
                                $total_interest=$row->interest+$total_interest;
                                $total_interest_paid=$row->interest_paid;
                                $total_balance=($total_interest+$total_capital);
                                $total_paid=($total_interest_paid+$total_capital_paid);


       // $total_balance=$total_balance+$balance_amount;

                                $html.="<tr><td style='text-align:center;border:1px solid #ccc;background:#F9F9EC;width:50px;'>".$row->ins_no."</td>";
                                $html.="<td style='text-align:center;border:1px solid #ccc;background:#F9F9EC'>".$row->due_date."</td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".number_format($row->capital, 2, '.', '')."</td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' value='".number_format($row->capital_paid, 2, '.', '')."' readonly='readonly' name='cp_amt_'".$x."' id='cp_amt_'".$x."' style='width:100px;text-align:right;border:none;'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".number_format($row->interest, 2, '.', '')."</td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' value='".number_format($row->interest_paid, 2, '.', '')."' readonly='readonly' name='int_amt_'".$x."' id='int_amt_'".$x."' style='width:100px;text-align:right;border:none;'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' name='int_dis_'".$x."' id='int_dis_'".$x."' readonly='readonly' style='width:100px;text-align:right;border:none;'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' value='".number_format($row->balance, 2, '.', '')."' readonly='readonly' name='balance_amt_'".$x."' id='balance_amt_'".$x."' style='width:100px;text-align:right;border:none;'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' value='".number_format($row->install_paid, 2, '.', '')."' readonly='readonly' name='paid_amt_'".$x."' id='paid_amt_'".$x."' style='width:100px;text-align:right;border:none;'/></td></tr>";
                              }


                              $html.= "<tr style='background:#e6eeff;color:#000;font-weight:bold;' >
                              <td style='text-align:center;width:50px'>&nbsp;</td>
                              <td style='text-align:right;width:100px'>Total</td>
                              <td style='text-align:right;width:100px'>".number_format($total_capital, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_capital_paid, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_interest, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_interest_paid, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'></td>
                              <td style='text-align:right;width:100px'>".number_format($total_balance, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_paid, 2, '.', '')."</td></tr>";

                            }else{

                              $sql="SELECT * FROM t_ins_schedule WHERE cl='$cl' AND bc='$bc' AND trans_no='$no' HAVING (ins_amount)-(ins_paid)>0 OR (capital_amount)-(capital_paid)>0 ";
                              $query=$this->db->query($sql);

                              foreach($query->result() as $row){
                               
                                $actual_int_amount=$row->int_amount;
                                $actual_capital_amount=$row->capital_amount;
                                $int_paid=$row->int_paid;
                                $capital_paid=$row->capital_paid;

                                $total_capital=$total_capital+$row->capital_amount;
                                $total_interest=$total_interest+$row->int_amount;
                                $total_installment=$total_installment+$row->ins_amount;

                                $int_amount=$actual_int_amount-$int_paid;
                                $capital_amount=$actual_capital_amount-$capital_paid;

                                if($balance<=$int_amount){
                                  $int_amount=$balance;
                                  $balance=0;
                                  $total_interest_paid=$total_interest_paid+$int_amount;

                                }else if($balance>=$int_amount){

                                  $balance=$balance-$int_amount;
                                  $total_interest_paid=$total_interest_paid+$int_amount;
                                }

                                if($balance<=$capital_amount){
                                  $capital_amount=$balance;
                                  $balance=0;
                                  $total_capital_paid=$total_capital_paid+$capital_amount;
                                }else if($balance>$capital_amount){
                                  $balance=$balance-$capital_amount;
                                  $total_capital_paid=$total_capital_paid+$capital_amount;
                                }

                                $paid_amount=$capital_amount+$int_amount;
                                $total_paid=$total_paid+$paid_amount;
                                $balance_amount=($row->int_amount+$row->capital_amount);
                                $total_balance=$total_balance+$balance_amount;

         //var_dump($paid_amount);

                                $html.="<tr><td style='text-align:center;border:1px solid #ccc;background:#F9F9EC;width:50px;'>".$row->ins_no."</td>";
                                $html.="<td style='text-align:center;border:1px solid #ccc;background:#F9F9EC'>".$row->due_date."</td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".number_format($row->capital_amount, 2, '.', '')."</td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc;'><input type='text' value='".number_format(($capital_paid+$capital_amount), 2, '.', '')."' name='cp_amt_'".$x."' id='cp_amt_'".$x."' style='width:100px;text-align:right;border:none;' readonly='readonly'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".number_format($row->int_amount, 2, '.', '')."</td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' value='".number_format(($int_paid+$int_amount), 2, '.', '')."' name='int_amt_'".$x."' id='int_amt_'".$x."' style='width:100px;text-align:right;border:none;' readonly='readonly'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' name='int_dis_'".$x."' id='int_dis_'".$x."' style='width:100px;text-align:right;border:none;' readonly='readonly'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' value='".number_format($balance_amount, 2, '.', '')."' name='balance_amt_'".$x."' id='balance_amt_'".$x."' readonly='readonly' style='width:100px;text-align:right;border:none;'/></td>";
                                $html.="<td style='text-align:right;border:1px solid #ccc'><input type='text' value='".number_format($paid_amount, 2, '.', '')."' name='paid_amt_'".$x."' id='paid_amt_'".$x."' readonly='readonly' style='width:100px;text-align:right;border:none;'/></td></tr>";
                                $x++;         
                              }

                              $html.= "<tr style='background:#e6eeff;color:#000;font-weight:bold;' >
                              <td style='text-align:center;width:50px'>&nbsp;</td>
                              <td style='text-align:right;width:100px'>Total</td>
                              <td style='text-align:right;width:100px'>".number_format($total_capital, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_capital_paid, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_interest, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_interest_paid, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'></td>
                              <td style='text-align:right;width:100px'>".number_format($total_balance, 2, '.', '')."</td>
                              <td style='text-align:right;width:100px'>".number_format($total_paid, 2, '.', '')."</td></tr>";
                              
                            } 
                            


                            echo $html;
                          }


                          public function save_install_payment_trans($cl,$bc,$no,$customer,$amount){
                            $amount=(double)$amount; 

                            $sql="SELECT * FROM t_ins_schedule WHERE cl='$cl' AND bc='$bc' AND trans_no='$no' HAVING (ins_amount)-(ins_paid)>0 OR (capital_amount)-(capital_paid)>0 ";
                            $query=$this->db->query($sql);
                            $due_date="";

                            foreach($query->result() as $row){
                              $actual_int_amount=$row->int_amount;
                              $actual_capital_amount=$row->capital_amount;
                              $int_paid=$row->int_paid;
                              $capital_paid=$row->capital_paid;

                              $due_date= $row->due_date;
                              $ins_no=$row->ins_no;

                              $int_amount=$actual_int_amount-$int_paid;
                              $capital_amount=$actual_capital_amount-$capital_paid;

       // echo $actual_int_amount."<br/>";
        //echo $int_amount."first<br/>";
                              
                              if($amount<=$int_amount){
                                $int_amount=$amount;
                                $this->t_payment_option->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],5,$no,'27',$this->max_no,"0",$int_amount,$this->max_no,$due_date,$ins_no,16,$cl,$bc); 
                                $amount=0;
                              }else if($amount>=$int_amount){
                                $amount=$amount-$int_amount;
                                $this->t_payment_option->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],5,$no,'27',$this->max_no,"0",$int_amount,$this->max_no,$due_date,$ins_no,16,$cl,$bc); 
                              }

       // echo $int_amount."second<br/>";

                              if($amount<=$capital_amount){
                                $capital_amount=$amount;
                                $this->t_payment_option->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],5,$no,'26',$this->max_no,"0",$capital_amount,$this->max_no,$due_date,$ins_no,16,$cl,$bc);
                                $amount=0;
                              }else if($amount>=$capital_amount){
                                $amount=$amount-$capital_amount;
                                $this->t_payment_option->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],5,$no,'26',$this->max_no,"0",$capital_amount,$this->max_no,$due_date,$ins_no,16,$cl,$bc);
                              }

       // echo $int_amount."third<br/>";

                              $ins_paid=$int_amount+$capital_amount;
                              $this->t_payment_option->save_install_payment("t_ins_trans",$_POST['customer'],$_POST['date'],5,$no,'25',$this->max_no,"0",$ins_paid,$this->max_no,$due_date,$ins_no,16,$cl,$bc);

                              $t_receipt_install_temp[]=array(
                                "cl"=>$this->sd['cl'],
                                "bc"=>$this->sd['branch'],
                                "nno"=>$this->max_no,
                                "ins_no"=>$ins_no,
                                "due_date"=>$row->due_date,
                                "capital"=>$row->capital_amount,
                                "capital_paid"=>$capital_amount,
                                "interest"=>$row->int_amount,
                                "interest_paid"=>$int_amount,
                                "balance"=>($row->int_amount+$row->capital_amount),
                                "install_paid"=>$capital_amount+$int_amount,
                                "sub_cl"=>$cl,
                                "sub_bc"=>$bc,
                                "sub_nno"=>$no
                                );

       // echo $int_amount."--".$no."<br/>";

        // $this->interest=array(
        //   "int_amount"=>$int_amount,
        //   "inv_no"=>$no
        // );


                              if($amount<=0){
                                break;
                              }
                            }

                            if(count($t_receipt_install_temp)){$this->db->insert_batch("t_receipt_install_temp",$t_receipt_install_temp);}

                            $this->db->query("UPDATE t_ins_schedule i, (SELECT  sub_cl, sub_bc, trans_no, ins_no
                              , SUM(CASE WHEN ins_trans_code='25' THEN cr END) AS ins_paid
                              , SUM(CASE WHEN ins_trans_code='26' THEN cr END) AS capital_paid
                              , SUM(CASE WHEN ins_trans_code='27' THEN cr END) AS int_paid
                              FROM t_ins_trans WHERE trans_no='$no' GROUP BY sub_cl, sub_bc, trans_no, ins_no) it
                              SET i.ins_paid=it.ins_paid, i.capital_paid=it.capital_paid, i.int_paid=it.int_paid
                              WHERE i.agr_no=it.trans_no AND i.ins_no=it.ins_no AND (i.cl=it.sub_cl) AND (i.bc=it.sub_bc)");
                          }

                          public function get_interest_calculation(){
                            for($x = 0; $x<300; $x++){


                              if(isset($_POST['is_install_'.$x],$_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
                                if($_POST['is_install_'.$x]==1 && $_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){
                                  $amount=(double)$_POST['6_'.$x];
                                  $cl=$_POST['cl0_'.$x];
                                  $bc=$_POST['bc0_'.$x];
                                  $no=$_POST['2_'.$x];
                                  $sql="SELECT * FROM t_ins_schedule WHERE cl='$cl' AND bc='$bc' AND trans_no='$no' HAVING (ins_amount)-(ins_paid)>0 OR (capital_amount)-(capital_paid)>0 ";
                                  $query=$this->db->query($sql);

                                  foreach($query->result() as $row){
                                    $actual_int_amount=$row->int_amount;
                                    $actual_capital_amount=$row->capital_amount;
                                    $int_paid=$row->int_paid;
                                    $capital_paid=$row->capital_paid;

                                    $due_date= $row->due_date;
                                    $ins_no=$row->ins_no;

                                    $int_amount=$actual_int_amount-$int_paid;
                                    $capital_amount=$actual_capital_amount-$capital_paid;

                                    if($amount<=$int_amount){
                                      $int_amount=$amount;
                                      $amount=0;
                                    }else if($amount>=$int_amount){
                                      $amount=$amount-$int_amount;
                                    }

            // echo $int_amount."second<br/>";

                                    if($amount<=$capital_amount){
                                      $capital_amount=$amount;
                                      $amount=0;
                                    }else if($amount>=$capital_amount){
                                      $amount=$amount-$capital_amount;
                                    }

                                    $this->interest[]=array(
                                      "int_amount"=>$int_amount,
                                      "inv_no"=>$no
                                      );

                                    if($amount<=0){
                                      break;
                                    }

                                  }  
                                }
                              } 
                            }   
                          }

                          public function load_penalty_details(){
                            $cus_id=$_POST['cus'];
                            $cl=$this->sd['cl'];
                            $bc=$this->sd['branch'];
                            $total_paid_amount=$_POST['paid'];

                            $table ="<table style='width:100%'><thead style='background:#69F;color:#fff;font-weight:bold;'>";
                            $table.="<tr><td>Trans No</td>";
                            $table.="<td>Memo</td>";
                            $table.="<td>Date</td>";
                            $table.="<td>Amount</td>";
                            $table.="<td>Balance</td>"; 
                            $table.="<td>Paid Amount</td></tr></thead>";

                            if($_POST['hid'] == "0" || $_POST['hid'] == ""){
                              $sql="SELECT s.cl, s.bc, 47 AS `type`, s.nno AS trans_no, 'DEFAULT INTEREST' AS memo ,MIN(s.ddate) AS ddate, SUM(dr) AS amount, SUM(dr)-SUM(cr) AS  balance FROM t_penalty_trance s
                              WHERE cus_id='$cus_id' AND (cl='$cl') AND (bc='$bc') GROUP BY s.cl, s.bc, s.nno HAVING SUM(dr)-SUM(cr) > 0 
                              ORDER BY ddate";
                              $query=$this->db->query($sql);
                              
                              foreach($query->result() as $row){
                                $paid=0;
                                if($total_paid_amount>$row->balance){
                                  $total_paid_amount=$total_paid_amount-$row->balance;       
                                  $paid=$row->balance;
                                }else{
                                  $paid=$total_paid_amount;
                                }

                                $table.="<tr><td style='text-align:center;border:1px solid #ccc;background:#F9F9EC'>".$row->trans_no."</td>";
                                $table.="<td style='text-align:left;border:1px solid #ccc;background:#F9F9EC'>".$row->memo."</td>";
                                $table.="<td style='text-align:center;border:1px solid #ccc;background:#F9F9EC'>".$row->ddate."</td>";
                                $table.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".$row->amount."</td>";
                                $table.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".$row->balance."</td>";
                                $table.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".$paid."</td></tr>";
                              }
                            }else{
                             $sql="SELECT * FROM t_receipt_default_temp WHERE rec_no='$_POST[hid]' AND (cl='$cl') AND (bc='$bc') ORDER BY ddate";
                             $query=$this->db->query($sql);
                             
                             foreach($query->result() as $row){
                              $table.="<tr><td style='text-align:center;border:1px solid #ccc;background:#F9F9EC'>".$row->inv_no."</td>";
                              $table.="<td style='text-align:left;border:1px solid #ccc;background:#F9F9EC'>".$row->memo."</td>";
                              $table.="<td style='text-align:center;border:1px solid #ccc;background:#F9F9EC'>".$row->ddate."</td>";
                              $table.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".$row->amount."</td>";
                              $table.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".$row->balance."</td>";
                              $table.="<td style='text-align:right;border:1px solid #ccc;background:#F9F9EC'>".$row->paid."</td></tr>";
                            }      
                          }

                          

                          echo $table."</table>";          
                        }


                        public function save_penalty_trance($sub_cl,$sub_bc,$inv_no,$ddate,$customer,$paid){
                          $cl=$this->sd['cl'];
                          $bc=$this->sd['branch'];
                          $total_paid_amount=$paid;
                          
                          $sql="SELECT s.cl, s.bc, 47 AS `type`, s.nno AS trans_no, 'DEFAULT INTEREST' AS memo ,MIN(s.ddate) AS ddate, SUM(dr) AS amount, SUM(dr)-SUM(cr) AS  balance FROM t_penalty_trance s
                          WHERE cus_id='$customer' AND (cl='$cl') AND (bc='$bc') GROUP BY s.cl, s.bc, s.nno HAVING SUM(dr)-SUM(cr) > 0 
                          ORDER BY ddate";             

                          $query=$this->db->query($sql);
                          
                          foreach($query->result() as $row){

                            if($total_paid_amount>$row->balance){
                              $total_paid_amount=$total_paid_amount-$row->balance;       
                              $paid=$row->balance;
                            }else{
                              $paid=$total_paid_amount;
                            }

                            $t_receipt_default_temp[]=array(
                              "cl"=>$this->sd['cl'],
                              "bc"=>$this->sd['branch'],
                              "sub_cl"=>$row->cl,
                              "sub_bc"=>$row->bc,
                              "memo"=>$row->memo,
                              "rec_no"=>$this->max_no,
                              "inv_no"=>$row->trans_no,
                              "ddate"=>$row->ddate,
                              "amount"=>$row->amount,
                              "balance"=>$row->balance,
                              "paid"=>$paid
                              );
                          }
                          if(count($t_receipt_default_temp)){$this->db->insert_batch("t_receipt_default_temp",$t_receipt_default_temp);}
                        }
                      }