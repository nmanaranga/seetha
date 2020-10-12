<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_bank_branch extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_bank;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
	$this->mtb = $this->tables->tb['m_bank_branch'];
    $this->tb_bank = $this->tables->tb['m_bank'];
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
	$a['bank']=$this->get_bank();
	return $a;
    }
    
    public function get_bank(){
        $query = $this->db->get('m_bank');
        
        $s = "<select name='bank' id='bank'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option value='".$r->code."'>".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

   
        public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $bank = array("data"=>"bank", "style"=>"width: 50px; cursor : pointer;", "onclick"=>"set_short(1)");
        $code = array("data"=>"code", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $des = array("data"=>"Description", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
    
        $this->table->set_heading($bank,$code, $des, $action);
        
        $this->db->select(array($this->mtb.'.bank', $this->mtb.'.code',$this->mtb.'.description'));
        //$this->db->join($this->tb_bank,$this->tb_bank.'.code='.$this->mtb.'.bank');
        $query = $this->db->get($this->mtb);

        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_bank_branch')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $des = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
            $bank = array("data"=>$r->bank, "style"=>"width: 50px; cursor : pointer;", "onclick"=>"");

            $this->table->add_row($bank,$code, $des, $ed);
        }
        
        return $this->table->generate();
    }
    
    public function bank_branch_list(){
	$this->db->order_by('code');
	
	$res = $tres = array(); $mcat = "";
	foreach($this->db->get($this->mtb)->result() as $r){
	    if($mcat != $r->code && $mcat != ""){
		$res[$mcat] = $tres;
		$tres = array();
	    }
	    $mcat = $r->code;
	    
	    $tres[] = array($r->Bank, $r->Description);
	}
	$res[$mcat] = $tres;
	
	echo json_encode($res);
    }
    
    public function save(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {    
            $_POST['code'] = strtoupper($_POST['code']);
            $_POST['branch_code']  = strtoupper($_POST['branch_code']);

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_bank_branch')){

                    $data=$arrayName = array(
                        'bank' =>$_POST['sbank'], 
                        'code'=>$_POST['branch_code'],
                        'branch_code'=>$_POST['code'],
                        'description'=>$_POST['description']
                    );
                    unset($_POST['code_']);
                    $this->db->insert($this->mtb, $data);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{
                if($this->user_permissions->is_edit('m_bank_branch')){
                    $data=$arrayName = array(
                        'bank' =>$_POST['sbank'],
                        'description'=>$_POST['description']
                    );
                    $this->db->where("code", $_POST['code_']);
                    unset($_POST['code_']);
                    $this->db->update($this->mtb, $data);
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
    
    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){

    $this->db->select(array($this->mtb.'.code', $this->mtb.'.description', $this->mtb.'.bank',$this->tb_bank.'.description as bank_des',$this->mtb.'.branch_code'));
	$this->db->where($this->mtb.'.code', $_POST['code']);
    $this->db->join($this->tb_bank,$this->tb_bank.'.code='.$this->mtb.'.bank');
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
            if($this->user_permissions->is_delete('m_bank_branch')){
            	$this->db->where('code', $_POST['code']);
            	$this->db->limit(1);
            	$this->db->delete($this->mtb);
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
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='bank_branch' id='bank_branch'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option value='".$r->code."'>".$r->bank_name."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function auto_com_bank(){        
    	$this->db->like("code", $_GET['q']);
    	$this->db->or_like("description", $_GET['q']);
    	
    	foreach($this->db->get("m_bank")->result() as $r){
    	    echo $r->code."|".$r->description."|".$r->code."\n";
    	}
    }
    
    public function auto_com_branch(){        
	$this->db->like("bank", $_GET['q']);
	$this->db->or_like("description", $_GET['q']);

	foreach($this->db->get($this->mtb)->result() as $r){
	    echo $r->Bank."|".$r->Description."|".$r->code."\n";
	}
    }
}



