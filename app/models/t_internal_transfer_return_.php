<?php
// 1=pending,2=issued,3=rejected t_internal_transfer_sum table, status field
if (!defined('BASEPATH'))exit('No direct script access allowed');
class t_internal_transfer_return extends CI_Model {
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
    
    
  }

  public function base_details(){
    $a['id'] = $this->utility->get_max_no("t_internal_transfer_return_sum", "nno");
    $a['sub_max_no']= $this->get_max_no_in_type_transfer("t_internal_transfer_return_sum","sub_no","request");
    $a['branch'] = $this->branch();
    $a['cluster'] = $this->cluster();
    $a['store'] = $this->store();
    $a['location_store'] = $this->location_store();    
    $a['from_branch'] = $this->sd['branch'];
    $a['from_branch_name'] = $this->from_branch_name();
    $a['cl'] = $this->sd['cl'];
    $a['bc'] = $this->sd['branch'];
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


  public function load_all_return(){
    $sql="SELECT s.sub_no as nno,ddate 
          FROM t_internal_transfer_sum s
          JOIN t_internal_transfer_det d ON s.`cl` = d.`cl` AND s.`bc` = d.`bc` AND s.`nno` = d.`nno`
          WHERE  s.cl='".$this->sd['cl']."' 
          AND s.bc='".$this->sd['branch']."'
          AND s.type='".$_POST['type']."'
          AND s.`trans_code` ='42'
          AND s.return_no='0'
          AND (s.nno LIKE '%$_POST[search]%')
          GROUP BY s.cl, s.bc, s.nno";

    $query=$this->db->query($sql);

    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>No</th>";
    $a .= "<th class='tb_head_th'>Date</th>";
    $a .= "</thead></tr>";
    $a .= "<tr class='cl'>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
    $a .= "</tr>";
    foreach ($query->result() as $r) {
      $a .= "<tr class='cl'>";
      $a .= "<td>" . $r->nno . "</td>";
      $a .= "<td>" . $r->ddate . "</td>";
      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }

  public function load_return_details(){
    $sql="SELECT m.`code`, 
                  m.`description`, 
                  m.`model`, 
                  d.`batch_no`, 
                  d.`item_cost` AS cost, 
                  d.`min_price`, 
                  d.`max_price`, 
                  d.`qty`, 
                  0 AS balance, 
                  (m.`purchase_price` * d.qty) AS amount  
          FROM t_internal_transfer_sum s 
          JOIN t_internal_transfer_det d ON s.`cl` = d.`cl` AND s.`bc` = d.`bc` AND s.`nno` = d.`nno` 
          JOIN m_item m ON m.`code` = d.`item_code` 
          WHERE s.cl = '".$this->sd['cl']."' 
                AND s.bc = '".$this->sd['branch']."' 
                AND s.type = '".$_POST['type']."' 
                AND s.`trans_code` = '42' 
                AND s.`sub_no` = '".$_POST['no']."' ";

    $query = $this->db->query($sql);

    if($query->num_rows()>0){
      $a = $query->result();
    }else{
      $a = 2;
    }
    echo json_encode($a);
  }

  public function load_balance(){
    $sql="SELECT d.bc,s.`sub_no`,d.nno, s.`type`, r.bc, r.sub_no,  d.`item_code`, r.item_code AS receive_item, d.`qty` AS issue_qty, r.qty AS receive_qty, IFNULL(d.qty,0)-IFNULL(r.qty,0) AS balance  
          FROM `t_internal_transfer_sum` s
          JOIN `t_internal_transfer_det` d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
          LEFT JOIN (SELECT s.sub_no,s.cl,s.bc,s.`nno`,d.`item_code`,d.`qty`,s.`ref_trans_code`,s.`ref_trans_no`,s.`issue_bc`,s.`trans_code` FROM t_internal_transfer_sum s 
            JOIN t_internal_transfer_det d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
            WHERE s.trans_code='43' GROUP BY d.cl,d.bc,d.nno,d.`item_code`
          ) r ON s.`to_cl`=r.cl AND s.`to_bc`=r.bc AND s.nno=r.ref_trans_no AND r.trans_code='43' AND d.`item_code`=r.item_code
          WHERE s.bc='".$this->sd['branch']."' AND s.`sub_no`='".$_POST['no']."'
          AND s.`type`='".$_POST['type']."'
          GROUP BY d.cl,d.bc,d.nno,d.`item_code`";

    $query = $this->db->query($sql);

    if($query->num_rows()>0){
      $a = $query->result();
    }else{
      $a = 2;
    }
    echo json_encode($a);
  }

  public function validation() {
    $status  = 1;
    $this->sub_max_no=$this->get_max_no_in_type_echo2_transfer("t_internal_transfer_return_sum","sub_no",$_POST['types']);
    $this->max_no = $this->utility->get_max_no("t_internal_transfer_return_sum", "nno");

    $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_internal_transfer_return_sum');
    if ($check_is_delete != 1) {
      return "Internal transfer already deleted";
    }
    $chk_item_store_validation = $this->check_item_with_store($_POST['v_store'], '0_');
    if ($chk_item_store_validation != 1) {
      return $chk_item_store_validation;
    }
    $serial_validation_status = $this->validation->serial_update('0_', '2_','all_serial_');
    if ($serial_validation_status != 1) {
      return $serial_validation_status;
    }
    $check_zero_value=$this->validation->empty_net_value($_POST['total']);
      if($check_zero_value!=1){
        return $check_zero_value;
    }
   /* $chk_zero_qty=$this->validation->empty_qty('0_','2_');
    if($chk_zero_qty!=1){
        return $chk_zero_qty;
    }*/
    /*$account_update=$this->account_update(0);
    if($account_update!=1){
        return "Invalid account entries";
    }  */
    return $status;
  }

  public function save(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
      $validation_status=$this->validation();
      if($validation_status==1){
          $execute=0;
          $subs="";
          

        for($x = 0; $x<25; $x++){
          if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['1_'.$x],$_POST['3_'.$x])){
            if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['1_'.$x] != "" && $_POST['3_'.$x] != ""){
             $b[]= array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],
                  "nno"=>$this->max_no,
                  "sub_no"=>$this->sub_max_no,
                  "type"=>$_POST['types'],
                  "item_code"=>$_POST['0_'.$x],
                  "batch_no"=>$_POST['1_'.$x],
                  "item_cost" => $this->utility->get_cost_price($_POST['0_' . $x]),
                  "issued_qty" =>$_POST['issue_'.$x],
                  "accept_qty" =>$_POST['2_'.$x],
                  "balance_qty" =>$_POST['bal_'.$x]
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
          "from_store" => $_POST['v_store'],
          "to_store" => $_POST['this_store'],
          "issue_no" => $_POST['issue_no'],
          "note" => $_POST['note'],
          "vehicle" => $_POST['vehicle'],
          "oc" => $this->sd['oc']
        );

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_internal_transfer_return')){  
            if($_POST['df_is_serial']=='1'){
              $this->serial_save_out();   
              $this->serial_save_in();    
            }
            $this->save_sub_items();
            //$this->account_update(1);

            $this->db->insert('t_internal_transfer_return_sum',$data);
            if(count($b)){$this->db->insert_batch("t_internal_transfer_return_det",$b);}
            
            $this->load->model('trans_settlement');
            for($x = 0; $x<25; $x++){
              if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['1_'.$x],$_POST['3_'.$x])){
                if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['1_'.$x] != "" && $_POST['3_'.$x] != ""){
                 
                  $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '80',
                  $this->max_no,
                  $_POST['ddate'],
                  $_POST['2_'.$x],
                  0,
                  $_POST['this_store'],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['1_'.$x],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $this->utility->get_min_price($_POST['0_' . $x]),
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  '001');

                  $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '80',
                  $this->max_no,
                  $_POST['ddate'],
                  0,
                  $_POST['2_'.$x],
                  $_POST['v_store'],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['1_'.$x],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $this->utility->get_min_price($_POST['0_' . $x]),
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  '001');
                }
              }     
            }

            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("type", $_POST['types']);
            $this->db->where("sub_no", $_POST['issue_no']);
            $this->db->update("t_internal_transfer_sum", array("return_no"=>$_POST['sub_no'])); 


            $sql="SELECT * FROM t_internal_transfer_sum
                  WHERE cl='".$this->sd['cl']."' 
                  AND bc='".$this->sd['branch']."' 
                  AND type='".$_POST['types']."' 
                  AND nno='".$_POST['issue_no']."' 
                  AND trans_code='42'";
            $query=$this->db->query($sql);
            if($query->num_rows()>0){
              $t_cl=$query->first_row()->to_cl;
              $t_bc=$query->first_row()->to_bc;
              $nno=$query->first_row()->nno;
            }else{
              $t_cl=$this->sd['cl'];
              $t_bc=$this->sd['branch'];
              $nno=$_POST['issue_no'];
            }  

            $sql="SELECT acc_code FROM r_branch_current_acc 
                  WHERE ref_cl='$t_cl'
                  AND ref_bc='$t_bc'";
            $br_acc_code = $this->db->query($sql)->first_row()->acc_code;

            $this->trans_settlement->save_iternal_transfer_trans("t_internal_transfer_trans", 
              $this->sd['cl'],
              $this->sd['branch'],
              $t_cl,
              $t_bc,
              $br_acc_code, 
              $_POST['ddate'], 
              42, 
              $nno, 
              80, 
              $this->max_no, 
              "0", 
              $_POST['total'],
              "0");   



            $this->utility->save_logger("SAVE",80,$this->max_no,$this->mod,$this->sub_max_no);
            echo $this->db->trans_commit()."@".$this->max_no;
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }   
        }else{
          if($this->user_permissions->is_edit('t_internal_transfer_return')){
              if($_POST['df_is_serial']=='1'){
                $this->serial_save_out();   
                $this->serial_save_in(); 
              }
              $this->save_sub_items();
              //$this->account_update(1);
              /*$data_update=array(
                  "cl" => $this->sd['cl'],
                  "bc" => $this->sd['branch'],
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

              $this->db->where("trans_code", 80);
              $this->db->where("trans_no", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_item_movement");*/

              $this->load->model('trans_settlement');
              $this->trans_settlement->delete_item_movement('t_item_movement',80,$_POST['hid']);

              $this->db->where("nno", $_POST['sub_hid']);
              $this->db->where("type",$_POST['types']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_internal_transfer_return_det");

              $this->db->where("nno", $_POST['sub_hid']);
              $this->db->where("type",$_POST['types']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_internal_transfer_return_sum", $data);

              if(count($b)){$this->db->insert_batch("t_internal_transfer_return_det",$b);}
              $this->load->model('trans_settlement');
              for($x = 0; $x<25; $x++){
                if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['1_'.$x],$_POST['3_'.$x])){
                  if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['1_'.$x] != "" && $_POST['3_'.$x] != ""){
                 
                  $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '80',
                  $this->max_no,
                  $_POST['ddate'],
                  $_POST['2_'.$x],
                  0,
                  $_POST['this_store'],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['1_'.$x],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $this->utility->get_min_price($_POST['0_' . $x]),
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  '001');

                  $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '80',
                  $this->max_no,
                  $_POST['ddate'],
                  0,
                  $_POST['2_'.$x],
                  $_POST['v_store'],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['1_'.$x],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $this->utility->get_min_price($_POST['0_' . $x]),
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  '001');
                }
              }     
            }
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->where("type", $_POST['types']);
              $this->db->where("sub_no", $_POST['issue_no']);
              $this->db->update("t_internal_transfer_sum", array("return_no"=>$_POST['sub_no']));  

              $this->trans_settlement->delete_internal_trans_sub_settlement('t_internal_transfer_trans',80,$this->max_no);  

              $sql="SELECT * FROM t_internal_transfer_sum
                  WHERE cl='".$this->sd['cl']."' 
                  AND bc='".$this->sd['branch']."' 
                  AND type='".$_POST['types']."' 
                  AND nno='".$_POST['issue_no']."' 
                  AND trans_code='42'";

              $query=$this->db->query($sql);
              if($query->num_rows()>0){
                $t_cl=$query->first_row()->to_cl;
                $t_bc=$query->first_row()->to_bc;
                $nno=$query->first_row()->nno;
              }else{
                $t_cl=$this->sd['cl'];
                $t_bc=$this->sd['branch'];
                $nno=$_POST['issue_no'];
              }  

              $sql="SELECT acc_code FROM r_branch_current_acc 
                    WHERE ref_cl='$t_cl'
                    AND ref_bc='$t_bc'";
              $br_acc_code = $this->db->query($sql)->first_row()->acc_code;

              $this->trans_settlement->save_iternal_transfer_trans("t_internal_transfer_trans", 
                $this->sd['cl'],
                $this->sd['branch'],
                $t_cl,
                $t_bc,
                $br_acc_code, 
                $_POST['ddate'], 
                42, 
                $nno, 
                80, 
                $this->max_no, 
                "0", 
                $_POST['total'],
                "0");   

              $this->utility->save_logger("EDIT",80,$this->max_no,$this->mod,$this->sub_max_no);
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
  
   public function account_update($condition) {

        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 42);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");

        if ($_POST['hid'] != "0" || $_POST['hid'] != "") {
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_code',42);
            $this->db->where('trans_no',$this->max_no);
            $this->db->delete('t_account_trans');
        }

        $config = array(
            "ddate" => $_POST['date'],
            "trans_code" => 42,
            "trans_no" => $this->max_no,
            "op_acc" => 0,
            "reconcile" => 0,
            "cheque_no" => 0,
            "narration" => "",
            "ref_no" => $_POST['ref_no']
        );

        $des = "Internal Sale - " . $this->max_no;
        $this->load->model('account');
        $this->account->set_data($config);

        $internal_cost_acc     = $this->utility->get_default_acc('COST_INTERNAL_SALE');
        $stock_acc             = $this->utility->get_default_acc('STOCK_ACC');
        $good_in_transit_acc   = $this->utility->get_default_acc('GOOD_TRANSIT');
        $revenue_int_sales_acc = $this->utility->get_default_acc('REV_INTERNAL_SALE');

        $item_cost=0;
        for($x=0;$x<25;$x++){
            if(isset($_POST['0_' . $x])){
              $item_cost+=($_POST['2_'.$x])*(double)$this->utility->get_cost_price($_POST['0_'.$x]);
            }
        }    
        $this->account->set_value2($des, $item_cost, "dr", $internal_cost_acc,$condition);
        $this->account->set_value2($des, $item_cost, "cr", $stock_acc,$condition);
        $this->account->set_value2($des, $item_cost, "dr", $good_in_transit_acc,$condition);
        $this->account->set_value2($des, $item_cost, "cr", $revenue_int_sales_acc,$condition);

        if($condition==0){
             $query = $this->db->query("
                 SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
                 FROM `t_check_double_entry` t
                 LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
                 WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='42'  AND t.`trans_no` ='" . $this->max_no . "' AND 
                 a.`is_control_acc`='0'");

            if ($query->row()->ok == "0") {
                $this->db->where("trans_no", $this->max_no);
                $this->db->where("trans_code", 42);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_check_double_entry");
                return "0";
            } else {
                return "1";
            }
        }
    }  

    public function serial_save_out() {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
            if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
            if ($this->check_is_serial_items($_POST['0_'.$x]) == 1 && $_POST['2_' . $x] != "0") {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){
                  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                      $t_seriall = array(
                          "engine_no"   => "",
                          "chassis_no"  => '',
                          "out_doc"     => 80,
                          "out_no"      => $this->max_no,
                          "out_date"    => $_POST['ddate'],
                          "available"   => '0'
                      );

                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->where('serial_no', $p[$i]);
                      $this->db->where("item", $_POST['0_' . $x]);
                      $this->db->update("t_serial", $t_seriall);

                      $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$_POST['0_' . $x]."' AND serial_no='".$p[$i]."' ");

                      $t_serial_movement_out[] = array(
                          "cl" => $this->sd['cl'],
                          "bc" => $this->sd['branch'],
                          "trans_type" => 80,
                          "trans_no" => $this->max_no,
                          "item" => $_POST['0_' . $x],
                          "batch_no" => $this->get_batch_serial_wise($_POST['0_' . $x], $p[$i]),
                          "serial_no" => $p[$i],
                          "qty_in" => 0,
                          "qty_out" => 1,
                          "cost" => $_POST['c_'.$x],
                          "store_code" => $_POST['v_store'],
                          "computer" => $this->input->ip_address(),
                          "oc" => $this->sd['oc'],
                      );

                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->where("item", $_POST['0_' . $x]);
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
             "store_code"  => $_POST['v_store'],
             "engine_no" => "",
             "chassis_no" => '',
             "out_doc" => "",
             "out_no" => "",
             "out_date" => "",
             "available" => '1'
           );

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 80);
           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc", $this->sd['branch']);
           $query1 = $this->db->get("t_serial_movement");

          foreach ($query1->result() as $row) {
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("item", $row->item);
            $this->db->where("serial_no", $row->serial_no);
            $this->db->update("t_serial", $t_serial);
          }

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 80);
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
           $this->db->where("trans_type", 80);
           $this->db->delete("t_serial_movement");

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc",$this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 80);
           $this->db->delete("t_serial_movement_out");


           for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
                if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
               if ($this->check_is_serial_items($_POST['0_' . $x]) == 1 && $_POST['2_' . $x] != "0") {
                    $serial = $_POST['all_serial_'.$x];
                    $p=explode(",",$serial);
                    for($i=0; $i<count($p); $i++){

                           $t_seriall = array(
                               "engine_no" => "",
                               "chassis_no" => '',
                               "out_doc" => 80,
                               "out_no" => $this->max_no,
                               "out_date" => $_POST['ddate'],
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
                               "trans_type" => 80,
                               "trans_no" => $this->max_no,
                               "item" => $_POST['0_'.$x],
                               "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
                               "serial_no" => $p[$i],
                               "qty_in" => 0,
                               "qty_out" => 1,
                               "cost" => $_POST['c_' . $x],
                               "store_code" => $_POST['v_store'],
                               "computer" => $this->input->ip_address(),
                               "oc" => $this->sd['oc'],
                           );

                           $this->db->where("cl", $this->sd['cl']);
                           $this->db->where("bc", $this->sd['branch']);
                           $this->db->where("item", $_POST['0_' . $x]);
                           $this->db->where("serial_no", $p[$i]);
                           $this->db->delete("t_serial_movement");
                        }
                       // $execute = 1;
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


    public function serial_save_in() {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
            if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
            if ($this->check_is_serial_items($_POST['0_'.$x]) == 1 && $_POST['2_' . $x] != "0") {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){
                  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                      $t_seriall = array(
                          "store_code"  => $_POST['this_store'],
                          "engine_no"   => "",
                          "chassis_no"  => '',
                          "out_doc"     => "",
                          "out_no"      => "",
                          "out_date"    => "",
                          "available"   => '1'
                      );

                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->where('serial_no', $p[$i]);
                      $this->db->where("item", $_POST['0_' . $x]);
                      $this->db->update("t_serial", $t_seriall);

                      $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_' . $x]."' AND serial_no='".$p[$i]."' ");

                      $t_serial_movement[] = array(
                          "cl" => $this->sd['cl'],
                          "bc" => $this->sd['branch'],
                          "trans_type" => 80,
                          "trans_no" => $this->max_no,
                          "item" => $_POST['0_' . $x],
                          "batch_no" => $this->get_batch_serial_wise($_POST['0_' . $x], $p[$i]),
                          "serial_no" => $p[$i],
                          "qty_in" => 1,
                          "qty_out" => 0,
                          "cost" => $_POST['c_' . $x],
                          "store_code" => $_POST['this_store'],
                          "computer" => $this->input->ip_address(),
                          "oc" => $this->sd['oc'],
                      );

                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->where("item", $_POST['0_' . $x]);
                      $this->db->where("serial_no", $p[$i]);
                      $this->db->delete("t_serial_movement_out");
                  }

                }
                }
              }
            }
          }

        }else{
           $t_serial = array(
             "store_code"  => $_POST['v_store'],
             "engine_no" => "",
             "chassis_no" => '',
             "out_doc" => "",
             "out_no" => "",
             "out_date" => "",
             "available" => '1'
           );

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 80);
           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc", $this->sd['branch']);
           $query = $this->db->get("t_serial_movement");

           foreach ($query->result() as $row) {
             $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$row->item' AND serial_no='$row->serial_no'");
             $this->db->where("item", $row->item);
             $this->db->where("serial_no", $row->serial_no);
             $this->db->where("cl", $this->sd['cl']);
             $this->db->where("bc", $this->sd['branch']);
             $this->db->delete("t_serial_movement");

           }

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc",$this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 80);
           $this->db->delete("t_serial_movement");

           for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
                if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
               if ($this->check_is_serial_items($_POST['0_' . $x]) == 1 && $_POST['2_' . $x] != "0") {
                    $serial = $_POST['all_serial_'.$x];
                    $p=explode(",",$serial);
                    for($i=0; $i<count($p); $i++){

                           $t_seriall = array(
                                "store_code"  => $_POST['this_store'],
                                "engine_no"   => "",
                                "chassis_no"  => '',
                                "out_doc"     => "",
                                "out_no"      => "",
                                "out_date"    => "",
                                "available"   => '1'
                           );

                             $this->db->where("cl", $this->sd['cl']);
                             $this->db->where("bc", $this->sd['branch']);
                             $this->db->where('serial_no', $p[$i]);
                             $this->db->where("item", $_POST['0_'.$x]);
                             $this->db->update("t_serial", $t_seriall);

                           $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                           $t_serial_movement[] = array(
                               "cl" => $this->sd['cl'],
                               "bc" => $this->sd['branch'],
                               "trans_type" => 80,
                               "trans_no" => $this->max_no,
                               "item" => $_POST['0_'.$x],
                               "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
                               "serial_no" => $p[$i],
                               "qty_in" => 1,
                               "qty_out" => 0,
                               "cost" => $_POST['c_'.$x],
                               "store_code" => $_POST['this_store'],
                               "computer" => $this->input->ip_address(),
                               "oc" => $this->sd['oc'],
                           );

                           $this->db->where("cl", $this->sd['cl']);
                           $this->db->where("bc", $this->sd['branch']);
                           $this->db->where("item", $_POST['0_' . $x]);
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


public function save_sub_items(){
    for ($x = 0; $x < 25; $x++) {
      if (isset($_POST['0_' . $x], $_POST['2_' . $x])) {
        if ($_POST['0_' . $x] != "") {

          $item_code=$_POST['0_'.$x];
          $qty=$_POST['2_'.$x];
          $batch=$_POST['1_'.$x];
          $date=$_POST['ddate'];
          $store=$_POST['v_store'];
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

              $t_sub_item_movement_out[] = array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "item" => $item_code,
                "sub_item"=>$row->sub_item,
                "trans_code" => 80,
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
                "group_sale_id" => 1,
              );

              $t_sub_item_movement_in[] = array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "item" => $item_code,
                "sub_item"=>$row->sub_item,
                "trans_code" => 80,
                "trans_no" => $max,
                "ddate" => $date,
                "qty_in" => $total_qty,
                "qty_out" => 0,
                "store_code" => $_POST['this_store'],
                "avg_price" => $this->utility->get_cost_price($item_code),
                "batch_no" => $batch,
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
        if(isset($t_sub_item_movement_out)){if(count($t_sub_item_movement_out)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_out);}}
        if(isset($t_sub_item_movement_in)){if(count($t_sub_item_movement_in)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_in);}}
    }else{
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("trans_code", 80);
        $this->db->where("trans_no", $_POST['hid']);
        $this->db->delete("t_item_movement_sub");

        if(isset($t_sub_item_movement_out)){if(count($t_sub_item_movement_out)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_out);}}
        if(isset($t_sub_item_movement_in)){if(count($t_sub_item_movement_in)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_in);}}
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
               AND qry_current_stock.`bc`='".$this->sd['branch']."'
               group by t_item_batch.`batch_no`";
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

  public function get_display() {

    $type= $_POST['type'];
    $sub_id=$_POST['sub_no'];

    $sql="SELECT  s.`nno`, 
                  s.`sub_no`, 
                  s.`type`,
                  s.`ddate`, 
                  s.`ref_no`, 
                  s.`from_store`,
                  m.`description` AS from_store_name, 
                  s.`to_store`,
                  mm.`description` AS to_store_name, 
                  s.`vehicle`, 
                  v.`description` AS vehicle_name,
                  s.`issue_no`, 
                  s.`note`,
                  s.is_cancel
          FROM t_internal_transfer_return_sum s
          JOIN m_stores m ON m.`code` = s.`from_store` AND m.`cl` = s.`cl` AND m.`bc` = s.`bc`  
          JOIN m_stores mm ON mm.`code` = s.`to_store` AND mm.`cl` = s.`cl` AND mm.`bc` = s.`bc`
          LEFT JOIN m_vehicle_setup v ON v.`code` = s.`vehicle` AND v.`cl` = s.`cl` AND s.`bc` = v.`bc`
          WHERE s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND s.sub_no='$sub_id' AND s.type='$type'";

    $query = $this->db->query($sql);
    $id = 0;
    $x  = 0;
    if ($query->num_rows() > 0) {
      $a['sum'] = $query->result();
      $id = $query->row()->nno;
    } else {
      $x = 2;
    }

    $sql2 = "SELECT d.item_code, 
                    d.`batch_no`,
                    d.`item_cost`, 
                    d.`issued_qty`, 
                    d.`accept_qty`, 
                    d.`balance_qty`,
                    m.`model`, 
                    m.`description`, 
                    m.`min_price`, 
                    m.`max_price`, 
                    (d.`item_cost` * d.accept_qty)AS amount  
              FROM t_internal_transfer_return_det d
              JOIN m_item m ON m.`code` = d.`item_code`
              WHERE d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."' AND d.sub_no='$sub_id' AND d.type='$type'
    ";
    
    $query2 = $this->db->query($sql2);
    if ($query2->num_rows() > 0) {
      $a['det'] = $query2->result();
    } else {
      $x = 2;
    }


    $sql_serial="SELECT s.item,serial_no
                 FROM t_serial_movement s
                 WHERE s.`cl` ='".$this->sd['cl']."' 
                  AND s.`bc` ='".$this->sd['branch']."' 
                  AND s.`trans_type` ='80' 
                  AND s.`trans_no` ='$id'
                 GROUP BY item,serial_no";

    $query_serial = $this->db->query($sql_serial);
    $a['serial'] = $query_serial->result();
    
  
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

        $sqll = "SELECT  s.`nno`, 
                  s.`sub_no`, 
                  s.`type`,
                  s.`ddate`, 
                  s.`ref_no`, 
                  s.`from_store`,
                  m.`description` AS from_store_name, 
                  s.`to_store`,
                  mm.`description` AS to_store_name, 
                  s.`vehicle`, 
                  v.`description` AS vehicle_name,
                  s.`issue_no`, 
                  s.`note`,
                  s.is_cancel
          FROM t_internal_transfer_return_sum s
          JOIN m_stores m ON m.`code` = s.`from_store` AND m.`cl` = s.`cl` AND m.`bc` = s.`bc`  
          JOIN m_stores mm ON mm.`code` = s.`to_store` AND mm.`cl` = s.`cl` AND mm.`bc` = s.`bc`
          JOIN m_vehicle_setup v ON v.`code` = s.`vehicle` AND v.`cl` = s.`cl` AND s.`bc` = v.`bc`
          WHERE s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND s.nno='".$_POST['qno']."'";

        $rr=$this->db->query($sqll);
        $r_detail['sum'] = $rr->result();

        $sql="SELECT 
                  `t_internal_transfer_return_det`.`item_code` AS code,
                  `t_internal_transfer_return_det`.`accept_qty` AS qty, 
                  `t_internal_transfer_return_det`.item_cost,
                  `m_item`.`description`,
                  `m_item`.`model`,
                  c.`cc` AS sub_item,
                  c.`deee` AS des,
                  c.qty * t_internal_transfer_return_det.accept_qty AS sub_qty,
                  `t_internal_transfer_return_det`.item_cost * `t_internal_transfer_return_det`.`accept_qty` AS amount 
              FROM (`t_internal_transfer_return_det`) 
              JOIN `m_item` ON `m_item`.`code` = `t_internal_transfer_return_det`.`item_code` 
              LEFT JOIN `m_item_sub` ON `m_item_sub`.`item_code` = `m_item`.`code` 
              LEFT JOIN `r_sub_item` ON `r_sub_item`.`code` = `m_item_sub`.`sub_item` 
              LEFT JOIN (
                        SELECT t_internal_transfer_return_det.`item_code` , 
                                r_sub_item.`description` AS deee, 
                                r_sub_item.`code` AS cc,
                                r_sub_item.`qty` AS qty,
                                t_item_movement_sub.cl,
                                t_item_movement_sub.bc,
                                t_item_movement_sub.item,
                                t_item_movement_sub.`sub_item` 
                        FROM t_item_movement_sub 
                        LEFT JOIN t_internal_transfer_return_det ON t_internal_transfer_return_det.`item_code` = t_item_movement_sub.`item`  
                        AND t_internal_transfer_return_det.`cl` = t_item_movement_sub.`cl` AND t_internal_transfer_return_det.`bc`=t_item_movement_sub.`bc`
                        JOIN r_sub_item ON t_item_movement_sub.`sub_item` = r_sub_item.`code`
                        WHERE t_item_movement_sub.batch_no = t_internal_transfer_return_det.`batch_no` AND `t_internal_transfer_return_det`.`cl` = '".$this->sd['cl']."'  
                        AND `t_internal_transfer_return_det`.`bc` = '".$this->sd['branch']."' AND `t_internal_transfer_return_det`.`nno` = '".$_POST['qno']."'  )c ON c.item_code = t_internal_transfer_return_det.`item_code`
            WHERE `t_internal_transfer_return_det`.`cl` = '".$this->sd['cl']."' 
            AND `t_internal_transfer_return_det`.`bc` = '".$this->sd['branch']."'
            AND `t_internal_transfer_return_det`.`nno` = '".$_POST['qno']."'  

            GROUP BY c.cc ,t_internal_transfer_return_det.`item_code`
            ORDER BY `t_internal_transfer_return_det`.`auto_no` ASC 
        ";

        $query = $this->db->query($sql);
        $r_detail['items'] = $this->db->query($sql)->result();

        $sql_serial="SELECT s.item,serial_no
             FROM t_serial_movement s
             WHERE s.`cl` ='".$this->sd['cl']."' 
              AND s.`bc` ='".$this->sd['branch']."' 
              AND s.`trans_type` ='80' 
              AND s.`trans_no` ='".$_POST['qno']."'
             GROUP BY item,serial_no";

        $query_serial = $this->db->query($sql_serial);
        $r_detail['serial'] = $query_serial->result();

        $s="SELECT SUM(item_cost)*accept_qty AS amount
            FROM t_internal_transfer_return_det
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
      return $this->db->get('t_serial')->first_row()->batch;
    }
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

      if($this->user_permissions->is_delete('t_internal_transfer_return')){
    
        $this->load->model('trans_settlement');
        $this->trans_settlement->delete_item_movement('t_item_movement',80,$trans_no);

        $this->trans_settlement->delete_internal_trans_sub_settlement('t_internal_transfer_trans',80,$trans_no);

        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code',80);
        $this->db->where('trans_no',$trans_no);
        $this->db->delete('t_account_trans');

        $this->db->where('cl',$cl);
        $this->db->where('bc',$bc);
        $this->db->where('trans_code',80);
        $this->db->where('trans_no',$trans_no);
        $this->db->delete('t_item_movement_sub');
       
        $t_serial = array(
         "store_code" => $_POST['store'],
         "engine_no"  => "",
         "chassis_no" => '',
         "out_doc"    => "",
         "out_no"     => "",
         "out_date"   => "",
         "available"  => '1'
        );

        $this->db->select(array('item', 'serial_no'));
        $this->db->where("trans_no", $trans_no);
        $this->db->where("trans_type", 80);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $query1 = $this->db->get("t_serial_movement");

        foreach ($query1->result() as $row) {
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->where("item", $row->item);
          $this->db->where("serial_no", $row->serial_no);
          $this->db->update("t_serial", $t_serial);
        }

        $this->db->where("trans_no", $trans_no);
        $this->db->where("trans_type", 80);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_serial_movement");

        $data=array('is_cancel'=>'1');
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('nno',$trans_no);
        $this->db->update('t_internal_transfer_return_sum',$data);

        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("type", $_POST['types']);
        $this->db->where("sub_no", $_POST['issue_no']);
        $this->db->update("t_internal_transfer_sum", array("return_no"=>0));  

        $this->utility->save_logger("CANCEL",80,$trans_no,$this->mod);
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
              FROM t_internal_transfer_order_sum
              WHERE bc='".$_POST['to_bc']."'
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


  public function get_max_no_in_type_transfer($table_name,$field_name,$type){
    $this->db->select_max($field_name);
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("type",$type);   
    return $this->db->get($table_name)->first_row()->$field_name+1;
  }

  public function get_max_no_in_type_transfer_echo(){      
      $table_name =$_POST['table'];
      $field_name =$_POST['sub_no'];
      $type = $_POST['type'];

        if(isset($_POST['sub_hid'])){
          if($_POST['sub_hid'] == "0" || $_POST['sub_hid'] == ""){      
            $this->db->select_max($field_name);
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']); 
            $this->db->where("type",$type);   
            echo $this->db->get($table_name)->first_row()->$field_name+1;
          }else{
            echo $_POST['sub_hid'];  
          }
        }else{
            $this->db->select_max($field_name);
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("type",$type);     
            echo $this->db->get($table_name)->first_row()->$field_name+1;
        }
    }

     public function get_max_no_in_type_echo2_transfer($table_name,$field_name,$type){
      
        if(isset($_POST['sub_hid'])){
          if($_POST['sub_hid'] == "0" || $_POST['sub_hid'] == ""){      
            $this->db->select_max($field_name);
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']); 
            $this->db->where("type",$type);   
            return $this->db->get($table_name)->first_row()->$field_name+1;
          }else{
            return $_POST['sub_hid'];  
          }
        }else{
            $this->db->select_max($field_name);
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("type",$type);     
            return $this->db->get($table_name)->first_row()->$field_name+1;
        }
    }



public function check_item_with_store($store_code,$item_pre){
      $status=1;
      $cl=$this->sd['cl'];
      $bc=$this->sd['branch'];

      for($x = 0; $x<25; $x++){
        $item_code=$_POST[$item_pre.$x];
            if(isset($_POST[$item_pre.$x]) && !empty($_POST[$item_pre.$x])){
              $this->db->where('bc',$bc);
              $this->db->where('cl',$cl);
              $this->db->where('store_code',$store_code);
              $this->db->where('item',$item_code);
              $count=$this->db->get('t_item_movement')->num_rows();
                if($count==0){
                  $status="Invalid item( ".$item_code. ") with stores";
                }   
            }
        
      }
      return $status; 
    }


}
?>