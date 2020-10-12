 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class chq_print_scheme extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_contact;
    private $tb_acc_trance;
    private $tb_sum;
    
    function __construct(){
    	parent::__construct();
    	
    	$this->sd = $this->session->all_userdata();
    	$this->load->database($this->sd['db'], true);
    	$this->load->model('user_permissions');
    	$this->mtb = $this->tables->tb['chq_print_scheme'];
	
    }
    
    public function base_details(){

    	$a['table_data'] = $this->data_table();
    	return $a;
    }
    

    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 60px;");
        $name = array("data"=>"Name", "style"=>"");
        //$phone = array("data"=>"T/P", "style"=>"width: 100px;");
        $action = array("data"=>"Action", "style"=>"width: 80px;");
        $this->table->set_heading($code, $name,  $action);

        $this->db->select(array('code', 'name', 'inactive'));
        $this->db->limit(10);
      	$query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
        	
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('chq_print_scheme')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            
            $code = array("data"=>$r->code, "value"=>"code");
            $name = array("data"=>$this->useclass->limit_text($r->name, 25), "style"=>" text-align: left;");
           
            //$tp = array("data"=>$this->useclass->limit_text($r->inactive, 15),"style"=>" text-align: center;");
            $action = array("data"=>$but, "style"=>" text-align: center;");
            
	    $this->table->add_row($code, $name, $action);
        }         
        return $this->table->generate();
    }
   
    
    public function save(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {   
            if(isset($_POST['inactive'])){
                $_POST['inactive']='1';
            }else{
                $_POST['inactive']='0';
            } 

            if(isset($_POST['iscfprint'])){
                $_POST['iscfprint']='1';
            }else{
                $_POST['iscfprint']='0';
            } 

    	    if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('chq_print_scheme')){
                   	unset($_POST['code_']);
            		$this->db->insert($this->mtb,$_POST);            		
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
    		}else{
                if($this->user_permissions->is_edit('chq_print_scheme')){
                    
                	$this->db->where("code", $_POST['code_']);
                    unset($_POST['code_']);
                    $this->db->update($this->mtb, $_POST);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }      
    		}            
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
   
       
    public function load(){
        
        $this->db->where('code', $_POST['code']);
    	$this->db->limit(1);
    	$a["data"] = $this->db->get("chq_print_scheme")->first_row();    	
    	echo json_encode($a);
    }

    public function check_code(){
        $sql="SELECT * FROM chq_print_scheme WHERE code ='".$_POST['code']."' limit 1 ";            
        $query=$this->db->query($sql);
        if($query->num_rows()>0){           
            $a['data']=1;
        }else{
            $a['data']=2;
        }       
        echo json_encode($a);
    }
    
    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_delete('chq_print_scheme')){
               
            	$this->db->where('code', $_POST['code']);
            	$this->db->limit(1);
            	$this->db->delete("chq_print_scheme");
                echo $this->db->trans_commit();             
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        }catch( Exception $e ){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }
    
       

}