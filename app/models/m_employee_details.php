<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_employee_details extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['m_employee'];
    
    }
    
    public function base_details(){
    $this->load->model('r_designation');
    $a['designation']=$this->r_designation->select();
    $a['table_data'] = $this->data_table();
    $a['category']=$this->category_details();
    
    return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 50px; ");
        $name = array("data"=>"Name", "style"=>"");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($code, $name, $action);
        
        $this->db->select(array('code', 'name'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
            
            $code = array("data"=>$r->code);
            $name = array("data"=>$this->useclass->limit_text($r->name, 25));
            $action = array("data"=>$but, "style"=>"text-align: center;");
        
            $this->table->add_row($code, $name, $action);
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

            $_POST['code']=strtoupper($_POST['code']);

            if(isset($_POST['inactive'])){$_POST['inactive']=1; }else{$_POST['inactive']=0; }

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                unset($_POST['code_']);
                $this->db->insert($this->mtb, $_POST);
            }else{
                $this->db->where("code", $_POST['code_']);
                unset($_POST['code_']);
                $this->db->update($this->mtb, $_POST);
            }
            echo $this->db->trans_commit();
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
    $this->db->where('code', $_POST['code']);
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
            $this->db->where('code', $_POST['code']);
            $this->db->limit(1);
            $this->db->delete($this->mtb);
            echo $this->db->trans_commit();
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
        $this->db->like('code', $_GET['q']);
        $this->db->or_like($this->mtb.'.name', $_GET['q']);
        $query = $this->db->select(array('code', $this->mtb.'.name'))->get($this->mtb);
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->name;
            
            // if(isset($_GET['f'])){ $abc .= "|".$r->rate; }
            // if(isset($_GET['c'])){ $abc .= "|".$r->tire_count; }
            
            $abc .= "\n";
            }
        
        echo $abc;
        }


        public function category_details(){
        $sql="SELECT `code`,`description` FROM m_employee_category";
        $query=$this->db->query($sql);

        $s = "<select name='category' id='category'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." | ".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
        }  

}