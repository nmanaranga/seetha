<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class t_open_stock extends CI_Model {

    private $sd;
    private $mtb;
    private $max_no;
    private $mod = '003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->mtb = $this->tables->tb['t_op_stock'];
        $this->load->model('user_permissions');
    }

    public function base_details() {
        $a['max_no'] = $this->utility->get_max_no("t_op_stock", "nno");
        $this->load->model('m_stores');
        $a['stores'] = $this->m_stores->select();
        $a['open_bal_date'] = $this->utility->get_open_bal_date();
        return $a;
    }

    public function validation() {
        $status = 1;

        $this->max_no = $this->utility->get_max_no("t_op_stock", "nno");

        $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_op_stock');
        if ($check_is_delete != 1) {
            return "This open stock detail already cancelled";
        }

       /* $check_open_stock_item_validation_status = $this->validation->check_open_stock_item_validation();
        if ($check_open_stock_item_validation_status != 1) {
            return $check_open_stock_item_validation_status;
        }*/

        /*$new_min_price = $this->utility->check_min_price('0_', '2_', 'min_price', 'c');*/
        $new_min_price = $this->utility->check_min_price2('0_', '2_', 'min_', 'max_');
        if ($new_min_price != 1) {
            return $new_min_price;
        }
        $serial_validation_status = $this->validation->serial_update('0_', '1_','all_serial_');
        if ($serial_validation_status != 1) {
            return "Please check the serial numbers";
        }
        $check_zero_value = $this->validation->empty_net_value($_POST['total']);
        if ($check_zero_value != 1) {
            return $check_zero_value;
        }
        $chk_zero_qty=$this->validation->empty_qty('0_','1_');
        if($chk_zero_qty!=1){
            return $chk_zero_qty;
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
            throw new Exception($errLine);
        }

        set_error_handler('exceptionThrower');
        try {
            $validation_status = $this->validation();
            if ($validation_status == 1) {

                

                $t_op_stock = array(
                    "cl" => $this->sd['cl'],
                    "bc" => $this->sd['branch'],
                    "nno" => $this->max_no,
                    "ddate" => $_POST['ddate'],
                    "ref_no" => $_POST['ref_no'],
                    "store" => $_POST['stores'],
                    "net_amount" => $_POST['total'],
                    "oc" => $this->sd['oc']
                );


                for ($x = 0; $x < 40; $x++) {
                    if (isset($_POST['0_'. $x])) {
                        if ($_POST['0_' . $x] != "") {
                            // $this->db->select(array("purchase_price", "min_price", "max_price"));
                            // $this->db->where("code", $_POST['0_' . $x]);
                            // $result = $this->db->get("m_item")->result();

                            // foreach ($result as $row) {
                            //     $cost = $avg_price = $row->purchase_price;
                            //     $sales_price = $row->max_price;
                            //     $last_sales_price = $row->min_price;
                            // }
                            if (isset($_POST['subcode_' . $x])) {
                                $subs = $_POST['subcode_' . $x];
                                if ($_POST['subcode_' . $x] != "0" && $_POST['subcode_' . $x] != "") {

                                    $sub_items = (explode(",", $subs));
                                    $arr_length = sizeof($sub_items) - 1;

                                    for ($y = 0; $y < $arr_length; $y++) {
                                        $item_sub = (explode("-", $sub_items[$y]));
                                        $sub_qty = (int) $_POST['1_' . $x] * (int) $item_sub[1];

                                        $t_sub_item_movement[] = array(
                                            "cl" => $this->sd['cl'],
                                            "bc" => $this->sd['branch'],
                                            "item" => $_POST['0_' . $x],
                                            "sub_item" => $item_sub[0],
                                            "trans_code" => 2,
                                            "trans_no" => $this->max_no,
                                            "ddate" => $_POST['ddate'],
                                            "qty_in" => $sub_qty,
                                            "qty_out" => 0,
                                            "store_code" => $_POST['stores'],
                                            "avg_price" =>$_POST['2_'.$x],
                                            "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['2_'.$x],$_POST['max_'.$x],$_POST['min_'.$x]),
                                            "sales_price" => $_POST['max_'.$x],
                                            "last_sales_price" => $_POST['min_'.$x],
                                            "cost" => $_POST['2_'.$x]
                                        );
                                    }
                                }
                            }
                      
                            $t_op_stock_det[] = array(
                                "cl" => $this->sd['cl'],
                                "bc" => $this->sd['branch'],
                                "nno" => $this->max_no,
                                "item_code" => $_POST['0_' . $x],
                                "qty" => $_POST['1_' . $x],
                                "cost" => $_POST['2_' . $x],
                                "min_price" => $_POST['min_'.$x],
                                "max_price" => $_POST['max_'.$x]
                            );
                        }
                    }
                }

                if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                    if($this->user_permissions->is_add('t_open_stock')){
                        $account_update=$this->account_update(0);
                        if($account_update==1){
                            if ($_POST['df_is_serial'] == '1') {
                                $this->save_serial();
                            }
                            $this->db->insert($this->mtb, $t_op_stock);

                            //$this->account_update(1);
                            /*if (count($t_item_movement)) {
                                $this->db->insert_batch("t_item_movement", $t_item_movement);
                            }*/
                            if (count($t_op_stock_det)) {
                                $this->db->insert_batch("t_op_stock_det", $t_op_stock_det);
                            }
                            if (isset($t_sub_item_movement)) {
                                if (count($t_sub_item_movement)) {
                                    $this->db->insert_batch("t_item_movement_sub", $t_sub_item_movement);
                                }
                            }

                            $this->load->model('trans_settlement');
                            for($x =0; $x<40; $x++){
                              if(isset($_POST['0_'.$x],$_POST['1_'.$x])){
                                if($_POST['0_'.$x] != "" && $_POST['1_'.$x] != "" ){

                                    $bbatch_no = $this->utility->get_batch_no($_POST['0_'.$x],$_POST['2_'.$x],$_POST['max_'.$x],$_POST['min_'.$x]);

                                  if($this->utility->is_batch_item($_POST['0_'.$x])){
                                    if($this->utility->batch_in_item_batch_tb($_POST['0_'.$x],$bbatch_no)){
                                      $this->load->model('utility');

                                        $this->utility->insert_batch_items(
                                            $this->sd['cl'],
                                            $this->sd['branch'],
                                            $_POST['0_'.$x],
                                            2,
                                            $this->max_no,
                                            $bbatch_no,
                                            $_POST['2_'.$x],
                                            $_POST['min_'.$x],
                                            $_POST['max_'.$x],
                                            $this->utility->get_item_supplier($_POST['0_'.$x]),
                                            $this->sd['oc'],
                                            "t_item_batch"
                                      );               
                                    }
                                  }else if($this->utility->check_item_in_batch_table($_POST['0_'.$x])){
                                      $this->utility->insert_batch_items(
                                          $this->sd['cl'],
                                          $this->sd['branch'],
                                          $_POST['0_'.$x],
                                          2,
                                          $this->max_no,
                                          $bbatch_no,
                                          $_POST['2_'.$x],
                                          $_POST['min_'.$x],
                                          $_POST['max_'.$x],
                                          $this->utility->get_item_supplier($_POST['0_'.$x]),
                                          $this->sd['oc'],
                                          "t_item_batch"
                                    );
                                  }

                                $this->trans_settlement->save_item_movement('t_item_movement',
                                $_POST['0_'.$x],
                                '2',
                                $this->max_no,
                                $_POST['ddate'],
                                $_POST['1_'.$x],
                                0,
                                $_POST['stores'],
                                $_POST['2_'.$x],
                                $bbatch_no,
                                $_POST['2_'.$x],
                                $_POST['max_'.$x],
                                $_POST['min_'.$x],
                                '001');  

                                }
                              }
                            }
                            
                            $this->utility->save_logger("SAVE", 2, $this->max_no, $this->mod);
                            echo $this->db->trans_commit()."@".$this->max_no;
                        }else{
                            echo "Invalid account entries";
                            $this->db->trans_commit();
                        }
                    }else{
                        echo "No permission to save records";
                        $this->db->trans_commit();
                    }

                } else {
                    
                    if($this->user_permissions->is_edit('t_open_stock')){
                        $account_update=$this->account_update(0);
                        if($account_update==1){
                            if ($_POST['df_is_serial'] == '1') {
                                $this->save_serial();
                            }
                            $this->db->where('cl',$this->sd['cl']);
                            $this->db->where('bc',$this->sd['branch']);
                            $this->db->where('nno', $_POST['hid']);
                            $this->db->update($this->mtb, $t_op_stock);
                            $this->set_delete();

                            //$this->account_update(1);
                            //$this->utility->remove_batch_item($this->sd['cl'],$this->sd['branch'],2,$this->max_no,"t_item_batch");

                            $this->load->model('trans_settlement');
                            for($x =0; $x<40; $x++){
                              if(isset($_POST['0_'.$x],$_POST['1_'.$x])){
                                if($_POST['0_'.$x] != "" && $_POST['1_'.$x] != "" ){
                                    
                                    $bbatch_no = $this->utility->get_batch_no($_POST['0_'.$x],$_POST['2_'.$x],$_POST['max_'.$x],$_POST['min_'.$x]);

                                  if($this->utility->is_batch_item($_POST['0_'.$x])){
                                    if($this->utility->batch_in_item_batch_tb($_POST['0_'.$x],$bbatch_no)){
                                      $this->load->model('utility');

                                        $this->utility->insert_batch_items(
                                            $this->sd['cl'],
                                            $this->sd['branch'],
                                            $_POST['0_'.$x],
                                            2,
                                            $this->max_no,
                                            $bbatch_no,
                                            $_POST['2_'.$x],
                                            $_POST['min_'.$x],
                                            $_POST['max_'.$x],
                                            $this->utility->get_item_supplier($_POST['0_'.$x]),
                                            $this->sd['oc'],
                                            "t_item_batch"
                                      );               
                                    }
                                  }else if($this->utility->check_item_in_batch_table($_POST['0_'.$x])){
                                      $this->utility->insert_batch_items(
                                          $this->sd['cl'],
                                          $this->sd['branch'],
                                          $_POST['0_'.$x],
                                          2,
                                          $this->max_no,
                                          $bbatch_no,
                                          $_POST['2_'.$x],
                                          $_POST['min_'.$x],
                                          $_POST['max_'.$x],
                                          $this->utility->get_item_supplier($_POST['0_'.$x]),
                                          $this->sd['oc'],
                                          "t_item_batch"
                                    );
                                  } 
                                $this->trans_settlement->save_item_movement('t_item_movement',
                                $_POST['0_'.$x],
                                '2',
                                $this->max_no,
                                $_POST['ddate'],
                                $_POST['1_'.$x],
                                0,
                                $_POST['stores'],
                                $_POST['2_'.$x],
                                $bbatch_no,
                                $_POST['2_'.$x],
                                $_POST['max_'.$x],
                                $_POST['min_'.$x],
                                '001');  
                                }
                              }
                            }

                            if (count($t_op_stock_det)) {
                                $this->db->insert_batch("t_op_stock_det", $t_op_stock_det);
                            }
                            
                             
                            if (isset($t_sub_item_movement)) {
                                if (count($t_sub_item_movement)) {
                                    $this->db->insert_batch("t_item_movement_sub", $t_sub_item_movement);
                                }
                            }
                            $this->utility->save_logger("EDIT", 2, $this->max_no, $this->mod);
                            echo $this->db->trans_commit();
                        }else{
                            echo "invalid account entries";
                            $this->db->trans_commit();
                        }
                    }else{
                        echo "No permission to edit records";
                        $this->db->trans_commit();
                    }
                }
            } else {
                echo $validation_status;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin";
        }
    }

    public function check_code() {
        $this->db->where('nno', $_POST['nno']);
        $this->db->limit(1);
        echo $this->db->get($this->mtb)->num_rows;
    }

    private function set_delete() {

        if ($_POST['hid'] != "0" || $_POST['hid'] != "") {
            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_item_movement('t_item_movement',2,$this->max_no);

            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("trans_code", 2);
            $this->db->where("trans_no", $this->max_no);
            $this->db->delete("t_item_movement_sub");

            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("trans_code", 2);
            $this->db->where("trans_no", $this->max_no);
            $this->db->delete("t_item_batch");

            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("nno", $this->max_no);
            $this->db->delete("t_op_stock_det");
        }
    }

    public function save_serial() {
        for ($x = 0; $x < 40; $x++) { 
            if (isset($_POST['0_' . $x])) {
                if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
                   if($this->check_is_serial_items($_POST['0_'.$x])==1){
                        $serial = $_POST['all_serial_'.$x];
                        $pp=explode(",",$serial);
                       
                        for($t=0; $t<count($pp); $t++){
                            $p = explode("-",$pp[$t]);

                                $t_serial[] = array(
                                    "cl" => $this->sd['cl'],
                                    "bc" => $this->sd['branch'],
                                    "trans_type" => 2,
                                    "trans_no" => $this->max_no,
                                    "date" => $_POST['ddate'],
                                    "item" => $_POST['0_'.$x],
                                    "batch" =>$this->utility->get_batch_no($_POST['0_'.$x],$_POST['2_'.$x],$_POST['max_'.$x],$_POST['min_'.$x]),
                                    "serial_no" => $p[0],
                                    "other_no1" => $p[1],
                                    "other_no2" => $p[2],
                                    "cost" => $_POST['2_' . $x],
                                    "max_price" => $this->utility->get_max_price($_POST['0_'.$x]),
                                    "last_price" => $this->utility->get_min_price($_POST['0_'.$x]),
                                    "store_code" => $_POST['stores'],
                                    "engine_no" => "",
                                    "chassis_no" => '',
                                    "out_doc" => '',
                                    "out_no" => '',
                                    "out_date" => ''
                                );

                                $t_serial_movement[] = array(
                                    "cl" => $this->sd['cl'],
                                    "bc" => $this->sd['branch'],
                                    "trans_type" => 2,
                                    "trans_no" => $this->max_no,
                                    "item" => $_POST['0_'.$x],
                                    "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['2_'.$x],$_POST['max_'.$x],$_POST['min_'.$x]),
                                    "serial_no" => $p[0],
                                    "qty_in" => 1,
                                    "qty_out" => 0,
                                    "cost" => $_POST['2_' . $x],
                                    "store_code" => $_POST['stores'],
                                    "computer" => $this->input->ip_address(),
                                    "oc" => $this->sd['oc'],
                                );
                            }//end serial for loop                  
                        } //end execute
                   
                }// end item is empty
            }//end isset item 
        }//end for loop


        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
            if (isset($t_serial)) {
                if (count($t_serial)) {
                    $this->db->insert_batch("t_serial", $t_serial);
                }
            }
            if (isset($t_serial_movement)) {
                if (count($t_serial_movement)) {
                    $this->db->insert_batch("t_serial_movement", $t_serial_movement);
                }
            }
        } else {

            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("trans_type", 2);
            $this->db->where("trans_no", $this->max_no);
            $this->db->delete("t_serial_movement");

            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("trans_type", 2);
            $this->db->where("trans_no", $this->max_no);
            $query = $this->db->get("t_serial");

            foreach ($query->result() as $row) {
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("item", $row->item);
                $this->db->where("serial_no", $row->serial_no);
                $this->db->delete("t_serial");

                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("item", $row->item);
                $this->db->where("serial_no", $row->serial_no);
                $this->db->delete("t_serial_movement");
            }
            if (isset($t_serial)) {
                if (count($t_serial)) {
                    $this->db->insert_batch("t_serial", $t_serial);
                }
            }
            if (isset($t_serial_movement)) {
                if (count($t_serial_movement)) {
                    $this->db->insert_batch("t_serial_movement", $t_serial_movement);
                }
            }
        }
    }

    public function load() {
        $this->db->select(array(
            't_op_stock.ddate',
            't_op_stock.ref_no',
            't_op_stock.store',
            't_op_stock.nno',
            't_op_stock.net_amount',
            't_op_stock.is_cancel'
        ));

        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $_POST['nno']);
        $this->db->from('t_op_stock');
        $this->db->limit(1);
        $qry_sum = $this->db->get();

        $x = 0;
        if ($qry_sum->num_rows() > 0) {
            $a['sum'] = $qry_sum->result();
        } else {
            $x = 2;
        }


        $this->db->select(array(
            't_op_stock_det.item_code ',
            't_op_stock_det.qty',
            't_op_stock_det.cost',
            't_op_stock_det.min_price',
            't_op_stock_det.max_price',
            'm_item.description AS item_desc',
            'm_item.model',
        ));
        $this->db->from('t_op_stock_det');
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $_POST['nno']);
        $this->db->join('m_item', 'm_item.code=t_op_stock_det.item_code');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $a['det'] = $query->result();
        }


        $this->db->select(array('item', 'serial_no', 'other_no1', 'other_no2'));
        $this->db->where('trans_type', 2);
        $this->db->where('trans_no', $_POST['nno']);
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $query = $this->db->get('t_serial');

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


    public function account_update($condition){

      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 2);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");

      if($_POST['hid']=="0"||$_POST['hid']==""){
            
        }else{
          if($condition=="1"){
              $this->db->where("trans_no",  $this->max_no);
              $this->db->where("trans_code", 2);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_account_trans");
        }
      }


      $config = array(
      "ddate" => $_POST['ddate'],
      "trans_code"=>2,
      "trans_no"=>$this->max_no,
      "op_acc"=>0,
      "reconcile"=>0,
      "cheque_no"=>0,
      "narration"=>"",
      "ref_no" => $_POST['ref_no']
     );
            
      $des = "OPENING STOCK - ".$this->max_no;
      $this->load->model('account');
      $this->account->set_data($config);

      $acc_code=$this->utility->get_default_acc('STOCK_ACC');  
      $rtn_acc=$this->utility->get_default_acc('RETURN_EARNING'); 

      $this->account->set_value2($des, $_POST['total'], "dr", $acc_code,$condition);
      $this->account->set_value2($des, $_POST['total'], "cr", $rtn_acc,$condition);

          
      if($condition==0){
        $query = $this->db->query("
             SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
             FROM `t_check_double_entry` t
             LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
             WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='2'  AND t.`trans_no` ='" . $this->max_no . "' AND 
             a.`is_control_acc`='0'");
        
             
          if($query->row()->ok == "0") {
            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_code",2);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_check_double_entry");
            return "0";
          }else {
            return "1";
          }
      }
    }  



    public function delete() {
        $this->db->trans_begin();
        error_reporting(E_ALL);

        function exceptionThrower($type, $errMsg, $errFile, $errLine) {
            throw new Exception($errMsg);
        }

        set_error_handler('exceptionThrower');
        try {
            if($this->user_permissions->is_delete('t_open_stock')){
                $status = $this->trans_cancellation->open_stock_update_status($_POST['trans_no']);
                if ($status == "OK") {
                    $this->load->model('trans_settlement');
                    $this->trans_settlement->delete_item_movement('t_item_movement',2,$_POST['trans_no']);

                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("trans_code", 2);
                    $this->db->where("trans_no", $_POST['trans_no']);
                    $this->db->delete("t_item_movement_sub");

                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("trans_type", 2);
                    $this->db->where("trans_no", $_POST['trans_no']);
                    $this->db->delete("t_serial");

                    $this->db->where("trans_no",  $_POST['trans_no']);
                    $this->db->where("trans_code", 2);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->delete("t_account_trans");

                    //$this->utility->remove_batch_item($this->sd['cl'],$this->sd['branch'],2,$_POST['trans_no'],"t_item_batch");

                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("trans_type", 2);
                    $this->db->where("trans_no", $_POST['trans_no']);
                    $this->db->delete("t_serial_movement");

                    $this->db->where('cl', $this->sd['cl']);
                    $this->db->where('bc', $this->sd['branch']);
                    $this->db->where('nno', $_POST['trans_no']);
                    $this->db->update($this->mtb, array("is_cancel" => 1));

                    $this->utility->save_logger("CANCEL", 2, $_POST['trans_no'], $this->mod);
                    echo $this->db->trans_commit();
                } else {
                    echo $status;
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
        $query = $this->db->get($this->mtb);
        $s = "<select name='sales_ref' id='sales_ref'>";
        $s .= "<option value='0'>---</option>";
        foreach ($query->result() as $r) {
            $s .= "<option title='" . $r->name . "' value='" . $r->code . "'>" . $r->code . " | " . $r->name . "</option>";
        }
        $s .= "</select>";
        return $s;
    }

    public function item_list_all() {
        if ($_POST['search'] == 'Key Word: code, name') {
            $_POST['search'] = "";
        }

        $sql = "SELECT * FROM m_item WHERE (description LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%' OR model LIKE '%$_POST[search]%' OR m_item.`min_price` LIKE '%$_POST[search]%' OR m_item.`max_price` LIKE '%$_POST[search]%') AND inactive='0' LIMIT 40";
        $query = $this->db->query($sql);
        $a = "<table id='item_list' style='width : 100%' >";
        $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Code</th>";
        $a .= "<th class='tb_head_th'>Item Name</th>";
        $a .= "<th class='tb_head_th'>model</th>";
        $a .= "<th class='tb_head_th'>Purchase Price</th>";
        $a .= "<th class='tb_head_th'>Min Price</th>";
        $a .= "<th class='tb_head_th'>Max Price</th>";
        $a .= "</thead></tr>
                <tr class='cl'>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
            ";


        foreach ($query->result() as $r) {
            $a .= "<tr class='cl'>";
            $a .= "<td>" . $r->code . "</td>";
            $a .= "<td>" . $r->description . "</td>";
            $a .= "<td>" . $r->model . "</td>";
            $a .= "<td>" . $r->purchase_price . "</td>";
            $a .= "<td>" . $r->min_price . "</td>";
            $a .= "<td>" . $r->max_price . "</td>";
            $a .= "</tr>";
        }
        $a .= "</table>";
        echo $a;
    }

    public function get_item() {
        $this->db->select(array('code', 'description', 'purchase_price', 'min_price', 'max_price','model'));
        $this->db->where("code", $this->input->post('code'));
        $this->db->limit(1);
        $query = $this->db->get('m_item');
        if ($query->num_rows() > 0) {
            $data['a'] = $query->result();
        } else {
            $data['a'] = 2;
        }

        echo json_encode($data);
    }

    public function check_is_serial_items($code) {
        $this->db->select(array('serial_no'));
        $this->db->where("code", $code);
        $this->db->limit(1);
        return $this->db->get("m_item")->first_row()->serial_no;
    }

    public function get_batch_no($x) {
        //$this->set_delete();

        $field = "batch_no";
        $this->db->where("batch_item", "1");
        $this->db->where("code", $x);
        $query = $this->db->get("m_item");

        if ($query->num_rows() > 0) {
            $this->db->select_max($field);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("item", $x);
            return $this->db->get("t_item_movement")->first_row()->$field + 1;
        } else {
            return "0";
        }
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

      $this->db->SELECT(array('serial_no','item'));
      $this->db->FROM('t_serial');
      $this->db->WHERE('t_serial.cl', $this->sd['cl']);
      $this->db->WHERE('t_serial.bc', $this->sd['branch']);
      $this->db->WHERE('t_serial.trans_type','2');
      $this->db->WHERE('t_serial.trans_no',$_POST['qno']);
      $r_detail['serial'] = $this->db->get()->result();


        $no = $this->input->post('qno');
        $bc = $this->sd['branch'];

        $sql = "SELECT s.ddate , d.item_code, m.`description`, m.`model`, d.qty, d.cost, t.description as store 
                FROM t_op_stock_det d
                JOIN t_op_stock s ON s.`nno` = d.`nno` AND s.`bc`=d.`bc` AND s.`cl` = d.`cl`
                JOIN m_item m ON m.`code` = d.`item_code`
                JOIN m_stores t ON t.code = s.store
                WHERE s.`cl` ='".$this->sd['cl']."' 
                AND s.`bc`='".$this->sd['branch']."' 
                AND s.`nno` ='".$_POST['qno']."'
                GROUP BY d.item_code
        ";

         $query=$this->db->query($sql);
          if($query->num_rows>0){
            $r_detail['det']=$query->result();
          }else{
            $r_detail['det']=2;
          }


        $r_detail['qno'] = $_POST['qno'];
        $r_detail['jtype'] = $_POST['jtype'];
        $r_detail['jtype_desc'] = $_POST['jtype_desc'];
        $r_detail['page'] = $_POST['page'];
        $r_detail['header'] = $_POST['header'];
        $r_detail['orientation'] = $_POST['orientation'];
        $r_detail['type'] = $_POST['type'];


        $this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $r_detail['branch'] = $this->db->get('m_branch')->result();

        //$r_detail['jrn_en_body'] = $query->result();
        //$r_detail['otherdtl'] = $query2->result();
        $r_detail['cl'] = $cluster = $this->sd['cl'];
        $r_detail['bc'] = $this->sd['branch'];

        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
    }

}
