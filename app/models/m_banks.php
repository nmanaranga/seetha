<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_banks extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['m_banks'];
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 60px; cursor : pointer;");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;");
        $dt = array("data"=>"Sales Ref", "style"=>"width: 150px;");
        $region = array("data"=>"Main Region", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
	
        $this->table->set_heading($code, $des, $region, $dt, $action);//
        
        $this->db->select(array($this->tb_main_regeon.'.description AS main_region', $this->mtb.'.code', $this->mtb.'.description', 'name'));
	$this->db->join($this->tb_main_regeon, $this->tb_main_regeon.'.code = '.$this->mtb.'.region', 'INNER');
	$this->db->join($this->tb_sales_ref, $this->tb_sales_ref.'.code = '.$this->mtb.'.sales_ref', 'INNER');
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
	    
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 68px;");
            $dt = array("data"=>$this->useclass->limit_text($r->name, 20), "style"=>"text-align: left;  width: 158px;");
            $region = array("data"=>$this->useclass->limit_text($r->main_region, 20), "style"=>"text-align: left;  width: 158px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 68px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis, $region, $dt, $ed);//
        }
        
        return $this->table->generate();
    }
    
    public function save(){
        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
            unset($_POST['code_']);
            $this->db->insert($this->mtb, $_POST);
        }else{
            $this->db->where("code", $_POST['code_']);
            unset($_POST['code_']);
            $this->db->update($this->mtb, $_POST);
        }
	
	redirect(base_url()."?action=m_area");
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
    
    public function auto_com(){
	$rr = array();
	foreach($this->db->get($this->mtb)->result() as $r){
	    $rr[$r->code] = array($r->code, $r->bank_name);
	}
	
	echo json_encode($rr);
    }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='bank' id='bank'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option value='".$r->code."'>".$r->bank_name."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
}