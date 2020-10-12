<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_defult_account extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	
	$this->mtb = $this->tables->tb['m_accounts'];
	
    }
    
    public function base_details(){
	
	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    public function data_table(){
	$std = new stdClass;
	foreach($this->db->get($this->tables->tb['defult_account'])->first_row() as $key=>$r){
	    $std->{$key} = $r;
	    $std->{$key."_des"} = $this->get_des($r);
	}
	
	return $std;
    }
    
    private function get_des($code){
	$this->load->database("account", true);
	
	$this->db->select('description');
	$this->db->where('code', $code);
	$this->db->limit(1);
	
	$res = $this->db->get($this->tables->tb['m_accounts']);
	if($res->num_rows){
	    return $res->first_row()->description;
	}else{
	    return "";
	}
    }
    
       

    public function auto_com(){
	$acc = $this->load->database("account", true);
	
	$acc->like("description", $_GET['q']);
	$acc->or_like("code", $_GET['q']);
	$acc->limit(25);
	
	foreach($acc->get($this->mtb)->result() as $r){
	    echo $r->code."|".$r->description."\n";
	}
    }
    
    public function auto_com_branch(){
	$acc = $this->load->database("account", true);
	
    $acc->where('is_Bank_acc', '1');
	$acc->limit(25);
	
	foreach($acc->get($this->mtb)->result() as $r){
	    echo $r->code."|".$r->description."|".$r->code."\n";
	}
    }
    
    public function save(){
	$this->db->limit(1);
	$this->db->update($this->tables->tb['defult_account'], $_POST);
	
	redirect(base_url()."?action=m_defult_account");
    }
}