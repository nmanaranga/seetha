<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class g_t_pur_ret_sum extends CI_Model {

  private $sd;
  private $mtb;
  private $max_no;
  private $debit_max_no;
  private $mod = '003';

  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->load->model('m_stores');
  }

  public function base_details() {
    $a['stores'] = $this->m_stores->select();
    $a['nno'] = $this->utility->get_max_no("g_t_pur_ret_sum", "nno");
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

  public function get_debit_max_no2($table_name, $field_name) {
    $this->db->select_max($field_name);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    return $this->db->get($table_name)->first_row()->$field_name + 1;            
  }

  public function validation() {
    $status = 1;

    $this->max_no = $this->utility->get_max_no("g_t_pur_ret_sum", "nno");

    $check_is_delete = $this->validation->check_is_cancel($this->max_no, 'g_t_pur_ret_sum');
    if ($check_is_delete != 1) {
      return "Purchase return already deleted";
    }
    $supplier_validation = $this->validation->check_is_supplier($_POST['supplier_id']);
    if ($supplier_validation != 1) {
      return "Please check supplier";
    }
    $serial_validation_status = $this->validation->serial_update('0_', '2_','all_serial_');
    if ($serial_validation_status != 1) {
      return $serial_validation_status;
    }

    $chk_item_store_validation = $this->validation->gift_check_item_with_store($_POST['stores'], '0_');
    if ($chk_item_store_validation != 1) {
      return "Please check items available in selected store";
    }
    $check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
    if($check_zero_value!=1){
      return $check_zero_value;
    }
    $chk_zero_qty=$this->validation->empty_qty('0_','2_');
    if($chk_zero_qty!=1){
      return $chk_zero_qty;
    } 
    return $status;
  }

  public function validation2() {
    $status = 1;

    $check_grn_with_supplier_status=$this->validation->check_gift_grn_supplier($_POST['supplier_id'],$_POST['grnno']);
    if ($check_grn_with_supplier_status != 1) {
      return $check_grn_with_supplier_status;
    }

    $check_return_qty_validation = $this->utility->check_return_qty('0_', '2_', $_POST['grnno'], '117');
    if ($check_return_qty_validation != 1) {
      return $check_return_qty_validation;
    }

    $chk_item_with_grn_validation = $this->utility->check_item_with_gift_grn_no($_POST['grnno'], '0_');
    if ($chk_item_with_grn_validation != 1) {
      return $chk_item_with_grn_validation;
    }

    $check_purchase_return_save = $this->validation->is_gift_purchase_return('0_','stores','2_');
    if ($check_purchase_return_save != 1) {
      return $check_purchase_return_save;
    }

    $check_purchase_is_cancel=$this->validation->gift_purchase_is_cancel($_POST['grnno']);
    if($check_purchase_is_cancel != 1) {
      return $check_purchase_is_cancel;
    }  
    return $status;
  }

  public function validation3(){
    $status=1;
    $validation_status = $this->validation();
    $validation_status2 = $this->validation2();

    if ($validation_status == 1) {
      if ($_POST['grnno'] == "" || $_POST['grnno'] == 0){
        $status=1;
      }else{
        if($validation_status2 == 1){
          $status=1;
        }else{
          $status=$validation_status2;
        }        
      }          
    }else{
      $status=$validation_status;
    }

    return $status;
  }

  public function save() {

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errFile); 
    }
    set_error_handler('exceptionThrower'); 
    try { 

      $this->debit_max_no = $this->get_debit_max_no("t_debit_note", "nno");
      $this->debit_max_no2 = $this->get_debit_max_no2("t_debit_note", "nno");

      $validation_status = $this->validation3();
     
      if ($validation_status == 1) {
        $g_t_pur_ret_sum = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "oc" => $this->sd['oc'],
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
          "net_amount" => $_POST['net_amount'],
          "additional_add"=>$_POST['additional_add'],
          "additional_deduct"=>$_POST['additional_deduct'],
          );

        $g_t_pur_ret_sum_update = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "oc" => $this->sd['oc'],
          "nno" => $this->max_no,
          "ddate" => $_POST['date'],
          "ref_no" => $_POST['ref_no'],
          "supp_id" => $_POST['supplier_id'],
          "grn_no" => $_POST['grnno'],
          "drn_no" => $this->debit_max_no2,
          "memo" => $_POST['memo'],
          "store" => $_POST['stores'],
          "gross_amount" => $_POST['gross_amount'],
          "other" => $_POST['total'],
          "net_amount" => $_POST['net_amount'],
          "additional_add"=>$_POST['additional_add'],
          "additional_deduct"=>$_POST['additional_deduct'],
          );


        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x], $_POST['2_' . $x], $_POST['4_' . $x])) {
            if ($_POST['0_' . $x] != "" && $_POST['2_' . $x] != "" && $_POST['4_' . $x] != "") {

              $t_pur_ret_det[] = array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "nno" => $this->max_no,
                "code" => $_POST['0_' . $x],
                "qty" => $_POST['2_' . $x],
                "price" => $_POST['4_' . $x],
                "reason" => $_POST['ret_' . $x],
                "sales_price"=>$this->utility->get_max_price_gift($_POST['0_' . $x])
                );

              $t_item_movement_gift[]=array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "item"=>$_POST['0_'.$x],
                "trans_code"=>117,
                "trans_no"=>$this->max_no,
                "ddate"=>$_POST['date'],
                "qty_in"=>0,
                "qty_out"=>$_POST['2_'.$x],
                "store_code"=>$_POST['stores'],
                "sales_price"=>$this->utility->get_max_price_gift($_POST['0_' . $x]),
                "cost"=>$_POST['4_'.$x]
                );
            }
          }
        }

        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['00_' . $x], $_POST['11_' . $x], $_POST['22_' . $x])) {
            if ($_POST['00_' . $x] != "" && $_POST['11_' . $x] != "" && $_POST['22_' . $x] != "") {
              $t_pur_ret_additional_item[] = array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "nno" => $this->max_no,
                "type" => $_POST['00_' . $x],
                "rate_p" => $_POST['11_' . $x],
                "amount" => $_POST['22_' . $x]
                );
            }
          }
        }

        $t_debit_note = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->debit_max_no2,
          "ddate" => $_POST['date'],
          "ref_no" => $_POST['ref_no'],
          "memo" => "GIFT VOUCHER PURCHASE RETURN [" . $this->max_no . "]",
          "is_customer" => 0,
          "code" => $_POST['supplier_id'],
          "acc_code" => $this->utility->get_default_acc('STOCK_ACC'),
          "amount" => $_POST['net_amount'],
          "oc" => $this->sd['oc'],
          "ref_trans_code"=>117,
          "ref_trans_no"=>$this->max_no,
          "balance"=> $_POST['net_amount']
          );

        $approve = array(
          "is_approve"=> "1"
          );

        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
          if($this->user_permissions->is_add('g_t_pur_ret_sum')){

            $this->db->insert('g_t_pur_ret_sum', $g_t_pur_ret_sum);   

            if (isset($t_pur_ret_additional_item)) {
              if (count($t_pur_ret_additional_item)) {
                $this->db->insert_batch("g_t_pur_ret_additional_item", $t_pur_ret_additional_item);
              }
            }

            if(count($t_pur_ret_det)) {
              $this->db->insert_batch("g_t_pur_ret_det", $t_pur_ret_det);
            }

            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_code", 117);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_serial_trans");

            $this->save_tempory_serials();

            $this->utility->save_logger("SAVE",117,$this->max_no,$this->mod); 
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            echo $this->db->trans_commit()."@".$this->max_no;
          }
        } else {
          if($this->user_permissions->is_edit('g_t_pur_ret_sum')){
            $this->set_delete();  
            $this->remove_tempory_serials();

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where('nno', $_POST['hid']);
            $this->db->update('g_t_pur_ret_sum', $g_t_pur_ret_sum);

            if (isset($t_pur_ret_additional_item)) {
              if (count($t_pur_ret_additional_item)) {
                $this->db->insert_batch("g_t_pur_ret_additional_item", $t_pur_ret_additional_item);
              }
            }

            if (count($t_pur_ret_det)) {
              $this->db->insert_batch("g_t_pur_ret_det", $t_pur_ret_det);
            }

            if($this->user_permissions->is_approve('g_t_pur_ret_sum')){    

              if($_POST['approve']=="2"){

                $account_update=$this->account_update(0);
                if($account_update==1){
                  
                  $this->account_update(1);

                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->where('nno', $_POST['id']);
                  $this->db->update('g_t_pur_ret_sum', $approve);


                  if($_POST['df_is_serial']==1){
                    $this->serial_save();
                  }
                  if(count($t_item_movement_gift)){$this->db->insert_batch("g_t_item_movement",$t_item_movement_gift);}

                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->where('nno', $_POST['hid']);
                  $this->db->update('g_t_pur_ret_sum', $g_t_pur_ret_sum_update);

                  

                  $this->load->model('trans_settlement');
                  $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","117",$this->max_no);
                  $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['supplier_id'], $_POST['date'], 18, $this->debit_max_no2, 117, $this->max_no, $_POST['net_amount'], "0");
                  $this->db->insert("t_debit_note", $t_debit_note);

                  $this->update_purchase_return_qty($_POST['grnno']);

                  $this->utility->update_debit_note_balance($_POST['supplier_id']);
                  $this->utility->save_logger("APPROVE",117,$this->max_no,$this->mod); 

                }else{
                  echo "Invalid account entries";
                  $this->db->trans_commit();
                } 
              }else{
                $this->db->where("trans_no", $this->max_no);
                $this->db->where("trans_code", 117);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_serial_trans");

                $this->save_tempory_serials();
              }   

            }else{
              echo "No permission to approve records";
              $this->db->trans_commit();
            }

            $this->utility->save_logger("EDIT",117,$this->max_no,$this->mod); 
            echo $this->db->trans_commit();
          }else{
            echo "No permission to edit records";
            $this->db->trans_commit();
          }
        }
      }else{
        echo $validation_status;
        $this->db->trans_commit();  
      }
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo $e->getMessage()." - Operation fail please contact admin"; 
    }    
  }

  public function save_tempory_serials(){
    for($x =0; $x<25; $x++){
      if(isset($_POST['0_'.$x])){
        if($_POST['0_'.$x] != "" || !empty($_POST['0_' . $x])){
          $serial = $_POST['all_serial_'.$x];
          $pp=explode(",",$serial);

          for($t=0; $t<count($pp); $t++){
            $p = explode("-",$pp[$t]);  

            $t_serial_trans[]=array(
              "cl"=>$this->sd['cl'],
              "bc"=>$this->sd['branch'],
              "trans_code"=>117,
              "trans_no"=>$this->max_no,
              "item_code"=>$_POST['0_'.$x],
              "serial_no"=>$p[0],
              "qty"=>1,
              "computer"=>$this->input->ip_address(),
              "oc"=>$this->sd['oc'],
              ); 
          }                  

        }
      }
    }
    if(isset($t_serial_trans)){if(count($t_serial_trans)){  $this->db->insert_batch("t_serial_trans", $t_serial_trans);}}
  }

  public function remove_tempory_serials(){
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 117);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_serial_trans");
  }



  public function update_purchase_return_qty($grn_no){
    $sql="UPDATE `g_t_grn_det` d, (SELECT s.cl,s.bc,grn_no, d.`code`, SUM(qty) AS qty FROM `g_t_pur_ret_det` d INNER JOIN `g_t_pur_ret_sum` s ON d.`nno`=s.`nno`
      AND d.`cl`=s.`cl` AND d.`bc`=s.`bc` WHERE grn_no='$grn_no' GROUP BY grn_no, d.`code`) t SET d.`return_qty`=t.qty
