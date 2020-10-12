<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_advance_payment extends CI_Model {
    
  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';
  private $trans_code=17;
  private $subtrans_code=24;
  private $ref_trans_code=24;

  function __construct(){
	  parent::__construct();
	
	  $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	  $this->mtb = $this->tables->tb['t_advance_sum'];
	  $this->load->model('trans_settlement');
    $this->load->model('user_permissions');
  }
    
  public function base_details(){
	
	  $this->load->model('m_customer');
    $a['customer']=$this->m_customer->select();
    //$a['max_no'] = $this->get_next_no();
    $a['max_cn_no'] = $this->get_next_cnno();
    $a['max_no']= $this->utility->get_max_no("t_advance_sum","nno");
    $a['type'] = 'ADVANCE';
    return $a;
  }

  public function validation(){
    $status=1;
    
    $this->max_no=$this->utility->get_max_no("t_advance_sum","nno");

    $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_advance_sum');
    if($check_is_delete!=1){
      return "Advance payment already deleted";
    }
    $check_customer_validation=$this->validation->check_is_customer($_POST['customer']);
    if($check_customer_validation!=1){
      return "Please enter valid customer";
    }
    $payment_option_validation = $this->validation->payment_option_calculation();
    if ($payment_option_validation != 1) {
      return $payment_option_validation;
    }
    $check_valid_dr_no=$this->validation->check_valid_trans_no2('customer','t_code_','no2_','cl_','bc_');
    if($check_valid_dr_no!=1){
      return $check_valid_dr_no;
    }
    $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle2('customer','t_code_','no2_','settle2_','cl_','bc_');
    if($check_valid_trans_settle_status!=1){
      return $check_valid_trans_settle_status; 
    }
    $check_valid_dr_no2=$this->validation->check_valid_trans_no2('customer','t_code3_','no3_','cl3_','bc3_');
    if($check_valid_dr_no2!=1){
      return $check_valid_dr_no2;
    }
    $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle2('customer','t_code3_','no3_','settle3_','cl3_','bc3_');
    if($check_valid_trans_settle_status2!=1){
      return $check_valid_trans_settle_status2; 
    } 
    $check_zero_value=$this->validation->empty_net_value($_POST['net']);
      if($check_zero_value!=1){
        return $check_zero_value;
    }
   /* $account_update=$this->account_update(0);
        if($account_update!=1){
            return "Invalid account entries";
        }   */

    return $status;
  }

    public function get_credit_max_no() {
        if (isset($_POST['hid'])) {
            if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                $field = "nno";
                $this->db->select_max($field);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                return $this->db->get("t_credit_note")->first_row()->$field + 1;
            }else{
                return $_POST['cn_no'];
            }
        }else{
            $field = "nno";
            $this->db->select_max($field);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            return $this->db->get("t_credit_note")->first_row()->$field + 1;
        }
    }

  public function save(){
    
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
      $this->credit_max_no = $this->get_credit_max_no();


      $validation_status=$this->validation();
      if($validation_status==1){

        $_POST['acc_codes']=$_POST['customer'];
      	
        $a=array(
          "cl"=>$this->sd['cl'],
          "bc"=>$this->sd['branch'],
          "nno"=>$this->utility->get_max_no("t_advance_sum","nno"),
          "ddate"=>$_POST['date'],
          "ref_no"=>$_POST['ref_no'],
          "acc_code"=>$_POST['customer'],
          "description"=>$_POST['description'],
          "expire_date"=>$_POST['edate'],
          "cash_amount"=>isset($_POST['cash'])?$_POST['cash']:'',
          "card_amount"=>isset($_POST['credit_card'])?$_POST['credit_card']:'',                            
          "cheque_amount"=>isset($_POST['cheque_recieve'])?$_POST['cheque_recieve']:'',
          "total_amount"=>$_POST['net'],
          "cn_no"=>$this->credit_max_no,
          "employee" => $_POST['officer'],
          "oc" => $this->sd['oc']

        );

        $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_advance_payment')){
            $account_update=$this->account_update(0);
              if($account_update==1){
                $this->db->insert($this->mtb, $a);
                $this->trans_settlement->update_crdr_note('t_credit_note',"1", $_POST['date'], $this->credit_max_no,"Advance Payment[".$_POST['id']."]", $_POST['net'],$_POST['ref_no'],$_POST['customer'],"1",$acc_code,$this->ref_trans_code, $_POST['id']);         
                $this->trans_settlement->save_settlement('t_credit_note_trans',$_POST['customer'],$_POST['date'],$this->trans_code ,$_POST['cn_no'],$this->subtrans_code,$_POST['id'],$_POST['net'],0); 
                $this->load->model('t_payment_option');
               
                $this->t_payment_option->save_payment_option($this->max_no,24);
                $this->utility->update_debit_note_balance($_POST['customer']);
                $this->utility->update_credit_note_balance($_POST['customer']);  

                $this->utility->save_logger("SAVE",24,$this->max_no,$this->mod);
                $this->account_update(1);
                echo $this->db->trans_commit()."@".$this->max_no;;
            }else{
              return "Invalid account entries";
              $this->db->trans_commit();
            }
          }else{
              echo "No permission to save records";
              $this->db->trans_commit();
          }  
        }else{
          if($this->user_permissions->is_edit('t_advance_payment')){
            $status=$this->trans_cancellation->advance_status($this->max_no);
            if($status=="OK"){
              $account_update=$this->account_update(0);
              if($account_update==1){

              $this->load->model('t_payment_option');
              $this->t_payment_option->delete_all_payments_opt(24,$this->max_no);

              $this->load->model('trans_settlement');
              $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","24",$this->max_no);   
              $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","24",$this->max_no); 

              $this->t_payment_option->save_payment_option($this->max_no,24);
              $this->utility->update_debit_note_balance($_POST['customer']);
              $this->utility->update_credit_note_balance($_POST['customer']); 
        
              $this->db->where('nno',$this->max_no);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update($this->mtb, $a);

              $_POST['hid']=$this->credit_max_no;
              $this->trans_settlement->update_crdr_note('t_credit_note','0', $_POST['date'], $this->credit_max_no,"Advance Payment[".$_POST['id']."]", $_POST['net'],$_POST['ref_no'],$_POST['customer'],"1",$acc_code,$this->ref_trans_code, $_POST['id']);         
              
              $this->trans_settlement->delete_settlement('t_credit_note_trans',$this->trans_code ,$_POST['cn_no']); 
              $this->trans_settlement->save_settlement('t_credit_note_trans',$_POST['customer'],$_POST['date'],$this->trans_code ,$_POST['cn_no'],$this->subtrans_code,$_POST['id'],$_POST['net'],0); 
             
              $this->utility->save_logger("EDIT",24,$this->max_no,$this->mod);
               $this->account_update(1);
              echo $this->db->trans_commit();
             }else{
                return "Invalid account entries";
                $this->db->trans_commit();
            }
          }else{
            echo $status;
            $this->db->trans_commit();
          } 
        }else{
          echo "No permission to edit records";
          $this->db->trans_commit();
        }   
      }      
      }else{
        echo $validation_status;
        $this->db->trans_commit();
      }
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
    }   
  }
   
   public function account_update($condition){

      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 24);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");

      
      if($_POST['hid']=="0"||$_POST['hid']==""){
            
      }else{
        if($condition=="1"){
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('trans_code',24);
          $this->db->where('trans_no',$this->max_no);
          $this->db->delete('t_account_trans');
        }
      }
      
        $config = array(
          "ddate" => $_POST['date'],
          "trans_code"=>24,
          "trans_no"=>$this->max_no,
          "op_acc"=>0,
          "reconcile"=>0,
          "cheque_no"=>0,
          "narration"=>"",
          "ref_no" => $_POST['ref_no']
       );
            
        $sql="SELECT name FROM m_customer WHERE code='".$_POST['customer']."'";
        $cus=$this->db->query($sql)->row()->name;
        $des = $cus;
        
        $this->load->model('account');
        $this->account->set_data($config);

   // $total_amount=(double)$_POST['net']-(double)$_POST['discount'];
    $total_amount=(double)$_POST['net'];
          
    $this->account->set_value2($_POST['description'],  $total_amount, "cr", $_POST['customer'],$condition);

    if(isset($_POST['cash']) && !empty($_POST['cash']) && $_POST['cash']>0){
      $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
      $this->account->set_value2($des, $_POST['cash'], "dr", $acc_code,$condition);    
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

    if(isset($_POST['gv']) && !empty($_POST['gv'])){
      $acc_code = $this->utility->get_default_acc('GIFT_VOUCHER_IN_HAND');
      $this->account->set_value2('gv', $_POST['gv'], "dr", $acc_code,$condition);    
    }

    if(isset($_POST['cheque_recieve']) && !empty($_POST['cheque_recieve']) && $_POST['cheque_recieve']>0){
      $acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
      $this->account->set_value2($des, $_POST['cheque_recieve'], "dr", $acc_code,$condition);    
    }


 
    if($condition==0){
      $query = $this->db->query("
           SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
           FROM `t_check_double_entry` t
           LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
           WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='24'  AND t.`trans_no` ='" . $this->max_no . "' AND 
           a.`is_control_acc`='0'");

      if($query->row()->ok=="0"){
          $this->db->where("trans_no", $_POST['hid']);
          $this->db->where("trans_code", 24);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->delete("t_check_double_entry");
        return "0";
      }else{
        return "1";
      }
    }
  }  



  public function get_payment_option() {
        $this->db->where("code", $_POST['code']);
        $data['result'] = $this->db->get("r_payment_option")->result();
        echo json_encode($data);
    }


  public function load(){

    $this->db->select(array(
      't_advance_sum.ddate' ,
      't_advance_sum.ref_no',
      't_advance_sum.cn_no',
      't_advance_sum.acc_code',
      't_advance_sum.description' ,
      't_advance_sum.expire_date',
      't_advance_sum.total_amount',
      't_advance_sum.is_cancel',
      't_advance_sum.cash_amount',
      't_advance_sum.cheque_amount',
      't_advance_sum.card_amount',
      't_advance_sum.employee',
      'm_customer.name',
      'm_employee.name as emp_name'    
    ));

    $this->db->from('t_advance_sum');
    $this->db->join('m_customer','t_advance_sum.acc_code=m_customer.code');
    $this->db->join('m_employee','t_advance_sum.employee=m_employee.code','LEFT');
    $this->db->where('t_advance_sum.nno',$_POST['id']);
    $this->db->where("t_advance_sum.cl", $this->sd['cl']);
    $this->db->where("t_advance_sum.bc", $this->sd['branch']);
    $query=$this->db->get();

    if($query->num_rows()>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      echo json_encode("2");
    }
  }

  public function checkdelete(){
    

    $codeeees = $_POST['payment_ids'];
   
    $sql="SELECT *
    FROM `t_advance_sum` 
    WHERE (`t_advance_sum`.`nno` = '$codeeees') ";   

    $query=$this->db->query($sql);

    if($query->num_rows()>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      echo json_encode("2");
    }
  }

  public function deletePaymant(){

  	$pID = $_POST['payment_id'];
  	$nno = $_POST['nno'];
  	$this->trans_settlement->cancel_crdr_note('cr',$nno);



  	$this->db->where('nno',$pID);
  	$this->db->where('cl',$this->sd['cl']);
  	$this->db->where('bc',$this->sd['branch']);
  	echo $this->db->update('t_advance_sum', array("is_cancel"=>1));
  
  }

  public function delete(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try {
      if($this->user_permissions->is_delete('t_advance_payment')){
        $nno=$_POST['no'];
        $status="OK";
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

         if($status=="OK"){
           $trans_code=24;
           $table_trans='t_credit_note_trans';
                   
            $this->db->where("cl",$cl);
            $this->db->where("bc",$bc);
            $this->db->where("trans_code",$trans_code);
            $this->db->where("trans_no",$nno);
            $this->db->delete('t_credit_note_trans');

            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_code','24');
            $this->db->where('trans_no',$nno);
            $this->db->delete('t_account_trans');
        
            $this->db->where('nno',$nno);
            $this->db->where('cl',$cl);
            $this->db->where('bc',$bc);
            $this->db->update('t_advance_sum', array("is_cancel"=>1));

            //$sql="UPDATE t_credit_note cn,(
            //      SELECT cl,bc,nno,cn_no FROM t_advance_sum WHERE nno='$nno' AND cl='$cl' AND bc='$bc') ad
            //      SET cn.`is_cancel`='1' WHERE  cn.`cl`=ad.cl AND cn.bc=ad.bc AND cn.`nno`=ad.cn_no";
            
            $sql="Update t_credit_note cn
                  set cn.`is_cancel`='1'
                  where cn.cl ='".$cl."' and cn.bc='".$bc."'
                  and cn.nno = (select cn_no FROM t_advance_sum WHERE nno='".$nno."' AND cl='".$cl."' AND bc='".$bc."')";

            $this->db->query($sql); 

            $sql="SELECT cus_acc FROM t_receipt WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$nno."'";

            $res =$this->db->query($sql);
            if($res->num_rows>0){
                $cus_id=$this->db->query($sql)->first_row()->cus_acc;

                $this->utility->update_debit_note_balance($cus_id);
                $this->utility->update_credit_note_balance($cus_id);     
            }

            $this->db->query("INSERT INTO t_cheque_received_cancel SELECT * FROM t_cheque_received WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND trans_code='24' AND trans_no ='$nno'");
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("trans_code",24);
            $this->db->where("trans_no",$nno);
            $this->db->delete("t_cheque_received");  

            $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","24",$nno);   
            $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","24",$nno);               
     

            $this->utility->save_logger("CANCEL",24,$nno,$this->mod);
            echo $this->db->trans_commit();

        }else{
          
          echo $status;
          $this->db->trans_commit();
        }
      }else{
        echo "No permission to delete records";
        $this->db->trans_commit();
      }  
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
    }    
  }

  public function get_next_cnno(){
    $field="nno";
    $this->db->select_max($field);
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);    
    return $this->db->get('t_credit_note')->first_row()->$field+1;
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

    $this->db->where("code",$_POST['sales_type']);
    $query= $this->db->get('t_trans_code'); 
    if ($query->num_rows() > 0){
      foreach ($query->result() as $row){
        $r_detail['r_type']= $row->description;       
      }
    } 

    $ssql=" SELECT card_no, ddate, opt_credit_card_det.amount   
            FROM opt_credit_card_det
            JOIN t_advance_sum ON t_advance_sum.nno = opt_credit_card_det.`trans_no` 
            AND t_advance_sum.cl = opt_credit_card_det.cl
            AND t_advance_sum.bc = opt_credit_card_det.bc
            WHERE trans_code = '24' AND trans_no = '".$_POST['qno']."' AND t_advance_sum.cl='".$this->sd['cl']."' AND t_advance_sum.bc = '".$this->sd['branch']."'
            GROUP BY card_no,trans_no 
    ";

    $query_card = $this->db->query($ssql);
    $r_detail['credit_card'] = $this->db->query($ssql)->result();


    $sql_chq=" SELECT cheque_date,m_bank.description as bank,m_bank_branch.description as branch,account_no,cheque_no,amount   
            FROM opt_receive_cheque_det
            JOIN t_advance_sum ON t_advance_sum.nno = opt_receive_cheque_det.`trans_no` 
            JOIN m_bank ON m_bank.code = opt_receive_cheque_det.`bank` 
            JOIN m_bank_branch ON m_bank_branch.code = opt_receive_cheque_det.`branch` 
            AND t_advance_sum.cl = opt_receive_cheque_det.cl
            AND t_advance_sum.bc = opt_receive_cheque_det.bc
            WHERE trans_code = '24' AND trans_no = '".$_POST['qno']."' AND t_advance_sum.cl='".$this->sd['cl']."' AND t_advance_sum.bc = '".$this->sd['branch']."'
    ";
    $query = $this->db->query($sql_chq);
    $r_detail['cheque'] = $this->db->query($sql_chq)->result();

   
 	  $r_detail['type']=$_POST['type'];        
    $r_detail['dt']=$_POST['dt'];
    $r_detail['qno']=$_POST['qno'];
    $r_detail['num']=$_POST['reciviedAmount'];

    $r_detail['org_print']=$_POST['org_print'];

    $num =$_POST['reciviedAmount'];
    
     $this->utility->num_in_letter($num);

    $r_detail['rec']=convertNum($num);
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

    $this->db->select(array('t_advance_sum.description','t_advance_sum.card_amount','t_advance_sum.cash_amount','t_advance_sum.cheque_amount'));
    $this->db->from('t_advance_sum');
    $this->db->where('t_advance_sum.cl',$this->sd['cl'] );
    $this->db->where('t_advance_sum.bc',$this->sd['branch']);
    $this->db->where('t_advance_sum.nno',$_POST['qno']);
    $r_detail['items']=$this->db->get()->result_array();

    $r_detail['credit_tot']=$r_detail['items'][0]["card_amount"];
    $r_detail['cash_tot']=$r_detail['items'][0]["cash_amount"];
    $r_detail['cheque_tot']=$r_detail['items'][0]["cheque_amount"];

    $this->db->select(array('t_advance_sum.oc', 's_users.discription','action_date'));
    $this->db->from('t_advance_sum');
    $this->db->join('s_users', 't_advance_sum.oc=s_users.cCode');
    $this->db->where('t_advance_sum.cl', $this->sd['cl']);
    $this->db->where('t_advance_sum.bc', $this->sd['branch']);
    $this->db->where('t_advance_sum.nno', $_POST['qno']);
    $r_detail['user'] = $this->db->get()->result();

    $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  } 
}