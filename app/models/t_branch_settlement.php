<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_branch_settlement extends CI_Model {

  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';

  function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->mtb = $this->tables->tb['t_branch_settlement'];
  }

  public function base_details(){
   $this->load->model("utility");
   $a['max_no']=$this->utility->get_max_no("t_gtn_settlement_sum","nno");
   return $a;
 }

 public function validation(){
  $status=1;

  $this->max_no=$this->utility->get_max_no("t_gtn_settlement_sum","nno"); 

/*  $total_cr=0;
  $total_dr=0;
  if(isset($_POST['ttl_cr']) && !empty($_POST['ttl_cr'])){
    $total_cr=(float)$_POST['ttl_cr'];
  }

  if(isset($_POST['ttl_dr']) && !empty($_POST['ttl_dr'])){
    $total_dr=(float)$_POST['ttl_dr'];
  }  

  if(($total_cr==0 && $total_dr==0) || $total_dr!=$total_cr){
    return "Please check the CR Amount with DR Amount";
  }

  $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_gtn_settlement_sum');
  if($check_is_delete!=1){
    return "Supplier settlement already deleted";
  }
  $supplier_validation = $this->validation->check_is_supplier($_POST['supplier']);
  if($supplier_validation != 1){
    return "Please enter valid supplier";
  }
  $check_valid_cr_no=$this->validation->check_valid_trans_no('supplier','hh_','nn_');
  if($check_valid_cr_no!=1){
    return $check_valid_cr_no;
  }
  $check_valid_dr_no=$this->validation->check_valid_trans_no('supplier','h_','n_');
  if($check_valid_dr_no!=1){
    return $check_valid_dr_no;
  }
  $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle('supplier','hh_','nn_','44_');
  if($check_valid_trans_settle_status!=1){
    return $check_valid_trans_settle_status;
  }  
  $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle('supplier','h_','n_','4_');
  if($check_valid_trans_settle_status2!=1){
    return $check_valid_trans_settle_status2;
  }   */      
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

     $t_gtn_settlement_dr_temp=array(
       "cl"=>$this->sd['cl'],
       "bc"=>$this->sd['branch'],
       "oc"=>$this->sd['oc'],
       "type"=>$_POST['h_0'],
       "trans_no"=>$_POST['n_0'],
       "nno"=>$this->max_no,
       "date"=>$_POST['date'],
       "balance"=>$_POST['3_0'],
       "settle"=>$_POST['4_0'],
       "description"=>$_POST['5_0'],
       "amount"=>$_POST['2_0']
       );

     $t_gtn_settlement_sum=array(
       "cl"=>$this->sd['cl'],
       "bc"=>$this->sd['branch'],
       "oc"=>$this->sd['oc'],                  
       "nno"=>$this->max_no,
       "ddate"=>$_POST['date'],
       "acc_code"=>$_POST['acc_code'],                  
       "is_cancel"=>0,                 
       "amount"=>$_POST['ttl_dr']
       );

     for($i=0 ; $i < 100 ; $i++){
      if(!empty($_POST['44_'.$i])){
        $t_gtn_settlement_cr_temp[]=array(
         "cl"=>$this->sd['cl'],
         "bc"=>$this->sd['branch'],
         "oc"=>$this->sd['oc'],
         "type"=>$_POST['hh_'.$i],
         "trans_no"=>$_POST['nn_'.$i],
         "sub_no"=>$_POST['sn_'.$i],
         "nno"=>$this->max_no,
         "date"=>$_POST['date'],
         "balance"=>$_POST['33_'.$i],
         "settle"=>$_POST['44_'.$i],
         "description"=>$_POST['55_'.$i],
         "amount"=>$_POST['22_'.$i]
         );
      }
    }

    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
      if($this->user_permissions->is_add('t_branch_settlement')){

        if($this->sd['branch']!='MS'){
          $this->db->insert("t_gtn_settlement_sum",$t_gtn_settlement_sum);
          $this->db->insert("t_gtn_settlement_dr_temp",$t_gtn_settlement_dr_temp);

          if(count($t_gtn_settlement_cr_temp)){$this->db->insert_batch("t_gtn_settlement_cr_temp",$t_gtn_settlement_cr_temp);}
          $this->load->model('trans_settlement');

          $this->trans_settlement->save_settlement("t_debit_note_trans",$_POST['acc_code'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],135,$_POST['id'],"0",$_POST['4_0']);
          $this->utility->update_debit_note_balance($_POST['acc_code']);

          for($x=0; $x<100; $x++){
            if(isset($_POST['44_'.$x])){
              if($_POST['hh_'.$x]=="42"){
               $this->trans_settlement->save_settlement("t_gtn_settlement",$_POST['acc_code'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],135,$this->max_no,"0",$_POST['44_'.$x]);  
             }else if($_POST['hh_'.$x]=="17"){
              $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['acc_code'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],135,$this->max_no,"0",$_POST['44_'.$x]);  
            }
          }
        }

        $this->utility->update_transfer_balance_debit($_POST['acc_code']);
      }else{
       $this->db->insert("t_gtn_settlement_sum",$t_gtn_settlement_sum);
       $this->db->insert("t_gtn_settlement_dr_temp",$t_gtn_settlement_dr_temp);
       if(count($t_gtn_settlement_cr_temp)){$this->db->insert_batch("t_gtn_settlement_cr_temp",$t_gtn_settlement_cr_temp);}
       $this->load->model('trans_settlement');

       $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['acc_code'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],135,$_POST['id'],"0",$_POST['4_0']);
       $this->utility->update_credit_note_balance($_POST['acc_code']);

       for($x=0; $x<100; $x++){
        if(isset($_POST['44_'.$x])){
          if($_POST['44_'.$x] != ""){
           $this->trans_settlement->save_settlement("t_internal_transfer_trans",$_POST['acc_code'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],135,$this->max_no,"0",$_POST['44_'.$x]);  
           
         }
       }
     }
     $this->utility->update_transfer_balance_credit($_POST['acc_code']);
   }


   $this->utility->update_credit_note_balance($_POST['acc_code']);  
   $this->utility->save_logger("SAVE",135,$this->max_no,$this->mod); 
   echo $this->db->trans_commit();
 }else{
  echo "No permission to save records";
  $this->db->trans_commit();
}
}else{
  if($this->user_permissions->is_edit('t_branch_settlement')){
   $this->set_delete(); 
   if($this->sd['branch']!='MS'){
     $this->db->where("cl",$this->sd['cl']);
     $this->db->where("bc",$this->sd['branch']);
     $this->db->where("nno",$this->max_no);
     $this->db->update("t_gtn_settlement_sum",$t_gtn_settlement_sum);

     $this->db->where("cl",$this->sd['cl']);
     $this->db->where("bc",$this->sd['branch']);
     $this->db->where("nno",$this->max_no);
     $this->db->update("t_gtn_settlement_dr_temp",$t_gtn_settlement_dr_temp);


     if(count($t_gtn_settlement_cr_temp)){$this->db->insert_batch("t_gtn_settlement_cr_temp",$t_gtn_settlement_cr_temp);}
     $this->load->model('trans_settlement');

     $this->trans_settlement->save_settlement("t_debit_note_trans",$_POST['acc_code'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],135,$_POST['id'],"0",$_POST['4_0']);
     $this->utility->update_debit_note_balance($_POST['acc_code']);

     for($x=0; $x<100; $x++){
      if(isset($_POST['44_'.$x])){
        if($_POST['44_'.$x] != ""){
          if($_POST['hh_'.$x]=="42"){
           $this->trans_settlement->save_settlement("t_gtn_settlement",$_POST['acc_code'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],135,$this->max_no,"0",$_POST['44_'.$x]);  
         }else if($_POST['hh_'.$x]=="17"){
          $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['acc_code'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],135,$this->max_no,"0",$_POST['44_'.$x]);  
        }
      }
    }
  }

  $this->utility->update_transfer_balance_debit($_POST['acc_code']);
}else{
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("nno",$this->max_no);
  $this->db->update("t_gtn_settlement_sum",$t_gtn_settlement_sum);

  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("nno",$this->max_no);
  $this->db->update("t_gtn_settlement_dr_temp",$t_gtn_settlement_dr_temp);

  if(count($t_gtn_settlement_cr_temp)){$this->db->insert_batch("t_gtn_settlement_cr_temp",$t_gtn_settlement_cr_temp);}
  $this->load->model('trans_settlement');

  $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['acc_code'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],135,$_POST['id'],"0",$_POST['4_0']);
  $this->utility->update_credit_note_balance($_POST['acc_code']);

  for($x=0; $x<100; $x++){
    if(isset($_POST['44_'.$x])){
      if($_POST['44_'.$x] != ""){
        $this->trans_settlement->save_settlement("t_internal_transfer_trans",$_POST['acc_code'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],135,$this->max_no,"0",$_POST['44_'.$x]);  
      }
    }
  }
  $this->utility->update_transfer_balance_credit($_POST['acc_code']);
}



