<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_account_type extends CI_Model {

    private $sd;
    private $mtb;
    private $mod = '004';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->mtb = $this->tables->tb['m_account_type'];
        $this->load->model('user_permissions');
    }

    public function base_details() {
        $this->load->model('m_account_type');
        $a['account_type'] = $this->m_account_type->select();
        $this->load->model('m_account_category');
        $a['account_category'] = $this->m_account_category->select();
        $a['table_data'] = $this->data_table();

        $this->db->select_max('int_code');
        $a['max_no']=$this->db->get('m_account_type')->row()->int_code+1;
        return $a;
    }




    public function data_table() {
        $this->load->library('table');
        $this->load->library('useclass');

        $this->table->set_template($this->useclass->grid_style());

        $code = array("data" => "Code", "style" => "width: 50px;");
        $description = array("data" => "Heading", "style" => "");
        $iscontrol = array("data" => "Control Account", "style" => "width: 50px;");
        $control_type_id = array("data" => "Control Id", "style" => "width: 50px;");
        $ledger_acc=array("data" => "Is Ledger Acc", "style" => "width: 50px;");
        $control_type = array("data" => "Control Type", "style" => "width: 250px;");
        $action = array("data" => "Action", "style" => "width: 60px;");

        $this->table->set_heading($code, $description, $iscontrol, $control_type_id,$ledger_acc, $control_type, $action);

        if(isset($_POST['cond']) && isset($_POST['search'])){
            $cond=$_POST['cond'];
            $search=$_POST['search'];
            if($cond==1){
                $sql = "SELECT `code`,`heading`,`is_control_category`,IF((control_category=''),'_',control_category)AS `control_category` ,IFNULL((SELECT `heading` FROM `m_account_type` `ma2` WHERE  `ma1`.`control_category`= `ma2`.`code` ),'_') AS `account`,is_ledger_acc FROM `m_account_type` `ma1` WHERE  ma1.`is_ledger_acc`='1' AND (
                ma1.`heading` LIKE '%%' OR ma1.`code` LIKE '%%') LIMIT 25";
            }else{
                $sql = "SELECT `code`,`heading`,`is_control_category`,IF((control_category=''),'_',control_category)AS `control_category` ,IFNULL((SELECT `heading` FROM `m_account_type` `ma2` WHERE  `ma1`.`control_category`= `ma2`.`code` ),'_') AS `account`,is_ledger_acc FROM `m_account_type` `ma1` WHERE ma1.`heading` LIKE '%$search%' OR
                ma1.`code` LIKE '%$search%' LIMIT 25";    
            }
        }else{
            $sql = "SELECT `code`,`heading`,`is_control_category`,IF((control_category=''),'_',control_category)AS `control_category` ,IFNULL((SELECT `heading` FROM `m_account_type` `ma2` WHERE  `ma1`.`control_category`= `ma2`.`code` ),'_') AS `account`,is_ledger_acc FROM `m_account_type` `ma1` LIMIT 25";
        }


        $query = $this->db->query($sql);

        foreach ($query->result() as $r) {
            $but = "<img src='" . base_url() . "img/edit.gif' onclick='set_edit(\"" . $r->code . "\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_account_type')){$but .= "<img src='" . base_url() . "img/delete.gif' onclick='set_delete(\"" . $r->code . "\")' title='Delete' />";}

            $code = array("data" => $r->code);
            $heading = array("data" => $this->useclass->limit_text($r->heading, 25));
            $iscontrol = array("data" => $this->useclass->limit_text($r->is_control_category));
            $is_ledger_acc = array("data" => $this->useclass->limit_text($r->is_ledger_acc));
            $control_type_id = array("data" => $this->useclass->limit_text($r->control_category));
            $control_type = array("data" => $this->useclass->limit_text($r->account));
            $action = array("data" => $but, "style" => "text-align: center;");
            $this->table->add_row($code, $heading, $iscontrol, $control_type_id,$is_ledger_acc, $control_type, $action);
        }
        return $this->table->generate();
    }

    public function get_data_table() {
        echo $this->data_table();
    }


    public function generate_acc_code(){
        $cc=$_POST['get_code'];
        $code="";
        $query="SELECT MAX(int_code) AS `no`FROM m_account_type WHERE control_category='$cc' LIMIT 1";
        $res=$this->db->query($query);

        if($res->num_rows()>0){
            $qury="SELECT ncformat FROM m_account_type WHERE code='$cc'";
            $re=$this->db->query($qury);
            $length=strlen($re->row()->ncformat);
            $code=($res->row()->no)+1;
        }else{
            $qury="SELECT ncformat FROM m_account_type WHERE code='$cc'";
            $re=$this->db->query($qury);
            $length=strlen($re->row()->ncformat);
            $code=1;
        }

        $q="SELECT LPAD((".$code."),".$length.",0) AS no";
        $r=$this->db->query($q)->row()->no;
        $a['code']=$cc.$r;

        $q2="SELECT report,rtype,is_control_category FROM m_account_type WHERE code='$cc' LIMIT 1";
        $r2=$this->db->query($q2);

        $a['rtype']=$r2->row()->rtype;
        $a['report']=$r2->row()->report;
        $a['is_control_category']=$r2->row()->is_control_category;
        echo json_encode($a);
    }

    public function save() {
        if(!isset($_POST['is_ledger_acc'])){$_POST['is_ledger_acc'] = 0;}
        if(!isset($_POST['is_control_acc'])){$_POST['is_control_acc'] = 0;}
        if(!isset($_POST['is_bank_acc'])){$_POST['is_bank_acc'] = 0;}
        if(!isset($_POST['is_control_category'])){$_POST['is_control_category'] = 0;}
        
        $this->db->trans_begin();
        error_reporting(E_ALL);

        function exceptionThrower($type, $errMsg, $errFile, $errLine) {
            throw new Exception($errMsg);
        }



        if(isset($_POST['control_category'])){
         $length=strlen($_POST['control_category']);
         $_POST['int_code']=substr($_POST['code'],$length);
     }else{
        $_POST['int_code']=$_POST['code'];
    }

    set_error_handler('exceptionThrower');
    try {
        $_POST['code'] = strtoupper($_POST['code']);

        if ($_POST['code_'] == "0" || $_POST['code_'] == "") {


         if($this->user_permissions->is_add('m_account_type')){ 
            unset($_POST['code_']);
            $data=array(
                "int_code"=>$_POST['int_code'],
                "ncformat"=>$_POST['ncformat'],
                "code"=>$_POST['code'],
                "heading"=>$_POST['heading'],
                "report"=>$_POST['report'],
                "rtype"=>$_POST['rtype'],
                "control_category"=>$_POST['control_category'],
                "is_control_category"=>$_POST['is_control_category'],
                "is_ledger_acc"=>$_POST['is_ledger_acc']
            );

            $this->db->insert($this->mtb, $data);

            if(isset($_POST['is_ledger_acc']) && $_POST['is_ledger_acc']!=0){

                $data2=array(
                    "type"=>$_POST['control_type2'],   
                    "code"=>$_POST['code_samp'],
                    "description"=>$_POST['description_samp'],
                    "control_acc"=>$_POST['control_acc'],
                    "is_control_acc"=>$_POST['is_control_acc'],
                    "is_bank_acc"=>$_POST['is_bank_acc'],
                    "category"=>$_POST['control_category'],
                    "order_no"=>$_POST['order_no'],
                    "display_text"=>$_POST['dis_text'],
                    "is_sys_acc"=>0,
                    "oc"=>$this->sd['oc']
                );
                $this->db->insert('m_account', $data2);
            }


            echo $this->db->trans_commit();

        }else{
         echo "No permission to save records";
         $this->db->trans_commit();
     }
 }else{
    if($this->user_permissions->is_edit('m_account_type')){
       // if($this->check_rec_in_acc_trans()>0){
            $this->db->where("code", $_POST['code_']);
            unset($_POST['code_']);


            $data=array(
                "int_code"=>$_POST['int_code'],
                "ncformat"=>$_POST['ncformat'],
                "code"=>$_POST['code'],
                "heading"=>$_POST['heading'],
                "report"=>$_POST['report'],
                "rtype"=>$_POST['rtype'],
                "control_category"=>$_POST['control_category'],
                "is_control_category"=>$_POST['is_control_category'],
                "is_ledger_acc"=>$_POST['is_ledger_acc']
            );



            $this->db->update($this->mtb, $data);


                    /*if(isset($_POST['is_ledger_acc']) && $_POST['is_ledger_acc']!=0){

                        $data2=array(
                        "type"=>$_POST['control_type2'],   
                        "description"=>$_POST['description_samp'],
                        "control_acc"=>$_POST['control_acc'],
                        "is_control_acc"=>$_POST['is_control_acc'],
                        "is_bank_acc"=>$_POST['is_bank_acc'],
                        "category"=>$_POST['control_category'],
                        "order_no"=>$_POST['order_no'],
                        "display_text"=>$_POST['dis_text'],
                        "is_sys_acc"=>0,
                        "oc"=>$this->sd['oc']
                        );

                        $this->db->where("code", $_POST['code_samp']);
                        $this->db->update('m_account', $data2);
                    }
*/

                    if(isset($_POST['is_ledger_acc']) && $_POST['is_ledger_acc']!=0){

                        $ssql="SELECT code from m_account where code ='".$_POST['code_samp']."'";
                        $query = $this->db->query($ssql);

                        if($query->num_rows()>0){

                            $data2=array(
                                "type"=>$_POST['control_type2'],   
                                "description"=>$_POST['description_samp'],
                                "control_acc"=>$_POST['control_acc'],
                                "is_control_acc"=>$_POST['is_control_acc'],
                                "is_bank_acc"=>$_POST['is_bank_acc'],
                                "category"=>$_POST['control_category'],
                                "order_no"=>$_POST['order_no'],
                                "display_text"=>$_POST['dis_text'],
                                "is_sys_acc"=>0,
                                "oc"=>$this->sd['oc']
                            );

                            $this->db->where("code", $_POST['code_samp']);
                            $this->db->update('m_account', $data2);
                        }else{

                            $data2=array(
                                "type"=>$_POST['control_type2'],   
                                "code"=>$_POST['code_samp'],
                                "description"=>$_POST['description_samp'],
                                "control_acc"=>$_POST['control_acc'],
                                "is_control_acc"=>$_POST['is_control_acc'],
                                "is_bank_acc"=>$_POST['is_bank_acc'],
                                "category"=>$_POST['control_category'],
                                "order_no"=>$_POST['order_no'],
                                "display_text"=>$_POST['dis_text'],
                                "is_sys_acc"=>0,
                                "oc"=>$this->sd['oc']
                            );
                            $this->db->insert('m_account', $data2);
                        }   
                    }




                    unset($_POST['code_']);

                    echo $this->db->trans_commit();
                /*}else{
                    echo "Can't edit records";
                    $this->db->trans_commit();
                }*/
            }else{
             echo "No permission to edit records";
             $this->db->trans_commit();
         }
     }

 } catch (Exception $e) {
    $this->db->trans_rollback();
    echo $e->getMessage()." Operation fail please contact admin";
}
}



