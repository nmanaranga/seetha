<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class t_pettycash extends CI_Model {

    private $sd;
    private $tb_sum;
    private $tb_det;
    private $mod = '003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');

        $this->tb_sum = $this->tables->tb['t_petty_cash_sum'];
        $this->tb_det = $this->tables->tb['t_petty_cash_det'];
        $this->tb_check_double_entry = $this->tables->tb['t_check_double_entry'];
        $this->tb_payable_sum = $this->tables->tb['m_journal_type_sum'];
        $this->tb_payable_det = $this->tables->tb['m_journal_type_det'];
        $this->tb_branch = $this->tables->tb['s_branches'];
        $this->tb_users = $this->tables->tb['s_users'];
        $this->tb_trance = $this->tables->tb['t_account_trans'];
        $this->tb_payable_type = $this->tables->tb['m_journal_type_sum'];
        $this->tb_account = $this->tables->tb['m_account'];
        $this->tb_default = $this->tables->tb['m_default_account'];
        $this->max_no = $this->utility->get_max_no("t_petty_cash_sum", "no");
    }

    public function base_details() {
        $this->load->model('m_option_setup');
        $a['grid'] = $this->m_option_setup->get_grid();
        $a['option'] = $this->m_option_setup->get_option();
        $a['max_no'] = $this->utility->get_max_no("t_petty_cash_sum", "no");
        $a['sd'] = $this->sd;
        $this->load->model('r_groups');
        $a['groups'] = $this->r_groups->select();
        $this->load->model('r_sales_category');
        $a['sales_category'] = $this->r_sales_category->select();
        return $a;
    }

    private function max_no() {
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->select_max("no");

        return $this->db->get($this->tb_sum)->first_row()->no + 1;
    }

    public function load_pettycash_account() {
        $sql = "SELECT
                `m_default_account`.`acc_code`
                , `m_default_account`.`description`
            FROM
                `m_default_account`
                WHERE `code`='petty_cash'";
        $qry = $this->db->query($sql);
        $a['sum'] = $qry->first_row();
        echo json_encode($a);
    }

    public function get_max() {
        $a['max_no'] = $this->max_no();
        echo json_encode($a);
    }

    public function validation() {
        $status = 1;
        $this->max_no = $this->utility->get_max_no("t_petty_cash_sum", "no");
        $is_account2 = $this->validation->check_is_account3($_POST['account']);
        if ($is_account2 != 1) {
            return $is_account2;
        }
        $is_account = $this->validation->check_is_account2("0_");
        if ($is_account != 1) {
            return $is_account;
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
            throw new Exception($errMsg);
        }

        set_error_handler('exceptionThrower');
        try {
            $validation = $this->validation();

            if ($validation == 1) {
                $a = array(
                "date" => $_POST['date'],
                "ref_no" => $_POST['ref_no'],
                "pettycash_account" => $_POST['saccount'],
                "description" => $_POST['description'],
                "narration" => $_POST['narration'],
                "total" => $_POST['total'],
                'bc' => $this->sd['branch'],
                'cl' => $this->sd['cl'],
                "oc" => $this->sd['oc'],
                "group_sales_id" => $_POST['groups'],
                "category_id" => $_POST['sales_category'],
                "no" => $this->max_no
                );

                //$a1 = $a2 = array();

                for ($i = 0; $i < $_POST['grid_row']; $i++) {
                    if ($_POST['h_' . $i] != "" && $_POST['h_' . $i] != "0" && $_POST['1_' . $i] > 0 && $_POST['1_' . $i] != "" ) {
                        $a1[] = array(
                            "no" => $this->max_no,
                            "account_code" => $_POST['h_' . $i],
                            "amount" => $_POST['1_' . $i],
                            "description" => $_POST['2_' . $i],
                            "bc" => $this->sd['branch'],
                            "cl" => $this->sd['cl'],
                        );
                    }
                }

                if ($_POST['hid'] == "" || $_POST['hid'] == 0) {
                    if($this->user_permissions->is_add('t_pettycash')){
                        $account_update = $this->account_update(0);
                        if ($account_update == 1) {
                            $this->db->insert($this->tb_sum, $a);
                            if (count($a1)) {
                                $this->db->insert_batch($this->tb_det, $a1);
                            }
                            $this->account_update(1);
                            $this->utility->save_logger("SAVE", 38, $this->max_no, $this->mod);
                            echo $this->db->trans_commit();
                        }else{
                            $this->db->trans_commit();
                            echo "invalid account entries";
                        }
                    }else{
                        $this->db->trans_commit();
                        echo "No permission to save records";
                    }    
                } else {
                    if($this->user_permissions->is_edit('t_pettycash')){
                        $account_update = $this->account_update(0);
                        if ($account_update == 1) {
                            $this->db->where("no", $_POST['hid']);
                            $this->db->where('cl', $this->sd['cl']);
                            $this->db->where('bc', $this->sd['branch']);
                            $this->db->limit(1);
                            $this->db->update($this->tb_sum, $a);
                            
                            $this->set_delete();
                            if (count($a1)) {
                                $this->db->insert_batch($this->tb_det, $a1);
                            }
                            $this->account_update(1);
                            $this->utility->save_logger("UPDATE", 38, $this->max_no, $this->mod);
                            echo $this->db->trans_commit();
                        }else{
                            $this->db->trans_commit();
                            echo "invalid account entries";
                        }
                    }else{
                        $this->db->trans_commit();
                        echo "No permission to edit records";
                    }      
                }
            } else {
                $this->db->trans_commit();
                echo $validation;
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin";
        }
    }

    public function account_update($condition) {
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", '38');
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete($this->tb_check_double_entry);

        if($_POST['hid']=="0"||$_POST['hid']==""){
            
        }else{
            if($condition=="1"){
                $this->db->where("trans_no", $this->max_no);
                $this->db->where("trans_code", 38);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_account_trans");
            }
        }

        $config = array(
            "ddate" => $_POST['date'],
            "trans_code" => '38',
            "trans_no" => $this->max_no,
            "op_acc" => 0,
            "reconcile" => 0,
            "cheque_no" => 0,
            "narration" => "",
            "ref_no" => $_POST['ref_no']
        );

        
        $descr = "petty cash : " . $_POST['description'];
        $this->load->model('account');
        $this->account->set_data($config);

        $this->account->set_value2($descr, $_POST['total'], "cr", $_POST['saccount'], $condition);

        for ($i = 0; $i < $_POST['grid_row']; $i++) {
            if ($_POST['h_' . $i] != "" && $_POST['h_' . $i] != "0") {
                if ($_POST['1_' . $i] > 0) {
                    if($_POST['2_'.$i]!=""){
                        $desdr = "cus : " . $_POST['2_'.$i];
                    }else{
                        $desdr = "cus : " . $_POST['description'];
                    }
                    $this->account->set_value2($desdr, $_POST['1_' . $i], "dr", $_POST['h_' . $i], $condition);
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
                $this->db->where("trans_code", 38);
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

        $this->db->select(array($this->tb_sum . '.no', $this->tb_sum . '.date', $this->tb_sum . '.is_cancel', $this->tb_sum . '.ref_no', 'narration', $this->tb_sum . '.description AS pdes', 'pettycash_account', $this->tb_account . '.description AS pettycase_des','group_sales_id','category_id'));
        $this->db->join($this->tb_account, $this->tb_account . ".code = " . $this->tb_sum . ".pettycash_account", "INNER");
        $this->db->where($this->tb_sum . '.no', $_POST['id']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('cl', $this->sd['cl']);
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();

        if (isset($a["sum"]->no)) {
            $this->db->select(array('account_code', $this->tb_account . '.description AS acc_des', 'amount', $this->tb_det . '.description'));
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
            if($this->user_permissions->is_delete('t_pettycash')){
                $this->db->where("trans_no", $_POST['id']);
                $this->db->where("trans_code", '38');
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_account_trans");

                $this->db->where("no", $_POST['id']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->limit(1);
                $this->db->update($this->tb_sum, array("is_cancel" => $this->sd['oc']));
                $this->utility->save_logger("DELETE", 38, $this->max_no, $this->mod);
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
    }

    public function auto_com_pettycash() {
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('m_account' . '.description', $_GET['q']);
        $query = $this->db->select(array('code', 'm_account' . '.description'))->get('m_account');
        $abc = "";
        foreach ($query->result() as $r) {
            $abc .= $r->code . "|" . $r->description;
            $abc .= "\n";
        }
        echo $abc;
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


        $num=$_POST['price'];
        
         $this->utility->num_in_letter($num);
        
        $r_detail['brnch']=convertNum($num);;
  


        $no = $_POST['qno'];
        $bc = $this->sd['branch'];
        $pblt = $_POST['pblt'];
        $clus = $this->sd['cl'];

        $sql2 = "SELECT  `ps`.`no`
       ,`ps`.`ref_no`
       ,`ps`.`date`
       ,`ps`.`pettycash_account`
       ,`m`.`description`AS`petty_csh_acc`
       ,`ps`.`description` AS `petty_csh_desc`
       ,`ps`.`narration`
       ,`ps`.`cl`
       ,`mc`.`description`AS `cluster`
       ,`ps`.`bc`
       ,`mb`.`name`
       ,`pcd`.`account_code`
       ,`pcd`.`description`
       ,`pcd`.`amount`
       ,`mm`.`description`AS`account`
       ,`ps`.`group_sales_id`
       ,`rg`.`name` AS `gname`              
       FROM `t_petty_cash_sum` `ps`       
       INNER JOIN `m_account` `m` ON `ps`.`pettycash_account`=`m`.`code`
       INNER JOIN `m_cluster` `mc` ON `ps`.`cl`=`mc`.`code`
       INNER JOIN `m_branch` `mb` ON `ps`.`bc`=`mb`.`bc`
       INNER JOIN `t_petty_cash_det` `pcd` ON `ps`.`no`=`pcd`.`no`
       INNER JOIN `m_account` `mm` ON `pcd`.`account_code`=`mm`.`code`
       INNER JOIN `r_groups` `rg` ON `ps`.`group_sales_id`=`rg`.`code`                
       WHERE (`ps`.`no`='$no')  AND (`ps`.`bc`='$bc')AND (`ps`.`cl`='$clus')";

//$this->sd['cl']
//        $this->sd['branch']
        $r_detail['qno'] = $_POST['qno'];
        $r_detail['po_dt'] = $_POST['po_dt'];
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
//          exit;

        $code = $_POST['qno']; 

        $sql = "SELECT 
        DATE,
        total,
        description,
        pettycash_account 
        FROM
        t_petty_cash_sum 
        WHERE bc = '$bc' 
        AND cl = '$clus' 
        AND NO = '$code'";

        $r_detail['sum'] = $this->db->query($sql)->result();

        $sql2 = "SELECT 
        ptc.`account_code`,
        m.`description` as m_des,
        ptc.`description`,
        ptc.`amount`
        FROM
        t_petty_cash_det ptc
        JOIN m_account m 
        ON ptc.`account_code` = m.`code`
        WHERE bc = '$bc' 
        AND cl = '$clus' 
        AND NO = '$code' ";

        $r_detail['det'] = $this->db->query($sql2)->result();
        

        $this->db->select(array('t_petty_cash_sum.oc', 's_users.discription','action_date'));
        $this->db->from('t_petty_cash_sum');
        $this->db->join('s_users', 't_petty_cash_sum.oc=s_users.cCode');
        $this->db->where('t_petty_cash_sum.cl', $this->sd['cl']);
        $this->db->where('t_petty_cash_sum.bc', $this->sd['branch']);
        $this->db->where('t_petty_cash_sum.no', $_POST['qno']);
        $r_detail['user'] = $this->db->get()->result();

        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
    }

}
