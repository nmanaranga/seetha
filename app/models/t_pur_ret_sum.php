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
        $this->load->model('user_permissions');
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

    public function get_debit_max_no2($table_name, $field_name) {
        $this->db->select_max($field_name);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        return $this->db->get($table_name)->first_row()->$field_name + 1;            
    }

    public function validation() {
        $status = 1;
        
        $this->max_no = $this->utility->get_max_no("t_pur_ret_sum", "nno");

        $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_pur_ret_sum');
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

        $batch_validation_status = $this->validation->batch_update('0_', '3_', '2_', '4eee_');
        if ($batch_validation_status != 1) {
            return $batch_validation_status;
        }

        $chk_item_store_validation = $this->validation->check_item_with_store($_POST['stores'], '0_');
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


    $check_grn_with_supplier_status=$this->validation->check_grn_supplier($_POST['supplier_id'],$_POST['grnno']);
    if ($check_grn_with_supplier_status != 1) {
        return $check_grn_with_supplier_status;
    }

        //if($_POST['approve']!="2"){
    $check_return_qty_validation = $this->utility->check_return_qty('0_', '2_', $_POST['grnno'], '6');
    if ($check_return_qty_validation != 1) {
        return $check_return_qty_validation;
    }
        //}    

    $chk_item_with_grn_validation = $this->utility->check_item_with_grn_no($_POST['grnno'], '0_');
    if ($chk_item_with_grn_validation != 1) {
        return $chk_item_with_grn_validation;
    }

    $check_item_price_validation = $this->utility->check_item_price('0_', $_POST['grnno'], '6', '4_','3_');
    if ($check_item_price_validation != 1) {
        return $check_item_price_validation;
    }

    $check_grn_serial_status=$this->validation->check_grn_serial($_POST['grnno'],$_POST['stores'],$this->max_no);
    if ($check_grn_serial_status != 1) {
        return $check_grn_serial_status;
    }

    $check_purchase_return_save = $this->validation->is_purchase_return('0_','3_','stores','maxretqty_');
    if ($check_purchase_return_save != 1) {
        return $check_purchase_return_save;
    }

    $check_purchase_is_cancel=$this->validation->purchase_is_cancel($_POST['grnno']);

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
        throw new Exception($errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try { 

        /*-------------------------------------------------------------------------------*/
        $check_item_exist=0;
        for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x], $_POST['2_' . $x], $_POST['4_' . $x])) {
                if ($_POST['0_' . $x] != "" && $_POST['2_' . $x] != "" && $_POST['4_' . $x] != "") {
                    $check_item_exist=1;
                }
            }
        }
        /*-------------------------------------------------------------------------------*/

        $this->debit_max_no = $this->get_debit_max_no("t_debit_note", "nno");
        $this->debit_max_no2 = $this->get_debit_max_no2("t_debit_note", "nno");

        $validation_status = $this->validation3();

        if ($validation_status == 1) {


            $t_pur_ret_sum = array(
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
                "discount" => $_POST['total_discount'],
                "net_amount" => $_POST['net_amount'],
                "post" => "",
                "post_by" => "",
                "post_date" => "",
                "is_po_update" => $_POST['update_po_status'],
                "po_no" => $_POST['update_po_no'],
                "additional_add"=>$_POST['additional_add'],
                "additional_deduct"=>$_POST['additional_deduct'],
            );

            $t_pur_ret_sum_update = array(
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
                "discount" => $_POST['total_discount'],
                "net_amount" => $_POST['net_amount'],
                "post" => "",
                "post_by" => "",
                "post_date" => "",
                "is_po_update" => $_POST['update_po_status'],
                "po_no" => $_POST['update_po_no'],
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
                            "discountp" => $_POST['5_' . $x],
                            "discount" => $_POST['6_' . $x],
                            "batch_no" => $_POST['3_' . $x],
                            "reason" => $_POST['ret_' . $x],
                            "min_price"=>$this->utility->get_min_price($_POST['0_' . $x]),
                            "sales_price"=>$this->utility->get_max_price($_POST['0_' . $x])

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


            $subs="";
            for ($x = 0; $x < 25; $x++) {
                if (isset($_POST['0_' . $x])) {
                    if ($_POST['0_' . $x] != "") {
                        if(isset($_POST['subcode_'.$x])){
                            $subs = $_POST['subcode_'.$x];
                            if($_POST['subcode_'.$x]!="0" && $_POST['subcode_'.$x]!=""){

                                $sub_items = (explode(",",$subs));
                                $arr_length= sizeof($sub_items);

                                for($y = 0; $y<$arr_length; $y++){
                                  $item_sub = (explode("-",$sub_items[$y]));
                                  $sub_qty = (int)$_POST['2_'.$x] * (int)$item_sub[1];

                                  $t_sub_item_movement[]=array(
                                    "cl" => $this->sd['cl'],
                                    "bc" => $this->sd['branch'],
                                    "item" => $_POST['0_' . $x],
                                    "sub_item"=>$item_sub[0],
                                    "trans_code" => 10,
                                    "trans_no" => $this->max_no,
                                    "ddate" => $_POST['date'],
                                    "qty_in" => 0,
                                    "qty_out" => $sub_qty,
                                    "store_code" => $_POST['stores'],
                                    "avg_price" => $this->utility->get_cost_price($_POST['0_' . $x]),
                                    "batch_no" => $_POST['3_' . $x],
                                    "sales_price" => $this->utility->get_max_price($_POST['0_' . $x]),
                                    "last_sales_price" => $this->utility->get_min_price($_POST['0_' . $x]),
                                    "cost" => $_POST['4_' . $x],
                                );
                              }
                          }
                      }
                  }
              }
          }

          $t_debit_note = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "nno" => $this->debit_max_no2,
            "ddate" => $_POST['date'],
            "ref_no" => $_POST['ref_no'],
            "memo" => "PURCHASE RETURN [" . $this->max_no . "]",
            "is_customer" => 0,
            "code" => $_POST['supplier_id'],
            "acc_code" => $this->utility->get_default_acc('STOCK_ACC'),
            "amount" => $_POST['net_amount'],
            "oc" => $this->sd['oc'],
            "post" => "",
            "post_by" => "",
            "post_date" => "",
            "is_cancel" => 0,
            "ref_trans_code"=>10,
            "ref_trans_no"=>$this->max_no,
            "balance"=> $_POST['net_amount']
        );


          $approve = array(
            "is_approve"=> "1"
        );



          if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
            if($this->user_permissions->is_add('t_pur_ret_sum')){
                if ($check_item_exist==1){
                    $this->db->insert($this->mtb, $t_pur_ret_sum);         
                    if (isset($t_pur_ret_additional_item)) {
                        if (count($t_pur_ret_additional_item)) {
                            $this->db->insert_batch("t_pur_ret_additional_item", $t_pur_ret_additional_item);
                        }
                    }
                    if (count($t_pur_ret_det)) {
                        $this->db->insert_batch("t_pur_ret_det", $t_pur_ret_det);
                    }

                    $this->db->where("trans_no", $this->max_no);
                    $this->db->where("trans_code", 10);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->delete("t_serial_trans");

                    $this->save_tempory_serials();

                    $this->utility->save_logger("SAVE",10,$this->max_no,$this->mod); 
                    echo $this->db->trans_commit();
                }else{
                    echo "System Error! Please Check & Resave";
                }

            }else{
                echo "No permission to save records";
                echo $this->db->trans_commit()."@".$this->max_no;
            }
        } else {
                                               //$status=$this->trans_cancellation->purchase_return_update_status($this->max_no);
                                    //if($status=="OK"){  
          if ($check_item_exist==1){
            $this->set_delete();  
            $this->remove_tempory_serials();

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where('nno', $_POST['hid']);
            $this->db->update($this->mtb, $t_pur_ret_sum);


            if($_POST['approve']!="2"){
             if($this->user_permissions->is_edit('t_pur_ret_sum')){
                $this->load->model('trans_settlement');

                if (isset($t_pur_ret_additional_item)) {
                    if (count($t_pur_ret_additional_item)) {
                        $this->db->insert_batch("t_pur_ret_additional_item", $t_pur_ret_additional_item);
                    }
                }
                if (count($t_pur_ret_det)) {
                    $this->db->insert_batch("t_pur_ret_det", $t_pur_ret_det);
                }
                $this->utility->save_logger("EDIT",10,$this->max_no,$this->mod); 

                echo $this->db->trans_commit();
            }else{
                echo "No permission to edit records";
                $this->db->trans_commit();
            }
        }else{

            if($this->user_permissions->is_approve('t_pur_ret_sum')){    

                if($_POST['approve']=="2" && $_POST['update_po_status']!="1"){
                    $account_update=$this->account_update(0);
                    if($account_update==1){

                        $this->account_update(1);

                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where('nno', $_POST['id']);
                        $this->db->update($this->mtb, $approve);

                        if($_POST['df_is_serial']==1){
                            $this->serial_save();
                        }

                        $this->load->model('trans_settlement');
                        for($x = 0; $x < 25; $x++) {
                            if(isset($_POST['0_' . $x])) {
                                if($_POST['0_' . $x] != "") {
                                    $this->trans_settlement->save_item_movement('t_item_movement',
                                      $_POST['0_'.$x],
                                      '10',
                                      $this->max_no,
                                      $_POST['date'],
                                      0,
                                      $_POST['2_'.$x],
                                      $_POST['stores'],
                                      $this->utility->get_cost_price($_POST['0_' . $x]),
                                      $_POST['3_' . $x],
                                      $this->utility->get_min_price($_POST['0_' . $x]),
                                      $this->utility->get_max_price($_POST['0_' . $x]),
                                      $_POST['4_' . $x],
                                      '001');
                                }
                            }
                        }

                        $this->db->where("cl", $this->sd['cl']);
                        $this->db->where("bc", $this->sd['branch']);
                        $this->db->where('nno', $_POST['hid']);
                        $this->db->update($this->mtb, $t_pur_ret_sum_update);


                        $this->load->model('trans_settlement');
                        $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","10",$this->max_no);
                        $this->trans_settlement->save_settlement("t_debit_note_trans", $_POST['supplier_id'], $_POST['date'], 18, $this->debit_max_no2, 10, $this->max_no, $_POST['net_amount'], "0");
                        $this->db->insert("t_debit_note", $t_debit_note);
                        if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}

                        $this->update_purchase_return_qty($_POST['grnno']);

                        $this->utility->update_debit_note_balance($_POST['supplier_id']);
                        $this->utility->save_logger("APPROVE",10,$this->max_no,$this->mod); 
                    }else{
                        return "Invalid account entries";
                    } 
                }else{
                    $this->db->where("trans_no", $this->max_no);
                    $this->db->where("trans_code", 10);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->delete("t_serial_trans");

                    $this->save_tempory_serials();
                }    
                                            //----------------------------------------------------------------------------


                if($_POST['approve']=="2" && $_POST['update_po_status']=="1"){

                                            // var_dump("Approve".$_POST['approve']);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where('nno', $_POST['id']);
                    $this->db->update($this->mtb, $approve);

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('trans_code','3');
                    $this->db->where('trans_no',$_POST['grnno']);
                    $this->db->delete('t_sup_settlement');

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('trans_code','3');
                    $this->db->where('trans_no',$_POST['grnno']);
                    $this->db->delete('t_item_batch');

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('trans_type','3');
                    $this->db->where('trans_no',$_POST['grnno']);
                    $this->db->delete('t_serial_movement');

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('trans_type','3');
                    $this->db->where('trans_no',$_POST['grnno']);
                    $this->db->delete('t_serial');

                    $this->load->model('trans_settlement');
                    $this->trans_settlement->delete_item_movement("t_item_movement","3",$_POST['grnno']);

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('trans_code','3');
                    $this->db->where('trans_no',$_POST['grnno']);
                    $this->db->delete('t_item_movement_sub');

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('trans_code','3');
                    $this->db->where('trans_no',$_POST['grnno']);
                    $this->db->delete('t_account_trans');

                    $sql="SELECT type,supp_id FROM t_grn_sum 
                    WHERE cl='".$this->sd['cl']."' 
                    AND bc='".$this->sd['branch']."' 
                    AND nno='".$_POST['grnno']."'";

                    $query  =$this->db->query($sql);
                    $t      = $query->row()->type;
                    $sup_id = $query->row()->supp_id;

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('sub_trans_code','3');
                    $this->db->where('sub_trans_no',$_POST['grnno']);
                    $this->db->where("type", $t);
                    $this->db->delete('t_po_trans');

                                            //$this->utility->remove_batch_item($this->sd['cl'],$this->sd['branch'],3,$_POST['grnno'],"t_item_batch");

                    $this->utility->update_purchase_balance($sup_id);     

                    $this->utility->save_logger("APPROVE",10,$this->max_no,$this->mod); 
                }
            }else{
                echo "No permission to approve records";
                $this->db->trans_commit();
            }
        }

    }else{
        echo "System Error! Please Check & Resave";
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
          if($this->check_is_serial_items($_POST['0_'.$x])==1){
            $serial = $_POST['all_serial_'.$x];
            $pp=explode(",",$serial);

            for($t=0; $t<count($pp); $t++){
              $p = explode("-",$pp[$t]);  

              $t_serial_trans[]=array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "trans_code"=>10,
                "trans_no"=>$this->max_no,
                "item_code"=>$_POST['0_'.$x],
                "batch_no"=>$_POST['3_'.$x],
                "serial_no"=>$p[0],
                "qty"=>1,
                "computer"=>$this->input->ip_address(),
                "oc"=>$this->sd['oc'],
            ); 
          }                  
      }
  }
}
}
if(isset($t_serial_trans)){if(count($t_serial_trans)){  $this->db->insert_batch("t_serial_trans", $t_serial_trans);}}
}

