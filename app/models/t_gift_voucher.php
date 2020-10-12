<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_gift_voucher extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
	//$this->mtb = $this->tables->tb['m_item'];
    }
    
    public function base_details(){
    $a['max_no']= $this->get_max_no("t_gift_voucher","nno");
	$a['type'] = 'GIFT VOUCHER';
	return $a;
    }
    
    public function get_max_no($table_name,$field_name){
        if(isset($_POST['hid'])){
          if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
            $this->db->select_max($field_name);
            return $this->db->get($table_name)->first_row()->$field_name+1;
          }else{
            return $_POST['hid'];  
          }
        }else{
            $this->db->select_max($field_name);
            return $this->db->get($table_name)->first_row()->$field_name+1;
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
        $selected_radio = $_POST['issue'];

        $a=array(
            "nno"=>$_POST['no'],
            "ddate"=>$_POST['date'],
            "ref_no"=>$_POST['ref_no'],
            "type"=>$_POST['type'],
            "account_id"=>$_POST['from'],
            "description"=>$_POST['description'],
            "total_amount"=>$_POST['net'],
          );

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_gift_voucher')){
            $this->db->insert('t_gift_voucher', $a);
            echo $this->db->trans_commit();
          }else{
              echo "No permission to save records";
              $this->db->trans_commit();
          }  
        }
        else
        {
          if($this->user_permissions->is_edit('t_gift_voucher')){
            $this->db->where('nno',$_POST['hid']);
            $this->db->update('t_gift_voucher', $a);
            echo $this->db->trans_commit();
          }else{
              echo "No permission to edit records";
              $this->db->trans_commit();
          } 
        }
        
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo "Operation fail please contact admin"; 
    }
 }
    
    
    public function load(){

    $this->db->select(array(
      't_gift_voucher.nno' ,
      't_gift_voucher.ddate' ,
      't_gift_voucher.ref_no',
      't_gift_voucher.type',
      't_gift_voucher.account_id',
      't_gift_voucher.description' ,
      't_gift_voucher.total_amount',
      't_gift_voucher.is_cancel'    
    ));

    $this->db->from('t_gift_voucher');
    $this->db->where('t_gift_voucher.nno',$_POST['id']);
    $query=$this->db->get();

    if($query->num_rows()>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      echo json_encode("2");
    }
  }


  public function loadname(){
    $code = $_POST['code'];
    $type = $_POST['type'];

    if($type==1){
      $db="m_supplier";
    }else if($type==2){
      $db="m_customer";
    }else if($type==3){
      $db="m_supplier";
    }
   
    $sql="SELECT `name`
    FROM $db 
    WHERE (code = '$code')
    LIMIT 1 ";   

    $query=$this->db->query($sql);

    if($query->num_rows()>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      echo json_encode("2");
    }
  }

    public function checkdelete(){
    $code = $_POST['ids'];
   
    $sql="SELECT *
    FROM `t_gift_voucher` 
    WHERE (`t_gift_voucher`.`nno` = '$code')
    LIMIT 25 ";   

    $query=$this->db->query($sql);

    if($query->num_rows()>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      echo json_encode("2");
    }
  }

    public function deleteGiftVoucher(){

      $this->db->trans_begin();
      error_reporting(E_ALL); 
      function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
      }
      set_error_handler('exceptionThrower'); 
      try {  
        if($this->user_permissions->is_delete('t_gift_voucher')){
          $pID = $_POST['id'];

          $this->db->where('nno',$pID);
          $this->db->update('t_gift_voucher', array("is_cancel"=>1));
          echo $this->db->trans_commit();
        }else{
          echo "No permission to delete records";
          $this->db->trans_commit();
        }

    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo "Operation fail please contact admin"; 
    }
  
  }

}