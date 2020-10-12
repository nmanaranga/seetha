<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_job_issue extends CI_Model {

  private $sd;
  private $mod = '003';

  function __construct(){
   parent::__construct();

   $this->sd = $this->session->all_userdata();
   $this->load->database($this->sd['db'], true);
   $this->load->model('user_permissions');
 }

 public function base_details(){
  $this->load->model("utility");
  $a['max_no'] = $this->utility->get_max_no("t_job_issue", "nno");
  $a['type'] = 'JOB_ISSUE';
  $a['drn_no'] = $this->get_debit_max_no("t_debit_note", "nno");
  return $a;

}

public function validation(){

  $status =1;
  $this->max_no = $this->utility->get_max_no("t_job_issue","nno");
  $this->debit_max_no = $this->get_debit_max_no("t_debit_note", "nno");

  $account_update=$this->account_update(0);
  if($account_update!=1){
    return "Invalid account entries";
  }

  return $status;
}

public function save(){

  $this->db->trans_begin();
  error_reporting(E_ALL);
  function exceptionThrower($type,$errMsg,$errFile, $errLine){
    throw new Exception($errLine);     
  }
  set_error_handler('exceptionThrower');
  try{

    $validate = $this->validation();
    if($validate ==1){
      $this->load->model('trans_settlement');
      $_POST['acc_codes']=$_POST['customer'];
      if(isset($_POST['cash'])){
        $_POST['cash'] = $_POST['cash'];
      }else{
        $_POST['cash'] =0;
      }

      if(isset($_POST['cheque_recieve'])){
        $_POST['cheque_recieve'] = $_POST['cheque_recieve'];
      }else{
        $_POST['cheque_recieve'] =0;
      }

      if(isset($_POST['credit_card'])){
        $_POST['credit_card'] = $_POST['credit_card'];
      }else{
        $_POST['credit_card'] =0;
      }

      if(isset($_POST['credit_note'])){
        $_POST['credit_note'] = $_POST['credit_note'];
      }else{
        $_POST['credit_note'] =0;
      }


      $sum = array(
       "cl"=>$this->sd['cl'],
       "bc"=>$this->sd['branch'],
       "nno"=>$this->max_no,
       "ref_no"=>$_POST['ref_no'],
       "ddate"=>$_POST['date'],
       "customer"=>$_POST['customer'],
       "memo"=>$_POST['comment'],
       "drn_no"=>$_POST['drn'],
       "amount"=>$_POST['amount'],
       //"advance"=>$_POST['adv_amount'],
       "balance"=>$_POST['net'],
       "pay_cash"=>$_POST['cash'],
       "pay_receive_chq"=>$_POST['cheque_recieve'],
       "pay_ccard"=> $_POST['credit_card'],
       "pay_cnote"=> $_POST['credit_note'],
       "oc"=>$this->sd['oc'],
       "is_debit_note"=>$_POST['is_check'],
       "discount"=>$_POST['dis_amount']
     );

      for($x =0;$x<25;$x++){

        if(isset($_POST['0_'.$x],$_POST['n_'.$x],$_POST['sel_'.$x])){
          if($_POST['0_'.$x]!="" && $_POST['n_'.$x]!=""){
            $det[] = array(
             "cl"=>$this->sd['cl'],
             "bc"=>$this->sd['branch'],
             "nno"=>$this->max_no,
             "job_no"=>$_POST['0_'.$x],
             "price"=>$_POST['6_'.$x],
             "discount"=>$_POST['di_'.$x],
             "amount"=>$_POST['7_'.$x],
           );
          }
        }
      }

      if($_POST['is_check'] == "1"){
        $t_debit_note = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->debit_max_no,
          "ddate" => $_POST['date'],
          "ref_no" => $_POST['ref_no'],
          "memo" => "JOB ISSUE TO CUSTOMER [" . $this->max_no . "]",
          "is_customer" => 1,
          "code" => $_POST['customer'],
          "acc_code" => $this->utility->get_default_acc('SERVICE_INCOME'),
          "amount" => $_POST['net'],
          "oc" => $this->sd['oc'],
          "ref_trans_code"=>102,
          "ref_trans_no"=>$this->max_no,
          "balance"=> $_POST['net']
        );
      }

      if($_POST['hid']== "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_job_issue')){
          $this->db->insert("t_job_issue",$sum);
          if(count($det)){
            $this->db->insert_batch("t_job_issue_det",$det);  
          }

          for($x=0;$x<25;$x++){
            if(isset($_POST['sel_'.$x])){

              $this->db->where("nno",$_POST['0_'.$x]);
              $this->db->update("t_job", array("status" =>"4"));
            }
          }
          if($_POST['is_check'] == "1"){
            $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['customer'], $_POST['date'], 18, $this->debit_max_no, 102, $this->max_no, $_POST['net'], "0");
            $this->db->insert("t_debit_note",$t_debit_note );
          }

          $this->load->model('t_payment_option');
          $this->t_payment_option->save_payment_option($this->max_no, 102);

          $this->account_update(1);
          $this->utility->save_logger("SAVE",102,$this->max_no,$this->mod);
          echo $this->db->trans_commit();
        }else{
          $this->db->trans_commit();
          echo "No permission to save records";
        }   
      }else{

        if($this->user_permissions->is_edit('t_job_issue')){
          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("nno",$_POST['id']);
          $this->db->update("t_job_issue",$sum);

          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("nno",$_POST['id']);
          $this->db->delete("t_job_issue_det");

          if(count($det)){
            $this->db->insert_batch("t_job_issue_det",$det);
          }
          for($x=0;$x<25;$x++){

            $this->db->where("nno",$_POST['0_'.$x]);
            $this->db->update("t_job",array("status"=>"3"));

            if(isset($_POST['sel_'.$x])){

              $this->db->where("nno",$_POST['0_'.$x]);
              $this->db->update("t_job",array("status"=>"4"));
            }
          }

          $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","102",$this->max_no);
          if($_POST['is_check'] == "1"){
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("nno",$this->debit_max_no);
            $this->db->update("t_debit_note",$t_debit_note); 
            $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['customer'], $_POST['date'], 18, $this->debit_max_no, 102, $this->max_no, $_POST['net'], "0");
          }


          $this->load->model('t_payment_option');
          $this->t_payment_option->delete_all_payments_opt(102, $this->max_no);
          $this->t_payment_option->save_payment_option($this->max_no, 102);
          $this->account_update(1);
          $this->utility->save_logger("EDIT",102,$this->max_no,$this->mod);
          echo $this->db->trans_commit();  

        }else{
          $this->db->trans_commit();
          echo "No permission to edit record";
        }

      }   

    }else{
     $this->db->trans_commit();
     echo $validate;
   }

 }catch(Exception $e){

   $this->db->trans_rollback();
   echo $e->getMessage()."Operation fail please contatct admin";
 }        

}

