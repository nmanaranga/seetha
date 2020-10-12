  <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class t_debit_note extends CI_Model {

    private $sd;
    private $mtb;
    private $max_no;
    private $tb_dn_trans;
    private $tb_acc_trans;

    private $mod = '003';
    private $trans_code=18;

    function __construct(){
      parent::__construct();

      $this->sd = $this->session->all_userdata();
      $this->load->database($this->sd['db'], true);

      $this->mtb = $this->tables->tb['t_debit_note'];
      $this->tb_dn_trans= $this->tables->tb['t_debit_note_trans'];
      $this->tb_acc= $this->tables->tb['m_account'];
      $this->tb_acc_trans= $this->tables->tb['t_account_trans'];
      $this->load->model('trans_settlement');
      $this->load->model('user_permissions');
    }

    public function base_details(){
      $this->load->model('utility');
      $a['max_no'] = $this->utility->get_max_no($this->mtb,'nno');
      return $a;
    }

    public function validation(){
      $status=1;
      $validation;

      $this->max_no=$this->utility->get_max_no($this->mtb,'nno');

      $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_debit_note');
      if($check_is_delete!=1){
        return "This debit note has been already cancelled";
      }

      if($_POST['ref_code']!=0 && $_POST['ref_code']!='18'){
        return "You cannot edit this transaction";
      }

      $radio_btn = $_POST['is_customer']; 
      if($radio_btn == '1'){
        $validation=$this->validation->check_is_customer($_POST['code_s']);
      }else if($radio_btn == '2'){
        $validation=$this->validation->check_is_supplier($_POST['code_s']);
      }else{
        $validation=$this->validation->check_is_account($_POST['code_s']);
      }

      if($validation!=1){
        return $validation;
      }

      $check_account_validation=$this->validation->check_is_account($_POST['acc']);
      if($check_account_validation!=1){
        return $check_account_validation;
      }
      $check_zero_value=$this->validation->empty_net_value($_POST['amount']);
      if($check_zero_value!=1){
        return $check_zero_value;
      }

  /*$account_update=$this->account_update(0);
  if($account_update!=1){
    return "Invalid account entries";
  } */

  return $status;
} 


public function save(){
  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errMsg); 
  }
  set_error_handler('exceptionThrower'); 
  try {
    $this->load->model('utility');

    $validation_status=$this->validation();
    if($validation_status==1){

      $sum=array(
        "cl"            =>$this->sd['cl'],
        "bc"            =>$this->sd['branch'],
        "nno"           =>$this->max_no,
        "ddate"         =>$_POST['date'],
        "ref_no"        =>$_POST['ref_no'],
        "memo"          =>$_POST['description'],
        "is_customer"   =>$_POST['c_type'],
        "code"          =>$_POST['code_s'],
        "acc_code"      =>$_POST['acc'],
        "amount"        =>$_POST['amount'],
        "oc"            =>$this->sd['oc'],
        "ref_trans_code"=>18,
        "ref_trans_no"  =>$this->max_no,
        "balance"       =>$_POST['amount'],
        "dis_type"      =>$_POST['typ'],
        "employee"      =>$_POST['emp']
      );

      if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_debit_note')){
          $account_update=$this->account_update(0);
          if($account_update==1){
            $this->db->insert('t_debit_note',$sum);
            $this->utility->save_logger("SAVE",18,$this->max_no,$this->mod);
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
        if($this->user_permissions->is_edit('t_debit_note')){
          $account_update=$this->account_update(0);
          if($account_update==1){
            if($_POST['is_approve']=="1"){

              $this->account_update(1);
              
              $this->db->where('nno',$_POST['hid']);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update('t_debit_note',$sum);

              $this->db->where('nno',$_POST['hid']);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update('t_debit_note',array("is_approve"=>1));

              
              $this->trans_settlement->delete_settlement($this->tb_dn_trans,$this->trans_code,$this->max_no);
              $this->trans_settlement->save_settlement($this->tb_dn_trans,$_POST['code_s'],$_POST['date'],$this->trans_code ,$this->max_no,$this->trans_code,$this->max_no,$_POST['amount'],0);
              $this->utility->save_logger("APPROVE",18,$this->max_no,$this->mod);
              echo $this->db->trans_commit();
            }else{
              $this->db->where('nno',$_POST['hid']);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update('t_debit_note',$sum);
              $this->utility->save_logger("EDIT",18,$this->max_no,$this->mod);
              echo $this->db->trans_commit();
            }
          }else{
            echo "invalid account entries";
            $this->db->trans_commit();
          }
        }else{
          echo "No permission to edit records";
          $this->db->trans_commit();
        }   
      }
      $this->utility->update_debit_note_balance($_POST['code_s']);
    }else{
      echo $validation_status;
      $this->db->trans_commit();
    }
  }catch(Exception $e){ 
    $this->db->trans_rollback();
    echo "Operation fail please contact admin"; 
  } 
} 

