<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_department extends CI_Model {
    
    private $sd;
    private $mtb;
   
	
    function __construct(){
	parent::__construct();
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['r_department'];
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
        $des = array("data"=>"Description", "style"=>"cursor : pointer;width: 200px;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Privi.Rate", "style"=>"width: 100px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
        $this->table->set_heading($code, $des,$dt,$action);
        
        $this->db->select(array('code', 'description', 'pv_card_rate','action_date'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('r_department')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 100px;");
            
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left; width: 200px;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 100px; ", "value"=>"code");
			$pv = array("data"=>$r->pv_card_rate, "style"=>"text-align: left; width: 100px; ", "value"=>"code");
            $this->table->add_row($code, $dis,$pv, $ed);
        } 
        
        return $this->table->generate();
    }
    
    public function check_exist($root){
	$this->db->select('code');
	$this->db->where('description', $root);
	$this->db->or_where('code', $root);
	$this->db->limit(1);
	$query = $this->db->get($this->mtb);
	
	if($query->num_rows){
	    return $query->first_row()->code;
	}else{
	    return false;
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
            $_POST['oc']=$this->sd['oc'];
            $_POST['code'] = strtoupper($_POST['code']);
            $_POST['code_gen'] = strtoupper($_POST['code_gen']);

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('r_department')){
            		unset($_POST['code_']);
            		$insert_values=array(
            			"code"=>$_POST['code'],
            			"description"=>$_POST['description'],
            			"pv_card_rate"=>$_POST['pv_card_rate'],
                        "code_gen"=>$_POST['code_gen'],
            			"oc"=>$this->sd['oc'],
            			"action_date"=>date('Y-m-d')
            		);
            		$this->db->insert($this->mtb, $insert_values);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
    	  
            }else{
                if($this->user_permissions->is_edit('r_department')){
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

	public function get_data_table(){
    echo $this->data_table();
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

    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'Department','r_department','m_item','department');
        if ($check_cancellation != 1) {
          return $check_cancellation;
        }
        return $status;
    }
    
    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
       set_error_handler('exceptionThrower'); 
       try { 
            if($this->user_permissions->is_delete('r_department')){
                $delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){
                	$this->db->where('code', $_POST['code']);
                	$this->db->limit(1);
                	$this->db->delete($this->mtb);
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
    
    public function select(){
        $query = $this->db->get($this->mtb);
        $s = "<select name='department' id='department'>";
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
        $query = $this->db->select(array('code','code_gen' , $this->mtb.'.description'))
                    ->get($this->mtb);

        if ($query->num_rows() > 0) {
            $this->db->like('code', $_GET['q']);
            $this->db->or_like($this->mtb.'.description', $_GET['q']);
            $query2 = $this->db->select(array('code','code_gen' , $this->mtb.'.description'))->get($this->mtb);
        }else{
            $query2 = $this->db->select(array('code','code_gen' , $this->mtb.'.description'))->get($this->mtb);
        }            
        $abc = "";
            foreach($query2->result() as $r){
                $abc .= $r->code."|".$r->description."_".$r->code_gen;
            $abc .= "\n";
            }
        
        echo $abc;
        } 

        public function r_namal(){
            $data = array('name' => $_POST['name'], 
                    
                        'address' => $_POST['address']
                );
           echo  $this->db->insert('tbl_namal',$data);
        } 
}