<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class utility extends CI_Model {
  private $sd;
  function __construct(){
    parent::__construct();
    $this->sd = $this->session->all_userdata();
  }
  
  public function is_SuperAdmin(){
   return ($this->sd['up_oc']=='0' && $this->sd['name']=='SuperAdmin');
 }

 public function get_max_no($table_name,$field_name){
  if(isset($_POST['hid'])){
    if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
      $this->db->select_max($field_name);
      $this->db->where("cl",$this->sd['up_cl']);
      $this->db->where("bc",$this->sd['up_branch']);    
      return $this->db->get($table_name)->first_row()->$field_name+1;
    }else{
      return $_POST['hid'];  
    }
  }else{
    $this->db->select_max($field_name);
    $this->db->where("cl",$this->sd['up_cl']);
    $this->db->where("bc",$this->sd['up_branch']);    
    return $this->db->get($table_name)->first_row()->$field_name+1;
  }
}


public function get_max_no2($table_name,$field_name){
 
  $this->db->select_max($field_name);
  return $this->db->get($table_name)->first_row()->$field_name+1;
  
}

public function save_logger($action,$trans_code,$trans_no,$module){
 $data=array(
  "cl"=>$this->sd['up_cl'],
  "bc"=>$this->sd['up_branch'],
  "oc"=>$this->sd['up_oc'],
  "action"=>$action,
  "trans_code"=>$trans_code,
  "trans_no"=>$trans_no,
  "module"=>$module,
  "ip_address"=>$this->input->ip_address()
  );
 $this->db->insert("t_log_det",$data);
}

public function get_data_table(){
  echo $this->data_table2();
}

