<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s_module extends CI_Model {
    
    private $sd;
    private $mtb;
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
	$this->mtb = $this->tables->tb['u_modules'];
    $this->load->model("utility");
    }
    
    public function base_details(){

	$a['table_data'] = $this->data_table();
    $a['max_no']=$this->utility->get_max_no2("u_modules","m_code");
	$a['def_mod']=$this->default_modules();
	return $a;
    }

    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
        $m_name = array("data"=>"Module Name", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(3)");
        $dt = array("data"=>"Date/Time");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $m_name, $des, $action);
        
        $this->db->select(array('action_date', 'm_description', 'm_code', 'module_name'));
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->m_code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('s_module')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->m_code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center;");
            $dt = array("data"=>$r->action_date, "style"=>"text-align: center; ");
            $dis = array("data"=>$this->useclass->limit_text($r->m_description, 50), "style"=>"text-align: left;");
            $m_name = array("data"=>$r->module_name, "style"=>"text-align: left;  ", "value"=>"m_name");
            $code = array("data"=>$r->m_code, "style"=>"text-align: left;  ", "value"=>"code");
	    
            $this->table->add_row($code, $m_name, $dis, $ed);
        }
        
        return $this->table->generate();
    }
    
    public function save(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile,$errLine ) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
        	$_POST['m_code']=strtoupper($_POST['m_code']);
            $x=array(3,7,8,10,11,12,13,14,15,16);
           
            $u_modules= array("m_code"      => $_POST['package'],
                            "module_id"     =>$_POST['m_code'],
                            "module_name"   =>$_POST['module_name'],
                            "is_active"     =>"1",
                            "main_mod"      =>$_POST['main_mod']
                            );

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('s_module')){
                     unset($_POST['code_']);
                    $this->db->insert($this->mtb, $_POST);

                    
                    if(in_array( $_POST['package'], $x)){
                        $this->db->insert("def_option_module", $u_modules);
                    }
                    
                    $this->utility->save_logger("SAVE",40,$_POST['m_code'],$this->mod);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{
                if( $this->user_permissions->is_edit('s_module')){
                    $code=$_POST['code_'];
                    $this->db->where("m_code", $_POST['code_']);
                    unset($_POST['code_']); 
                    $this->db->update($this->mtb, $_POST);
                    
                    $this->db->where("module_id", $code);
                    $this->db->update("def_option_module", $u_modules);
                    
                   
                    $this->utility->save_logger("UPDATE",40,$_POST['m_code'],$this->mod);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }
            }
            
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage();
            echo"Operation fail please contact admin"; 
        }   
    }
    
    public function check_code(){
	$this->db->where('m_code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
	$this->db->where('m_code', $_POST['code']);
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
            if($this->user_permissions->is_delete('s_module')){
            	$this->db->where('m_code', $_POST['code']);
            	$this->db->limit(1);
            	$this->db->delete($this->mtb);
                $this->utility->save_logger("DELETE",40,$_POST['code'],$this->mod);
                echo $this->db->trans_commit();
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }
    
   public function default_modules(){

       $sql="SELECT def_use_seettu,def_use_hp,def_use_service,def_use_cheque,def_use_giftV FROM `def_option_sales`";
       $query = $this->db->query($sql)->first_row();

        $s = "<select name='main_mod' id='main_mod'>";
        $s .= "<option value='0'>---</option>";
        
            if($query->def_use_seettu==1){
            $s .= "<option value='1'>Seettu</option>";
            }
            if($query->def_use_hp==1){
            $s .= "<option value='2'>HP</option>";
            }
            if($query->def_use_service==1){
            $s .= "<option value='3'>Service</option>";
            }
            if($query->def_use_cheque==1){
            $s .= "<option value='4'>Cheque</option>";
            }
            if($query->def_use_giftV==1){
            $s .= "<option value='5'>Gift Voucher</option>";
            }
            $s .= "<option value='6'>Common</option>";
            
        $s .= "</select>";
        
        return $s;
       
    }
}