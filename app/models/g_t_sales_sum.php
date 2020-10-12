<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class g_t_sales_sum extends CI_Model {

    private $sd;
    private $mtb;
    private $max_no;
    private $credit_max_no;
    private $mod = '003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->mtb = $this->tables->tb['g_t_sales_sum'];
        $this->load->model('user_permissions');
    }

    public function base_details() {
        $this->load->model('r_sales_category');
        $a['sales_category'] = $this->r_sales_category->select();

        $this->load->model('r_groups');
        $a['groups'] = $this->r_groups->select();

        $this->load->model('m_customer');
        $a['customer'] = $this->m_customer->select();

        $this->load->model('m_stores');
        $a['stores'] = $this->m_stores->select();

        $this->load->model("utility");
        $a['max_no'] = $this->utility->get_max_no("g_t_sales_sum", "nno");

        $a["cl"] = $this->sd['cl'];

        $a["bc"] = $this->sd['branch'];

        $a['type'] = 'GIFT_SALE';

        $a['det_box']=$this->pending_special_sales();
        return $a;
    }


    public function validation() {
        $this->max_no = $this->utility->get_max_no("g_t_sales_sum", "nno");
        $status = 1;
        if (empty($_POST['save_status']) && $_POST['save_status'] != "1") {
            return "Please check the payment option";
        }
        $check_is_delete = $this->validation->check_is_cancel($this->max_no, 'g_t_sales_sum');
        if ($check_is_delete != 1) {
            return "This cash sale already deleted";
        }

        $customer_validation = $this->validation->check_is_customer($_POST['customer']);
        if ($customer_validation != 1) {
            return "Please enter valid customer";
        }
        
        
        $employee_validation = $this->validation->check_is_employer($_POST['sales_rep']);
        if ($employee_validation != 1) {
            return "Please enter valid sales rep";
        }
        $store_validation = $this->validation->gift_check_item_with_store($_POST['stores'], '0_');
        if ($store_validation != 1) {
            return $store_validation;
        }
        // $minimum_price_validation = $this->validation->check_min_price('0_', 'free_price_');
        // if ($minimum_price_validation != 1) {
        //     return $minimum_price_validation;
        // }
        /*$minimum_price_validation = $this->validation->check_min_price2('0_', '3_', 'free_price_','7_','f_','1_');
        if ($minimum_price_validation != 1) {
            return $minimum_price_validation;
        }*/
        // $free_item = $this->validation->check_is_free('0', '5', 'date', 'f');
        // if ($free_item != 1) {
        //     return $free_item;
        // }


        $serial_validation_status = $this->validation->serial_update_gift('0_', '5_',"all_serial_");

        if ($serial_validation_status != 1) {
            return $serial_validation_status;
        }

        $payment_option_validation = $this->validation->payment_option_calculation();
        if ($payment_option_validation != 1) {
            return $payment_option_validation;
        }
        $check_valid_dr_no=$this->validation->check_valid_trans_no2('customer','t_code_','no2_','cl_','bc_');
        if($check_valid_dr_no!=1){
          return $check_valid_dr_no;
      }
      $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle2('customer','t_code_','no2_','settle2_','cl_','bc_');
      if($check_valid_trans_settle_status!=1){
          return $check_valid_trans_settle_status; 
      }
      $check_valid_dr_no2=$this->validation->check_valid_trans_no2('customer','t_code3_','no3_','cl3_','bc3_');
      if($check_valid_dr_no2!=1){
          return $check_valid_dr_no2;
      }
      $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle2('customer','t_code3_','no3_','settle3_','cl3_','bc3_');
      if($check_valid_trans_settle_status2!=1){
          return $check_valid_trans_settle_status2; 
      }

      for ($i=0; $i <25; $i++) { 
        if($_POST['0_'.$i]!=""){
            if($_POST['f_'.$i]!=1){
                $check_zero_value=$this->validation->empty_net_value($_POST['net']);
                if($check_zero_value!=1){
                    return $check_zero_value;
                } 
            }
        }
    }


        /*$check_cash_limit=$this->validation->sales_limit_with_customer($_POST['net'],$_POST['customer']);
        if($check_cash_limit!=1){
            return $check_cash_limit;
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
        $this->load->model("utility");
        $validation_status = $this->validation();
        if ($validation_status == 1) {
            $_POST['acc_codes']=$_POST['customer'];
            $subs="";
            if($_POST['groups']!='0'){
                $group=$_POST['groups'];
            }else{
                $group=$this->utility->default_group();
            }
            for ($x = 0; $x < 25; $x++) {
                if (isset($_POST['0_' . $x], $_POST['5_' . $x], $_POST['3_' . $x])) {
                    if ($_POST['0_' . $x] != "" && $_POST['5_' . $x] != "" && $_POST['3_' . $x] != "") {

                        $t_item_movement[] = array(
                            "cl" => $this->sd['cl'],
                            "bc" => $this->sd['branch'],
                            "item" => $_POST['0_' . $x],
                            "trans_code" => 63,
                            "trans_no" => $this->max_no,
                            "ddate" => $_POST['date'],
                            "qty_in" => 0,
                            "qty_out" => $_POST['5_' . $x],
                            "store_code" => $_POST['stores'],
                            "sales_price" => $_POST['3_' . $x],
                            "cost" => $this->utility->get_cost_gift($_POST['0_' . $x]),
                            "group_sale_id" =>$group ,
                            );

                    }
                }
            }


            $total=(float)"0";

            for ($x = 0; $x < 25; $x++) {
                if (isset($_POST['0_' . $x], $_POST['5_' . $x], $_POST['3_' . $x])) {
                    if ($_POST['0_' . $x] != "" && $_POST['5_' . $x] != "" && $_POST['3_' . $x] != "") {

                        $g_t_sales_det[] = array(
                            "cl" => $this->sd['cl'],
                            "bc" => $this->sd['branch'],
                            "nno" => $this->max_no,
                            "code" => $_POST['0_'.$x],
                            "qty" => $_POST['5_'.$x],
                            "price" => $_POST['3_'.$x],    
                            "cost" => $this->utility->get_cost_gift($_POST['0_'.$x]),
                            "amount" => $_POST['8_'.$x],
                            "free" => $_POST['f_'.$x],
                            );
                    }
                }
            }

            $g_t_sales_sum = array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "oc" => $this->sd['oc'],
                "nno" => $this->max_no,
                "type" => $_POST['type'],
                "ddate" => $_POST['date'],
                "ref_no" => $_POST['ref_no'],
                "cus_id" => $_POST['customer'],
                "category" => $_POST['sales_category1'],
                "sub_no" => $this->utility->get_max_sales_category2('sub_no','g_t_sales_sum',$_POST['sales_category1']),
                "memo" => $_POST['memo'],
                "store" => $_POST['stores'],
                "rep" => $_POST['sales_rep'],
                "group_no" => $_POST['groups'],
                "net_amount" => $_POST['net'],
                "pay_cash" => $_POST['hid_cash'],
                "pay_issue_chq" => $_POST['hid_cheque_issue'],
                "pay_receive_chq" => $_POST['hid_cheque_recieve'],
                "pay_ccard" => $_POST['hid_credit_card'],
                "pay_cnote" => $_POST['hid_credit_note'],
                "pay_dnote" => $_POST['hid_debit_note'],
                "pay_bank_debit" => $_POST['hid_bank_debit'],
                "pay_discount" => $_POST['hid_discount'],
                "pay_advance" => $_POST['hid_advance'],
                "pay_gift_voucher" => $_POST['hid_gv'],
                "pay_credit" => $_POST['hid_credit'],
                "pay_privi_card" => $_POST['hid_pc'],
                "cus_name"=>$_POST['bill_cuss_name'],
                "cus_address"=>$_POST['cus_address'],

                );
            $approve = array(
              "is_approve"=> "1"
              );

            if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                if($this->user_permissions->is_add('g_t_sales_sum')){

                    $this->db->insert($this->mtb, $g_t_sales_sum);
                    if (isset($g_t_sales_det)) {
                        if (count($g_t_sales_det)) {
                            $this->db->insert_batch("g_t_sales_det", $g_t_sales_det);
                        }
                    }

                    $this->save_tempory_serials();

                    /*  Approve    */ 
                    if($_POST['approve']=="1"){

                        $account_update=$this->account_update(0);
                        if($account_update==1){

                            if($_POST['df_is_serial']=='1'){
                                $this->serial_save();    
                            }
                            $this->db->where("cl", $this->sd['cl']);
                            $this->db->where("bc", $this->sd['branch']);
                            $this->db->where('nno', $this->max_no);
                            $this->db->update('g_t_sales_sum', $approve);

                            if (isset($t_item_movement)) {
                                if (count($t_item_movement)) {
                                    $this->db->insert_batch("g_t_item_movement", $t_item_movement);
                                }
                            }

                            $this->load->model('t_payment_option');
                            $this->t_payment_option->save_payment_option($this->max_no, 63);
                            $this->account_update(1);

                            $this->utility->save_logger("APPROVE",63,$this->max_no,$this->mod);
                            echo $this->db->trans_commit();

                        }else{
                            echo "Invalid account entries";
                            $this->db->trans_commit();
                        }  
                    }else{
                      $this->utility->save_logger("SAVE",63,$this->max_no,$this->mod);
                      echo $this->db->trans_commit();
                  }
                  /*end of apprve*/



              }else{
                echo "No permission to save records";
                $this->db->trans_commit();
            }    
        } else {

            if($this->user_permissions->is_edit('g_t_sales_sum')){
                $this->set_delete();
                $this->remove_tempory_serials();

                $this->db->where('nno', $_POST['hid']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->update($this->mtb, $g_t_sales_sum);

                if (isset($g_t_sales_det)) {
                    if (count($g_t_sales_det)) {
                        $this->db->insert_batch("g_t_sales_det", $g_t_sales_det);
                    }
                }
                /*  Approve    */  
                if($this->user_permissions->is_approve('t_pur_ret_sum')){        
                    if($_POST['approve']=="1"){
                        $account_update=$this->account_update(0);
                        if($account_update==1){

                         $this->db->where("cl", $this->sd['cl']);
                         $this->db->where("bc", $this->sd['branch']);
                         $this->db->where('nno', $_POST['hid']);
                         $this->db->update('g_t_sales_sum', $approve);

                         if($_POST['df_is_serial']=='1'){
                            $this->serial_save();    
                        }

                        $this->account_update(1);

                        $this->utility->update_debit_note_balance($_POST['customer']);
                        $this->utility->update_credit_note_balance($_POST['customer']);  


                        if (isset($t_item_movement)) {
                            if (count($t_item_movement)) {
                                $this->db->insert_batch("g_t_item_movement", $t_item_movement);
                            }
                        }
                        $this->load->model('t_payment_option');
                        $this->t_payment_option->delete_all_payments_opt(63, $this->max_no);
                        $this->t_payment_option->save_payment_option($this->max_no, 63);

                    }else{
                        echo "Invalid account entries";
                        $this->db->trans_commit();
                    } 
                    echo $this->db->trans_commit();
                    $this->utility->save_logger("APPROVE",63,$this->max_no,$this->mod);

                    /*End of approve*/
                }else{

                 $this->db->where("trans_no", $this->max_no);
                 $this->db->where("trans_code", 8);
                 $this->db->where("cl", $this->sd['cl']);
                 $this->db->where("bc", $this->sd['branch']);
                 $this->db->delete("t_serial_trans");

                 $this->save_tempory_serials();
                 $this->load->model('t_payment_option');
                 $this->t_payment_option->delete_all_payments_opt(63, $this->max_no);
                 $this->t_payment_option->save_payment_option($this->max_no, 63);


                 echo $this->db->trans_commit();
                 $this->utility->save_logger("EDIT",63,$this->max_no,$this->mod);
             }
         }else{
          echo "No permission to approve records";
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
}catch(Exception $e){ 
    $this->db->trans_rollback();
    echo $e->getMessage()."Operation fail please contact admin"; 
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
                "trans_code"=>63,
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
}
if(isset($t_serial_trans)){if(count($t_serial_trans)){  $this->db->insert_batch("t_serial_trans", $t_serial_trans);}}
}


public function remove_tempory_serials(){
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 63);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_serial_trans");
}


private function set_delete() {
    $this->db->where("nno", $this->max_no);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("g_t_sales_det");

    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_code", 63);
    $this->db->where("trans_no", $this->max_no);
    $this->db->delete("g_t_item_movement");
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


                      $g_t_serial = array(
                          "engine_no" => "",
                          "chassis_no" => '',
                          "out_doc" => 63,
                          "out_no" => $this->max_no,
                          "out_date" => $_POST['date'],
                          "available" => '0'
                          );

                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->where('serial_no', $p[$i]);
                      $this->db->where("item", $_POST['0_' . $x]);
                      $this->db->update("g_t_serial", $g_t_serial);

                      $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_' . $x]."' AND serial_no='".$p[$i]."' ");

                      $g_t_serial_movement_out[] = array(
                          "cl" => $this->sd['cl'],
                          "bc" => $this->sd['branch'],
                          "trans_type" => 63,
                          "trans_no" => $this->max_no,
                          "item" => $_POST['0_' . $x],
                          "serial_no" => $p[$i],
                          "qty_in" => 0,
                          "qty_out" => 1,
                          "cost" => $_POST['3_' . $x],
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
}

}else{
 $g_t_serial = array(
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
 $this->db->where("out_doc", 63);
 $this->db->update("g_t_serial", $g_t_serial);

 $this->db->select(array('item', 'serial_no'));
 $this->db->where("trans_no", $this->max_no);
 $this->db->where("trans_type", 63);
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
 $this->db->where("trans_type", 63);
 $this->db->delete("g_t_serial_movement");

 $this->db->where("cl", $this->sd['cl']);
 $this->db->where("bc",$this->sd['branch']);
 $this->db->where("trans_no", $this->max_no);
 $this->db->where("trans_type", 63);
 $this->db->delete("g_t_serial_movement_out");


 for ($x = 0; $x < 25; $x++) {
    if (isset($_POST['0_' . $x])) {
        if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
         if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
            $serial = $_POST['all_serial_'.$x];
            $p=explode(",",$serial);
            for($i=0; $i<count($p); $i++){

             $g_t_serial = array(
                 "engine_no" => "",
                 "chassis_no" => '',
                 "out_doc" => 63,
                 "out_no" => $this->max_no,
                 "out_date" => $_POST['date'],
                 "available" => '0'
                 );

             $this->db->where("cl", $this->sd['cl']);
             $this->db->where("bc", $this->sd['branch']);
             $this->db->where('serial_no', $p[$i]);
             $this->db->where("item", $_POST['0_'.$x]);
             $this->db->update("g_t_serial", $g_t_serial);

             $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

             $g_t_serial_movement_out[] = array(
                 "cl" => $this->sd['cl'],
                 "bc" => $this->sd['branch'],
                 "trans_type" => 63,
                 "trans_no" => $this->max_no,
                 "item" => $_POST['0_'.$x],
                 "serial_no" => $p[$i],
                 "qty_in" => 0,
                 "qty_out" => 1,
                 "cost" => $_POST['3_' . $x],
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
                       // $execute = 1;
     }
 } 
}
}
}

if($_POST['hid'] == "0" || $_POST['hid'] == "") {
    if(isset($g_t_serial_movement_out)) {
        if(count($g_t_serial_movement_out)) {
            $this->db->insert_batch("g_t_serial_movement_out", $g_t_serial_movement_out);
        }
    }
}else{
    if(isset($g_t_serial_movement_out)) {
        if(count($g_t_serial_movement_out)) {
            $this->db->insert_batch("g_t_serial_movement_out", $g_t_serial_movement_out);
        }
    }
}
}

public function account_update($condition) {

    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 63);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");

    if($_POST['hid']=="0"||$_POST['hid']==""){

    }else{
        if($condition=="1"){
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_code',63);
            $this->db->where('trans_no',$this->max_no);
            $this->db->delete('t_account_trans');
        }
    }

    $config = array(
        "ddate" => $_POST['date'],
        "trans_code" => $_POST['type'],
        "trans_no" => $this->max_no,
        "op_acc" => 0,
        "reconcile" => 0,
        "cheque_no" => 0,
        "narration" => "",
        "ref_no" => $_POST['ref_no']
        );

    $des = "Gift Voucher Sale - " . $this->max_no;
    $this->load->model('account');
    $this->account->set_data($config);

    $acc_code=$this->utility->get_default_acc('GIFT_SALES');
    $this->account->set_value2($des, $_POST['net'], "cr", $acc_code,$condition);

    if(isset($_POST['cash']) && !empty($_POST['cash']) && $_POST['cash']>0){
      $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
      $this->account->set_value2($des, $_POST['cash'], "dr", $acc_code,$condition);    
  }

  if(isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card']>0){
    for($x = 0; $x<25; $x++){
        if(isset($_POST['type1_'.$x]) && isset($_POST['amount1_'.$x]) && isset($_POST['bank1_'.$x]) && isset($_POST['no1_'.$x])){
            if(!empty($_POST['type1_'.$x]) && !empty($_POST['amount1_'.$x]) && !empty($_POST['bank1_'.$x]) && !empty($_POST['no1_'.$x])){
                $acc_code = $_POST['acc1_'.$x];
                $this->account->set_value2('credit_card', $_POST['amount1_'.$x], "dr", $acc_code,$condition);    


                $acc_code_dr = $this->utility->get_default_acc('CREDIT_CARD_INTEREST');
                $this->account->set_value2('Merchant commission', $_POST['amount_rate1_'.$x], "dr", $acc_code_dr,$condition);    

                $acc_code_cr = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
                $this->account->set_value2('Merchant commission', $_POST['amount_rate1_'.$x], "cr", $acc_code_cr,$condition); 

            }
        }
    }  
}


$gift_tot =0;
$acc_code=$this->utility->get_default_acc('GIFT_SALES');
$this->account->set_value2($des, $gift_tot, "dr", $acc_code,$condition);

$acc_code=$this->utility->get_default_acc('GIFT_STOCK_ACC');
$this->account->set_value2($des, $gift_tot, "cr", $acc_code,$condition);

if($condition==0){
   $query = $this->db->query("
       SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
       FROM `t_check_double_entry` t
       LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
       WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='63'  AND t.`trans_no` ='" . $this->max_no . "' AND 
       a.`is_control_acc`='0'");

   if ($query->row()->ok == "0") {
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 63);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");
    return "0";
} else {
    return "1";
}
}
}

