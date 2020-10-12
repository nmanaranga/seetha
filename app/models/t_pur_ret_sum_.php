<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class t_pur_ret_sum extends CI_Model {

    private $sd;
    private $mtb;
    private $max_no;
    private $debit_max_no;
    private $mod = '003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->mtb = $this->tables->tb['t_pur_ret_sum'];
        $this->load->model('m_stores');
    }

    public function base_details() {
        $a['stores'] = $this->m_stores->select();
        $a['nno'] = $this->utility->get_max_no("t_pur_ret_sum", "nno");
        $a["drn_no"] = $this->get_drn_no();
        return $a;
    }

    public function get_debit_max_no($table_name, $field_name) {
        if (isset($_POST['hid'])) {
            if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                $this->db->select_max($field_name);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                return $this->db->get($table_name)->first_row()->$field_name + 1;
            } else {
                return $_POST['drn_no'];
            }
        } else {
            $this->db->select_max($field_name);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            return $this->db->get($table_name)->first_row()->$field_name + 1;
        }
    }

    public function validation() {
        $status = 1;

        $check_is_delete = $this->validation->check_is_cancel($_POST['id'], 't_pur_ret_sum');
        if ($check_is_delete != 1) {
            return "Purchase return already deleted";
        }
        $supplier_validation = $this->validation->check_is_supplier($_POST['supplier_id']);
        if ($supplier_validation != 1) {
            return "Please check supplier";
        }
        $serial_validation_status = $this->validation->serial_update('0_', '2_');
        if ($serial_validation_status != 1) {
            return $serial_validation_status;
        }
        return $status;
    }

    public function validation2() {
        $status = 1;

        $check_return_qty_validation = $this->utility->check_return_qty('0_', '2_', $_POST['grnno'], '6');
        if ($check_return_qty_validation != 1) {
            return $check_return_qty_validation;
        }
        $chk_item_with_grn_validation = $this->utility->check_item_with_grn_no($_POST['grnno'], '0_');
        if ($chk_item_with_grn_validation != 1) {
            return $chk_item_with_grn_validation;
        }
        $check_item_price_validation = $this->utility->check_item_price('0_', $_POST['grnno'], '6', '4_');
        if ($check_item_price_validation != 1) {
            return $check_item_price_validation;
        }
        return $status;
    }

    public function save() {
        $this->max_no = $this->utility->get_max_no("t_pur_ret_sum", "nno");
        $this->debit_max_no = $this->get_debit_max_no("t_debit_note", "nno");

        $validation_status = $this->validation();
        $validation_status2 = $this->validation2();

        if ($validation_status == 1) {

            if ($_POST['grnno'] == "" || $_POST['grnno'] == 0) {
                $chk_item_store_validation = $this->validation->check_item_with_store($_POST['stores'], '0_');
                if ($chk_item_store_validation == 1) {


                    $this->db->trans_start();

                    $_POST['cl'] = $this->sd['cl'];
                    $_POST['branch'] = $this->sd['branch'];
                    $_POST['oc'] = $this->sd['oc'];

                    $this->serial_save();

                    $t_pur_ret_sum = array(
                        "cl" => $_POST['cl'],
                        "bc" => $_POST['branch'],
                        "oc" => $_POST['oc'],
                        "nno" => $this->max_no,
                        "ddate" => $_POST['date'],
                        "ref_no" => $_POST['ref_no'],
                        "supp_id" => $_POST['supplier_id'],
                        "grn_no" => $_POST['grnno'],
                        "drn_no" => $_POST['drn_no'],
                        "memo" => $_POST['memo'],
                        "store" => $_POST['stores'],
                        "gross_amount" => $_POST['gross_amount'],
                        "other" => $_POST['total'],
                        "discount" => $_POST['total_discount'],
                        "net_amount" => $_POST['net_amount'],
                        "post" => "",
                        "post_by" => "",
                        "post_date" => ""
                    );

                    for ($x = 0; $x < 25; $x++) {
                        if (isset($_POST['0_' . $x], $_POST['2_' . $x], $_POST['4_' . $x])) {
                            if ($_POST['0_' . $x] != "" && $_POST['2_' . $x] != "" && $_POST['4_' . $x] != "") {

                                $t_pur_ret_det[] = array(
                                    "cl" => $_POST['cl'],
                                    "bc" => $_POST['branch'],
                                    "nno" => $this->max_no,
                                    "code" => $_POST['0_' . $x],
                                    "qty" => $_POST['2_' . $x],
                                    "price" => $_POST['4_' . $x],
                                    "discountp" => $_POST['5_' . $x],
                                    "discount" => $_POST['6_' . $x],
                                    "batch_no" => $_POST['3_' . $x],
                                    "reason" => $_POST['7_' . $x]
                                );
                            }
                        }
                    }

                    for ($x = 0; $x < 25; $x++) {
                        if (isset($_POST['00_' . $x], $_POST['11_' . $x], $_POST['22_' . $x])) {
                            if ($_POST['00_' . $x] != "" && $_POST['11_' . $x] != "" && $_POST['22_' . $x] != "") {

                                $t_pur_ret_additional_item[] = array(
                                    "cl" => $_POST['cl'],
                                    "bc" => $_POST['branch'],
                                    "nno" => $this->max_no,
                                    "type" => $_POST['00_' . $x],
                                    "rate_p" => $_POST['11_' . $x],
                                    "amount" => $_POST['22_' . $x]
                                );
                            }
                        }
                    }



                    for ($x = 0; $x < 25; $x++) {
                        if (isset($_POST['0_' . $x])) {
                            if ($_POST['0_' . $x] != "") {

                                $this->db->select(array("purchase_price", "min_price", "max_price"));
                                $this->db->where("code", $_POST['0_' . $x]);
                                $result = $this->db->get("m_item")->result();

                                foreach ($result as $row) {
                                    $cost = $avg_price = $row->purchase_price;
                                    $sales_price = $row->max_price;
                                    $last_sales_price = $row->min_price;
                                }

                                $t_item_movement[] = array(
                                    "cl" => $this->sd['cl'],
                                    "bc" => $this->sd['branch'],
                                    "item" => $_POST['0_' . $x],
                                    "trans_code" => 10,
                                    "trans_no" => $this->max_no,
                                    "ddate" => $_POST['date'],
                                    "qty_in" => 0,
                                    "qty_out" => $_POST['2_' . $x],
                                    "store_code" => $_POST['stores'],
                                    "avg_price" => $avg_price,
                                    "batch_no" => $_POST['3_' . $x],
                                    "sales_price" => $sales_price,
                                    "last_sales_price" => $last_sales_price,
                                    "cost" => $cost
                                );
                            }
                        }
                    }

                    $t_debit_note = array(
                        "cl" => $this->sd['cl'],
                        "bc" => $this->sd['branch'],
                        "nno" => $this->debit_max_no,
                        "ddate" => $_POST['date'],
                        "ref_no" => $_POST['ref_no'],
                        "memo" => "PURCHASE RETURN [" . $this->max_no . "]",
                        "is_customer" => 0,
                        "code" => $_POST['supplier_id'],
                        "acc_code" => $this->utility->get_account_code('stock'),
                        "amount" => $_POST['net_amount'],
                        "oc" => $this->sd['oc'],
                        "post" => "",
                        "post_by" => "",
                        "post_date" => "",
                        "is_cancel" => 0,
                    );


                    if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                        $this->load->model('trans_settlement');
                        $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['supplier_id'], $_POST['date'], 18, $this->debit_max_no, 18, $this->debit_max_no, $_POST['net_amount'], "0");
                        $this->db->insert($this->mtb, $t_pur_ret_sum);
                        $this->db->insert("t_debit_note", $t_debit_note);

                        if (isset($t_pur_ret_additional_item)) {
                            if (count($t_pur_ret_additional_item)) {
                                $this->db->insert_batch("t_pur_ret_additional_item", $t_pur_ret_additional_item);
                            }
                        }
                        if (count($t_pur_ret_det)) {
                            $this->db->insert_batch("t_pur_ret_det", $t_pur_ret_det);
                        }
                        if (count($t_item_movement)) {
                            $this->db->insert_batch("t_item_movement", $t_item_movement);
                        }
                    } else {
                        $this->set_delete();

                        $this->load->model('trans_settlement');
                        $this->trans_settlement->delete_settlement("t_debit_note_trans", 18, $this->debit_max_no);
                        $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['supplier_id'], $_POST['date'], 18, $this->debit_max_no, 18, $this->debit_max_no, $_POST['net_amount'], "0");

                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where('nno', $_POST['hid']);
                        $this->db->update($this->mtb, $t_pur_ret_sum);

                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where('nno', $_POST['drn_no']);
                        $this->db->update("t_debit_note", $t_debit_note);

                        if (isset($t_pur_ret_additional_item)) {
                            if (count($t_pur_ret_additional_item)) {
                                $this->db->insert_batch("t_pur_ret_additional_item", $t_pur_ret_additional_item);
                            }
                        }
                        if (count($t_pur_ret_det)) {
                            $this->db->insert_batch("t_pur_ret_det", $t_pur_ret_det);
                        }
                        if (count($t_item_movement)) {
                            $this->db->insert_batch("t_item_movement", $t_item_movement);
                        }
                    }
                    echo $this->db->trans_complete();
                } else {
                    echo "6";
                }
            } else {

                if ($validation_status2 == 1) {

                    $this->max_no = $this->utility->get_max_no("t_pur_ret_sum", "nno");
                    $this->debit_max_no = $this->get_debit_max_no("t_debit_note", "nno");


                    $this->db->trans_start();

                    $this->serial_save();
                    $_POST['cl'] = $this->sd['cl'];
                    $_POST['branch'] = $this->sd['branch'];
                    $_POST['oc'] = $this->sd['oc'];

                    $t_pur_ret_sum = array(
                        "cl" => $_POST['cl'],
                        "bc" => $_POST['branch'],
                        "oc" => $_POST['oc'],
                        "nno" => $this->max_no,
                        "ddate" => $_POST['date'],
                        "ref_no" => $_POST['ref_no'],
                        "supp_id" => $_POST['supplier_id'],
                        "grn_no" => $_POST['grnno'],
                        "drn_no" => $_POST['drn_no'],
                        "memo" => $_POST['memo'],
                        "store" => $_POST['stores'],
                        "gross_amount" => $_POST['gross_amount'],
                        "other" => $_POST['total'],
                        "discount" => $_POST['total_discount'],
                        "net_amount" => $_POST['net_amount'],
                        "post" => "",
                        "post_by" => "",
                        "post_date" => ""
                    );



                    for ($x = 0; $x < 25; $x++) {
                        if (isset($_POST['0_' . $x], $_POST['2_' . $x], $_POST['4_' . $x])) {
                            if ($_POST['0_' . $x] != "" && $_POST['2_' . $x] != "" && $_POST['4_' . $x] != "") {

                                $t_pur_ret_det[] = array(
                                    "cl" => $_POST['cl'],
                                    "bc" => $_POST['branch'],
                                    "nno" => $this->max_no,
                                    "code" => $_POST['0_' . $x],
                                    "qty" => $_POST['2_' . $x],
                                    "price" => $_POST['4_' . $x],
                                    "discountp" => $_POST['5_' . $x],
                                    "discount" => $_POST['6_' . $x],
                                    "batch_no" => $_POST['3_' . $x],
                                    "reason" => $_POST['7_' . $x]
                                );
                            }
                        }
                    }

                    for ($x = 0; $x < 25; $x++) {
                        if (isset($_POST['00_' . $x], $_POST['11_' . $x], $_POST['22_' . $x])) {
                            if ($_POST['00_' . $x] != "" && $_POST['11_' . $x] != "" && $_POST['22_' . $x] != "") {

                                $t_pur_ret_additional_item[] = array(
                                    "cl" => $_POST['cl'],
                                    "bc" => $_POST['branch'],
                                    "nno" => $this->max_no,
                                    "type" => $_POST['00_' . $x],
                                    "rate_p" => $_POST['11_' . $x],
                                    "amount" => $_POST['22_' . $x]
                                );
                            }
                        }
                    }

                    $wordChunks = explode(",", $_POST['srls']);
                    $execute = 0;

                    for ($x = 0; $x < 25; $x++) {
                        if (isset($_POST['0_' . $x])) {
                            if ($_POST['0_' . $x] != "") {
                                $this->db->select(array("purchase_price", "min_price", "max_price"));
                                $this->db->where("code", $_POST['0_' . $x]);
                                $result = $this->db->get("m_item")->result();

                                foreach ($result as $row) {
                                    $cost = $avg_price = $row->purchase_price;
                                    $sales_price = $row->max_price;
                                    $last_sales_price = $row->min_price;
                                }

                                $t_item_movement[] = array(
                                    "cl" => $this->sd['cl'],
                                    "bc" => $this->sd['branch'],
                                    "item" => $_POST['0_' . $x],
                                    "trans_code" => 10,
                                    "trans_no" => $this->max_no,
                                    "ddate" => $_POST['date'],
                                    "qty_in" => 0,
                                    "qty_out" => $_POST['2_' . $x],
                                    "store_code" => $_POST['stores'],
                                    "avg_price" => $avg_price,
                                    "batch_no" => $_POST['3_' . $x],
                                    "sales_price" => $sales_price,
                                    "last_sales_price" => $last_sales_price,
                                    "cost" => $cost
                                );
                            }
                        }
                    }

                    $t_debit_note = array(
                        "cl" => $this->sd['cl'],
                        "bc" => $this->sd['branch'],
                        "nno" => $this->debit_max_no,
                        "ddate" => $_POST['date'],
                        "ref_no" => $_POST['ref_no'],
                        "memo" => "PURCHASE RETURN [" . $this->max_no . "]",
                        "is_customer" => 0,
                        "code" => $_POST['supplier_id'],
                        "acc_code" => $this->utility->get_account_code('stock'),
                        "amount" => $_POST['net_amount'],
                        "oc" => $this->sd['oc'],
                        "post" => "",
                        "post_by" => "",
                        "post_date" => "",
                        "is_cancel" => 0
                    );


                    if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                        $this->load->model('trans_settlement');
                        $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['supplier_id'], $_POST['date'], 18, $this->debit_max_no, 18, $this->debit_max_no, $_POST['net_amount'], "0");

                        $this->db->insert($this->mtb, $t_pur_ret_sum);
                        $this->db->insert("t_debit_note", $t_debit_note);
                        if (isset($t_pur_ret_additional_item)) {
                            if (count($t_pur_ret_additional_item)) {
                                $this->db->insert_batch("t_pur_ret_additional_item", $t_pur_ret_additional_item);
                            }
                        }
                        if (count($t_pur_ret_det)) {
                            $this->db->insert_batch("t_pur_ret_det", $t_pur_ret_det);
                        }
                        if (count($t_item_movement)) {
                            $this->db->insert_batch("t_item_movement", $t_item_movement);
                        }
                    } else {
                        $this->set_delete();

                        $this->load->model('trans_settlement');
                        $this->trans_settlement->delete_settlement("t_debit_note_trans", 18, $this->debit_max_no);
                        $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['supplier_id'], $_POST['date'], 18, $this->debit_max_no, 18, $this->debit_max_no, $_POST['net_amount'], "0");

                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where('nno', $_POST['hid']);
                        $this->db->update($this->mtb, $t_pur_ret_sum);

                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where('nno', $_POST['drn_no']);
                        $this->db->update("t_debit_note", $t_debit_note);

                        if (isset($t_pur_ret_additional_item)) {
                            if (count($t_pur_ret_additional_item)) {
                                $this->db->insert_batch("t_pur_ret_additional_item", $t_pur_ret_additional_item);
                            }
                        }
                        if (count($t_pur_ret_det)) {
                            $this->db->insert_batch("t_pur_ret_det", $t_pur_ret_det);
                        }
                        if (count($t_item_movement)) {
                            $this->db->insert_batch("t_item_movement", $t_item_movement);
                        }
                    }
                    echo $this->db->trans_complete();
                } else {
                    echo $validation_status2;
                }
            }
        } else {
            echo $validation_status;
        }
    }

    public function get_invoice() {

        $cl = $this->sd['cl'];
        $bc = $this->sd['branch'];
        $trans_no = $_POST['code'];

        $sql = "SELECT  s.`supp_id`, m.`name` ,
                s.`store` , 
                t.`description` AS store_name, 
                s.`gross_amount`,
                s.`is_cancel`  
        FROM `t_grn_sum` s 
        JOIN `m_supplier` m ON m.`code` = s.`supp_id` 
        JOIN `m_stores` t ON t.`code` = s.`store` 
        WHERE s.`nno` = '$trans_no' AND s.`cl` = 'C1' AND s.`bc` = 'B01' ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $a['sum'] = $query->result();
        } else {
            $a['sum'] = 2;
        }

        $sql = "SELECT  s.nno,
                d.code,
                m.description,
                m.model,
                im.current_stock AS qty,
                d.batch_no,
                d.discount,
                d.discountp,
                d.price,
                d.return_qty,
                d.amount 
          FROM `t_grn_det`  d 
          INNER JOIN `m_item` m  ON m.code=d.code
          INNER JOIN `t_grn_sum` s ON s.nno =d.`nno` AND s.cl=d.cl AND s.bc = d.bc
          
          INNER JOIN(
            SELECT `item`,SUM(qty_in)- SUM(`qty_out`) current_stock,`batch_no`,cl,bc,store_code 
          FROM `t_item_movement` 
          GROUP BY item,cl,bc,store_code,batch_no )im

          ON s.cl= im.cl AND s.bc=im.bc AND s.`store` = im.store_code AND d.code = im.item AND d.`batch_no`=im.batch_no
          WHERE S.cl='$cl' AND S.bc='$bc' AND S.nno=$trans_no";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $a['det'] = $query->result();
        } else {
            $a['det'] = 2;
        }

        $this->db->select(array('t_serial.item', 't_serial.serial_no'));
        $this->db->where('t_serial.trans_type', 3);
        $this->db->where('t_serial.trans_no', "$trans_no");
        $this->db->where('t_serial.available', "1");
        $this->db->where('t_serial.cl', $this->sd['cl']);
        $this->db->where('t_serial.bc', $this->sd['branch']);
        $this->db->from('t_serial');
        $query1 = $this->db->get();

        if ($query1->num_rows() > 0) {
            $a['serial'] = $query1->result();
        } else {
            $a['serial'] = 2;
        }


        $sql2 = "SELECT sum(`t_grn_det`.`qty` - `t_grn_det`.`return_qty`) as return_qty
           	FROM `t_grn_det` 
          	WHERE `t_grn_det`.`nno` = '$trans_no' AND `t_grn_det`.`cl` = '$cl'  AND `t_grn_det`.`bc` = '$bc'
    	      GROUP BY `t_grn_det`.code
    	      LIMIT 25 ";

        $query2 = $this->db->query($sql2);

        if ($query2->num_rows() > 0) {
            $a['max'] = $query2->result();
        } else {
            $a['max'] = 2;
        }
        echo json_encode($a);
    }

    public function serial_save() {
        $wordChunks = explode(",", $_POST['srls']);
        $execute = 0;
        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
            for ($x = 0; $x < 25; $x++) {
                if (isset($_POST['0_' . $x])) {
                    if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
                        if ($execute == 0) {

                            for ($i = 0; $i < count($wordChunks); $i++) {
                                $p = explode("-", $wordChunks[$i]);

                                if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                                    $t_seriall = array(
                                        "engine_no" => "",
                                        "chassis_no" => '',
                                        "out_doc" => 10,
                                        "out_no" => $this->max_no,
                                        "out_date" => $_POST['date'],
                                        "available" => '0'
                                    );

                                    $this->db->where('serial_no', $p[1]);
                                    $this->db->where("item", $p[0]);
                                    $this->db->update("t_serial", $t_seriall);

                                    $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$p[0]' AND serial_no='$p[1]'");

                                    $t_serial_movement_out[] = array(
                                        "cl" => $this->sd['cl'],
                                        "bc" => $this->sd['branch'],
                                        "trans_type" => 10,
                                        "trans_no" => $this->max_no,
                                        "item" => $p[0],
                                        "batch_no" => $this->get_batch_serial_wise($p[0], $p[1]),
                                        "serial_no" => $p[1],
                                        "qty_in" => 0,
                                        "qty_out" => 1,
                                        "cost" => $_POST['4_' . $x],
                                        "store_code" => $_POST['stores'],
                                        "computer" => $this->input->ip_address(),
                                        "oc" => $this->sd['oc'],
                                    );

                                    $this->db->where("item", $p[0]);
                                    $this->db->where("serial_no", $p[1]);
                                    $this->db->delete("t_serial_movement");
                                }
                            }
                            $execute = 1;
                        }
                    }
                }
            }
        } else {
            $t_serial = array(
                "engine_no" => "",
                "chassis_no" => '',
                "out_doc" => "",
                "out_no" => "",
                "out_date" => date("Y-m-d", time()),
                "available" => '1'
            );

            $this->db->where("out_no", $this->max_no);
            $this->db->where("out_doc", 10);
            $this->db->update("t_serial", $t_serial);

            $this->db->select(array('item', 'serial_no'));
            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_type", 10);
            $query = $this->db->get("t_serial_movement_out");

            foreach ($query->result() as $row) {
                $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='$row->item' AND serial_no='$row->serial_no'");
                $this->db->where("item", $row->item);
                $this->db->where("serial_no", $row->serial_no);
                $this->db->delete("t_serial_movement_out");
            }

            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_type", 10);
            $this->db->delete("t_serial_movement");

            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_type", 10);
            $this->db->delete("t_serial_movement_out");


            for ($x = 0; $x < 25; $x++) {
                if (isset($_POST['0_' . $x])) {
                    if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
                        if ($execute == 0) {
                            for ($i = 0; $i < count($wordChunks); $i++) {
                                $p = explode("-", $wordChunks[$i]);

                                $t_seriall = array(
                                    "engine_no" => "",
                                    "chassis_no" => '',
                                    "out_doc" => 10,
                                    "out_no" => $this->max_no,
                                    "out_date" => $_POST['date'],
                                    "available" => '0'
                                );

                                $this->db->where('serial_no', $p[1]);
                                $this->db->where("item", $p[0]);
                                $this->db->update("t_serial", $t_seriall);

                                $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$p[0]' AND serial_no='$p[1]'");

                                $t_serial_movement_out[] = array(
                                    "cl" => $this->sd['cl'],
                                    "bc" => $this->sd['branch'],
                                    "trans_type" => 10,
                                    "trans_no" => $this->max_no,
                                    "item" => $p[0],
                                    "batch_no" => $this->get_batch_serial_wise($p[0], $p[1]),
                                    "serial_no" => $p[1],
                                    "qty_in" => 0,
                                    "qty_out" => 1,
                                    "cost" => $_POST['4_' . $x],
                                    "store_code" => $_POST['stores'],
                                    "computer" => $this->input->ip_address(),
                                    "oc" => $this->sd['oc'],
                                );

                                $this->db->where("item", $p[0]);
                                $this->db->where("serial_no", $p[1]);
                                $this->db->delete("t_serial_movement");
                            }
                            $execute = 1;
                        }//end serial for loop
                    } //end execute
                }
            }
        }

        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
            if (isset($t_serial_movement_out)) {
                if (count($t_serial_movement_out)) {
                    $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);
                }
            }
        } else {
            if (isset($t_serial_movement_out)) {
                if (count($t_serial_movement_out)) {
                    $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);
                }
            }
        }
    }

    public function PDF_report() {


     

        $this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $r_detail['branch'] = $this->db->get('m_branch')->result();

        $this->db->where("code", $_POST['sales_type']);
        $query = $this->db->get('t_trans_code');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $r_detail['r_type'] = $row->description;
            }
        }

        $r_detail['type'] = $_POST['type'];
        $r_detail['dt'] = $_POST['dt'];
        $r_detail['qno'] = $_POST['qno'];
        $r_detail['drn'] = $_POST['drn'];
        $r_detail['page'] = $_POST['page'];
        $r_detail['header'] = $_POST['header'];
        $r_detail['orientation'] = $_POST['orientation'];

        $this->db->select(array('name', 'address1', 'address2', 'address3'));
        $this->db->where("code", $_POST['v_id']);
        $r_detail['vender'] = $this->db->get('m_supplier')->result();

        $this->db->select(array('name'));
        $this->db->where("code", $_POST['salesp_id']);
        $query = $this->db->get('m_employee');

        foreach ($query->result() as $row) {
            $r_detail['employee'] = $row->name;
        }

        $num = $_POST['netAmnt'];

        function convertNum($num) {
            $safeNum = $num;
            $num = (int) $num;    // make sure it's an integer

            if ($num < 0)
                return "negative" . convertTri(-$num, 0);
            if ($num == 0)
                return "zero";

            $pos = strpos($safeNum, '.');
            $len = strlen($safeNum);
            $decimalPart = substr($safeNum, $pos + 1, $len - ($pos + 1));

            if ($pos > 0) {
                return convertTri($num, 0) . " and " . convertTri($decimalPart, 0) . ' Cents Rupees';
            } else {
                return convertTri($num, 0);
            }
        }

        function convertTri($num, $tri) {
            $ones = array("", " One", " Two", " Three", " Four", " Five", " Six", " Seven", " Eight", " Nine", " Ten", " Eleven", " Twelve", " Thirteen", " Fourteen", " Fifteen", " Sixteen", " Seventeen", " Eighteen", " Nineteen");
            $tens = array("", "", " Twenty", " Thirty", " Forty", " Fifty", " Sixty", " Seventy", " Eighty", " Ninety");
            $triplets = array("", " Thousand", " Million", " Billion", " Trillion", " Quadrillion", " Quintillion", " Sextillion", " Septillion", " Octillion", " Nonillion");

            // chunk the number, ...rxyy
            $r = (int) ($num / 1000);
            $x = ($num / 100) % 10;
            $y = $num % 100;

            // init the output string
            $str = "";

            // do hundreds		  
            if ($x > 0)
                $str = $ones[$x] . " Hundred";

            // do ones and tens
            if ($y < 20)
                $str .= $ones[$y];
            else
                $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

            // add triplet modifier only if there
            // is some output to be modified...
            if ($str != "")
                $str .= $triplets[$tri];

            // continue recursing?
            if ($r > 0)
                return convertTri($r, $tri + 1) . $str;
            else
                return $str;
        }

        $r_detail['netString'] = convertNum($num);

        $this->db->select(array('t_pur_ret_det.code', 'm_item.description', 'm_item.model', 't_pur_ret_det.qty', 't_pur_ret_det.price', 't_pur_ret_det.discount', 't_pur_ret_det.price * t_pur_ret_det.qty as prices', 't_pur_ret_sum.other', 't_pur_ret_sum.net_amount'));
        $this->db->from('t_pur_ret_det');
        $this->db->join('m_item', 'm_item.code=t_pur_ret_det.code');
        $this->db->join('t_pur_ret_sum', 't_pur_ret_det.nno=t_pur_ret_sum.nno');
        $this->db->where('t_pur_ret_det.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_det.bc', $this->sd['branch']);
        $this->db->where('t_pur_ret_det.nno', $_POST['qno']);
        $this->db->order_by("auto_num", "asc");
        $r_detail['items'] = $this->db->get()->result();

        $this->db->select(array('gross_amount', 'net_amount'));
        $this->db->where('t_cash_sales_sum.cl', $this->sd['cl']);
        $this->db->where('t_cash_sales_sum.bc', $this->sd['branch']);
        $this->db->where('t_cash_sales_sum.nno', $_POST['qno']);
        $r_detail['amount'] = $this->db->get('t_cash_sales_sum')->result();

        $this->db->select(array('t_pur_ret_sum.grn_no','t_pur_ret_sum.memo','t_pur_ret_sum.other', 'sum(t_pur_ret_det.discount) as discount', 't_pur_ret_sum.gross_amount+sum(t_pur_ret_det.discount) as tot', 't_pur_ret_sum.net_amount'));
        $this->db->from('t_pur_ret_sum');
        $this->db->join('t_pur_ret_det', 't_pur_ret_det.nno=t_pur_ret_sum.nno');
        $this->db->where('t_pur_ret_sum.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_sum.bc', $this->sd['branch']);
        $this->db->where('t_pur_ret_sum.nno', $_POST['qno']);
        $r_detail['additional'] = $this->db->get()->result();


        $this->db->select_sum("discount");
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $_POST['qno']);
        $r_detail['discount'] = $this->db->get('t_cash_sales_det')->result();

        $this->db->select(array('loginName'));
        $this->db->where('cCode', $this->sd['oc']);
        $r_detail['user'] = $this->db->get('users')->result();

        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
    }

    public function account_update() {
        $this->db->where("trans_no", $_POST['id']);
        $this->db->where("trans_code", 10);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_account_trans");

        $config = array(
            "ddate" => $_POST['date'],
            "trans_code" => 10,
            "trans_no" => $this->max_no,
            "op_acc" => 0,
            "reconcile" => 0,
            "cheque_no" => 0,
            "narration" => "",
            "ref_no" => $_POST['ref_no']
        );


        $des = "Supplier : " . $_POST['supplier_id'];
        $this->load->model('account');
        $this->account->set_data($config);

        for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['00_' . $x], $_POST['22_' . $x], $_POST['hh_' . $x])) {
                if ($_POST['00_' . $x] != "" && $_POST['22_' . $x] != "" && $_POST['hh_' . $x] != "") {
                    $this->db->select(array('is_add', 'account'));
                    $this->db->where('code', $_POST['00_' . $x]);
                    $condition = $this->db->get('r_additional_item')->first_row()->is_add;
                    $acc_code = $this->db->get('r_additional_item')->first_row()->account;
                    if ($condition == "1") {
                        $this->account->set_value2($des, $_POST['22_' . $x], "cr", $_POST['00_' . $x]);
                    } elseif ($condition == "0") {
                        $this->account->set_value2($des, $_POST['22_' . $x], "dr", $_POST['00_' . $x]);
                    }
                }
            }
        }

        $this->db->select(array('acc_code'));
        $this->db->where('code', 'purchase');
        $acc_code = $this->db->get('m_default_account')->first_row()->acc_code;

        $this->account->set_value2($des, $_POST['net_amount'], "dr", $_POST['supplier_id']);
        $this->account->set_value2($des, $_POST['gross_amount'], "cr", $acc_code);


        $query = $this->db->query("SELECT  (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
    FROM `t_account_trans` t
    LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
    WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='10'  AND t.`trans_no` ='" . $this->max_no . "' AND 
    IFNULL( a.`control_acc`,'')=''");

        if ($query->row()->ok == "0") {
            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_code", 10);
            $this->db->where("cl", $_POST['cl']);
            $this->db->where("bc", $_POST['branch']);
            $this->db->delete("t_account_trans");
            return "0";
        } else {
            return "1";
        }
    }

    public function item_list_all() {

        if ($_POST['search'] == 'Key Word: code, name') {
            $_POST['search'] = "";
        }
        $sql = "SELECT * FROM m_item  WHERE description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' AND inactive='0' LIMIT 25";
        $query = $this->db->query($sql);
        $a = "<table id='item_list' style='width : 100%' >";
        $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Code</th>";
        $a .= "<th class='tb_head_th'>Item Name</th>";
        $a .= "<th class='tb_head_th'>Model</th>";
        $a .= "<th class='tb_head_th'>Price</th>";
        $a .= "</thead></tr>";
        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "</tr>";
        foreach ($query->result() as $r) {
            $a .= "<tr class='cl'>";
            $a .= "<td>" . $r->code . "</td>";
            $a .= "<td>" . $r->description . "</td>";
            $a .= "<td>" . $r->model . "</td>";
            $a .= "<td>" . $r->purchase_price . "</td>";

            $a .= "</tr>";
        }
        $a .= "</table>";

        echo $a;
    }

    private function set_delete() {
        $this->db->where("nno", $this->max_no);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_pur_ret_additional_item");

        $this->db->where("nno", $this->max_no);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_pur_ret_det");

        $this->db->where("trans_code", 10);
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("cl", $_POST['cl']);
        $this->db->where("bc", $_POST['branch']);
        $this->db->delete("t_item_movement");


        $this->db->where("nno", $this->debit_max_no);
        $this->db->where("cl", $_POST['cl']);
        $this->db->where("bc", $_POST['branch']);
        $this->db->delete("t_debit_note");
    }

    public function check_code() {
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);
        echo $this->db->get($this->mtb)->num_rows;
    }

    public function delete() {
        $p = $this->user_permissions->get_permission($this->mod, array('is_delete'));
        if ($p->is_delete) {
            $this->db->where('code', $_POST['code']);
            $this->db->limit(1);
            echo $this->db->delete($this->mtb);
        } else {
            echo 2;
        }
    }

    public function select() {
        $query = $this->db->get($this->mtb);
        $s = "<select name='sales_ref' id='sales_ref'>";
        $s .= "<option value='0'>---</option>";
        foreach ($query->result() as $r) {
            $s .= "<option title='" . $r->name . "' value='" . $r->code . "'>" . $r->code . " | " . $r->name . "</option>";
        }
        $s .= "</select>";
        return $s;
    }

    public function get_batch_no($x) {
        $field = "batch_no";
        $this->db->where("batch_item", "1");
        $this->db->where("code", $_POST['0_' . $x]);
        $query = $this->db->get("m_item");

        if ($query->num_rows() > 0) {
            $this->db->select_max($field);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("item", $_POST['0_' . $x]);
            return $this->db->get("t_item_movement")->first_row()->$field + 1;
        } else {
            return "0";
        }
    }

    public function get_drn_no() {
        $field = "nno";
        $this->db->select_max($field);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        return $this->db->get("t_debit_note")->first_row()->$field + 1;
    }

    public function check_grn_no() {
        $this->db->where("supp_id", $_POST['supplier_id']);
        $this->db->where("nno", $_POST['grn_no']);
        echo $this->db->get('t_grn_sum')->num_rows();
    }

    public function load() {
        $this->db->select(array(
            't_pur_ret_sum.nno',
            't_pur_ret_sum.ddate',
            't_pur_ret_sum.ref_no',
            't_pur_ret_sum.supp_id',
            't_pur_ret_sum.grn_no',
            't_pur_ret_sum.drn_no',
            't_pur_ret_sum.memo',
            't_pur_ret_sum.store',
            't_pur_ret_sum.gross_amount',
            't_pur_ret_sum.discount',
            't_pur_ret_sum.other',
            't_pur_ret_sum.net_amount',
            'm_supplier.name as supplier_name',
        ));

        $this->db->from('t_pur_ret_sum');
        $this->db->join('m_supplier', 'm_supplier.code=t_pur_ret_sum.supp_id');
        $this->db->where('t_pur_ret_sum.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_sum.bc', $this->sd['branch']);
        $this->db->where('t_pur_ret_sum.nno', $_POST['id']);
        $query = $this->db->get();

        $x = 0;

        if ($query->num_rows() > 0) {
            $a['sum'] = $query->result();
        } else {
            $x = 2;
        }

        $this->db->select(array(
            'm_item.code as icode',
            'm_item.description as idesc',
            'm_item.model',
            't_pur_ret_det.nno',
            't_pur_ret_det.qty',
            't_pur_ret_det.batch_no',
            't_pur_ret_det.discountp',
            't_pur_ret_det.discount',
            't_pur_ret_det.price',
            't_pur_ret_det.reason'
        ));

        $this->db->from('m_item');
        $this->db->join('t_pur_ret_det', 'm_item.code=t_pur_ret_det.code');
        $this->db->where('t_pur_ret_det.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_det.bc', $this->sd['branch']);
        $this->db->where('t_pur_ret_det.nno', $_POST['id']);
        $this->db->order_by('t_pur_ret_det.auto_num', 'asc');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $a['det'] = $query->result();
        } else {
            $x = 2;
        }

        $this->db->select(array(
            't_pur_ret_additional_item.type',
            't_pur_ret_additional_item.rate_p',
            't_pur_ret_additional_item.amount',
            'r_additional_item.description'
        ));

        $this->db->from('t_pur_ret_additional_item');
        $this->db->join('r_additional_item', 'r_additional_item.code=t_pur_ret_additional_item.type');
        $this->db->where('t_pur_ret_additional_item.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_additional_item.bc', $this->sd['branch']);
        $this->db->where('t_pur_ret_additional_item.nno', $_POST['id']);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $a['add'] = $query->result();
        } else {
            $a['add'] = 2;
        }

        $this->db->select(array('t_serial.item', 't_serial.serial_no'));
        $this->db->from('t_serial');
        $this->db->join('t_pur_ret_sum', 't_serial.out_no=t_pur_ret_sum.nno');
        $this->db->where('t_serial.out_doc', 10);
        $this->db->where('t_serial.out_no', $_POST['id']);
        $this->db->where('t_pur_ret_sum.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_sum.bc', $this->sd['branch']);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $a['serial'] = $query->result();
        } else {
            $a['serial'] = 2;
        }

        if ($x == 0) {
            echo json_encode($a);
        } else {
            echo json_encode($x);
        }
    }

    public function is_batch_item() {
        $this->db->select(array("batch_no", "qty"));
        $this->db->where("item", $_POST['code']);
        $this->db->where("qty >", "0");
        $query = $this->db->get("qry_current_stock");

        if ($query->num_rows() == 1) {
            foreach ($query->result() as $row) {
                echo $row->batch_no . "-" . $row->qty;
            }
        } else if ($query->num_rows() > 0) {
            echo "a";
        } else {
            echo "b";
        }
    }

    public function batch_item() {
        $this->db->select(array('batch_no', 'qty', 'cost'));
        $this->db->where("item", $_POST['search']);
        $this->db->where("qty >", "0");
        $query = $this->db->get('qry_current_stock');
        $a = "<table id='batch_item_list' style='width : 100%' >";
        $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Batch No</th>";
        $a .= "<th class='tb_head_th'>Available Quantity</th>";
        $a .= "<th class='tb_head_th'>Cost</th>";
        $a .= "</thead></tr>";
        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "</tr>";
        foreach ($query->result() as $r) {
            $a .= "<tr class='cl'>";
            $a .= "<td>" . $r->batch_no . "</td>";
            $a .= "<td>" . $r->qty . "</td>";
            $a .= "<td>" . $r->cost . "</td>";
            $a .= "</tr>";
        }
        $a .= "</table>";
        echo $a;
    }

    public function check_is_serial_items($code) {
        $this->db->select(array('serial_no'));
        $this->db->where("code", $code);
        $this->db->limit(1);
        foreach ($this->db->get("m_item")->result() as $row) {
            return $row->serial_no;
        }
    }

    public function is_serial_entered($trans_no, $item, $serial) {
        $this->db->select(array('available'));
        $this->db->where("serial_no", $serial);
        $this->db->where("item", $item);
        $query = $this->db->get("t_serial");

        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_batch_serial_wise($item, $serial) {
        $this->db->select("batch");
        $this->db->where("item", $item);
        $this->db->where("serial_no", $serial);
        $query = $this->db->get("t_serial");
        if ($query->num_rows() > 0) {
            return $query->first_row()->batch;
        } else {
            return 0;
        }
    }

}
