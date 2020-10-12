<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class t_payable_invoice extends CI_Model {

    private $sd;
    private $tb_sum;
    private $tb_det;
    private $mod = '003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');
        $this->tb_sum = $this->tables->tb['t_payable_invoice_sum'];
        $this->tb_det = $this->tables->tb['t_payable_invoice_det'];
        $this->tb_payable_transe = $this->tables->tb['t_payable_invoice_transe'];
        $this->tb_check_double_entry = $this->tables->tb['t_check_double_entry'];
        $this->tb_payable_sum = $this->tables->tb['m_journal_type_sum'];
        $this->tb_payable_det = $this->tables->tb['m_journal_type_det'];
        $this->tb_branch = $this->tables->tb['s_branches'];
        $this->tb_users = $this->tables->tb['s_users'];
        $this->tb_trance = $this->tables->tb['t_account_trans'];
        $this->tb_payable_type = $this->tables->tb['m_journal_type_sum'];
        $this->tb_account = $this->tables->tb['m_account'];
        $this->max_no = $this->utility->get_max_no("t_payable_invoice_sum", "no");
    }

    public function base_details() {
        $this->load->model('m_option_setup');

        $a['grid'] = $this->m_option_setup->get_grid();
        $a['option'] = $this->m_option_setup->get_option();
        $a['max_no'] = $this->max_no();
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

    public function validation() {
        $status = 1;

        $is_payble_type = $this->validation->check_is_payble($_POST['journal_type']);
        if ($is_payble_type != 1) {
            return $is_payble_type;
        }
        $is_account = $this->validation->check_is_account2("0_");
        if ($is_account != 1) {
            return $is_account;
        }
        $is_account2 = $this->validation->check_is_account3($_POST['account']);
        if ($is_account2 != 1) {
            return $is_account2;
        }
        /*$account_update = $this->account_update(0);
        if ($account_update != 1) {
            return "Invalid account entries";
        }*/
        return $status;
    }

    public function save() {
        $this->db->trans_begin();
        error_reporting(E_ALL);

        function exceptionThrower($type, $errMsg, $errFile, $errLine) {
            throw new Exception($errLine);
        }
        set_error_handler('exceptionThrower');
        try {
            $validation = $this->validation();
            if ($validation == 1) {
                

                $a = array(
                    "date" => $_POST['date'],
                    "ref_no" => $_POST['ref_no'],
                    "payable_type" => $_POST['journal_type'],
                    "payable_account" => $_POST['account'],
                    "payable_date" => $_POST['payable_date'],
                    "description" => $_POST['description'],
                    "narration" => $_POST['narration'],
                    "total" => $_POST['total'],
                    'bc' => $this->sd['branch'],
                    'cl' => $this->sd['cl'],
                    "oc" => $this->sd['oc']
                );

                $a1 = $a2 = array();
                $a["no"] = $this->max_no();

                for ($i = 0; $i < $_POST['grid_row']; $i++) {
                    if ($_POST['h_' . $i] != "" && $_POST['h_' . $i] != "0") {
                        $a1[] = array(
                            "no" => $a["no"],
                            "account_code" => $_POST['h_' . $i],
                            "amount" => $_POST['1_' . $i],
                            "bc" => $this->sd['branch'],
                            "cl" => $this->sd['cl'],
                        );
                    }
                }
                $a2[] = array(
                    "no" => $a["no"],
                    "date" => $_POST['date'],
                    "dr_transe_code" => '35',
                    "dr_transe_no" => $a["no"],
                    "dr_amount" => 0,
                    "cr_transe_code" => '35',
                    "cr_transe_no" => $a["no"],
                    "cr_amount" => $_POST['total'],
                    "bc" => $this->sd['branch'],
                    "cl" => $this->sd['cl'],
                );

               

                if ($_POST['hid'] == "" || $_POST['hid'] == 0) {
                    if($this->user_permissions->is_add('t_payable_invoice')){
                        $account_update = $this->account_update(0);
                        if ($account_update == 1) {
                            $a["no"] = $this->max_no();
                            $this->db->insert($this->tb_sum, $a);
                            if (count($a1)) {
                                $this->db->insert_batch($this->tb_det, $a1);
                            }
                            if (count($a2)) {
                                $this->db->insert_batch($this->tb_payable_transe, $a2);
                            }
                            $this->account_update(1);
                            $this->utility->save_logger("SAVE", 35, $this->max_no, $this->mod);
                            echo $this->db->trans_commit()."@".$this->max_no;
                        }else{
                            $this->db->trans_commit();
                            echo "invalid account entries";
                        }
                    }else{
                        $this->db->trans_commit();
                        echo "No permission to save records";
                    }    
                } else {
                    if($this->user_permissions->is_edit('t_payable_invoice')){
                        $account_update = $this->account_update(0);
                        if ($account_update == 1) {
                            $this->db->where("no", $_POST['hid']);
                            $this->db->where('cl', $this->sd['cl']);
                            $this->db->where('bc', $this->sd['branch']);
                            $this->db->limit(1);
                            $this->db->update($this->tb_sum, $a);
                            $a['no'] = $_POST['id'];
                            $this->set_delete();
                            if (count($a1)) {
                                $this->db->insert_batch($this->tb_det, $a1);
                            }
                            if (count($a2)) {
                                $this->db->insert_batch($this->tb_payable_transe, $a2);
                            }
                            $this->account_update(1);
                            $this->utility->save_logger("UPDATE", 35, $this->max_no, $this->mod);
                            echo $this->db->trans_commit();
                         }else{
                            $this->db->trans_commit();
                            echo "invalid account entries";
                        }
                    }else{
                        $this->db->trans_commit();
                        echo "No permission to edit records ";
                    }      
                }
            } else {
                $this->db->trans_commit();
                echo $validation;
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo "Operation fail please contact admin";
        }
    }

    public function account_update($condition) {

        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 35);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");


        if($_POST['hid']=="0"||$_POST['hid']==""){
            
        }else{
            if($condition=="1"){
                $this->db->where("trans_no", $this->max_no);
                $this->db->where("trans_code", 35);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_account_trans");
            }
        }


        $config = array(
            "ddate" => $_POST['date'],
            "trans_code" => '35',
            "trans_no" => $_POST['id'],
            "op_acc" => 0,
            "reconcile" => 0,
            "cheque_no" => 0,
            "narration" => "",
            "ref_no" => $_POST['ref_no']
        );

        $desdr = "Payable : " . $_POST['description'];
        $descr = "Expense : " . $_POST['description'];
        $this->load->model('account');
        $this->account->set_data($config);


        $this->account->set_value2($desdr, $_POST['total'], "dr", $_POST['account'], $condition);


        for ($i = 0; $i < $_POST['grid_row']; $i++) {
            if ($_POST['h_' . $i] != "" && $_POST['h_' . $i] != "0") {
                if ($_POST['1_' . $i] > 0) {
                    $this->account->set_value2($descr, $_POST['1_' . $i], "cr", $_POST['h_' . $i], $condition);
                }
            }
        }

        if ($condition == 0) {

            $query = $this->db->query("
             SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
             FROM `t_check_double_entry` t
             LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
             WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='3'  AND t.`trans_no` ='" . $this->max_no . "' AND 
             a.`is_control_acc`='0'");

            if ($query->row()->ok == "0") {
                $this->db->where("trans_no", $this->max_no);
                $this->db->where("trans_code", 35);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_check_double_entry");
                return "0";
            } else {
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

        $this->db->select(array($this->tb_sum . '.no', $this->tb_sum . '.date', $this->tb_sum . '.is_cancel', $this->tb_sum . '.ref_no', $this->tb_sum . '.payable_type', $this->tb_payable_type . '.description AS payable_type_des', $this->tb_sum . '.description AS payable_des', 'narration', 'payable_account', $this->tb_account . '.description AS payble_account_des'));
        $this->db->join($this->tb_payable_type, $this->tb_payable_type . ".code=" . $this->tb_sum . ".payable_type", "LEFT");
        $this->db->join($this->tb_account, $this->tb_account . ".code = " . $this->tb_sum . ".payable_account", "INNER");
        $this->db->where($this->tb_sum . '.no', $_POST['id']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('cl', $this->sd['cl']);
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();

        if (isset($a["sum"]->no)) {
            $this->db->select(array('account_code', $this->tb_account . '.description AS acc_des', 'amount'));
            $this->db->join($this->tb_account, $this->tb_account . ".code = " . $this->tb_det . ".account_code", "INNER");
            $this->db->where($this->tb_det . ".no", $a["sum"]->no);
            $this->db->where('bc', $this->sd['branch']);
            $this->db->where('cl', $this->sd['cl']);
            $this->db->order_by($this->tb_det . ".auto_num", 'ACS');
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
            if($this->user_permissions->is_delete('t_payable_invoice')){
                $this->db->where("trans_no", $_POST['id']);
                $this->db->where("trans_code", '35');
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_account_trans");

                $this->db->where('no', $_POST['id']);
                $this->db->where('dr_transe_no', $_POST['id']);
                $this->db->where('cr_transe_no', $_POST['id']);
                $this->db->where('dr_transe_code', '35');
                $this->db->where('cr_transe_code', '35');
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete($this->tb_payable_transe);

                $this->db->where("no", $_POST['id']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->limit(1);
                $this->db->update($this->tb_sum, array("is_cancel" => $this->sd['oc']));
                $this->utility->save_logger("DELETE", 35, $this->max_no, $this->mod);
                echo $this->db->trans_commit();
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }    
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo "Operation fail please contact admin";
        }
    }

    public function set_delete() {


        $this->db->where('no', $_POST['hid']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete($this->tb_det);

        $this->db->where('no', $_POST['hid']);
        $this->db->where('dr_transe_no', $_POST['hid']);
        $this->db->where('cr_transe_no', $_POST['hid']);
        $this->db->where('dr_transe_code', '35');
        $this->db->where('cr_transe_code', '35');
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete($this->tb_payable_transe);
    }

    public function get_account() {
        $this->db->select(array('code', 'description'));
        $this->db->where("code", $this->input->post('code'));
        $this->db->where('is_control_acc', '0');
        $this->db->limit(1);
        $query = $this->db->get('m_account');
        if ($query->num_rows() > 0) {
            $data['a'] = $query->result();
        } else {
            $data['a'] = 2;
        }
        echo json_encode($data);
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
        $pblt = $_POST['pblt'];
        $clus = $this->sd['cl'];

        $sql2 = "SELECT `pis`.`payable_type`
       ,`mjs`.`description`AS`payable_dec`
       ,`pis`.`payable_account`
       ,`m`.`description` AS `payable_account_dec`
       ,`pis`.`date`
       ,`pis`.`description` AS `payable_desc`
       ,`pis`.`narration`
       ,`pid`.`account_code` AS `income_account`
       ,`mm`.`description`
       ,`pid`.`amount` AS `amount` 
       ,`mc`.`code` AS `mccode` 
       ,`mc`.`description` AS `mdescrp` 
       ,`mb`.`bc`
       ,`mb`.`name` AS `bname`
       ,`pis`.`no`
       ,`pis`.`ref_no`
       FROM `t_payable_invoice_sum` `pis`
       INNER JOIN `m_journal_type_sum` `mjs` ON `mjs`.`code`=`pis`.`payable_type`
       INNER JOIN `m_account` `m` ON `pis`.`payable_account`=`m`.`code`
       INNER JOIN `t_payable_invoice_det` `pid` ON `pis`.`no`=`pid`.`no`
       INNER JOIN m_account mm ON pid.account_code=mm.code
       INNER JOIN m_cluster mc ON pis.cl=mc.code
       INNER JOIN m_branch mb ON pis.bc=mb.bc
WHERE (`pis`.`no`='$no') AND (`mjs`.`payble_type`='1') AND (`pis`.`bc`='$bc')AND (`pid`.`cl`='$clus')";

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

        $r_detail['opn_bl_head'] = $query->result();
        $r_detail['jrn_en_body'] = $query2->result();
        $r_detail['cl'] = $cluster = $this->sd['cl'];

        $r_detail['bc'] = $this->sd['branch'];
//      echo $_POST['by'];
//         echo '<pre>'.print_r($r_detail,true).'</pre>';
//  		exit;

        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
    }

}
