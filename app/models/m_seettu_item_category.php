<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_seettu_item_category extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_area;
    
    function __construct(){
	parent::__construct();
	
	
    }
    
    public function base_details(){
	
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Area", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $dt, $action);
        
        $this->db->select(array($this->tb_area.'.description AS name', $this->mtb.'.description', $this->mtb.'.code'));
	$this->db->join($this->tb_area, $this->tb_area.'.code = '.$this->mtb.'.area', 'INNER');
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
	    
            $ed = array("data"=>$but, "style"=>"text-align: center; ");
            $dt = array("data"=>$this->useclass->limit_text($r->name, 20), "style"=>"text-align: left; ");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis, $dt, $ed);
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
	
	redirect(base_url()."?action=m_root");
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
    
    public function check_root($root){
	$this->db->select('code');
	$this->db->where('description', $root);
	$this->db->limit(1);
	$query = $this->db->get($this->mtb);
	
	if($query->num_rows){
	    return $query->first_row()->code;
	}else{
	    return false;
	}
    }
    
    public function select($name = "code"){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='root' id='root'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
	    if($name == "code"){
		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
	    }else{
		$s .= "<option value='".$r->code."'>".$r->description."</option>";
	    }
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function select_area_wise(){
	$this->db->order_by('area');
	
	$res = $tres = array(); $area = "";
	foreach($this->db->get($this->mtb)->result() as $r){
	    if($area != $r->area && $area != ""){
		$res[$area] = $tres;
		$tres = array();
	    }
	    $area = $r->area;
	    
	    $tres[] = array($r->code, $r->description);
	}
	$res[$area] = $tres;
	
	echo json_encode($res);
    }
}