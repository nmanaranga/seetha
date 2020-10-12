<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_internal_trans_voucher extends CI_Model {

  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';

  function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->mtb = $this->tables->tb['t_internal_trans_voucher_sum'];
  }

  public function base_details(){
    $a['max_no']=$this->utility->get_max_no("t_internal_trans_voucher_sum","nno");
    $a['type']='GTN_VOUCHER'; 
    return $a;
  }


  public function validation(){
    $status=1;

    $this->max_no=$this->utility->get_max_no("t_internal_trans_voucher_sum","nno");

    $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_internal_trans_voucher_sum');
    if($check_is_delete!=1){
      return "Voucher already deleted";
    }

    $multi_payment=$this->validation->check_multi_payment();
    if ($multi_payment != 1) {
      return $multi_payment;
    }

    $check_valid_dr_no=$this->validation->check_valid_trans_no2('acc_code','t_code_','no2_','cl_','bc_');
    if($check_valid_dr_no!=1){
      return $check_valid_dr_no;
    }
    $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle2('acc_code','t_code_','no2_','settle2_','cl_','bc_');
    if($check_valid_trans_settle_status!=1){
      return $check_valid_trans_settle_status; 
    }
    $check_valid_dr_no2=$this->validation->check_valid_trans_no2('acc_code','t_code3_','no3_','cl3_','bc3_');
    if($check_valid_dr_no2!=1){
      return $check_valid_dr_no2;
    }
    $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle2('acc_code','t_code3_','no3_','settle3_','cl3_','bc3_');
    if($check_valid_trans_settle_status2!=1){
      return $check_valid_trans_settle_status2; 
    }

    $payment_option_validation = $this->validation->payment_option_calculation();
    if ($payment_option_validation != 1) {
      return $payment_option_validation;
    }

    $this->load->model('t_payment_option');
    $is_issued      = $this->t_payment_option->is_chq_already_issued();
    if($is_issued!=1){
      return $is_issued;
    }

    return $status;
  }



  public function save(){

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errMsg."==".$errFile."==".$errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try {

      $validation_status=$this->validation();
      if($validation_status==1){

        $_POST['type']=131;

        $_POST['acc_codes']=$_POST['acc_code'];


        if(! isset($_POST['payment_option'])){ $_POST['payment_option'] = 0; }

        $t_internal_trans_voucher=array(
         "cl"=>$this->sd['cl'],
         "bc"=>$this->sd['branch'],
         "nno"=> $this->max_no,
         "ddate"=>$_POST['date'],
         "ref_no"=>$_POST['ref_no'],
         "acc_code"=>$_POST['acc_code'],
         "payment"=>$_POST['net'],
         "balance"=>$_POST['balance'],
         "settle_amount"=>$_POST['net_val'],
         "settle_balance"=>$_POST['balance2'],
         "is_multi_payment"=>$_POST['payment_option'],
         "memo"=>$_POST['memo'],
         "previlliage_card_no"=>$_POST['hid_pc_type'],
         "pay_cash"=>$_POST['hid_cash'],
         "pay_issue_chq"=>$_POST['hid_cheque_issue'],
         "pay_receive_chq"=>$_POST['hid_cheque_recieve'],
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
         "voucher_balance"=>$_POST['balance2']

       );

        for($x = 0; $x<100; $x++){
          if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
            if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){
             $t_internal_trans_voucher_temp[]=array(
               "cl"=>$this->sd['cl'],
               "bc"=>$this->sd['branch'],
               "nno"=> $this->max_no,
               "sub_cl"=>$_POST['cl0_'.$x],
               "sub_bc"=>$_POST['bc0_'.$x],
               "trans_code"=>$_POST['trans_code'.$x],
               "trans_no"=>$_POST['2_'.$x],
               "receive_no"=>$_POST['2r_'.$x],
               "mode_no"=>$_POST['2m_'.$x],
               "date"=>$_POST['3_'.$x],
               "amount"=>$_POST['4_'.$x],
               "description"=>$_POST['descrip_'.$x],
               "balance"=>$_POST['5_'.$x],
               "payment"=>$_POST['6_'.$x],
               "order_num"=>$x
             );
           }
         }
       }

       if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_internal_trans_voucher')){
          $account_update=$this->account_update(0);
          if($account_update==1){
            $this->account_update(1);
            $this->db->insert($this->mtb,  $t_internal_trans_voucher);
            if(count($t_internal_trans_voucher_temp)){$this->db->insert_batch("t_internal_trans_voucher_det",$t_internal_trans_voucher_temp);}

            $this->load->model('t_payment_option');
            $this->t_payment_option->save_payment_option($this->max_no,131);

            $this->load->model('trans_settlement');
            for($x = 0; $x<100; $x++){
              if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
                if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){
                  $this->trans_settlement->save_settlement_multi("t_gtn_settlement",$_POST['acc_code'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],131,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);  
                }
              }
            }
            /* supplier over payment save */
            $get_balance=(double)$_POST['balance2'];
            if($get_balance>0){
              $this->trans_settlement->save_settlement("t_gtn_settlement",$_POST['acc_code'],$_POST['date'],131,$this->max_no,131,$this->max_no,$get_balance,"0");  
            }

            $this->utility->update_purchase_balance($_POST['acc_code']);
            $this->utility->update_credit_note_balance($_POST['acc_code']);  
            $this->utility->update_debit_note_balance($_POST['acc_code']); 

            $this->utility->save_logger("SAVE",131,$this->max_no,$this->mod); 
            echo $this->db->trans_commit()."@".$this->max_no;
          }else{
            echo "invalid account entries";
            $this->db->trans_commit();
          }
        }else{
          echo "No permission to save records";
          $this->db->trans_commit();
        }

      }else{
        if($this->user_permissions->is_edit('t_internal_trans_voucher')){
          $status=$this->trans_cancellation->voucher_update_status($this->max_no);
          if($status=="OK"){
            $account_update=$this->account_update(0);
            if($account_update==1){
              $this->account_update(1);
              $this->load->model('t_payment_option');
              $this->t_payment_option->delete_all_payments_opt(131,$this->max_no);
              $this->t_payment_option->save_payment_option($this->max_no,131);

              $this->load->model('trans_settlement');
              $this->db->where('nno',$this->max_no);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update($this->mtb, $t_internal_trans_voucher);

              $this->set_delete();

              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->where('nno',$this->max_no);
              $query= $this->db->get('t_internal_trans_voucher_det');


              if(count($t_internal_trans_voucher_temp)){$this->db->insert_batch("t_internal_trans_voucher_det",$t_internal_trans_voucher_temp);}

              $this->trans_settlement->delete_settlement_sub("t_gtn_settlement",131,$this->max_no);

              for($x = 0; $x<100; $x++){
                if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
                  if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){
                    $this->trans_settlement->save_settlement_multi("t_gtn_settlement",$_POST['acc_code'],$_POST['date'],$_POST['trans_code'.$x],$_POST['2_'.$x],131,$this->max_no,"0",$_POST['6_'.$x],$_POST['cl0_'.$x],$_POST['bc0_'.$x]);        
                  }
                }
              }

              /* suppplier over payment save */
              $this->trans_settlement->delete_settlement("t_gtn_settlement",131,$this->max_no);
              $get_balance=(double)$_POST['balance2'];
              if($get_balance>0){
                $this->trans_settlement->save_settlement("t_gtn_settlement",$_POST['acc_code'],$_POST['date'],131,$this->max_no,131,$this->max_no,$get_balance,"0");  
              }
              $this->utility->save_logger("EDIT",131,$this->max_no,$this->mod); 
              $this->utility->update_purchase_balance($_POST['acc_code']);
              $this->utility->update_credit_note_balance($_POST['acc_code']); 
              $this->utility->update_debit_note_balance($_POST['acc_code']);       
              echo $this->db->trans_commit();
            }else{
              echo "invalid account entries";
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
  $this->db->where("trans_code", 131);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->delete("t_check_double_entry");


  if($_POST['hid']=="0"||$_POST['hid']==""){

  }else{
    if($condition=="1"){
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('trans_code',131);
      $this->db->where('trans_no',$this->max_no);
      $this->db->delete('t_account_trans');
    }
  }

  $sql="SELECT description as name FROM m_account
  WHERE code='".$_POST['acc_code']."'";

  $acc_name=$this->db->query($sql)->first_row()->name;

  $config = array(
    "ddate"=> $_POST['date'],
    "trans_code"=>131,
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

  $total_amount=(double)$_POST['net'];

  $this->account->set_value2($des, $total_amount, "dr", $_POST['acc_code'],$condition);

  if(isset($_POST['cash']) && !empty($_POST['cash']) && $_POST['cash']>0){
    $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
    $this->account->set_value2($acc_name, $_POST['cash'], "cr", $acc_code,$condition);    
  }

  if(isset($_POST['cheque_issue']) && !empty($_POST['cheque_issue']) && $_POST['cheque_issue']>0){
    $acc_code = $this->utility->get_default_acc('ISSUE_CHEQUES');
    $this->account->set_value2($acc_name, $_POST['cheque_issue'], "cr", $acc_code,$condition);    
  }

  if(isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card']>0){
    for($x = 0; $x<25; $x++){
      if(isset($_POST['type1_'.$x]) && isset($_POST['amount1_'.$x]) && isset($_POST['bank1_'.$x]) && isset($_POST['no1_'.$x])){
        if(!empty($_POST['type1_'.$x]) && !empty($_POST['amount1_'.$x]) && !empty($_POST['bank1_'.$x]) && !empty($_POST['no1_'.$x])){
          $acc_code = $_POST['acc1_'.$x];
          $this->account->set_value2($des, $_POST['amount1_'.$x], "cr", $acc_code,$condition);    
        }
      }
    }  
  }

  /*if(isset($_POST['debit_note']) && !empty($_POST['debit_note']) && $_POST['debit_note']>0){
    $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
    $this->account->set_value2($des, $_POST['debit_note'], "cr", $acc_code,$condition);    
  }*/



  if($condition==0){
    $query = $this->db->query("
     SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
     FROM `t_check_double_entry` t
     LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
     WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='131'  AND t.`trans_no` ='" . $this->max_no . "' AND 
     a.`is_control_acc`='0'");

    if($query->row()->ok=="0"){
     $this->db->where("trans_no",  $this->max_no);
     $this->db->where("trans_code", 131);
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
  $this->db->where('nno',$this->max_no);
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->delete("t_internal_trans_voucher_det");
}


public function load(){

  $this->db->select(array(
    't_internal_trans_voucher_sum.acc_code as acc_code' ,
    'm_account.description as acc_name',
    't_internal_trans_voucher_sum.memo',
    't_internal_trans_voucher_sum.payment',
    't_internal_trans_voucher_sum.ref_no',
    't_internal_trans_voucher_sum.ddate',
    't_internal_trans_voucher_sum.payment',
    't_internal_trans_voucher_sum.balance',
    't_internal_trans_voucher_sum.settle_balance',
    't_internal_trans_voucher_sum.settle_amount',
    't_internal_trans_voucher_sum.is_multi_payment',
    't_internal_trans_voucher_sum.is_multi_branch',
    't_internal_trans_voucher_sum.is_cancel',
    't_internal_trans_voucher_sum.pay_cash' ,
    't_internal_trans_voucher_sum.pay_issue_chq',
    't_internal_trans_voucher_sum.pay_receive_chq',
    't_internal_trans_voucher_sum.pay_ccard' ,
    't_internal_trans_voucher_sum.pay_cnote' ,
    't_internal_trans_voucher_sum.pay_dnote' ,
    't_internal_trans_voucher_sum.pay_bank_debit' ,
    't_internal_trans_voucher_sum.pay_advance' ,
    't_internal_trans_voucher_sum.pay_discount' ,
    't_internal_trans_voucher_sum.pay_gift_voucher' ,
    't_internal_trans_voucher_sum.pay_credit' ,
    't_internal_trans_voucher_sum.pay_privi_card' 
  ));


  $this->db->from('t_internal_trans_voucher_sum');
  $this->db->join('m_account','m_account.code=t_internal_trans_voucher_sum.acc_code');
  $this->db->where('t_internal_trans_voucher_sum.cl',$this->sd['cl'] );
  $this->db->where('t_internal_trans_voucher_sum.bc',$this->sd['branch'] );
  $this->db->where('t_internal_trans_voucher_sum.nno',$_POST['id']);
  $query=$this->db->get();


  if($query->num_rows()>0){
    $a['sum']=$query->result();
  }else{
    $a=2;
  }

  $this->db->select(array(
    't_internal_trans_voucher_det.sub_cl' ,
    't_internal_trans_voucher_det.sub_bc',
    't_internal_trans_voucher_det.trans_code',
    't_internal_trans_voucher_det.trans_no',
    't_internal_trans_voucher_det.receive_no',
    't_internal_trans_voucher_det.mode_no',
    't_internal_trans_voucher_det.date',
    't_internal_trans_voucher_det.amount',
    't_internal_trans_voucher_det.balance',
    't_internal_trans_voucher_det.payment',
    't_internal_trans_voucher_det.description as des1',
    't_trans_code.description'

  ));


  $this->db->from('t_internal_trans_voucher_det');
  $this->db->join('t_trans_code','t_trans_code.code=t_internal_trans_voucher_det.trans_code');
  $this->db->where('t_internal_trans_voucher_det.cl',$this->sd['cl'] );
  $this->db->where('t_internal_trans_voucher_det.bc',$this->sd['branch'] );
  $this->db->where('t_internal_trans_voucher_det.nno',$_POST['id']);
  $this->db->order_by("t_internal_trans_voucher_det.order_num", "asc"); 
  $query=$this->db->get();       


  if($query->num_rows()>0){
    $a['det']=$query->result();
  }else{
    $a=2;
  }
  echo json_encode($a);
  
}

public function delete(){

  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errMsg."==".$errFile."==".$errLine); 
  }
  set_error_handler('exceptionThrower'); 
  try { 
    if($this->user_permissions->is_delete('t_internal_trans_voucher')){
      $status=$this->trans_cancellation->voucher_update_status();
      if($status=="OK"){

        $sql="SELECT * 
        FROM t_account_trans t 
        JOIN m_account a ON a.code=t.acc_code
        WHERE cl='".$this->sd['cl']."' 
        AND bc='".$this->sd['branch']."' 
        AND trans_no='".$_POST['trans_no']."' 
        AND trans_code='131'
        AND a.is_control_acc='0'
        ";
        $query=$this->db->query($sql);
        $this->load->model('account');
        $config = array(
          "ddate"=> $_POST['date'],
          "trans_code"=>131,
          "trans_no"=>$_POST['trans_no'],
          "op_acc"=>0,
          "reconcile"=>0,
          "cheque_no"=>0,
          "narration"=>"",
          "ref_no" => ""
        );
        $this->account->set_data($config);
        $dr=$cr=0;

        foreach($query->result() as $row){
          if($row->dr_amount>0){
            $dr+=$row->dr_amount;
          }else if($row->cr_amount>0){
            $cr+=$row->cr_amount;
          }
        }

        if($dr==$cr){
          $st=1;
        }else{
          $st=2;
        }

        if($st==1){


          foreach($query->result() as $row){
            if($row->dr_amount>0){
              $this->account->set_value2("VOUCHER NO - ".$_POST['trans_no']." CENCELATION", $row->dr_amount, "cr", $row->acc_code,1);  
            }else if($row->cr_amount>0){
              if($row->acc_code=='103000005005'){
                $sql="SELECT * FROM `t_cheque_issued` 
                WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND trans_code='131' AND trans_no='".$_POST['trans_no']."'";
                $query=$this->db->query($sql);
                $bank=$query->row()->bank;
                $dep=$query->row()->status;
                if($dep=='D'){
                  $row->acc_code=$bank;
                }
                
              }
              $this->account->set_value2("VOUCHER NO - ".$_POST['trans_no']." CENCELATION", $row->cr_amount, "dr", $row->acc_code,1);
            }
          }

          /*$this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('trans_no',$_POST['trans_no']);
          $this->db->where('trans_code',131);
          $this->db->delete('t_account_trans');*/

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('sub_trans_no',$_POST['trans_no']);
          $this->db->where('sub_trans_code',131);
          $this->db->delete('t_gtn_settlement');

          $this->db->query("INSERT INTO t_cheque_issued_cancel SELECT * FROM t_cheque_issued WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND trans_code='131' AND trans_no ='".$_POST['trans_no']."'");
          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("trans_code",131);
          $this->db->where("trans_no",$_POST['trans_no']);
          $this->db->delete("t_cheque_issued"); 


          $data=array('is_cancel'=>'1');
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('nno',$_POST['trans_no']);
          $this->db->update('t_internal_trans_voucher_sum',$data);

          $sql="SELECT acc_code FROM t_internal_trans_voucher_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
          $acc_id=$this->db->query($sql)->first_row()->acc_code;

          $this->utility->update_purchase_balance($acc_id);
          $this->utility->update_credit_note_balance($acc_id);    
          $this->utility->update_debit_note_balance($acc_id);    

          $this->utility->save_logger("CANCEL",131,$_POST['trans_no'],$this->mod); 
          echo  $this->db->trans_commit();
        }else{
          echo "Invalid account entries";
          $this->db->trans_commit();
        }
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


public function load_supplier_details(){
  $acc_code=$_POST['acc_code'];
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];



     /* $sql="SELECT tc.`description`, d.* FROM( 
              SELECT s.cl, s.bc, s.type, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
              FROM t_grn_sum s  JOIN (
                      SELECT t.sub_cl AS cl,t.sub_bc AS bc,
                        t.trans_code,
                        t.trans_no,
                        t.acc_code
                        
                      FROM t_sup_settlement t
                      WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
                      GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
                      
                    )t 
              ON t.trans_no=s.nno AND  t.acc_code=s.supp_id WHERE s.supp_id='$acc_code' AND t.trans_code='3' 
              AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' HAVING balance > 0
              UNION ALL 
              SELECT s.cl, s.bc, s.type,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
              FROM t_credit_note s INNER JOIN (
                      SELECT t.sub_cl AS cl,t.sub_bc AS bc,
                        t.trans_code,
                        t.trans_no,
                        t.acc_code
                        
                      FROM t_credit_note_trans t
                      WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
                      GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
                       
                    ) t 
              ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
              AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' 
              HAVING balance > 0 
            ) d 
            INNER JOIN t_trans_code tc ON tc.`code` = d.type";*/



            $sql="SELECT tc.`description`, d.* FROM( 
            SELECT s.cl, s.bc, t.trans_code as type, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
            FROM t_grn_sum s  JOIN (
            SELECT t.sub_cl AS cl,t.sub_bc AS bc,
            t.trans_code,
            t.trans_no,
            t.acc_code

            FROM t_sup_settlement t
            WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
            GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code

            )t 
            ON t.trans_no=s.nno AND  t.acc_code=s.supp_id WHERE s.supp_id='$acc_code' AND t.trans_code='3' 
            AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' HAVING balance > 0

            UNION ALL 

            SELECT tr.cl, 
            tr.bc, 
            tr.trans_code AS TYPE,
            tr.trans_no AS trans_no, 
            'Records From Opening Balance' AS memo, 
            s.date AS ddate, 
            SUM(tr.dr) AS amount,
            SUM(tr.cr)-SUM(tr.dr) AS balance  
            FROM t_opening_bal_trans tr 
            JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc
            WHERE tr.acc_code='$acc_code' AND tr.sub_cl='$cl' AND tr.sub_bc='$bc'
            GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code,s.no
            HAVING balance >0  

            UNION ALL
            SELECT s.cl, s.bc,  t.trans_code as type ,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
            FROM t_credit_note s INNER JOIN (
            SELECT t.sub_cl AS cl,t.sub_bc AS bc,
            t.trans_code,
            t.trans_no,
            t.acc_code

            FROM t_credit_note_trans t
            WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
            GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code

            ) t 
            ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
            AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' 
            HAVING balance > 0 
            ) d 
            INNER JOIN t_trans_code tc ON tc.`code` = d.type ";  


            $query=$this->db->query($sql);

            if($query->num_rows()>0){
              $a['det']=$query->result();
              echo json_encode($a);
            }else{
              echo json_encode($a['det']=2);
            }
          }


          public function load_supplier_details_all(){
            $acc_code=$_POST['acc_code']; 


     /* $sql="SELECT tc.`description`, d.* FROM( 
              SELECT s.cl, s.bc, s.type, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
              FROM t_grn_sum s  JOIN (
                      SELECT t.sub_cl AS cl,t.sub_bc AS bc,
                        t.trans_code,
                        t.trans_no,
                        t.acc_code
                      FROM t_sup_settlement t
                      WHERE t.acc_code='$acc_code'
                      GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
                      
                    )t 
              ON t.trans_no=s.nno AND  t.acc_code=s.supp_id WHERE s.supp_id='$acc_code' AND t.trans_code='3' 
              AND s.bc =t.bc AND s.cl = t.cl HAVING balance > 0
              UNION ALL 
              SELECT s.cl, s.bc, s.type,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
              FROM t_credit_note s INNER JOIN (
                      SELECT t.sub_cl AS cl,t.sub_bc AS bc,
                        t.trans_code,
                        t.trans_no,
                        t.acc_code,
                        SUM(dr)-SUM(cr) balance
                      FROM t_credit_note_trans t
                      WHERE t.acc_code='$acc_code'
                      GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
                      
                    ) t 
              ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
              AND s.bc =t.bc AND s.cl = t.cl HAVING balance > 0
            ) d 
            INNER JOIN t_trans_code tc ON tc.`code` = d.type";*/


            $sql="SELECT tc.`description`, d.* FROM( 
            SELECT s.cl, s.bc, t.trans_code as type, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
            FROM t_grn_sum s  JOIN (
            SELECT t.sub_cl AS cl,t.sub_bc AS bc,
            t.trans_code,
            t.trans_no,
            t.acc_code
            FROM t_sup_settlement t
            WHERE t.acc_code='$acc_code'
            GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code

            )t 
            ON t.trans_no=s.nno AND  t.acc_code=s.supp_id WHERE s.supp_id='$acc_code' AND t.trans_code='3' 
            AND s.bc =t.bc AND s.cl = t.cl HAVING balance > 0
            UNION ALL 

            SELECT tr.cl, 
            tr.bc, 
            tr.trans_code AS TYPE,
            tr.trans_no AS trans_no, 
            'Records From Opening Balance' AS memo, 
            s.date AS ddate, 
            SUM(tr.dr) AS amount,
            SUM(tr.cr)-SUM(tr.dr) AS balance  
            FROM t_opening_bal_trans tr 
            JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc
            WHERE tr.acc_code='$acc_code' 
            GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code, s.no
            HAVING balance >0  

            UNION ALL
            SELECT s.cl, s.bc,  t.trans_code as type ,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
            FROM t_credit_note s INNER JOIN (
            SELECT t.sub_cl AS cl,t.sub_bc AS bc,
            t.trans_code,
            t.trans_no,
            t.acc_code,
            SUM(dr)-SUM(cr) balance
            FROM t_credit_note_trans t
            WHERE t.acc_code='$acc_code'
            GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code

            ) t 
            ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
            AND s.bc =t.bc AND s.cl = t.cl HAVING balance > 0
            ) d 
            INNER JOIN t_trans_code tc ON tc.`code` = d.type";

            $query=$this->db->query($sql);

            if($query->num_rows()>0){
             $a['det']=$query->result();
             echo json_encode($a);
           }else{
            echo json_encode($a['det']=2);
          }
        }


        function load_supplier_balance(){
          $acc_code=$_POST['acc_code']; 
          $cl=$this->sd['cl'];
          $bc=$this->sd['branch'];
          $sql="SELECT SUM(balance) AS balance FROM(
          SELECT SUM(balance) AS balance FROM t_grn_sum WHERE t_grn_sum.supp_id='$acc_code' AND cl='$cl' AND bc='$bc' 
          UNION ALL 
          SELECT SUM(cr)-SUM(dr) AS balance FROM t_opening_bal_trans WHERE acc_code='$acc_code' AND cl='$cl' AND bc='$bc' 
          UNION ALL 
          SELECT SUM(balance) AS balance FROM t_credit_note WHERE t_credit_note.code='$acc_code' AND cl='$cl' AND bc='$bc'
        ) AS a";

        echo $this->db->query($sql)->row()->balance;
      } 


      function load_supplier_balance_all(){
        $acc_code=$_POST['acc_code']; 
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $sql="SELECT SUM(balance) AS balance FROM(
        SELECT SUM(balance) AS balance FROM t_grn_sum WHERE t_grn_sum.supp_id='$acc_code' 
        UNION ALL 
        SELECT SUM(cr)-SUM(dr) AS balance FROM t_opening_bal_trans WHERE acc_code='$acc_code' 
        UNION ALL 
        SELECT SUM(balance) AS balance FROM t_credit_note WHERE t_credit_note.acc_code='$acc_code'
      ) AS a";

      echo $this->db->query($sql)->row()->balance;
    } 

    public function get_payment_option(){
     $this->db->where("code",$_POST['code']);
     $data['result']=$this->db->get("r_payment_option")->result();
     echo json_encode($data);
   }  

   public function get_department_pv_rate(){
    $this->db->select(array("pv_card_rate"));
    $this->db->from("r_department");
    $this->db->join("m_item","r_department.code=m_item.department");
    $this->db->where("m_item.code",$this->input->post('code'));
    echo $this->db->get()->first_row()->pv_card_rate;
  }



  public function get_max_no(){
    $field="nno";
    $this->db->select_max($field);
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);    
    echo $this->db->get($this->mtb)->first_row()->$field+1;

  }


  public function PDF_report(){
    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();
    $r_detail['duplicate'] = $_POST['is_duplicate'];

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

    $r_detail['type']=$_POST['type'];        
    $r_detail['dt']=$_POST['dt'];
    $r_detail['qno']=$_POST['qno'];
    $r_detail['num']=$_POST['recivied'];

    $num =$_POST['recivied'];

    $sql_credit="SELECT * FROM `opt_credit_card_det` 
    WHERE cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."' 
    AND trans_code='131'
    AND trans_no='".$_POST['qno']."'";

    $query=$this->db->query($sql_credit);

    if($query->num_rows()>0){
      $r_detail['credit_card']=$query->result();
    }else{
      $r_detail['credit_card']=2;
    }


    $sql_cheque="SELECT d.*, a.description as b_des
    FROM `opt_issue_cheque_det` d 
    JOIN m_account a ON a.code=d.`bank` 
    WHERE cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."' 
    AND trans_code='131'
    AND trans_no='".$_POST['qno']."'";

    $query_chq=$this->db->query($sql_cheque);

    if($query_chq->num_rows()>0){
      $r_detail['issue_chq']=$query_chq->result();
    }else{
      $r_detail['issue_chq']=2;
    }

    $sql="SELECT d.sub_cl, 
    d.sub_bc, 
    mb.name as mb_name, 
    ml.description as ml_name,
    t.description AS t_name, 
    d.trans_no, 
    d.date, 
    d.`amount`, 
    d.`balance`,
    d.`payment`,
    d.receive_no,
    d.mode_no
    FROM t_internal_trans_voucher_det d
    JOIN m_branch mb ON mb.bc = d.sub_bc
    JOIN m_cluster ml ON ml.code = d.sub_cl
    JOIN t_trans_code t ON t.code=d.`trans_code`
    WHERE nno='".$_POST['qno']."'
    AND d.cl='".$this->sd['cl']."'
    AND d.bc='".$this->sd['branch']."'";

    $query=$this->db->query($sql);
    $r_detail['grid'] = $query->result();

    $this->utility->num_in_letter($num);
    $r_detail['rec']=convertNum($num);;


    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];

    $this->db->select(array('code','description as name'));
    $this->db->where("code",$_POST['acc_codes']);
    $r_detail['supplier']=$this->db->get('m_account')->result();

    $this->db->select(array('name'));
    $this->db->where("code",$_POST['salesp_id']);
    $query=$this->db->get('m_employee');

    foreach ($query->result() as $row){
      $r_detail['employee']= $row->name;
    }

    $sql_acc="SELECT SUM(dr_amount)-SUM(cr_amount) AS clb FROM t_account_trans 
    WHERE cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."' 
    AND acc_code='".$_POST['supp_id']."'";

    $query_acc=$this->db->query($sql_acc);

    if($query_acc->num_rows()>0){
      $r_detail['closin_legd']=$query_acc->result();
    }else{
      $r_detail['closin_legd']=2;
    }

    $this->db->select(array('`t_internal_trans_voucher_sum.balance`-`t_internal_trans_voucher_sum.payment` as bal' , 'balance','memo'));
    $this->db->from('t_internal_trans_voucher_sum');
      //$this->db->join('m_item','m_item.code=t_credit_sales_det.code');
    $this->db->where('t_internal_trans_voucher_sum.cl',$this->sd['cl'] );
    $this->db->where('t_internal_trans_voucher_sum.bc',$this->sd['branch']);
    $this->db->where('t_internal_trans_voucher_sum.nno',$_POST['qno']);
    $r_detail['items']=$this->db->get()->result();


    $this->db->select(array('t_internal_trans_voucher_sum.oc', 's_users.discription','action_date'));
    $this->db->from('t_internal_trans_voucher_sum');
    $this->db->join('s_users', 't_internal_trans_voucher_sum.oc=s_users.cCode');
    $this->db->where('t_internal_trans_voucher_sum.cl', $this->sd['cl']);
    $this->db->where('t_internal_trans_voucher_sum.bc', $this->sd['branch']);
    $this->db->where('t_internal_trans_voucher_sum.nno', $_POST['qno']);
    $r_detail['user'] = $this->db->get()->result();



    $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  }



  public function load_branch_details(){

    $acc_code=$_POST['acc_id'];
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];

    $sql="SELECT sub_cl,sub_bc,cl,bc,trans_no,receive_no,mode_no,trans_code as type,t_trans_code.description,ddate,'Internal Transfer Payments' AS des, SUM(dr) AS amount , SUM(dr)-SUM(cr) AS  balance,trans_mode
    FROM `t_gtn_settlement` 
    JOIN t_trans_code ON t_trans_code.code=t_gtn_settlement.trans_code
    WHERE acc_code='$acc_code' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
    GROUP BY cl,bc,acc_code,trans_code,trans_no
    HAVING balance > 0
    UNION ALL 
    SELECT t.sub_cl,t.sub_bc,t.cl,t.bc,trans_no,t.`trans_no` AS receive_no,0 AS mode_no,trans_code AS TYPE,t_trans_code.description,t.ddate,'Credit Note' AS des, SUM(dr) AS amount , SUM(dr)-SUM(cr) AS balance,0 AS trans_mode 
    FROM `t_credit_note_trans` t 
    INNER JOIN `t_credit_note` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc 
    JOIN t_trans_code ON t_trans_code.code=t.`sub_trans_code` 
    WHERE (t.acc_code='103000004028' AND t.`trans_code`=17)AND t.cl='".$this->sd['cl']."' AND t.bc='".$this->sd['branch']."' 
    GROUP BY t.sub_cl,t.sub_bc,trans_no, t.trans_code 
    HAVING balance > 0"; 
    $query=$this->db->query($sql);   

    if($query->num_rows()>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      echo json_encode($a['det']=2);
    }
  }



  function load_branch_balance(){
    $acc_code=$_POST['acc_id']; 
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $sql="SELECT SUM(dr)-SUM(cr) AS balance 
    FROM `t_gtn_settlement` 
    WHERE acc_code='$acc_code' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";       
    $query=$this->db->query($sql);
    echo $this->db->query($sql)->row()->balance;
  } 


}