public function check_code(){
  $this->db->where('code', $_POST['code']);
  $this->db->limit(1);
  echo $this->db->get($this->mtb)->num_rows;
}

public function load(){

 if (isset($_POST['num'])){   

  $sql_settle="SELECT  t.cl,
  CONCAT(b.`name`,' - ',t.bc) AS branch,
  t.`ddate`,
  CONCAT(a.description,' - ',acc_code) AS acc_code,
  tc.`description`,
  t.`sub_trans_no`,
  t.`cr` AS amount
  FROM t_debit_note_trans t
  JOIN t_trans_code tc ON tc.`code`=t.`sub_trans_code`
  JOIN m_branch b ON b.`bc` = t.`bc`
  JOIN m_account a ON a.`code` = t.`acc_code`
  WHERE t.cl='".$this->sd['cl']."' AND t.bc='".$this->sd['branch']."'
  AND trans_code='18'
  AND trans_no='".$_POST['num']."'
  AND sub_trans_code!='18'
  -- AND sub_trans_no!='".$_POST['num']."'
  AND t.cr>0";

  $query_s=$this->db->query($sql_settle);
  if($query_s->num_rows()>0){
    $a['settle'] = $query_s->result();
  }else{
    $a['settle'] = 2;
  }

  if($this->chk_cus($_POST['num'])==0){
    $sql="t_debit_note.code=m_supplier.code";
    $sql1="m_supplier";
    $sql2="m_supplier.name";
  }else if($this->chk_cus($_POST['num'])==1){
    $sql="t_debit_note.code=m_customer.code";
    $sql1="m_customer";
    $sql2="m_customer.name";
  }else{
    $sql="t_debit_note.code=a.code";
    $sql1="m_account AS a";
    $sql2="a.description AS name";
  }

  $this->db->select(array(
    't_debit_note.code',
    't_debit_note.ref_no',
    't_debit_note.ddate',
    't_debit_note.memo',
    't_debit_note.acc_code',
    't_debit_note.amount',
    't_debit_note.is_customer',
    't_debit_note.ref_trans_no',
    't_debit_note.ref_trans_code',
    't_debit_note.is_cancel',
    't_debit_note.is_approve',
    'm_account.description',
    't_debit_note.employee',
    't_debit_note.dis_type',
    'm_employee.name as emp_name',
    $sql2
  ));

  $this->db->from('t_debit_note');
  $this->db->join('m_account','t_debit_note.acc_code=m_account.code','LEFT');
  $this->db->join($sql1,$sql,'LEFT');   
  $this->db->join('m_employee',$this->mtb.'.employee=m_employee.code','left');     
  $this->db->where("nno",$_POST['num']);
  $this->db->where("t_debit_note.cl",$this->sd['cl']);
  $this->db->where("t_debit_note.bc",$this->sd['branch']);
  $query1=$this->db->get();

  $a['det']=$query1->result();
  if($query1->num_rows()){
    echo json_encode($a);
  }else{
    echo json_encode("2");
  } 
} 
}


public function validate_settlement() {
      //settlement
  $this->load->model('trans_settlement');
  $a = $this->trans_settlement->has_settled_trans($this->tb_dn_trans, $this->trans_code,$_POST['no']);
  if(count($a) == '1'){
    $b['sum']='0';
  }else{
    $b['sum']=$a;
  }   

  echo json_encode ($b);
}

