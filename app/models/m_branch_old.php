<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_branch extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_branch'];
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
	$a['cluster']=$this->cluster();
	$a['accNos']=$this->accNo();
    //$a['stores']=$this->storeDetails();
	return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $cluster = array("data"=>"Cluster", "style"=>"width: 50px;");
        $name = array("data"=>"Name", "style"=>"");
        $bc = array("data"=>"Code", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($cluster, $bc,$name,$action);
        
        $this->db->select(array('name', 'cl', 'bc'));
        $query = $this->db->get($this->mtb);
       
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->bc."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_branch')){ $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->bc."\")' title='Delete' />";}
            
            $bc = array("data"=>$r->bc, "value"=>"code");
            $cluster = array("data"=>$r->cl, "value"=>"code");
            $name = array("data"=>$this->useclass->limit_text($r->name, 25));
            
            $action = array("data"=>$but, "style"=>"text-align: center;");
	    
            $this->table->add_row($cluster, $bc, $name, $action);
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
    
        	$_POST['bc']=strtoupper($_POST['bc']);

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_branch')){
                    unset($_POST['code_']);
                    unset($_POST['def_customer_des'],$_POST['acc_dtls'],$_POST['store_id'],$_POST['category_id'],$_POST['group_dtls'],$_POST['def_loan_customer_des']);
                    $this->db->insert($this->mtb, $_POST);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }    
            }else{
                if($this->user_permissions->is_edit('m_branch')){    
                    $this->db->where("bc", $_POST['code_']);
                    unset($_POST['code_']);
                    unset($_POST['def_customer_des'],$_POST['acc_dtls'],$_POST['store_id'],$_POST['category_id'],$_POST['group_dtls'],$_POST['def_loan_customer_des']);
                    $this->db->update($this->mtb, $_POST);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }    
            }
            
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->setMessage()."Operation fail please contact admin"; 
        }    
    }
    
    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }

    

    public function get_data_table(){
    echo $this->data_table();
    }


    
    public function load(){
  /*  	$this->db->where('bc', $_POST['bc']);
    	$this->db->limit(1);
    	echo json_encode($this->db->get($this->mtb)->first_row());*/
        $sql="SELECT  m_branch.cl,
                   m_branch.bc, 
                   m_branch.name,
                   m_branch.address,
                   m_branch.tp,
                   m_branch.fax,
                   m_branch.email,
                   m_branch.current_acc,
                   m_branch.is_store,
                   m_branch.cash_customer_limit,
                   m_branch.`def_cash_customer`,
                   m_branch.`def_sales_store`,
                   m_branch.`def_sales_category`,
                   m_branch.`def_sales_group`,
                   m_branch.`def_sales_store`,
                   m_branch.`def_sales_category`,
                   m_branch.`def_sales_group`,
                   m_branch.`def_loan_customer`,
                   a.name AS loan_customer_name,
                   m_customer.name AS customer_name,
                   m_stores.`description` AS stores_name,
                   r_sales_category.`description` AS category_name,
                   r_groups.`name` AS group_name,
                   m_account.`description` AS Acc_name
            FROM m_branch 
            LEFT JOIN m_customer ON m_branch.def_cash_customer= m_customer.`code`
            LEFT JOIN m_customer a ON m_branch.def_loan_customer= a.`code`
            LEFT JOIN m_stores ON m_branch.`def_sales_store`=m_stores.`code`
            LEFT JOIN r_sales_category ON m_branch.`def_sales_category`=r_sales_category.`code` 
            LEFT JOIN r_groups ON m_branch.`def_sales_group`=r_groups.`code`
            LEFT JOIN m_account ON m_branch.`current_acc`=m_account.`code`  
              WHERE m_branch.bc='".$_POST['bc']."'";
        $query = $this->db->query($sql)->result();
        
        echo json_encode($query);

    }


    
    public function delete(){

        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_delete('m_branch')){
            	$this->db->where('bc', $_POST['bc']);
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
        $s = "<select name='bc' id='bc'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc." | ".$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }


    public function cluster(){
        $query = $this->db->get("m_cluster");
        $s = "<select name='cl' id='cluster' style='width:100px;'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        return $s;

    }

    public function accNo(){
        $this->db->where("is_control_acc", '0');
        $query = $this->db->get("m_account");
        $s = "<select name='current_acc' id='acc' style='width:100px;'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." | ".$r->description."</option>";
        }
        $s .= "</select>";
        return $s;

    }



     public function auto_com(){
        $this->db->like('bc', $_GET['q']);
        $this->db->or_like($this->mtb.'.name', $_GET['q']);
        $query = $this->db->select(array('bc', $this->mtb.'.name'))->get($this->mtb);
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->bc."|".$r->name;
            $abc .= "\n";
            }
        
        echo $abc;
        } 

       
}