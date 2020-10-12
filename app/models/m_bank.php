<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_bank extends CI_Model {
    
    private $sd;
    private $mtb;
    private $m_item;
    private $m_sub;
    
    function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');    
    $this->mtb = $this->tables->tb['m_bank'];

    }
    
    public function base_details(){
    $a['table_data'] = $this->data_table();
    return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 50px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Date/Time", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
    
        $this->table->set_heading($code, $des, $action);
        
        $this->db->select(array('action_date', 'description', 'code'));
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_bank')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $dt = array("data"=>$r->action_date, "style"=>"text-align: center;  width: 158px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
        
            $this->table->add_row($code, $dis, $ed);
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
        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
            if($this->user_permissions->is_add('m_bank')){
                unset($_POST['code_']);
                echo $this->db->insert($this->mtb, $_POST);
            }else{
                echo "No permission to save records";
            }
        }else{
            if($this->user_permissions->is_edit('m_bank')){
                $this->db->where("code", $_POST['code_']);
                unset($_POST['code_']);
                echo  $this->db->update($this->mtb, $_POST);
            }else{
                echo "No permission to edit records";
            }
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

    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'bank','m_bank','opt_credit_card_det','bank_id');
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
            if($this->user_permissions->is_delete('m_bank')){
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
        
        $s = "<select id='bank' name='bank'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }


    public function get_data_table(){
        echo $this->data_table();
    }

     public function auto_com(){
        $query =$this->db->query("SELECT `m_bank`.`code` AS bank_code,m_bank.`description` AS `desc`,m_bank_branch.`code` AS branch_code,m_bank_branch.`description` FROM `m_bank` 
        JOIN `m_bank_branch` ON `m_bank`.`code`=`m_bank_branch`.`bank` where `m_bank`.`code` like '%".$_GET['q']."%' OR m_bank.`description` like'%".$_GET['q']."%' ");    
        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->bank_code."|".$r->desc."|".$r->branch_code."|".$r->description;
            $abc .= "\n";
        }
        echo $abc;
    } 



        
    public function auto_com2(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like($this->mtb.'.description', $_GET['q']);
        $query = $this->db->select(array('code', $this->mtb.'.description'))
                    ->get($this->mtb);
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;
                $abc .= "\n";
            }
        echo $abc;
    }  

     public function auto_com22(){

        $this->db->where('is_bank_acc', "1");
        $this->db->like('code', $_GET['q']);
        //$this->db->or_like('description', $_GET['q']);
        $query = $this->db->select(array('code', 'description'))
                    ->get("m_account");
          
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;
                $abc .= "\n";
            }
        echo $abc;
    } 

    public function auto_com3(){
        $q=$_GET['q'];
        $query =$this->db->query("SELECT bank_id,month,rate,description FROM r_credit_card_rate JOIN m_bank  ON m_bank.`code`=r_credit_card_rate.`bank_id` where `r_credit_card_rate`.`bank_id` like '%$q%' OR m_bank.`description` like'%$q%' ");    
        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->bank_id."|".$r->description."|".$r->month."|".$r->rate;
            $abc .= "\n";
        }
        echo $abc;
    } 

    

    
}