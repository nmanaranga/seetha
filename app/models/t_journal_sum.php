<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class t_journal_sum extends CI_Model {

    private $sd;
    private $tb_sum;
    private $tb_det;
    private $mod='003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');
        $this->tb_sum = $this->tables->tb['t_journal_entry_sum'];
        $this->tb_det = $this->tables->tb['t_journal_entry_det'];
        $this->tb_check_double_entry = $this->tables->tb['t_check_double_entry'];
        $this->tb_payable_sum = $this->tables->tb['m_journal_type_sum'];
        $this->tb_payable_det = $this->tables->tb['m_journal_type_det'];
        $this->tb_branch = $this->tables->tb['s_branches'];
        $this->tb_users = $this->tables->tb['s_users'];
        $this->tb_trance = $this->tables->tb['t_account_trans'];
        $this->tb_payable_type = $this->tables->tb['m_journal_type_sum'];
        $this->tb_account = $this->tables->tb['m_account'];
        $this->max_no=$this->utility->get_max_no("t_journal_entry_sum","no");
    }

    public function base_details() {
        $this->load->model('m_option_setup');

        $a['grid'] = $this->m_option_setup->get_grid();
        //$a['max_no'] = $this->max_no();
        $a['max_no'] = $this->utility->get_max_no("t_journal_entry_sum", "no");
        $a['sd'] = $this->sd;

        return $a;
    }

    private function max_no() {
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->select_max("no");

        return $this->db->get($this->tb_sum)->first_row()->no + 1;
    }

    public function get_max() {
        $a['max_no'] = $this->max_no();
        echo json_encode($a);
    }

    public function validation(){
        $status=1;
        $this->max_no = $this->utility->get_max_no("t_journal_entry_sum", "no");
        $is_journal_type=$this->validation->check_is_journal($_POST['journal_type']);
        if($is_journal_type!=1){
            return $is_journal_type;
        } 
        $is_account = $this->validation->check_is_account2("0_");
        if ($is_account != 1) {
            return $is_account;
        }
        /*$account_update=$this->account_update(0);
        if($account_update!=1){
            return "Invalid account entries";
        }*/  
           
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
        $validation=$this->validation();
        if($validation==1){
            $a = array(
                "date" => $_POST['date'],
                "ref_no" => $_POST['ref_no'],
                "journal_type" => $_POST['journal_type'],
                "description" => $_POST['description'],
                "narration" => $_POST['narration'],
                "total" => $_POST['tot_dr'],
                'bc' => $this->sd['branch'],
                'cl' => $this->sd['cl'],
                "oc" => $this->sd['oc'],
                "no" => $this->max_no ,
            );

            $a1 = $a2 = $a3 = array();

            for ($i = 0; $i < $_POST['grid_row']; $i++) {
                if ($_POST['h_' . $i] != "" && $_POST['h_' . $i] != "0") {
                    $a1[] = array(
                        "no" => $this->max_no ,
                        "account_code" => $_POST['h_' . $i],
                        "memo" => $_POST['1_' . $i],
                        "dr_amount" => $_POST['2_' . $i],
                        "cr_amount" => $_POST['3_' . $i],
                        "bc" => $this->sd['branch'],
                        "cl" => $this->sd['cl'],
                    );
                }
            }

            if ($_POST['hid'] == "" || $_POST['hid'] == 0) {
                if($this->user_permissions->is_add('t_journal_sum')){
                    $account_update=$this->account_update(0);
                    if($account_update==1){
                        $this->db->insert($this->tb_sum, $a);
                        $this->account_update(1);  
                        if(count($a1)){
                            $this->db->insert_batch($this->tb_det, $a1);
                        }
                        $this->utility->save_logger("SAVE",37,$this->max_no,$this->mod);
                        echo $this->db->trans_commit()."@".$this->max_no;
                    }else{
                        $this->db->trans_commit();
                        echo "Invalid account entries";
                    }
                }else{
                    $this->db->trans_commit();
                    echo "No permission to save records";
                }    
            } else {
                if($this->user_permissions->is_edit('t_journal_sum')){
                    $account_update=$this->account_update(0);
                    if($account_update==1){
                        $this->db->where("no", $_POST['hid']);
                        $this->db->where('cl', $this->sd['cl']);
                        $this->db->where('bc', $this->sd['branch']);
                        $this->db->limit(1);
                        $this->db->update($this->tb_sum, $a);
                        $this->set_delete();
                        $this->account_update(1); 
                        if(count($a1)){
                            $this->db->insert_batch($this->tb_det, $a1);
                        } 
                        $this->utility->save_logger("UPDATE",37,$this->max_no,$this->mod);
                        echo $this->db->trans_commit();
                    }else{
                        $this->db->trans_commit();
                        echo "Invalid account entries";
                    }
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

    public function account_update($condition) {
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", '37');
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete($this->tb_check_double_entry);

        if($_POST['hid']=="0"||$_POST['hid']==""){
            
        }else{
            if($condition=="1"){
                $this->db->where("trans_no", $this->max_no);
                $this->db->where("trans_code", 37);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_account_trans");
            }
        }


        $config = array(
            "ddate" => $_POST['date'],
            "trans_code" => '37',
            "trans_no" => $this->max_no,
            "op_acc" => 0,
            "reconcile" => 0,
            "cheque_no" => 0,
            "narration" => "",
            "ref_no" => $_POST['ref_no']
        );

        $des = "JE : " . $_POST['description'];
        $this->load->model('account');
        $this->account->set_data($config);

        for ($i = 0; $i < $_POST['grid_row']; $i++) {
            if ($_POST['h_' . $i] != "" && $_POST['h_' . $i] != "0") {
                if ($_POST['2_' . $i] > 0) {
                    $this->account->set_value2($des, $_POST['2_' . $i], "dr", $_POST['h_' . $i],$condition);
                }
                if ($_POST['3_' . $i] > 0) {
                    $this->account->set_value2($des, $_POST['3_' . $i], "cr", $_POST['h_' . $i],$condition);
                }
            }
        }

       if($condition==0){

        $query = $this->db->query("
             SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
             FROM `t_check_double_entry` t
             LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
             WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='3'  AND t.`trans_no` ='" .$this->max_no. "' AND 
             a.`is_control_acc`='0'");

          if($query->row()->ok == "0") {
            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_code",37);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_check_double_entry");
            return "0";
          }else {
            return "1";
          }
      }
    }

    public function load_je_type() {
        $this->db->select(array($this->tb_payable_sum . '.code', $this->tb_payable_sum . '.description', 'account', 'payble_type', $this->tb_account . '.description AS payable_acc'));
        $this->db->where($this->tb_payable_sum . '.code', $_POST['je_type']);
        $this->db->join($this->tb_account, $this->tb_account . ".code=" . $this->tb_payable_sum . ".account", "INNER");
        $this->db->limit(1);

        $a['sum'] = $this->db->get($this->tb_payable_sum)->first_row();

        $this->db->select(array($this->tb_payable_det . '.account_code', $this->tb_payable_det . '.note', 'dr', 'cr', $this->tb_account . '.description AS acc_des'));
        $this->db->where($this->tb_payable_det . '.code', $a['sum']->code);
        $this->db->join($this->tb_account, $this->tb_account . ".code=" . $this->tb_payable_det . ".account_code", "INNER");

        $a['det'] = $this->db->get($this->tb_payable_det)->result();


        echo json_encode($a);
    }

    public function load() {

        $this->db->select(array($this->tb_sum . '.no', $this->tb_sum . '.date', $this->tb_sum . '.is_cancel', $this->tb_sum . '.ref_no', $this->tb_sum . '.journal_type', $this->tb_payable_type . '.description AS journal_type_des', $this->tb_sum . '.description AS je_des', 'narration'));
        $this->db->join($this->tb_payable_type, $this->tb_payable_type . ".code=" . $this->tb_sum . ".journal_type", "LEFT");
        $this->db->where($this->tb_sum . '.no', $_POST['id']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('cl', $this->sd['cl']);
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();

        if (isset($a["sum"]->no)) {
            $this->db->select(array('account_code', $this->tb_account . '.description AS acc_des', 'dr_amount', 'cr_amount', 'memo'));
            $this->db->join($this->tb_account, $this->tb_account . ".code = " . $this->tb_det . ".account_code", "INNER");
            $this->db->where($this->tb_det . ".no", $a["sum"]->no);
            $this->db->where('bc', $this->sd['branch']);
            $this->db->where('cl', $this->sd['cl']);
            $this->db->order_by($this->tb_det . ".order_no", 'ACS');
            $a['det'] = $this->db->get($this->tb_det)->result();
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
            if($this->user_permissions->is_delete('t_journal_sum')){
                $this->db->where("trans_no", $_POST['id']);
                $this->db->where("trans_code", 37);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_account_trans");

                $this->db->where("no", $_POST['id']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->limit(1);
                $this->db->update($this->tb_sum, array("is_cancel" => $this->sd['oc']));
                $this->utility->save_logger("DELETE",37,$this->max_no,$this->mod);
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

        $r_detail['duplicate'] = $_POST['is_duplicate'];
        $this->db->SELECT(array('serial_no', 'item'));
        $this->db->FROM('t_serial');
        $this->db->WHERE('t_serial.cl', $this->sd['cl']);
        $this->db->WHERE('t_serial.bc', $this->sd['branch']);
        $this->db->WHERE('t_serial.trans_type', '3');
        $this->db->WHERE('t_serial.trans_no', $_POST['qno']);
        $r_detail['serial'] = $this->db->get()->result();


        $invoice_number= $this->utility->invoice_format($_POST['qno']);
        $session_array = array(
             $this->sd['cl'],
             $this->sd['branch'],
             $invoice_number
        );
        $r_detail['session'] = $session_array;

        $no = $this->input->post('qno');
        $bc = $this->sd['branch'];

        $sql = "
            SELECT *,`m_account`.`description` FROM `t_journal_entry_det`
            INNER JOIN `m_account`
            ON `t_journal_entry_det`.`account_code`=`m_account`.`code`
            WHERE `no`='$no'AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' ORDER BY t_journal_entry_det.order_no";
        $sql2 = "
            SELECT *,t_journal_entry_sum.description as des,(`m_cluster`.`description`)AS `cluster_name`,(`m_branch`.`name`)AS `branch_name` FROM `t_journal_entry_sum`
            JOIN `m_branch` ON `t_journal_entry_sum`.`bc`=`m_branch`.`bc`
            JOIN `m_cluster` ON `t_journal_entry_sum`.`cl`=`m_cluster`.`code`
            WHERE `t_journal_entry_sum`.`no`='$no' AND t_journal_entry_sum.cl='".$this->sd['cl']."' AND t_journal_entry_sum.bc='".$this->sd['branch']."'";

//$this->sd['cl']
//        $this->sd['branch']
        $r_detail['qno'] = $_POST['qno'];
        $r_detail['jtype'] = $_POST['jtype'];
        $r_detail['jtype_desc'] = $_POST['jtype_desc'];
        $r_detail['page'] = $_POST['page'];
        $r_detail['header'] = $_POST['header'];
        $r_detail['orientation'] = $_POST['orientation'];
        $r_detail['type'] = $_POST['type'];


        $query = $this->db->query($sql);
        $query2 = $this->db->query($sql2);
//        if ($query->num_rows > 0) {
//            $r_detail['det'] = $query->result();
//        } else {
//            $r_detail['det'] = 2;
//        }
        $this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $r_detail['branch'] = $this->db->get('m_branch')->result();
        
        $r_detail['jrn_en_body'] = $query->result();
        $r_detail['otherdtl'] = $query2->result();
         $r_detail['cl'] = $cluster = $this->sd['cl'];

 $r_detail['bc'] = $this->sd['branch'];    
        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
    }


    public function get_account(){
        $this->db->select(array('code','description'));
        $this->db->where("code",$this->input->post('code'));
        $this->db->limit(1);
        $query=$this->db->get('m_account');
        if($query->num_rows() > 0){
            $data['a']=$query->result();
        }else{
            $data['a']=2;
        }
        echo json_encode($data);
    }

}