public function check_rec_in_acc_trans() {

    $this->db->where('acc_code', $_POST['code']);
    echo $this->db->get('t_account_trans')->num_rows;
}


public function check_code() {
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);

    echo $this->db->get($this->mtb)->num_rows;
}

public function load() {
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    $a['det']=$this->db->get($this->mtb)->first_row();

    $q="SELECT control_category FROM m_account_type WHERE code='$_POST[code]'";

    $code=$this->db->query($q)->row()->control_category;
    $a['m_code']=$code;

    $q="SELECT heading FROM m_account_type WHERE code='$code'";
    if($this->db->query($q)->num_rows()>0){
        $a['m_heading']=$this->db->query($q)->row()->heading;    
    }else{
        $a['m_heading']="";
    }

    echo json_encode($a);

}


public function delete_validation(){
    $status=1;
    $codes=$_POST['code'];
    $check_cancellation = $this->utility->check_account_trans($codes,'account type','m_account_type','t_account_trans','acc_code');
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
        if($this->user_permissions->is_delete('m_account_type')){
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
    } catch (Exception $e) {
        $this->db->trans_rollback();
        echo "Operation fail please contact admin";
    }
}

public function select() {

    $query = $this->db->query("SELECT `code`,`heading` FROM m_account_type WHERE `is_control_category`='1'");
    $s = "<select name='control_type' id='control_type'>";
    $s .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
        $s .= "<option title='" . $r->heading . "' value='" . $r->code . "'>" . $r->code . " | " . $r->heading . "</option>";
    }
    $s .= "</select>";

    return $s;
}

