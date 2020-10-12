<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class t_bankrec extends CI_Model {

  private $sd;
  private $tb_sum;
  private $mtb;
  private $mod = '003';
  private $tb_det;
  private $max_no;

  function __construct(){
   parent::__construct();

   $this->sd = $this->session->all_userdata();
   $this->load->database($this->sd['db'], true);
   $this->mtb = $this->tables->tb['m_items'];
   $this->load->model('user_permissions');
 }

 public function base_details(){
   $a['table_data'] = $this->data_table();
   $a['id'] = $this->utility->get_max_no("t_bank_reconcil_sum", "nno"); 
   return $a;
 }

 public function data_table(){
  $this->load->library('table');
  $this->load->library('useclass');

  $this->table->set_template($this->useclass->grid_style());

  $code = array("data"=>"Code", "style"=>"width: 50px;");
  $description = array("data"=>"Description", "style"=>"");
  $action = array("data"=>"Action", "style"=>"width: 60px;");

  $this->table->set_heading($code, $description, $action);
  return $this->table->generate();
}

public function get_data_table(){
  echo $this->data_table();
}

public function save(){
  $this->max_no = $this->utility->get_max_no("t_bank_reconcil_sum", "nno"); 
  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errFile); 
  }
  set_error_handler('exceptionThrower'); 
  try { 

    $_POST['cl']=$this->sd['cl'];
    $_POST['branch']=$this->sd['branch'];
    $_POST['oc']=$this->sd['oc']; 

    $t_bank_rec_sum=array(
     "cl"           =>$_POST['cl'],
     "bc"           =>$_POST['branch'],
     "nno"          =>$this->max_no,
     "ddate"        =>$_POST['date'],
     "account_id"   =>$_POST['bank_id'],
     "date_from"    =>$_POST['date_from'],
     "date_to"      =>$_POST['date_to'],
     "opening_bal"  =>$_POST['op_balance'],
     "closing_bal"  =>$_POST['cl_balance'],
     "total_dr"     =>$_POST['total_dr'],
     "total_cr"     =>$_POST['total_cr'],
     "reconcil_dr"  =>$_POST['chk_dr'],
     "reconcil_cr"  =>$_POST['chk_cr'],
     "oc"           =>$_POST['oc'],
   );


    for($x = 0; $x<200; $x++){    
      if(isset($_POST['tcd_'.$x])){  
        if($_POST['tcd_'.$x]!=""){  

          if(isset($_POST['reconz_'.$x])){
            $tick =1;   
          }else{
            $tick =0; 
          }
          $t_bank_rec_det[]= array(
            "cl"          =>$_POST['cl'],
            "bc"          =>$_POST['branch'],
            "nno"         =>$this->max_no,
            "trans_date"  =>$_POST['date_'.$x],
            "description" =>$_POST['description_'.$x],
            "trans_code"  =>$_POST['tc_'.$x],
            "trans_no"    =>$_POST['no_'.$x],
            "dr"          =>$_POST['dr_'.$x],
            "cr"          =>$_POST['cr_'.$x],
            "auto"        =>$_POST['auto_'.$x],
            "is_reconcil" =>$tick,
          );

        }        
      }
    }

    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
      if($this->user_permissions->is_add('t_bankrec')){ 
        if($_POST['temp_save']=='0'){
          $this->db->insert("t_bank_reconcil_sum", $t_bank_rec_sum);
          if(count($t_bank_rec_det)){$this->db->insert_batch("t_bank_reconcil_det",$t_bank_rec_det);}  

          for($x = 0; $x<200; $x++){      
            if(isset($_POST['reconz_'.$x])){
             $this->db->query("UPDATE `t_account_trans` 
              SET `bank_rec_no` = '".$this->max_no."' , reconcile='1'
              WHERE cl = '".$_POST['cl']."' AND 
              bc = '".$_POST['branch']."' AND  
              acc_code = '".$_POST['bank_id']."' AND 
              trans_code = '".$_POST['tc_'.$x]."' AND 
              trans_no = '".$_POST['no_'.$x]."' AND
              auto_no='".$_POST['auto_'.$x]."'");
           }
         }

         for($x = 0; $x<200; $x++){      
          if(isset($_POST['reconz_'.$x])){
           $this->db->query("UPDATE `t_account_trans` 
            SET temp_save='0'
            WHERE cl = '".$_POST['cl']."' AND 
            bc = '".$_POST['branch']."' AND  
            acc_code = '".$_POST['bank_id']."' AND 
            trans_code = '".$_POST['tc_'.$x]."' AND 
            trans_no = '".$_POST['no_'.$x]."' AND
            auto_no='".$_POST['auto_'.$x]."'");
         }
       }
       $this->save_additional_empty_undifined($this->max_no);
       $this->save_additional_empty_bank_error($this->max_no);
       $this->save_additional_bank_chg($this->max_no);
       $this->utility->save_logger("SAVE",84,$this->max_no,$this->mod);
       echo $this->db->trans_commit();
     }else{

      for($x = 0; $x<200; $x++){      
        if(isset($_POST['reconz_'.$x])){
         $this->db->query("UPDATE `t_account_trans` 
          SET  reconcile='1'
          WHERE cl = '".$_POST['cl']."' AND 
          bc = '".$_POST['branch']."' AND  
          acc_code = '".$_POST['bank_id']."' AND 
          trans_code = '".$_POST['tc_'.$x]."' AND 
          trans_no = '".$_POST['no_'.$x]."' AND
          auto_no='".$_POST['auto_'.$x]."'");
       }
     }

     for($x = 0; $x<200; $x++){      
      if(isset($_POST['reconz_'.$x])){
       $this->db->query("UPDATE `t_account_trans` 
        SET temp_save='1'
        WHERE cl = '".$_POST['cl']."' AND 
        bc = '".$_POST['branch']."' AND  
        acc_code = '".$_POST['bank_id']."' AND 
        trans_code = '".$_POST['tc_'.$x]."' AND 
        trans_no = '".$_POST['no_'.$x]."'AND
        auto_no='".$_POST['auto_'.$x]."'");
     }
   }

   echo $this->db->trans_commit();
 }
}else{
  echo "No permission to save records";
  $this->db->trans_commit();
} 
}else{
  $this->max_no = $this->utility->get_max_no("t_bank_reconcil_sum", "nno"); 
  if($this->user_permissions->is_edit('t_bankrec')){ 
   if($_POST['temp_save']=='0'){
    $this->db->where('nno',$_POST['hid']);
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
    $this->db->update("t_bank_reconcil_sum", $t_bank_rec_sum);
    $this->set_delete();
    $this->save_additional_empty_undifined($this->max_no);
    $this->save_additional_empty_bank_error($this->max_no);
    $this->save_additional_bank_chg($this->max_no);
    if(count($t_bank_rec_det)){$this->db->insert_batch("t_bank_reconcil_det",$t_bank_rec_det);}  

    for($x = 0; $x<200; $x++){ 
      if($_POST['tcd_'.$x]!=""){       
       $this->db->query("UPDATE `t_account_trans` 
        SET `bank_rec_no` = '0' , reconcile='0'
        WHERE cl = '".$_POST['cl']."' AND 
        bc = '".$_POST['branch']."' AND  
        acc_code = '".$_POST['bank_id']."' AND 
        trans_code = '".$_POST['tc_'.$x]."' AND 
        trans_no = '".$_POST['no_'.$x]."' AND
        auto_no='".$_POST['auto_'.$x]."'");
     }
   }

   for($x = 0; $x<200; $x++){      
    if(isset($_POST['reconz_'.$x])){
     $this->db->query("UPDATE `t_account_trans` 
      SET `bank_rec_no` = '".$this->max_no."', reconcile='1'
      WHERE cl = '".$_POST['cl']."' AND 
      bc = '".$_POST['branch']."' AND  
      acc_code = '".$_POST['bank_id']."' AND 
      trans_code = '".$_POST['tc_'.$x]."' AND 
      trans_no = '".$_POST['no_'.$x]."' AND
      auto_no='".$_POST['auto_'.$x]."'");
   }
 }

 for($x = 0; $x<200; $x++){      
  if(isset($_POST['reconz_'.$x])){
   $this->db->query("UPDATE `t_account_trans` 
    SET temp_save='0'
    WHERE cl = '".$_POST['cl']."' AND 
    bc = '".$_POST['branch']."' AND  
    acc_code = '".$_POST['bank_id']."' AND 
    trans_code = '".$_POST['tc_'.$x]."' AND 
    trans_no = '".$_POST['no_'.$x]."' AND
    auto_no='".$_POST['auto_'.$x]."'");
 }
}

$this->utility->save_logger("EDIT",84,$this->max_no,$this->mod); 
echo $this->db->trans_commit();
}else{

  for($x = 0; $x<200; $x++){      
    if(isset($_POST['reconz_'.$x])){
     $this->db->query("UPDATE `t_account_trans` 
      SET  reconcile='1'
      WHERE cl = '".$_POST['cl']."' AND 
      bc = '".$_POST['branch']."' AND  
      acc_code = '".$_POST['bank_id']."' AND 
      trans_code = '".$_POST['tc_'.$x]."' AND 
      trans_no = '".$_POST['no_'.$x]."' AND
      auto_no='".$_POST['auto_'.$x]."'");
   }
 }

 for($x = 0; $x<200; $x++){      
  if(isset($_POST['reconz_'.$x])){
   $this->db->query("UPDATE `t_account_trans` 
    SET temp_save='1'
    WHERE cl = '".$_POST['cl']."' AND 
    bc = '".$_POST['branch']."' AND  
    acc_code = '".$_POST['bank_id']."' AND 
    trans_code = '".$_POST['tc_'.$x]."' AND 
    trans_no = '".$_POST['no_'.$x]."' AND
    auto_no='".$_POST['auto_'.$x]."'");
 }
}

echo $this->db->trans_commit();
}
}else{
  echo "No permission to edit records";
  $this->db->trans_commit();
}
}        
}catch(Exception $e){ 
  $this->db->trans_rollback();
  echo $e->getMessage()."Operation fail please contact admin"; 
} 
}  

public function save_additional_empty_undifined($no){
  $sql="SELECT nno FROM t_bank_rec_additional_det
  WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='$no' AND type='1'";
  $query=$this->db->query($sql);

  if($query->num_rows()>0){
  }else{
    $addi_det = array(
      "cl"          =>$this->sd['cl'],
      "bc"          =>$this->sd['branch'],
      "nno"         =>$no,
      "type"        =>'1',
      "rec_ddate"   =>$_POST['date'], 
      "date"        =>$_POST['date'], 
      "description" =>"This bank reconcillation number haven't undifined deposits",
      "dr_amount"   =>'0.00',
      "oc"          =>$this->sd['oc']
    );
    $this->db->insert("t_bank_rec_additional_det", $addi_det);
  }
}

public function save_additional_empty_bank_error($no){
  $sql="SELECT nno FROM t_bank_rec_additional_det
  WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='$no' AND type='2'";
  $query=$this->db->query($sql);

  if($query->num_rows()>0){
  }else{
    $addi_det = array(
      "cl"          =>$this->sd['cl'],
      "bc"          =>$this->sd['branch'],
      "nno"         =>$no,
      "type"        =>'2',
      "rec_ddate"   =>$_POST['date'], 
      "date"        =>$_POST['date'], 
      "description" =>"This bank reconcillation number haven't error made by bank",
      "dr_amount"   =>'0.00',
      "oc"          =>$this->sd['oc']
    );
    $this->db->insert("t_bank_rec_additional_det", $addi_det);
  }
}

public function save_additional_bank_chg($no){
  $sql="SELECT nno FROM t_bank_rec_additional_det
  WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='$no' AND type='3'";
  $query=$this->db->query($sql);

  if($query->num_rows()>0){
  }else{
    $addi_det = array(
      "cl"          =>$this->sd['cl'],
      "bc"          =>$this->sd['branch'],
      "nno"         =>$no,
      "type"        =>'3',
      "rec_ddate"   =>$_POST['date'], 
      "date"        =>$_POST['date'], 
      "description" =>"This bank reconcillation number haven't Bank Chargers",
      "dr_amount"   =>'0.00',
      "oc"          =>$this->sd['oc']
    );
    $this->db->insert("t_bank_rec_additional_det", $addi_det);
  }
}

public function set_delete(){
  $this->db->where('nno',$this->max_no);
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->delete("t_bank_reconcil_det");
}


public function load(){

  $x=1;

  $sql="SELECT s.* ,a.description as acc_des
  FROM t_bank_reconcil_sum s
  JOIN m_account a on a.code=s.account_id
  WHERE s.`nno`='".$_POST['code']."' AND s.`cl`='".$this->sd['cl']."' AND s.`bc`='".$this->sd['branch']."' ";
  $query=$this->db->query($sql);
  if($query->num_rows()>0){
    $a['sum']=$query->result();
  }else{
    $x=2;
  }

  $sql1="SELECT d.* , t.description as t_des 
  FROM t_bank_reconcil_det d
  JOIN t_trans_code t on t.code=d.trans_code
  WHERE d.`nno`='".$_POST['code']."' AND d.`cl`='".$this->sd['cl']."' AND d.`bc`='".$this->sd['branch']."' 
  ORDER BY d.trans_date,d.auto_no";
  $query1=$this->db->query($sql1);
  if($query1->num_rows()>0){
    $a['det']=$query1->result();
  }else{
    $x=2;
  }

  if($x!=2){
    echo json_encode($a);
  }else{
    echo $x;
  }
}

public function delete(){
  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errMsg); 
  }
  set_error_handler('exceptionThrower'); 
  try {
    if($this->user_permissions->is_delete('t_bankrec')){
      $this->db->where('nno',$_POST['code']);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->update("t_bank_reconcil_sum",array("is_cancel"=>1));

      $this->db->where('bank_rec_no',$_POST['code']);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->update("t_account_trans",array("bank_rec_no"=>0));

        //----delete undifined deposits from account trans start-------
      $this->db->where('trans_no',$_POST['code']);
      $this->db->where('trans_code',85);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->delete("t_account_trans");

      $this->db->where('nno',$_POST['code']);
      $this->db->where('type',1);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->update("t_bank_rec_additional_det",array("is_cancel"=>1));
        //-----end

        //----delete error made by banks from account trans start-------
      $this->db->where('trans_no',$_POST['code']);
      $this->db->where('trans_code',86);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->delete("t_account_trans");

      $this->db->where('nno',$_POST['code']);
      $this->db->where('type',2);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->update("t_bank_rec_additional_det",array("is_cancel"=>1));
        //-----end

        //----delete bank chargers from account trans start-------
      $this->db->where('trans_no',$_POST['code']);
      $this->db->where('trans_code',94);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->delete("t_account_trans");

      $this->db->where('nno',$_POST['code']);
      $this->db->where('type',3);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->update("t_bank_rec_additional_det",array("is_cancel"=>1));
        //-----end
      echo $this->db->trans_commit();

    }else{
      $this->db->trans_commit();
      echo "No permission to delete records";
    }

  }catch(Exception $e){ 
    $this->db->trans_rollback();
    echo $e->getMessage()." - Operation fail please contact admin"; 
  } 
}

public function select(){
  $query = $this->db->get($this->mtb);

  $s = "<select name='sales_ref' id='sales_ref'>";
  $s .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
  }
  $s .= "</select>";

  return $s;
}


public function select_bank(){

  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

    $sql = "SELECT a.code,a.description FROM `m_account` a WHERE (code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%' )AND a.is_bank_acc='1' LIMIT 25";

    $query = $this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th' colspan='2'>Name</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->code."</td>";
      $a .= "<td>".$r->description."</td>";

      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }

  public function load_grid(){

    $acc_code=$_POST['acc_code'];
    $fdate=$_POST['date_from'];
    $tdate=$_POST['date_to'];

    $x=0;
    $sql_sum="SELECT a.acc_code,a.ddate,a.description,a.trans_code,t.`description` as trans,a.trans_no,a.dr_amount,a.cr_amount
    ,a.bank_rec_no,a.`reconcile`,a.`temp_save`, a.`auto_no`
    FROM`t_account_trans` a join `t_trans_code` t on t.`code`=a.`trans_code` WHERE a.acc_code='$acc_code' AND a.bank_rec_no = 0 AND a.ddate  <= '$tdate' AND ((a.`reconcile`=0 AND a.temp_save=0) OR(a.`reconcile`=1 AND a.temp_save=1)) 
    AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' LIMIT 200 ";

    $query2 = $this->db->query($sql_sum);               

    if ($query2->num_rows() > 0) {
      $a['det'] = $query2->result();
    } else {
      $x = 2;
    }  

    if ($x == 0) {
      echo json_encode($a);
    } else {
      echo json_encode($x);
    }  
  }


  public function op_bal(){

    $acc_code=$_POST['acc_code'];
    $fdate=$_POST['date_from'];
    $tdate=$_POST['date_to'];

    $x=0;
    $sql_sum="SELECT IFNULL(sum(dr_amount)-sum(cr_amount),0) as bal from `t_account_trans` WHERE acc_code='$acc_code' AND ddate <'$fdate' ";

    $query2 = $this->db->query($sql_sum);               

    if ($query2->num_rows() > 0) {
      $a['op'] = $query2->result();
    } else {
      $x = 2;
    }  

    if ($x == 0) {
      echo json_encode($a);
    } else {
      echo json_encode($x);
    }  
  }

  public function PDF_report(){

   $this->db->select(array(
    'name',
    'address',
    'tp',
    'fax',
    'email'
  ));
   $this->db->where("cl", $this->sd['cl']);
   $this->db->where("bc", $this->sd['branch']);
   $r_detail['branch'] = $this->db->get('m_branch')->result();

   $invoice_number      = $this->utility->invoice_format($_POST['qno']);
   $session_array       = array(
    $this->sd['cl'],
    $this->sd['branch'],
    $invoice_number
  );

   $this->db->select(array(
    'loginName'
  ));
   $this->db->where('cCode', $this->sd['oc']);
   $r_detail['user'] = $this->db->get('users')->result();

   $cl = $this->sd['cl'];
   $bc = $this->sd['branch'];
   $id = $_POST['qno'];

   $r_detail['session'] = $session_array;
   $r_detail['page']        = $_POST['page'];
   $r_detail['header']      = $_POST['header'];
   $r_detail['orientation'] = $_POST['orientation']; 

   $sql_sum = "SELECT  account_id, 
   a.`description`, 
   date_to 
   FROM t_bank_reconcil_sum s
   JOIN m_account a ON a.`code` = s.`account_id`
   WHERE cl='".$this->sd['cl']."' 
   AND bc='".$this->sd['branch']."' 
   AND nno='$id'";
   $query_sum = $this->db->query($sql_sum);
   $r_detail['sum'] = $query_sum->result(); 
   $acc = $query_sum->row()->account_id;
   $ddate=$query_sum->row()->date_to;

   $sql_op="SELECT SUM(dr_amount)-SUM(cr_amount) AS balance
   FROM t_account_trans
   WHERE acc_code = '$acc'
   AND cl='".$this->sd['cl']."'
   AND bc='".$this->sd['branch']."'
   AND ddate <= '$ddate' ";

   $query_op = $this->db->query($sql_op);
   $r_detail['op_balance'] = $query_op->row()->balance; 

   $sql_undifined="SELECT description,dr_amount 
   FROM t_bank_rec_additional_det a
   WHERE `type`='1'
   AND cl ='".$this->sd['cl']."'
   AND bc='".$this->sd['branch']."'
   AND nno='$id'
   AND dr_amount > 0";

   $query_undifined=$this->db->query($sql_undifined);
   $r_detail['unidentified'] = $query_undifined->result(); 

   $sql2="SELECT description,cr 
   FROM t_bank_reconcil_sum s
   JOIN `t_bank_reconcil_det` d ON d.cl = s.`cl` AND d.bc = s.bc AND d.nno = s.`nno`
   WHERE s.cl='".$this->sd['cl']."'
   AND s.bc='".$this->sd['branch']."'
   AND s.nno='$id'
   AND d.is_reconcil ='0'
   AND cr > 0";

   $query2 = $this->db->query($sql2);
   $r_detail['chq_not_presented'] = $query2->result(); 


   $sql_error="SELECT description,cr_amount 
   FROM t_bank_rec_additional_det a
   WHERE `type`='2'
   AND cl ='".$this->sd['cl']."'
   AND bc='".$this->sd['branch']."'
   AND nno='$id'
   AND cr_amount > 0";

   $query_error=$this->db->query($sql_error);
   $r_detail['error_made_cr'] = $query_error->result(); 

   $sql3="SELECT description,dr 
   FROM t_bank_reconcil_sum s
   JOIN `t_bank_reconcil_det` d ON d.cl = s.`cl` AND d.bc = s.bc AND d.nno = s.`nno`
   WHERE s.cl='".$this->sd['cl']."'
   AND s.bc='".$this->sd['branch']."'
   AND s.nno='$id'
   AND d.is_reconcil ='0'
   AND dr > 0";

   $query3 = $this->db->query($sql3);
   $r_detail['deposit_not_realized'] = $query3->result(); 

   $sql_error2="SELECT description,dr_amount 
   FROM t_bank_rec_additional_det a
   WHERE `type`='2'
   AND cl ='".$this->sd['cl']."'
   AND bc='".$this->sd['branch']."'
   AND nno='$id'
   AND dr_amount >0";

   $query_error2=$this->db->query($sql_error2);
   $r_detail['error_made_dr'] = $query_error2->result(); 

   $sql_bank_chg="SELECT description,dr_amount 
   FROM t_bank_rec_additional_det a
   WHERE `type`='3'
   AND cl ='".$this->sd['cl']."'
   AND bc='".$this->sd['branch']."'
   AND nno='$id'
   AND dr_amount >0";

   $query_chargers=$this->db->query($sql_bank_chg);
   $r_detail['bank_chargers'] = $query_chargers->result(); 

   if($query_sum ->num_rows()>0){            
    $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  }else{
    echo "<script>alert('No data found');close();</script>";
  }

}
}

