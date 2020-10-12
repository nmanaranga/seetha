

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_account_trans extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_items'];
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
        $description = array("data"=>"Description", "style"=>"");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($code, $description, $action);
        
       /*  $this->db->select(array('code', 'name', 'address01', 'address02', 'address03', 'phone01', 'phone02', 'phone03'));
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
            
            $code = array("data"=>$r->code);
            $name = array("data"=>$this->useclass->limit_text($r->name, 25));
            $address = array("data"=>$this->useclass->limit_text($r->address01." ".$r->address02." ".$r->address03, 17));
            $phone = array("data"=>$this->useclass->limit_text($r->phone01.", ".$r->phone02.", ".$r->phone03, 17));
            $action = array("data"=>$but, "style"=>"text-align: center;");
        
            $this->table->add_row($code, $name, $address, $phone, $action);
        }
         */
        return $this->table->generate();
    }
    
    public function get_data_table(){
	echo $this->data_table();
    }
	
    public function save(){
	
   $p = $this->user_permissions->get_permission($this->mod, array('is_edit', 'is_add'));

        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
        if($p->is_add){
		unset($_POST['code_']);
        echo $this->db->insert($this->mtb, $_POST);
        }else{
		echo 2;
	    }
        }else{
	    if($p->is_edit){
		$this->db->where("code", $_POST['code_']);
		unset($_POST['code_']);
		echo $this->db->update($this->mtb, $_POST);
	    }else{
		echo 3;
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
    
public function delete(){
	$p = $this->user_permissions->get_permission($this->mod, array('is_delete'));
	
	if($p->is_delete){
	    $this->db->where('code', $_POST['code']);
	    $this->db->limit(1);
	    
	    echo $this->db->delete($this->mtb);
	}else{
	    echo 2;
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
}