<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_employee_category extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_employee_category'];
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
  return $a;
    }
    



    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $action);
        
        $this->db->select(array('action_date', 'description', 'code'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){

           
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_employee_category')){ $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 100px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 100px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis, $ed);
        }
        
        return $this->table->generate();
    }

       public function get_data_table(){
     echo $this->data_table();
   }

    
    public function save(){

        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
             $code=strtoupper($_POST['code']);
            
            $a=array(
                
                "code"=>$code,
                "description"=>$_POST['description']          
            );


            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_employee_category')){
                    unset($_POST['code_']);
                    $this->db->insert($this->mtb, $a);
                    echo $this->db->trans_commit();
    			}else{
                    $this->db->trans_commit();
                    echo "No permission to save records";
                }
            }else{             
                if($this->user_permissions->is_edit('m_employee_category')){
                    $this->db->where("code", $code);
                   
                    unset($_POST['code_']);
                    $this->db->update($this->mtb, $a);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }   
            }              
        }catch( Exception $e ){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
    public function check_code(){
    $code=$_POST['code'];  
	$this->db->where('code', $code);
	$this->db->limit(1);
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
    $code=$_POST['code'];    
	$this->db->where('code', $code);
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
            if($this->user_permissions->is_delete('m_employee_category')){
                //$delete_validation_status=$this->delete_validation();
                
                    $this->db->where('code', $_POST['code']);
                   $this->db->limit(1);
                	$this->db->delete($this->mtb);
                    echo $this->db->trans_commit();
                  
            }else{
                 echo "No permission to delete records";
            }
        } catch ( Exception $e ) { 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
        } 
    }

      
   
}