<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_gift_voucher extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_main_cat;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');	
	$this->mtb = $this->tables->tb['g_m_gift_voucher'];
	$this->tb_main_cat = $this->tables->tb['r_category'];
	
    }
    
    public function base_details(){
	$this->load->model('r_category');
	$a['table_data'] = $this->data_table();
	//$a['main_cat'] = $this->r_category->select();
	return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 75px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;width: 100px;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Price", "style"=>"width: 75px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $dt,$action);
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){            
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_gift_voucher')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $main_category = array("data"=>$r->code, "style"=>"text-align: center;  width: 75px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->price, "style"=>"text-align: left; width: 50px; ", "value"=>"code");
	    
            $this->table->add_row( $main_category,$dis,$code, $ed);
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
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
        	$data=array(
                "code"         =>strtoupper($_POST['code']),
                "description"  =>$_POST['description'],
                "cost"         =>$_POST['cost'],
                "price"        =>$_POST['price'],
                "supplier"     =>$_POST['supplier'],
                "oc"           =>$this->sd['oc']   
            );

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_gift_voucher')){            		
            		$this->db->insert("g_m_gift_voucher", $data);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{  
                if($this->user_permissions->is_edit('m_gift_voucher')){

            		$this->db->where("code", $_POST['code_']);
            		$this->db->update("g_m_gift_voucher", $data);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                } 
            }
        } catch ( Exception $e ) { 
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
        $sql="SELECT g.* ,s.`name` FROM g_m_gift_voucher g 
              JOIN m_supplier s ON s.`code` = g.`supplier` 
              WHERE g.code = '".$_POST['code']."'";
	
        echo json_encode($this->db->query($sql)->first_row());
	
    }
    
    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'Gift Voucher','r_sub_cat','m_item','category');
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
            if($this->user_permissions->is_delete('m_gift_voucher')){
                /*$delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){*/
                	$this->db->where('code', $_POST['code']);
                	$this->db->limit(1);
                	$this->db->delete($this->mtb);
                    echo $this->db->trans_commit();
                /*}else{
                    echo $delete_validation_status;
                    $this->db->trans_commit();
                }*/   
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
	   
    public function get_data_table(){
    echo $this->data_table();
    }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        $s = "<select name='category' id='category'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function sub_cat_list(){
	$this->db->select(array($this->tb_main_cat.'.code AS main_cat', $this->mtb.'.code', $this->mtb.'.description'));
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

     public function auto_com(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like($this->mtb.'.description', $_GET['q']);
        $query = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);

        if ($query->num_rows() > 0) {
            $this->db->like('code', $_GET['q']);
            $this->db->or_like($this->mtb.'.description', $_GET['q']);
            $query2 = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);
        }else{
            $query2 = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);
        }
        
        $abc = "";
            foreach($query2->result() as $r){
                $abc .= $r->code."|".$r->description."_".$r->code_gen;
            $abc .= "\n";
            }
        
        echo $abc;
        }  

}