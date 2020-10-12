<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class r_subitem extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->mtb = $this->tables->tb['r_sub_item'];
        $this->load->model('user_permissions');
    }
    public function base_details() {
        $a['table_data'] = $this->data_table();
        $a['max_no'] = $this->genarate_max();
        return $a;
    }
    public function data_table() {
        $this->load->library('table');
        $this->load->library('useclass');
        $this->table->set_template($this->useclass->grid_style());
        $code   = array(
            "data" => "Code",
            "style" => "width: 100px; cursor : pointer;",
            "onclick" => "set_short(1)"
        );
        $des    = array(
            "data" => "Description",
            "style" => "cursor : pointer;",
            "onclick" => "set_short(2)"
        );
        $qty    = array(
            "data" => "Quantity",
            "style" => "cursor : pointer;"
            
        );
        $dt     = array(
            "data" => "Date/Time",
            "style" => "width: 150px;"
        );
        $action = array(
            "data" => "Action",
            "style" => "width: 100px;"
        );
        $this->table->set_heading($code, $des, $qty, $action);
        $this->db->select(array(
            'action_date',
            'description',
            'code',
            'qty'
        ));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        foreach ($query->result() as $r) {
            $but = "<img src='" . base_url() . "img/edit.gif' onclick='set_edit(\"" . $r->code . "\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('r_subitem')){$but .= "<img src='" . base_url() . "img/delete.gif' onclick='set_delete(\"" . $r->code . "\")' title='Delete' />";}
            $ed   = array(
                "data" => $but,
                "style" => "text-align: center; width: 108px;"
            );
            $dt   = array(
                "data" => $r->action_date,
                "style" => "text-align: center;  width: 158px;"
            );
            $dis  = array(
                "data" => $this->useclass->limit_text($r->description, 50),
                "style" => "text-align: left;"
            );
            $qty  = array(
                "data" => $this->useclass->limit_text($r->qty, 50),
                "style" => "text-align: right;"
            );
            $code = array(
                "data" => $r->code,
                "style" => "text-align: left; width: 108px; ",
                "value" => "code"
            );
            $this->table->add_row($code, $dis, $qty, $ed);
        }
        return $this->table->generate();
    }


    public function genarate_int(){
        $sql="SELECT max(`max_no`) as max_no FROM (`r_sub_item`) LIMIT 1";
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
        $sql="SELECT LPAD((SELECT IFNULL(MAX(max_no),0)+1 FROM r_sub_item),4,0) AS v";
        $code=$this->db->query($sql)->first_row()->v;
        $new_code="SI".$code;
        return $new_code; 
    }

   /* public function genarate_max_save(){
        $sql="SELECT LPAD((SELECT IFNULL(MAX(max_no),0)+1 FROM r_sub_item),4,0) AS v";
        $code=$this->db->query($sql)->first_row()->v;
        $new_code="SI".$code;
        return $new_code; 
    }*/

    public function save() {
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
                $arr=array(
                    "code"         =>$this->genarate_max(),
                    "max_no"       =>$this->genarate_int(),
                    "description"  =>$_POST['description'],
                    "qty"          =>$_POST['qty'],
                    "oc"           =>$this->sd['oc']
                );
                $arr_update=array(
                    "max_no"       =>$_POST['max_no'],
                    "description"  =>$_POST['description'],
                    "qty"          =>$_POST['qty'],
                    "oc"           =>$this->sd['oc']
                );
                if ($_POST['code_'] == "0" || $_POST['code_'] == "") {
                    if($this->user_permissions->is_add('r_subitem')){
                        if($this->utility->check_code_dup("r_sub_item",$_POST['code'])){
                            $this->db->insert($this->mtb, $arr);
                            echo $this->db->trans_commit();
                        }else{
                            echo "This code (".$_POST['code'].") already added.";
                            $this->db->trans_commit();
                        }
                    }else{
                        echo "No permission to save records";
                        $this->db->trans_commit();
                    }
                } else {
                    if($this->user_permissions->is_edit('r_subitem')){
                        $this->db->where("code", $_POST['code_']);
                        $this->db->update($this->mtb, $arr_update);
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

    public function get_data_table() {
        echo $this->data_table();
    }
    public function check_code() {
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);
        echo $this->db->get($this->mtb)->num_rows;
    }
    public function load() {
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);
        echo json_encode($this->db->get($this->mtb)->first_row());
    }

    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'Sub Item','r_sub_item','t_item_movement_sub','sub_item');
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
            if($this->user_permissions->is_delete('r_subitem')){
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
    public function select() {
        $query = $this->db->get($this->mtb);
        $s     = "<select name='units' id='units'>";
        $s .= "<option value='0'>---</option>";
        foreach ($query->result() as $r) {
            $s .= "<option title='" . $r->description . "' value='" . $r->code . "'>" . $r->description . "</option>";
        }
        $s .= "</select>";
        return $s;
    }

    public function item_list_all() {
        if ($_POST['search'] == 'Key Word: code, name') {
            $_POST['search'] = "";
        }
        $sql   = "SELECT * FROM  $this->mtb  WHERE description LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%' LIMIT 25";
        $query = $this->db->query($sql);
        $a     = "<table id='item_list' style='width : 100%' >";
        $a .= "<thead><tr>";
        $a .= "<tr>";
        $a .= "<th class='tb_head_th'>Code</th>";
        $a .= "<th class='tb_head_th'>Item Name</th>";
        $a .= "</tr>";
        $a .= "</thead></tr>";
        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td></tr>";
        foreach ($query->result() as $r) {
            $a .= "<tr class='cl'>";
            $a .= "<td>" . $r->code . "</td>";
            $a .= "<td>" . $r->description . "</td>";
            $a .= "</tr>";
        }
        $a .= "</table>";
        echo $a;
    }
}