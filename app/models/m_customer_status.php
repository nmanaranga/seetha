 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_customer_status extends CI_Model {
    
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
	
    $this->mtb = $this->tables->tb['m_customer_status'];
	
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
        $desc = array("data"=>"Description", "style"=>"");
        $color = array("data"=>"Color", "style"=>"");
        $action = array("data"=>"Action", "style"=>"width: 80px;");
        $this->table->set_heading($code, $desc,$color, $action);

        $this->db->select(array('code', 'description', 'color'));
        //$this->db->where('cl',$this->sd['cl']);
        //$this->db->where('bc',$this->sd['branch']);
       // $this->db->limit(10);
      	$query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
        	
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_customer_status')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            
            $code = array("data"=>$r->code, "value"=>"code");
            $desc = array("data"=>$this->useclass->limit_text($r->description, 25), "style"=>" text-align: left;");
            $color = array("data"=>$this->useclass->limit_text($r->color, 25));
           
            $action = array("data"=>$but, "style"=>" text-align: center;");
            
	    $this->table->add_row($code, $desc,$color, $action);
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
        	         

    		$a=array(
                "code"            =>$_POST['code'],
        		"description"     =>$_POST['txtDesc'],
        		"color"           =>$_POST['color'],
    		);

    	    if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_customer_status')){
            		$this->db->insert($this->mtb,$a);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }

    		}else{
                if($this->user_permissions->is_edit('m_customer_status')){
                	$this->db->where("code",$_POST['code']);
                    $this->db->update($this->mtb,$a);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }      
    		}
        } catch (Exception $e ) { 
            $this->db->trans_rollback();
            echo $e->getMessage()." - Operation fail please contact admin "; 
        } 
    }
    
   
    
   
  
    public function load(){

    $sql="SELECT 
            code,
            description,
            color
              
        FROM m_customer_status
        WHERE code='".$_POST['code']."' ";

         $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $a["data"] = $this->db->query($sql)->first_row();
        } else {
            $a["data"] = 2;
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
            if($this->user_permissions->is_delete('m_customer_status')){
               
                	$this->db->where('code', $_POST['code']);
                	$this->db->limit(1);
                	$this->db->delete($this->mtb);
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

   public function get_data_table(){
   	 echo $this->data_table();
   }

    public function check_code(){

    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    
    echo $this->db->get($this->mtb)->num_rows;
   
    
    }



 
}