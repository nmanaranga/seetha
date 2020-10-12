<?php
// 1=pending,2=issued,3=rejected t_internal_transfer_sum table, status field
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class g_internel_transfer extends CI_Model {
  private $max_no;
  private $mod='003';
  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->m_stores          = $this->tables->tb['m_stores'];
    $this->qry_current_stock = $this->tables->tb['qry_current_stock'];
    $this->t_damage_sum      = $this->tables->tb['t_damage_sum'];
    $this->load->model("utility");
    $this->load->model('user_permissions');
    $this->max_no = $this->utility->get_max_no("g_t_internal_transfer_sum", "nno");
    
  }

  public function base_details(){
    $a['id'] = $this->utility->get_max_no("g_t_internal_transfer_sum", "nno");
    $a['sub_max_no']= $this->utility->get_max_no_in_type_transfer("g_t_internal_transfer_sum","sub_no","request","62");
    $a['branch'] = $this->branch();
    $a['cluster'] = $this->cluster();
    $a['store'] = $this->store();
    $a['location_store'] = $this->location_store();    
    $a['from_branch'] = $this->sd['branch'];
    $a['from_branch_name'] = $this->from_branch_name();
    return $a;
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

  public function location_store(){
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $this->db->where('transfer_location', '1');
    $query = $this->db->get("m_stores");
    $st    = "<select name='v_store' id='v_store' class='store11'>";
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
    $sql="SELECT 
    d.`item_code`,
    m.`description`,
    m.`cost`,
    m.`price`,
    IFNULL(q.`qty`,0) AS cur,
    d.`qty` 
    FROM
    g_t_internal_transfer_order_sum s 
    LEFT JOIN g_t_internal_transfer_order_det d 
    ON d.`nno` = s.`nno` 
    AND d.`cl` = s.`cl` 
    AND d.`bc` = s.`bc` 
    LEFT JOIN g_m_gift_voucher m 
    ON m.`code` = d.`item_code` 

    LEFT JOIN (SELECT item,qty FROM gift_qry_current_stock WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND store_code='".$_POST['store_from']."') q ON q.item=d.`item_code`         
    WHERE s.sub_no = '$order'  AND s.`to_bc`='".$this->sd['branch']."' AND s.is_cancel !='1' AND d.type ='".$_POST['type']."'
    AND s.`cl` = '".$_POST['get_cl']."' AND s.`bc` = '".$_POST['get_bc']."'
    GROUP BY d.`item_code`";
  /*  $sql="SELECT 
                d.`item_code`,
                m.`description`,
                m.`cost`,
                m.`price`,
                q.`qty` AS cur,
                d.`qty` 
                FROM
                g_t_internal_transfer_order_sum s 
                LEFT JOIN g_t_internal_transfer_order_det d 
                ON d.`nno` = s.`nno` 
                AND d.`cl` = s.`cl` 
                AND d.`bc` = s.`bc` 
                LEFT JOIN g_m_gift_voucher m 
                ON m.`code` = d.`item_code` 
                LEFT JOIN gift_qry_current_stock q 
                ON q.`item` = m.`code`          
                WHERE s.sub_no = '$order'  AND s.`to_bc`='".$this->sd['branch']."' AND s.is_cancel !='1' AND d.type ='".$_POST['type']."'
                AND s.`cl` = '".$_POST['get_cl']."' AND s.`bc` = '".$_POST['get_bc']."'
                GROUP BY d.`item_code`";*/

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
                FROM g_t_internal_transfer_order_sum
                WHERE sub_no='".$_POST['order_no']."' 
                AND to_bc='".$this->sd['branch']."' 
                AND bc='".$_POST['bc']."'
                AND type ='".$_POST['type']."'";

                $query=$this->db->query($sql)->result();
                $q=0;
                foreach($query as $r){
                  $q = $r->status;
                }

                echo $q;
              }


              public function reject(){
                $this->db->trans_begin();
                error_reporting(E_ALL); 
                function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
                  throw new Exception($errMsg); 
                }
                set_error_handler('exceptionThrower'); 
                try { 
                  $sql="UPDATE t_internal_transfer_order_sum SET status='3' WHERE sub_no='".$_POST['order_no']."' AND to_bc='".$this->sd['branch']."' AND type ='".$_POST['type']."' ";
                  $query=$this->db->query($sql);
                  echo $this->db->trans_commit(); 
                }catch(Exception $e){ 
                  $this->db->trans_rollback();
                  echo $e->getMessage()."Operation fail please contact admin"; 
                } 

              }

              public function validation() {
                $status  = 1;
                $this->sub_max_no=$this->utility->get_max_no_in_type_echo2_transfer("g_t_internal_transfer_sum","sub_no",$_POST['types'],"62");

                $check_is_delete = $this->validation->check_is_cancel($this->max_no, 'g_t_internal_transfer_sum');
                if ($check_is_delete != 1) {
                  return "Internal transfer already deleted";
                }
                $chk_item_store_validation = $this->validation->gift_check_item_with_store($_POST['store_from'], '0_');
                if ($chk_item_store_validation != 1) {
                  return $chk_item_store_validation;
                }
                $serial_validation_status = $this->validation->serial_update_gift('0_', '2_','all_serial_');
                if ($serial_validation_status != 1) {
                  return $serial_validation_status;
                }
                $check_gift_qty = $this->utility->check_gift_cur_qty('0_','2_',$_POST['store_from']);
                if ($check_gift_qty != 1) {
                  return $check_gift_qty;
                }
    /*$check_batch_validation = $this->utility->batch_updatee('0_', '1_', '2_');
    if ($check_batch_validation != 1) {
      return $check_batch_validation;
    }
    $check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
      if($check_zero_value!=1){
        return $check_zero_value;
      }*/
      $minimum_price_validation = $this->validation->check_min_price('0_', '3_');
      if ($minimum_price_validation != 1) {
        return $minimum_price_validation;
      }
      return $status;

    }

    public function save(){

      $this->db->trans_begin();
      error_reporting(E_ALL); 
      function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errLine); 
      }
      set_error_handler('exceptionThrower'); 
      try { 

        $_POST['date']=$_POST['ddate'];
        $validation_status=$this->validation();
        if($validation_status==1){
          $execute=0;
          $subs="";

          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
              if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != ""  && $_POST['3_'.$x] != ""){

                $g_t_item_movement[]=array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],                  
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>62,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['ddate'],
                  "qty_in"=>0,
                  "qty_out"=>$_POST['2_'.$x],
                  "store_code"=>$_POST['store_from'],
                  "sales_price"=>$_POST['3_' . $x],
                  "cost"=>$_POST['c_' . $x],
                );

                $g_t_item_movement_out[]=array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],                  
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>62,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['ddate'],
                  "qty_in"=>$_POST['2_'.$x],
                  "qty_out"=>0,
                  "store_code"=>$_POST['v_store'],
                  "sales_price"=>$_POST['3_' . $x],
                  "cost"=>$_POST['c_' . $x],
                );
              }
            }     
          }

          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
              if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != ""){
               $b[]= array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "nno"=>$this->max_no,
                "sub_no"=>$this->sub_max_no,
                "type"=>$_POST['types'],
                "item_code"=>$_POST['0_'.$x],
                "qty"=>$_POST['2_'.$x],
                "item_cost" =>$_POST['c_' . $x],
                "trans_code" =>62,
              );               
             }
           }
         }                    

         $data=array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->max_no,
          "sub_no"=>$this->sub_max_no,
          "type"=>$_POST['types'],
          "ref_no" => $_POST['ref_no'],
          "ddate" => $_POST['ddate'],
          "to_cl" => $_POST['to_cluster'],
          "to_bc" => $_POST['t_branch'],
          "store" => $_POST['store_from'],
          "order_no" => $_POST['order_no'],
          "note" => $_POST['note'],
          "oc" => $this->sd['oc'],
          "trans_code" => 62,
          "trans_no" => $this->max_no,
          "vehicle" => $_POST['vehicle'],
          "location_store" => $_POST['v_store']
          //"trans_no" =>$this->sub_max_no
        );

         $status=array(
          "status" => 2, //-- status update to issued
        );


         if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('g_internel_transfer')){  
            $account_update=$this->account_update(0);
            if($account_update==1){
              if($_POST['df_is_serial']=='1'){
                $this->serial_save_out(); 
                $this->serial_save_in();   
              }


              $this->db->insert('g_t_internal_transfer_sum',$data);
              if(count($b)){$this->db->insert_batch("g_t_internal_transfer_det",$b);}
              if(count($g_t_item_movement)){ $this->db->insert_batch("g_t_item_movement", $g_t_item_movement);}
              if(count($g_t_item_movement_out)){ $this->db->insert_batch("g_t_item_movement", $g_t_item_movement_out);}

              /*$this->db->where("to_bc", $_POST['f_branch']);
              $this->db->where("type", $_POST['types']);
              $this->db->where("sub_no", $_POST['order_no']);
              $this->db->update("g_t_internal_transfer_order_sum", $status); */  

              $this->db->where("bc", $_POST['t_branch']);
              $this->db->where("to_bc", $this->sd['branch']);
              $this->db->where("type", $_POST['types']);
              $this->db->where("sub_no", $_POST['order_no']);
              $this->db->update("g_t_internal_transfer_order_sum", $status); 

              $this->account_update(1);

              $this->utility->save_logger("SAVE",62,$this->max_no,$this->mod);
              echo $this->db->trans_commit();
            }else{
              echo "Invalid account entries";
              $this->db->trans_commit();
            }
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }   
        }else{
          if($this->user_permissions->is_edit('g_internel_transfer')){
            $check_cancellation = $this->check_transfer_issue_received($_POST['id'],$_POST['to_cluster'],$_POST['t_branch']);
            if ($check_cancellation == 1) {   
              $account_update=$this->account_update(0);
              if($account_update==1){        
                if($_POST['df_is_serial']=='1'){
                  $this->serial_save_out();   
                  $this->serial_save_in(); 
                }

                $data_update=array(
                  "cl" => $this->sd['cl'],
                  "bc" => $this->sd['branch'],
                  "nno" => $this->max_no,
                  "sub_no"=>$this->sub_max_no,
                  "type"=>$_POST['types'],
                  "ref_no" => $_POST['ref_no'],
                  "ddate" => $_POST['ddate'],
                  "to_cl" => $_POST['to_cluster'],
                  "to_bc" => $_POST['t_branch'],
                  "store" => $_POST['store_from'],
                  "order_no" => $_POST['order_no'],
                  "note" => $_POST['note'],
                  "vehicle" => $_POST['vehicle'],
                  "oc" => $this->sd['oc'],
                  "location_store" => $_POST['v_store']
                );

                $this->db->where("trans_code", 62);
                $this->db->where("trans_no", $_POST['hid']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("g_t_item_movement");

                $this->db->where("nno", $_POST['hid']);
                $this->db->where("type",$_POST['types']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("g_t_internal_transfer_det");

                $this->db->where("nno", $_POST['hid']);
                $this->db->where("type",$_POST['types']);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->update("g_t_internal_transfer_sum", $data_update);

                if(count($b)){$this->db->insert_batch("g_t_internal_transfer_det",$b);}
                if(count($g_t_item_movement)){ $this->db->insert_batch("g_t_item_movement", $g_t_item_movement);}
                if(count($g_t_item_movement_out)){ $this->db->insert_batch("g_t_item_movement", $g_t_item_movement_out);}
                
                $this->db->where("bc", $_POST['t_branch']);
                $this->db->where("to_bc", $this->sd['branch']);
                $this->db->where("type", $_POST['types']);
                $this->db->where("sub_no", $_POST['order_no']);
                $this->db->update("g_t_internal_transfer_order_sum", $status); 

                $this->account_update(1);

                $this->utility->save_logger("EDIT",62,$this->max_no,$this->mod);
                echo $this->db->trans_commit();   
              }else{
                echo "Invalid account entries";
                $this->db->trans_commit();
              }
            }else{
              echo "Updating failed, Transfer issue number already received";
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

  public function serial_save_out() {
    if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
      for ($x = 0; $x < 25; $x++) {
        if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
          if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
            if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){
                if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                  $g_t_serial = array(
                    "out_doc"     => 62,
                    "out_no"      => $this->max_no,
                    "out_date"    => $_POST['date'],
                    "available"   => '0'
                  );

                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->where('serial_no', $p[$i]);
                  $this->db->where("item", $_POST['0_' . $x]);
                  $this->db->where("max_price", $_POST['3_' . $x]);
                  $this->db->update("g_t_serial", $g_t_serial);

                  $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_' . $x]."' AND serial_no='".$p[$i]."' ");

                  $g_t_serial_movement_out[] = array(
                    "cl" => $this->sd['cl'],
                    "bc" => $this->sd['branch'],
                    "trans_type" => 62,
                    "trans_no" => $this->max_no,
                    "item" => $_POST['0_' . $x],
                    "serial_no" => $p[$i],
                    "qty_in" => 0,
                    "qty_out" => 1,
                    "cost" => $_POST['3_' . $x],
                    "store_code" => $_POST['store_from'],
                    "computer" => $this->input->ip_address(),
                    "oc" => $this->sd['oc'],
                  );

                  $this->db->where("cl", $this->sd['cl']);
                  $this->db->where("bc", $this->sd['branch']);
                  $this->db->where("item", $_POST['0_' . $x]);
                  $this->db->where("serial_no", $p[$i]);
                  $this->db->delete("g_t_serial_movement");

                  $this->db->where("cl", $_POST['to_cluster']);
                  $this->db->where("bc", $_POST['t_branch']);
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

           /*
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
           $this->db->where("out_doc", 60);
           $this->db->update("t_serial", $t_serial);
          */

           $g_t_serial = array(
             "store_code"  => $_POST['store_from'],
             "engine_no" => "",
             "chassis_no" => '',
             "out_doc" => "",
             "out_no" => "",
             "out_date" => "",
             "available" => '1'
           );

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 62);
           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc", $this->sd['branch']);
           $query1 = $this->db->get("g_t_serial_movement");

           foreach ($query1->result() as $row) {
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("item", $row->item);
            $this->db->where("serial_no", $row->serial_no);
            $this->db->update("g_t_serial", $g_t_serial);
          }

          $this->db->select(array('item', 'serial_no'));
          $this->db->where("trans_no", $this->max_no);
          $this->db->where("trans_type", 62);
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
         $this->db->where("trans_type", 62);
         $this->db->delete("g_t_serial_movement");

         $this->db->where("cl", $this->sd['cl']);
         $this->db->where("bc",$this->sd['branch']);
         $this->db->where("trans_no", $this->max_no);
         $this->db->where("trans_type", 62);
         $this->db->delete("g_t_serial_movement_out");


         for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
            if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
             if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){

               $g_t_seriall = array(
                 "out_doc" => 62,
                 "out_no" => $this->max_no,
                 "out_date" => $_POST['date'],
                 "available" => '0'
               );

               $this->db->where("cl", $this->sd['cl']);
               $this->db->where("bc", $this->sd['branch']);
               $this->db->where('serial_no', $p[$i]);
               $this->db->where("item", $_POST['0_'.$x]);
               $this->db->where("max_price", $_POST['3_' . $x]);
               $this->db->update("g_t_serial", $g_t_seriall);

               $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

               $g_t_serial_movement_out[] = array(
                 "cl" => $this->sd['cl'],
                 "bc" => $this->sd['branch'],
                 "trans_type" => 62,
                 "trans_no" => $this->max_no,
                 "item" => $_POST['0_'.$x],
                 "serial_no" => $p[$i],
                 "qty_in" => 0,
                 "qty_out" => 1,
                 "cost" => $_POST['3_' . $x],
                 "store_code" => $_POST['store_from'],
                 "computer" => $this->input->ip_address(),
                 "oc" => $this->sd['oc'],
               );

               $this->db->where("cl", $this->sd['cl']);
               $this->db->where("bc", $this->sd['branch']);
               $this->db->where("item", $_POST['0_' . $x]);
               $this->db->where("serial_no", $p[$i]);
               $this->db->delete("g_t_serial_movement");

               $this->db->where("cl", $_POST['to_cluster']);
               $this->db->where("bc", $_POST['t_branch']);
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

public function serial_save_in() {
  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
    for ($x = 0; $x < 25; $x++) {
      if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
        if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
          if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
            $serial = $_POST['all_serial_'.$x];
            $p=explode(",",$serial);
            for($i=0; $i<count($p); $i++){
              if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                $g_t_seriall = array(
                          /*"store_code"  => $this->sales_order_store ,
                          "engine_no"   => "",
                          "chassis_no"  => '',
                          "out_doc"     => "",
                          "out_no"      => "",
                          "out_date"    => "",
                          "available"   => '1'*/

                          "out_doc" => 62,
                          "store_code" =>$_POST['v_store'],
                          "out_no" => $this->max_no,
                          "out_date" => $_POST['date'],
                          "available" => '1'
                        );

                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where('serial_no', $p[$i]);
                $this->db->where("item", $_POST['0_' . $x]);
                $this->db->where("max_price", $_POST['3_' . $x]);
                $this->db->update("g_t_serial", $g_t_seriall);

                $this->db->query("INSERT INTO g_t_serial_movement SELECT * FROM g_t_serial_movement_out WHERE item='".$_POST['0_' . $x]."' AND serial_no='".$p[$i]."' ");

                $g_t_serial_movement[] = array(
                  "cl" => $this->sd['cl'],
                  "bc" => $this->sd['branch'],
                  "trans_type" => 62,
                  "trans_no" => $this->max_no,
                  "item" => $_POST['0_' . $x],
                  "serial_no" => $p[$i],
                  "qty_in" => 1,
                  "qty_out" => 0,
                  "cost" => $_POST['3_' . $x],
                  "store_code" => $_POST['v_store'],
                  "computer" => $this->input->ip_address(),
                  "oc" => $this->sd['oc'],
                );

                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where("item", $_POST['0_' . $x]);
                $this->db->where("serial_no", $p[$i]);
                $this->db->delete("g_t_serial_movement_out");

                $this->db->where("cl", $_POST['to_cluster']);
                $this->db->where("bc", $_POST['t_branch']);
                $this->db->where("item", $_POST['0_' . $x]);
                $this->db->where("serial_no", $p[$i]);
                $this->db->delete("g_t_serial_movement_out");
              }

            }
          }
        }
      }
    }

  }else{
   $g_t_serial = array(
     "store_code"  => $_POST['store_from'],
     "engine_no" => "",
     "chassis_no" => '',
     "out_doc" => "",
     "out_no" => "",
     "out_date" => "",
     "available" => '1'
   );

   $this->db->select(array('item', 'serial_no'));
   $this->db->where("trans_no", $this->max_no);
   $this->db->where("trans_type", 62);
   $this->db->where("cl", $this->sd['cl']);
   $this->db->where("bc", $this->sd['branch']);
   $query = $this->db->get("g_t_serial_movement");


          /*   $this->db->where("cl", $this->sd['cl']);
             $this->db->where("bc", $this->sd['branch']);
             $this->db->where("item", $row->item);
             $this->db->where("serial_no", $row->serial_no);
             $this->db->update("t_serial", $t_serial);

             var_dump($query->result());
*/



             foreach ($query->result() as $row) {
               $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='$row->item' AND serial_no='$row->serial_no'");
               $this->db->where("item", $row->item);
               $this->db->where("serial_no", $row->serial_no);
               $this->db->where("cl", $this->sd['cl']);
               $this->db->where("bc", $this->sd['branch']);
               $this->db->delete("g_t_serial_movement");

             }

         /*  $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc", $this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 60);
           $this->db->delete("t_serial_movement_out");*/

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc",$this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 62);
           $this->db->delete("g_t_serial_movement");


           for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
              if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
               if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
                $serial = $_POST['all_serial_'.$x];
                $p=explode(",",$serial);
                for($i=0; $i<count($p); $i++){

                 $g_t_seriall = array(
                                /*"store_code"  => $this->sales_order_store,
                                "engine_no"   => "",
                                "chassis_no"  => '',
                                "out_doc"     => "",
                                "out_no"      => "",
                                "out_date"    => "",
                                "available"   => '1'*/
                                "out_doc" => 62,
                                "out_no" => $this->max_no,
                                "store_code" =>$_POST['v_store'],
                                "out_date" => $_POST['ddate'],
                                "available" => '1'
                              );

                 $this->db->where("cl", $this->sd['cl']);
                 $this->db->where("bc", $this->sd['branch']);
                 $this->db->where('serial_no', $p[$i]);
                 $this->db->where("item", $_POST['0_'.$x]);
                 $this->db->where("max_price", $_POST['3_' . $x]);
                 $this->db->update("g_t_serial", $g_t_seriall);

                 $this->db->query("INSERT INTO g_t_serial_movement SELECT * FROM g_t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                 $g_t_serial_movement[] = array(
                   "cl" => $this->sd['cl'],
                   "bc" => $this->sd['branch'],
                   "trans_type" => 62,
                   "trans_no" => $this->max_no,
                   "item" => $_POST['0_'.$x],
                   "serial_no" => $p[$i],
                   "qty_in" => 1,
                   "qty_out" => 0,
                   "cost" => $_POST['3_' . $x],
                   "store_code" => $_POST['v_store'],
                   "computer" => $this->input->ip_address(),
                   "oc" => $this->sd['oc'],
                 );

                 $this->db->where("cl", $this->sd['cl']);
                 $this->db->where("bc", $this->sd['branch']);
                 $this->db->where("item", $_POST['0_' . $x]);
                 $this->db->where("serial_no", $p[$i]);
                 $this->db->delete("g_t_serial_movement_out");

                 $this->db->where("cl", $_POST['to_cluster']);
                 $this->db->where("bc", $_POST['t_branch']);
                 $this->db->where("item", $_POST['0_' . $x]);
                 $this->db->where("serial_no", $p[$i]);
                 $this->db->delete("g_t_serial_movement_out");
               }
             }
           } 
         }
       }
     }

     if($_POST['hid'] == "0" || $_POST['hid'] == "") {
      if(isset($g_t_serial_movement)) {
        if(count($g_t_serial_movement)) {
          $this->db->insert_batch("g_t_serial_movement", $g_t_serial_movement);
        }
      }
    }else{
      if(isset($g_t_serial_movement)) {
        if(count($g_t_serial_movement)) {
          $this->db->insert_batch("g_t_serial_movement", $g_t_serial_movement);
        }
      }
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

  // public function checkload(){
  //     $status = 1;
  //     $sql="SELECT * 
  //           FROM t_internal_transfer_sum 
  //           WHERE cl='".$_POST['cluster']."' 
  //           AND bc='".$_POST['branchs']."' 
  //           AND ref_trans_code='42' 
  //           AND ref_trans_no='".$_POST['id']."' 
  //           AND is_cancel='0'";
  //     $query = $this->db->query($sql);
  
  //     if($query->num_rows() > 0){      
  //       $status = "Deleteing failed, Transfer issue number already received";
  //     }else{
  //       $status = 1;
  //     }
  //     echo $status;
  //   }

  
  public function get_display() {

    $sub_id= $_POST['max_no'];
    $type= $_POST['type'];
   // $sub_id=$_POST['sub_no'];

    $sql="SELECT nno, 
    ddate, 
    store, 
    ref_no, 
    to_bc, 
    note, 
    order_no, 
    is_cancel, 
    to_cl as t_cl, 
    to_bc,
    type,
    sub_no,
    g_t_internal_transfer_sum.vehicle,
    m_vehicle_setup.description as vehicle_des,
    location_store
    FROM g_t_internal_transfer_sum
    JOIN m_branch ON m_branch.`bc` = g_t_internal_transfer_sum.`to_bc`
    LEFT JOIN m_vehicle_setup ON m_vehicle_setup.`code` = g_t_internal_transfer_sum.`vehicle`
    WHERE sub_no = '$sub_id' AND trans_code='62' AND g_t_internal_transfer_sum.cl = '".$this->sd['cl']."' AND g_t_internal_transfer_sum.bc ='".$this->sd['branch']."' AND g_t_internal_transfer_sum.type ='$type'";

    $query = $this->db->query($sql);

    $id="";
    $x     = 0;
    if ($query->num_rows() > 0) {
      $a['sum'] = $query->result();
      $id = $query->row()->nno;
    } else {
      $x = 2;
    }

    $sql2 = "SELECT d.item_code, 
    m.`description`, 
    m.`cost`,
    m.`price`,
    q.`qty` AS cur,
    d.qty
    FROM g_t_internal_transfer_det d
    JOIN g_m_gift_voucher m ON m.`code` = d.`item_code`
    left JOIN gift_qry_current_stock q ON q.`item` = d.`item_code` 
    WHERE d.sub_no ='$sub_id' AND d.`trans_code` ='62' AND d.type='$type' AND d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."'
    GROUP BY d.`item_code`
    ";
    

    $query2 = $this->db->query($sql2);


    if ($query2->num_rows() > 0) {
      $a['det'] = $query2->result();
    } else {
      $x = 2;
    }

    $this->db->select(array('g_t_serial.item', 'g_t_serial.serial_no'));
    $this->db->from('g_t_serial');
    $this->db->join('g_t_internal_transfer_sum', 'g_t_serial.out_no=g_t_internal_transfer_sum.nno');
    $this->db->where('g_t_serial.out_doc', 62);
    $this->db->where('g_t_serial.out_no', $id);
    $this->db->where('g_t_internal_transfer_sum.cl', $this->sd['cl']);
    $this->db->where('g_t_internal_transfer_sum.bc', $this->sd['branch']);
    $query3 = $this->db->get();

    $a['serial'] = $query3->result();



    if ($x == 0) {
      echo json_encode($a);
    } else {
      echo json_encode($x);
    }
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

    $this->db->select(array('code', 'name', 'address1', 'address2', 'address3'));
    $this->db->where("code", $_POST['cus_id']);
    $r_detail['customer'] = $this->db->get('m_customer')->result();

    $this->db->select(array('name'));
    $this->db->where("code", $_POST['salesp_id']);
    $query = $this->db->get('m_employee');

    foreach ($query->result() as $row) {
      $r_detail['employee'] = $row->name;
    }

    $sqll = "SELECT s.nno, b.`name` as bc , l.`description` as cl, c.name as to_bc, c.description as to_cl
    FROM g_t_internal_transfer_sum s
    JOIN m_branch b ON s.`bc` = b.`bc` 
    JOIN m_cluster l ON l.`code` = s.`cl` 
    JOIN(SELECT m_branch.`name`, m_cluster.description , nno,g_t_internal_transfer_sum.cl,g_t_internal_transfer_sum.bc FROM g_t_internal_transfer_sum
    JOIN m_branch ON m_branch.`bc` = g_t_internal_transfer_sum.`to_bc`
    JOIN m_cluster ON m_cluster.`code` = g_t_internal_transfer_sum.to_cl

    ) c ON c.nno = s.nno AND c.cl = s.cl AND c.bc = s.bc
    WHERE s.sub_no ='".$_POST['qno']."' AND  s.`trans_code` ='62'
    AND s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' ";

    $rr=$this->db->query($sqll);
    $r_detail['sum'] = $rr->result();
    $nno = $rr->row()->nno;

    $sql="SELECT 
    TD.`item_code` AS code,
    GV.description,
    TD.item_cost,
    GV.price,
    TD.`qty`,
    TD.item_cost * TD.`qty` AS amount 
    FROM
    `g_t_internal_transfer_det` TD
    JOIN g_m_gift_voucher GV ON GV.code=TD.`item_code`
    WHERE TD.`cl` = '".$this->sd['cl']."' 
    AND TD.`bc` = '".$this->sd['branch']."'
    AND TD.`sub_no` = '".$_POST['qno']."'  
    AND TD.`type` = '".$_POST['p_type']."'
    AND TD.trans_code='62' 
    GROUP BY TD.`item_code`
    ORDER BY TD.`auto_no` ASC ";

    $query = $this->db->query($sql);
    $r_detail['items'] = $this->db->query($sql)->result();

    $this->db->SELECT(array('serial_no','item'));
    $this->db->FROM('g_t_serial_movement');
    $this->db->WHERE('g_t_serial_movement.cl', $this->sd['cl']);
    $this->db->WHERE('g_t_serial_movement.bc', $this->sd['branch']);
    $this->db->WHERE('g_t_serial_movement.trans_type','62');
    $this->db->WHERE('g_t_serial_movement.trans_no',$nno); //$_POST['qno']
    $this->db->GROUP_BY('g_t_serial_movement.serial_no,g_t_serial_movement.item');
    $r_detail['serial'] = $this->db->get()->result();

    $s="SELECT SUM(item_cost)*qty AS amount
    FROM g_t_internal_transfer_det
    WHERE nno='".$_POST['qno']."' 
    AND cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."'";
    $q= $this->db->query($s);    
    $r_detail['amount'] = $q->result();

    $this->db->select(array('loginName'));
    $this->db->where('cCode', $this->sd['oc']);
    $r_detail['user'] = $this->db->get('users')->result();

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
    $this->db->delete("g_t_damage_det");

    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("trans_code", 14);
    $this->db->where("trans_no", $_POST['hid']);
    $this->db->delete("g_t_item_movement");
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

    /*$sql="SELECT `serial_no` ,code  FROM `m_item` WHERE `code` = '$code' LIMIT 1";
    $query = $this->db->query($sql);
     foreach($query->result() as $row){
        return $row->serial_no;
      }*/
      return "1";
    }

    public function is_serial_entered($trans_no, $item, $serial) {
      $this->db->select(array(
        'available'
      ));
      $this->db->where("serial_no", $serial);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where("item", $item);
      $query = $this->db->get("g_t_serial");
      if ($query->num_rows() > 0) {
        return 1;
      } else {
        return 0;
      }
    }




    public function checkdelete(){
      $id = $_POST['no'];

      $sql="SELECT *
      FROM `g_t_internal_transfer_sum` 
      WHERE `g_t_internal_transfer_sum`.`nno` = '$id'
      AND cl='".$this->sd['cl']."' 
      AND bc='".$this->sd['branch']."' 
      AND trans_code='62'
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

      $check_cancellation = $this->check_transfer_issue_received($_POST['id'],$_POST['to_cluster'],$_POST['to_bc']);
      if ($check_cancellation != 1) {
        return $check_cancellation;
      }
      return $status;
    }

    public function check_transfer_issue_received($id, $to_cl, $to_bc){
      $status = 1;
      $sql="SELECT * 
      FROM g_t_internal_transfer_sum 
      WHERE cl='".$to_cl."' 
      AND bc='".$to_bc."' 
      AND ref_trans_code='62' 
      AND ref_trans_no='".$id."' 
      AND is_cancel='0'";
      $query = $this->db->query($sql);

      if($query->num_rows() > 0){      
        $status = "Deleteing failed, Transfer issue number already received";
      }else{
        $status = 1;
      }
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


        if($this->user_permissions->is_delete('g_internel_transfer')){
          $delete_validation_status=$this->delete_validation();
          if($delete_validation_status==1){

            $this->db->where('cl',$cl);
            $this->db->where('bc',$bc);
            $this->db->where('trans_code',62);
            $this->db->where('trans_no',$trans_no);
            $this->db->delete('g_t_item_movement');

            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_code','62');
            $this->db->where('trans_no',$trans_no);
            $this->db->delete('t_account_trans');


            $g_t_serial = array(
             "out_doc" => "",
             "out_no" => "",
             "out_date" => $_POST['ddate'],
             "available" => '1'
           );

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("out_no", $trans_no);
            $this->db->where("out_doc", 62);
            $this->db->update("g_t_serial", $g_t_serial);

            $this->db->select(array('item', 'serial_no'));
            $this->db->where("trans_no", $trans_no);
            $this->db->where("trans_type", 62);
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
           $this->db->where('nno',$trans_no);
           $this->db->update('g_t_internal_transfer_sum',$data);

           $status=array("status" => 1);
           $this->db->where("nno", $_POST['order_no']);
           $this->db->update("g_t_internal_transfer_order_sum", $status);   

           $this->utility->save_logger("CANCEL",62,$_POST['trans_no'],$this->mod);
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


  public function f1_selection_list_vehicle(){


    $table         = $_POST['data_tbl'];
    $field         = isset($_POST['field'])?$_POST['field']:'code';
    $field2        = isset($_POST['field2'])?$_POST['field2']:'description';
    $field3        = $_POST['field3'];
    $hid_field     = isset($_POST['hid_field'])?$_POST['hid_field']:0;
    $add_query     = isset($_POST['add_query'])?$_POST['add_query']:"";
    $preview_name1 = isset($_POST['preview1'])?$_POST['preview1']:'Code';
    $preview_name2 = isset($_POST['preview2'])?$_POST['preview2']:'Description';

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

    $sql = "SELECT * FROM $table  WHERE $field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' LIMIT 25";

    $query = $this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th' style='width:200px;'>".$preview_name1."</th>";
    $a .= "<th class='tb_head_th' colspan='3'>".$preview_name2."</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->{$field}."</td>";
      $a .= "<td>".$r->{$field2}."</td>";
      $a .= "<td style='display:none;'>".$r->$field3."</td>";

      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }


  public function f1_load_orders(){

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

    $sql = "SELECT * 
    FROM g_t_internal_transfer_order_sum
    WHERE bc='".$_POST['to_bc']."'
    AND to_bc='".$this->sd['branch']."'
    AND TYPE='".$_POST['type']."'
    AND STATUS='1'
    group by sub_no,to_bc";

    $query = $this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th' style='width:200px;'>Order Number</th>";
    $a .= "<th class='tb_head_th' colspan='3'>Date</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->sub_no."</td>";
      $a .= "<td>".$r->ddate."</td>";
      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }


  public function account_update($condition){

    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 62);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");


    if($_POST['hid']=="0"||$_POST['hid']==""){

    }else{
      if($condition=="1"){
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code',62);
        $this->db->where('trans_no',$this->max_no);
        $this->db->delete('t_account_trans');
      }
    }

    $config = array(
      "ddate" => $_POST['date'],
      "trans_code" => 62,
      "trans_no" => $this->max_no,
      "op_acc" => 0,
      "reconcile" => 0,
      "cheque_no" => 0,
      "narration" => "",
      "ref_no" => $_POST['ref_no']
    );

    $des = "GIFT VOUCHER TRANSFER - " . $this->max_no;
    $this->load->model('account');
    $this->account->set_data($config);

    $gift_in_transit_acc   = $this->utility->get_default_acc('GIFT_IN_TRANSIT');
    $gift_stock_acc        = $this->utility->get_default_acc('GIFT_STOCK_ACC');  

    $this->account->set_value2($des, $_POST['net_amount'], "dr", $gift_in_transit_acc,$condition);
    $this->account->set_value2($des, $_POST['net_amount'], "cr", $gift_stock_acc,$condition);

    if($condition==0){
      $query = $this->db->query("
        SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
        FROM `t_check_double_entry` t
        LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
        WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='62'  AND t.`trans_no` ='" . $this->max_no . "' AND 
        a.`is_control_acc`='0'");

      if($query->row()->ok == "0"){
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 62);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      }else{
        return "1";
      }
    }
  }  







}
