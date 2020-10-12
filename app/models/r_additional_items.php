<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class R_additional_items extends CI_Model {


    private $sd;
    private $mtb;

    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
	$this->mtb = $this->tables->tb['r_additional_item'];
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
	return $a;
    }
 
 
 public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 70px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Rate", "style"=>"width: 68px;");
        $action = array("data"=>"Action", "style"=>"width: 70px;");
	
        $this->table->set_heading($code, $des, $dt, $action);
        

        $this->db->select(array('code', 'description', 'rate'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('r_additional_items')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 75px;");
            $dt = array("data"=>$r->description, "style"=>"text-align: center;  ");
            $dis = array("data"=>$r->rate, "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 75px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dt, $dis,$ed);
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

            if(!isset($_POST['is_add'])){$_POST['is_add']=0;}else{$_POST['is_add']=1;}
            if(!isset($_POST['is_reverse'])){$_POST['is_reverse']=0;}else{$_POST['is_reverse']=1;}

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('r_additional_items')){
            		$arr=array(
            			"code"=>strtoupper($_POST['code']),
            			"description"=>$_POST['description'],
            			"rate"=>$_POST['rate'],
                        "is_add"=>$_POST['is_add'],
            			"oc"=>$_POST['oc'],
            			"action_date"=>date("Y-m-d"),
                        "account"=>$_POST['account_id'],
                        "type"=>$_POST['type'],
                        "is_reverse"=>$_POST['is_reverse']
            		);
            		$this->db->insert($this->mtb,$arr);
                    echo $this->db->trans_commit();
    	        }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{

                if($this->user_permissions->is_edit('r_additional_items')){
                    $arr=array(
                        "description"=>$_POST['description'],
                        "rate"=>$_POST['rate'],
                        "is_add"=>$_POST['is_add'],
                        "oc"=>$_POST['oc'],
                        "action_date"=>date("Y-m-d"),
                        "account"=>$_POST['account_id'],
                        "type"=>$_POST['type'],
                        "is_reverse"=>$_POST['is_reverse']
                    );
        		
        			$this->db->where("code", $_POST['code_']);
        			unset($_POST['code_']);
        			$this->db->update($this->mtb, $arr);
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
    $this->db->select(array('r_additional_item.is_reverse','r_additional_item.type','r_additional_item.code','r_additional_item.description','r_additional_item.rate','r_additional_item.is_add','r_additional_item.account','m_account.description as acc_des'));    
    $this->db->from($this->mtb);
    $this->db->join('m_account','r_additional_item.account=m_account.code');
	$this->db->where('r_additional_item.code', $_POST['code']);
	$this->db->limit(1);
	
	echo json_encode($this->db->get()->first_row());
    }

    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'additional item','r_additional_items','t_cash_sales_additional_item','type');
        if ($check_cancellation != 1) {
          return $check_cancellation;
        }
        $check_cancellation2 = $this->utility->check_account_trans($codes,'additional item','r_additional_items','t_credit_sales_additional_item','type');
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
        if($this->user_permissions->is_delete('r_additional_items')){
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
    
    public function select(){
        $query = $this->db->get($this->mtb);
        $s = "<select name='category' id='category'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>"; 
        return $s;
    }

    public function item_list_all(){
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}  

        if(isset($_POST['type'])){
            if($_POST['type']=="1"){
                 $sql = "SELECT * FROM r_additional_item  WHERE type='1' AND description LIKE '$_POST[search]%'";
            }
        }else{
            $sql = "SELECT * FROM r_additional_item  WHERE type='2' AND description LIKE '$_POST[search]%'";
        }    
         $query = $this->db->query($sql);
        
        $a = "<table id='item_list2' style='width : 100%' >";
        
       $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Code</th>";
            $a .= "<th class='tb_head_th'>Item Name</th>";
            $a .= "<th class='tb_head_th'>Rate</th>";
            $a .= "<th class='tb_head_th'>Is Add</th>";
            
         
        $a .= "</thead></tr>";
            $a .= "<tr class='cl'>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                   
                    
                $a .= "</tr>";
            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->code."</td>";
                    $a .= "<td>".$r->description."</td>";
                    $a .= "<td>".$r->rate."</td>";
                    $a .= "<td>".$r->is_add."</td>";
                   
                    
                $a .= "</tr>";
            }
        $a .= "</table>";
        
        echo $a;
    }


    public function get_type(){
        // $this->db->select(array('is_add'));
        // $this->db->where("code",$_POST['id']);
        // echo json_encode($this->db->get($this->mtb)->first_row());

        $this->db->select(array('is_add'));
        $this->db->where("code",$_POST['id']);
        $query=$this->db->get($this->mtb);

        foreach ($query->result() as $row) {
            echo $row->is_add;
        }
       

    }

    
	
	

}