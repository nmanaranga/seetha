<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_area extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_main_regeon;
    private $tb_sales_ref;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
	$this->mtb = $this->tables->tb['r_area'];
	//$this->tb_main_regeon = $this->tables->tb['m_sub_regon'];
	//$this->tb_sales_ref = $this->tables->tb['m_sales_ref']; */
    }
    
    public function base_details(){
	//$this->load->model('m_sub_regon');
	//$this->load->model('m_sales_ref');
	
	$a['table_data'] = $this->data_table();
	//$a['sub_regon'] = $this->m_sub_regon->select();
	//$a['sales_ref'] = $this->m_sales_ref->select();
	
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
	
        $this->table->set_heading($code, $des, $action);//
       
      //  $this->db->select(array($this->tb_main_regeon.'.description AS main_region', $this->mtb.'.code', $this->mtb.'.description', 'name'));
	//	$this->db->join($this->tb_main_regeon, $this->tb_main_regeon.'.code = '.$this->mtb.'.region', 'INNER');
		//$this->db->join($this->tb_sales_ref, $this->tb_sales_ref.'.code = '.$this->mtb.'.sales_ref', 'INNER');
        
		$this->db->select(array('action_date', 'description', 'code'));
		$this->db->limit(10);
		$query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('r_area')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
	    
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 68px;");
            //$dt = array("data"=>$this->useclass->limit_text($r->name, 20), "style"=>"text-align: left;  width: 158px;");
          // $region = array("data"=>$this->useclass->limit_text($r->main_region, 20), "style"=>"text-align: left;  width: 158px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 68px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis,$ed);//
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
            $_POST['oc']=$this->sd['oc'];
            $_POST['code']=strtoupper($_POST['code']);
        	if(isset($_POST['code_'])){
                if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                    if($this->user_permissions->is_add('r_area')){
                        unset($_POST['code_']);
                        $this->db->insert($this->mtb, $_POST);
                        echo $this->db->trans_commit();
                    }else{
                        echo "No permission to save records";
                        $this->db->trans_commit();
                    }
                }else{
                    if($this->user_permissions->is_edit('r_area')){
                        $this->db->where("code", $_POST['code_']);
                        unset($_POST['code_']);
                        $this->db->update($this->mtb, $_POST);
                        echo $this->db->trans_commit();
                    }else{
                        echo "No permission to edit records";
                        $this->db->trans_commit();
                    }
                }
        	}  
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


    public function get_data_table(){
    echo $this->data_table();
    }
    
    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'area','r_area','m_customer','area');
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
            if($this->user_permissions->is_delete('r_area')){
                $delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){
                	$this->db->where('code', $_POST['code']);
                	$this->db->limit(1);
                	$this->db->delete($this->mtb);
                    echo $this->db->trans_commit();
                }else{
                    echo $delete_validation_status;
                    $this->db->trans_commit();
                }    
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
    public function select($type = 'code', $style = ''){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='area' id='area' style='".$style."'>";
        $s .= "<option value='0'>---</option>";
	
	    foreach($query->result() as $r){
		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."|".$r->description."</option>";
	   }
        $s .= "</select>";
        
        return $s;
    }
}