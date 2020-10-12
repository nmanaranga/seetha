<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class t_opening_balance extends CI_Model {

    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_items;
    private $tb_cost_log;
    private $tb_branch;
    private $tb_acc_trance;
    private $tb_cus;
    private $h = 297;
    private $w = 210;
    private $mod='003';

    function __construct() {
        parent::__construct();

        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');

        $this->tb_sum = $this->tables->tb['t_opening_bal_sum'];
        $this->tb_det = $this->tables->tb['t_opening_bal_det'];
        $this->tb_account_type = $this->tables->tb['m_account_type'];
        $this->tb_check_double_entry = $this->tables->tb['t_check_double_entry'];
        $this->tb_account = $this->tables->tb['m_account'];
        $this->tb_branch = $this->tables->tb['s_branches'];
    }

    public function base_details() {
        $this->load->model('m_option_setup');
        $a['max_no'] = $this->utility->get_max_no("t_opening_bal_sum", "no");
        $a['sd'] = $this->sd;
        $a['grid'] = $this->m_option_setup->get_grid();
        $a['open_bal_date'] = $this->utility->get_open_bal_date();
        
        return $a;
    }

    public function validation() {
        $status = 1;
        $this->max_no = $this->utility->get_max_no("t_opening_bal_sum", "no");
        $is_account = $this->validation->check_is_account2("0_",100);
        if ($is_account != 1) {
            return $is_account;
        }
        
        return $status;
    }

    public function save() {
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
      }
      set_error_handler('exceptionThrower'); 
      try { 
        $validation = $this->validation();
        if ($validation == 1){                
            $a = array(
                "no" => $this->max_no,
                "date" => $_POST['date'],
                "ref_no" => $_POST['ref_no'],
                "note" => $_POST['note'],
                "dr_amount" => $_POST['tot_dr'],
                "cr_amount" => $_POST['tot_cr'],
                'bc' => $this->sd['branch'],
                'cl' => $this->sd['cl'],
                "oc" => $this->sd['oc']
            );

            for ($i = 0; $i < 100; $i++) {
                if(isset($_POST['h_' . $i])){
                    if ($_POST['h_' . $i] != "" && $_POST['h_' . $i] != "0") {
                        $a1[] = array(
                            "no" => $this->max_no,
                            "account_code" => $_POST['h_' . $i],
                            "type" => $_POST['4_' . $i],
                            "dr_amount" => $_POST['1_' . $i],
                            "cr_amount" => $_POST['2_' . $i],
                            "bc" => $this->sd['branch'],
                            "cl" => $this->sd['cl'],
                        );
                    }
                }
            }

            if ($_POST['hid'] == "" || $_POST['hid'] == 0) {
                if($this->user_permissions->is_add('t_opening_balance')){
                    $this->db->insert($this->tb_sum, $a);
                    if (count($a1)) {
                        $this->db->insert_batch($this->tb_det, $a1);
                    }   
                    $this->utility->save_logger("SAVE",1,$_POST['id'],$this->mod);
                    echo $this->db->trans_commit()."@".$this->max_no;
                }else{
                    $this->db->trans_commit();
                    echo "No permission to save records";
                }    
            }else{
                if($this->user_permissions->is_edit('t_opening_balance')){
                    $this->set_delete();
                    $this->db->where("no", $_POST['hid']);
                    $this->db->where('cl', $this->sd['cl']);
                    $this->db->where('bc', $this->sd['branch']);
                    $this->db->limit(1);
                    $this->db->update($this->tb_sum, $a);

                    if (count($a1)) {
                        $this->db->insert_batch($this->tb_det, $a1);
                    }              
                    
                    $this->utility->save_logger("UPDATE",1,$_POST['id'],$this->mod);
                    echo $this->db->trans_commit();
                }else{
                    $this->db->trans_commit();
                    echo "No permission to edit records";
                }     
            }
        }else{
            echo $validation;
            $this->db->trans_commit();
        }
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
    }           
}