WHERE (d.`nno`=t.grn_no)  AND (d.`code`=t.code) AND (d.`cl`=t.cl) AND (d.`bc`=t.bc)";
$this->db->query($sql);           
}

public function get_item(){
  $cl=$this->sd['cl'];
  $branch=$this->sd['branch'];
  $code=$_POST['code'];

  $sql = "SELECT DISTINCT(m_item.code), 
  m_item.`description`,
  m_item.`model`,
  m_item.`purchase_price` 
  FROM m_item 
  JOIN qry_current_stock ON m_item.`code`=qry_current_stock.`item` 
  WHERE qry_current_stock.`store_code`='$_POST[stores]' 
  AND cl='$cl' AND bc='$branch' AND m_item.code='$code'
  AND `m_item`.`inactive`='0' LIMIT 25";          

  $query = $this->db->query($sql);
  if($query->num_rows() > 0){
    $data['a'] = $this->db->query($sql)->result();
  }else{
    $data['a'] = 2;
  }
  echo json_encode($data);
}

public function get_invoice() {
  $trans_no = $_POST['code'];
  $sql = "SELECT s.`supp_id`,
  m.`name`,
  s.`store`,
  t.`description` AS store_name,
  s.`gross_amount`,
  s.`is_cancel`   
  FROM `g_t_grn_sum` s 
  JOIN `m_supplier` m ON m.`code` = s.`supp_id` 
  JOIN `m_stores` t ON t.`code` = s.`store` 
  WHERE s.`nno` = '$trans_no' 
  AND s.`cl` = '".$this->sd['cl']."' 
  AND s.`bc` = '".$this->sd['branch']."' ";

  $query = $this->db->query($sql);

  if ($query->num_rows() > 0) {
    $a['sum'] = $query->result();
  } else {
    $a['sum'] = 2;
  }

  $sql = "SELECT s.nno,
  d.code,
  m.description,
  d.qty,
  d.price,
  d.return_qty,
  d.amount 
  FROM `g_t_grn_det` d 
  INNER JOIN `g_m_gift_voucher` m  ON m.code = d.code 
  INNER JOIN `g_t_grn_sum` s ON s.nno = d.`nno` AND s.cl = d.cl  AND s.bc = d.bc 
  WHERE s.cl = '".$this->sd['cl']."' 
  AND s.bc = '".$this->sd['branch']."' 
  AND s.nno = '$trans_no' ";

  $query = $this->db->query($sql);

  if ($query->num_rows() > 0) {
    $a['det'] = $query->result();
  } else {
    $a['det'] = 2;
  }

  $this->db->select(array('g_t_serial.item', 'g_t_serial.serial_no'));
  $this->db->where('g_t_serial.trans_type', 3);
  $this->db->where('g_t_serial.trans_no', "$trans_no");
  $this->db->where('g_t_serial.available', "1");
  $this->db->where('g_t_serial.cl', $this->sd['cl']);
  $this->db->where('g_t_serial.bc', $this->sd['branch']);
  $this->db->from('g_t_serial');
  $query1 = $this->db->get();

  if ($query1->num_rows() > 0) {
    $a['serial'] = $query1->result();
  } else {
    $a['serial'] = 2;
  }


  $sql2 = "SELECT sum(`g_t_grn_det`.`qty` - `g_t_grn_det`.`return_qty`) as return_qty
  FROM `g_t_grn_det` 
  WHERE `g_t_grn_det`.`nno` = '$trans_no' AND `g_t_grn_det`.`cl` = '".$this->sd['cl']."'  
  AND `g_t_grn_det`.`bc` = '".$this->sd['branch']."'
  GROUP BY `g_t_grn_det`.code
  LIMIT 25 ";

  $query2 = $this->db->query($sql2);

  if ($query2->num_rows() > 0) {
    $a['max'] = $query2->result();
  } else {
    $a['max'] = 2;
  }

  $check_sprchase_is_cancel=$this->validation->gift_purchase_is_cancel($trans_no);
  if($check_sprchase_is_cancel == 1) {
    $a['status']=1; 
  }else{
    $a['status']=$check_sprchase_is_cancel;
  } 

  echo json_encode($a);


}

