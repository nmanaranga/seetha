<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_groups extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
       parent::__construct();
       
       $this->sd = $this->session->all_userdata();
       $this->load->database($this->sd['db'], true);
       $this->load->model('user_permissions');

       $this->mtb = $this->tables->tb['r_groups'];
   }
   
   public function base_details(){
    $this->load->model('r_sales_category');
    $a['sales_category'] = $this->r_sales_category->select();
    $a['table_data'] = $this->data_table();
    $a['max_no'] = $this->get_next_no();
    return $a;
}

public function data_table(){
    $this->load->library('table');
    $this->load->library('useclass');
    
    $this->table->set_template($this->useclass->grid_style());
    
    $code = array("data"=>"Code", "style"=>"width: 50px;");
    $description = array("data"=>"Description", "style"=>"");
    $date1 = array("data"=>"From Date", "style"=>"width: 80px;");
    $date2 = array("data"=>"To Date", "style"=>"width: 80px;");
    $active=array("data"=>"In-active", "style"=>"width:60px;");
    $action = array("data"=>"Action", "style"=>"width: 60px;");
    
    $this->table->set_heading( $code,$description,$date1,$date2,$active,$action);
    
    $this->db->select(array('name', 'code', 'fdate', 'tdate', 'inactive'));
    $this->db->where('bc',$this->sd['branch']);

    $query = $this->db->get($this->mtb);
    
    
    foreach($query->result() as $r){
        $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
        if($this->user_permissions->is_delete('r_groups')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
        $code = array("data"=>$r->code, "value"=>"code", "style"=>"width: 50px;");
        $description = array("data"=>$this->useclass->limit_text($r->name, 25));
        $active= array("data"=>$r->inactive,"style"=>"width: 60px;");
        $fdate = array("data"=>$r->fdate,"style"=>"width: 80px;");
        $tdate = array("data"=>$r->tdate,"style"=>"width: 80px;");
        $action = array("data"=>$but, "style"=>"text-align: center;width: 60px;");
        $this->table->add_row($code, $description, $fdate, $tdate, $active, $action);
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

       if(!isset($_POST['inactive'])){$_POST['inactive']=0;}else{$_POST['inactive']=1;}

       $a = array(
           "cl"=>$this->sd['cl'],
           "bc"=>$this->sd['branch'],
           "code"=>$_POST['code'],
           "name"=>$_POST['name'],
           "fdate"=>$_POST['fdate'],
           "tdate"=>$_POST['tdate'],
           "category"=>$_POST['sales_category'],
           "officer"=>$_POST['officer'],
           "inactive"=>$_POST['inactive'],
           "oc"=>$this->sd['oc']
        	    //"bc"=>$this->sd['bc'],
       );

       if($_POST['code_'] == "0" || $_POST['code_'] == ""){
        if($this->user_permissions->is_add('r_groups')){
            unset($_POST['code_']);
            $this->db->insert($this->mtb, $a);
            echo $this->db->trans_commit();
        }else{
            $this->db->trans_commit();
            echo "No permission to save records";
        }
    }else{
        if($this->user_permissions->is_edit('r_groups')){
            $this->db->where("code", $_POST['code_']);
            unset($_POST['code_']);
            $this->db->update($this->mtb, $a);
            echo $this->db->trans_commit();
        }else{
            $this->db->trans_commit();
            echo "No permission to edit records";
        }
    }
    
}catch(Exception $e){ 
    $this->db->trans_rollback();
    echo "Operation fail please contact admin"; 
} 
}


public function get_data_table(){
    echo $this->data_table();
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
        if($this->user_permissions->is_delete('r_groups')){
           $this->db->where('code', $_POST['code']);
           $this->db->limit(1);
           $this->db->delete($this->mtb);
           echo $this->db->trans_commit();
       }else{
        $this->db->trans_commit();
        echo "No permission to delete records";
    }
}catch(Exception $e){ 
    $this->db->trans_rollback();
    echo "Operation fail please contact admin"; 
}
}

public function select(){
    $date=date("Y-m-d");
    $sql="SELECT * FROM r_groups WHERE `fdate` <= '$date' AND '$date' <= `tdate` AND inactive='0' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' ";
    $query=$this->db->query($sql);
    
    $s = "<select name='groups' id='groups'>";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
        $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."-".$r->name."</option>";
    }
    $s .= "</select>";
    
    return $s;
}

public function select_by_category(){

    $category_id = $_POST['category_id'];
    $date=date("Y-m-d");
    $sql="SELECT * FROM r_groups WHERE `fdate` <= '$date' AND '$date' <= `tdate` AND inactive='0' AND category = '$category_id'";
    $query=$this->db->query($sql);
    
    $s = "<select name='groups' id='groups'>";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
        $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."-".$r->name."</option>";
    }
    $s .= "</select>";
    
    echo $s;
}

public function get_next_no(){
    $field="code";
    $user=$this->sd['cl'];
    $branch=$this->sd['branch'];
    $sql="SELECT IFNULL( MAX( SUBSTRING(CODE, LENGTH(CONCAT(cl,bc ))+1)), 0)+1 AS num FROM `r_groups` where `cl` ='$user' and `bc` = '$branch'";
    $query=$this->db->query($sql);
    $field1=$query->first_row()->num;
    $setNumber=str_pad($field1,4,"0",STR_PAD_LEFT);
    $fullCode=$user.$branch.$setNumber;
    return $fullCode;
}

public function auto_com(){
    $date=date("Y-m-d");
    $sql="SELECT * FROM r_groups WHERE `fdate` <= '$date' AND '$date' <= `tdate` AND inactive='0' AND (code LIKE '%$_GET[q]%' OR name LIKE '%$_GET[q]%')";
    $query=$this->db->query($sql);
    $abc = "";
    foreach($query->result() as $r){
        $abc .= $r->code."|".$r->name;
        $abc .= "\n";
    }
    
    echo $abc;
}  

}