public function check_code() {
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    echo $this->db->get($this->mtb)->num_rows;
}

public function load() {
    $this->db->select(array(
        'g_t_sales_sum.cl',
        'g_t_sales_sum.bc',
        'g_t_sales_sum.nno',
        'g_t_sales_sum.type',
        'g_t_sales_sum.ddate',
        'g_t_sales_sum.ref_no',
        'g_t_sales_sum.cus_id',
        'g_t_sales_sum.category',
        'g_t_sales_sum.sub_no',
        'g_t_sales_sum.memo',
        'g_t_sales_sum.store',
        'g_t_sales_sum.rep',
        'g_t_sales_sum.group_no',
        'g_t_sales_sum.net_amount',
        'g_t_sales_sum.oc',
        'g_t_sales_sum.action_date',
        'g_t_sales_sum.pay_cash',
        'g_t_sales_sum.pay_issue_chq',
        'g_t_sales_sum.pay_receive_chq',
        'g_t_sales_sum.pay_ccard',
        'g_t_sales_sum.pay_cnote',
        'g_t_sales_sum.pay_dnote',
        'g_t_sales_sum.pay_discount',
        'g_t_sales_sum.pay_bank_debit',
        'g_t_sales_sum.pay_advance',
        'g_t_sales_sum.pay_gift_voucher',
        'g_t_sales_sum.pay_credit',
        'g_t_sales_sum.pay_privi_card',
        'g_t_sales_sum.is_multi_payment',
        'g_t_sales_sum.is_cancel',
        'g_t_sales_sum.cus_name',
        'g_t_sales_sum.is_approve',
        'g_t_sales_sum.cus_address',
        'm_customer.name',
        'm_customer.address1',
        'm_customer.address2',
        'm_customer.address3',
        'm_employee.name as rep_name',
        ));

    $this->db->from('g_t_sales_sum');
    $this->db->join('m_customer', 'm_customer.code=g_t_sales_sum.cus_id');
    $this->db->join('m_employee', 'm_employee.code=g_t_sales_sum.rep');
    $this->db->where('g_t_sales_sum.cl', $this->sd['cl']);
    $this->db->where('g_t_sales_sum.bc', $this->sd['branch']);
    $this->db->where('g_t_sales_sum.nno', $_POST['id']);

    $query = $this->db->get();

    $x = 0;
    $acc =0;
    $app=0;

    if ($query->num_rows() > 0) {
        $a['sum'] = $query->result();
        $acc=$query->row()->cus_id;
        $app= $query->row()->is_approve;
    } else {
        $x = 2;
    }

    $sql_bal="SELECT IFNULL(SUM(dr_amount)-SUM(cr_amount),0) AS balance 
    FROM t_account_trans 
    WHERE acc_code='$acc'";         
    $a['balance'] = $this->db->query($sql_bal)->first_row()->balance; 



    $this->db->select(array(
        'g_t_sales_det.code',
        'g_t_sales_det.qty',
        'g_t_sales_det.price',
        'g_t_sales_det.amount',
        'g_t_sales_det.free',
        'g_m_gift_voucher.cost',
        'g_m_gift_voucher.description as item_des',
        ));

    $this->db->from('g_t_sales_det');
    $this->db->join('g_m_gift_voucher', 'g_m_gift_voucher.code=g_t_sales_det.code');
    $this->db->where('g_t_sales_det.cl', $this->sd['cl']);
    $this->db->where('g_t_sales_det.bc', $this->sd['branch']);
    $this->db->where('g_t_sales_det.nno', $_POST['id']);
    $this->db->group_by('g_t_sales_det.code');
    $this->db->group_by('g_t_sales_det.free');
    $this->db->order_by('g_t_sales_det.auto_num', "asc");
    $query = $this->db->get();




    if ($query->num_rows() > 0) {
        $a['det'] = $query->result();
    } else {
        $x = 2;
    }

    if($app!=0){
        $this->db->select(array('g_t_serial.item', 'g_t_serial.serial_no'));
        $this->db->from('g_t_serial');
        $this->db->join('g_t_sales_sum', 'g_t_serial.out_no=g_t_sales_sum.nno');
        $this->db->where('g_t_serial.out_doc', 63);
        $this->db->where('g_t_serial.out_no', $_POST['id']);
        $this->db->where('g_t_sales_sum.cl', $this->sd['cl']);
        $this->db->where('g_t_sales_sum.bc', $this->sd['branch']);
        $query = $this->db->get();
    }else{
        $sql="SELECT item_code AS item , serial_no, '' AS other_no1 , '' AS other_no2
        FROM t_serial_trans
        WHERE cl='".$this->sd['cl']."' 
        AND bc='".$this->sd['branch']."' 
        AND trans_code='63' 
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

public function delete() {
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try {
        if($this->user_permissions->is_delete('g_t_sales_sum')){
            $bc=$this->sd['branch'];
            $cl=$this->sd['cl'];
            $trans_no=$_POST['trans_no'];
            $status=$this->trans_cancellation->cash_sales_update_status();  

            if($status=="OK"){

                $this->db->where('cl',$cl);
                $this->db->where('bc',$bc);
                $this->db->where('trans_code','63');
                $this->db->where('trans_no',$trans_no);
                $this->db->delete('g_t_item_movement');

                $this->db->where('cl',$this->sd['cl']);
                $this->db->where('bc',$this->sd['branch']);
                $this->db->where('trans_code','63');
                $this->db->where('trans_no',$trans_no);
                $this->db->delete('t_account_trans');


                $g_t_serial = array(
                 "engine_no" => "",
                 "chassis_no" => '',
                 "out_doc" => "",
                 "out_no" => "",
                 "out_date" => date("Y-m-d", time()),
                 "available" => '1'
                 );

                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("out_no", $trans_no);
                $this->db->where("out_doc", 63);
                $this->db->update("g_t_serial", $g_t_serial);

                $this->db->select(array('item', 'serial_no'));
                $this->db->where("trans_no", $trans_no);
                $this->db->where("trans_type", 63);
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

             $data=array('is_cancel'=>'1');
             $this->db->where('cl',$this->sd['cl']);
             $this->db->where('bc',$this->sd['branch']);
             $this->db->where('nno',$_POST['trans_no']);
             $this->db->update('g_t_sales_sum',$data);

             $sql="SELECT cus_id FROM g_t_sales_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
             $cus_id=$this->db->query($sql)->first_row()->cus_id;
             $this->utility->update_credit_note_balance($cus_id);
             $this->utility->update_debit_note_balance($cus_id);


             $this->utility->save_logger("CANCEL",63,$_POST['trans_no'],$this->mod);
             echo $this->db->trans_commit();
         }else{
            $this->db->trans_commit();
            echo "No permission to delete records";
        }  

    }else{
        echo $status;
    }
}catch(Exception $e){ 
    $this->db->trans_rollback();
    echo "Operation fail please contact admin"; 
} 
}



public function is_batch_item() {
    $this->db->select(array("batch_no", "qty"));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("item", $_POST['code']);
    $this->db->where("store_code", $_POST['store']);
    $this->db->where("qty >", "0");
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

function get_batch_qty() {

  $batch_no=$_POST['batch_no'];
  $store=$_POST['store'];
  $no=$_POST['hid'];
  $item=$_POST['code'];

  if(isset($_POST['hid']) && $_POST['hid']=="0"){
    $this->db->select(array('IFNULL(qty,0) AS qty'));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("batch_no", $this->input->post("batch_no"));
    $this->db->where("store_code", $this->input->post('store'));
    $this->db->where("item", $this->input->post('code'));
    $query = $this->db->get("qry_current_stock");

    if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
            echo $row->qty;
        }
    }else{
        echo 0;
    }

}
else
{
    $sql="SELECT IFNULL(SUM(qry_current_stock.`qty`)+SUM(c.qty),0) as qty 
    FROM (`qry_current_stock`)  
    INNER JOIN (SELECT qty,code,batch_no,cl,bc 
    FROM g_t_sales_det 
    WHERE  `batch_no` = '$batch_no'  
    AND  nno='$no' 
    AND `code` = '$item') c 
    ON c.code=qry_current_stock.`item` AND c.cl=qry_current_stock.cl AND c.bc=qry_current_stock.bc AND c.batch_no=qry_current_stock.batch_no
    WHERE qry_current_stock.`batch_no` = '$batch_no' 
    AND qry_current_stock.`store_code` = '$store' 
    AND `item` = '$item'
    ";


    if ($this->db->query($sql)->num_rows() > 0) {
        foreach($this->db->query($sql)->result() as $row){
          echo $row->qty; 
      }
  }else{
    echo 0;
}





}
}


public function item_list_all() {
    $cl=$this->sd['cl'];
    $branch=$this->sd['branch'];
    $group_sale=$_POST['group_sale'];

    if ($_POST['search'] == 'Key Word: code, name') {
        $_POST['search'] = "";
    }
    if($group_sale!="0"){
        $sql="SELECT g.* FROM g_m_gift_voucher g
        JOIN gift_qry_current_stock q ON q.`item` = g.`code`
        WHERE q.`qty` >0
        AND q.group_sale='$group_sale'
        AND q.store_code='$_POST[stores]'
        AND (g.`description` LIKE '%$_POST[search]%' 
        OR g.`code` LIKE '%$_POST[search]%'                    
        OR g.`price` LIKE '$_POST[search]%' 
        OR g.`cost` LIKE '$_POST[search]%')
        GROUP BY g.`code`
        LIMIT 25";

    }else{
        $sql="SELECT g.* FROM g_m_gift_voucher g
        JOIN gift_qry_current_stock q ON q.`item` = g.`code`
        WHERE q.`qty` >0
        AND q.store_code='$_POST[stores]'
        AND (g.`description` LIKE '%$_POST[search]%' 
        OR g.`code` LIKE '%$_POST[search]%'                    
        OR g.`price` LIKE '$_POST[search]%' 
        OR g.`cost` LIKE '$_POST[search]%')
        GROUP BY g.`code`
        LIMIT 25";
    }        
    $query = $this->db->query($sql);
    $a = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th'>Item Name</th>";
    $a .= "<th class='tb_head_th'>Cost</th>";
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
        $a .= "<td>" . $r->cost . "</td>";
        $a .= "<td>" . $r->price . "</td>";
        $a .= "</tr>";
    }
    $a .= "</table>";

    echo $a;
}

