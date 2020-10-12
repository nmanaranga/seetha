<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_sub_cat extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_main_cat;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['r_sub_category'];
	$this->tb_main_cat = $this->tables->tb['r_category'];
    }
    
    public function base_details(){
	$this->load->model('m_main_cat');
	
	$a['table_data'] = $this->data_table();
	$a['main_cat'] = $this->m_main_cat->select('name');
	
	return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Main Category", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Code", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Description", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $dt,$action);
     /*    
        $this->db->select(array($this->tb_main_cat.'.description AS main_cat', $this->mtb.'.code', $this->mtb.'.description'));
	$this->db->join($this->tb_main_cat, $this->tb_main_cat.'.code = '.$this->mtb.'.main_cat', 'INNER');
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $dt = array("data"=>$r->main_cat, "style"=>"text-align: center;  width: 158px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis, $ed);
        } */
        
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
            unset($_POST['code_']);
            $this->db->insert($this->mtb, $_POST);
        }else{
            $this->db->where("code", $_POST['code_']);
            unset($_POST['code_']);
            $this->db->update($this->mtb, $_POST);
        }
	
	redirect(base_url()."?action=m_sub_cat");
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
        
        $s = "<select name='sub_cat' id='sub_cat'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function sub_cat_list(){
	$this->db->select(array($this->tb_main_cat.'.code AS mcat', $this->mtb.'.code', $this->mtb.'.description'));
	$this->db->join($this->tb_main_cat, $this->tb_main_cat.'.code = '.$this->mtb.'.main_cat');
	$this->db->order_by($this->tb_main_cat.'.code');
	
	//echo json_encode($this->db->get($this->mtb)->result()); exit;
	
	$res = $tres = array(); $mcat = "";
	foreach($this->db->get($this->mtb)->result() as $r){
	    if($mcat != $r->mcat && $mcat != ""){
		$res[$mcat] = $tres;
		$tres = array();
	    }
	    $mcat = $r->mcat;
	    
	    $tres[] = array($r->code, $r->description);
	}
	$res[$mcat] = $tres;
	
	echo json_encode($res);
    }
}