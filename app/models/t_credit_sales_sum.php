<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class t_credit_sales_sum extends CI_Model {

  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';

  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_credit_sales_sum'];
    $this->load->model('user_permissions');
  }

  public function base_details() {
    $this->load->model('r_sales_category');
    $a['sales_category'] = $this->r_sales_category->select();

    $this->load->model('r_groups');
    $a['groups'] = $this->r_groups->select();

      //$this->load->model('m_customer');
      //$a['customer'] = $this->m_customer->select();

    $this->load->model('m_stores');
    $a['stores'] = $this->m_stores->select();

    $this->load->model("utility");
    $a['max_no'] = $this->utility->get_max_no("t_credit_sales_sum", "nno");

    $a["crn_no"] = $this->get_credit_max_no();

    $a['type'] = "CREDIT";

    $a["cl"] = $this->sd['cl'];

    $a["bc"] = $this->sd['branch'];
    
    return $a;
  }

  public function get_credit_max_no() {
    if (isset($_POST['hid'])) {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        $field = "nno";
        $this->db->select_max($field);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        return $this->db->get("t_credit_note")->first_row()->$field + 1;
      }else{
        return $_POST['crn_no'];
      }
    }else{
      $field = "nno";
      $this->db->select_max($field);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      return $this->db->get("t_credit_note")->first_row()->$field + 1;
    }
  }


  public function validation(){

    $this->max_no = $this->utility->get_max_no("t_credit_sales_sum", "nno");
    
    $status=1;

    $check_zero_value=$this->validation->empty_net_value($_POST['net']);
    if($check_zero_value!=1){
      return $check_zero_value;
    }
        // if (empty($_POST['save_status']) && $_POST['save_status'] != "1") {
        //   return "Please check the payment option";
        // }
        /*$check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_credit_sales_sum');
        if($check_is_delete!=1){
          return "Credit sale already deleted";
        }*/
        /*$customer_validation = $this->validation->check_is_customer($_POST['customer']);
        if ($customer_validation != 1){
          return "Please enter valid customer";
        }*/
        /*$employee_validation = $this->validation->check_is_employer($_POST['sales_rep']);
        if ($employee_validation != 1){
          return "Please enter valid sales rep";
        }*/
        /*$store_validation = $this->validation->check_item_with_store($_POST['stores'], '0_');
        if ($store_validation != 1){
          return $store_validation;
        }*/
        $minimum_price_validation = $this->validation->check_min_price2('0_', '3_', 'free_price_','7_','f_','1_');
        if ($minimum_price_validation != 1){
          return $minimum_price_validation;
        }
        // $minimum_price_validation = $this->validation->check_min_price('0_', '3_','f_');
        // if ($minimum_price_validation != 1){
        //   return $minimum_price_validation;
        // }
        $serial_validation_status = $this->validation->serial_update('0_', '5_',"all_serial_");
        if ($serial_validation_status != 1){
          return $serial_validation_status;
        }
        $batch_validation_status = $this->validation->batch_update('0_', '1_', '5_', '4_');
        if ($batch_validation_status != 1){
          return $batch_validation_status;
        }
        $payment_option_validation = $this->validation->payment_option_calculation();
        if ($payment_option_validation != 1){
          return $payment_option_validation;
        }
        
        $check_cash_limit=$this->validation->sales_limit_with_customer($_POST['net'],$_POST['customer']);
        if($check_cash_limit!=1){
          return $check_cash_limit;
        } 

        /*$account_update=$this->account_update(0);
        if($account_update!=1){
          return "Invalid account entries";
        } */

        //Payment option credit note
        $check_valid_dr_no=$this->validation->check_valid_trans_no2('customer','t_code_','no2_','cl_','bc_');
        if($check_valid_dr_no!=1){
          return $check_valid_dr_no;
        }
        $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle2('customer','t_code_','no2_','settle2_','cl_','bc_');
        if($check_valid_trans_settle_status!=1){
          return $check_valid_trans_settle_status; 
        }
        //Payment option debit note
        $check_valid_dr_no2=$this->validation->check_valid_trans_no2('customer','t_code3_','no3_','cl3_','bc3_');
        if($check_valid_dr_no2!=1){
          return $check_valid_dr_no2;
        }
        $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle2('customer','t_code3_','no3_','settle3_','cl3_','bc3_');
        if($check_valid_trans_settle_status2!=1){
          return $check_valid_trans_settle_status2; 
        }

        $chk_zero_qty=$this->validation->empty_qty('0_','5_','f_');
        if($chk_zero_qty!=1){
          return $chk_zero_qty;
        }

        $chk_customer_limit=$this->customer_limit($_POST['customer']);
        if($chk_customer_limit!=1){
          return $chk_customer_limit;
        }
        
        return $status;
      }


      public function save() {
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg."=".$errFile."=".$errLine); 
        }
        set_error_handler('exceptionThrower'); 
        try {

          $this->load->model("utility");

          $this->credit_max_no = $this->get_credit_max_no();
          
          $validation_status=$this->validation();
          if($validation_status==1){

            $_POST['acc_codes']=$_POST['customer'];
            
            

            for ($x = 0; $x < 25; $x++) {
              if (isset($_POST['00_' . $x],  $_POST['tt_' . $x])) {
                if ($_POST['00_' . $x] != ""  && $_POST['tt_' . $x] != "") {
                  $t_credit_sales_additional_item[] = array(
                    "cl" => $this->sd['cl'],
                    "bc" => $this->sd['branch'],
                    "nno" => $this->max_no,
                    "type" => $_POST['00_' . $x],
                    "rate_p" => $_POST['11_' . $x],
                    "amount" => $_POST['tt_' . $x]
                  );
                }
              }
            }

            if($_POST['groups']!='0'){
              $group=$_POST['groups'];
            }else{
              $group=$this->utility->default_group();
            }

            $total=(float)"0";

            for ($x = 0; $x < 25; $x++) {
              if (isset($_POST['0_' . $x], $_POST['5_' . $x], $_POST['free_price_' . $x])) {
                if ($_POST['0_' . $x] != "" && $_POST['5_' . $x] != "" && $_POST['free_price_' . $x] != "") {

                  $balance="0";
                  
                  $c =$_POST['bal_tot_' . $x];
                  $d = explode("-",$c);
                  
                  if(isset($d[0])) {
                   $balance = $d[0];
                 }
                 if(isset($d[1])) {
                   $tot =(float)$d[1];
                   $total=$total+$tot;
                 }

                              //$_POST['free_price_' . $x]=$_POST['3_' . $x];

                 $t_credit_sales_det[] = array(
                  "cl" => $this->sd['cl'],
                  "bc" => $this->sd['branch'],
                  "nno" => $this->max_no,
                  "code" => $_POST['0_' . $x],
                  "qty" => $_POST['5_' . $x],
                  "price" => $_POST['free_price_' . $x], 
                  "discountp" => $_POST['6_' . $x],
                  "discount" => $_POST['7_' . $x],
                  "cost" => $_POST['cost_' . $x],
                  "foc" => $_POST['4_' . $x],
                  "batch_no" => $_POST['1_' . $x],
                  "warranty" => $_POST['9_' . $x],
                  "amount" => $_POST['8_' . $x],
                  "avg_price" => $_POST['cost_' . $x],
                  "min_price"=>$_POST['item_min_price_' . $x],
                  "is_free"=>$_POST['f_' . $x],
                  "free_balance"=>$balance,
                  "foc_amount"=>$_POST['tot_foc_'.$x],
                  "discount_total"=>$_POST['tot_dis_' .$x]
                );
               }
             }
           }


           $t_credit_sales_sum = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "oc" => $this->sd['oc'],
            "nno" => $this->max_no,
            "type" => $_POST['type'],
            "ddate" => $_POST['date'],
            "ref_no" => $_POST['ref_no'],
            "cus_id" => $_POST['customer'],
            "so_no" => $_POST['serial_no'],
            "category" => $_POST['sales_category1'],
            "cus_code" => $_POST['bill_cus_id'],
            "sub_no" => $this->utility->get_max_sales_category2('sub_no','t_credit_sales_sum',$_POST['sales_category1']),
            "memo" => $_POST['memo'],
            "store" => $_POST['stores'],
            "quotation" => $_POST['quotation'],
            "rep" => $_POST['sales_rep'],
            "gross_amount" => $_POST['gross1'],
            "group_no" => $_POST['groups'],
            "additional" => $_POST['additional_amount'],
            "net_amount" => $_POST['net'],
            "post" => "",
            "post_by" => "",
            "previlliage_card_no" => $_POST['privi_card'],
            "previlliage_point_add" => $_POST['points'],
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
            "pay_installment" => $_POST['hid_installment'],
            "ins_down_payment" => $_POST['hid_ins_down_payment'],
            "ins_rate_per_month" => $_POST['hid_ins_rate_per_month'],
            "ins_period_by_days" => $_POST['hid_ins_period_by_days'],
            "num_of_installment" => $_POST['hid_num_of_installment'],
            "crn_no" =>$total!=0?$_POST['crn_no']:0,
            "balance"=>isset($_POST['credit'])?$_POST['credit']:$_POST['installment'],
            "total_foc_amount"=>$_POST['free_tot'],
            "is_install"=>(isset($_POST['ci']) && $_POST['ci']=='c')?0:1,
            "credit_period"=>$_POST['credit_period'],
            "is_bulk_foc"=>$_POST['is_foc'],
            "cus_name"=>$_POST['bill_cuss_name'],
            "cus_address"=>$_POST['cus_address'],
            "do_no"=>$_POST['do_no'],
            "receipt_no"=>$_POST['rcpt_no'],
            "discount_amount"=>$_POST['dis_amount'],
            "additional_add"=>$_POST['additional_add'],
            "additional_deduct"=>$_POST['additional_deduct'],
          );

           $t_credit_note = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "nno" => $this->credit_max_no,
            "ddate" => $_POST['date'],
            "ref_no" => $_POST['ref_no'],
            "memo" => "FREE ISSUE ITEM - CREDIT_SALES [" . $this->max_no . "]",
            "is_customer" => 1,
            "code" => $_POST['customer'],
            "acc_code" => $this->utility->get_default_acc('STOCK_ACC'),
            "amount" => $total,
            "oc" => $this->sd['oc'],
            "post" => "",
            "post_by" => "",
            "post_date" => "",
            "is_cancel" => 0,
            "ref_trans_no" => $this->max_no,
            "ref_trans_code" =>5,
            "balance"=>$total
          );


           if (isset($_POST['hid_pc']) && !empty($_POST['hid_pc'])) {
            $t_previlliage_trans = array(
              "cl" => $this->sd['cl'],
              "bc" => $this->sd['branch'],
              "trans_type" => $_POST['type'],
              "trans_no" => $this->max_no,
              "dr" => 0,
              "card_no" => $_POST['hid_pc_type'],
              "cr" => $_POST['hid_pc'],
              "ddate" => $_POST['date']
            );
          }

          if (isset($_POST['points']) && !empty($_POST['points'])) {
            $t_previlliage_trans2 = array(
              "cl" => $this->sd['cl'],
              "bc" => $this->sd['branch'],
              "trans_type" => $_POST['type'],
              "trans_no" => $this->max_no,
              "dr" => $_POST['points'],
              "card_no" => $_POST['privi_card'],
              "cr" => 0,
              "ddate" => $_POST['date']
            );
          }

          $sql="SELECT * FROM `t_loyalty_card` i
          JOIN `t_loyalty_card_category` c ON i.`category`=c.`code`
          WHERE customer_id='".$_POST['customer']."'";

          $query=$this->db->query($sql)->result();
          foreach ($query as $value) {
            $earnrs=$value->earn_rs;
            $earnpt=$value->earn_point;
          }
          $point=($_POST['net']/$earnrs)*$earnpt;

          $this->load->model('trans_settlement');

          if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
            if($this->user_permissions->is_add('t_credit_sales_sum')){
              $account_update=$this->account_update(0);
              if($account_update==1){
                if($_POST["df_is_serial"]=='1'){
                  $this->serial_save(); 
                }
                $this->save_sub_items();
                $this->account_update(1);

                $this->load->model('trans_settlement');
                
                if($total!=0){
                  $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['customer'], $_POST['date'], 17, $this->credit_max_no, 5, $this->max_no, $total, "0");
                  $this->db->insert('t_credit_note', $t_credit_note);
                }


                $this->trans_settlement->save_settlement("t_loyalty_point_trans", $_POST['customer'], $_POST['date'], 5, $this->max_no, 5, $this->max_no, $point, "0");

                $this->db->insert($this->mtb, $t_credit_sales_sum);

                //if (isset($t_credit_sales_det)) {
                if (count($t_credit_sales_det)) {
                  $this->db->insert_batch("t_credit_sales_det", $t_credit_sales_det);
                }
                //}
                if (isset($t_credit_sales_additional_item)) {
                  if (count($t_credit_sales_additional_item)) {
                    $this->db->insert_batch("t_credit_sales_additional_item", $t_credit_sales_additional_item);
                  }
                }
                
                $this->load->model('trans_settlement');
                for ($x = 0; $x < 25; $x++) {
                  if (isset($_POST['0_' . $x], $_POST['5_' . $x], $_POST['free_price_' . $x])) {
                    if ($_POST['0_' . $x] != "" && $_POST['5_' . $x] != "" && $_POST['free_price_' . $x] != "") {
                      $this->trans_settlement->save_item_movement('t_item_movement',$_POST['0_'.$x],'5',$this->max_no,$_POST['date'],0,$_POST['5_'.$x],$_POST['stores'],$this->utility->get_cost_price($_POST['0_' . $x]),$_POST['1_'.$x],$this->utility->get_cost_price($_POST['0_' . $x]),$_POST['3_'.$x],$this->utility->get_min_price($_POST['0_' . $x]),$group);
                    }
                  }
                }
                


                $this->load->model('t_payment_option');
                $this->t_payment_option->save_payment_option($this->max_no, 5);
                $amount=isset($_POST['credit'])?$_POST['credit']:$_POST['installment'];
                $this->trans_settlement->save_settlement("t_cus_settlement", $_POST['customer'], $_POST['date'], $_POST['type'], $this->max_no, $_POST['type'], $this->max_no, $amount, "0");
                

                if (isset($_POST['hid_pc']) && !empty($_POST['hid_pc'])) {
                  $this->db->insert("t_previlliage_trans", $t_previlliage_trans);
                }

                if (isset($_POST['points']) && !empty($_POST['points'])) {
                  $this->db->insert("t_previlliage_trans", $t_previlliage_trans2);
                }

                $this->utility->save_logger("SAVE",5,$this->max_no,$this->mod);
                
                $this->utility->update_credit_note_balance($_POST['customer']);
                $this->utility->update_credit_sale_balance($_POST['customer']);
                $this->utility->update_debit_note_balance($_POST['customer']);

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
            if($this->user_permissions->is_edit('t_credit_sales_sum')){
            /*$status=$this->trans_cancellation->credit_sales_update_status($this->max_no);     
            if($status=="OK"){*/
              $account_update=$this->account_update(0);
              if($account_update==1){
                $this->set_delete();
                if($_POST["df_is_serial"]=='1'){
                  $this->serial_save(); 
                }
                $this->save_sub_items();
                $this->account_update(1);

                $this->load->model('trans_settlement');

                if($total!=0 || $_POST['crn_no_hid']!=0){

                  $this->trans_settlement->delete_settlement("t_credit_note_trans", 17, $this->credit_max_no);
                  $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['customer'], $_POST['date'], 17, $this->credit_max_no, 5, $this->max_no, $total, "0");

                  if($_POST['crn_no_hid']!=0){
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where('nno', $this->credit_max_no);
                    $this->db->update('t_credit_note', $t_credit_note);
                  }else{
                    $this->db->insert('t_credit_note', $t_credit_note);
                  }

                }
                
                $this->load->model('trans_settlement');
                $amount=isset($_POST['credit'])?$_POST['credit']:$_POST['installment'];
                $this->trans_settlement->delete_settlement_sub("t_cus_settlement", $_POST['type'], $this->max_no);
                $this->trans_settlement->save_settlement("t_cus_settlement", $_POST['customer'], $_POST['date'], $_POST['type'], $this->max_no, $_POST['type'], $this->max_no, $amount, "0");


                $this->db->where('nno', $_POST['hid']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->update($this->mtb, $t_credit_sales_sum);

                $this->load->model('t_payment_option');
                $this->t_payment_option->delete_settlement("t_ins_trans", $_POST['type'], $this->max_no);
                $this->t_payment_option->delete_all_payments_opt(5, $this->max_no);
                

                $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","5",$this->max_no);   
                $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","5",$this->max_no); 

                $this->trans_settlement->delete_settlement_sub("t_loyalty_point_trans","5",$this->max_no);   
                $this->trans_settlement->save_settlement("t_loyalty_point_trans", $_POST['customer'], $_POST['date'], 5, $this->max_no, 5, $this->max_no, $point, "0");

                
                $this->t_payment_option->save_payment_option($this->max_no, 5);

                if (isset($t_credit_sales_det)) {
                  if (count($t_credit_sales_det)) {
                    $this->db->insert_batch("t_credit_sales_det", $t_credit_sales_det);
                  }
                }

                if (isset($t_credit_sales_additional_item)) {
                  if (count($t_credit_sales_additional_item)) {
                    $this->db->insert_batch("t_credit_sales_additional_item", $t_credit_sales_additional_item);
                  }
                }

                if($_POST["df_is_serial"]=='1')
                {
                  if (isset($t_serial_movement_out)) {
                    if (count($t_serial_movement_out)) {
                      $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);
                    }
                  }
                }

                $this->load->model('trans_settlement');
                for ($x = 0; $x < 25; $x++) {
                  if (isset($_POST['0_' . $x], $_POST['5_' . $x], $_POST['free_price_' . $x])) {
                    if ($_POST['0_' . $x] != "" && $_POST['5_' . $x] != "" && $_POST['free_price_' . $x] != "") {
                      $this->trans_settlement->save_item_movement('t_item_movement',$_POST['0_'.$x],'5',$this->max_no,$_POST['date'],0,$_POST['5_'.$x],$_POST['stores'],$this->utility->get_cost_price($_POST['0_' . $x]),$_POST['1_'.$x],$this->utility->get_cost_price($_POST['0_' . $x]),$_POST['3_'.$x],$this->utility->get_min_price($_POST['0_' . $x]),$group);
                    }
                  }
                }

                if (isset($_POST['hid_pc']) && !empty($_POST['hid_pc'])) {
                  $this->db->where('trans_no', $_POST['hid']);
                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->update("t_previlliage_trans", $t_previlliage_trans);
                }

                if (isset($_POST['points']) && !empty($_POST['points'])) {
                  $this->db->where('trans_no', $_POST['hid']);
                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->update("t_previlliage_trans", $t_previlliage_trans2);
                }

                $this->utility->update_credit_note_balance($_POST['customer']);
                $this->utility->update_credit_sale_balance($_POST['customer']);
                $this->utility->update_debit_note_balance($_POST['customer']);
                
                $this->utility->save_logger("EDIT",5,$this->max_no,$this->mod);
                echo $this->db->trans_commit()."@".$this->max_no;
            /*}else{
              echo $status;
              $this->db->trans_commit();
            }*/
          }else{
            echo "Invalid account entries";
            $this->db->trans_commit();
          }
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
    echo $e->getMessage()."Operation fail please contact admin"; 
  }  
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
                "out_doc" => 5,
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
                "trans_type" => 5,
                "trans_no" => $this->max_no,
                "item" => $_POST['0_'.$x],
                "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
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

  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("out_no", $this->max_no);
  $this->db->where("out_doc", 5);
  $this->db->update("t_serial", $t_serial);

  $this->db->select(array('item', 'serial_no'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("trans_no", $this->max_no);
  $this->db->where("trans_type",5);
  $query = $this->db->get("t_serial_movement_out");

  foreach ($query->result() as $row) {
    $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='$row->item' AND serial_no='$row->serial_no'");
    $this->db->where("item", $row->item);
    $this->db->where("serial_no", $row->serial_no);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_serial_movement_out");
  }

  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("trans_no", $this->max_no);
  $this->db->where("trans_type", 5);
  $this->db->delete("t_serial_movement");

  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("trans_no", $this->max_no);
  $this->db->where("trans_type", 5);
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
            "out_doc" => 5,
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
            "trans_type" => 5,
            "trans_no" => $this->max_no,
            "item" => $_POST['0_'.$x],
            "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
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
          $this->db->where("item", $_POST['0_'.$x]);
          $this->db->where("serial_no",  $p[$i]);
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
    }else{
      if (isset($t_serial_movement_out)) {
        if (count($t_serial_movement_out)) {
          $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);
        }
      }
    }
  }

  private function set_delete() {
    $this->db->where("nno", $this->max_no);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_credit_sales_det");

    $this->db->where("nno", $this->max_no);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_credit_sales_additional_item");

    $this->load->model('trans_settlement');
    $this->trans_settlement->delete_item_movement('t_item_movement',5,$this->max_no);

  }

  public function account_update($condition) {

    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code",5);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");

    
    if($_POST['hid']=="0"||$_POST['hid']==""){

    }else{
      if($condition=="1"){
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code',5);
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
      "ref_no" => $_POST['do_no']
    );

    $des = "Invoice - " . $_POST['bill_cuss_name'];
    $this->load->model('account');
    $this->account->set_data($config);


    $total_discount=$_POST['total_discount'];
    $total_amount=$_POST['total_amount'];

    if($total_discount>0){
      $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
      $this->account->set_value2("Sales Discount", $total_discount, "dr", $acc_code,$condition);
    }

    for($x = 0; $x<25; $x++){
      if(isset($_POST['00_'.$x]) && isset($_POST['tt_'.$x])){
        if(!empty($_POST['00_'.$x]) && !empty($_POST['tt_'.$x])){

          $sql="select is_add,account,description from r_additional_item where code ='".$_POST['00_'.$x]."'";

          $query   =$this->db->query($sql);

          $con     =$query->row()->is_add;
          $dess    =$query->row()->description;   
          $acc_code=$query->row()->account;


                        /*$this->db->select(array('is_add','account'));
                        $this->db->where('code',$_POST['00_'.$x]);


                        $this->db->select(array('is_add'));
                        $this->db->where('code',$_POST['00_'.$x]);
                        $con=$this->db->get("r_additional_item")->first_row()->is_add;

                        $this->db->select(array('description'));
                        $this->db->where('code',$_POST['00_'.$x]);
                        $dess=$this->db->get("r_additional_item")->first_row()->description;

                        $this->db->select(array('account'));
                        $this->db->where('code',$_POST['00_'.$x]);
                        $acc_code=$this->db->get("r_additional_item")->first_row()->account;*/
                        
                        if($con=="1"){
                         $this->account->set_value2($dess,$_POST['tt_'.$x], "cr", $acc_code,$condition);  
                       }elseif($con=="0"){
                         $this->account->set_value2($dess,$_POST['tt_'.$x], "dr", $acc_code,$condition); 
                       }elseif($condition=="0"){
                         $this->account->set_value2($dess,$_POST['tt_'.$x], "dr", $acc_code,$condition);    
                       }
                     }
                   }
                 }  

                 $acc_code = $this->utility->get_default_acc('CREDIT_SALES');
                 $this->account->set_value2($des, $total_amount, "cr", $acc_code,$condition);

            // if(isset($_POST['cash']) && !empty($_POST['cash'])){
            //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
            //   $this->account->set_value2($des, $_POST['cash'], "cr", $acc_code,$condition);    
            // }

                 if(isset($_POST['credit']) && !empty($_POST['credit']) && $_POST['credit']>0){
                  $this->account->set_value2($des, $_POST['credit'], "dr", $_POST['customer'],$condition);    
                }

            // if(isset($_POST['cheque_issue']) && !empty($_POST['cheque_issue'])){
            //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
            //   $this->account->set_value2($des, $_POST['cheque_issue'], "dr", $acc_code,$condition);    
            // }

                if(isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card']>0){
                  for($x = 0; $x<25; $x++){
                    if(isset($_POST['type1_'.$x]) && isset($_POST['amount1_'.$x]) && isset($_POST['bank1_'.$x]) && isset($_POST['no1_'.$x])){
                      if(!empty($_POST['type1_'.$x]) && !empty($_POST['amount1_'.$x]) && !empty($_POST['bank1_'.$x]) && !empty($_POST['no1_'.$x])){
                            // $acc_code = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
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

                if(isset($_POST['credit_note']) && !empty($_POST['credit_note'])&& $_POST['credit_note']>0){
              //$acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
                  $acc_code = $_POST['customer'];
                  $this->account->set_value2($des, $_POST['credit_note'], "dr", $acc_code,$condition);    
                }

            // if(isset($_POST['debit_note']) && !empty($_POST['debit_note'])){
            //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
            //   $this->account->set_value2($des, $_POST['debit_note'], "dr", $acc_code,$condition);    
            // }

            // if(isset($_POST['discount']) && !empty($_POST['discount'])){
            //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
            //   $this->account->set_value2($des, $_POST['discount'], "dr", $acc_code,$condition);    
            // }
            // if(isset($_POST['gv']) && !empty($_POST['gv'])){
            //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
            //   $this->account->set_value2($des, $_POST['gv'], "dr", $acc_code,$condition);    
            // }
            // if(isset($_POST['pc']) && !empty($_POST['pc'])){
            //   $acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
            //   $this->account->set_value2($des, $_POST['pc'], "dr", $acc_code,$condition);    
            // }
                if(isset($_POST['installment']) && !empty($_POST['installment'])){
                  $this->account->set_value2($des, $_POST['installment'], "dr", $_POST['customer'],$condition);    
                  
                  $this->account->set_value2("Interest Amount", $_POST['total_interest_amount'], "dr", $_POST['customer'],$condition); 

                  $acc_code = $this->utility->get_default_acc('INTEREST_SUSPENCE');
                  $this->account->set_value2($des, $_POST['total_interest_amount'], "cr", $acc_code,$condition); 

                } 
                

        //start updating cost of sales

                $total_item_cost=0;
                for($x=0;$x<25;$x++){
                  if(isset($_POST['0_' . $x])){
                    $total_item_cost=$total_item_cost+(($_POST['5_' . $x])*(double)$this->utility->get_cost_price($_POST['0_'.$x]));
                  }
                }    
                $acc_code=$this->utility->get_default_acc('STOCK_ACC');
                $this->account->set_value2($des, $total_item_cost, "cr", $acc_code,$condition);

                $acc_code=$this->utility->get_default_acc('COST_OF_SALES');
                $this->account->set_value2($des, $total_item_cost, "dr", $acc_code,$condition);

        //end updating cost of sales


                if($condition==0){

                 $query = $this->db->query("
                   SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
                   FROM `t_check_double_entry` t
                   LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
                   WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='5'  AND t.`trans_no` ='" . $this->max_no . "' AND 
                   a.`is_control_acc`='0'");

                 if ($query->row()->ok == "0") {
                  $this->db->where("trans_no", $this->max_no);
                  $this->db->where("trans_code", 5);
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
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->limit(1);

              echo $this->db->get($this->mtb)->num_rows;
            }

            public function load() {

              $this->db->select(array(
                't_credit_sales_sum.cl',
                't_credit_sales_sum.bc',
                't_credit_sales_sum.nno',
                't_credit_sales_sum.type',
                't_credit_sales_sum.ddate',
                't_credit_sales_sum.ref_no',
                't_credit_sales_sum.cus_id',
                't_credit_sales_sum.so_no',
                't_credit_sales_sum.category',
                't_credit_sales_sum.sub_no',
                't_credit_sales_sum.memo',
                't_credit_sales_sum.store',
                't_credit_sales_sum.quotation',
                't_credit_sales_sum.rep',
                't_credit_sales_sum.gross_amount',
                't_credit_sales_sum.group_no',
                't_credit_sales_sum.additional',
                't_credit_sales_sum.net_amount',
                't_credit_sales_sum.oc',
                't_credit_sales_sum.action_date',
                't_credit_sales_sum.post',
                't_credit_sales_sum.post_by',
                't_credit_sales_sum.previlliage_card_no',
                't_credit_sales_sum.previlliage_point_add',
                't_credit_sales_sum.pay_cash',
                't_credit_sales_sum.pay_issue_chq',
                't_credit_sales_sum.pay_receive_chq',
                't_credit_sales_sum.pay_ccard',
                't_credit_sales_sum.pay_cnote',
                't_credit_sales_sum.pay_dnote',
                't_credit_sales_sum.pay_bank_debit',
                't_credit_sales_sum.pay_advance',
                't_credit_sales_sum.pay_discount',
                't_credit_sales_sum.pay_gift_voucher',
                't_credit_sales_sum.pay_credit',
                't_credit_sales_sum.pay_privi_card',
                't_credit_sales_sum.pay_installment',
                't_credit_sales_sum.is_multi_payment',
                't_credit_sales_sum.ins_down_payment',
                't_credit_sales_sum.ins_rate_per_month',
                't_credit_sales_sum.ins_period_by_days',
                't_credit_sales_sum.num_of_installment',
                't_credit_sales_sum.is_cancel',
                't_credit_sales_sum.crn_no',
                't_credit_sales_sum.credit_period',
                't_credit_sales_sum.is_bulk_foc',
                't_credit_sales_sum.cus_name',
                't_credit_sales_sum.cus_address',
                't_credit_sales_sum.do_no',
                't_credit_sales_sum.receipt_no',
                't_credit_sales_sum.additional_add',
                't_credit_sales_sum.additional_deduct',
                't_credit_sales_sum.cus_code',
                'm_customer.name',
                'm_customer.address1',
                'm_customer.address2',
                'm_customer.address3',
                'm_employee.name as rep_name',
              ));

              $this->db->from('t_credit_sales_sum');
              $this->db->join('m_customer', 'm_customer.code=t_credit_sales_sum.cus_id');
              $this->db->join('m_employee', 'm_employee.code=t_credit_sales_sum.rep');

              $this->db->where('t_credit_sales_sum.cl', $this->sd['cl']);
              $this->db->where('t_credit_sales_sum.bc', $this->sd['branch']);
              $this->db->where('t_credit_sales_sum.nno', $_POST['id']);

              $query = $this->db->get();

              $x = 0;
              $acc =0;
              if ($query->num_rows() > 0) {
                $a['sum'] = $query->result();
                $acc=$query->row()->cus_id;
              } else {
                $x = 2;
              }

       // $a['balance'] =$this->utility->get_account_balance($acc);

              $sql_bal="SELECT  SUM(`balance`) as balance FROM m_customer_balance 
              WHERE code='$acc' ";    
              $a['balance'] = $this->db->query($sql_bal)->first_row()->balance; 

              $this->db->select(array(
                't_credit_sales_det.code',
                't_credit_sales_det.qty',
                't_credit_sales_det.discountp',
                't_credit_sales_det.discount',
                't_credit_sales_det.price',
                't_credit_sales_det.amount',
                't_credit_sales_det.foc',
                't_credit_sales_det.batch_no',
                't_credit_sales_det.warranty',
                't_credit_sales_det.is_free',
                't_credit_sales_det.cost',
                't_credit_sales_det.free_balance',
                'm_item.description as item_des',
                't_credit_sales_det.min_price',
                'm_item.model'
              ));

              $this->db->from('t_credit_sales_det');
              $this->db->join('m_item', 'm_item.code=t_credit_sales_det.code');
        //$this->db->join('t_item_batch', 't_item_batch.item = t_credit_sales_det.code AND  t_item_batch.batch_no= t_credit_sales_det.batch_no');
              $this->db->where('t_credit_sales_det.cl', $this->sd['cl']);
              $this->db->where('t_credit_sales_det.bc', $this->sd['branch']);
              $this->db->where('t_credit_sales_det.nno', $_POST['id']);
              $this->db->group_by('m_item.code, t_credit_sales_det.batch_no');
              $this->db->order_by("t_credit_sales_det.auto_num", "asc");
              $query = $this->db->get();

              if ($query->num_rows() > 0) {
                $a['det'] = $query->result();
              } else {
                $x = 2;
              }


              $this->db->select(array(
                't_credit_sales_additional_item.type as sales_type',
                't_credit_sales_additional_item.rate_p',
                't_credit_sales_additional_item.amount',
                'r_additional_item.description'
              ));

              $this->db->from('t_credit_sales_additional_item');
              $this->db->join('r_additional_item', 'r_additional_item.code=t_credit_sales_additional_item.type');
              $this->db->where('t_credit_sales_additional_item.cl', $this->sd['cl']);
              $this->db->where('t_credit_sales_additional_item.bc', $this->sd['branch']);
              $this->db->where('t_credit_sales_additional_item.nno', $_POST['id']);
              $query = $this->db->get();

              if ($query->num_rows() > 0) {
                $a['add'] = $query->result();
              } else {
                $a['add'] = 2;
              }


              $this->db->select_max('nno');
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $query = $this->db->get("t_credit_note")->first_row()->nno + 1;

              $a['crn'] = $query;



              $this->db->select(array('t_serial.item', 't_serial.serial_no'));
              $this->db->from('t_serial');
              $this->db->join('t_credit_sales_sum', 't_serial.out_no=t_credit_sales_sum.nno');
              $this->db->where('t_serial.out_doc', 5);
              $this->db->where('t_serial.out_no', $_POST['id']);
              $this->db->where('t_credit_sales_sum.cl', $this->sd['cl']);
              $this->db->where('t_credit_sales_sum.bc', $this->sd['branch']);
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

            public function delete() {
              $this->db->trans_begin();
              error_reporting(E_ALL); 
              function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
                throw new Exception($errMsg); 
              }
              set_error_handler('exceptionThrower'); 
              try {
                if($this->user_permissions->is_delete('t_credit_sales_sum')){
                  $bc=$this->sd['branch'];
                  $cl=$this->sd['cl'];
                  $trans_no=$_POST['trans_no'];
                  $status=$this->trans_cancellation->credit_sales_update_status();     
                  if($status=="OK"){

                    $this->load->model('trans_settlement');
                    $this->trans_settlement->delete_item_movement('t_item_movement',5,$trans_no);

                    $this->db->where('cl',$cl);
                    $this->db->where('bc',$bc);
                    $this->db->where('trans_code','5');
                    $this->db->where('trans_no',$trans_no);
                    $this->db->delete('t_item_movement_sub');

                    $this->db->where('cl',$cl);
                    $this->db->where('bc',$bc);
                    $this->db->where('trans_type','5');
                    $this->db->where('trans_no',$trans_no);
                    $this->db->delete('t_previlliage_trans');

                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                    $this->db->where('trans_code','5');
                    $this->db->where('trans_no',$trans_no);
                    $this->db->delete('t_account_trans');

                    $this->load->model('trans_settlement');
                    $this->load->model('t_payment_option');
                    $this->trans_settlement->delete_settlement_sub("t_cus_settlement","5", $trans_no);
                    $this->t_payment_option->delete_settlement("t_ins_trans","5", $trans_no);
                    $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","5",$trans_no);   
                    $this->trans_settlement->delete_settlement_sub("t_debit_note_trans","5",$trans_no); 

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
                    $this->db->where("out_no", $trans_no);
                    $this->db->where("out_doc", 5);
                    $this->db->update("t_serial", $t_serial);

                    $this->db->select(array('item', 'serial_no'));
                    $this->db->where("trans_no", $trans_no);
                    $this->db->where("trans_type", 5);
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

                   $data=array('is_cancel'=>'1');
                   $this->db->where('cl',$this->sd['cl']);
                   $this->db->where('bc',$this->sd['branch']);
                   $this->db->where('nno',$_POST['trans_no']);
                   $this->db->update('t_credit_sales_sum',$data);

                   $sql="SELECT cus_id FROM t_credit_sales_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
                   $cus_id=$this->db->query($sql)->first_row()->cus_id;

                   $this->utility->update_credit_note_balance($cus_id);
                   $this->utility->update_credit_sale_balance($cus_id);
                   $this->utility->update_debit_note_balance($cus_id);

                   $this->utility->save_logger("CANCEL",5,$_POST['trans_no'],$this->mod);
                   
                   echo $this->db->trans_commit();
                 }else{
                  echo $status;
                  $this->db->trans_commit();
                }
              }else{
                $this->db->trans_commit();
                echo "No permission to delete records";
              }
            }catch(Exception $e){ 
              $this->db->trans_rollback();
              echo "Operation fail please contact admin"; 
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

            if($query->num_rows()==1){
              foreach ($query->result() as $row) {
                echo $row->batch_no . "-" . $row->qty;
              }
            }else if ($query->num_rows() > 0) {
              echo "1";
            }else {
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
            AND qry_current_stock.`cl` ='".$this->sd['cl']."'
            AND qry_current_stock.`bc`= '".$this->sd['branch']."'
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

          function get_batch_qty(){

            $batch_no=$_POST['batch_no'];
            $store=$_POST['store'];
            $no=$_POST['hid'];
            $item=$_POST['code'];
            $group=$_POST['group'];

            if(isset($_POST['hid']) && $_POST['hid']=="0")
            {
              if($group != "0"){
                $this->db->select(array('IFNULL(qty,0) AS qty'));
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("batch_no", $this->input->post("batch_no"));
                $this->db->where("store_code", $this->input->post('store'));
                $this->db->where("group_sale", $group);
                $this->db->where("item", $this->input->post('code'));
                $query = $this->db->get("qry_current_stock_group");
              }else{
                $this->db->select(array('IFNULL(qty,0) AS qty'));
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("batch_no", $this->input->post("batch_no"));
                $this->db->where("store_code", $this->input->post('store'));
                $this->db->where("item", $this->input->post('code'));
                $query = $this->db->get("qry_current_stock");
              }

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
            $sql="SELECT IFNULL(SUM(qry_current_stock.`qty`)+SUM(c.qty),0) as qty FROM (`qry_current_stock`)  
            INNER JOIN (SELECT qty,code,batch_no,cl,bc FROM t_credit_sales_det WHERE  `batch_no` = '$batch_no'  AND  nno='$no' AND `code` = '$item') c 
            ON c.code=qry_current_stock.`item` AND c.cl=qry_current_stock.cl AND c.bc=qry_current_stock.bc AND c.batch_no=qry_current_stock.batch_no
            WHERE qry_current_stock.`batch_no` = '$batch_no' AND qry_current_stock.`store_code` = '$store' AND `item` = '$item'
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

          $sql = "SELECT DISTINCT(m_item.code), 
          m_item.`description`,
          m_item.`model`,
          m_item.`warranty`,
          t_item_batch.`max_price`, 
          t_item_batch.`min_price`,
          t_item_batch.purchase_price 
          FROM m_item 
          JOIN qry_current_stock ON m_item.`code`=qry_current_stock.`item`
          JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no`
          WHERE qry_current_stock.`store_code`='$_POST[stores]' 
          AND qry_current_stock.qty > 0 
          AND qry_current_stock.cl='$cl' 
          AND qry_current_stock.bc='$branch' 
          AND (m_item.`description` LIKE '%$_POST[search]%' 
          OR m_item.`code` LIKE '%$_POST[search]%' 
          OR m_item.model LIKE '$_POST[search]%' 
          OR t_item_batch.`max_price` LIKE '$_POST[search]%'
          OR `t_item_batch`.`min_price` LIKE '$_POST[search]%' 
          OR `t_item_batch`.`max_price` LIKE '$_POST[search]%') 
          AND `m_item`.`inactive`='0'
          GROUP BY  m_item.code
          LIMIT 25";  
          /*$sql = "SELECT DISTINCT(m_item.code), 
          m_item.`description`,
          m_item.`model`,
          m_item.`warranty`,
          t_item_batch.`max_price`, 
          t_item_batch.`min_price`,
          t_item_batch.purchase_price
         
          FROM m_item 
          JOIN qry_current_stock_group ON m_item.`code`=qry_current_stock_group.`item` 
          JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock_group.`batch_no`
          WHERE qry_current_stock_group.`store_code`='$_POST[stores]' 
          AND qry_current_stock_group.qty > 0
          AND qry_current_stock_group.cl='$cl' 
          AND qry_current_stock_group.bc='$branch' 
          AND qry_current_stock_group.group_sale='$group_sale'
          AND (m_item.`description` LIKE '%$_POST[search]%' 
          OR m_item.`code` LIKE '%$_POST[search]%' 
          OR m_item.model LIKE '$_POST[search]%' 
          OR t_item_batch.`max_price` LIKE '$_POST[search]%' 
          OR `t_item_batch`.`min_price` LIKE '$_POST[search]%' 
          OR `t_item_batch`.`max_price` LIKE '$_POST[search]%')
          AND `m_item`.`inactive`='0' 
          GROUP BY  m_item.code
          LIMIT 25";*/
        }else{
          $sql = "SELECT DISTINCT(m_item.code), 
          m_item.`description`,
          m_item.`model`,
          m_item.`warranty`,
          t_item_batch.`max_price`, 
          t_item_batch.`min_price`,
          t_item_batch.purchase_price 
          FROM m_item 
          JOIN qry_current_stock ON m_item.`code`=qry_current_stock.`item`
          JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no`
          WHERE qry_current_stock.`store_code`='$_POST[stores]' 
          AND qry_current_stock.qty > 0 
          AND qry_current_stock.cl='$cl' 
          AND qry_current_stock.bc='$branch' 
          AND (m_item.`description` LIKE '%$_POST[search]%' 
          OR m_item.`code` LIKE '%$_POST[search]%' 
          OR m_item.model LIKE '$_POST[search]%' 
          OR t_item_batch.`max_price` LIKE '$_POST[search]%'
          OR `t_item_batch`.`min_price` LIKE '$_POST[search]%' 
          OR `t_item_batch`.`max_price` LIKE '$_POST[search]%') 
          AND `m_item`.`inactive`='0'
          GROUP BY  m_item.code
          LIMIT 25";  
        }        
        
        $query = $this->db->query($sql);
        $a = "<table id='item_list' style='width : 100%' >";
        $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Code</th>";
        $a .= "<th class='tb_head_th'>Item Name</th>";
        $a .= "<th class='tb_head_th'>Model</th>";
        $a .= "<th class='tb_head_th'>Price</th>";
        $a .= "<th class='tb_head_th'>Min Price</th>";
        $a .= "<th class='tb_head_th' style='display:none'>Purchase Price</th>";

        $a .= "</thead></tr>";
        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td style='display:none'>&nbsp;</td>";    

        $a .= "</tr>";
        foreach ($query->result() as $r) {
          $a .= "<tr class='cl'>";
          $a .= "<td>" . $r->code . "</td>";
          $a .= "<td>" . $r->description . "</td>";
          $a .= "<td>" . $r->model . "</td>";
          $a .= "<td>" . $r->max_price . "</td>";
          $a .= "<td>" . $r->min_price . "</td>";
          $a .= "<td style='display:none'>" . $r->purchase_price . "</td>";
          $a .= "<td style='display:none'>" . $r->warranty . "</td>";

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
          $sql = "SELECT DISTINCT(m_item.code), 
          m_item.`description`,
          m_item.`model`,
          t_item_batch.`max_price`,
          t_item_batch.`min_price` 
          FROM m_item 
          JOIN qry_current_stock_group ON m_item.`code`=qry_current_stock_group.`item` 
          JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock_group.`batch_no`
          WHERE qry_current_stock_group.`store_code`='$_POST[stores]' 
          AND qry_current_stock_group.cl='$cl' AND qry_current_stock_group.bc='$branch' AND m_item.code='$code' 
          AND qry_current_stock_group.group_sale='$group_sale'
          
          AND `m_item`.`inactive`='0' LIMIT 25";
        }else{
          $sql = "SELECT DISTINCT(m_item.code), 
          m_item.`description`,
          m_item.`model`,
          t_item_batch.`max_price`,
          t_item_batch.`min_price`
          FROM m_item 
          JOIN qry_current_stock ON m_item.`code`=qry_current_stock.`item` 
          JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no`
          WHERE qry_current_stock.`store_code`='$_POST[stores]' 
          AND qry_current_stock.cl='$cl' AND qry_current_stock.bc='$branch' AND m_item.code='$code'
          
          AND `m_item`.`inactive`='0' LIMIT 25";  
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

        $r_detail['duplicate'] = $_POST['is_duplicate'];

        $sqll="SELECT price*qty as price
        FROM t_credit_sales_det
        WHERE is_free='1' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['qno']."' ";

        $r_detail['free_item'] = $this->db->query($sqll)->result();


        $ssql=" SELECT card_no, ddate, opt_credit_card_det.amount   
        FROM opt_credit_card_det
        JOIN t_credit_sales_sum ON t_credit_sales_sum.nno = opt_credit_card_det.`trans_no` 
        AND t_credit_sales_sum.cl = opt_credit_card_det.cl
        AND t_credit_sales_sum.bc = opt_credit_card_det.bc
        WHERE trans_code = '5' AND trans_no = '".$_POST['qno']."' AND t_credit_sales_sum.cl='".$this->sd['cl']."' AND t_credit_sales_sum.bc = '".$this->sd['branch']."'
        ";
        $query = $this->db->query($ssql);
        $r_detail['credit_card'] = $this->db->query($ssql)->result();

        $ssql2=" SELECT sum(opt_credit_card_det.amount) as amount   
        FROM opt_credit_card_det
        JOIN t_credit_sales_sum ON t_credit_sales_sum.nno = opt_credit_card_det.`trans_no` 
        AND t_credit_sales_sum.cl = opt_credit_card_det.cl
        AND t_credit_sales_sum.bc = opt_credit_card_det.bc
        WHERE trans_code = '5' AND trans_no = '".$_POST['qno']."' AND t_credit_sales_sum.cl='".$this->sd['cl']."' AND t_credit_sales_sum.bc = '".$this->sd['branch']."'
        ";
        $query2 = $this->db->query($ssql2);
        $r_detail['credit_card_sum'] = $this->db->query($ssql2)->result();

        $sql11="SELECT sum(amount) as amount FROM opt_receive_cheque_det WHERE trans_code='5' AND trans_no ='".$_POST['qno']."' AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'  ";
        $query11= $this->db->query($sql11);
        $r_detail['cheque_detail'] = $this->db->query($sql11)->result();

        $sql22="SELECT sum(settled) as amount FROM opt_credit_note_det WHERE trans_code='5' AND trans_no ='".$_POST['qno']."' AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'  ";
        $query22= $this->db->query($sql22);
        $r_detail['other1'] = $this->db->query($sql22)->result();

        $sql33="SELECT sum(amount) as amount FROM opt_debit_note_det WHERE trans_code='5' AND trans_no ='".$_POST['qno']."' AND cl ='".$this->sd['cl']."' AND bc ='".$this->sd['branch']."'  ";
        $query33= $this->db->query($sql33);
        $r_detail['other2'] = $this->db->query($sql33)->result();

        $sql_query="SELECT SUM( IF(ai.`is_add`,1,-1) *ca.`amount`) AS amt
        FROM `t_credit_sales_additional_item` ca
        INNER JOIN `r_additional_item` ai ON ai.`code`=ca.`type`
        WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno ='".$_POST['qno']."' ";
        $r_detail['additional_tot']=$this->db->query($sql_query)->row()->amt;   


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

        $this->db->select(array('cus_name', 'cus_address', 'do_no', 'receipt_no','memo'));
        $this->db->where("nno", $_POST['qno']);
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['credit_sum'] = $this->db->get('t_credit_sales_sum')->result();


        foreach ($query->result() as $row) {
          $r_detail['employee'] = $row->name;
        }


        $sql="SELECT `t_credit_sales_det`.`code`, 
        `t_credit_sales_det`.`qty`, 
        `t_credit_sales_det`.`discount`, 
        `t_credit_sales_det`.`price`, 
        `t_credit_sales_det`.`amount`, 
        `t_credit_sales_det`.`batch_no`,
        `m_item`.`description`, 
        `m_item`.`model`, 
        c.`cc` AS sub_item,
        c.`deee` AS des,
        c.qty * t_credit_sales_det.qty as sub_qty
        FROM (`t_credit_sales_det`) 
        JOIN `m_item` ON `m_item`.`code` = `t_credit_sales_det`.`code` 
        LEFT JOIN `m_item_sub` ON `m_item_sub`.`item_code` = `m_item`.`code` 
        LEFT JOIN `r_sub_item` ON `r_sub_item`.`code` = `m_item_sub`.`sub_item` 
        LEFT JOIN (
        SELECT t_credit_sales_det.`code`, 
        r_sub_item.`description` AS deee, 
        r_sub_item.`code` AS cc,
        r_sub_item.`qty` AS qty,
        t_item_movement_sub.cl,
        t_item_movement_sub.bc,
        t_item_movement_sub.item,
        t_item_movement_sub.`sub_item` 
        FROM t_item_movement_sub 
        LEFT JOIN t_credit_sales_det ON t_credit_sales_det.`code` = t_item_movement_sub.`item`  
        AND t_credit_sales_det.`cl` = t_item_movement_sub.`cl` AND t_credit_sales_det.`bc`=t_item_movement_sub.`bc`
        JOIN r_sub_item ON t_item_movement_sub.`sub_item` = r_sub_item.`code`
        WHERE t_item_movement_sub.batch_no = t_credit_sales_det.`batch_no` AND `t_credit_sales_det`.`cl` = '".$this->sd['cl']."'  
        AND `t_credit_sales_det`.`bc` = '".$this->sd['branch']."' AND `t_credit_sales_det`.`nno` = '".$_POST['qno']."'  )c ON c.code = t_credit_sales_det.`code`
        WHERE `t_credit_sales_det`.`cl` = '".$this->sd['cl']."' 
        AND `t_credit_sales_det`.`bc` = '".$this->sd['branch']."'
        AND `t_credit_sales_det`.`nno` = '".$_POST['qno']."'  
        GROUP BY c.cc ,t_credit_sales_det.`code`,t_credit_sales_det.batch_no
        ORDER BY `t_credit_sales_det`.`auto_num` ASC 
        ";

        $query = $this->db->query($sql);
        $r_detail['items'] = $this->db->query($sql)->result();



        $this->db->SELECT(array('serial_no','item'));
        $this->db->FROM('t_serial_movement_out');
        $this->db->WHERE('t_serial_movement_out.cl', $this->sd['cl']);
        $this->db->WHERE('t_serial_movement_out.bc', $this->sd['branch']);
        $this->db->WHERE('t_serial_movement_out.trans_type','5');
        $this->db->WHERE('t_serial_movement_out.trans_no',$_POST['qno']);
        $r_detail['serial'] = $this->db->get()->result();

        $this->db->select(array('gross_amount','net_amount','pay_credit','pay_cnote','group_no'));
        $this->db->from('t_credit_sales_sum');
        $this->db->join('t_credit_sales_det', 't_credit_sales_det.nno=t_credit_sales_sum.nno');
        $this->db->where('t_credit_sales_sum.cl', $this->sd['cl']);
        $this->db->where('t_credit_sales_sum.bc', $this->sd['branch']);
        $this->db->where('t_credit_sales_sum.nno', $_POST['qno']);
        $r_detail['amount'] = $this->db->get()->result();
        $group=$r_detail['amount'][0]->group_no;

        $this->db->select(array('t_credit_sales_additional_item.type', 't_credit_sales_additional_item.amount', 'r_additional_item.description', 'r_additional_item.is_add'));
        $this->db->from('t_credit_sales_additional_item');
        $this->db->join('r_additional_item', 't_credit_sales_additional_item.type=r_additional_item.code');
        $this->db->where('t_credit_sales_additional_item.cl', $this->sd['cl']);
        $this->db->where('t_credit_sales_additional_item.bc', $this->sd['branch']);
        $this->db->where('t_credit_sales_additional_item.nno', $_POST['qno']);
        $r_detail['additional'] = $this->db->get()->result();

        $this->db->select(array('trans_no', 'ins_no', 'due_date', 'ins_amount'));
        $this->db->from('t_ins_schedule');
        $this->db->where('t_ins_schedule.cl', $this->sd['cl']);
        $this->db->where('t_ins_schedule.bc', $this->sd['branch']);
        $this->db->where('t_ins_schedule.trans_no', $_POST['qno']);
        $r_detail['ins_payment'] = $this->db->get()->result();

        $this->db->select("discount_amount AS discount_total");
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $_POST['qno']);
        $r_detail['discount_total'] = $this->db->get('t_credit_sales_sum')->result();

        $this->db->select(array('t_credit_sales_sum.oc', 's_users.discription', 'action_date'));
        $this->db->from('t_credit_sales_sum');
        $this->db->join('s_users', 't_credit_sales_sum.oc=s_users.cCode');
        $this->db->where('t_credit_sales_sum.cl', $this->sd['cl']);
        $this->db->where('t_credit_sales_sum.bc', $this->sd['branch']);
        $this->db->where('t_credit_sales_sum.nno', $_POST['qno']);
        $r_detail['user'] = $this->db->get()->result();

        $sql="SELECT * FROM r_groups WHERE code='".$group."'";
        $query=$this->db->query($sql);
        if($query->num_rows()>0){
          $r_detail['gr_id']=$query->first_row()->code;
          $r_detail['gr_des']=$query->first_row()->name;
        }else{
          $r_detail['gr_id']="";
          $r_detail['gr_des']="";
        }

        $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
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

      public function get_department_pv_rate() {
        $this->db->select(array("pv_card_rate"));
        $this->db->from("r_department");
        $this->db->join("m_item", "r_department.code=m_item.department");
        $this->db->where("m_item.code", $this->input->post('code'));
        echo $this->db->get()->first_row()->pv_card_rate;
      }


      public function check_is_serial_items($code) {
        $this->db->select(array('serial_no'));
        $this->db->where("code", $code);
        $this->db->limit(1);
        $query = $this->db->get('m_item');
        if ($query->num_rows() > 0) {
          foreach ($query->result() as $row) {
            return $row->serial_no;
          }
        }
      }

      public function is_serial_entered($trans_no, $item, $serial) {
        $this->db->select(array('available'));
        $this->db->where("serial_no", $serial);
        $this->db->where("item", $item);
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $query = $this->db->get("t_serial");

        if ($query->num_rows() > 0) {
          return 1;
        }else{
          return 0;
        }
      }


      public function get_batch_serial_wise($item, $serial) {
        $this->db->select("batch");
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where("item", $item);
        $this->db->where("serial_no", $serial);
        return $this->db->get('t_serial')->first_row()->batch;
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

          $a .= "<thead><tr class='cl'><td colspan='6' style='font-weight:bold;background:#aaa;color:#fff;font-size:14px; word-spacing: 2px'>Free Issue Item List</td></tr><tr>";
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




      public function save_sub_items(){
        if($_POST['groups']!='0'){
          $group=$_POST['groups'];
        }else{
          $group=$this->utility->default_group();
        }  
        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x])) {
            if ($_POST['0_' . $x] != "") {

              $item_code=$_POST['0_'.$x];
              $qty=$_POST['5_'.$x];
              $batch=$_POST['1_'.$x];
              $date=$_POST['date'];
              $store=$_POST['stores'];
              $price=$_POST['3_'.$x];
              $max=$this->max_no;

              
              $sql="SELECT s.sub_item , r.qty 
              FROM t_item_movement_sub s
              JOIN r_sub_item r ON r.`code`=s.`sub_item`
              WHERE s.`item`='$item_code' AND s.cl='".$this->sd['cl']."' AND s.bc ='".$this->sd['branch']."' AND s.`batch_no`='$batch' 
              GROUP BY r.`code`";
              $query=$this->db->query($sql);

              foreach($query->result() as $row) {
                $total_qty=$qty*(int)$row->qty;
                $t_sub_item_movement[] = array(
                  "cl" => $this->sd['cl'],
                  "bc" => $this->sd['branch'],
                  "item" => $item_code,
                  "sub_item"=>$row->sub_item,
                  "trans_code" => 5,
                  "trans_no" => $max,
                  "ddate" => $date,
                  "qty_in" => 0,
                  "qty_out" => $total_qty,
                  "store_code" => $store,
                  "avg_price" => $this->utility->get_cost_price($item_code),
                  "batch_no" => $batch,
                  "sales_price" => $price,
                  "last_sales_price" => $this->utility->get_min_price($item_code),
                  "cost" => $this->utility->get_cost_price($item_code),
                  "group_sale_id" =>$group,
                  

                );
              }
              
            }
          }
        }

        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
          if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
        }else{
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->where("trans_code", 5);
          $this->db->where("trans_no", $_POST['hid']);
          $this->db->delete("t_item_movement_sub");

          if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
        }   
        
      }   

      public function load_crdt_period(){
        $sql="SELECT credit_period FROM m_customer WHERE code='".$_POST['code']."'  ";

        $query=$this->db->query($sql);

        foreach($query->result() as $a){
          $period=$a->credit_period;
        }

        echo json_encode($period);
      } 


      public function customer_limit($cus){
        $result=1;
        $sql = "SELECT credit_limit FROM m_customer WHERE code='$cus'";
        $cus_limit = $this->db->query($sql)->row()->credit_limit;

        $cus_pre_bal = $this->utility->get_account_balance($cus);

        $cus_bal=(float)$cus_pre_bal+(float)$_POST['credit'];
        if($cus_limit>0){
          if($cus_bal>$cus_limit){
            $result =  "Customer reached his credit limit";
          }
        }
        return $result;
      }
      
    }