public function addional_accounts(){
    $sql="SELECT account_code,dr_amount,cr_amount 
    FROM t_opening_bal_det
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
    $query = $this->db->query($sql);

    foreach($query->result() as $row){
        $sql_c="SELECT * FROM m_customer WHERE code='".$row->account_code."'";
        $sql_s="SELECT * FROM m_supplier WHERE code='".$row->account_code."'";

        $is_customer = $this->db->query($sql_c)->num_rows();
        $is_supplier = $this->db->query($sql_s)->num_rows();

        if($is_customer>0){
          $this->customer_accounts($row->account_code,$row->dr_amount,$row->cr_amount);
      }else if($is_supplier>0){
          $this->supplier_accounts($row->account_code,$row->dr_amount,$row->cr_amount);
      }
  }
}

public function customer_accounts($customer,$dr,$cr){
  if($dr>0){
    $this->trans_settlement->save_settlement("t_cus_settlement", $customer,$_POST['date'],1,1,1,1,$dr,"0");
}
if($cr>0){
    $this->trans_settlement->save_settlement("t_cus_settlement", $customer,$_POST['date'],1,1,1,1,"0",$cr);  
}
}

public function supplier_accounts($supplier,$dr,$cr){
  if($dr>0){
    $this->trans_settlement->save_settlement("t_sup_settlement",$supplier,$_POST['date'],1,1,1,1,$dr,"0");
}
if($cr>0){
    $this->trans_settlement->save_settlement("t_sup_settlement",$supplier,$_POST['date'],1,1,1,1,"0",$cr);
}
}