public function remove_tempory_serials(){
  $this->db->where("trans_no", $this->max_no);
  $this->db->where("trans_code", 10);
  $this->db->where("cl", $this->sd['cl']);
  $this->db->where("bc", $this->sd['branch']);
  $this->db->delete("t_serial_trans");
}



public function update_purchase_return_qty($grn_no){
        //$grn_no=$_POST['grnno'];

    $sql="UPDATE `t_grn_det` d, 
    (SELECT s.cl,s.bc,grn_no, d.`code`, SUM(qty) AS qty 
    FROM `t_pur_ret_det` d 
    INNER JOIN `t_pur_ret_sum` s ON d.`nno`=s.`nno` AND d.`cl`=s.`cl` AND d.`bc`=s.`bc` 
    WHERE grn_no='$grn_no' 
    AND s.cl='".$this->sd['cl']."' 
    AND s.bc='".$this->sd['branch']."' 
    GROUP BY grn_no, d.`code`) t 
    SET d.`return_qty`=t.qty
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
    $sql = "SELECT  s.`supp_id`, m.`name` ,
    s.`store` , 
    t.`description` AS store_name, 
    s.`gross_amount`,
    s.`is_cancel`,
    s.po_no  
    FROM `t_grn_sum` s 
    JOIN `m_supplier` m ON m.`code` = s.`supp_id` 
    JOIN `m_stores` t ON t.`code` = s.`store` 
    WHERE s.`nno` = '$trans_no' AND s.`cl` = '".$this->sd['cl']."' AND s.`bc` = '".$this->sd['branch']."'
    LIMIT 1";

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
    d.qty,
    d.batch_no,
    d.discount,
    d.discountp,
    d.price,
    d.return_qty,
    d.amount 
    FROM `t_grn_det`  d 
    INNER JOIN `m_item` m  ON m.code=d.code
    INNER JOIN `t_grn_sum` s ON s.nno =d.`nno` AND s.cl=d.cl AND s.bc = d.bc        

    WHERE S.cl='".$this->sd['cl']."' AND S.bc='".$this->sd['branch']."' AND S.nno=$trans_no";

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
    WHERE `t_grn_det`.`nno` = '$trans_no' AND `t_grn_det`.`cl` = '".$this->sd['cl']."'  AND `t_grn_det`.`bc` = '".$this->sd['branch']."'
    GROUP BY `t_grn_det`.code
    LIMIT 25 ";

    $query2 = $this->db->query($sql2);

    if ($query2->num_rows() > 0) {
        $a['max'] = $query2->result();
    } else {
        $a['max'] = 2;
    }

    $check_sprchase_is_cancel=$this->validation->purchase_is_cancel($trans_no);
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
          if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
            $serial = $_POST['all_serial_'.$x];
            $p=explode(",",$serial);
            for($i=0; $i<count($p); $i++){
              if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                $t_seriall = array(
                    "engine_no" => "",
                    "chassis_no" => '',
                    "out_doc" => 10,
                    "out_no" => $this->max_no,
                    "out_date" => $_POST['date'],
                    "available" => '0'
                );

                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where('serial_no', $p[$i]);
                $this->db->where("item", $_POST['0_'.$x]);
                $this->db->update("t_serial", $t_seriall);

                $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."' ");

                $t_serial_movement_out[] = array(
                    "cl" => $this->sd['cl'],
                    "bc" => $this->sd['branch'],
                    "trans_type" => 10,
                    "trans_no" => $this->max_no,
                    "item" => $_POST['0_'.$x],
                    "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
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
                $this->db->delete("t_serial_movement");
            }
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
    $this->db->where("out_doc", 10);
    $this->db->update("t_serial", $t_serial);

    $this->db->select(array('item', 'serial_no'));
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_type", 10);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $query = $this->db->get("t_serial_movement_out");

    foreach ($query->result() as $row) {
        $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='$row->item' AND serial_no='$row->serial_no'");
        $this->db->where("item", $row->item);
        $this->db->where("serial_no", $row->serial_no);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_serial_movement_out");
    }

    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_type", 10);
    $this->db->delete("t_serial_movement");

    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_type", 10);
    $this->db->delete("t_serial_movement_out");


    for ($x = 0; $x < 25; $x++) {
      if (isset($_POST['0_' . $x])) {
        if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
          if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
            $serial = $_POST['all_serial_'.$x];
            $p=explode(",",$serial);
            for($i=0; $i<count($p); $i++){

              $t_seriall = array(
                  "engine_no" => "",
                  "chassis_no" => '',
                  "out_doc" => 10,
                  "out_no" => $this->max_no,
                  "out_date" => $_POST['date'],
                  "available" => '0'
              );

              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->where('serial_no', $p[$i]);
              $this->db->where("item", $_POST['0_'.$x]);
              $this->db->update("t_serial", $t_seriall);

              $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

              $t_serial_movement_out[] = array(
                  "cl" => $this->sd['cl'],
                  "bc" => $this->sd['branch'],
                  "trans_type" => 10,
                  "trans_no" => $this->max_no,
                  "item" => $_POST['0_' . $x],
                  "batch_no" => $this->get_batch_serial_wise($_POST['0_' . $x], $p[$i]),
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
              $this->db->delete("t_serial_movement");
          }
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

        
        $sql="SELECT `t_pur_ret_det`.`code`,
        `m_item`.`description`,
        `m_item`.`model`, 
        `t_pur_ret_det`.`qty`, 
        `t_pur_ret_det`.`batch_no`, 
        `t_pur_ret_det`.`price`, 
        `t_pur_ret_det`.`discount`, 
        `t_pur_ret_sum`.`other`, 
        `t_pur_ret_sum`.`net_amount` ,
        c.`cc` AS sub_item,
        c.`deee` AS des,
        c.qty *  t_pur_ret_det.qty AS sub_qty
        FROM (`t_pur_ret_det`)
        JOIN `m_item` ON `m_item`.`code`=`t_pur_ret_det`.`code` 
        JOIN `t_pur_ret_sum` ON `t_pur_ret_det`.`nno`=`t_pur_ret_sum`.`nno` 
        LEFT JOIN `m_item_sub` ON `m_item_sub`.`item_code` = `m_item`.`code` 
        LEFT JOIN `r_sub_item` ON `r_sub_item`.`code` = `m_item_sub`.`sub_item` 
        LEFT JOIN (SELECT t_pur_ret_det.`code`, 
        r_sub_item.`description` AS deee, 
        r_sub_item.`code` AS cc,
        r_sub_item.`qty` AS qty,
        t_item_movement_sub.cl,
        t_item_movement_sub.bc,
        t_item_movement_sub.item,
        t_item_movement_sub.`sub_item` 
        FROM t_item_movement_sub 
        LEFT JOIN t_pur_ret_det ON t_pur_ret_det.`code` = t_item_movement_sub.`item`  
        AND t_pur_ret_det.`cl` = t_item_movement_sub.`cl` AND t_pur_ret_det.`bc`=t_item_movement_sub.`bc`
        JOIN r_sub_item ON t_item_movement_sub.`sub_item` = r_sub_item.`code`
        WHERE t_item_movement_sub.batch_no = t_pur_ret_det.`batch_no` AND `t_pur_ret_det`.`cl` = '".$this->sd['cl']."'  
        AND `t_pur_ret_det`.`bc` = '".$this->sd['branch']."' AND `t_pur_ret_det`.`nno` = '".$_POST['qno']."'   )c ON c.code = t_pur_ret_det.`code`
        WHERE `t_pur_ret_det`.`cl` = '".$this->sd['cl']."' 
        AND `t_pur_ret_det`.`bc` = '".$this->sd['branch']."' 
        AND `t_pur_ret_det`.`nno` = '".$_POST['qno']."'  
        GROUP BY c.cc ,t_pur_ret_det.`auto_num`,`t_pur_ret_det`.`batch_no`
        ORDER BY `t_pur_ret_det`.`auto_num` ASC 
        ";

        $query = $this->db->query($sql);
        $r_detail['items'] = $this->db->query($sql)->result();



        // $this->db->select(array('t_pur_ret_det.code', 'm_item.description', 'm_item.model', 't_pur_ret_det.qty', 't_pur_ret_det.price', 't_pur_ret_det.discount', 't_pur_ret_det.price * t_pur_ret_det.qty as prices', 't_pur_ret_sum.other', 't_pur_ret_sum.net_amount'));
        // $this->db->from('t_pur_ret_det');
        // $this->db->join('m_item', 'm_item.code=t_pur_ret_det.code');
        // $this->db->join('t_pur_ret_sum', 't_pur_ret_det.nno=t_pur_ret_sum.nno');
        // $this->db->where('t_pur_ret_det.cl', $this->sd['cl']);
        // $this->db->where('t_pur_ret_det.bc', $this->sd['branch']);
        // $this->db->where('t_pur_ret_det.nno', $_POST['qno']);
        // $this->db->order_by("auto_num", "asc");
        // $r_detail['items'] = $this->db->get()->result();



        $this->db->SELECT(array('serial_no','item','batch_no'));
        $this->db->FROM('t_serial_movement_out');
        $this->db->WHERE('t_serial_movement_out.cl', $this->sd['cl']);
        $this->db->WHERE('t_serial_movement_out.bc', $this->sd['branch']);
        $this->db->WHERE('t_serial_movement_out.trans_type','10');
        $this->db->WHERE('t_serial_movement_out.trans_no',$_POST['qno']);
        $this->db->ORDER_BY('t_serial_movement_out.auto_no');
        $r_detail['serial'] = $this->db->get()->result();




        $this->db->select(array('gross_amount', 'net_amount'));
        $this->db->where('t_cash_sales_sum.cl', $this->sd['cl']);
        $this->db->where('t_cash_sales_sum.bc', $this->sd['branch']);
        $this->db->where('t_cash_sales_sum.nno', $_POST['qno']);
        $r_detail['amount'] = $this->db->get('t_cash_sales_sum')->result();

        $this->db->select(array('t_pur_ret_sum.other', 't_pur_ret_sum.grn_no', 't_pur_ret_sum.memo','sum(t_pur_ret_det.discount) as discount', 't_pur_ret_sum.gross_amount+sum(t_pur_ret_det.discount) as tot', 't_pur_ret_sum.net_amount'));
        $this->db->from('t_pur_ret_sum');
        $this->db->join('t_pur_ret_det', 't_pur_ret_det.nno=t_pur_ret_sum.nno AND t_pur_ret_det.cl=t_pur_ret_sum.cl AND t_pur_ret_det.bc=t_pur_ret_sum.bc');
        $this->db->where('t_pur_ret_sum.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_sum.bc', $this->sd['branch']);
        $this->db->where('t_pur_ret_sum.nno', $_POST['qno']);
        $r_detail['additional'] = $this->db->get()->result();
        
        $this->db->select_sum("discount");
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $_POST['qno']);
        $r_detail['discount'] = $this->db->get('t_pur_ret_det')->result();

        $this->db->select(array('t_pur_ret_sum.oc', 's_users.discription','action_date'));
        $this->db->from('t_pur_ret_sum');
        $this->db->join('s_users', 't_pur_ret_sum.oc=s_users.cCode');
        $this->db->where('t_pur_ret_sum.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_sum.bc', $this->sd['branch']);
        $this->db->where('t_pur_ret_sum.nno', $_POST['qno']);
        $r_detail['user'] = $this->db->get()->result();

        $sql="SELECT is_approve FROM t_pur_ret_sum
        WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
        AND nno='". $_POST['qno']."'";
        $approve=$this->db->query($sql)->row()->is_approve;
        // if($approve==1){
        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
        // }else{
        //     echo "<script>alert('Transaction Not Approved For Get Print');window.close();</script>";
        // }
    }


    public function account_update($condition) {

        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 10);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");

        
        if($_POST['hid']=="0"||$_POST['hid']==""){

        }else{
            if($condition=="1"){
              $sql="SELECT is_approve FROM t_pur_ret_sum WHERE cl='".$this->sd['cl']."' 
              AND bc='".$this->sd['branch']."' AND nno='".$this->max_no."' LIMIT 1";
              $query=$this->db->query($sql);
              
              if($query->first_row()->is_approve!=0){  
                  $this->db->where("trans_no",  $this->max_no);
                  $this->db->where("trans_code", 10);
                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->delete("t_account_trans");
              }
          }
      }

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


      $des = "PURCHASE RETURN - " . $_POST['supplier_id'];
      $this->load->model('account');
      $this->account->set_data($config);

      for ($x = 0; $x < 25; $x++) {
        if (isset($_POST['00_' . $x]) && isset($_POST['22_' . $x])){
            if (!empty($_POST['00_' . $x]) && !empty($_POST['22_' . $x])) {
                $this->db->select(array('is_add', 'account'));
                $this->db->where('code', $_POST['00_' . $x]);
                $con = $this->db->get('r_additional_item')->first_row()->is_add;
                $acc_code = $this->db->get('r_additional_item')->first_row()->account;
                if ($con == "1") {
                    $this->account->set_value2($des, $_POST['22_' . $x], "cr", $acc_code,$condition);
                } elseif ($condition == "0") {
                    $this->account->set_value2($des, $_POST['22_' . $x], "dr", $acc_code,$condition);
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
            WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='10'  AND t.`trans_no` ='" . $this->max_no . "' AND 
            a.`is_control_acc`='0'");

        if ($query->row()->ok == "0") {
            $this->db->where("trans_no", $this->max_no);
            $this->db->where("trans_code", 10);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_check_double_entry");
            return "0";
        } else {
            return "1";
        }
    }   


}

public function item_list_all() {

    $cl=$this->sd['cl'];
    $branch=$this->sd['branch'];

    if ($_POST['search'] == 'Key Word: code, name') {
        $_POST['search'] = "";
    }

    $sql = "SELECT DISTINCT(m_item.code), 
    m_item.`description`,
    m_item.`model`,
    m_item.`purchase_price` 
    FROM m_item 
    JOIN qry_current_stock ON m_item.`code`=qry_current_stock.`item` 
    WHERE qry_current_stock.`store_code`='$_POST[stores]' 
    AND cl='$cl' AND bc='$branch' 
    AND (m_item.description LIKE '$_POST[search]%' OR m_item.code LIKE '$_POST[search]%')
    AND `m_item`.`inactive`='0' 
    AND qry_current_stock.qty>0 LIMIT 25";         


       // $sql = "SELECT * FROM m_item  WHERE description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' AND inactive='0' LIMIT 25";
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

    $this->load->model('trans_settlement');
    $this->trans_settlement->delete_item_movement("t_item_movement",10,$this->max_no);

    $this->db->where("trans_code", 10);
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_item_movement_sub");

}

public function check_code() {
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    echo $this->db->get($this->mtb)->num_rows;
}

public function delete() {

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
        if($this->user_permissions->is_delete('t_pur_ret_sum')){
            $status=$this->trans_cancellation->purchase_return_update_status($_POST['trans_no']);

            $cl=$this->sd['cl'];
            $bc=$this->sd['branch'];

                //if($status=="OK"){

            $trans_no=$_POST['trans_no'];

            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_item_movement("t_item_movement",10,$trans_no);

            $this->db->where('cl',$cl);
            $this->db->where('bc',$bc);
            $this->db->where('trans_code','10');
            $this->db->where('trans_no',$trans_no);
            $this->db->delete('t_item_movement_sub');

            $sql="UPDATE t_debit_note SET is_cancel='1' WHERE cl='$cl' AND bc='$bc' AND  nno IN (SELECT drn_no FROM t_pur_ret_sum WHERE  cl='$cl' AND bc='$bc' AND nno='$trans_no')";
            $this->db->query($sql);

            $sql="DELETE  FROM t_debit_note_trans  WHERE cl='$cl' AND bc='$bc' AND  trans_no IN (SELECT drn_no FROM t_pur_ret_sum WHERE  cl='$cl' AND bc='$bc' AND nno='$trans_no')";
            $this->db->query($sql);

            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","10",$trans_no);

            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_code','10');
            $this->db->where('trans_no',$trans_no);
            $this->db->delete('t_account_trans');

            $this->db->where("trans_no", $trans_no);
            $this->db->where("trans_code", 10);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_serial_trans");

            $sql="UPDATE t_serial ts,
            (SELECT cl,bc,serial_no,item FROM t_serial_movement_out WHERE cl='$cl' AND bc='$bc' AND trans_type='10' AND trans_no='$trans_no') tsmo
            SET ts.out_doc='' , ts.out_no='', ts.available='1' 
            WHERE ts.cl=tsmo.cl AND ts.bc=tsmo.bc AND ts.serial_no=tsmo.serial_no AND ts.item=tsmo.item";
            $this->db->query($sql);

            $this->db->select(array('item','serial_no'));
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_type','10');
            $this->db->where('trans_no',$trans_no);
            $query=$this->db->get('t_serial_movement_out');

            foreach($query->result() as $row){
                $sql="INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out  WHERE item='$row->item' AND serial_no='$row->serial_no'";
                $this->db->query($sql);

                $this->db->where('cl',$this->sd['cl']);
                $this->db->where('bc',$this->sd['branch']);
                $this->db->where('item',$row->item);
                $this->db->where('serial_no',$row->serial_no);
                $this->db->delete('t_serial_movement_out');

            }

            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_type','10');
            $this->db->where('trans_no',$trans_no);
            $this->db->delete('t_serial_movement');

            $data=array('is_cancel'=>'1');
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('nno',$_POST['trans_no']);
            $this->db->update('t_pur_ret_sum',$data);


            $sql="SELECT supp_id FROM t_pur_ret_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$trans_no."'";
            $sup_id=$this->db->query($sql)->first_row()->supp_id;

            $this->utility->update_debit_note_balance($sup_id);
            $this->utility->save_logger("CANCEL",10,$_POST['trans_no'],$this->mod); 

            echo $this->db->trans_commit();
            //}else{
                //echo "No permission to delete records";
                //$this->db->trans_commit();
            //}
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
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
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
        't_pur_ret_sum.is_cancel',
        't_pur_ret_sum.is_approve',
        't_pur_ret_sum.is_po_update',
        't_pur_ret_sum.po_no',
        't_pur_ret_sum.additional_add',
        't_pur_ret_sum.additional_deduct',
        'm_supplier.name as supplier_name',
    ));

    $this->db->from('t_pur_ret_sum');
    $this->db->join('m_supplier', 'm_supplier.code=t_pur_ret_sum.supp_id','left');
    $this->db->where('t_pur_ret_sum.cl', $this->sd['cl']);
    $this->db->where('t_pur_ret_sum.bc', $this->sd['branch']);
    $this->db->where('t_pur_ret_sum.nno', $_POST['id']);
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
        'm_item.code as icode',
        'm_item.description as idesc',
        'm_item.model',
        't_pur_ret_det.nno',
        't_pur_ret_det.qty',
        't_pur_ret_det.batch_no',
        't_pur_ret_det.discountp',
        't_pur_ret_det.discount',
        't_pur_ret_det.price',
        't_pur_ret_det.reason',
        'r_return_reason.description as rdesc'
    ));

    $this->db->from('m_item');
    $this->db->join('t_pur_ret_det', 'm_item.code=t_pur_ret_det.code','left');
    $this->db->join('r_return_reason', 'r_return_reason.code=t_pur_ret_det.reason','left');
    $this->db->where('t_pur_ret_det.cl', $this->sd['cl']);
    $this->db->where('t_pur_ret_det.bc', $this->sd['branch']);
    $this->db->where('t_pur_ret_det.nno', $_POST['id']);
    $this->db->where('r_return_reason.type', 1);
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
    $this->db->join('r_additional_item', 'r_additional_item.code=t_pur_ret_additional_item.type','left');
    $this->db->where('t_pur_ret_additional_item.cl', $this->sd['cl']);
    $this->db->where('t_pur_ret_additional_item.bc', $this->sd['branch']);
    $this->db->where('t_pur_ret_additional_item.nno', $_POST['id']);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $a['add'] = $query->result();
    } else {
        $a['add'] = 2;
    }

    if($app!=0){
        $this->db->select(array('t_serial.item', 't_serial.serial_no','t_serial.batch'));
        $this->db->from('t_serial');
        $this->db->join('t_pur_ret_sum', 't_serial.out_no=t_pur_ret_sum.nno','left');
        $this->db->where('t_serial.out_doc', 10);
        $this->db->where('t_serial.out_no', $_POST['id']);
        $this->db->where('t_pur_ret_sum.cl', $this->sd['cl']);
        $this->db->where('t_pur_ret_sum.bc', $this->sd['branch']);
        $query = $this->db->get();
    }else{
        $sql="SELECT item_code AS item , t_serial_trans.serial_no, '' AS other_no1 , '' AS other_no2,s.batch
        FROM t_serial_trans
        JOIN t_serial s ON s.serial_no=t_serial_trans.serial_no AND s.batch=t_serial_trans.batch_no
        WHERE t_serial_trans.cl='".$this->sd['cl']."' 
        AND t_serial_trans.bc='".$this->sd['branch']."' 
        AND t_serial_trans.trans_code='10' 
        AND t_serial_trans.trans_no='".$_POST['id']."'
        GROUP BY item_code,t_serial_trans.serial_no,batch";
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

public function batch_item() {
    $sql="SELECT qry_current_stock.`qty`,
    qry_current_stock.`batch_no`,
    t_item_batch.`purchase_price` AS cost,
    t_item_batch.`min_price` AS min,
    t_item_batch.`max_price` AS max

    FROM qry_current_stock 
    JOIN m_item ON qry_current_stock.`item`=m_item.`code` 
    JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no`
    WHERE qry_current_stock.`qty`>'0'
    AND qry_current_stock.`store_code`='$_POST[stores]' 
    AND qry_current_stock.`item`='$_POST[search]' 
    AND qry_current_stock.cl = '".$this->sd['cl']."' 
    AND qry_current_stock.bc = '".$this->sd['branch']."'
    group by t_item_batch.`batch_no`";

    $query = $this->db->query($sql);

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


/*
    public function batch_item() {
        $sql = "SELECT qry_current_stock.`qty`,
                        qry_current_stock.`batch_no`,
                        t_item_batch.`purchase_price` AS cost,
                        t_item_batch.`min_price` AS min,
                        t_item_batch.`max_price` AS max

               FROM qry_current_stock 
               JOIN m_item ON qry_current_stock.`item`=m_item.`code` 
               JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no`
               WHERE qry_current_stock.`qty`>'0'
               AND qry_current_stock.`store_code`='$_POST[stores]' 
               AND qry_current_stock.`item`='$_POST[search]' 
               AND qry_current_stock.cl = '".$this->sd['cl']."' 
               AND qry_current_stock.bc = '".$this->sd['branch']."'
               group by t_item_batch.`batch_no`";

        $query = $this->db->query($sql);

        $a = "<table id='batch_item_list' style='width : 100%' >";

        $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Batch No</th>";
        $a .= "<th class='tb_head_th'>Available Quantity</th>";
        $a .= "<th class='tb_head_th'>Max Price</th>";
        $a .= "<th class='tb_head_th'>Min Price</th>";
        $a .= "<th class='tb_head_th'>Cost</th>";



        $a .= "</thead></tr>";
        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";



        $a .= "</tr>";
        foreach ($query->result() as $r) {
            $a .= "<tr class='cl'>";
            $a .= "<td>" . $r->batch_no . "</td>";
            $a .= "<td>" . $r->qty . "</td>";
            $a .= "<td style='text-align:right;'>" . $r->max . "</td>";
            $a .= "<td style='text-align:right;'>" . $r->min . "</td>";
            $a .= "<td style='text-align:right;'>" . $r->cost . "</td>";


            $a .= "</tr>";
        }
        $a .= "</table>";

        echo $a;
    }


*/





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
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
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
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $query = $this->db->get("t_serial");
        if ($query->num_rows() > 0) {
            return $query->first_row()->batch;
        } else {
            return 0;
        }
    }
}
