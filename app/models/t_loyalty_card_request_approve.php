<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class t_loyalty_card_request_approve extends CI_Model{

  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct()
  {
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
  }
  
  public function base_details()
  {

    $this->load->model("utility");
    $a['max_no'] = $this->utility->get_max_no("t_loyalty_card_request", "nno");
    $a['cluster']=$this->select_cluster();
    return $a;
  }

  public function select_cluster(){
    $query = $this->db->get('m_cluster');
    $s = "<select name='cluster' id='cluster' style='width:185px;'>";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
     $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." - ".$r->description."</option>";
   }
   $s .= "</select>";
   return $s;
 }

 public function select_branch(){
  $sql="SELECT * FROM m_branch WHERE cl = '".$_POST['cluster']."' AND bc != '".$this->sd['branch']."' ";
  $query = $this->db->query($sql);

  $s = "";
  $s .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
   $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc." - ".$r->name."</option>";
 }
 echo $s;
}



public function validation(){
  $status         = 1;
  $this->max_no = $this->utility->get_max_no("t_loyalty_card_request", "nno");
  return $status;
}


public function save(){
  $this->db->trans_begin();
  error_reporting(E_ALL);    
  function exceptionThrower($type, $errMsg, $errFile, $errLine){
    throw new Exception($errMsg."==".$errFile."==".$errLine);
  }    
  set_error_handler('exceptionThrower');
  try {      
    $validation_status = $this->validation();
    if ($validation_status == 1) {

     for ($x = 0; $x <$_POST['count']; $x++) {

      $sum = array(
        "issue_date" => date("Y-m-d"),
        "is_post"=>'1',
        "issue_oc" => $this->sd['oc'],
      );

      if(isset($_POST['chk_'. $x])){
       $this->db->where("cl", $_POST['0_'. $x]);
       $this->db->where("bc", $_POST['11_'. $x]);
       $this->db->where("nno", $_POST['2_'. $x]);
       $this->db->update("t_loyalty_card_request",$sum);   
       echo $this->db->trans_commit();
     }

   }

 } else {
  echo $validation_status;
  $this->db->trans_commit();
}

}
catch (Exception $e) {
  $this->db->trans_rollback();
  echo $e->getMessage()."Operation fail please contact admin";
}

}


public function load_details(){
  $x=0;

  $cl=$_POST['cl'];
  $bc=$_POST['bc'];

  $sql_load="SELECT r.nno,r.from_cl,r.from_bc,r.cus_code,c.`name`,r.comments,b.name as bc_name
  FROM `t_loyalty_card_request` r
  JOIN `m_customer` c ON c.`code`=r.`cus_code`
  JOIN `m_branch` b ON b.`bc`=r.`bc`
  WHERE r.is_post=0 and is_cancel=0";
  if($cl!=0){
    $sql_load.=" AND r.cl='$cl'";
  }
  if($bc!=0){
    $sql_load.=" AND r.bc='$bc'";
  }
  $sql_load.="  LIMIT 25";

  $query = $this->db->query($sql_load);
  if($query->num_rows()>0){
    $result['det']=$query->result();
  }else{
   $result['det']=2;
 }

 echo json_encode($result);


}



public function load_area(){
  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

  $sql="  SELECT * FROM r_root r
  WHERE (r.`code` LIKE '%$_POST[search]%' 
  OR r.`description` LIKE '%$_POST[search]%' )";

  $query=$this->db->query($sql);

  $a  = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Area Code</th>";
  $a .= "<th class='tb_head_th' colspan='2'>Area</th>";
  $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

  foreach($query->result() as $r){
    $a .= "<tr class='cl'>";
    $a .= "<td>".$r->code."</td>";
    $a .= "<td colspan='2'>".$r->description."</td>";
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

  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];

  $sql="SELECT *,b.ddate AS stdate,b.balance AS bbalance FROM t_balance_correction b
  JOIN r_root r ON r.code=b.area
  JOIN `t_hp_sales_sum` s ON s.`agreement_no`=b.`agr_no`
  JOIN `m_customer` c ON c.`code`=s.`cus_id`";
  if($_POST['qno']=="0"){
    $sql.=" WHERE b.area='".$_POST['bal_area']."' AND b.ddate='".$_POST['bal_date']."' ";
  }else{
    $sql.=" WHERE b.nno='".$_POST['qno']."'";
  }
  $sql.=" GROUP BY b.agr_no HAVING bbalance>0";
  $query = $this->db->query($sql);
  $r_detail['details'] = $this->db->query($sql)->result();

  $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
}



}