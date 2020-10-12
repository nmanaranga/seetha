<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_agent extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['m_agent'];
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
        $name = array("data"=>"Name", "style"=>"");
        $address = array("data"=>"Address", "style"=>"width: 150px;");
        $phone = array("data"=>"Phones", "style"=>"width: 130px;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($code, $name, $address, $phone, $action);
        
        $this->db->select(array('name', 'code', 'address01', 'address02', 'address03', 'phone01', 'phone02', 'phone03'));
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
            
            $code = array("data"=>$r->code, "value"=>"code");
            $name = array("data"=>$this->useclass->limit_text($r->name, 25));
            $address = array("data"=>$this->useclass->limit_text($r->address01." ".$r->address02." ".$r->address03, 20));
            $phone = array("data"=>$this->useclass->limit_text($r->phone01.", ".$r->phone02.", ".$r->phone03, 20));
            $action = array("data"=>$but, "style"=>"text-align: center;");
	    
            $this->table->add_row($code, $name, $address, $phone, $action);
        }
        
        return $this->table->generate();
    }
    
    public function save(){
	
	if($_POST['phone01'] == "Mobile"){ $_POST['phone01'] = ""; }
	if($_POST['phone02'] == "Office"){ $_POST['phone02'] = ""; }
	if($_POST['phone03'] == "Fax"){ $_POST['phone03'] = ""; }
	
	if($_POST['address01'] == "No"){ $_POST['address01'] = ""; }
	if($_POST['address02'] == "Street"){ $_POST['address02'] = ""; }
	if($_POST['address03'] == "City"){ $_POST['address03'] = ""; }
	
        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
            unset($_POST['code_']);
            $this->db->insert($this->mtb, $_POST);
        }else{
            $this->db->where("code", $_POST['code_']);
            unset($_POST['code_']);
            $this->db->update($this->mtb, $_POST);
        }
	
	redirect(base_url()."?action=m_agent");
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
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->delete($this->mtb);
    }
    
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='agent' id='agent'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
}