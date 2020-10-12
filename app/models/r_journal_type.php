<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_journal_type extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->sum = $this->tables->tb['m_journal_type_sum'];
	$this->det = $this->tables->tb['m_journal_type_det'];
	$this->account = $this->tables->tb['m_account'];
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
        
       
        $code = array("data"=>"Code", "style"=>"width: 50px;");
        $description = array("data"=>"Description", "style"=>"200px;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($code, $description, $action);
        
        $this->db->select(array('code', 'description'));
        $query = $this->db->get($this->sum);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('r_journal_type')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            
            $code = array("data"=>$r->code);
            $code = array("data"=>$this->useclass->limit_text($r->code, 17));
            $des = array("data"=>$this->useclass->limit_text($r->description, 17));
            $action = array("data"=>$but, "style"=>"text-align: center;");
        
            $this->table->add_row($code, $des, $action);
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
        	$a = array();
        	for($x = 0; $x<25; $x++){
        	    if($_POST['h_'.$x] != "" && $_POST['h_'.$x] != '0'){
            		$a[] = array(
            		    "code"=>$_POST['code'],
            		    "account_code"=>$_POST['h_'.$x],
            		    "dr"=>$_POST['1_'.$x],
            		    "cr"=>$_POST['2_'.$x],
            		    "note"=>$_POST['3_'.$x]
            		);
        	    }      	    
                unset($_POST['h_'.$x]); 
                unset($_POST['1_'.$x]);
                unset($_POST['2_'.$x]);
                unset($_POST['3_'.$x]);
        	}

            $_POST['code'] = strtoupper($_POST['code']);
            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('r_journal_type')){
                    unset($_POST['code_']);
                    $this->db->insert($this->sum, $_POST);
                    if(count($a))$this->db->insert_batch($this->det, $a);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{     
                if($this->user_permissions->is_edit('r_journal_type')){
        	        $this->set_delete();
                    $this->db->where("code", $_POST['code_']);
                    unset($_POST['code_']);
                    $this->db->update($this->sum, $_POST);
                    if(count($a))$this->db->insert_batch($this->det, $a);
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


     
    private function set_delete()
    {
        $this->db->where('code', $_POST['code_']);
	    $this->db->delete($this->det);
    }

    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->sum)->num_rows;
    }
    
    public function load(){
        
        $this->db->select(array($this->sum.'.code',$this->sum.'.description','account','payble_type',$this->account.'.description AS payable_acc'));
        $this->db->where($this->sum.'.code',$_POST['code']);
        $this->db->join ($this->account,$this->account.".code=".$this->sum.".account","INNER");
	    $this->db->limit(1);
        
        $a['sum']=$this->db->get($this->sum)->first_row();
        
        $this->db->select(array($this->det.'.account_code',$this->det.'.note','dr','cr',$this->account.'.description AS acc_des'));
        $this->db->where($this->det.'.code',$_POST['code']);
        $this->db->join ($this->account,$this->account.".code=".$this->det.".account_code","INNER");
        
        $a['det']=$this->db->get($this->det)->result();
        
        
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
            if($this->user_permissions->is_delete('r_journal_type')){
                $this->db->where('code', $_POST['code']);
                $this->db->limit(1);        
                $this->db->delete($this->det);

                $this->db->where('code', $_POST['code']);
                $this->db->limit(1);        
                $this->db->delete($this->sum);
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
        
        $s = "<select name='sales_ref' id='sales_ref'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function auto_com(){

    $sql="SELECT `code`, `m_journal_type_sum`.`description`
              FROM (`m_journal_type_sum`)
              WHERE `payble_type` =  '2' AND  (`code`  LIKE '%".$_GET['q']."%'
              OR  `m_journal_type_sum`.`description`  LIKE '%".$_GET['q']."%')";
        
    $query=$this->db->query($sql);

        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;  
            $abc .= "\n";
            }
        
        echo $abc;
        } 
        
    public function auto_com_payable(){

        $sql="SELECT `code`, `m_journal_type_sum`.`description`
              FROM (`m_journal_type_sum`)
              WHERE `payble_type` =  '1' AND  (`code`  LIKE '%".$_GET['q']."%'
              OR  `m_journal_type_sum`.`description`  LIKE '%".$_GET['q']."%')";
        
        $query=$this->db->query($sql);
        
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;  
            $abc .= "\n";
            }
        
        echo $abc;
        } 
        
        
    public function auto_com_receivable(){

        $sql="SELECT `code`, `m_journal_type_sum`.`description`
              FROM (`m_journal_type_sum`)
              WHERE `payble_type` =  '3' AND  (`code`  LIKE '%".$_GET['q']."%'
              OR  `m_journal_type_sum`.`description`  LIKE '%".$_GET['q']."%')";
        
        $query=$this->db->query($sql);
        
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;  
            $abc .= "\n";
            }
        
        echo $abc;
        } 
        
}