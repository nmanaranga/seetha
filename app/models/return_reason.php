<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class return_reason extends CI_Model {

    private $sd;
    private $mtb;
    private $mod = '018';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
	$this->mtb = $this->tables->tb['r_return_reason'];
    }
    
    public function base_details(){
    	$a['table_data'] = $this->data_table();
        $a['max_no'] = $this->genarate_max();
    	return $a;
    }
    
    public function genarate_int($type){
        $sql="SELECT max(`max_no`) as max_no FROM (`r_return_reason`)  WHERE TYPE='".$type."' LIMIT 1";
        $query=$this->db->query($sql); 
        if($query->num_rows()>0){
            $result=$query->first_row()->max_no;   
        }else{
            $result=0;
        }
        $result=(int)$result+1;
        return $result;
    }


    public function genarate_max(){
        if(isset($_POST['type'])){
            $sql="SELECT LPAD((SELECT IFNULL(MAX(max_no),0)+1 FROM r_return_reason WHERE TYPE='".$_POST['type']."'),4,0) AS v";
            $code=$this->db->query($sql)->first_row()->v;
            if($_POST['type']==1){
                $new_code='PR'.$code;
            }else if($_POST['type']==2){
                $new_code='SR'.$code;
            }else if($_POST['type']==3){
                $new_code='CR'.$code;
            }else if($_POST['type']==4){
                $new_code='BC'.$code;
            }else if($_POST['type']==5){
                $new_code='SE'.$code;
            }else if($_POST['type']==6){
                $new_code='CE'.$code;
            }else if($_POST['type']==7){
                $new_code='CC'.$code;
            }else{
                $new_code=$code;
            }
            echo $new_code; 
        }else{
            $sql="SELECT LPAD((SELECT IFNULL(MAX(max_no),0)+1 FROM r_return_reason WHERE TYPE='1'),4,0) AS v";
            $code=$this->db->query($sql)->first_row()->v;
            $new_code='PR'.$code;
            return $new_code; 
        }
    }

    public function genarate_max_save($type){
        $sql="SELECT LPAD((SELECT IFNULL(MAX(max_no),0)+1 FROM r_return_reason WHERE TYPE='$type'),4,0) AS v";
        $code=$this->db->query($sql)->first_row()->v;
        if($type==1){
            $new_code='PR'.$code;
        }else if($type==2){
            $new_code='SR'.$code;
        }else if($type==3){
            $new_code='CR'.$code;
        }else if($type==4){
            $new_code='BC'.$code;
        }else if($type==5){
            $new_code='SE'.$code;
        }else if($type==6){
            $new_code='CE'.$code;
        }else if($type==7){
            $new_code='CC'.$code;
        }else{
            $new_code=$code;
        }
        return $new_code; 
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
        
        $this->db->select(array('action_date', 'description', 'code','type'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code.'-'.$r->type."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('return_reason')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code.'-'.$r->type."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $dt = array("data"=>$r->action_date, "style"=>"text-align: center;  width: 158px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis, $ed);
        }
        
        return $this->table->generate();
    }
    
    public function get_data_table(){
    echo $this->data_table();
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
           
            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
    	        if($this->user_permissions->is_add('return_reason')){
            		unset($_POST['code_']);
            		$arr=array(
            			"code"              =>$this->genarate_max_save($_POST['r_type']),
                        "max_no"            =>$this->genarate_int($_POST['r_type']),
                        "type"              =>$_POST['r_type'],
            			"description"       =>$_POST['description'],
            			"oc"                =>$this->sd['oc']
            		);
                    
            		$this->db->insert($this->mtb,$arr);
                    echo $this->db->trans_commit();
    	        }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{
    			if($this->user_permissions->is_edit('return_reason')){
                    $arr_update=array(
                        "code"              =>$_POST['code'],
                        "max_no"            =>$_POST['hid_max'],
                        //"type"              =>$_POST['r_type'],
                        "description"       =>$_POST['description'],
                        "oc"                =>$this->sd['oc']
                    );
        			$this->db->where("code", $_POST['code']);
        			$this->db->where("type", $_POST['r_type']);
        			$this->db->update($this->mtb, $arr_update);
                    echo $this->db->trans_commit();	
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }    
            }
            
        }catch( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
    public function check_code(){
        $this->db->where('type', $_POST['type']);
    	$this->db->where('code', $_POST['code']);
    	$this->db->limit(1);
    	
    	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
        $this->db->where('type', $_POST['type']);
    	$this->db->where('code', $_POST['code']);
    	$this->db->limit(1);
    	
    	echo json_encode($this->db->get($this->mtb)->first_row());
    }
    

    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'Return reason','return_reason','t_pur_ret_det','reason');
        if ($check_cancellation != 1) {
          return $check_cancellation;
        }
        $check_cancellation2 = $this->utility->check_account_trans($codes,'Return reason','return_reason','t_sales_return_det','reason');
        if ($check_cancellation2 != 1) {
          return $check_cancellation2;
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
            if($this->user_permissions->is_delete('return_reason')){
                $delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){
                    $this->db->where('type', $_POST['type']);
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
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='brand' id='brand'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

     // public function auto_com(){
     //    $this->db->like('code', $_GET['q']);
     //    $this->db->or_like($this->mtb.'.description', $_GET['q']);
     //    $query = $this->db->select(array('code', $this->mtb.'.description'))->get($this->mtb);
     //    $abc = "";
     //        foreach($query->result() as $r){
     //            $abc .= $r->code."|".$r->description;
     //        $abc .= "\n";
     //        }
        
     //    echo $abc;
     //    }  


    public function auto_com2(){
        $q=$_GET['q'];
        $sql="SELECT code,description FROM r_return_reason WHERE code LIKE '%$q%' OR description LIKE '%$q%' AND(sales_return='1')";
        $query=$this->db->query($sql);
        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->code."|".ucwords(strtolower($r->description));
            $abc .= "\n";
        }
        echo $abc;
    }

    public function auto_com3(){
        $q=$_GET['q'];
        $sql="SELECT code,description FROM r_return_reason WHERE code LIKE '%$q%' OR description LIKE '%$q%' AND(purchase_return='1')";
        $query=$this->db->query($sql);
        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->code."|".ucwords(strtolower($r->description));
            $abc .= "\n";
        }
        echo $abc;
    } 


  
}