public function get_item() {

    $cl=$this->sd['cl'];
    $branch=$this->sd['branch'];
    $code=$_POST['code'];
    $group_sale=$_POST['group_sale'];


    if($group_sale!="0"){

        $sql="SELECT g.* FROM g_m_gift_voucher g
        JOIN gift_qry_current_stock q ON q.`item` = g.`code`
        WHERE q.`qty` >0
        AND q.cl='$cl' 
        AND q.bc='$branch'
        AND g.code='$code' 
        AND q.store='$_POST[stores]' 
        AND qr.group_sale='$group_sale'";

    }else{
        $sql="SELECT g.* FROM g_m_gift_voucher g
        JOIN gift_qry_current_stock q ON q.`item` = g.`code`
        WHERE q.`qty` >0
        AND q.cl='$cl' 
        AND q.bc='$branch'
        AND g.code='$code' 
        AND q.store='$_POST[stores]'";

    }        

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
        $data['a'] = $this->db->query($sql)->result();
    } else {
        $data['a'] = 2;
    }

    echo json_encode($data);

}

public function PDF_report() {
    $this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();


    $invoice_number= $this->utility->invoice_format($_POST['qno']);
    $session_array = array(
       $this->sd['cl'],
       $this->sd['branch'],
       $invoice_number
       );
    $r_detail['session'] = $session_array;

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

    $r_detail['page'] = $_POST['page'];
    $r_detail['header'] = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];

    $this->db->select(array('nic as code', 'name', 'address1', 'address2', 'address3'));
    $this->db->where("code", $_POST['cus_id']);
    $r_detail['customer'] = $this->db->get('m_customer')->result();

    $this->db->select(array('name'));
    $this->db->where("code", $_POST['salesp_id']);
    $query = $this->db->get('m_employee');

    foreach ($query->result() as $row) {
        $r_detail['employee'] = $row->name;
    }


    $sql="SELECT `g_t_sales_det`.`code`,
    `g_t_sales_det`.`qty`,
    `g_t_sales_det`.`price`,
    `g_t_sales_det`.`amount`,
    `g_t_sales_det`.`free`,
    `g_m_gift_voucher`.`description`
    FROM (`g_t_sales_det`) 
    JOIN `g_m_gift_voucher` ON `g_m_gift_voucher`.`code` = `g_t_sales_det`.`code` 
    WHERE `g_t_sales_det`.`cl` = '".$this->sd['cl']."' 
    AND `g_t_sales_det`.`bc` = '".$this->sd['branch']."'
    AND `g_t_sales_det`.`nno` = '".$_POST['qno']."'  
    GROUP BY g_t_sales_det.`code`, g_t_sales_det.free
    ORDER BY `g_t_sales_det`.`auto_num` ASC";

    $query = $this->db->query($sql);
    $r_detail['items'] = $this->db->query($sql)->result();

    $ssql=" SELECT card_no, ddate, opt_credit_card_det.amount   
    FROM opt_credit_card_det
    JOIN g_t_sales_sum ON g_t_sales_sum.nno = opt_credit_card_det.`trans_no` 
    AND g_t_sales_sum.cl = opt_credit_card_det.cl
    AND g_t_sales_sum.bc = opt_credit_card_det.bc
    WHERE trans_code = '63' AND trans_no = '".$_POST['qno']."' AND g_t_sales_sum.cl='".$this->sd['cl']."' AND g_t_sales_sum.bc = '".$this->sd['branch']."'
    ";
    $query = $this->db->query($ssql);
    $r_detail['credit_card'] = $this->db->query($ssql)->result();

    $ssql2=" SELECT sum(opt_credit_card_det.amount) as amount   
    FROM opt_credit_card_det
    JOIN g_t_sales_sum ON g_t_sales_sum.nno = opt_credit_card_det.`trans_no` 
    AND g_t_sales_sum.cl = opt_credit_card_det.cl
    AND g_t_sales_sum.bc = opt_credit_card_det.bc
    WHERE trans_code = '63' AND trans_no = '".$_POST['qno']."' AND g_t_sales_sum.cl='".$this->sd['cl']."' AND g_t_sales_sum.bc = '".$this->sd['branch']."'
    ";
    $query2 = $this->db->query($ssql2);
    $r_detail['credit_card_sum'] = $this->db->query($ssql2)->result();

    $sql11="SELECT sum(amount) as amount FROM opt_receive_cheque_det WHERE trans_code='63' AND trans_no ='".$_POST['qno']."' AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'  ";
    $query11= $this->db->query($sql11);
    $r_detail['cheque_detail'] = $this->db->query($sql11)->result();

    $sql22="SELECT sum(amount) as amount FROM opt_credit_note_det WHERE trans_code='63' AND trans_no ='".$_POST['qno']."' AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'  ";
    $query22= $this->db->query($sql22);
    $r_detail['other1'] = $this->db->query($sql22)->result();

    $sql33="SELECT sum(amount) as amount FROM opt_debit_note_det WHERE trans_code='63' AND trans_no ='".$_POST['qno']."' AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'  ";
    $query33= $this->db->query($sql33);
    $r_detail['other2'] = $this->db->query($sql33)->result();

    
    $this->db->SELECT(array('serial_no','item'));
    $this->db->FROM('g_t_serial_movement_out');
    $this->db->WHERE('g_t_serial_movement_out.cl', $this->sd['cl']);
    $this->db->WHERE('g_t_serial_movement_out.bc', $this->sd['branch']);
    $this->db->WHERE('g_t_serial_movement_out.trans_type','63');
    $this->db->WHERE('g_t_serial_movement_out.trans_no',$_POST['qno']);
    $r_detail['serial'] = $this->db->get()->result();


    $this->db->select(array('loginName'));
    $this->db->where('cCode', $this->sd['oc']);
    $r_detail['user'] = $this->db->get('users')->result();

    $this->db->select(array('def_cash_customer'));
    $r_detail['cash_customer'] = $this->db->get('def_option_sales')->first_row()->def_cash_customer;

    $this->db->select(array('cus_name','cus_address','memo'));
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $this->db->where('nno', $_POST['qno']);
    $r_detail['cash_sum'] = $this->db->get('g_t_sales_sum')->result();

    $sql="SELECT is_approve FROM g_t_sales_sum
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
    AND nno='". $_POST['qno']."'";
    $approve=$this->db->query($sql)->row()->is_approve;
    if($approve==1){
        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
    }else{
       echo "<script>alert('Transaction Not Approved For Get Print');window.close();</script>";
   }
}

