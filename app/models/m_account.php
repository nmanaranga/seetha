<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_account extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '004';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_account'];
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
    $this->load->model('m_account_type');
    $a['account_type']=$this->s_type();
    
    $this->load->model('m_account_category');
    $a['account_category']=$this->m_account_category->select();

	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $type = array("data"=>"Type", "style"=>"width: 40px;");
        $code = array("data"=>"Code", "style"=>"width: 50px;");
        $description = array("data"=>"Description", "style"=>"");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($type,$code, $description, $action);
        
      $this->db->select(array('code', 'description', 'type'));
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_account')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            
            $code = array("data"=>$r->code);
            $type = array("data"=>$r->type);
            $description = array("data"=>$this->useclass->limit_text($r->description, 25));
   
            $action = array("data"=>$but, "style"=>"text-align: center;");
        
            $this->table->add_row($type,$code, $description, $action);
        }
         
        return $this->table->generate();
    }
    
    public function get_data_table(){
	echo $this->data_table();
    }
	
    public function check_is_transaction()
    {
        $sql="SELECT COUNT(`acc_code`) AS cc FROM `t_account_trans` WHERE `acc_code`='".$_POST['code']."'";
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        if($r->cc>0)
        {
            echo '1';
        }        
        else
        {
            echo '0';
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
            $_POST['code'] = strtoupper($_POST['code']);
            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_account')){
                    unset($_POST['code_']);
                    if(isset($_POST['is_bank_acc'])){$_POST['is_bank_acc']="1";}else{$_POST['is_bank_acc']="0";}
                    if(isset($_POST['is_control_acc'])){$_POST['is_control_acc']="1";}else{$_POST['is_control_acc']="0";}
                    $this->db->insert($this->mtb, $_POST);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{
                if($this->user_permissions->is_edit('m_account')){
                    $this->db->where("code", $_POST['code_']);
                    unset($_POST['code_']);
                    if(isset($_POST['is_bank_acc'])){$_POST['is_bank_acc']="1";}else{$_POST['is_bank_acc']="0";}
                    if(isset($_POST['is_control_acc'])){$_POST['is_control_acc']="1";}else{$_POST['is_control_acc']="0";}
                    $this->db->update($this->mtb, $_POST);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }
            }
            
        }catch(Exception $e){ 
          $this->db->trans_rollback();
          echo $e->getMessage()."Operation fail please contact admin"; 
        }    
    }
 
public function account_list(){
    
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
    
      
       $sql = "SELECT * FROM `m_account`  WHERE is_control_acc='0' AND description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' LIMIT 25";
       $query = $this->db->query($sql);
       $a = "<table id='item_list' style='width : 100%' >";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Account Code</th>";
            $a .= "<th class='tb_head_th'>Account Description</th>";
         
        $a .= "</thead></tr>
                <tr class='cl'>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>

        ";
            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->code."</td>";
                    $a .= "<td>".$r->description."</td>";        
                    
                $a .= "</tr>";
            }
        $a .= "</table>";
        
        echo $a;
    }
    
public function account_list_opn(){
    
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

       /*$sql = "SELECT
            m_account.`code`,description
            ,m_account_type.`heading`
            ,m_account_type.`rtype`

        FROM
            m_account
        INNER JOIN `m_account_type` ON `m_account_type`.code=m_account.type    
        WHERE m_account.`is_control_acc`=0 AND (`m_account_type`.`rtype`!='1' AND `rtype`!='2')
        AND (description LIKE '$_POST[search]%' OR m_account.`code` LIKE '$_POST[search]%') LIMIT 25";*/
       
       $sql = "SELECT
            m_account.`code`,description
            ,m_account_type.`heading`
            ,m_account_type.`rtype`

        FROM
            m_account
        INNER JOIN `m_account_type` ON `m_account_type`.code=m_account.type    
        WHERE m_account.`is_control_acc`=0 
        AND (description LIKE '$_POST[search]%' OR m_account.`code` LIKE '$_POST[search]%') LIMIT 25";
       //echo $sql;exit;
       
       
       $query = $this->db->query($sql);
       $a = "<table id='item_list' style='width : 100%' >";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Account Code</th>";
            $a .= "<th class='tb_head_th'>Account Description</th>";
            $a .= "<th class='tb_head_th'>Type</th>";
         
        $a .= "</thead></tr>
                <tr class='cl'>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style='display:none;'>&nbsp;</td>
                </tr>

        ";
            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->code."</td>";
                    $a .= "<td>".$r->description."</td>";        
                    $a .= "<td>".$r->heading."</td>";        
                    $a .= "<td  style='display:none;'>".$r->rtype."</td>";        
                    
                $a .= "</tr>";
            }
        $a .= "</table>";
        
        echo $a;
    }
 
    
    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){        
       $sql="SELECT
                  `m_account`.`type`
                , `m_account`.`code`
                , `m_account`.`description`
                , `m_account`.`control_acc`
                , `m_account`.`is_control_acc`
                , `m_account_1`.`description` AS con_des
                , `m_account`.`is_bank_acc`
                , `m_account`.`category`
                , `m_account`.`order_no`
                , `m_account`.`display_text`
                , `m_account`.`is_sys_acc`
            FROM
                `m_account` AS `m_account_1`
                RIGHT JOIN `m_account` 
                    ON (`m_account_1`.`code` = `m_account`.`control_acc`)
            WHERE `m_account`.`code`='".$_POST['code']."'";

        $qry=$this->db->query($sql);
        echo json_encode($qry->first_row());   
    }
    
    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if($this->user_permissions->is_delete('m_account')){
        	    $this->db->where('code', $_POST['code']);
        	    $this->db->limit(1);  
        	    $this->db->delete('m_account_type');

                $this->db->where('code', $_POST['code']);
                $this->db->limit(1);  
                $this->db->delete('m_account');
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

    public function auto_com(){
        $this->db->where('is_control_acc', '0');
        $this->db->like('code', $_GET['q']);
        $this->db->or_like($this->mtb.'.description', $_GET['q']);
        $query = $this->db->select(array('code', $this->mtb.'.description'))
        
        ->get($this->mtb);
        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->code."|".$r->description;  
            $abc .= "\n";
        }
        
        echo $abc;
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

        public function select_Control(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='control_acc' id='control_acc'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." | ".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }


     public function s_type() {

        $query = $this->db->query("SELECT `code`,`heading` FROM m_account_type WHERE `is_control_category`='1'");
        $s = "<select name='type' id='type'>";
        $s .= "<option value='0'>---</option>";
        foreach ($query->result() as $r) {
            $s .= "<option title='" . $r->heading . "' value='" . $r->code . "'>" . $r->code . " | " . $r->heading . "</option>";
        }
        $s .= "</select>";

        return $s;
    }
}