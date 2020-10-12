<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_default_account extends CI_Model {

    private $sd;
    private $mtb;
    private $mod = '004';

    function __construct() {
        parent::__construct();

        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');
        $this->mtb = $this->tables->tb['m_default_account'];
        $this->tb_account = $this->tables->tb['m_account'];
    }

    public function base_details() {
        $a['table_data'] = $this->data_table();
        return $a;
    }

    public function data_table() {
        $this->load->library('table');
        $this->load->library('useclass');

        $this->table->set_template($this->useclass->grid_style());

        $code = array("data" => "Code", "style" => "width: 100px; cursor : pointer;", "onclick" => "set_short(1)");
        $acc_code = array("data" => "Acc Code", "style" => "width: 100px; cursor : pointer;", "onclick" => "set_short(1)");
        $des = array("data" => "Description", "style" => "cursor : pointer;", "onclick" => "set_short(2)");
        $dt = array("data" => "Date/Time", "style" => "width: 150px;");
        $action = array("data" => "Action", "style" => "width: 100px;");

        $this->table->set_heading($code, $acc_code, $des, $action);

        $this->db->select(array('acc_code', 'description', 'code'));
        $query = $this->db->get($this->mtb);

        foreach ($query->result() as $r) {
            $but = "<img src='" . base_url() . "img/edit.gif' onclick='set_edit(\"" . $r->code . "\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_default_account')){$but .= "<img src='" . base_url() . "img/delete.gif' onclick='set_delete(\"" . $r->code . "\")' title='Delete' />";}
            $ed = array("data" => $but, "style" => "text-align: center; width: 108px;");
            $dt = array("data" => $r->acc_code, "style" => "text-align: center;  width: 158px;");
            $dis = array("data" => $this->useclass->limit_text($r->description, 50), "style" => "text-align: left;");
            $code = array("data" => $r->code, "style" => "text-align: left; width: 108px; ", "value" => "code");

            $this->table->add_row($code, $dt, $dis, $ed);
        }

        return $this->table->generate();
    }

    public function get_data_table() {
        echo $this->data_table();
    }

    public function save() {
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            $_POST['code'] = strtoupper($_POST['code']);
            if ($_POST['code_'] == "0" || $_POST['code_'] == "") {  
                if($this->user_permissions->is_add('m_default_account')){
                    $arr = array(
                        "code" => $_POST['code'],
                        "description" => $_POST['description'],
                        "oc" => $this->sd['oc'],
                        "acc_code" => $_POST['acc_code2']
                    );
                    $this->db->insert($this->mtb, $arr); 
                    echo $this->db->trans_commit(); 
                }else{
                     echo "No permission to save records";
                     $this->db->trans_commit(); 
                }      
            } else {
                if($this->user_permissions->is_edit('m_default_account')){
                    $arr = array(
                        "code" => $_POST['code'],
                        "description" => $_POST['description'],
                        "oc" => $this->sd['oc'],
                        "acc_code" => $_POST['acc_code2']
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
            
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }

    public function check_code() {
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);

        echo $this->db->get($this->mtb)->num_rows;
    }

    public function load() {
        $this->db->select(array(
            'm_default_account.code',
            'm_default_account.acc_code',
            'm_default_account.description',
            'm_account.description as m_account_desc'
        ));
        $this->db->from('m_default_account');
        $this->db->join('m_account', 'm_account.code=m_default_account.acc_code', 'LEFT');
        $this->db->where('m_default_account.code', $_POST['code']);
        $this->db->limit(1);

        echo json_encode($this->db->get()->first_row());
    }

    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'default account','m_default_account','t_account_trans','acc_code');
        if ($check_cancellation != 1) {
          return $check_cancellation;
        }
        return $status;
    }

    public function delete() {
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if($this->user_permissions->is_delete('m_default_account')){
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
        }catch(Exception $e){ 
          $this->db->trans_rollback();
          echo "Operation fail please contact admin"; 
        }      
    }

    public function select() {
        $query = $this->db->get($this->mtb);

        $s = "<select name='brand' id='brand'>";
        $s .= "<option value='0'>---</option>";
        foreach ($query->result() as $r) {
            $s .= "<option title='" . $r->description . "' value='" . $r->code . "'>" . $r->code . '-' . $r->description . "</option>";
        }
        $s .= "</select>";

        return $s;
    }

    public function auto_com() {
        $this->db->like('code', $_GET['q']);
        $this->db->or_like($this->mtb . '.description', $_GET['q']);
        $query = $this->db->select(array('code', $this->mtb . '.description'))->get($this->mtb);
        $abc = "";
        foreach ($query->result() as $r) {
            $abc .= $r->code . "|" . $r->description;
            $abc .= "\n";
        }

        echo $abc;
    }

    public function auto_com_branch(){
	
        $this->db->where('is_Bank_acc', '1');
	$this->db->limit(25);
	
	foreach($this->db->get($this->tb_account)->result() as $r){
	    echo $r->code."|".$r->description."|".$r->code."\n";
	}
    }

}