public function cat_list_all() {

    if ($_POST['search'] == 'Key Word: code, name') {
        $_POST['search'] = "";
    }
    $sql = "SELECT `code`,`heading` FROM m_account_type WHERE `is_control_category`='1' AND `heading` LIKE '%n%'";
    $query = $this->db->query($sql);
    $a = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th'>Heading</th>";
    $a .= "</thead></tr>
    <tr class='cl'>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    ";
    foreach ($query->result() as $r) {
        $a .= "<tr class='cl'>";
        $a .= "<td>" . $r->code . "</td>";
        $a .= "<td>" . $r->heading . "</td>";
        $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
}

public function setrt() {
    $accid = $_POST['code'];
    $query = $this->db->query("SELECT `report`,`is_control_category` FROM m_account_type WHERE `code`='$accid'");
    foreach ($query->result() as $r) {
        if (($r->report) == "1") {
            $s = '<option value="1">Balance Sheet</option>';
        } if (($r->report) == "2") {
            $s = '<option value="2">Profit and Loss</option>';
        }

    }
    if ($accid == '0') {

        $s = '<option value="0">--</option>';
        $s.= '<option value="1">Balance Sheet</option>';
        $s.= '<option value="2">Profit and Loss</option>';
    }
    echo $s;
}

public function setrt2() {
    $accid = $_POST['code'];
    $query2 = $this->db->query("SELECT `rtype`,`is_control_category` FROM m_account_type WHERE `code`='$accid'");
    foreach ($query2->result() as $rs) {
        if (($rs->rtype) == '1') {
            $s = '<option value="1">Income</option>';
        } if (($rs->rtype) == "2") {
            $s = '<option value="2">Expence</option>';
        }
        if (($rs->rtype) == "3") {
            $s = '<option value="3">Assets</option>';
        }
        if (($rs->rtype) == "4") {
            $s = '<option value="4">Liabilities</option>';
        }
    }
    if (($accid) == '0') {
        $s = '<option value="0">--</option>';
        $s.= '<option value="1">Income</option>';
        $s.= '<option value="2">Expence</option>';
    }
    echo $s;
}

public function auto_com(){
    $this->db->like('code', $_GET['q']);
    $this->db->or_like('description', $_GET['q']);
    $query = $this->db->select(array('code', 'description'))->get('r_category');

    if ($query->num_rows() > 0) {
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('description', $_GET['q']);
        $query2 = $this->db->select(array('code', 'description'))->get('r_category');
    }else{
        $query2 = $this->db->select(array('code', 'description'))->get('r_category');
    }

    $abc = "";
    foreach($query2->result() as $r){
        $abc .= $r->code."|".$r->description;
        $abc .= "\n";
    }

    echo $abc;
}  

public function load_m_account(){

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
    , `m_account`.`is_sys_acc`,
    m_account_type.heading
    FROM
    `m_account` AS `m_account_1`
    RIGHT JOIN `m_account` 
    ON (`m_account_1`.`code` = `m_account`.`control_acc`)
    JOIN m_account_type 
    ON m_account_type.code=m_account.type

    WHERE `m_account`.`code`='".$_POST['code']."'";

    $qry=$this->db->query($sql);

    if($qry->num_rows()>0){
        $b=$qry->result();
    }else{
        $b=2;
    }
    echo json_encode($b);
}

}