public function data_table2(){
  $this->load->library('table');
  $this->load->library('useclass');
  
  $this->table->set_template($this->useclass->grid_style());
  
  
  $codes=$_POST['code'];
  $tbl=$_POST['tbl'];

  

  if(isset($_POST['col4'])){


    $pp=explode("-",$_POST['tbl_fied_names']);

    $tbl_field_name1=$pp[0];
    $tbl_field_name2=$pp[1];
    $tbl_field_name3=$pp[2];

    $pp1=explode("-",$_POST['fied_names']);

    $field_name1=$pp1[0];
    $field_name2=$pp1[1];
    $field_name3=$pp1[2];
    
    
    $code = array("data"=>"$tbl_field_name1", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
    $des = array("data"=>"$tbl_field_name2", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
    $field_head = array("data"=>"$tbl_field_name3", "style"=>"cursor : pointer;", "onclick"=>"set_short(3)");
    $action = array("data"=>"Action", "style"=>"width: 100px;");

    $this->table->set_heading($code, $des, $field_head, $action);

    $sql="SELECT `$field_name1`,`$field_name2`, `$field_name3` FROM ".$tbl." WHERE $field_name1 like '%$codes%' OR `$field_name2` like '%$codes%' OR `$field_name3` like '%$codes%' LIMIT 10";
    
    $query=$this->db->query($sql);

    foreach($query->result() as $r){

      if ($r->cCode==0 && $r->loginName=='SuperAdmin') {continue;}

      $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->$field_name1."\")' title='Edit' />&nbsp;&nbsp;";
      $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->$field_name1."\")' title='Delete' />";
      $ed = array("data"=>$but, "style"=>"text-align: center; width: 100px;");
      $des = array("data"=>$this->useclass->limit_text($r->$field_name2, 50), "style"=>"text-align: left;");
      $field_body = array("data"=>$this->useclass->limit_text($r->$field_name3, 50), "style"=>"text-align: left;");
      $code = array("data"=>$r->$field_name1, "style"=>"text-align: left; width: 100px; ", "value"=>"code");
      
      $this->table->add_row($code, $des, $field_body, $ed);
    }


  }

  else if(isset($_POST['item'])){


    $code = array("data"=>"Code", "style"=>"width: 80px; cursor : pointer;");
    $des = array("data"=>"Description", "style"=>"width: 150px;");
    $min = array("data"=>"Min Price", "style"=>"width: 80px;");
    $max = array("data"=>"Max Price", "style"=>"width: 80px;");
    $pur = array("data"=>"Purchase", "style"=>"width: 100px;");
    $action = array("data"=>"Action", "style"=>"width: 80px;");

    $this->table->set_heading($code, $des, $min,$max, $pur, $action);

    $sql="SELECT `code`, `description`, `max_price`, `min_price`, `purchase_price` FROM ".$tbl." WHERE `code` like '%$codes%' OR `description` like '%$codes%' OR `max_price` like '%$codes%' OR `min_price` like '%$codes%' OR `purchase_price` like '%$codes%'LIMIT 10";
    
    $query=$this->db->query($sql);


    foreach($query->result() as $r){
      $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
      $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
      $ed = array("data"=>$but, "style"=>"text-align: center;");
      $code = array("data"=>$r->code, "value"=>"code");
      $des = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
      $max = array("data"=>number_format($r->max_price, 2, ".", ","), "style"=>"text-align: right;");
      $min = array("data"=>number_format($r->min_price, 2, ".", ","), "style"=>"text-align: right;");
      $pur = array("data"=>number_format($r->purchase_price, 2, ".", ","), "style"=>"text-align: right;");
      $this->table->add_row($code, $des, $min, $max, $pur, $ed);
    }
  }


  
  else if(isset($_POST['itemFree'])){

    $code = array("data"=>"No", "style"=>"width: 50px;");
    $item = array("data"=>"Item", "style"=>"width: 50px;");
    $description = array("data"=>"Description", "style"=>"");
    $from = array("data"=>"From Date", "style"=>"width: 60px;");
    $to = array("data"=>"To Date", "style"=>"width: 50px;");
    $action = array("data"=>"Action", "style"=>"width: 60px;");
    
    $this->table->set_heading($code,$item, $description, $from,$to,$action);
    
    $sql="SELECT `code`, `nno`, `description`, `date_from`, `date_to` FROM ".$tbl." WHERE `code` like '%$codes%' OR `description` like '%$codes%' OR `nno` like '%$codes%' OR `date_from` like '%$codes%' OR `date_to` like '%$codes%'LIMIT 10";
    $query=$this->db->query($sql);

    
    foreach($query->result() as $r){
      $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->nno."\")' title='Edit' />&nbsp;&nbsp;";
      $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->nno."\")' title='Delete' />";
      
      $no = array("data"=>$r->nno);
      $item = array("data"=>$r->code);
      $description = array("data"=>$this->useclass->limit_text($r->description, 25));
      $from = array("data"=>$r->date_from);
      $to = array("data"=>$r->date_to);
      $action = array("data"=>$but, "style"=>"text-align: center;");
      
      $this->table->add_row($no, $item, $description, $from,$to, $action);
    }
    
  }else{

    $pp=explode("-",$_POST['tbl_fied_names']);

    $tbl_field_name1=$pp[0];
    $tbl_field_name2=$pp[1];
    
    $pp1=explode("-",$_POST['fied_names']);

    $field_name1=$pp1[0];
    $field_name2=$pp1[1];
    
    $code = array("data"=>"$tbl_field_name1", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
    $des = array("data"=>"$tbl_field_name2", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
    $action = array("data"=>"Action", "style"=>"width: 100px;");

    $this->table->set_heading($code, $des, $action);

    $sql="SELECT `$field_name1`,`$field_name2` FROM ".$tbl." WHERE $field_name1 like '%$codes%' OR `$field_name2` like '%$codes%' LIMIT 10";
    $query=$this->db->query($sql);

    foreach($query->result() as $r){

      $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->$field_name1."\")' title='Edit' />&nbsp;&nbsp;";
      $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->$field_name1."\")' title='Delete' />";
      $ed = array("data"=>$but, "style"=>"text-align: center; width: 100px;");
      $des = array("data"=>$this->useclass->limit_text($r->$field_name2, 50), "style"=>"text-align: left;");
      $code = array("data"=>$r->$field_name1, "style"=>"text-align: left; width: 100px; ", "value"=>"code");
      
      $this->table->add_row($code, $des, $ed);
    }
  }        
  return $this->table->generate();
}

public function select_user(){
  $role_id= $_POST['u_role'];

  if($_POST['u_role']!=""){
   $sql ="SELECT   s_users.`cCode`,
   s_users.`loginName`,
   s_users.`discription` 
   FROM  u_user_role 
   JOIN  u_add_user_role ON u_user_role.`role_id` = u_add_user_role.`role_id` 
   JOIN  s_users ON u_add_user_role.`user_id` = s_users.`cCode` 
   WHERE u_user_role.`role_id` = '$role_id' 
   AND (cCode LIKE '%$_POST[search]%' OR discription LIKE '%$_POST[search]%') 
   GROUP BY cCode
   LIMIT 25";
 }else{
  $sql ="SELECT   s_users.`cCode`,
  s_users.`loginName`,
  s_users.`discription` 
  FROM  u_user_role 
  JOIN  u_add_user_role ON u_user_role.`role_id` = u_add_user_role.`role_id` 
  JOIN  s_users ON u_add_user_role.`user_id` = s_users.`cCode` 
  WHERE cCode LIKE '%$_POST[search]%' OR discription LIKE '%$_POST[search]%' 
  GROUP BY cCode
  LIMIT 25";
}



$query = $this->db->query($sql);
$a = "<table id='item_list' style='width : 100%' >";
$a .= "<thead><tr>";
$a .= "<th class='tb_head_th'>Code</th>";
$a .= "<th class='tb_head_th'>Login Name</th>";
$a .= "<th class='tb_head_th' colspan='2'>Description</th>";
$a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

foreach($query->result() as $r){
  $a .= "<tr class='cl'>";
  $a .= "<td>".$r->cCode."</td>";
  $a .= "<td>".$r->loginName."</td>";
  $a .= "<td>".$r->discription."</td>";

  
  $a .= "</tr>";
}
$a .= "</table>";
echo $a;
}




public function f1_selection_list(){
  $table=$_POST['data_tbl'];
  $field=isset($_POST['field'])?$_POST['field']:'code';
  $field2=isset($_POST['field2'])?$_POST['field2']:'description';
  $hid_field=isset($_POST['hid_field'])?$_POST['hid_field']:0;
  $add_query=isset($_POST['add_query'])?$_POST['add_query']:"";

  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

  if($add_query!=""){
    $sql = "SELECT * FROM $table  WHERE ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' ) ".$add_query." LIMIT 25";
  }else{
    $sql = "SELECT * FROM $table  WHERE $field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' LIMIT 25";
  }


  $query = $this->db->query($sql);
  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Code</th>";
  $a .= "<th class='tb_head_th' colspan='2'>Description</th>";
  $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

  foreach($query->result() as $r){
    $a .= "<tr class='cl'>";
    $a .= "<td>".$r->{$field}."</td>";
    $a .= "<td>".$r->{$field2}."</td>";

    if($hid_field!=0){
      $a .= "<td><input type='hidden' class='code_gen' value='".$r->{$hid_field}."' title='".$r->{$hid_field}."' /></td>";        
    }

    $a .= "</tr>";
  }
  $a .= "</table>";
  echo $a;
}

}