$this->utility->save_logger("EDIT",135,$this->max_no,$this->mod); 
echo $this->db->trans_commit();
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



public function set_delete(){
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("nno",$this->max_no);
  $this->db->delete("t_gtn_settlement_cr_temp");
  $this->load->model('trans_settlement');
  $this->trans_settlement->delete_settlement_sub("t_internal_transfer_trans","135",$this->max_no);   
  $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","135",$this->max_no);   
  $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","135",$this->max_no); 
  $this->trans_settlement->delete_settlement_sub("t_gtn_settlement","135",$this->max_no); 

}

public function check_code(){
  $this->db->where('code', $_POST['code']);
  $this->db->limit(1);
  echo $this->db->get($this->mtb)->num_rows;
}

public function load(){
  $this->db->where('code', $_POST['code']);
  $this->db->limit(1);
  echo json_encode($this->db->get($this->mtb)->first_row());
}

public function delete(){

  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errMsg."==".$errFile."==".$errLine); 
  }
  set_error_handler('exceptionThrower'); 
  try { 
    if($this->user_permissions->is_delete('t_branch_settlement')){
      $trans_no=$_POST['trans_no'];

      $this->load->model('trans_settlement');
      $this->trans_settlement->delete_settlement_sub("t_gtn_settlement",135,$trans_no);
      $this->trans_settlement->delete_settlement_sub("t_internal_transfer_trans",135,$trans_no);
      $this->trans_settlement->delete_settlement_sub("t_credit_note_trans",135,$trans_no); 
      $this->trans_settlement->delete_settlement_sub("t_debit_note_trans",135,$trans_no);   

      $data=array('is_cancel'=>'1');
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('nno',$_POST['trans_no']);
      $this->db->update('t_gtn_settlement_sum',$data);  

      $sql="SELECT acc_code FROM t_gtn_settlement_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
      $acc_id=$this->db->query($sql)->first_row()->acc_code;



      $this->utility->update_credit_note_balance_branch($acc_id);
      $this->utility->update_transfer_balance_credit($acc_id);
      $this->utility->update_credit_note_balance($acc_id);  

      $this->utility->update_debit_note_balance_branch($acc_id);
      $this->utility->update_transfer_balance_debit($acc_id);
      $this->utility->update_debit_note_balance($acc_id);  


      $this->utility->save_logger("CANCEL",135,$this->max_no,$this->mod);  

      echo $this->db->trans_commit(); 
    }else{
      echo "No permission to delete records";
      $this->db->trans_commit();
    }
  }catch(Exception $e){ 
    $this->db->trans_rollback();
    echo $e->getMessage()."sOperation fail please contact admin"; 
  }
}

