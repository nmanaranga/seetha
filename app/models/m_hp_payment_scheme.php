<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_hp_payment_scheme extends CI_Model {
    
    private $sd;
    private $mtb;

    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
	$this->mtb = $this->tables->tb['m_hp_payment_scheme'];

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
            if($this->user_permissions->is_delete('r_category')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
	    
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

            $down_payment = ""; 

            if($_POST['down_pay_type'] == 1){

                $down_payment = $_POST['down_pay_val'];
            }
            else{

                $down_payment = $_POST['down_pay_pre'];
            }
        
           
            $scheme['code'] = $_POST['code'] = strtoupper($_POST['code']);
            $scheme['description'] = $_POST['description'];
            $scheme['interest_rate'] = $_POST['interest_rate']; 
            $scheme['interest_type'] = $_POST['interest_type'];
            $scheme['period'] = $_POST['period'];
            $scheme['payment_type'] = $_POST['payment_type'];
            $scheme['payment_gap'] = $_POST['payment_gap'];
            $scheme['document_charges'] = $_POST['document_charges'];
            $scheme['down_payment'] = $down_payment;
            $scheme['down_payment_type'] = $_POST['down_pay_type'];
            $scheme['loan_min_amount'] = $_POST['loan_min_amount'];
            $scheme['loan_max_amount'] = $_POST['loan_max_amount'];
            if(isset($_POST['is_editable']) && $_POST['is_editable'] == 1){
                $scheme['is_editable'] =  $_POST['is_editable'];
            }
            else{
                $scheme['is_editable'] =  0;
            }

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
               
                    unset($_POST['code_']);
                    $this->db->insert($this->mtb, $scheme);
                    echo $this->db->trans_commit();
               
            }else{


                
                    $this->db->where("code", $_POST['code_']);
                    unset($_POST['code_']);
                    $this->db->update($this->mtb, $scheme);
                    echo $this->db->trans_commit();
             
            }

        } catch ( Exception $e ) { 
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
	// $this->db->where('code', $_POST['code']);
	// $this->db->limit(1);
	
    $sql="SELECT * FROM m_hp_payment_scheme WHERE CODE = ".$_POST["code"]."";
    
    echo json_encode($this->db->query($sql)->first_row());
    
	// echo json_encode($this->db->get($this->mtb)->first_row());
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
          
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select id='main_category' name='main_category'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }


       public function auto_com(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like($this->mtb.'.description', $_GET['q']);
        $query = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);
        
        if ($query->num_rows() > 0) {
            $this->db->like('code', $_GET['q']);
            $this->db->or_like($this->mtb.'.description', $_GET['q']);
            $query2 = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);
        } else {
            $query2 = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);
        }

        $abc = "";
            foreach($query2->result() as $r){
                $abc .= $r->code."|".$r->description."_".$r->code_gen;
            $abc .= "\n";
            }
        
        echo $abc;
        }  


 
    
}