<?php
class user_permissions extends CI_Model {
  private $session_data;
  private $table_name = "u_user_role_permition";

  function __construct(){
    parent::__construct();
    $this->load->library('session');
       // $this->load->library('useclass');

    $this->session_data = $this->session->all_userdata();
  }



  function delete(){
    $this->db->where("user_id", $this->session_data['up_oc']);
        //$this->db->where("ci", $this->session_data['ci']);
    $this->db->where("bc", $this->session_data['up_bc']);

    $this->db->delete($this->table_name);
  }

  function add_permission(){

    $sql = "SELECT
    module_id,
    module_name,
    SUM(is_view) AS is_view,
    SUM(is_add) AS is_add,
    SUM(is_edit) AS is_edit,
    SUM(is_delete) AS is_delete,
    SUM(is_approve) AS is_approve,
    SUM(is_print) AS is_print,
    SUM(is_re_print) AS is_re_print,
    SUM(is_back_date) AS is_back_date
    FROM u_user_role_detail
    WHERE role_id IN(SELECT
    role_id
    FROM u_add_user_role
    WHERE user_id = '".$this->session_data['up_oc']."'
    AND ((date_from >= '".date("Y-m-d")."'
    AND date_to >= '".date("Y-m-d")."')
    OR (date_from = '0000-00-00'
    AND date_to = '0000-00-00')))

    AND  u_user_role_detail.`module_id` in (select `m_code` from `u_modules` where `is_special`=0) 

    GROUP BY module_id";

        //echo $sql;exit;


    if ($this->session_data['up_oc']=='0' && $this->session_data['name']=='SuperAdmin') {
      $sql ="SELECT
      `m_code` AS module_id,
      `module_name`,
      '1' AS is_view,
      '1' AS is_add,
      '1' AS is_edit,
      '1' AS is_delete,
      '1' AS is_approve,
      '1' AS is_print,
      '1' AS is_re_print,
      '1' AS is_back_date,
      '0' AS is_special   
      FROM u_modules  GROUP BY m_code";
    }



    $this->delete();

    $query = $this->db->query($sql);

    foreach($query->result() as $r){
      $sql = "INSERT INTO `".$this->table_name."` SET
      `user_id` = '".$this->session_data['up_oc']."',
      `module_id` = '".$r->module_id."' ,
      `module_name` = '".$r->module_name."' ,
      `is_view` = '".$r->is_view."' ,
      `is_add` = '".$r->is_add."' ,
      `is_edit` = '".$r->is_edit."' ,
      `is_delete` = '".$r->is_delete."' ,
      `is_approve` = '".$r->is_approve."' ,
      `is_print` = '".$r->is_print."' ,
      `is_re_print` = '".$r->is_re_print."',
      `is_back_date` = '".$r->is_back_date."',
      `bc` = '".$this->session_data['up_bc']."'            
      ";

      $this->db->query($sql);
    }
  }

  public function get_permission()
  {
    if($_POST['is_edit']>0)
    {    
      $sql="SELECT
      `is_edit`
      FROM
      `u_user_role_permition`
      WHERE `module_id`='".$_POST['module_id']."' AND `user_id`='".$this->session_data['up_oc']."' 
      AND bc='".$this->session_data['bc']."'   
      GROUP BY `module_id`";

      $qry=$this->db->query($sql);
      $r=$qry->first_row();

      if($_POST['is_edit']>0)
      {
       if($r->is_edit>0)
       {    
         echo '1';
       }
       else
       {
         echo '2';
       }    
     } 
   }
   else
   {
    echo '0';
  }
}         

public function get_delete_permission()
{
  $sql="SELECT
  `is_delete`
  FROM
  `u_user_role_permition`
  WHERE `module_id`='".$_POST['module_id']."' AND `user_id`='".$this->session_data['up_oc']."' 
  AND bc='".$this->session_data['up_bc']."'   
  GROUP BY `module_id`";

        //echo $sql;exit;

  $qry=$this->db->query($sql);
  $r=$qry->first_row();

  if($r->is_delete>0)
  {
    echo '1';
  }
  else
  {
    echo '0';
  }    
}         


function details(){
  $sql = "SELECT
  module_id,
  is_view,
  is_add,
  is_edit,
  is_delete,
  is_print,
  is_re_print
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'
  AND `ci` = '".$this->session_data['ci']."'
  AND `bc` = '".$this->session_data['bc']."' ";

  $query = $this->db->query($sql);

  return $query->result();
}

function is_view($mod){
  $sql = "SELECT
  is_view
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'       
  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '".$mod."' LIMIT 1 ";
       // echo $sql; 
  $query = $this->db->query($sql);
  if($query->num_rows){
    return $query->first_row()->is_view;
  }else{
    return 0;
  }
       // return 1;
}

function is_view_module($mod){
  $sql = "SELECT
  is_view
  FROM `sys_company_modules_det`
  WHERE `com_id` = '".$this->session_data['ci']."'
  AND `ci` = '".$this->session_data['ci']."'
  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_id` = '".$mod."' LIMIT 1 ";
        //echo $sql;exit;
  $query = $this->db->query($sql);

  return $query->first_row()->is_view;
        //return 1;
}

function is_add($mod){

  $db=$this->load->database('seetha', true);
  $db->select('is_add');
  $db->where('user_id', $this->session_data['up_oc']);
  $db->where('bc',$this->session_data['up_bc']);
  $db->where('module_name',$mod);
  $query = $db->get('u_user_role_permition');

  return $query->first_row()->is_add;

}

function is_edit($mod){

  $sql = "SELECT
  is_edit
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'
  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '$mod' LIMIT 1 ";

  $query = $this->db->query($sql);
  return $query->first_row()->is_edit;
}

function is_delete($mod){

  $sql = "SELECT
  is_delete
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'

  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '".$mod."' LIMIT 1 ";
  $query = $this->db->query($sql);

  return $query->first_row()->is_delete;

}

function is_approve($mod){

  $sql = "SELECT
  is_approve
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'

  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '".$mod."' LIMIT 1 ";
  $query = $this->db->query($sql);

  return $query->first_row()->is_delete;

}

function is_delete2(){

  $sql = "SELECT
  is_delete
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'

  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '".$_POST['module']."' LIMIT 1 ";
       //echo $sql;exit;
  $query = $this->db->query($sql);

  echo $query->first_row()->is_delete;
    //return $query->first_row()->is_delete;
     //  return 1;
}

function is_print($mod){
  $sql = "SELECT
  is_print
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'

  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '".$mod."' LIMIT 1 ";

        //echo $sql;exit;

  $query = $this->db->query($sql);

  return $query->first_row()->is_print;
      // return 1;
}

function is_re_print($mod){
  $sql = "SELECT
  is_re_print
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'
  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '".$mod."' LIMIT 1 ";

  $query = $this->db->query($sql);

  return $query->first_row()->is_re_print;
}

function is_back_date($mod){
  $sql = "SELECT
  is_back_date
  FROM `u_user_role_permition`
  WHERE `user_id` = '".$this->session_data['up_oc']."'
  AND `bc` = '".$this->session_data['up_bc']."'
  AND `module_name` = '".$mod."' LIMIT 1 ";

  $query = $this->db->query($sql);

  return $query->first_row()->is_back_date;
}
}
?>