public function serial_save() {

  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
    for ($x = 0; $x < 25; $x++) {
      if (isset($_POST['0_' . $x])) {
        if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
          $serial = $_POST['all_serial_'.$x];
          $p=explode(",",$serial);
          for($i=0; $i<count($p); $i++){
            if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

              $t_seriall = array(
                "engine_no" => "",
                "chassis_no" => '',
                "out_doc" => 117,
                "out_no" => $this->max_no,
                "out_date" => $_POST['date'],
                "available" => '0'
                );

              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->where('serial_no', $p[$i]);
              $this->db->where("item", $_POST['0_'.$x]);
              $this->db->update("g_t_serial", $t_seriall);

              $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."' ");

              $t_serial_movement_out[] = array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "trans_type" => 117,
                "trans_no" => $this->max_no,
                "item" => $_POST['0_'.$x],
                "serial_no" => $p[$i],
                "qty_in" => 0,
                "qty_out" => 1,
                "cost" => $_POST['4_' . $x],
                "store_code" => $_POST['stores'],
                "computer" => $this->input->ip_address(),
                "oc" => $this->sd['oc'],
                );

              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->where("item", $_POST['0_'.$x]);
              $this->db->where("serial_no", $p[$i]);
              $this->db->delete("g_t_serial_movement");
            }
          }
        }
      }
    }
  }else{
    $t_serial = array(
      "engine_no" => "",
      "chassis_no" => '',
      "out_doc" => "",
      "out_no" => "",
      "out_date" => date("Y-m-d", time()),
      "available" => '1'
      );

    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("out_no", $this->max_no);
    $this->db->where("out_doc", 117);
    $this->db->update("g_t_serial", $t_serial);

    $this->db->select(array('item', 'serial_no'));
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_type", 117);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $query = $this->db->get("g_t_serial_movement_out");

    foreach ($query->result() as $row) {
      $this->db->query("INSERT INTO g_t_serial_movement SELECT * FROM g_t_serial_movement_out WHERE item='$row->item' AND serial_no='$row->serial_no'");
      $this->db->where("item", $row->item);
      $this->db->where("serial_no", $row->serial_no);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("g_t_serial_movement_out");
    }

    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_type", 117);
    $this->db->delete("g_t_serial_movement");

    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_type", 117);
    $this->db->delete("g_t_serial_movement_out");


    for ($x = 0; $x < 25; $x++) {
      if (isset($_POST['0_' . $x])) {
        if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
          $serial = $_POST['all_serial_'.$x];
          $p=explode(",",$serial);
          for($i=0; $i<count($p); $i++){

            $t_seriall = array(
              "engine_no" => "",
              "chassis_no" => '',
              "out_doc" => 117,
              "out_no" => $this->max_no,
              "out_date" => $_POST['date'],
              "available" => '0'
              );

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where('serial_no', $p[$i]);
            $this->db->where("item", $_POST['0_'.$x]);
            $this->db->update("g_t_serial", $t_seriall);

            $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

            $t_serial_movement_out[] = array(
              "cl" => $this->sd['cl'],
              "bc" => $this->sd['branch'],
              "trans_type" => 117,
              "trans_no" => $this->max_no,
              "item" => $_POST['0_' . $x],
              "serial_no" => $p[$i],
              "qty_in" => 0,
              "qty_out" => 1,
              "cost" => $_POST['4_' . $x],
              "store_code" => $_POST['stores'],
              "computer" => $this->input->ip_address(),
              "oc" => $this->sd['oc'],
              );

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("item", $_POST['0_' . $x]);
            $this->db->where("serial_no", $p[$i]);
            $this->db->delete("g_t_serial_movement");
          }
        } 
      }
    }
  }

  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
    if (isset($t_serial_movement_out)) {
      if (count($t_serial_movement_out)) {
        $this->db->insert_batch("g_t_serial_movement_out", $t_serial_movement_out);
      }
    }
  } else {
    if (isset($t_serial_movement_out)) {
      if (count($t_serial_movement_out)) {
        $this->db->insert_batch("g_t_serial_movement_out", $t_serial_movement_out);
      }
    }
  }
}

