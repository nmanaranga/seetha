<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_main_cat extends CI_Model {
    
    private $sd;
    private $mtb;
    private $m_item;
    private $m_sub;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['r_category'];
	$this->m_item = $this->tables->tb['m_items'];
	$this->m_sub = $this->tables->tb['r_sub_category'];
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Date/Time", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $action);
        
        $this->db->select(array('action_date', 'description', 'code'));
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
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
            unset($_POST['code_']);
            $this->db->insert($this->mtb, $_POST);
        }else{
            $this->db->where("code", $_POST['code_']);
            unset($_POST['code_']);
            $this->db->update($this->mtb, $_POST);
        }
	
	redirect(base_url()."?action=m_main_cat");
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
    
//    public function select($type = 'code'){
//        $query = $this->db->get($this->mtb);
//        
//        $s = "<select name='main_cat' id='main_cat'>";
//        $s .= "<option value='0'>---</option>";
//        foreach($query->result() as $r){
//            if($type == "code"){
//		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->main_cat."</option>";
//	    }elseif($type == "name"){
//		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->description."</option>";
//	    }
//        }
//        $s .= "</select>";
//        
//        return $s;
//    }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='main_cat' id='main_cat'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->code."' value='".$r->code."'>".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function load_main_cat(){
	
	$sql = "SELECT distinct `".$this->mtb."`.description,`".$this->mtb."`.code FROM `".$this->mtb."`
	JOIN `".$this->m_item."` ON `".$this->m_item."`.main_cat=`".$this->mtb."`.code
	WHERE m_items.department='".$_POST['dep']."' ";
	
	
	//$sql = "SELECT distinct main_cat FROM `".$this->m_item."` WHERE department='".$_POST['dep']."' ";
        $query = $this->db->query($sql);
	$result = $query->result();
	echo "<select id='main_cat'>";
        echo "<option value='0' id='root_0'>---</option>";
        $x=1;
	foreach($result as $r){
	  echo "<option value='".$r->code."' id='root_".$x++."' title='".$r->description."'>".$r->description." </option>";
	}
        echo "</select>";
       
    }
    
    
    public function load_sub_cat(){
	
	
	$sql = "SELECT distinct m_sub_category.description,m_sub_category.code FROM `".$this->m_sub."`
	JOIN m_items ON m_items.main_cat=m_sub_category.main_cat
	WHERE m_items.main_cat='".$_POST['cat']."' ";
        
        $query = $this->db->query($sql);
        $result = $query->result();
       
        echo "<select id='main_cat'>";
        echo "<option value='0' id='root_0'>---</option>";
        $x=1;
	foreach($result as $r){
	    echo "<option value='".$r->code."' id='root_".$x++."' title='".$r->description."'>".$r->description." - ".$r->code."</option>";
	}
	echo "</select>";
    }
    
}