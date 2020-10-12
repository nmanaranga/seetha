 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class chq_account_setup extends CI_Model {
    
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
    	$this->mtb = $this->tables->tb['chq_account_setup'];
	
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
        //$scheme = array("data"=>"Scheme Code", "style"=>"width: 100px;");
        $action = array("data"=>"Action", "style"=>"width: 80px;");
        $this->table->set_heading($code, $name, $action);

        $sql="SELECT c.id,c.name
              FROM chq_account_setup c             
              LIMIT 10";
    
      	$query = $this->db->query($sql);
        
        foreach($query->result() as $r){
        	
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->id."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('chq_account_setup')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->id."\")' title='Delete' />";}
            
            $code = array("data"=>$r->id, "value"=>"code");
            $name = array("data"=>$this->useclass->limit_text($r->name, 25), "style"=>" text-align: left;");
           //$scheme = array("data"=>$this->useclass->limit_text($r->scheme_code, 15),"style"=>" text-align: center;");
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
    	    if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('chq_account_setup')){
                    $a=array(
                        "id"=>$_POST['id'],
                        "name"=>$_POST['name'],
                        "code"=>$_POST['code'],
                        "scheme_code"=>$_POST['scheme_code'],
                        "stamp_1"=>$_POST['stamp_1'],
                        "stamp_2"=>$_POST['stamp_2']
                    );     
            		$this->db->insert($this->mtb,$a);            		
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
    		}else{
                if($this->user_permissions->is_edit('chq_account_setup')){                    
            		$a=array( 
                        "name"=>$_POST['name'],
                        "code"=>$_POST['code'],                       
                        "scheme_code"=>$_POST['scheme_code'],
                        "stamp_1"=>$_POST['stamp_1'],
                        "stamp_2"=>$_POST['stamp_2']
                    ); 
                    $this->db->where("id", $_POST['code_']);
                    $this->db->update($this->mtb, $a);
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
        $sql="SELECT c.id,c.name,c.code,scheme_code,stamp_1,stamp_2,ch.name as ch_name,m.description
              FROM chq_account_setup c
              JOIN m_account m on m.code=c.code
              left JOIN chq_print_scheme ch on ch.code = c.scheme_code
              WHERE c.id = '".$_POST['code']."'
              LIMIT 1  
        ";

    	$result = $this->db->query($sql)->result();

    	echo json_encode($result);
    }

    public function check_code(){
        $sql="SELECT * FROM chq_account_setup WHERE id ='".$_POST['code']."' limit 1 ";            
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
            if($this->user_permissions->is_delete('chq_account_setup')){
               	$this->db->where('id', $_POST['code']);
            	$this->db->limit(1);
            	$this->db->delete("chq_account_setup");
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