public function PDF_report(){
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
  $r_detail['duplicate'] = $_POST['is_duplicate'];
  $invoice_number= $this->utility->invoice_format($_POST['qno']);
  $session_array = array(
   $this->sd['cl'],
   $this->sd['branch'],
   $invoice_number
   );
  $r_detail['session'] = $session_array;

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

    $sql="SELECT `g_t_pur_ret_det`.`code`,
  `g_m_gift_voucher`.`description`,
  `g_t_pur_ret_det`.`qty`, 
  `g_t_pur_ret_det`.`price`, 
  `g_t_pur_ret_sum`.`other`, 
  `g_t_pur_ret_sum`.`net_amount`,
  `g_t_pur_ret_sum`.`additional_add` - `g_t_pur_ret_sum`.`additional_deduct` AS addi,
  `g_t_pur_ret_sum`.`gross_amount`,
  `g_t_pur_ret_sum`.`grn_no`,
  `g_t_pur_ret_sum`.`memo`,
  `g_t_pur_ret_sum`.`net_amount`
  FROM (`g_t_pur_ret_sum`)
  JOIN `g_t_pur_ret_det` ON `g_t_pur_ret_det`.`nno`=`g_t_pur_ret_sum`.`nno` 
  AND `g_t_pur_ret_det`.`cl`=`g_t_pur_ret_sum`.`cl` 
  AND `g_t_pur_ret_det`.`bc`=`g_t_pur_ret_sum`.`bc` 
  JOIN `g_m_gift_voucher` ON `g_m_gift_voucher`.`code`=`g_t_pur_ret_det`.`code` 
WHERE `g_t_pur_ret_det`.`cl` = '".$this->sd['cl']."' 
AND `g_t_pur_ret_det`.`bc` = '".$this->sd['branch']."' 
AND `g_t_pur_ret_det`.`nno` = '".$_POST['qno']."'  
GROUP BY g_t_pur_ret_det.`auto_num`
ORDER BY `g_t_pur_ret_det`.`auto_num` ASC 
";

$query = $this->db->query($sql);
$r_detail['items'] = $query->result();

$num = $query->first_row()->net_amount;

  function convertNum($num) {
    $safeNum = $num;
    $num = (int) $num;   

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




$this->db->SELECT(array('serial_no','item'));
$this->db->FROM('g_t_serial_movement_out');
$this->db->WHERE('g_t_serial_movement_out.cl', $this->sd['cl']);
$this->db->WHERE('g_t_serial_movement_out.bc', $this->sd['branch']);
$this->db->WHERE('g_t_serial_movement_out.trans_type','117');
$this->db->WHERE('g_t_serial_movement_out.trans_no',$_POST['qno']);
$r_detail['serial'] = $this->db->get()->result();


$this->db->select(array('g_t_pur_ret_sum.oc', 's_users.discription','action_date'));
$this->db->from('g_t_pur_ret_sum');
$this->db->join('s_users', 'g_t_pur_ret_sum.oc=s_users.cCode');
$this->db->where('g_t_pur_ret_sum.cl', $this->sd['cl']);
$this->db->where('g_t_pur_ret_sum.bc', $this->sd['branch']);
$this->db->where('g_t_pur_ret_sum.nno', $_POST['qno']);
$r_detail['user'] = $this->db->get()->result();

$sql="SELECT is_approve FROM g_t_pur_ret_sum
WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
AND nno='". $_POST['qno']."'";
$approve=$this->db->query($sql)->row()->is_approve;
$this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
}


public function account_update($condition) {

  $this->db->where("trans_no", $this->max_no);
  $this->db->where("trans_code", 117);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->delete("t_check_double_entry");

  if($_POST['hid']=="0"||$_POST['hid']==""){

  }else{
    if($condition=="1"){
      $sql="SELECT is_approve FROM g_t_pur_ret_sum WHERE cl='".$this->sd['cl']."' 
      AND bc='".$this->sd['branch']."' AND nno='".$this->max_no."' LIMIT 1";
      $query=$this->db->query($sql);
      if($query->first_row()->is_approve!=0){
        $this->db->where("trans_no",  $this->max_no);
        $this->db->where("trans_code", 117);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_account_trans");
      }
    }
  }

  $config = array(
    "ddate" => $_POST['date'],
    "trans_code" => 117,
    "trans_no" => $this->max_no,
    "op_acc" => 0,
    "reconcile" => 0,
    "cheque_no" => 0,
    "narration" => "",
    "ref_no" => $_POST['ref_no']
    );

  $des = "GIFT PURCHASE RETURN - " . $_POST['supplier_id'];
  $this->load->model('account');
  $this->account->set_data($config);

  for ($x = 0; $x < 25; $x++){
    if (isset($_POST['00_' . $x]) && isset($_POST['22_' . $x])){
      if (!empty($_POST['00_' . $x]) && !empty($_POST['22_' . $x])) {
        $this->db->select(array('is_add', 'account'));
        $this->db->where('code', $_POST['00_' . $x]);
        $con = $this->db->get('r_additional_item')->first_row()->is_add;
        $acc_code = $this->db->get('r_additional_item')->first_row()->account;
        if ($con == "1") {
          $this->account->set_value2($des, $_POST['22_' . $x], "dr", $acc_code,$condition);
        } elseif ($condition == "0") {
          $this->account->set_value2($des, $_POST['22_' . $x], "cr", $acc_code,$condition);
        }
      }
    }
  }

  $acc_code=$this->utility->get_default_acc('STOCK_ACC');
  $pur_code=$this->utility->get_default_acc('PURCHASE');  
  $cost_acc=$this->utility->get_default_acc('COST_OF_SALES'); 

  $this->account->set_value2($des, $_POST['net_amount'], "dr", $_POST['supplier_id'],$condition);
  $this->account->set_value2($des, $_POST['gross_amount'], "cr", $acc_code,$condition);
  $this->account->set_value2($des, $_POST['net_amount'], "cr", $pur_code,$condition);
  $this->account->set_value2($des, $_POST['net_amount'], "dr", $cost_acc,$condition);

  if($condition==0){
    $query = $this->db->query("
      SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
      FROM `t_check_double_entry` t
      LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
      WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='117'  AND t.`trans_no` ='" . $this->max_no . "' AND 
      a.`is_control_acc`='0'");

    if($query->row()->ok == "0") {
      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 117);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");
      return "0";
    } else {
      return "1";
    }
  }   
}

private function set_delete(){
  $this->db->where("nno", $this->max_no);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->delete("g_t_pur_ret_additional_item");

  $this->db->where("nno", $this->max_no);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->delete("g_t_pur_ret_det");

  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->where("trans_code", 117);
  $this->db->where("trans_no", $this->max_no);
  $this->db->delete("g_t_item_movement");
}

public function delete() {

  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errLine); 
  }
  set_error_handler('exceptionThrower'); 
  try { 
    if($this->user_permissions->is_delete('g_t_pur_ret_sum')){

      $cl=$this->sd['cl'];
      $bc=$this->sd['branch'];

      $trans_no=$_POST['trans_no'];

      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->where("trans_code", 117);
      $this->db->where("trans_no", $trans_no);
      $this->db->delete("g_t_item_movement");

      $sql="UPDATE t_debit_note SET is_cancel='1' WHERE cl='$cl' AND bc='$bc' AND  nno IN (SELECT drn_no FROM g_t_pur_ret_sum WHERE  cl='$cl' AND bc='$bc' AND nno='$trans_no')";
      $this->db->query($sql);

      $this->load->model('trans_settlement');
      $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","117",$trans_no);

      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('trans_code','117');
      $this->db->where('trans_no',$trans_no);
      $this->db->delete('t_account_trans');

      $this->db->where("trans_no", $trans_no);
      $this->db->where("trans_code", 117);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_serial_trans");

      $sql="UPDATE g_t_serial ts,
      (SELECT cl,bc,serial_no,item FROM g_t_serial_movement_out WHERE cl='$cl' AND bc='$bc' AND trans_type='117' AND trans_no='$trans_no') tsmo
      SET ts.out_doc='' , ts.out_no='', ts.available='1' 
      WHERE ts.cl=tsmo.cl AND ts.bc=tsmo.bc AND ts.serial_no=tsmo.serial_no AND ts.item=tsmo.item";
      $this->db->query($sql);

      $this->db->select(array('item','serial_no'));
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('trans_type','117');
      $this->db->where('trans_no',$trans_no);
      $query=$this->db->get('g_t_serial_movement_out');

      foreach($query->result() as $row){
        $sql="INSERT INTO g_t_serial_movement SELECT * FROM g_t_serial_movement_out  WHERE item='$row->item' AND serial_no='$row->serial_no'";
        $this->db->query($sql);

        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('item',$row->item);
        $this->db->where('serial_no',$row->serial_no);
        $this->db->delete('g_t_serial_movement_out');
      }

      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('trans_type','117');
      $this->db->where('trans_no',$trans_no);
      $this->db->delete('g_t_serial_movement');

      $data=array('is_cancel'=>'1');
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('nno',$_POST['trans_no']);
      $this->db->update('g_t_pur_ret_sum',$data);


      $sql="SELECT supp_id FROM g_t_pur_ret_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$trans_no."'";
      $sup_id=$this->db->query($sql)->first_row()->supp_id;

      $this->utility->update_debit_note_balance($sup_id);
      $this->utility->save_logger("CANCEL",117,$_POST['trans_no'],$this->mod); 

      echo $this->db->trans_commit();
    }else{
      echo "No permission to delete records";
      $this->db->trans_commit();
    }
  }catch(Exception $e){ 
    $this->db->trans_rollback();
    echo $e->getMessage()."Operation fail please contact admin"; 
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
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  echo $this->db->get('g_t_grn_sum')->num_rows();
}