public function delete(){
  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errMsg); 
  }
  set_error_handler('exceptionThrower'); 
  try {
    if($this->user_permissions->is_delete('t_debit_note')){
      $nno=$_POST['no'];
      $status=$this->trans_cancellation->debit_note_status($nno);

      if($status=="OK"){
        $trans_code=18;
        $table_trans='t_credit_note_trans';

        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("trans_code",$trans_code);
        $this->db->where("trans_no",$nno);
        $this->db->delete('t_debit_note_trans');

        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("trans_code",$trans_code);
        $this->db->where("trans_no",$nno);
        $this->db->delete("t_account_trans"); 
        
        $this->db->where('nno',$nno);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->update('t_debit_note', array("is_cancel"=>1));


        $sql="SELECT code FROM t_debit_note WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$nno."'";
        $sup_id=$this->db->query($sql)->first_row()->code;

        $this->utility->update_debit_note_balance($sup_id);

        $this->utility->save_logger("CANCEL",18,$nno,$this->mod);
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
    echo "Operation fail please contact admin"; 
  }  
}

public function get_next_no(){
  $this->load->model('utility');
  return $this->utility->get_max_no($this->mtb,'nno');
}

public function auto_com(){
 $this->db->like('code', $_GET['q']);
 $this->db->or_like($this->tb_acc.'.description', $_GET['q']);
 $query = $this->db->select(array('code', $this->tb_acc.'.description
  '))
 ->get($this->tb_acc);
 $abc = "";
 foreach($query->result() as $r){
  $abc .= $r->code."|".$r->description;          
  $abc .= "\n";
}
echo $abc;
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

  $r_detail['dt']=$_POST['dt'];
  $r_detail['qno']=$_POST['qno'];
  $r_detail['duplicate'] = $_POST['is_duplicate'];

  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];
  $r_detail['pdf_page_type']=$_POST['type1'];
  $r_detail['no']=$_POST['pdf_no'];
  $r_detail['cus_or_sup']=$_POST['cus_or_sup'];

  if (isset($_POST['pdf_no'])){ 
    if($this->chk_cus($_POST['pdf_no'])==0){
      $sql="t_debit_note.code=m_supplier.code";
      $sql1="m_supplier";
      $sql2="m_supplier.name";
    }else if($this->chk_cus($_POST['pdf_no'])==1){
      $sql="t_debit_note.code=m_customer.code";
      $sql1="m_customer";
      $sql2="m_customer.name";
    }else{
     $sql="t_debit_note.code=a.code";
     $sql1="m_account as a";
     $sql2="a.description AS name";
   }

   $this->db->select(array(
    't_debit_note.code',
    't_debit_note.ref_no',
    't_debit_note.ddate',
    't_debit_note.memo',
    't_debit_note.acc_code',
    't_debit_note.amount',
    't_debit_note.is_cancel',
    'm_account.description',
    $sql2
  ));
   $this->db->from('t_debit_note');
   $this->db->join('m_account','t_debit_note.acc_code=m_account.code');
   $this->db->join($sql1,$sql);      
   $this->db->where("nno",$_POST['pdf_no']);
   $this->db->where("t_debit_note.cl",$this->sd['cl']);
   $this->db->where("t_debit_note.bc",$this->sd['branch']);
   $query1=$this->db->get();

   if($query1->num_rows()){
    $r_detail['det']=$query1->result();
  }
  else{
    echo 2;
  } 
}
if($query1->num_rows()){
 $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
}
else{
  echo "<script>alert('No Data');window.close();</script>";
} 
}

private function chk_cus($num){
 $this->db->select('is_customer');
 $this->db->from("t_debit_note");
 $this->db->where("cl",$this->sd['cl']);
 $this->db->where("bc",$this->sd['branch']);
 $this->db->where("nno",$num);
 $query=$this->db->get()->row()->is_customer;
 return $query;
}

public function account_update($condition){
  $this->db->where("trans_no", $this->max_no);
  $this->db->where("trans_code",$this->trans_code);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->delete("t_check_double_entry");

  if($_POST['hid']=="0"||$_POST['hid']==""){

  }else{
    if($condition=="1"){
      $sql="SELECT is_approve FROM t_debit_note WHERE cl='".$this->sd['cl']."' 
      AND bc='".$this->sd['branch']."' AND nno='".$this->max_no."' LIMIT 1";
      $query=$this->db->query($sql);
      if($query->first_row()->is_approve!=0){
        $this->db->where("trans_no",  $this->max_no);
        $this->db->where("trans_code", $this->trans_code);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_account_trans");
      }
    }
  }

  $config = array(
    "ddate" => $_POST['date'],
    "trans_code"=>$this->trans_code,
    "trans_no"=>$this->max_no,
    "op_acc"=>0,
    "reconcile"=>0,
    "cheque_no"=>0,
    "narration"=>"",
    "ref_no" => $_POST['ref_no']
  );

  $des = "DEBIT NOTE - ".$_POST['description'];
  $this->load->model('account');
  $this->account->set_data($config);

  $this->account->set_value2($des, $_POST['amount'], "dr",  $_POST['code_s'],$condition);
  $this->account->set_value2($des, $_POST['amount'], "cr", $_POST['acc'],$condition);

  if($condition==0){ 
    $query = $this->db->query("
      SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
      FROM `t_check_double_entry` t
      LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
      WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='".$this->trans_code."'  AND t.`trans_no` ='" . $this->max_no . "' AND 
      a.`is_control_acc`='0'");



    if($query->row()->ok=="0"){
      $this->db->where("trans_no",$this->max_no);
      $this->db->where("trans_code",$this->trans_code);
      $this->db->where("cl", $_POST['cl']);
      $this->db->where("bc", $_POST['branch']);
      $this->db->delete("t_account_trans");
      return "0";
    }else{
      return "1";
    }
  } 
}

public function account_delete($trans_no){
  $this->db->where("trans_no",  $trans_no);
  $this->db->where("trans_code", $this->trans_code);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->delete($this->tb_acc_trans);
}

public function status_updation(){
  $this->db->where("nno",$_POST['nno']);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  echo $this->db->update('t_debit_note',array("is_approve"=>1));
}

}