public function get_payment_option() {
    $this->db->where("code", $_POST['code']);
    $data['result'] = $this->db->get("r_payment_option")->result();
    echo json_encode($data);
}

public function get_points() {
    $query = $this->db->query("
        SELECT t_previlliage_trans.card_no,(SUM(t_previlliage_trans.dr)-SUM(t_previlliage_trans.cr)) AS points FROM t_previlliage_trans 
        JOIN t_privilege_card ON t_previlliage_trans.card_no=t_privilege_card.card_no 
        WHERE t_privilege_card.customer_id='" . $_POST['customer'] . "'
        GROUP BY card_no
        ");


    $data['points_res'] = $query->first_row();

    echo json_encode($data);
}

public function get_points2() {
    $query = $this->db->query("
        SELECT card_no,(SUM(dr)-SUM(cr)) AS points FROM t_previlliage_trans 
        WHERE card_no='" . $_POST['type'] . "'
        GROUP BY card_no
        ");


    $data['points_res'] = $query->first_row();
    echo json_encode($data);
}

public function get_points3() {
    $query = $this->db->query("
     SELECT card_no,(SUM(dr)-SUM(cr)) AS points FROM t_previlliage_trans WHERE card_no='" . $_POST['card_no'] . "' 
     AND trans_type='" . $_POST['trans_type'] . "' AND trans_no<>'" . $_POST['trans_no'] . "' GROUP BY card_no;
     ");
    $data['points_res'] = $query->first_row();
    echo json_encode($data);
}

public function check_pv_no() {
    $this->db->select(array("card_no"));
    $this->db->where('card_no', $_POST['privi_card']);
    $this->db->limit(1);
    echo $this->db->get("t_privilege_card")->num_rows;
}




public function check_is_serial_items($code) {
        /*$this->db->select(array('serial_no'));
        $this->db->where("code", $code);
        $this->db->limit(1);
        $query = $this->db->get('m_item');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                return $row->serial_no;
            }
        }*/
        return "1";
    }


    public function is_serial_entered($trans_no, $item, $serial) {
        $this->db->select(array('available'));
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("serial_no", $serial);
        $this->db->where("item", $item);
        $query = $this->db->get("g_t_serial");

        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }


    public function get_batch_serial_wise($item, $serial) {
        $this->db->select("batch");
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("item", $item);
        $this->db->where("serial_no", $serial);
        return $this->db->get('g_t_serial')->first_row()->batch;
    }


    public function item_free(){

        $qty=$_POST['quantity'];
        $item_code=$_POST['item'];
        $date=$_POST['date'];

        $query = $this->db->query("
         SELECT  m.code ,mf.qty
         FROM m_item m
         JOIN m_item_free_det mfd ON mfd.`item` = m.`code`
         JOIN m_item_free mf ON mf.`nno` = mfd.`nno`
         WHERE mf.`code`='$item_code' 
         AND mf.`qty`<='$qty' 
         AND date_from < '$date'
         AND date_to > '$date'");

        if ($query->num_rows() > 0) {
            $a['a'] = $query->result();
        } else {
            $a = 2;
        }
        echo json_encode($a);
    }


    public function item_free_delete(){

        $qty=$_POST['quantity'];
        $item_code=$_POST['item'];

        $query = $this->db->query("
         SELECT  m.code  
         FROM m_item m
         JOIN m_item_free_det mfd ON mfd.`item` = m.`code`
         JOIN m_item_free mf ON mf.`nno` = mfd.`nno`
         WHERE mf.`code`='$item_code'");

        if ($query->num_rows() > 0) {
            $a['a'] = $query->result();
        } else {
            $a = 2;
        }
        echo json_encode($a);
    }


    public function item_free_list(){

        $qty=$_POST['quantity'];
        $item_code=$_POST['item'];
        $date=$_POST['date'];

        $sql = "SELECT  m.code , m.description, m.model, m.max_price, mf.qty
        FROM m_item m
        JOIN m_item_free_det mfd ON mfd.`item` = m.`code`
        JOIN m_item_free mf ON mf.`nno` = mfd.`nno`
        WHERE mf.`code`='$item_code' 
        AND mf.`qty`<='$qty' 
        AND date_from < '$date'
        AND date_to > '$date'";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {


            $a = "<table id='free_item_list' style='width : 100%' >";

            $a .= "<thead><tr class='cl'><td colspan='6' style='font-weight:bold;background:#aaa;color:#fff;font-size:14px; word-spacing: 2px'>Free Issue Items List</td></tr><tr>";$a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Code</th>";
            $a .= "<th class='tb_head_th'>Description</th>";
            $a .= "<th class='tb_head_th'>model</th>";
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
                $a .= "<td>" . $r->max_price . "</td>";
                $a .= "<td style='display:none;'>" . $r->qty . "</td>";
                $a .= "<td style='display:none;'>1</td>";


                $a .= "</tr>";
            }
            $a .= "</table>";


        } else {
            $a = 2;
        }
        echo $a;
    }



    public function det_box(){
        $html="<div style='margin:5px auto;width:800px;border:1px solid #ccc;'><table><tr>";
        $html.="<td>Is User Available For Invoice Approve</td>";
        $html.="<td>&nbsp;</td>";
        $html.="<td></td>";
        $html.="</tr></table><hr>";
        $html.="<table border='1' style='width:100%;'>

        <tr><td colspan='5' style='background:#AAA;color:#fff;font-weight:bolder;'>PENDING INVOICE LIST</td></tr>
        <tr>
            <td style='background:#ccc;width:75px;'>&nbsp;Invoice No</td>
            <td style='background:#ccc; width:400px;'>&nbsp;Approved By</td>
            <td style='background:#ccc; width:100px;'>&nbsp;Date</td>
            <td style='background:#ccc; width:100px;'>&nbsp;Time</td>
            <td style='background:#ccc; width:100px;'>&nbsp;</td>
        </tr>

        <tr>
            <td style='width:75px;text-align:center;'>12</td>
            <td >&nbsp;Susantha Wickramasinghe</td>
            <td style='width:100px;'>&nbsp;2014-11-11</td>
            <td style='width:100px;'>&nbsp;12.05 PM</td>
            <td style='width:100px;text-align:center;'><input type='button' title='Load'/></td>
        </tr>

        <tr><td colspan='5' style='background:#AAA;color:#fff;font-weight:bolder;'>APPROVE INVOICE LIST</td></tr>
        <tr>
            <td style='background:#ccc;width:75px;'>&nbsp;Invoice No</td>
            <td style='background:#ccc; width:400px;'>&nbsp;Approved By</td>
            <td style='background:#ccc; width:100px;'>&nbsp;Date</td>
            <td style='background:#ccc; width:100px;'>&nbsp;Time</td>
            <td style='background:#ccc; width:100px;'>&nbsp;</td>
        </tr>

        <tr>
            <td style='width:75px;text-align:center;'>28</td>
            <td >&nbsp;Susantha Wickramasinghe</td>
            <td style='width:100px;'>&nbsp;2014-11-11</td>
            <td style='width:100px;'>&nbsp;12.05 PM</td>
            <td style='width:100px;text-align:center;'><input type='button' title='Load'/></td>
        </tr>




    </table>";
    $html.="</div>";
    return $html;
}   

public function pending_special_sales(){
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $sql="SELECT * FROM g_t_sales_sum WHERE cl='$cl' AND bc='$bc' AND is_approve='0'";
    $query=$this->db->query($sql);

    $html="<div style='margin:5px auto;width:800px;border:1px solid #ccc;'><table><tr>";
    $html.="<td>Is User Available For Invoice Approve</td>";
    $html.="<td>&nbsp;</td>";
    $html.="<td></td>";
    $html.="</tr></table><hr>";
    $html.="<table border='1' style='width:100%;'>

    <tr><td colspan='5' style='background:#AAA;color:#fff;font-weight:bolder;'>PENDING INVOICE LIST</td></tr>
    <tr>
        <td style='background:#ccc;width:75px;'>&nbsp;Invoice No</td>
        <td style='background:#ccc; width:400px;'>&nbsp;Approved By</td>
        <td style='background:#ccc; width:100px;'>&nbsp;Date</td>
        <td style='background:#ccc; width:100px;'>&nbsp;Time</td>
        <td style='background:#ccc; width:100px;'>&nbsp;</td>
    </tr>";
    foreach($query->result() as $row){
        $time=explode(" ",$row->action_date);
        $html.="<tr>
        <td style='width:75px;text-align:center;'>".$row->nno."</td>
        <td >&nbsp;".$row->cus_id."</td>
        <td style='width:100px;'>&nbsp;".$row->ddate."</td>
        <td style='width:100px;'>&nbsp;".$time[1]."</td>
        <td style='width:100px;text-align:center;'><input type='button' title='Load' onclick='load_data_form(\"".$row->nno."\")' /></td>
    </tr>";    
}        

$html.=" </table>";
$html.="</div>";
return $html;


}



public function load_b_foc(){     

    $date = $_POST['date'];
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

    $sql = "SELECT code,name,dfrom,dto FROM m_item_free_sales 
    WHERE `dfrom` <= '$date' AND  `dto` >= '$date' AND is_cancel=0  ";

    $query = $this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th'>Name</th>";
    $a .= "<th class='tb_head_th'>From</th>";
    $a .= "<th class='tb_head_th'>To</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->code."</td>";
      $a .= "<td>".$r->name."</td>";
      $a .= "<td>".$r->dfrom."</td>";
      $a .= "<td>".$r->dto."</td>";
      $a .= "</tr>";
  }
  $a .= "</table>";
  echo $a;
}


public function load_foc_items(){     
    $id = $_POST['code'];
    $date = $_POST['date'];

    $sql = "SELECT po_item AS code, i.`description`, i.`model`,i.`purchase_price` AS cost,i.`min_price` ,i.`max_price` as price , po_qty  AS qty, 0 AS is_free
    FROM m_item_free_sales
    JOIN  m_item_free_sales_det ON m_item_free_sales.`nno` = m_item_free_sales_det.`nno` AND m_item_free_sales.`cl` = m_item_free_sales_det.`cl` AND m_item_free_sales.`bc` = m_item_free_sales_det.`bc`
    JOIN m_item i ON i.`code` = m_item_free_sales_det.`po_item`
    WHERE m_item_free_sales.code='$id' AND m_item_free_sales.`dfrom` <= '$date' AND  m_item_free_sales.`dto` >= '$date' 
    UNION ALL
    SELECT foc_item AS code, i.`description`,i.`model` ,i.`purchase_price` AS cost,i.`min_price` ,i.`max_price` as price, foc_qty  AS qty, 1 AS is_free              
    FROM m_item_free_sales
    JOIN  m_item_free_sales_det ON m_item_free_sales.`nno` = m_item_free_sales_det.`nno` AND m_item_free_sales.`cl` = m_item_free_sales_det.`cl` AND m_item_free_sales.`bc` = m_item_free_sales_det.`bc`
    JOIN m_item i ON i.`code` = m_item_free_sales_det.`foc_item`
    WHERE m_item_free_sales.code='$id' AND m_item_free_sales.`dfrom` <= '$date' AND  m_item_free_sales.`dto` >= '$date' 

    ";

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
        $a['a'] = $query->result();
    } else {
        $a = 2;
    }
    echo json_encode($a);
}





public function customer_list(){



    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

    $sql = "SELECT * FROM m_customer  WHERE code LIKE '%$_POST[search]%' OR name LIKE '%$_POST[search]%' LIMIT 25";


    $query = $this->db->query($sql);

    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th' colspan='2'>Name</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->code."</td>";
      $a .= "<td>".$r->name."</td>";
      $a .= "<td><input type='hidden' class='' value='".$r->address1." ".$r->address2." ".$r->address3."' title='".$r->address1." ".$r->address2." ".$r->address3."' /></td>";   
      $a .= "<td><input type='hidden' class='' value='".$this->utility->get_account_balance($r->code)."' title='".$this->utility->get_account_balance($r->code)."' /></td>";        
      $a .= "</tr>";
  }
  $a .= "</table>";
  echo $a;
}


public function load_default_customer(){

    $bc=$_POST['branch'];
    $cl=$_POST['cluster'];

    $sql="SELECT 
    m_branch.`def_cash_customer` AS def_customer,
    m_customer.`name` AS c_name,
    m_branch.`def_sales_store`,
    m_branch.`def_sales_category`,
    m_branch.`def_sales_group` 
    FROM
    m_branch 
    JOIN m_customer 
    ON m_branch.`cl` = m_customer.`cl` 
    AND m_branch.`bc` = m_customer.`bc` 
    AND m_branch.`def_cash_customer` = m_customer.`code` 
    WHERE m_branch.`cl`='$cl' AND m_branch.`bc`='$bc'";

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
     $a= $query->result();
 } else {
    $a = 2;
}
echo json_encode($a);

}

}
