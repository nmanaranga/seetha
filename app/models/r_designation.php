<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_designation extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['r_designation'];
    }
    
    public function base_details(){
    $a['table_data'] = $this->data_table();
    
    return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 50px;");
        $description = array("data"=>"Description", "style"=>"width: 170px;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($code, $description, $action);
        
        $this->db->select(array('code', 'description'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
            
            $code = array("data"=>$r->code);
            $description = array("data"=>$this->useclass->limit_text($r->description, 25));
            $action = array("data"=>$but, "style"=>"text-align: center;");
        
            $this->table->add_row($code, $description, $action);
        }
         
        return $this->table->generate();
    }
    
    public function get_data_table(){
    echo $this->data_table();
    }
    
    public function save(){
   /* 
    $p = $this->user_permissions->get_permission($this->mod, array('is_edit', 'is_add'));

         if($_POST['code_'] == "0" || $_POST['code_'] == ""){
         if($p->is_add){
         unset($_POST['code_']);
         echo $this->db->insert($this->mtb, $_POST);
         }else{
         echo 2;
         }
         }else{
         if($p->is_edit){
         $this->db->where("code", $_POST['code_']);
         unset($_POST['code_']);
         echo $this->db->update($this->mtb, $_POST);
         }else{
         echo 3;
         }
         }   
	*/
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {     
             $_POST['code']=strtoupper($_POST['code']);
             if($_POST['code_'] == "0" || $_POST['code_'] == ""){

                 unset($_POST['code_']);
                 $this->db->insert($this->mtb, $_POST);
                 }else{
                 $this->db->where("code", $_POST['code_']);
                 unset($_POST['code_']); 
                 $this->db->update($this->mtb, $_POST);
                 } 
                 echo $this->db->trans_commit();
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
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    
    echo json_encode($this->db->get($this->mtb)->first_row());
    }
    
    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            $this->db->where('code', $_POST['code']);
            $this->db->limit(1);
            $this->db->delete($this->mtb);
            echo $this->db->trans_commit();
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='designation' id='designation'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." | ".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
}