public function account_update($condition) {
        //$this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", '1');
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete($this->tb_check_double_entry);

    if($_POST['hid']=="0"||$_POST['hid']==""){
        
    }else{
        if($condition=="1"){
                //$this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_code", 1);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_account_trans");
        }
    }

    $config = array(
        "ddate" => $_POST['date'],
        "trans_code" => '1',
        "trans_no" => 0,
        "op_acc" => 0,
        "reconcile" => 0,
        "cheque_no" => 0,
        "narration" => "",
        "ref_no" => $_POST['ref_no']
    );

    $des = "OPBAL : " . $_POST['note'];
    $this->load->model('account');
    $this->account->set_data($config);

    $sql="SELECT account_code,dr_amount,cr_amount 
    FROM t_opening_bal_det
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
    $query = $this->db->query($sql);

    foreach($query->result() as $row){
        if ($row->dr_amount > 0) {
            $this->account->set_value2($des, $row->dr_amount, "dr", $row->account_code, $condition);
        }
        if ($row->cr_amount > 0) {
            $this->account->set_value2($des, $row->cr_amount, "cr", $row->account_code, $condition);
        }
    }


    if ($condition == 0) {
        $query = $this->db->query("
           SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
           FROM `t_check_double_entry` t
           LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
           WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='1' AND 
           a.`is_control_acc`='0'");

        if ($query->row()->ok == "0") {

                //$this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_code", 1);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_check_double_entry");
            return "0";
        } else {
            return "1";
        }
    }
}


public function load() {
    $this->db->select(array($this->tb_sum . '.no', $this->tb_sum . '.date', $this->tb_sum . '.is_cancel', $this->tb_sum . '.ref_no', $this->tb_sum . '.note', $this->tb_sum . '.is_approve'));
    $this->db->where($this->tb_sum . '.no', $_POST['id']);
    $this->db->where('bc', $this->sd['branch']);
    $this->db->where('cl', $this->sd['cl']);
    $this->db->limit(1);
    $a['sum'] = $this->db->get($this->tb_sum)->first_row();

    if (isset($a["sum"]->no)) {
        $sql="SELECT  `account_code`,
        `m_account`.`description` AS acc_des,
        `dr_amount`,
        `cr_amount`,
        `t_opening_bal_det`.`type`,
        `m_account_type`.`heading` 
        FROM
        (`t_opening_bal_det`) 
        INNER JOIN `m_account` ON `m_account`.`code` = `t_opening_bal_det`.`account_code` 
        INNER JOIN `m_account_type` ON `m_account_type`.code=m_account.type    
        WHERE `t_opening_bal_det`.`no` = '".$a["sum"]->no."' 
        AND `bc` = '".$this->sd['branch']."' 
        AND `cl` = '".$this->sd['cl']."' 
        GROUP BY `account_code` 
        ORDER BY `t_opening_bal_det`.`auto_num` ASC";
        
        $a['det'] = $this->db->query($sql)->result();
    }
    echo json_encode($a);
}

public function delete() {
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errMsg); 
  }
  set_error_handler('exceptionThrower'); 
  try { 
    if($this->user_permissions->is_delete('t_opening_balance')){

        $this->db->where("trans_no", $_POST['id']);
        $this->db->where("trans_code", '1');
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_account_trans");

        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->limit(1);
        $this->db->update($this->tb_sum, array("is_cancel" => $this->sd['oc']));
        $this->utility->save_logger("DELETE",1,$this->max_no,$this->mod);
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

public function set_delete() {
    $this->db->where('no', $_POST['hid']);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete($this->tb_det);
}

public function PDF_report() {

    $sql = "SELECT 
    `m_supplier`.`code`, 
    `m_supplier`.`name`, 
    `m_supplier`.`address1`, 
    IFNULL (`m_supplier`.`address2`,'') address2,
    IFNULL (`m_supplier`.`address3`,'') address3, 
    IFNULL (`m_supplier`.`email`,'') email,
    IFNULL (`m_supplier_contact`.`tp`,'') tp FROM (`m_supplier`) 
    LEFT JOIN `m_supplier_contact` ON `m_supplier_contact`.`code`=`m_supplier`.`code` WHERE `m_supplier`.`code`='" . $r_detail['supplier'] . "' LIMIT 1";

    $r_detail['suppliers'] = $this->db->query($sql)->result();

    $invoice_number= $this->utility->invoice_format($_POST['qno']);
    $session_array = array(
     $this->sd['cl'],
     $this->sd['branch'],
     $invoice_number
 );
    $r_detail['session'] = $session_array;

    $this->db->SELECT(array('serial_no', 'item'));
    $this->db->FROM('t_serial');
    $this->db->WHERE('t_serial.cl', $this->sd['cl']);
    $this->db->WHERE('t_serial.bc', $this->sd['branch']);
    $this->db->WHERE('t_serial.trans_type', '3');
    $this->db->WHERE('t_serial.trans_no', $_POST['qno']);
    $r_detail['serial'] = $this->db->get()->result();


    $no = $_POST['qno'];
    $bc = $this->sd['branch'];

    $sql = "SELECT 
    `m_cluster`.`description`,
    `m_cluster`.`code`,
    `m_branch`.`name`,
    `m_branch`.`bc`,
    `t_opening_bal_sum`.`date`,
    `t_opening_bal_sum`.`no`,
    `t_opening_bal_sum`.`ref_no` 
    FROM
    `t_opening_bal_sum` 
    INNER JOIN `m_cluster` ON `m_cluster`.`code` = `t_opening_bal_sum`.`cl` 
    INNER JOIN `m_branch`  ON `m_branch`.`bc` = `t_opening_bal_sum`.`bc` AND `m_branch`.`cl` = `t_opening_bal_sum`.`cl` 
    WHERE `t_opening_bal_sum`.`no` = '$no' 
    AND `t_opening_bal_sum`.`cl` = '".$this->sd['cl']."'
    AND `t_opening_bal_sum`.`bc` = '".$this->sd['branch']."'";
    $sql2 = "SELECT 
    `t_opening_bal_det`.`account_code`,
    `m_account`.`description`,
    `m_account_type`.`heading`,
    `t_opening_bal_det`.`dr_amount`,
    `t_opening_bal_det`.`cr_amount`,
    `t_opening_bal_sum`.`date`
    FROM `t_opening_bal_sum`
    INNER JOIN `t_opening_bal_det` ON `t_opening_bal_det`.`no`=`t_opening_bal_sum`.`no`
    AND `t_opening_bal_det`.`cl`=`t_opening_bal_sum`.`cl`
    AND `t_opening_bal_det`.`bc`=`t_opening_bal_sum`.`bc`
    INNER JOIN `m_account` ON `t_opening_bal_det`.`account_code`=`m_account`.`code`
    INNER JOIN `m_account_type` ON `t_opening_bal_det`.`type`=`m_account_type`.`rtype`
    WHERE `t_opening_bal_sum`.`no`='$no'
    AND `t_opening_bal_sum`.`cl` = '".$this->sd['cl']."'
    AND `t_opening_bal_sum`.`bc` = '".$this->sd['branch']."'";

    $sql2.=" GROUP BY `t_opening_bal_det`.`account_code`";

    $r_detail['qno'] = $_POST['qno'];
    $r_detail['jtype'] = $_POST['jtype'];
    $r_detail['jtype_desc'] = $_POST['jtype_desc'];
    $r_detail['page'] = $_POST['page'];
    $r_detail['header'] = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];
    $r_detail['type'] = $_POST['type'];

    
    $query = $this->db->query($sql);
    $query2 = $this->db->query($sql2);

    $this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();

    $r_detail['jrn_en_body'] = $query2->result();
    $r_detail['cl'] = $cluster = $this->sd['cl'];

    $r_detail['bc'] = $this->sd['branch'];
    $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
    
}

public function get_account(){
    $this->db->select(array('m_account.code','m_account.description','m_account_type.heading','m_account_type.rtype'));
    $this->db->join('m_account_type', 'm_account_type.code = m_account.type', "INNER");
    $this->db->where("m_account.code",$this->input->post('code'));
    $this->db->where("is_control_acc",'0');
    $this->db->where("m_account_type.rtype !=",'1');
    $this->db->where("m_account_type.rtype !=",'2');
    

    $this->db->limit(1);
    $query=$this->db->get('m_account');
    if($query->num_rows() > 0){
        $data['a']=$query->result();
    }else{
        $data['a']=2;
    }
    echo json_encode($data);
}

public function load_previous_bal(){
    $sql="SELECT IFNULL(SUM(cr_amount),0.00) AS cr, IFNULL(SUM(dr_amount),0.00) AS dr
    FROM `t_opening_bal_det` 
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
    $query=$this->db->query($sql);

    echo json_encode($query->result());
}

public function is_approve(){
    $result =2;
    $sql="SELECT is_approve
    FROM `t_opening_bal_sum` 
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
    $query=$this->db->query($sql);
    
    foreach($query->result() as $row){
        if($row->is_approve==1){
            $result=1;
        }
    }        
    echo json_encode($result);
}

public function is_amount_balance(){
    $sql="SELECT IFNULL(SUM(dr_amount)-SUM(cr_amount),0) as balance
    FROM t_opening_bal_det
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
    $query=$this->db->query($sql);

    if($query->row()->balance != 0 || $query->row()->balance != "0.00"){
        $result="DR and CR amounts not tally";
    }else{
        $result=1;
    }
    return $result;
}

public function approve(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errLine); 
  }
  set_error_handler('exceptionThrower'); 
  try { 
    if($this->user_permissions->is_approve('t_opening_balance')){
        $is_balance = $this->is_amount_balance();
        $account_update = $this->account_update(0);
        if($is_balance==1){
            if($account_update == "1") {
                $this->account_update(1);
                $this->load->model('trans_settlement');
                $this->addional_accounts();

                $sql="SELECT account_code,dr_amount,cr_amount 
                FROM t_opening_bal_det
                WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
                $query = $this->db->query($sql);

                foreach($query->result() as $row){
                    $this->trans_settlement->save_settlement("t_opening_bal_trans", $row->account_code, $_POST['date'], 1, 0, 1, 0, $row->dr_amount, $row->cr_amount);
                }
                
                $this->db->where("cl",$this->sd['cl']);
                $this->db->where("bc",$this->sd['branch']);
                $this->db->update("t_opening_bal_sum",array("is_approve"=>1));

                $this->utility->save_logger("APPROVE",1,0,$this->mod);
                echo $this->db->trans_commit();
            }else{
                $this->db->trans_commit();
                echo "Invalid account entries";
            }
        }else{
            $this->db->trans_commit();
            echo $is_balance;
        }
    }else{
        $this->db->trans_commit();
        echo "No permission to approve records";
    }    
}catch(Exception $e){ 
  $this->db->trans_rollback();
  echo $e->getMessage()." - Operation fail please contact admin"; 
}   
}
}
