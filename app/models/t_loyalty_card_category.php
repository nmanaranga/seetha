<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_loyalty_card_category extends CI_Model{

  private $sd;
  private $mtb;

  private $mod = '003';

  function __construct(){
   parent::__construct();

   $this->sd = $this->session->all_userdata();
   $this->load->database($this->sd['db'], true);
   $this->load->model('user_permissions');
 }

 public function base_details(){
  $a['table_data'] = $this->data_table();
  $this->load->model("utility");
  $a['max_no'] = $this->utility->get_max_no("t_loyalty_card_category", "code");
  return $a;
}

public function data_table(){
  $this->load->library('table');
  $this->load->library('useclass');

  $this->table->set_template($this->useclass->grid_style());

  $code = array("data"=>"Code", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
  $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
  $dt = array("data"=>"Date/Time", "style"=>"width: 150px;");
  $action = array("data"=>"Action", "style"=>"width: 100px;");
  
  $this->table->set_heading($code, $des, $action);

  $this->db->select(array( 'des', 'code'));
  $this->db->limit(10);
  $query = $this->db->get('t_loyalty_card_category');

  foreach($query->result() as $r){
    $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
    if($this->user_permissions->is_delete('return_reason')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
    $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
    $dis = array("data"=>$this->useclass->limit_text($r->des, 50), "style"=>"text-align: left;");
    $code = array("data"=>$r->code, "style"=>"text-align: left; width: 108px; ", "value"=>"code");

    $this->table->add_row($code, $dis, $ed);
  }

  return $this->table->generate();
}

public function get_data_table(){
  echo $this->data_table();
}

public function save(){ 

  $data= array(
    'cl'              =>$this->sd['cl']  ,
    'bc'              =>$this->sd['branch'] ,
    'code'            =>$_POST['code']  ,
    'des'             =>$_POST['description']  ,
    'card_cat'        =>$_POST['card_cat']  ,
    'earn_rs'         =>$_POST['earn_rs']  ,
    'earn_point'      =>$_POST['earn_point']  ,
    'red_point'       =>$_POST['red_point']  ,
    'red_rs'          =>$_POST['red_rs']  ,
    'update_level'    =>$_POST['upd_point'],
    'oc'              =>$this->sd['oc']  
  );
  if($_POST['code_'] == "0" || $_POST['code_'] == ""){
    if($this->user_permissions->is_add('t_loyalty_card_category')){
      echo $this->db->insert('t_loyalty_card_category', $data);
    }else{
      echo "No permission to save records";
    } 
  }else{
    if($this->user_permissions->is_edit('t_privilege_card')){
      $this->db->where("code", $_POST['code_']);
      echo $this->db->update('t_loyalty_card_category', $data);
    }else{
      echo "No permission to edit records";
    } 
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
    if($this->user_permissions->is_delete('t_loyalty_card_category')){
      $delete_validation_status=1;/*$this->delete_validation();*/
      if($delete_validation_status==1){
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);
        $this->db->delete('t_loyalty_card_category');
        echo $this->db->trans_commit();
      }else{
        echo $delete_validation_status;
        $this->db->trans_commit();
      }    
    }else{
      echo "No permission to delete records";
      $this->db->trans_commit();
    }
  } catch ( Exception $e ) { 
    $this->db->trans_rollback();
    echo "Operation fail please contact admin"; 
  } 
}

public function load(){
  $this->db->select('cl,bc,t_loyalty_card_category.code,des,r_return_reason.description,card_cat,earn_rs,earn_point,red_point,red_rs,update_level');
  $this->db->join('r_return_reason','r_return_reason.code=t_loyalty_card_category.card_cat');
  $this->db->where('t_loyalty_card_category.code', $_POST['code']);
  $this->db->limit(1);

  echo json_encode($this->db->get('t_loyalty_card_category')->first_row());
}
}