public function select(){
  $query = $this->db->get($this->mtb);
  $s = "<select name='sales_ref' id='sales_ref'>";
  $s.= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
  }
  $s .= "</select>";
  return $s;
}

public function load_item_cr(){
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $acc_code=$_POST['cus'];
  if($this->sd['branch']=='MS'){

    $sql="SELECT  tc.`description`, d.* FROM (
    SELECT s.sub_no,t.sub_cl, t.sub_bc, t.trans_code AS TYPE,t.trans_no, MIN(t.ddate) ddate, SUM(t.`dr`) AS amount,SUM(t.`dr`)-SUM(t.`cr`) AS balance 
    FROM `t_internal_transfer_trans` t
    INNER JOIN `t_internal_transfer_sum` s ON t.trans_no=s.nno AND t.cl= s.cl AND t.bc = s.bc AND s.is_cancel='0'
    WHERE (t.trans_code='42') AND (acc_code='$acc_code') AND t.bc='".$this->sd['branch']."' GROUP BY t.cl,t.bc,t.trans_no, t.trans_code   
    HAVING balance > 0
    ) d INNER JOIN t_trans_code tc ON tc.`code` = d.type WHERE 1=1 ";


    if($_POST['no_1']!="" && $_POST['no_2']!=""){
      $sql.=" AND d.trans_no BETWEEN '".$_POST['no_1']."' AND '".$_POST['no_2']."'";
    }
    $sql.=" HAVING balance > 0";

    $sql.=" UNION ALL SELECT tc.`description`, d.sub_trans_no,d.sub_cl,d.sub_bc,trans_code_no AS TYPE,d.trans_no,d.ddate,d.amount,balance FROM (          
    SELECT t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, sum(t.dr) as amount ,sum(t.dr)-sum(t.cr) as balance 
    FROM `t_debit_note_trans` t
    INNER JOIN `t_debit_note` s ON t.trans_no=s.nno
    WHERE (t.acc_code='$acc_code' AND t.`trans_code` IN ('18') ) AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0' GROUP BY t.sub_cl,t.sub_bc, t.trans_no, t.trans_code
    ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
    INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
    WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' HAVING balance>0";

  }else{


    $sql="SELECT  tc.`description`, d.* FROM (
    SELECT s.nno,s.sub_no,t.bc,t.sub_cl, t.sub_bc, t.trans_code AS TYPE,t.trans_no, MIN(t.ddate) ddate, SUM(t.`dr`) AS amount,s.balance 
    FROM `t_gtn_settlement` t
    INNER JOIN `t_internal_transfer_sum` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0'
    WHERE (t.trans_code='42') AND (acc_code='$acc_code') AND t.bc='".$this->sd['branch']."' GROUP BY t.cl,t.bc,t.trans_no, t.trans_code 
    HAVING balance > 0
    ) d INNER JOIN t_trans_code tc ON tc.`code` = d.type WHERE 1=1 ";

    if($_POST['no_1']!="" && $_POST['no_2']!=""){
      $sql.=" AND d.trans_no BETWEEN '".$_POST['no_1']."' AND '".$_POST['no_2']."'";
    }
    $sql.=" HAVING balance > 0 ";

    $sql.=" UNION ALL 
    SELECT  tc.`description`,  d.* FROM (
    SELECT  t.`sub_trans_code`, t.`trans_no` AS nno,t.sub_trans_no AS sub_trans_no,t.sub_cl, t.`sub_bc`,'17' AS TYPE,t.`trans_no`, MIN(t.ddate) ddate, s.amount ,s.balance 
    FROM `t_credit_note_trans` t
    INNER JOIN `t_credit_note` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc
    WHERE (t.acc_code='$acc_code' AND t.`trans_code`=17) GROUP BY t.sub_cl,t.sub_bc,trans_no, t.trans_code  
    ) d INNER JOIN t_trans_code tc ON tc.`code` = d.type 
    INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
    WHERE d.sub_cl ='".$this->sd['cl']."' AND d.sub_bc = '".$this->sd['branch']."' HAVING balance>0 ORDER BY nno";

  }
  $query=$this->db->query($sql);


  if($query->num_rows() > 0){
   $a['det']=$query->result();
   echo json_encode($a);
 }else{
  $a['det']=2;
  echo json_encode($a);


}
}