public function check_code(){
 $this->db->where('code', $_POST['code']);
 $this->db->limit(1);

 echo $this->db->get($this->mtb)->num_rows;
}

public function select_customer(){
  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
    $sql = " SELECT 
              tj.`cus_id`,
              mc.`name`  
              FROM t_job tj
              JOIN `m_customer` mc ON mc.`code`= tj.`cus_id`
              WHERE tj.`status` = '3' AND  tj.bc='".$this->sd['branch']."'
              AND(tj.nno LIKE '%$_POST[search]%' OR 
                  tj.item_code LIKE '%$_POST[search]%' OR 
                  tj.Item_name LIKE '%$_POST[search]%' OR
                  tj.`cus_id`  LIKE '%$_POST[search]%' OR 
                  mc.`name`    LIKE '%$_POST[search]%'   )
              GROUP BY tj.`cus_id` LIMIT 25";

    $query = $this->db->query($sql);
    $a ="<table id='item_list' style='width:100%'>";
    $a.="<thead><tr>";
    $a.="<th class ='tb_head_th'>Code</th>";
    $a.="<th class='tb_head_th' colspan='2'>Description</th>";
    $a.="</thead></tr><tr class='cl' style='visibility:hidden;'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a.="<tr class='cl'>";
      $a.="<td>".$r->cus_id."</td>";
      $a.="<td colspan='2'>".$r->name."</td>";
      $a.="</tr>";
    }
    $a."</table>";
    echo $a;
  }

  public function load_services(){

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
      $sql="SELECT
      td.`job_no`,
      tj.`ddate` AS receive_dt,
      tj.`item_code`,
      tj.`Item_name`,
      tj.`fault`,
      tj.`advance_amount`,
      tj.`w_start_date`,
      tj.`w_end_date`,
      td.`amount` AS sup_amt
      FROM t_job tj
      JOIN `t_job_receive_det` td ON  td.`job_no`=tj.`nno` 
      JOIN `t_job_receive` ts ON ts.`cl`= td.`cl` AND ts.`bc` = td.`bc` AND ts.`nno` =td.`nno`
      WHERE tj.`cus_id`= '".$_POST['customer']."'
      AND tj.`status` = '3'
      AND ts.`is_cancel` != '1' AND tj.bc='".$this->sd['branch']."'
      AND (td.job_no LIKE '%$_POST[search]%' OR tj.item_code LIKE '%$_POST[search]%' OR tj.Item_name LIKE '%$_POST[search]%')
      LIMIT 25";

      $query = $this->db->query($sql);
      if($query->num_rows > 0){
        $a['a']=$this->db->query($sql)->result();
      }else{
        $a['a'] = 2;
      }
      echo json_encode($a);
    }

    public function load(){

      $sql_sum = "SELECT 
      ti.`customer`,
      mc.`name`,
      ti.`memo`,
      ti.`ddate`,
      ti.`ref_no`,
      ti.`drn_no`,
      ti.`is_cancel`,
      ti.`amount`,
      ti.`advance`,
      ti.`balance`,
      ti.`pay_cash`,
      ti.`pay_receive_chq`,
      ti.`pay_ccard`,
      ti.`pay_cnote`,
      ti.`pay_dnote`,
      ti.`is_debit_note`,
      ti.`discount`
      FROM 
      t_job_issue ti
      JOIN m_customer mc ON mc.`code`=ti.`customer`
      WHERE ti.cl='".$this->sd['cl']."'
      AND ti.bc='".$this->sd['branch']."'
      AND ti.nno='".$_POST['id']."' ";

      $query_sum =$this->db->query($sql_sum);

      $sql_det="SELECT
      dt.`job_no`, 
      tj.`item_code`,
      tj.`Item_name`,
      tj.`ddate` AS r_date,
      tj.`fault`,
      tj.`w_end_date`,
      tj.`w_start_date`,
      dt.`amount`,
      trd.`amount` AS sup_amt,
      dt.`price`,
      dt.`discount`
      FROM t_job tj
      JOIN t_job_issue_det dt ON dt.`job_no`= tj.`nno`
      JOIN t_job_issue ti ON ti.`cl`=dt.`cl` AND ti.`bc`=dt.`bc` AND ti.`nno`= dt.`nno`
      JOIN t_job_receive_det trd ON trd.`job_no`= tj.`nno`
      WHERE dt.cl='".$this->sd['cl']."'
      AND dt.bc='".$this->sd['branch']."' 
      AND dt.nno = '".$_POST['id']."'
      GROUP BY dt.cl,dt.`bc`,dt.`job_no` ";

      $query_det=$this->db->query($sql_det);

      if($query_sum->num_rows()>0){
        $a['a']=$this->db->query($sql_sum)->result();
      }else{
        $a['a']=2;
      }

      if($query_det-> num_rows()>0){
        $a['b']=$this->db->query($sql_det)->result();
      }else{
        $a['b']=2;
      }
      echo json_encode($a);
    }

    public function delete(){

      $this->db->trans_begin();
      error_reporting(E_ALL);

      function exceptionThrower($type,$errMsg,$errFile,$errLine){
        throw new Exception($errLine); 
      }

      set_error_handler('exceptionThrower');
      try{
        if($this->user_permissions->is_delete('t_job_issue')){

          $nno=$_POST['id'];
          $status=$this->trans_cancellation->credit_note_status($nno);

          if($status=="ok"){
            $trans_code=17;
            $table_trans='t_credit_note_trans';
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("trans_code",$trans_code);
            $this->db->where("trans_no",$nno);
            $this->db->delete('t_credit_note_trans');

            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("trans_code",$trans_code);
            $this->db->where("trans_no",$nno);
            $this->db->delete("t_account_trans");

            $this->db->where('nno',$nno);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->update('t_credit_note', array("is_cancel"=>1));
          }

          $this->load->model('trans_settlement');
          $this->db->where("nno",$_POST['id']);
          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("cl",$this->sd['cl']);
          $this->db->update("t_job_issue", array("is_cancel"=>"1"));

          $sql = "SELECT nno,job_no FROM `t_job_issue_det` where nno= '".$_POST['id']."'";
          $query = $this->db->query($sql);
          foreach ($query->result() as $row) {
            $this->db->where("nno",$row->job_no);
            $this->db->update("t_job",array("status"=>"3"));
          }


          $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","102",$_POST['id']);
          $sql_drn = "SELECT drn_no FROM t_job_issue WHERE nno='".$_POST['id']."'";
          $query_drn = $this->db->query($sql_drn);
          foreach($query_drn->result() as $rw){
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("nno",$rw->drn_no);
            $this->db->update("t_debit_note",array("is_cancel"=>"1"));
          }

          $this->db->query("INSERT INTO t_cheque_received_cancel SELECT * FROM t_cheque_received WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND trans_code='102' AND trans_no ='".$_POST['id']."'");
          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("trans_code",102);
          $this->db->where("trans_no",$_POST['id']);
          $this->db->delete("t_cheque_received"); 

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('trans_code',102);
          $this->db->where('trans_no',$_POST['id']);
          $this->db->delete('t_account_trans');

          $this->utility->save_logger("CANCEL",102,$_POST['id'],$this->mod);
          echo $this->db->trans_commit();
        }else{
          echo "No permission to delete records";
          $this->db->trans_commit();
        }
      }catch(Exception $e){
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin";
      }
    }

    public function PDF_report(){
      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $r_detail['branch'] = $this->db->get('m_branch')->result();

      $this->db->select(array('loginName'));
      $this->db->where('cCode',$this->sd['oc']);
      $r_detail['user']=$this->db->get('users')->result();

      $r_detail['session'] = $session_array;

      $r_detail['orientation'] = "L";
      $r_detail['page']="A5";

      $sql = "SELECT 
      ti.`customer` AS cus_id,
      mc.`name`,
      ti.`memo`,
      ti.`ddate`,
      ti.`ref_no`,
      ti.`drn_no`,
      ti.`amount`,
      ti.`advance`,
      ti.`balance`,
      ti.discount,
      GROUP_CONCAT(cc.tp) AS tp
      FROM 
      t_job_issue ti
      JOIN m_customer mc ON mc.`code`=ti.`customer`
      LEFT JOIN  m_customer_contact cc ON cc.code=mc.code
      WHERE ti.cl='".$this->sd['cl']."'
      AND ti.bc='".$this->sd['branch']."'
      AND ti.nno='".$_POST['qno']."' 
      GROUP BY ti.nno,ti.cl,ti.bc";

      $r_detail['sum']= $this->db->query($sql)->result();

      $sql1="SELECT
      dt.job_no, 
      tj.`item_code`,
      tj.`Item_name`,
      tj.`ddate` AS r_date,
      tj.`fault`,
      dt.`amount` AS job_amt,
      dt.price,
      dt.discount

      FROM t_job tj
      JOIN t_job_issue_det dt ON dt.`job_no`= tj.`nno` 
      JOIN t_job_issue ti ON ti.`cl`=dt.`cl` AND ti.`bc`=dt.`bc` AND ti.`nno`= dt.`nno`
      WHERE dt.cl='".$this->sd['cl']."'
      AND dt.bc='".$this->sd['branch']."' 
      AND dt.nno = '".$_POST['qno']."'
      GROUP BY job_no
      ";

      $r_detail['det']= $this->db->query($sql1)->result();

      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

    }



    public function get_debit_max_no($table_name, $field_name) { 
      if (isset($_POST['hid'])) {
        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
          $this->db->select_max($field_name);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("bc", $this->sd['branch']);
          return $this->db->get($table_name)->first_row()->$field_name + 1;
        } else {
          return $_POST['drn'];
        }
      } else {
        $this->db->select_max($field_name);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        return $this->db->get($table_name)->first_row()->$field_name + 1;
      }
    }

    public function account_update($condition){
      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 102);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");


      if ($_POST['hid'] != "0" || $_POST['hid'] != "") {
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code',102);
        $this->db->where('trans_no',$this->max_no);
        $this->db->delete('t_account_trans');
      }

      $config = array(
        "ddate" => $_POST['date'],
        "trans_code" => 102,
        "trans_no" => $this->max_no,
        "op_acc" => 0,
        "reconcile" => 0,
        "cheque_no" => 0,
        "narration" => "",
        "ref_no" => $_POST['ref_no']
      );

      $des = "Invoice - Service " . $_POST['customer'];
      $this->load->model('account');
      $this->account->set_data($config);

  //----Above all are common

      $this->account->set_value2($des, $_POST['net'], "dr", $_POST['customer'],$condition);

      $acc_code=$this->utility->get_default_acc('SERVICE_INCOME');
      $this->account->set_value2($des,$_POST['net'], "cr", $acc_code,$condition);


      if(isset($_POST['cash']) && !empty($_POST['cash']) && $_POST['cash']>0){
        $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
        $this->account->set_value2($des, $_POST['cash'], "dr",$acc_code,$condition);
        $this->account->set_value2($des, $_POST['cash'], "cr", $_POST['customer'],$condition);

      }

      if(isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card']>0){
        for($x = 0; $x<20; $x++){
          if(isset($_POST['type1_'.$x]) && isset($_POST['amount1_'.$x]) && isset($_POST['bank1_'.$x]) && isset($_POST['no1_'.$x])){
            if(!empty($_POST['type1_'.$x]) && !empty($_POST['amount1_'.$x]) && !empty($_POST['bank1_'.$x]) && !empty($_POST['no1_'.$x])){
              $acc_code = $_POST['acc1_'.$x];
              $this->account->set_value2('credit_card', $_POST['amount1_'.$x], "dr", $acc_code,$condition);    
              $this->account->set_value2($des, $_POST['amount1_'.$x], "cr", $_POST['customer'],$condition);

              $acc_code_dr = $this->utility->get_default_acc('CREDIT_CARD_INTEREST');
              $this->account->set_value2('Merchant commission', $_POST['amount_rate1_'.$x], "dr", $acc_code_dr,$condition);    

              $acc_code_cr = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
              $this->account->set_value2('Merchant commission', $_POST['amount_rate1_'.$x], "cr", $acc_code_cr,$condition); 

            }
          }
        }  
      }

      if(isset($_POST['credit_note']) && !empty($_POST['credit_note']) && $_POST['credit_note']>0){
        $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
        //$this->account->set_value2($des.' Credit Note Settlement', $_POST['credit_note'], "dr", $acc_code,$condition);   
        //$this->account->set_value2($des.' Credit Note Settlement', $_POST['credit_note'], "cr", $_POST['customer'],$condition); 
      }

      if(isset($_POST['cheque_recieve']) && !empty($_POST['cheque_recieve']) && $_POST['cheque_recieve']>0){
        $acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
        $this->account->set_value2($des.' Receive cheque', $_POST['cheque_recieve'], "dr", $acc_code,$condition);
        $this->account->set_value2($des.' Receive cheque', $_POST['cheque_recieve'], "cr", $_POST['customer'],$condition);    
      }

  //if($_POST['cash']<0 || $_POST['credit_card']<0 || $_POST['credit_note']<0 || $_POST['cheque_recieve']<0){
    //$this->account->set_value2($des, $_POST['cash'], "cr", $_POST['customer'],$condition); 
  //}

      if($condition==0){
       $query = $this->db->query("
         SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
         FROM `t_check_double_entry` t
         LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
         WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='102'  AND t.`trans_no` ='" . $this->max_no . "' AND 
         a.`is_control_acc`='0'");

       if ($query->row()->ok == "0") {
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 102);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      }else {
        return "1";
      }
    }
  }


}