<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class t_internal_transfer_receive extends CI_Model {
  private $max_no;  
  private $mod='003';
  private $mode_max_no;
  private $sub_max_no;

  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->m_stores          = $this->tables->tb['m_stores'];
    $this->qry_current_stock = $this->tables->tb['qry_current_stock'];
    $this->t_damage_sum      = $this->tables->tb['t_damage_sum'];
    $this->load->model("utility");
    $this->load->model('user_permissions');

    
    $this->max_no = $this->utility->get_max_no("t_internal_transfer_sum", "nno");
    $this->credit_max_no = $this->get_credit_max_no();

  }



  public function base_details() {
    $a['id'] = $this->utility->get_max_no("t_internal_transfer_sum", "nno");
    $a['sub_max_no']= $this->utility->get_max_no_in_type_transfer("t_internal_transfer_sum","sub_no","branch","43");

    $a['branch'] = $this->branch();
    $a['cluster'] = $this->cluster();
    $a['store'] = $this->store();
    $a['from_branch'] = $this->sd['branch'];
    $a['from_branch_name'] = $this->from_branch_name();
    $a["crn_no"] = $this->get_credit_max_no();
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
                //return $_POST['crn_no'];
        $field = "nno";
        $this->db->select_max($field);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        return $this->db->get("t_credit_note")->first_row()->$field + 1;
      }
    }else{
      $field = "nno";
      $this->db->select_max($field);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      return $this->db->get("t_credit_note")->first_row()->$field + 1;
    }
  }

  public function branch(){
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $query = $this->db->get("m_branch");
    $st    = "<select name='branch' id='branch' class=''>";
    $st .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
      $st .= "<option title='" . $r->name. "' value='" . $r->bc . "'>" . $r->bc . "-" . $r->name . "</option>";
    }
    $st .= "</select>";
    return $st;
  }

  public function to_branch(){
    $this->db->where('cl', $_POST['cluster']);
    $query = $this->db->get("m_branch");
    $st    = "<select name='t_branch' id='t_branch' class=''>";
    $st .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
      $st .= "<option title='" . $r->name . "' value='" . $r->bc . "'>" . $r->bc . "-" . $r->name . "</option>";
    }
    $st .= "</select>";
    echo $st;
  }

  public function cluster(){
    $query = $this->db->get("m_cluster");
    $st    = "<select name='to_cluster' id='to_cluster' class=''>";
    $st .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
      $st .= "<option title='" . $r->description . "' value='" . $r->code . "'>" . $r->code . "-" . $r->description . "</option>";
    }
    $st .= "</select>";
    return $st;
  }

  public function store(){
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $query = $this->db->get("m_stores");
    $st    = "<select name='store_from' id='store_from' class='store11'>";
    $st .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
      $st .= "<option title='" . $r->description . "' value='" . $r->code . "'>" . $r->code . "-" . $r->description . "</option>";
    }
    $st .= "</select>";
    return $st;
  }

  public function from_branch_name(){
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $query = $this->db->get("m_branch")->row('name');
    return $query;
  }


  public function load_transfer_order(){
    $order = $_POST['order_no'];
    $sql="SELECT  d.`item_code` , 
    m.`description` , 
    m.`model` , 
    q.`batch_no` , 
    t_item_batch.`purchase_price`, 
    t_item_batch.`min_price`, 
    t_item_batch.`max_price`, 
    q.`qty` as cur, 
    d.`qty` 
    FROM t_internal_transfer_order_sum s
    JOIN t_internal_transfer_order_det d ON d.`nno` = s.`nno`
    JOIN m_item m ON m.`code` = d.`item_code`
    JOIN qry_current_stock q ON q.`item` = m.`code`
    JOIN t_item_batch ON t_item_batch.`item` = m.`code` 
    WHERE s.nno = '$order' 
    GROUP BY d.`item_code`";

    $query=$this->db->query($sql);

    if ($query->num_rows() > 0) {
      $a = $query->result();
    }else{
      $a = 2;
    }
    echo json_encode($a);

  }

  public function check_reject(){

    $sql="SELECT status 
    FROM t_internal_transfer_order_sum
    WHERE nno='".$_POST['order_no']."'";

    $query=$this->db->query($sql)->row()->status;

    echo $query;
  }


  public function reject(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errMsg."==".$errFile."==". $errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try { 

      $sql="UPDATE t_internal_transfer_order_sum SET status='3' WHERE nno='".$_POST['order_no']."'";
      $query=$this->db->query($sql);

      echo $this->db->trans_commit();
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo $e->getMessage()."Operation fail please contact admin"; 
    } 

  }

  public function validation() {
    $status  = 1;

    $this->sub_max_no=$this->utility->get_max_no_in_type_echo2_transfer("t_internal_transfer_sum","sub_no",$_POST['types'],"43");
    $this->mode_max_no=$this->utility->get_max_no_in_mode_transfer("43")
    ;
    // $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_internal_transfer_sum');
    // if ($check_is_delete != 1) {
    //   return "Internal transfer already deleted";
    // }
   /* $chk_item_store_validation = $this->validation->check_item_with_store_transfer($_POST['v_store'], '0_');
    if ($chk_item_store_validation != 1) {
      return $chk_item_store_validation;
    }*/
    if($_POST['tr_mode']!='1'){ 
      $serial_validation_status = $this->validation->serial_update('0_', '2_','all_serial_');
      if ($serial_validation_status != 1) {
        return $serial_validation_status;
      }
      $check_batch_validation = $this->utility->batch_updatee('0_', '1_', '2_');
      if ($check_batch_validation != 1) {
        return $check_batch_validation;
      }
      $check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
      if($check_zero_value!=1){
        return $check_zero_value;
      }
    }
    /*$minimum_price_validation = $this->validation->check_min_price('0_', '3_');
      if ($minimum_price_validation != 1) {
          return $minimum_price_validation;
        }*/
        /*$chk_zero_qty=$this->validation->empty_qty('0_','2_');
        if($chk_zero_qty!=1){
          return $chk_zero_qty;
        }*/
        $account_update=$this->account_update(0,$_POST['hid_bc'],$_POST['hid_cl']);
        if($account_update!=1){
          return "Invalid account entries";
        }  
        return $status;
      }

      public function save(){

        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg."==".$errFile."==". $errLine); 
        }
        set_error_handler('exceptionThrower'); 
        try { 

          $this->credit_max_no = $this->get_credit_max_no();


          $_POST['date']=$_POST['ddate'];
          $validation_status=$this->validation();
          if($validation_status==1){
            $this->load->model("t_grn_sum");

            $execute=0;
            $subs="";
            $sql="SELECT acc_code FROM r_branch_current_acc 
            WHERE ref_cl='".$_POST['hid_cl']."'
            AND ref_bc='".$_POST['hid_bc']."'";
            $br_acc_code = $this->db->query($sql)->first_row()->acc_code;

            for($x = 0; $x<25; $x++){
              if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['1_'.$x],$_POST['3_'.$x])){
                if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['1_'.$x] != "" && $_POST['3_'.$x] != "" && $_POST['2_'.$x]>0){
                  $t_item_movement_out[]=array(
                    "cl"=>$_POST['hid_cl'],
                    "bc"=>$_POST['hid_bc'],
                    "item"=>$_POST['0_'.$x],
                    "trans_code"=>43,
                    "trans_no"=>$this->max_no,
                    "ddate"=>$_POST['ddate'],
                    "qty_in"=>0,
                    "qty_out"=>$_POST['2_'.$x],
                    "store_code"=>$_POST['v_store'],
                    "avg_price"=>$_POST['c_' . $x],
                    "batch_no"=>$this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                    "sales_price"=>$_POST['3_' . $x],
                    "last_sales_price"=>$_POST['min_' . $x],
                    "cost"=>$_POST['c_' . $x],
                  );
                }
              }     
            }

            for($x = 0; $x<25; $x++){
              if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['1_'.$x],$_POST['3_'.$x])){
                if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['1_'.$x] != "" && $_POST['3_'.$x] != "" && $_POST['2_'.$x]>0){
                 $b[]= array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],
                  "nno"=>$this->max_no,
                  "sub_no"=>$this->sub_max_no,
                  "type"=>$_POST['types'],
                  "item_code"=>$_POST['0_'.$x],
                  "batch_no"=>$this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                  "qty"=>$_POST['2_'.$x],
                  "item_cost" => $_POST['c_'.$x],
                  "min_price" => $_POST['min_'.$x],
                  "max_price" => $_POST['3_'.$x],
                  "trans_code" =>43,
                  "issued_qty" =>$_POST['qtyh_'.$x],

                );               
               }
             }
           }                    

           $data=array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "nno" => $this->max_no,
            "ref_no" => $_POST['ref_no'],
            "ddate" => $_POST['ddate'],
            "to_bc" => 0,
            "store" => $_POST['store_from'],
            "order_no" => 0,
            "note" => $_POST['issue_note'],
            "status" => "R",
            "oc" => $this->sd['oc'],
            "trans_code" =>43,
            "trans_no" =>$this->max_no,
            "ref_trans_code" =>42,
            "ref_trans_no" =>$_POST['request_no'],
            "ref_sub_no"=>$_POST['request_no'],
            "ref_type"=>$_POST['types'],
            "sub_no"=>$this->sub_max_no,
            "type"=>$_POST['types'],
            "vehicle"=>$_POST['vehicle'],
            "location_store"=>$_POST['v_store'],
            "driver"=>$_POST['driver'],
            "helper"=>$_POST['helper'],
            "issue_bc"   =>$_POST['hid_bc'],
            "upwstock"   =>$_POST['updateWstock'],
            "trns_mode"   =>$_POST['tr_mode'],
            "mode_no"   =>$this->mode_max_no,
            "net_amount"=>$_POST['net_amount'],
            "balance"=>$_POST['net_amount']
          );

           $t_credit_note = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "nno" => $this->credit_max_no,
            "ddate" => $_POST['date'],
            "ref_no" => $_POST['ref_no'],
            "memo" => "TRANSFER RECEIVE [" . $this->max_no . "]",
            "is_customer" => 1,
            "code" => $br_acc_code,
            "acc_code" => $this->utility->get_default_acc('STOCK_ACC'),
            "amount" => $_POST['net_amount'],
            "oc" => $this->sd['oc'],
            "post" => "",
            "post_by" => "",
            "post_date" => "",
            "is_cancel" => 0,
            "ref_trans_no" => $_POST['request_no'],
            "ref_trans_code" => 43,
            "balance"=>$_POST['net_amount']
          );

           $status=array(
          "status" => "R", //-- status update to received
        );


           if($_POST['sub_hid'] == "0" || $_POST['sub_hid'] == ""){

            if($this->user_permissions->is_add('t_internal_transfer_receive')){  
              if($_POST['df_is_serial']=='1'){
                $this->serial_save_transfer();
                $this->serial_save();    
              }
              $this->save_sub_items();
              $this->account_update(1,$_POST['hid_bc'],$_POST['hid_cl']);
              $this->load->model('trans_settlement');

              $this->db->insert("t_internal_transfer_sum", $data);                        
              if(count($b)){$this->db->insert_batch("t_internal_transfer_det",$b);}

              $this->db->where("sub_no", $_POST['request_no']);
              $this->db->where("cl", $_POST['hid_cl']);
              $this->db->where("bc", $_POST['hid_bc']);
              $this->db->where("type", $_POST['types']);
              $this->db->update("t_internal_transfer_sum", $status);   

              $this->db->where("sub_trans_no", $_POST['max_nno']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_gtn_settlement", array("receive_no" => $_POST['sub_no']));   

              $this->db->where("sub_trans_no", $_POST['max_nno']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_gtn_settlement", array("mode_no" => $_POST['mode_no']));   


              if($_POST['tr_mode']!='1'){ 
                for($x =0; $x<25; $x++){
                  if(isset($_POST['0_'.$x],$_POST['2_'.$x])){
                    if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['2_'.$x]>0 ){
                      $bbatch_no=$this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]);
                      if($this->utility->is_batch_item($_POST['0_'.$x])){
                        if($this->utility->batch_in_item_batch_tb($_POST['0_'.$x],$this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]))){
                          $this->load->model('utility');

                          $this->utility->insert_batch_items(
                            $this->sd['cl'],
                            $this->sd['branch'],
                            $_POST['0_'.$x],
                            43,
                            $this->max_no,
                            $bbatch_no,
                            $_POST['c_'.$x],
                            $_POST['min_'.$x],
                            $_POST['3_'.$x],
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
                          43,
                          $this->max_no,
                          $bbatch_no,
                          $_POST['c_'.$x],
                          $_POST['min_'.$x],
                          $_POST['3_'.$x],
                          $this->utility->get_item_supplier($_POST['0_'.$x]),
                          $this->sd['oc'],
                          "t_item_batch"
                        );
                      }             
                      if($_POST['updateWstock']!='1'){
                        $this->trans_settlement->save_item_movement('t_item_movement',
                          $_POST['0_'.$x],
                          '43',
                          $this->max_no,
                          $_POST['ddate'],
                          $_POST['2_'.$x],
                          0,
                          $_POST['store_from'],
                          $_POST['c_'.$x],
                          $bbatch_no,
                          $_POST['c_'.$x],
                          $_POST['3_' . $x],
                          $_POST['min_' . $x],                  
                          '001');

                        $this->trans_settlement->save_item_movement('t_item_movement',
                          $_POST['0_'.$x],
                          '43',
                          $this->max_no,
                          $_POST['ddate'],
                          0,
                          $_POST['2_'.$x],
                          $_POST['v_store'],
                          $_POST['c_'.$x],
                          $_POST['1_'.$x],
                          $_POST['c_'.$x],
                          $_POST['3_' . $x],
                          $_POST['min_' . $x],                  
                          '001',
                          $_POST['hid_cl'],
                          $_POST['hid_bc']);
                      }else{
                        $this->trans_settlement->save_item_movement('t_item_movement',
                          $_POST['0_'.$x],
                          '43',
                          $this->max_no,
                          $_POST['ddate'],
                          0,
                          $_POST['2_'.$x],
                          $_POST['v_store'],
                          $_POST['c_'.$x],
                          $_POST['1_'.$x],
                          $_POST['c_'.$x],
                          $_POST['3_' . $x],
                          $_POST['min_' . $x],                  
                          '001',
                          $_POST['hid_cl'],
                          $_POST['hid_bc']);
                      }
                    }
                  }
                }
              }
              if($_POST['tr_mode']=='2'){
                $this->load->model('trans_settlement');
                $this->trans_settlement->save_settlement("t_credit_note_trans", $br_acc_code, $_POST['date'], 17, $this->credit_max_no, 43, $_POST['request_no'], $_POST['net_amount'], "0");
                $this->db->insert('t_credit_note', $t_credit_note);
              }
              $this->utility->save_logger("SAVE",43,$this->max_no,$this->mod,$this->sub_max_no);
              echo $this->db->trans_commit()."@".$this->max_no;
            }else{
              echo "No permission to save records";
              $this->db->trans_commit();
            }   
          }else{
            if($this->user_permissions->is_edit('t_internal_transfer_receive')){
              if($_POST['df_is_serial']=='1'){
                $this->serial_save_transfer();
                $this->serial_save();    
              }
              $this->save_sub_items();
              $this->account_update(1,$_POST['hid_bc'],$_POST['hid_cl']);

              $this->load->model('trans_settlement');
              $this->trans_settlement->delete_item_movement('t_item_movement',43,$_POST['hid']);

              $this->db->where("trans_code", 43);
              $this->db->where("trans_no", $_POST['hid']);
              $this->db->where("cl", $_POST['hid_cl']);
              $this->db->where("bc", $_POST['hid_bc']);
              $this->db->delete("t_item_movement");

              $this->db->where("nno", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_internal_transfer_det");

              $data_update=array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "nno" => $this->max_no,
                "ref_no" => $_POST['ref_no'],
                "ddate" => $_POST['ddate'],
                "to_bc" => 0,
                "store" => $_POST['store_from'],
                "order_no" => 0,
                "note" => $_POST['issue_note'],
                "oc" => $this->sd['oc'],
                "trans_code" =>43,
                "trans_no" =>$this->max_no,
                "ref_trans_code" =>42,
                "ref_trans_no" =>$_POST['request_no'],
                "ref_sub_no"=>$_POST['request_no'],
                "ref_type"=>$_POST['types'],
                "sub_no"=>$this->sub_max_no,
                "type"=>$_POST['types'],
                "vehicle"=>$_POST['vehicle'],
                "location_store"=>$_POST['v_store'],
                "mode_no"=>$this->mode_max_no,
                "net_amount"=>$_POST['net_amount'],
                "balance"=>$_POST['net_amount']
              );

              $this->db->where("nno", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_internal_transfer_sum", $data_update);

              if(count($b)){$this->db->insert_batch("t_internal_transfer_det",$b);}
              if($_POST['updateWstock']!='1'){
                for($x =0; $x<25; $x++){
                  if(isset($_POST['0_'.$x],$_POST['2_'.$x])){
                    if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" ){
                      $bbatch_no=$this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]);
                      if($this->utility->is_batch_item($_POST['0_'.$x])){
                        if($this->utility->batch_in_item_batch_tb($_POST['0_'.$x],$this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]))){
                          $this->load->model('utility');

                          $this->utility->insert_batch_items(
                            $this->sd['cl'],
                            $this->sd['branch'],
                            $_POST['0_'.$x],
                            43,
                            $this->max_no,
                            $bbatch_no,
                            $_POST['c_'.$x],
                            $_POST['min_'.$x],
                            $_POST['3_'.$x],
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
                          43,
                          $this->max_no,
                          $bbatch_no,
                          $_POST['c_'.$x],
                          $_POST['min_'.$x],
                          $_POST['3_'.$x],
                          $this->utility->get_item_supplier($_POST['0_'.$x]),
                          $this->sd['oc'],
                          "t_item_batch"
                        );
                      }             

                      $this->trans_settlement->save_item_movement('t_item_movement',
                        $_POST['0_'.$x],
                        '43',
                        $this->max_no,
                        $_POST['ddate'],
                        $_POST['2_'.$x],
                        0,
                        $_POST['store_from'],
                        $_POST['c_'.$x],
                        $bbatch_no,
                        $_POST['c_'.$x],
                        $_POST['3_' . $x],
                        $_POST['min_' . $x],                      
                        '001');

                      $this->trans_settlement->save_item_movement('t_item_movement',
                        $_POST['0_'.$x],
                        '43',
                        $this->max_no,
                        $_POST['ddate'],
                        0,
                        $_POST['2_'.$x],
                        $_POST['v_store'],
                        $_POST['c_'.$x],
                        $_POST['1_'.$x],
                        $_POST['c_'.$x],
                        $_POST['3_' . $x],
                        $_POST['min_' . $x],                  
                        '001',
                        $_POST['hid_cl'],
                        $_POST['hid_bc']);
                    }
                  }
                }
              }

            //if(count($t_item_movement_out)){ $this->db->insert_batch("t_item_movement", $t_item_movement_out);}
              if($_POST['tr_mode']=='2'){
                $this->load->model('trans_settlement');
                $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","43",$_POST['request_no']);
                $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['customer'], $_POST['date'], 17, $this->credit_max_no, 43, $_POST['request_no'], $_POST['net_amount'], "0");
                $this->db->insert('t_credit_note', $t_credit_note);
              }

              $this->db->where("sub_no", $_POST['request_no']);
              $this->db->where("cl", $_POST['hid_cl']);
              $this->db->where("bc", $_POST['hid_bc']);
              $this->db->where("type", $_POST['types']);
              $this->db->update("t_internal_transfer_sum", $status);    

              $this->db->where("sub_trans_no", $_POST['max_nno']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_gtn_settlement", array("receive_no" => $_POST['sub_no']));    

              $this->db->where("sub_trans_no", $_POST['max_nno']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_gtn_settlement", array("mode_no" => $_POST['mode_no']));              

              $this->utility->save_logger("EDIT",43,$this->max_no,$this->mod,$this->sub_max_no);
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
        echo $e->getMessage()."Operation fail please contact admin"; 
      } 
    }

    public function serial_save() {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x])) {
            if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
              if ($this->check_is_serial_items($_POST['0_'.$x]) == 1 && $_POST['2_' . $x] != "0") {
                $serial = $_POST['all_serial_'.$x];
                $p=explode(",",$serial);
                for($i=0; $i<count($p); $i++){
                  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                    $t_seriall = array(
                      "cl" =>$this->sd['cl'],
                      "bc" =>$this->sd['branch'],
                      "trans_type" => 43,
                      "trans_no" => $this->max_no,
                      "date" => $_POST['date'],
                      "batch"=> $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                      "store_code" => $_POST['store_from'],
                      "out_doc" => "",
                      "out_no" => "",
                      "out_date" => "",
                      "available" => '1'
                    );


                    $this->db->where("cl", $_POST['hid_cl']);
                    $this->db->where("bc", $_POST['hid_bc']);
                    $this->db->where('serial_no', $p[$i]);
                    $this->db->where("item",$_POST['0_'.$x]);
                    $this->db->update("t_serial", $t_seriall);

                    $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                    $t_serial_movement[] = array(
                      "cl" => $this->sd['cl'],
                      "bc" => $this->sd['branch'],
                      "trans_type" => 43,
                      "trans_no" => $this->max_no,
                      "item" => $_POST['0_'.$x],
                      "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                      "serial_no" => $p[$i],
                      "qty_in" => 1,
                      "qty_out" => 0,
                      "cost" => $_POST['3_' . $x],
                      "store_code" => $_POST['store_from'],
                      "computer" => $this->input->ip_address(),
                      "oc" => $this->sd['oc'],
                    );



                    $this->db->where("cl", $_POST['hid_cl']);
                    $this->db->where("bc", $_POST['hid_bc']);
                    $this->db->where("item", $_POST['0_'.$x]);
                    $this->db->where("serial_no", $p[$i]);
                    $this->db->delete("t_serial_movement_out");

                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("item", $_POST['0_'.$x]);
                    $this->db->where("serial_no", $p[$i]);
                    $this->db->delete("t_serial_movement_out");
                  }
                }
              }
            }
          }
        }
            //$this->db->insert_batch("t_serial_movement", $t_serial_movement); 
      }else{
        $t_serial = array(           
          "cl" =>$this->sd['cl'],
          "bc" =>$this->sd['branch'],
          "trans_type" => 43,
          "trans_no" => $this->max_no,
          "date" => $_POST['date'],
          "store_code" => $_POST['store_from'],
          "out_doc" => "",
          "out_no" => "",
          "out_date" => "",
          "available" => '1'
        );



        $this->db->where("cl",$_POST['hid_cl'] );
        $this->db->where("bc",$_POST['hid_bc'] );
        $this->db->where("out_no", $this->max_no);
        $this->db->where("out_doc", 43);
        $this->db->update("t_serial", $t_serial);

        $this->db->select(array('item', 'serial_no'));
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_type", 43);
        $this->db->where("cl", $_POST['hid_cl']);
        $this->db->where("bc", $_POST['hid_bc']);
        $query = $this->db->get("t_serial_movement_out");

        foreach ($query->result() as $row) {
         $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='$row->item' AND serial_no='$row->serial_no'");
         $this->db->where("item", $row->item);
         $this->db->where("serial_no", $row->serial_no);
         $this->db->where("cl", $_POST['hid_cl']);
         $this->db->where("bc", $_POST['hid_bc']);
         $this->db->delete("t_serial_movement_out");
       }

       $this->db->where("cl", $this->sd['cl']);
       $this->db->where("bc", $this->sd['branch']);
       $this->db->where("trans_no", $this->max_no);
       $this->db->where("trans_type", 43);
       $this->db->delete("t_serial_movement");

       $this->db->where("cl", $_POST['hid_cl']);
       $this->db->where("bc", $_POST['hid_bc']);
       $this->db->where("trans_no", $this->max_no);
       $this->db->where("trans_type", 42);
       $this->db->delete("t_serial_movement_out");


       for ($x = 0; $x < 25; $x++) {
        if (isset($_POST['0_' . $x])) {
          if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
            if ($this->check_is_serial_items($_POST['0_' . $x]) == 1 && $_POST['2_' . $x] != "0") {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){

                $t_seriall = array(
                  "cl" =>$this->sd['cl'],
                  "bc" =>$this->sd['branch'],
                  "trans_type" => 43,
                  "trans_no" => $this->max_no,
                  "date" => $_POST['date'],
                  "batch"=> $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                  "store_code" => $_POST['store_from'],
                  "out_doc" => "",
                  "out_no" => "",
                  "out_date" => "",
                  "available" => '1'
                );


                $this->db->where("cl", $_POST['hid_cl']);
                $this->db->where("bc", $_POST['hid_bc']);
                $this->db->where('serial_no', $p[$i]);
                $this->db->where("item", $_POST['0_'.$x]);
                $this->db->update("t_serial", $t_seriall);

                $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                $t_serial_movement[] = array(
                 "cl" => $this->sd['cl'],
                 "bc" => $this->sd['branch'],
                 "trans_type" => 43,
                 "trans_no" => $this->max_no,
                 "item" => $_POST['0_'.$x],
                 "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                 "serial_no" => $p[$i],
                 "qty_in" => 1,
                 "qty_out" => 0,
                 "cost" => $_POST['3_' . $x],
                 "store_code" => $_POST['store_from'],
                 "computer" => $this->input->ip_address(),
                 "oc" => $this->sd['oc'],
               );

                     //$this->db->insert_batch("t_serial_movement", $t_serial_movement); 

                $this->db->where("cl", $_POST['hid_cl']);
                $this->db->where("bc", $_POST['hid_bc']);
                $this->db->where("item", $_POST['0_'.$x]);
                $this->db->where("serial_no", $p[$i]);
                $this->db->delete("t_serial_movement_out");

                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("item", $_POST['0_'.$x]);
                $this->db->where("serial_no", $p[$i]);
                $this->db->delete("t_serial_movement_out");
              }
            }
          } 
        }
      }
    }

    if($_POST['hid'] == "0" || $_POST['hid'] == "") {
      if(isset($t_serial_movement)) {
        if(count($t_serial_movement)) {
          $this->db->insert_batch("t_serial_movement", $t_serial_movement);
        }
      }
    }else{
      if(isset($t_serial_movement)) {
        if(count($t_serial_movement)) {
          $this->db->insert_batch("t_serial_movement", $t_serial_movement);
        }
      }
    }
  }


  public function serial_save_transfer() {
    if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
      for ($x = 0; $x < 25; $x++) {
        if (isset($_POST['0_' . $x])) {
          if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
            if ($this->check_is_serial_items($_POST['0_'.$x]) == 1 && $_POST['2_' . $x] != "0") {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){
                if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                  $t_seriall = array(
                    "cl" =>$_POST['hid_cl'],
                    "bc" =>$_POST['hid_bc'],
                    "trans_type" => 43,
                    "trans_no" => $this->max_no,
                    "date" => $_POST['date'],
                    "batch"=> $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                    "store_code" => $_POST['v_store'],
                    "out_doc" => "",
                    "out_no" => "",
                    "out_date" => "",
                    "available" => '0'
                  );


                  $this->db->where("cl", $_POST['hid_cl']);
                  $this->db->where("bc", $_POST['hid_bc']);
                  $this->db->where('serial_no', $p[$i]);
                  $this->db->where("item",$_POST['0_'.$x]);
                  $this->db->update("t_serial", $t_seriall);

                  $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                  $t_serial_movement_out[] = array(
                    "cl" => $_POST['hid_cl'],
                    "bc" => $_POST['hid_bc'],
                    "trans_type" => 43,
                    "trans_no" => $this->max_no,
                    "item" => $_POST['0_'.$x],
                    "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                    "serial_no" => $p[$i],
                    "qty_in" => 0,
                    "qty_out" => 1,
                    "cost" => $_POST['3_' . $x],
                    "store_code" => $_POST['v_store'],
                    "computer" => $this->input->ip_address(),
                    "oc" => $this->sd['oc'],
                  );

                  $this->db->where("cl", $_POST['hid_cl']);
                  $this->db->where("bc", $_POST['hid_bc']);
                  $this->db->where("item", $_POST['0_'.$x]);
                  $this->db->where("serial_no", $p[$i]);
                  $this->db->delete("t_serial_movement");

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
            //$this->db->insert_batch("t_serial_movement", $t_serial_movement); 
    }else{
      $t_serial = array(           
        "cl" =>$_POST['hid_cl'],
        "bc" =>$_POST['hid_bc'],
        "trans_type" => 43,
        "trans_no" => $this->max_no,
        "date" => $_POST['date'],
        "store_code" => $_POST['v_store'],
        "out_doc" => "",
        "out_no" => "",
        "out_date" => "",
        "available" => '0'
      );

      $this->db->where("cl", $_POST['hid_cl']);
      $this->db->where("bc", $_POST['hid_bc']);
      $this->db->where("out_no", $this->max_no);
      $this->db->where("out_doc", 43);
      $this->db->update("t_serial", $t_serial);

      $this->db->select(array('item', 'serial_no'));
      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_type", 43);
      $this->db->where("cl", $_POST['hid_cl']);
      $this->db->where("bc", $_POST['hid_bc']);
      $query = $this->db->get("t_serial_movement");

      foreach ($query->result() as $row) {
       $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$row->item' AND serial_no='$row->serial_no'");
       $this->db->where("item", $row->item);
       $this->db->where("serial_no", $row->serial_no);
       $this->db->where("cl", $_POST['hid_cl']);
       $this->db->where("bc", $_POST['hid_bc']);
       $this->db->delete("t_serial_movement");
     }

     $this->db->where("cl", $_POST['hid_cl']);
     $this->db->where("bc", $_POST['hid_bc']);
     $this->db->where("trans_no", $this->max_no);
     $this->db->where("trans_type", 43);
     $this->db->delete("t_serial_movement_out");

     $this->db->where("cl", $_POST['hid_cl']);
     $this->db->where("bc", $_POST['hid_bc']);
     $this->db->where("trans_no", $this->max_no);
     $this->db->where("trans_type", 42);
     $this->db->delete("t_serial_movement");


     for ($x = 0; $x < 25; $x++) {
      if (isset($_POST['0_' . $x])) {
        if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
          if ($this->check_is_serial_items($_POST['0_' . $x]) == 1 && $_POST['2_' . $x] != "0") {
            $serial = $_POST['all_serial_'.$x];
            $p=explode(",",$serial);
            for($i=0; $i<count($p); $i++){

              $t_seriall = array(
                "cl" =>$_POST['hid_cl'],
                "bc" =>$_POST['hid_bc'],
                "trans_type" => 43,
                "trans_no" => $this->max_no,
                "date" => $_POST['date'],
                "batch"=> $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
                "store_code" => $_POST['v_store'],
                "out_doc" => "",
                "out_no" => "",
                "out_date" => "",
                "available" => '0'
              );

              $this->db->where("cl", $_POST['hid_cl']);
              $this->db->where("bc", $_POST['hid_bc']);
              $this->db->where('serial_no', $p[$i]);
              $this->db->where("item", $_POST['0_'.$x]);
              $this->db->update("t_serial", $t_seriall);

              $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

              $t_serial_movement_out[] = array(
               "cl" => $_POST['hid_cl'],
               "bc" => $_POST['hid_bc'],
               "trans_type" => 43,
               "trans_no" => $this->max_no,
               "item" => $_POST['0_'.$x],
               "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
               "serial_no" => $p[$i],
               "qty_in" => 0,
               "qty_out" => 1,
               "cost" => $_POST['3_' . $x],
               "store_code" => $_POST['v_store'],
               "computer" => $this->input->ip_address(),
               "oc" => $this->sd['oc'],
             );

                     //$this->db->insert_batch("t_serial_movement", $t_serial_movement); 

              $this->db->where("cl", $_POST['hid_cl']);
              $this->db->where("bc", $_POST['hid_bc']);
              $this->db->where("item", $_POST['0_'.$x]);
              $this->db->where("serial_no", $p[$i]);
              $this->db->delete("t_serial_movement");

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

  if($_POST['hid'] == "0" || $_POST['hid'] == "") {
    if(isset($t_serial_movement_out)) {
      if(count($t_serial_movement_out)) {
        $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);
      }
    }
  }else{
    if(isset($t_serial_movement_out)) {
      if(count($t_serial_movement_out)) {
        $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);
      }
    }
  }
}




public function save_sub_items(){


  for ($x = 0; $x < 25; $x++) {
    if (isset($_POST['0_' . $x])) {
      if ($_POST['0_' . $x] != "") {


        $item_code=$_POST['0_'.$x];
        $qty=$_POST['2_'.$x];
        $batch=$_POST['1_'.$x];
        $date=$_POST['ddate'];
        $store=$_POST['store_from'];
        $price=$_POST['3_'.$x];
        $max=$this->max_no;

        $sql="SELECT s.sub_item , r.qty 
        FROM t_item_movement_sub s
        JOIN r_sub_item r ON r.`code`=s.`sub_item`
        WHERE s.`item`='$item_code' AND s.cl='".$_POST['hid_cl']."' AND s.bc ='".$_POST['hid_bc']."' AND s.`batch_no`='$batch' 
        GROUP BY r.`code`";
        $query=$this->db->query($sql);

        foreach($query->result() as $row) {
          $total_qty=$qty*(int)$row->qty;

          $t_sub_item_movement[] = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "item" => $item_code,
            "sub_item"=>$row->sub_item,
            "trans_code" => 43,
            "trans_no" => $max,
            "ddate" => $date,
            "qty_in" =>  $total_qty,
            "qty_out" => 0,
            "store_code" => $store,
            "avg_price" => $this->utility->get_cost_price($item_code),
            "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
            "sales_price" => $price,
            "last_sales_price" => $this->utility->get_min_price($item_code),
            "cost" => $this->utility->get_cost_price($item_code),
            "group_sale_id" => 1,
          );

          $t_sub_item_movement_out[] = array(
            "cl" => $_POST['hid_cl'],
            "bc" => $_POST['hid_bc'],
            "item" => $item_code,
            "sub_item"=>$row->sub_item,
            "trans_code" => 43,
            "trans_no" => $max,
            "ddate" => $date,
            "qty_in" =>  0,
            "qty_out" =>$total_qty,
            "store_code" => $_POST['v_store'],
            "avg_price" => $this->utility->get_cost_price($item_code),
            "batch_no" => $this->utility->get_batch_no($_POST['0_'.$x],$_POST['c_'.$x],$_POST['3_'.$x],$_POST['min_'.$x]),
            "sales_price" => $price,
            "last_sales_price" => $this->utility->get_min_price($item_code),
            "cost" => $this->utility->get_cost_price($item_code),
            "group_sale_id" => 1,
          );
        }

      }
    }
  }
  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
    if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
    if(isset($t_sub_item_movement_out)){if(count($t_sub_item_movement_out)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_out);}}
  }else{
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_code", 43);
    $this->db->where("trans_no", $_POST['hid']);
    $this->db->delete("t_item_movement_sub");

    $this->db->where("cl", $_POST['hid_cl']);
    $this->db->where("bc", $_POST['hid_bc']);
    $this->db->where("trans_code", 43);
    $this->db->where("trans_no", $_POST['hid']);
    $this->db->delete("t_item_movement_sub");

    if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
    if(isset($t_sub_item_movement_out)){if(count($t_sub_item_movement_out)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_out);}}
  }   

}

public function account_update($condition,$issue_bc,$issue_cl) {
      /* 
      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 43);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");
      */

      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 43);
      $this->db->where("ref_cl", $this->sd['cl']);
      $this->db->where("ref_bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");

      if($_POST['hid']=="0"||$_POST['hid']==""){

      }else{
        if($condition=="1"){
          $this->db->where("ref_cl", $this->sd['cl']);
          $this->db->where("ref_bc", $this->sd['branch']);
          $this->db->where('trans_code',43);
          $this->db->where('trans_no',$this->max_no);
          $this->db->delete('t_account_trans');
        }
      }

      $sql="SELECT acc_code FROM r_branch_current_acc
      WHERE ref_bc='$issue_bc'";
      $issue_bc_acc = $this->db->query($sql)->first_row()->acc_code;

      $sql_receive="SELECT acc_code FROM r_branch_current_acc
      WHERE ref_bc='".$this->sd['branch']."'";
      $receive_bc_acc = $this->db->query($sql_receive)->first_row()->acc_code;

      $sql_b="SELECT name from m_branch where bc='$issue_bc'";
      $issue_bc_name=$this->db->query($sql_b)->first_row()->name;

      $sql_bb="SELECT name from m_branch where bc='".$this->sd['branch']."'";
      $receive_bc_name=$this->db->query($sql_bb)->first_row()->name;
      
      $config = array(
        "ddate" => $_POST['date'],
        "trans_code" => 43,
        "trans_no" => $this->max_no,
        "op_acc" => 0,
        "reconcile" => 0,
        "cheque_no" => 0,
        "narration" => "",
        "ref_no" => $_POST['ref_no']
      );

      $des = "Internal Transfer From- " . $issue_bc_name;
      $des_r = "Internal Transfer Receive By- " . $receive_bc_name;

      $this->load->model('account');
      $this->account->set_data($config);

      $purchase              = $this->utility->get_default_acc('PURCHASE');
      $stock_acc             = $this->utility->get_default_acc('STOCK_ACC');
      $cost_sale             = $this->utility->get_default_acc('COST_OF_SALES');
      
      $item_cost=0;
      for($x=0;$x<25;$x++){
        if(isset($_POST['0_' . $x])){
          $item_cost+=($_POST['2_'.$x])*(double)$this->utility->get_cost_batch_price($_POST['0_'.$x],$_POST['1_'.$x]);
        }
      }  

      $sql_dep="SELECT department FROM m_item WHERE code='".$_POST['0_0']."'";
      $QDep = $this->db->query($sql_dep)->first_row()->department;
      if($QDep=='0050'){
        $printing_stationery_payable   = $this->utility->get_default_acc('PRINTING_STATIONARY');
      }else if($QDep=='0060'){
        $printing_stationery_payable   = $this->utility->get_default_acc('VEHICLE_REPAIRES');
      }

      if($_POST['tr_mode']=='1'){
        $this->account->set_value_internal($this->sd['cl'],$this->sd['branch'],$des_r, $item_cost, "dr", $printing_stationery_payable,$condition);
        $this->account->set_value_internal($this->sd['cl'],$this->sd['branch'],$des_r, $item_cost, "cr", $issue_bc_acc,$condition);
      }else{
        $this->account->set_value_internal($this->sd['cl'],$this->sd['branch'], $des, $item_cost, "dr", $purchase,$condition);
        $this->account->set_value_internal($this->sd['cl'],$this->sd['branch'], $des, $item_cost, "cr", $issue_bc_acc,$condition);
        $this->account->set_value_internal($this->sd['cl'],$this->sd['branch'], $des, $item_cost, "dr", $stock_acc,$condition);
        $this->account->set_value_internal($this->sd['cl'],$this->sd['branch'], $des, $item_cost, "cr", $cost_sale,$condition);
      }
      //---------issue branch accout updates

      $good_in_transit_acc   = $this->utility->get_default_acc('GOOD_TRANSIT');
      $this->account->set_value_internal($issue_cl,$issue_bc,$des_r, $item_cost, "dr", $receive_bc_acc,$condition);
      $this->account->set_value_internal($issue_cl,$issue_bc,$des_r, $item_cost, "cr", $good_in_transit_acc,$condition);

      if($condition==0){
       $query = $this->db->query("
         SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
         FROM `t_check_double_entry` t
         LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
         WHERE  t.`ref_cl`='" . $this->sd['cl'] . "'  AND t.`ref_bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='43'  AND t.`trans_no` ='" . $this->max_no . "' AND 
         a.`is_control_acc`='0'");

       if ($query->row()->ok == "0") {
              /*$this->db->where("trans_no", $this->max_no);
              $this->db->where("trans_code", 43);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_check_double_entry");*/

              $this->db->where("trans_no", $this->max_no);
              $this->db->where("trans_code", 43);
              $this->db->where("ref_cl", $this->sd['cl']);
              $this->db->where("ref_bc", $this->sd['branch']);
              $this->db->delete("t_check_double_entry");
              return "0";
            } else {
              return "1";
            }
          }
        }  


        public function batch_item() {
          $sql   = "SELECT qry_current_stock.`qty`,
          qry_current_stock.`batch_no`,
          t_item_batch.`max_price` AS cost, 
          t_item_batch.`min_price` AS min, 
          t_item_batch.`purchase_price` AS pur 
          FROM qry_current_stock 
          JOIN m_item ON qry_current_stock.`item`=m_item.`code`
          JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no` 
          WHERE qry_current_stock.`qty`>'0'
          AND qry_current_stock.`store_code`='$_POST[stores]' AND qry_current_stock.`item`='$_POST[search]'
          AND qry_current_stock.`cl`='".$this->sd['cl']."'
          AND qry_current_stock.`bc`='".$this->sd['branch']."'";
          $query = $this->db->query($sql);
          $a     = "<table id='batch_item_list' style='width : 100%' >";
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
          $a .= "</tr>";
          foreach ($query->result() as $r) {
            $a .= "<tr class='cl'>";
            $a .= "<td>" . $r->batch_no . "</td>";
            $a .= "<td>" . $r->qty . "</td>";
            $a .= "<td>" . $r->cost . "</td>";
            $a .= "<td>" . $r->min . "</td>";
            $a .= "<td>" . $r->pur . "</td>";
            $a .= "</tr>";
          }
          $a .= "</table>";
          echo $a;
        }
        public function is_batch_item() {
          $this->db->select(array(
            "batch_no",
            "qty"
          ));

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
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

        public function get_item() {


          $sql   = "SELECT batch_no, description, item, cost, qty  FROM qry_current_stock  WHERE store_code='$_POST[stores]' AND item='".$_POST['code']."'  
          AND qry_current_stock.`cl`='".$this->sd['cl']."'
          AND qry_current_stock.`bc`='".$this->sd['branch']."'
          group by item 
          LIMIT 25";

          $query = $this->db->query($sql);

          if ($query->num_rows() > 0) {
            $data['a'] = $this->db->query($sql)->result();
          } else {
            $data['a'] = 2;
          }

          echo json_encode($data);

        }


  // public function item_list_all() {
  //   if ($_POST['search'] == 'Key Word: code, name') {
  //     $_POST['search'] = "";
  //   }
  //   $sql   = "SELECT batch_no, description, item, cost, sum(qty) as qty  FROM qry_current_stock  WHERE (description LIKE '$_POST[search]%' OR item LIKE '$_POST[search]%') 
  //             AND qry_current_stock.`cl`='".$this->sd['cl']."'
  //             AND qry_current_stock.`bc`='".$this->sd['branch']."'
  //             group by item 
  //             LIMIT 25";

  //   $query = $this->db->query($sql);
  //   $a     = "<table id='item_list' style='width : 100%' >";
  //   $a .= "<thead><tr>";
  //   $a .= "<th class='tb_head_th'>Code</th>";
  //   $a .= "<th class='tb_head_th'>Item Name</th>";
  //   $a .= "<th class='tb_head_th'>Quantity</th>";
  //    $a .= "<th class='tb_head_th'>Cost</th>";
  //   $a .= "</thead></tr>";
  //   $a .= "<tr class='cl'>";
  //   $a .= "<td>&nbsp;</td>";
  //   $a .= "<td>&nbsp;</td>";
  //      $a .= "<td>&nbsp;</td>";
  //   $a .= "</tr>";
  //   foreach ($query->result() as $r) {
  //     $a .= "<tr class='cl'>";
  //     $a .= "<td>" . $r->item . "</td>";
  //     $a .= "<td>" . $r->description . "</td>";
  //     $a .= "<td>" . $r->qty . "</td>";
  //     $a .= "<td>" . $r->cost . "</td>";
  //     $a .= "</tr>";
  //   }
  //   $a .= "</table>";
  //   echo $a;
  // }
        public function get_display() {

          $id= $_POST['max_no'];

    // $sql="SELECTs nno, ddate, store, ref_no, to_bc, note, order_no, is_cancel, to_bc, m_branch.`cl` as t_cl
    //       FROM t_internal_transfer_sum
    //       JOIN m_branch ON m_branch.`bc` = t_internal_transfer_sum.`to_bc`
    //       WHERE nno = '$id' AND t_internal_transfer_sum.cl = '".$this->sd['cl']."' AND t_internal_transfer_sum.bc ='".$this->sd['branch']."' AND trans_code='43'";

          $sql="SELECT a.nno AS receive_no,
          a.`ddate`AS receive_date,
          a.`ref_no`AS receive_ref_no,
          a.`store`AS receive_store,
          a.`is_cancel` AS is_cancel,
          a.`note` AS issue_note,
          b.`sub_no` AS issue_no,
          b.`cl` AS issue_cl,
          b.`bc` AS issue_bc,
          b.store AS issue_store,
          b.`to_bc` AS issue_to_bc,
          m.`description` AS issue_cl_name,
          mb.`name` AS issue_to_bc_name,
          a.ref_sub_no as receive_sub_no,
          a.ref_type as receive_type,
          a.vehicle,
          v.description as v_des,
          v.stores as v_store,
          a.location_store,
          s.description as location_des,
          a.driver,
          e.name as driver_name,
          a.helper,
          ee.name as helper_name,
          a.upwstock,
          a.trns_mode,
          a.mode_no
          FROM  t_internal_transfer_sum a 
          left JOIN t_internal_transfer_sum b ON a.`ref_trans_code` = b.`trans_code` 
          AND a.ref_sub_no = b.`sub_no` and a.bc = b.to_bc AND a.bc = b.to_bc 
          AND b.bc=a.`issue_bc`
          left JOIN m_cluster m ON m.`code` = b.`cl`
          left JOIN m_branch mb ON mb.`bc` = b.`bc`
          JOIN m_stores s ON s.`code` = a.`location_store`
          JOIN m_vehicle_setup v ON v.`code` = a.vehicle
          JOIN m_employee e on e.code = a.driver
          JOIN m_employee ee on ee.code = a.helper
          WHERE a.sub_no = '$id' 
          AND a.trans_code='43'
          AND a.type='".$_POST['type']."'
          AND a.cl = '".$this->sd['cl']."' 
          AND a.bc = '".$this->sd['branch']."' "  ;    

          $query = $this->db->query($sql);
          $ids="";
          $x     = 0;
          if ($query->num_rows() > 0) {
            $a['sum'] = $query->result();
            $ids = $query->row()->receive_no;
          } else {
            $x = 2;
          }


          $sql2 = "SELECT   
          d.`batch_no`,
          d.`item_code`,
          d.`item_cost`,
          d.`qty`,
          q.`qty` AS cur, 
          i.`description`,
          i.`model`,
          d.item_cost as purchase_price,
          d.`max_price`,
          d.`min_price`,
          d.issued_qty
          FROM t_internal_transfer_det d
          left JOIN qry_current_stock q ON q.`item` = d.`item_code` AND q.batch_no = d.`batch_no`
          JOIN t_internal_transfer_sum s ON s.`nno` = d.`nno` AND s.cl = d.cl AND s.`bc` = s.`bc` AND s.`trans_code` = d.`trans_code` 
          JOIN m_item i ON i.`code` = d.`item_code`
          LEFT JOIN t_item_batch ON t_item_batch.`item` = i.`code` AND d.batch_no = t_item_batch.batch_no
          WHERE d.sub_no = '$id' AND s.type='".$_POST['type']."'
          AND d.cl='".$this->sd['cl']."' 
          AND d.bc='".$this->sd['branch']."' 
          AND s.trans_code='43'
          GROUP BY d.`item_code`
          ";

          $query2 = $this->db->query($sql2);


          if ($query2->num_rows() > 0) {
            $a['det'] = $query2->result();
          } else {
            $x = 2;
          }


          $this->db->select(array('item', 'serial_no'));
          $this->db->from('t_serial_movement');

          $this->db->join('t_internal_transfer_sum', 't_serial_movement.trans_no=t_internal_transfer_sum.nno');
          $this->db->where('t_serial_movement.trans_type', "43");
          $this->db->where('t_serial_movement.trans_no', $ids);
          $this->db->where('t_internal_transfer_sum.cl', $this->sd['cl']);
          $this->db->where('t_internal_transfer_sum.bc', $this->sd['branch']);
          $this->db->group_by("serial_no");
          $query3 = $this->db->get();

          $a['serial'] = $query3->result();



          if ($x == 0) {
            echo json_encode($a);
          } else {
            echo json_encode($x);
          }
        }

        public function check_issue_transfer(){
          $sql="SELECT status FROM t_internal_transfer_sum WHERE trans_code='42' AND type='".$_POST['type']."' AND sub_no = '".$_POST['issue_no']."' AND cl='".$_POST['cl']."' AND bc='".$_POST['bc']."' ";
          $query = $this->db->query($sql)->row()->status;
          if($query=="P"){
            $query=1;
          }else{
            $query=2;
          }
          echo $query;
        }

        public function PDF_report() {
          $this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("bc", $this->sd['branch']);
          $r_detail['branch'] = $this->db->get('m_branch')->result();


          $invoice_number= $this->utility->invoice_format($_POST['qno']); 
          $mno = $this->db->query("select mode_no FROM  t_internal_transfer_sum a WHERE a.sub_no = '".$_POST['sub_qno']."'           AND a.trans_code='43'
            AND a.type='".$_POST['p_type']."'    AND a.cl = '".$this->sd['cl']."'    AND a.bc = '".$this->sd['branch']."'           LIMIT 1")->row()->mode_no;

          $mode_number= $this->utility->invoice_format($mno); 
          $session_array = array(
           $this->sd['cl'],
           $this->sd['branch'],
           $invoice_number,
           $mode_number
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

          $r_detail['store'] = $_POST['st'];
          $r_detail['branchs'] = $_POST['issue_b'];
          $r_detail['issue_no'] = $_POST['req_n'];
          $r_detail['clusters'] = $_POST['issue_cl'];

          $r_detail['page'] = $_POST['page'];
          $r_detail['header'] = $_POST['header'];
          $r_detail['orientation'] = $_POST['orientation'];

          $this->db->select(array('code', 'name', 'address1', 'address2', 'address3'));
          $this->db->where("code", $_POST['cus_id']);
          $r_detail['customer'] = $this->db->get('m_customer')->result();

          $this->db->select(array('name'));
          $this->db->where("code", $_POST['salesp_id']);
          $query = $this->db->get('m_employee');

          foreach ($query->result() as $row) {
            $r_detail['employee'] = $row->name;
          }

          $sqll = "SELECT 
          m.`description` AS cl,
          mb.`name` AS bc,
          a.sub_no,
          a.nno,
          a.trns_mode, a.mode_no

          FROM  t_internal_transfer_sum a 
          JOIN t_internal_transfer_sum b ON a.`ref_trans_code`=b.`trans_code` AND a.ref_sub_no = b.`sub_no`AND a.bc = b.to_bc 
          AND b.bc=a.`issue_bc`
          JOIN m_cluster m ON m.`code` = b.`cl`
          JOIN m_branch mb ON mb.`bc` = b.`bc`
          
          WHERE a.sub_no = '".$_POST['sub_qno']."' 
          AND a.trans_code='43'
          AND a.type='".$_POST['p_type']."'
          AND a.cl = '".$this->sd['cl']."' 
          AND a.bc = '".$this->sd['branch']."' 
          LIMIT 1";
          $n_no=0;          
          $rr=$this->db->query($sqll);
          $r_detail['sum'] = $rr->result();
          $n_no = $rr->first_row()->sub_no;
          $main_no = $rr->first_row()->nno;

          $sql="SELECT 
          `t_internal_transfer_det`.`item_code` AS 'code',
          `m_item`.`description`,  
          `m_item`.`model`,  
          `t_internal_transfer_det`.item_cost,  
          `t_internal_transfer_det`.`min_price`, 
          `t_internal_transfer_det`.`max_price`,   
          `t_internal_transfer_det`.`qty`,
          c.`cc` AS sub_item,
          c.`deee` AS des,
          c.qty * t_internal_transfer_det.qty AS sub_qty,
          `t_internal_transfer_det`.item_cost * `t_internal_transfer_det`.`qty` AS amount 
          FROM
          (`t_internal_transfer_det`) 
          JOIN `m_item` 
          ON `m_item`.`code` = `t_internal_transfer_det`.`item_code` 
          LEFT JOIN `m_item_sub` 
          ON `m_item_sub`.`item_code` = `m_item`.`code` 
          LEFT JOIN `r_sub_item` 
          ON `r_sub_item`.`code` = `m_item_sub`.`sub_item` 
          LEFT JOIN 
          (SELECT 
          t_internal_transfer_det.`item_code`,
          r_sub_item.`description` AS deee,
          r_sub_item.`code` AS cc,
          r_sub_item.`qty` AS qty,
          t_item_movement_sub.`cl`,
          t_item_movement_sub.`bc`,
          t_item_movement_sub.`item`,
          t_item_movement_sub.`sub_item` 
          FROM
          t_item_movement_sub 
          LEFT JOIN t_internal_transfer_det 
          ON t_internal_transfer_det.`item_code` = t_item_movement_sub.`item` 
          AND t_internal_transfer_det.`cl` = t_item_movement_sub.`cl` 
          AND t_internal_transfer_det.`bc` = t_item_movement_sub.`bc` 
          JOIN r_sub_item 
          ON t_item_movement_sub.`sub_item` = r_sub_item.`code` 
          WHERE `t_item_movement_sub`.batch_no = t_internal_transfer_det.`batch_no` 
          AND `t_internal_transfer_det`.`cl` = '".$this->sd['cl']."' 
          AND `t_internal_transfer_det`.`bc` = '".$this->sd['branch']."' 
          AND `t_internal_transfer_det`.`sub_no` = '".$_POST['qno']."') c 
          ON c.`item_code` = t_internal_transfer_det.`item_code` 
          WHERE `t_internal_transfer_det`.`cl` = '".$this->sd['cl']."' 
          AND `t_internal_transfer_det`.`bc` = '".$this->sd['branch']."' 
          AND `t_internal_transfer_det`.`sub_no` = '".$_POST['qno']."' AND t_internal_transfer_det.`type`='".$_POST['p_type']."'
          AND t_internal_transfer_det.trans_code='43' ";
        /*$sql="SELECT `t_internal_transfer_det`.`item_code` as code, 
                     `t_internal_transfer_det`.`qty`, 
                     `t_internal_transfer_det`.item_cost,  
                     `m_item`.`description`, 
                     `m_item`.`model`, 
                     c.`cc` AS sub_item,
                     c.`deee` AS des,
                     c.qty *  t_internal_transfer_det.qty AS sub_qty,
                     `t_internal_transfer_det`.item_cost * `t_internal_transfer_det`.`qty` AS amount
              FROM (`t_internal_transfer_det`) 
              JOIN `m_item` ON `m_item`.`code` = `t_internal_transfer_det`.`item_code` 
              LEFT JOIN `m_item_sub` ON `m_item_sub`.`item_code` = `m_item`.`code` 
              LEFT JOIN `r_sub_item` ON `r_sub_item`.`code` = `m_item_sub`.`sub_item` 
              LEFT JOIN (
                        SELECT t_internal_transfer_det.`item_code` , 
                                r_sub_item.`description` AS deee, 
                                r_sub_item.`code` AS cc,
                                r_sub_item.`qty` AS qty,
                                t_item_movement_sub.cl,
                                t_item_movement_sub.bc,
                                t_item_movement_sub.item,
                                t_item_movement_sub.`sub_item` 
                        FROM t_item_movement_sub 
                        LEFT JOIN t_internal_transfer_det ON t_internal_transfer_det.`item_code` = t_item_movement_sub.`item`  
                        AND t_internal_transfer_det.`cl` = t_item_movement_sub.`cl` AND t_internal_transfer_det.`bc`=t_item_movement_sub.`bc`
                        JOIN r_sub_item ON t_item_movement_sub.`sub_item` = r_sub_item.`code`
                        WHERE t_item_movement_sub.batch_no = t_internal_transfer_det.`batch_no` AND `t_internal_transfer_det`.`cl` = '".$this->sd['cl']."'  
                        AND `t_internal_transfer_det`.`bc` = '".$this->sd['branch']."' AND `t_internal_transfer_det`.`nno` = '".$_POST['qno']."'  )c ON c.item_code = t_internal_transfer_det.`item_code`
            WHERE `t_internal_transfer_det`.`cl` = '".$this->sd['cl']."' 
            AND `t_internal_transfer_det`.`bc` = '".$this->sd['branch']."'
            AND `t_internal_transfer_det`.`nno` = '".$_POST['qno']."'  

            GROUP BY c.cc ,t_internal_transfer_det.`item_code`
            ORDER BY `t_internal_transfer_det`.`auto_no` ASC 

            ";*/


            $sqlGC=$sql."GROUP BY c.cc,
            t_internal_transfer_det.`item_code` 
            HAVING qty >0
            ORDER BY `t_internal_transfer_det`.`auto_no` ASC ";

            $sqlGR=$sql."GROUP BY
            t_internal_transfer_det.`item_code` 
            HAVING qty >0
            ORDER BY `t_internal_transfer_det`.`auto_no` ASC ";   

       // $query = $this->db->query($sqlGC);
            $r_detail['items'] = $this->db->query($sqlGC)->result();

        //$query = $this->db->query($sqlGR);
            $r_detail['itemsGR'] = $this->db->query($sqlGR)->result();


            $this->db->SELECT(array('serial_no','item'));
            $this->db->FROM('t_serial_movement');
            $this->db->WHERE('t_serial_movement.cl', $this->sd['cl']);
            $this->db->WHERE('t_serial_movement.bc', $this->sd['branch']);
            $this->db->WHERE('t_serial_movement.trans_type','43');
            $this->db->WHERE('t_serial_movement.trans_no',$main_no);
            $this->db->group_by('serial_no');
            $r_detail['serial'] = $this->db->get()->result();

            $s="SELECT SUM(item_cost)*qty AS amount
            FROM t_internal_transfer_det
            WHERE nno='".$_POST['qno']."' 
            AND cl='".$this->sd['cl']."' 
            AND bc='".$this->sd['branch']."'";
            $q= $this->db->query($s);    
            $r_detail['amount'] = $q->result();

            $this->db->select(array('t_internal_transfer_sum.oc', 's_users.discription','action_date'));
            $this->db->from('t_internal_transfer_sum');
            $this->db->join('s_users', 't_internal_transfer_sum.oc=s_users.cCode');
            $this->db->where('t_internal_transfer_sum.cl', $this->sd['cl']);
            $this->db->where('t_internal_transfer_sum.bc', $this->sd['branch']);
            $this->db->where('t_internal_transfer_sum.sub_no', $_POST['sub_qno']);
            $this->db->where('t_internal_transfer_sum.type', $_POST['p_type']);
            $this->db->where('t_internal_transfer_sum.trans_code', 43);
            $r_detail['user'] = $this->db->get()->result();

            if($this->db->query($sql)->num_rows()>0){
              $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
            }else{
              echo "<script>alert('No Data');window.close();</script>";
            }

        //$this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
          }
          private function set_delete() {
            $this->db->where("nno", $_POST['id']);
            $this->db->where('cl', $this->sd['cl']);
            $this->db->where('bc', $this->sd['branch']);
            $this->db->delete("t_damage_det");

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("trans_code", 14);
            $this->db->where("trans_no", $_POST['hid']);
            $this->db->delete("t_item_movement");
          }
          public function check_is_serial_item() {
            $this->db->select(array(
              'serial_no'
            ));
            $this->db->where("code", $this->input->post('code'));
            $this->db->limit(1);
            echo $this->db->get("m_item")->first_row()->serial_no;
          }
          public function check_is_serial_items($code) {

            $sql="SELECT `serial_no` ,code  FROM `m_item` WHERE `code` = '$code' LIMIT 1";
            $query = $this->db->query($sql);
            foreach($query->result() as $row){
              return $row->serial_no;
            }
          }

          public function is_serial_entered($trans_no, $item, $serial) {
            $this->db->select(array(
              'available'
            ));
            $this->db->where("serial_no", $serial);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where("item", $item);
            $query = $this->db->get("t_serial");
            if ($query->num_rows() > 0) {
              return 1;
            } else {
              return 0;
            }
          }

          public function get_batch_serial_wise($item, $serial) {
            if ($_POST['df_is_serial'] == '1') {
              $this->db->select("batch");
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->where("item", $item);
              $this->db->where("serial_no", $serial);
      //return $this->db->get('t_serial')->first_row()->batch+1;

              $bb=1;
              $query=$this->db->get('t_serial')->result();
              foreach ($query as $a){
                $bb = $a->batch;
              }
              return $bb+1;

            }
          }



          public function checkdelete(){
            $id = $_POST['no'];

            $sql="SELECT *
            FROM `t_internal_transfer_sum` 
            WHERE `t_internal_transfer_sum`.`sub_no` = '$id' 
            AND cl='".$this->sd['cl']."' 
            AND bc='".$this->sd['branch']."' 
            AND trans_code='43'
            LIMIT 25 ";   

            $query=$this->db->query($sql);

            if($query->num_rows()>0){
              $a['det']=$query->result();
              echo json_encode($a);
            }else{
              echo json_encode("2");
            }
          }

          public function delete_validation(){
            $status=1;
            $no = $_POST['id'];

    // $check_cancellation = $this->trans_cancellation->damage_update_status($no,42,'t_internal_transfer_sum','t_internal_transfer_det');
    // if ($check_cancellation != 1) {
    //   return $check_cancellation;
    // }
            return $status;
          }

          public function delete(){

            $this->db->trans_begin();
            error_reporting(E_ALL); 
            function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
              throw new Exception($errMsg); 
            }
            set_error_handler('exceptionThrower'); 
            try {
              $bc=$this->sd['branch'];
              $cl=$this->sd['cl'];
              $trans_no=$_POST['id'];

              if($this->user_permissions->is_delete('t_internal_transfer_receive')){
                $delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){

                  $this->load->model('trans_settlement');
                  $this->trans_settlement->delete_item_movement('t_item_movement',43,$trans_no);

                  $this->db->where('cl',$cl);
                  $this->db->where('bc',$bc);
                  $this->db->where('trans_code',43);
                  $this->db->where('trans_no',$trans_no);
                  $this->db->delete('t_item_movement_sub');

                  $this->db->where('ref_cl',$this->sd['cl']);
                  $this->db->where('ref_bc',$this->sd['branch']);
                  $this->db->where('trans_code',43);
                  $this->db->where('trans_no',$trans_no);
                  $this->db->delete('t_account_trans');

                  $t_serial = array(
                    "cl" =>$_POST['hid_cl'],
                    "bc" =>$_POST['hid_bc'],
                    "trans_type" =>44,
                    "trans_no" =>$trans_no,
                    "out_doc" => 42,
                    "store_code" => $_POST['hid_store'],
                    "out_no" => $_POST['hid_nno'],
                    "out_date" => $_POST['date'],
                    "available" => '0'
                  );

                  $update_status = array(
                    "status" =>"P"            
                  );

                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->where("trans_no", $trans_no);
                  $this->db->where("trans_type", 43);
                  $this->db->update("t_serial", $t_serial);

         //$this->utility->remove_batch_item($this->sd['cl'],$this->sd['branch'],43,$trans_no,"t_item_batch");


                  $this->db->where("cl", $_POST['hid_cl']);
                  $this->db->where("bc", $_POST['hid_bc']);
                  $this->db->where("sub_no", $_POST['hid_nno']);
                  $this->db->where("trans_code", 42);
                  $this->db->update("t_internal_transfer_sum", $update_status);

                  $this->db->select(array('item', 'serial_no'));
                  $this->db->where("trans_no", $trans_no);
                  $this->db->where("trans_type", '43');
                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $query = $this->db->get("t_serial_movement");

                  foreach ($query->result() as $row) {
                   $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$row->item' AND serial_no='$row->serial_no' ");
                   $this->db->where("item", $row->item);
                   $this->db->where("serial_no", $row->serial_no);
         // $this->db->where("cl", $this->sd['cl']);
         // $this->db->where("bc", $this->sd['branch']);
        // $this->db->delete("t_serial_movement");


                   $this->db->where("item", $row->item);
                   $this->db->where("serial_no", $row->serial_no);
                   $this->db->where("cl", $this->sd['cl']);
                   $this->db->where("bc", $this->sd['branch']);
                   $this->db->where("trans_type", '43');
                   $this->db->where("trans_no", $trans_no);
                   $this->db->delete("t_serial_movement_out");
                 }



                 $data=array('is_cancel'=>'1');
                 $this->db->where('cl',$this->sd['cl']);
                 $this->db->where('bc',$this->sd['branch']);
                 $this->db->where('trans_code',43);
                 $this->db->where('trans_no',$trans_no);
                 $this->db->update('t_internal_transfer_sum',$data);

        // $status=array("status" => 1);
        // $this->db->where("nno", $_POST['order_no']);
        // $this->db->update("t_internal_transfer_order_sum", $status);   

                 $this->utility->save_logger("CANCEL",43,$_POST['trans_no'],$this->mod);
                 echo $this->db->trans_commit();
               }else{
                echo $delete_validation_status;
                $this->db->trans_commit();
              }
            }else{
              echo "No permission to delete records";
              $this->db->trans_commit();
            }
          }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
          }   
        }

        public function load_t_issue(){

          $sql="SELECT  s.nno ,
          d.`item_code` , 
          m.`description` , 
          m.`model` , 
          d.`batch_no` , 
          IFNULL(d.`item_cost`,t_item_batch.purchase_price) as purchase_price, 
          IFNULL(d.`min_price`,t_item_batch.min_price) as min_price, 
          IFNULL(d.`max_price`,t_item_batch.max_price) as max_price, 
          q.`qty` AS cur, 
          d.`qty` 
          FROM t_internal_transfer_sum s
          JOIN t_internal_transfer_det d ON d.`nno` = s.`nno` AND d.cl = s.cl AND s.bc = d.bc
          JOIN m_item m ON m.`code` = d.`item_code`
          LEFT JOIN qry_current_stock q ON q.`item` = m.`code`
          JOIN t_item_batch ON t_item_batch.`item` = m.`code` AND  t_item_batch.`batch_no`=d.`batch_no`
          WHERE s.sub_no = '".$_POST['issue_no']."'  AND s.cl='".$_POST['cl']."' AND s.bc='".$_POST['bc']."' AND s.type = '".$_POST['type']."'
          AND d.trans_code='42'
          GROUP BY d.`item_code`";

          $query = $this->db->query($sql);

          if ($query->num_rows() > 0) {
            $a = $query->result();
          }else{
            $a = 2;
          }
          echo json_encode($a);

        }

        public function selection_list(){


          if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

        //$sql = "SELECT * FROM $table  WHERE ($field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' ) ".$add_query." LIMIT 25";

      /*$sql="SELECT t.cl, t.bc, t.`sub_no` as nno, m.`name` ,c.`description`, c.code, t.note, t.store, t.`vehicle`,v.description as v_des,v.stores as v_stores
            FROM t_internal_transfer_sum t
            JOIN m_branch m ON m.`bc` = t.`to_bc`
            JOIN m_cluster c ON c.`code` = m.`cl`
            JOIN m_vehicle_setup v ON v.`code` = t.`vehicle`
            WHERE t.is_cancel ='0' AND t.trans_code='42' AND to_bc = '".$this->sd['branch']."' AND status='P' AND
            ( t.`nno`LIKE '%$_POST[search]%' OR m.`name` LIKE '%$_POST[search]%' ) AND type = '".$_POST['type']."'
            ";*/

            $sql="SELECT t.nno as max_no,
            t.cl, 
            t.bc, 
            t.`sub_no` as nno, 
            m.`name` ,
            c.`description`, 
            c.code, 
            t.note, 
            t.store, 
            t.`vehicle`,
            v.description as v_des,
            t.location_store ,
            s.description as location_name,
            t.driver,
            e.name as driver_name,
            t.helper,
            ee.name as helper_name,
            r.description AS dep_name
            FROM t_internal_transfer_sum t
            JOIN `t_internal_transfer_det` d ON d.`cl`=t.`cl` AND d.`bc`=t.`bc` AND d.`nno`=t.`nno`
            JOIN m_item i ON i.`code`=d.`item_code`
            JOIN `r_department` r ON r.`code`=i.`department`
            JOIN m_branch m ON m.`bc` = t.`bc` 
            JOIN m_cluster c ON c.`code` = t.`cl` 
            LEFT JOIN m_vehicle_setup v ON v.`code` = t.`vehicle`
            JOIN m_employee e on e.code = t.driver
            JOIN m_employee ee on ee.code = t.helper
            join m_stores s ON s.code = t.location_store AND s.cl = t.cl AND s.bc = t.bc
            WHERE t.is_cancel ='0'  AND t.trans_code='42' AND to_bc = '".$this->sd['branch']."' AND status='P' AND trns_mode='".$_POST['mode']."' AND
            ( t.`nno`LIKE '%$_POST[search]%' OR m.`name` LIKE '%$_POST[search]%' ) AND t.type = '".$_POST['type']."'
            AND t.is_approve='1' GROUP BY t.cl,t.bc,t.nno";

            $query = $this->db->query($sql);
            $a = "<table id='item_list' style='width : 100%' >";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Shipment Number</th>";
            $a .= "<th class='tb_head_th' colspan='2'>Branch</th>";
            $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

            foreach($query->result() as $r){
              $a .= "<tr class='cl'>";
              $a .= "<td>".$r->nno."</td>";
              $a .= "<td>".$r->name."</td>";
              $a .= "<td style='display:none;'>".$r->code."</td>";
              $a .= "<td style='display:none;'>".$r->description."</td>";
              $a .= "<td style='display:none;'>".$r->note."</td>";
              $a .= "<td style='display:none;'>".$r->cl."</td>";
              $a .= "<td style='display:none;'>".$r->bc."</td>";
              $a .= "<td style='display:none;'>".$r->nno."</td>";
              $a .= "<td style='display:none;'>".$r->store."</td>";
              $a .= "<td style='display:none;'>".$r->vehicle."</td>";
              $a .= "<td style='display:none;'>".$r->v_des."</td>";
              $a .= "<td style='display:none;'>".$r->location_store."</td>";
              $a .= "<td style='display:none;'>".$r->location_name."</td>";
              $a .= "<td style='display:none;'>".$r->max_no."</td>";

              $a .= "<td style='display:none;'>".$r->driver."</td>";
              $a .= "<td style='display:none;'>".$r->driver_name."</td>";
              $a .= "<td style='display:none;'>".$r->helper."</td>";
              $a .= "<td style='display:none;'>".$r->helper_name."</td>";
              $a .= "<td>".$r->dep_name."</td>";
              $a .= "</tr>";
            }
            $a .= "</table>";
            echo $a;
          }
        }
        ?>