public function item_list_all(){

  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $acc_code=$_POST['acc_code'];

  if($this->sd['branch']=='MS'){

   $sql="SELECT
   tc.`description`, ttc.`description` as sub_des, d.* FROM (           
   SELECT t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, sum(t.dr) as amount ,sum(t.dr)-sum(t.cr) as balance 
   FROM `t_credit_note_trans` t
   INNER JOIN `t_credit_note` s ON t.trans_no=s.nno
   WHERE (t.acc_code='$acc_code' AND t.`trans_code` IN ('17','134') ) 
   AND (t.trans_no LIKE '%$_POST[search]%' OR t.`sub_trans_no` LIKE '%$_POST[search]%') 
   AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0' GROUP BY t.sub_cl,t.sub_bc, t.trans_no, t.trans_code
   ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
   INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
   WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' 
   
   HAVING balance>0
   /*UNION ALL

   SELECT  tc.`description`, ttc.`description` as sub_des, d.* FROM (
   SELECT  t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, sum(t.dr) as amount ,sum(t.dr)-sum(t.cr) as balance 
   FROM `t_debit_note_trans` t
   INNER JOIN `t_debit_note` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc
   WHERE (t.acc_code='$acc_code' AND t.`trans_code`=18) GROUP BY t.sub_cl,t.sub_bc,trans_no, t.trans_code  
   ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
   INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
   WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' HAVING balance>0*/
   ";
 }else{
   $sql="SELECT  tc.`description`, ttc.`description` as sub_des, d.* FROM (
   SELECT  t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, sum(t.dr) as amount ,sum(t.dr)-sum(t.cr) as balance 
   FROM `t_debit_note_trans` t
   INNER JOIN `t_debit_note` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc
   WHERE (t.acc_code='$acc_code' AND t.`trans_code`=18)  
   AND (t.trans_no LIKE '%$_POST[search]%' OR t.`sub_trans_no` LIKE '%$_POST[search]%')  GROUP BY t.sub_cl,t.sub_bc,trans_no, t.trans_code  
   ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
   INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
   WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' 

   HAVING balance>0";   
 }




 $query=$this->db->query($sql);        

 $a = "<table id='item_list' style='width : 100%' >";
 $a .= "<thead><tr>";
 $a .= "<th class='tb_head_th'>Type</th>";
 $a .= "<th class='tb_head_th'>No</th>";
 $a .= "<th class='tb_head_th'>Ref Trans Type</th>";
 $a .= "<th class='tb_head_th'>Ref No</th>";
 $a .= "<th class='tb_head_th'>Amount</th>";
 $a .= "<th class='tb_head_th'>Balance</th>";
 $a .= "</thead></tr>";
 $a .= "<tr class='cl'>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";                   
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "</tr>";

 foreach($query->result() as $r){

  $a .= "<tr class='cl'>";
  $a .= "<td style='display:none;'>".$r->trans_code_no."</td>";
  $a .= "<td style='display:none;'>".$r->trans_no."</td>";
  $a .= "<td style='display:none;'>".$r->ddate."</td>";
  $a .= "<td style='display:none;'>".$r->amount."</td>";
  $a .= "<td style='display:none;'>".$r->balance."</td>";
  $a .= "<td style='display:none;'>".$r->description."</td>";

  $a .= "<td>".$r->description."</td>";
  $a .= "<td>".$r->trans_no."</td>";
  $a .= "<td>".$r->sub_des."</td>";
  $a .= "<td>".$r->sub_trans_no."</td>";
  $a .= "<td>".$r->amount."</td>";
  $a .= "<td>".$r->balance."</td>";
  $a .= "</tr>";
}
$a .= "</table>";
echo $a;

}

public function load_data(){
 $this->db->select(array(
  "ddate",
  "amount",
  "is_cancel"
  ));
 $this->db->where('nno',$_POST['id']);
 $this->db->where('cl',$this->sd['cl']);
 $this->db->where('bc',$this->sd['branch']);

 $query=$this->db->get('t_gtn_settlement_sum');
 $a['sum']=$query->result();

 $this->db->select(array(
  "dt.type as TYPE",
  "dt.amount",
  "dt.balance",
  "dt.settle",
  "dt.trans_no",
  "dt.description as des",
  "dt.date",
  "tc.description as description"
  ));

 $this->db->from('t_gtn_settlement_dr_temp dt');
 $this->db->join('t_trans_code tc','dt.type=tc.code',"L");             
 $this->db->where('dt.nno',$_POST['id']);
 $this->db->where('cl',$this->sd['cl']);
 $this->db->where('bc',$this->sd['branch']);
 $query=$this->db->get();
 $a['dr_det']=$query->result();

 $this->db->select(array( 
  "dt.type as TYPE",         
  "dt.amount",
  "dt.balance",
  "dt.settle",
  "dt.trans_no",
  "dt.sub_no",
  "dt.description",
  "dt.date",
  "tc.description as des"
  ));
 $this->db->from('t_gtn_settlement_cr_temp dt');
 $this->db->join('t_trans_code tc','dt.type=tc.code',"L");             
 $this->db->where('dt.nno',$_POST['id']);
 $this->db->where('cl',$this->sd['cl']);
 $this->db->where('bc',$this->sd['branch']);
 $query=$this->db->get();  

 $a['cr_det']=$query->result();

 $this->db->select(array(
  "s.description AS name",
  "m.acc_code"

  ));
 $this->db->from('t_gtn_settlement_sum m');
 $this->db->join('m_account s','s.code=m.acc_code',"L");             
 $this->db->where('m.nno',$_POST['id']);
 $this->db->where('cl',$this->sd['cl']);
 $this->db->where('bc',$this->sd['branch']);
 $this->db->limit(1);
 $query=$this->db->get(); 
 $a['sup_det']=$query->result();
 echo json_encode($a);
}

public function f1_selection_current_account(){

  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $sql="SELECT a.`code`,a.`description` FROM `r_branch_current_acc` c
  JOIN m_account a ON a.`code`=c.`acc_code`
  WHERE (a.code LIKE '%$_POST[search]%' OR a.`description` LIKE '%$_POST[search]%')";   

  $query=$this->db->query($sql);        

  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Code</th>";
  $a .= "<th class='tb_head_th'>Description</th>";
  $a .= "</thead></tr>";
  $a .= "<tr class='cl'>";
  $a .= "<td>&nbsp;</td>";
  $a .= "<td>&nbsp;</td>";
  $a .= "</tr>";

  foreach($query->result() as $r){

    $a .= "<tr class='cl'>";
    $a .= "<td>".$r->code."</td>";
    $a .= "<td>".$r->description."</td>";
    $a .= "</tr>";
  }
  $a .= "</table>";
  echo $a;
}

public function PDF_report(){

  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $r_detail['branch']=$this->db->get('m_branch')->result();

  $r_detail['page']='A5';
  $r_detail['orientation']='L';  
  $r_detail['dfrom']=$_POST['from'];
  $r_detail['dto']=$_POST['to'];


  $query1=$this->db->query('SELECT cs.cl,cs.bc,cs.`ddate`,cs.`nno`,cs.acc_code AS customer,c.description AS name,cs.`amount` 
    FROM `t_gtn_settlement_sum` cs  
    JOIN m_account c ON c.`code`=cs.`acc_code` 
    WHERE cs.`cl`="'.$this->sd['cl'].'" AND cs.`bc`= "'.$this->sd['branch'].'" AND cs.nno="'.$_POST['qno'].'"');

  $r_detail['sum']=$query1->result();

  $query = $this->db->query('SELECT cr.cl,cr.`bc`,t.`description` AS type,cr.`trans_no`,cr.`date`,cr.`amount`,cr.`balance`,cr.`settle`,cr.`description` 
    FROM `t_gtn_settlement_cr_temp` cr 
    JOIN `t_trans_code` t ON t.`code`=cr.`type`
    WHERE cr.`cl`="'.$this->sd['cl'].'" AND cr.`bc`= "'.$this->sd['branch'].'" AND cr.nno="'.$_POST['qno'].'"' );

  $r_detail['cr_det']=$query->result();  

  $query = $this->db->query('SELECT dr.cl,dr.`bc`,t.`description` AS type,dr.`trans_no`,dr.`date`,dr.`amount`,dr.`balance`,dr.`settle`,dr.`description` 
    FROM `t_gtn_settlement_dr_temp` dr 
    JOIN `t_trans_code` t ON t.`code`=dr.`type`
    WHERE dr.`cl`="'.$this->sd['cl'].'" AND dr.`bc`= "'.$this->sd['branch'].'" AND dr.nno="'.$_POST['qno'].'"' );

  $r_detail['dr_det']=$query->result();  



  if($query->num_rows()>0)
  {
    $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  }
  else
  {
    echo "<script>alert('No Data');window.close();</script>";
  }
}


}