public function load() {
  $this->db->select(array(
    'g_t_pur_ret_sum.nno',
    'g_t_pur_ret_sum.ddate',
    'g_t_pur_ret_sum.ref_no',
    'g_t_pur_ret_sum.supp_id',
    'g_t_pur_ret_sum.grn_no',
    'g_t_pur_ret_sum.drn_no',
    'g_t_pur_ret_sum.memo',
    'g_t_pur_ret_sum.store',
    'g_t_pur_ret_sum.gross_amount',
    
    'g_t_pur_ret_sum.other',
    'g_t_pur_ret_sum.net_amount',
    'g_t_pur_ret_sum.is_cancel',
    'g_t_pur_ret_sum.is_approve',

    'g_t_pur_ret_sum.additional_add',
    'g_t_pur_ret_sum.additional_deduct',
    'm_supplier.name as supplier_name',
    ));

  $this->db->from('g_t_pur_ret_sum');
  $this->db->join('m_supplier', 'm_supplier.code=g_t_pur_ret_sum.supp_id');
  $this->db->where('g_t_pur_ret_sum.cl', $this->sd['cl']);
  $this->db->where('g_t_pur_ret_sum.bc', $this->sd['branch']);
  $this->db->where('g_t_pur_ret_sum.nno', $_POST['id']);
  $query = $this->db->get();

  $x = 0;
  $app = 0;
  if ($query->num_rows() > 0) {
    $a['sum'] = $query->result();
    $app= $query->row()->is_approve;
  } else {
    $x = 2;
  }

  $this->db->select(array(
    'g_m_gift_voucher.code as icode',
    'g_m_gift_voucher.description as idesc',
    'g_t_pur_ret_det.nno',
    'g_t_pur_ret_det.qty',
    'g_t_pur_ret_det.price',
    'g_t_pur_ret_det.reason',
    'r_return_reason.description as rdesc'
    ));

  $this->db->from('g_t_pur_ret_det');
  $this->db->join('g_m_gift_voucher', 'g_m_gift_voucher.code=g_t_pur_ret_det.code');
  $this->db->join('r_return_reason', 'r_return_reason.code=g_t_pur_ret_det.reason');
  $this->db->where('g_t_pur_ret_det.cl', $this->sd['cl']);
  $this->db->where('g_t_pur_ret_det.bc', $this->sd['branch']);
  $this->db->where('g_t_pur_ret_det.nno', $_POST['id']);
  $this->db->where('r_return_reason.type', 1);
  $this->db->order_by('g_t_pur_ret_det.auto_num', 'asc');
  $query = $this->db->get();

  if ($query->num_rows() > 0) {
    $a['det'] = $query->result();
  } else {
    $x = 2;
  }

  $this->db->select(array(
    'g_t_pur_ret_additional_item.type',
    'g_t_pur_ret_additional_item.rate_p',
    'g_t_pur_ret_additional_item.amount',
    'r_additional_item.description'
    ));

  $this->db->from('g_t_pur_ret_additional_item');
  $this->db->join('r_additional_item', 'r_additional_item.code=g_t_pur_ret_additional_item.type');
  $this->db->where('g_t_pur_ret_additional_item.cl', $this->sd['cl']);
  $this->db->where('g_t_pur_ret_additional_item.bc', $this->sd['branch']);
  $this->db->where('g_t_pur_ret_additional_item.nno', $_POST['id']);
  $query = $this->db->get();

  if ($query->num_rows() > 0) {
    $a['add'] = $query->result();
  } else {
    $a['add'] = 2;
  }

  if($app!=0){
    $this->db->select(array('g_t_serial.item', 'g_t_serial.serial_no'));
    $this->db->from('g_t_serial');
    $this->db->join('g_t_pur_ret_sum', 'g_t_serial.out_no=g_t_pur_ret_sum.nno');
    $this->db->where('g_t_serial.out_doc', 117);
    $this->db->where('g_t_serial.out_no', $_POST['id']);
    $this->db->where('g_t_pur_ret_sum.cl', $this->sd['cl']);
    $this->db->where('g_t_pur_ret_sum.bc', $this->sd['branch']);
    $query = $this->db->get();
  }else{
    $sql="SELECT item_code AS item , serial_no, '' AS other_no1 , '' AS other_no2
    FROM t_serial_trans
    WHERE cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."' 
    AND trans_code='117' 
    AND trans_no='".$_POST['id']."'
    GROUP BY item_code,serial_no";
    $query=$this->db->query($sql);
  }

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
  $this->db->where("store_code", $_POST['store']);
  $this->db->where("qty >", "0");
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $query = $this->db->get("qry_current_stock");

  if ($query->num_rows() == 1) {
    foreach ($query->result() as $row) {
      echo $row->batch_no . "-" . $row->qty;
    }
  } else if ($query->num_rows() > 0) {
    echo "1";
  } else {
    echo "0";
  }
}



public function is_serial_entered($trans_no, $item, $serial) {
  $this->db->select(array('available'));
  $this->db->where("serial_no", $serial);
  $this->db->where("item", $item);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $query = $this->db->get("g_m_gift_voucher");

  if ($query->num_rows() > 0) {
    return 1;
  } else {
    return 0;
  }
}

}
