<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class t_damage_sum extends CI_Model {
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
    $this->max_no = $this->utility->get_max_no("t_damage_sum", "nno");
  }



  public function base_details() {
    $a['id'] = $this->utility->get_max_no("t_damage_sum", "nno");
    $this->db->select_max('nno', 'max_no');
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $query = $this->db->get("t_damage_sum");
    foreach ($query->result() as $value) {
      $max_no = $value->max_no;
    }
    $this->db->distinct('description');
    $this->db->select(array(
      'code',
      'description'
    ));
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $query = $this->db->get($this->m_stores);
    $st    = "<select name='store_to' id='store_to'>";
    $st .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
      $st .= "<option title='" . $r->description . "' value='" . $r->code . "'>" . $r->code . "-" . $r->description . "</option>";
    }
    $st .= "</select>";

    $a['store'] = $st;
    $this->db->distinct('description');
    $this->db->select(array(
      'code',
      'description'
    ));
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $query = $this->db->get($this->m_stores);
    $st    = "<select name='store_from' id='store_from' class='store11'>";
    $st .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
      $st .= "<option title='" . $r->description . "' value='" . $r->code . "'>" . $r->code . "-" . $r->description . "</option>";
    }
    $st .= "</select>";

    $a['store_from'] = $st;
    return $a;
  }



  public function validation() {
    $status  = 1;
    
    $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_damage_sum');
    if ($check_is_delete != 1) {
      return "Damages already deleted";
    }
    $check_validation_employee = $this->validation->check_is_employer($_POST['officer']);
    if ($check_validation_employee != 1) {
      return "Please selece valid officer";
    }
    $chk_item_store_validation = $this->validation->check_item_with_store($_POST['store_from'], '0_');
    if ($chk_item_store_validation != 1) {
      return $chk_item_store_validation;
    }
    $serial_validation_status = $this->validation->serial_update('0_', '2_','all_serial_');
    if ($serial_validation_status != 1) {
      return $serial_validation_status;
    }
    $check_batch_validation = $this->utility->batch_update2('0_', '1_', '2_', $_POST['store_from']);
    if ($check_batch_validation != 1) {
      return $check_batch_validation;
    }
    $check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
      if($check_zero_value!=1){
        return $check_zero_value;
    }
    $chk_zero_qty=$this->validation->empty_qty('0_','2_');
    if($chk_zero_qty!=1){
        return $chk_zero_qty;
    }
    // $minimum_price_validation = $this->validation->check_min_price('0_', '3_');
    //   if ($minimum_price_validation != 1) {
    //       return $minimum_price_validation;
    // }
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
      $_POST['date']=$_POST['ddate'];
      $validation_status=$this->validation();
      if($validation_status==1){
          
          $execute=0;
          $subs="";

          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
              if($_POST['0_'.$x] != ""){
                if(isset($_POST['subcode_'.$x])){
                    $subs = $_POST['subcode_'.$x];
                    if($_POST['subcode_'.$x]!="0" && $_POST['subcode_'.$x]!=""){
                                    
                      $sub_items = (explode(",",$subs));
                      $arr_length= sizeof($sub_items);

                      for($y = 0; $y<$arr_length; $y++){
                        $item_sub = (explode("-",$sub_items[$y]));
                        $sub_qty = (int)$_POST['2_'.$x] * (int)$item_sub[1];

                        $t_sub_item_movement[] = array(
                              "cl"=>$this->sd['cl'],
                              "bc"=>$this->sd['branch'],
                              "item"=>$_POST['0_'.$x],
                              "sub_item"=>$item_sub[0],
                              "trans_code"=>14,
                              "trans_no"=>$this->max_no,
                              "ddate"=>$_POST['ddate'],
                              "qty_in"=>0,
                              "qty_out"=>$sub_qty,
                              "store_code"=>$_POST['store_from'],
                              "avg_price"=>$_POST['3_' . $x],
                              "batch_no"=>$_POST['1_'.$x],
                              "sales_price"=>$_POST['3_' . $x],
                              "last_sales_price"=>$this->utility->get_min_price($_POST['0_' . $x]),
                              "cost"=>$_POST['3_' . $x],
                        );

                        $t_sub_item_movement2[] = array(
                              "cl"=>$this->sd['cl'],
                              "bc"=>$this->sd['branch'],
                              "item"=>$_POST['0_'.$x],
                              "sub_item"=>$item_sub[0],
                              "trans_code"=>14,
                              "trans_no"=>$this->max_no,
                              "ddate"=>$_POST['ddate'],
                              "qty_in"=>$sub_qty,
                              "qty_out"=>0,
                              "store_code"=>$_POST['store_to'],
                              "avg_price"=>$_POST['3_' . $x],
                              "batch_no"=>$_POST['1_'.$x],
                              "sales_price"=>$_POST['3_' . $x],
                              "last_sales_price"=>$this->utility->get_min_price($_POST['0_' . $x]),
                              "cost"=>$_POST['3_' . $x],
                        );
                      }
                    }
                  }
              }
          }     
        }

        for($x = 0; $x<25; $x++){
          if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
            if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
              $b[]= array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "nno"=>$this->max_no,
                "code"=>$_POST['0_'.$x],
                "qty"=>$_POST['2_'.$x],
                "cost" => $_POST['3_' . $x],
                "amount"=>$_POST['t_'.$x],
                "batch_no"=>$_POST['1_'.$x]
              );              
            }
          }
        }                    

        $data=array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->max_no,
          "ref_no" => $_POST['ref_no'],
          "memo" => $_POST['ref_des'],
          "store_from" => $_POST['store_from'],
          "store_to" => $_POST['store_to'],
          "amount" => $_POST['net_amount'],
          "officer" => $_POST['officer'],
          "ddate" => $_POST['ddate'],
          "dr_acc" => $_POST['dr_acc'],
          "cr_acc" => $_POST['cr_acc'],
          "oc" => $this->sd['oc']
        );

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_damage_sum')){ 
            if($_POST['df_is_serial']=='1'){
              $this->serial_save();    
            } 
            $this->db->insert($this->t_damage_sum,$data);
            if(count($b)){$this->db->insert_batch("t_damage_det",$b);}

            $this->load->model('trans_settlement');
            for($x = 0; $x<25; $x++){
              if(isset($_POST['0_'.$x])){
                if($_POST['0_'.$x] != ""){

                  $this->trans_settlement->save_item_movement('t_item_movement',
                    $_POST['0_'.$x],
                    '14',
                    $this->max_no,
                    $_POST['ddate'],
                    0,
                    $_POST['2_'.$x],
                    $_POST['store_from'],
                    $_POST['3_'.$x],
                    $_POST['1_'.$x],
                    $_POST['3_'.$x],
                    $this->utility->get_min_price($_POST['0_' . $x]),
                    $_POST['3_'.$x],
                    '001');

                  $this->trans_settlement->save_item_movement('t_item_movement',
                    $_POST['0_'.$x],
                    '14',
                    $this->max_no,
                    $_POST['ddate'],
                    $_POST['2_'.$x],
                    0,
                    $_POST['store_to'],
                    $_POST['3_'.$x],
                    $_POST['1_'.$x],
                    $_POST['3_'.$x],
                    $this->utility->get_min_price($_POST['0_' . $x]),
                    $_POST['3_'.$x],
                    '001');

                }
              }
            }

            if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
            if(isset($t_sub_item_movement2)){if(count($t_sub_item_movement2)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement2);}}
            $this->utility->save_logger("SAVE",14,$this->max_no,$this->mod);
            echo $this->db->trans_commit()."@".$this->max_no;
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }   
        }else{
          if($this->user_permissions->is_edit('t_damage_sum')){
            $check_update = $this->trans_cancellation->damage_update_status($this->max_no,14,'t_damage_sum','t_damage_det');
            if ($check_update == 1) {
              if($_POST['df_is_serial']=='1'){
                $this->serial_save();    
              }

              $data_update=array(
                  "ref_no" => $_POST['ref_no'],
                  "memo" => $_POST['ref_des'],
                  "store_from" => $_POST['store_from'],
                  "store_to" => $_POST['store_to'],
                  "amount" => $_POST['net_amount'],
                  "officer" => $_POST['officer'],
                  "ddate" => $_POST['ddate'],
                  "dr_acc" => $_POST['dr_acc'],
                  "cr_acc" => $_POST['cr_acc'],
                  "oc" => $this->sd['oc']
              );

              $this->load->model('trans_settlement');
              $this->trans_settlement->delete_item_movement('t_item_movement',14,$_POST['hid']);

              $this->db->where("trans_code", 14);
              $this->db->where("trans_no", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_item_movement_sub");

              //$this->db->where("type", 13);
              $this->db->where("nno", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_damage_det");

              //$this->db->where("type", 13);
              $this->db->where("nno", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_damage_sum", $data_update);

              if(count($b)){$this->db->insert_batch("t_damage_det",$b);}
              
              $this->load->model('trans_settlement');
              for($x = 0; $x<25; $x++){
                if(isset($_POST['0_'.$x])){
                  if($_POST['0_'.$x] != ""){

                    $this->trans_settlement->save_item_movement('t_item_movement',
                      $_POST['0_'.$x],
                      '14',
                      $this->max_no,
                      $_POST['ddate'],
                      0,
                      $_POST['2_'.$x],
                      $_POST['store_from'],
                      $_POST['3_'.$x],
                      $_POST['1_'.$x],
                      $_POST['3_'.$x],
                      $this->utility->get_min_price($_POST['0_' . $x]),
                      $_POST['3_'.$x],
                      '001');

                    $this->trans_settlement->save_item_movement('t_item_movement',
                      $_POST['0_'.$x],
                      '14',
                      $this->max_no,
                      $_POST['ddate'],
                      $_POST['2_'.$x],
                      0,
                      $_POST['store_to'],
                      $_POST['3_'.$x],
                      $_POST['1_'.$x],
                      $_POST['3_'.$x],
                      $this->utility->get_min_price($_POST['0_' . $x]),
                      $_POST['3_'.$x],
                      '001');

                  }
                }
              }

              if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
              if(isset($t_sub_item_movement2)){if(count($t_sub_item_movement2)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement2);}}
              $this->utility->save_logger("EDIT",14,$this->max_no,$this->mod);
              echo $this->db->trans_commit();   
            }else{
              echo $check_update;
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
        echo $e->getMessage().$e->getline()."Operation fail please contact admin"; 
    } 
  }
    

  public function serial_save(){

    for ($x = 0; $x < 25; $x++) {
      if (isset($_POST['0_' . $x])) {
        if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
        if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
          $serial = $_POST['all_serial_'.$x];
          $p=explode(",",$serial);
          for($i=0; $i<count($p); $i++){
              if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                  $t_serial=array(
                    "store_code"=>$_POST['store_to'],
                    "out_date"=>$_POST['ddate'],
                  );

                  $this->db->where('cl',$this->sd['cl']);
                  $this->db->where('bc',$this->sd['branch']);
                  $this->db->where('serial_no',$p[$i]);
                  $this->db->where("item", $_POST['0_'.$x]);
                  $this->db->where("store_code", $_POST['store_from']);
                  $this->db->update("t_serial", $t_serial);

                  $t_serial_movement=array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "trans_type"=>14,
                    "trans_no"=>$this->max_no,
                    "item"=>$_POST['0_'.$x],
                    "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                    "serial_no"=>$p[$i],
                    "qty_in"=>1,
                    "qty_out"=>0,
                    "cost"=>$_POST['3_' . $x],
                    "store_code"=>$_POST['store_to'],
                    "computer"=>$this->input->ip_address(),
                    "oc"=>$this->sd['oc'],
                  );

                  $this->db->insert("t_serial_movement", $t_serial_movement);

                  $t_serial_movement2=array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "trans_type"=>14,
                    "trans_no"=>$this->max_no,
                    "item"=>$_POST['0_'.$x],
                    "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                    "serial_no"=>$p[$i],
                    "qty_in"=>0,
                    "qty_out"=>1,
                    "cost"=>$_POST['3_' . $x],
                    "store_code"=>$_POST['store_from'],
                    "computer"=>$this->input->ip_address(),
                    "oc"=>$this->sd['oc'],
                  );

                  $this->db->insert("t_serial_movement", $t_serial_movement2);
                }//only if save 
               
            } //end execute
          }//check is serial item
        }
      }
    }

    if($_POST['hid'] == "0" || $_POST['hid'] == "")
    {
    }
    else
    {
      $update_serial_movement = array(
                  "qty_in" => 1,
                  "qty_out" => 0
      );

      $this->db->select(array('item', 'serial_no'));
      $this->db->where("trans_no", $_POST['hid']);
      $this->db->where("trans_type", '14');
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $query = $this->db->get("t_serial_movement");

      foreach ($query->result() as $row) {
      $this->db->query("UPDATE t_serial SET store_code='$_POST[store_from]' WHERE item='$row->item' AND serial_no='$row->serial_no' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'");
      }    
     
      $this->db->where('trans_type', 14);
      $this->db->where("trans_no", $_POST['hid']);
      $this->db->where("qty_out", 1);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->update("t_serial_movement", $update_serial_movement);

      $this->db->where("trans_type", '14');
      $this->db->where("trans_no", $_POST['hid']);
      $this->db->where("qty_in",'1');
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_serial_movement");

      $execute=0;

      for ($x = 0; $x < 25; $x++) {
        if (isset($_POST['0_' . $x])) {
          if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
            if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
            $serial = $_POST['all_serial_'.$x];
            $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){
                                          
                    $t_serial=array(
                      "store_code"=>$_POST['store_to'],
                      "out_date"=>$_POST['ddate'],
                      );

                      $this->db->where('cl',$this->sd['cl']);
                      $this->db->where('bc',$this->sd['branch']);
                      $this->db->where('serial_no',$p[$i]);
                      $this->db->where("item", $_POST['0_'.$x]);
                      $this->db->where("store_code", $_POST['store_from']);
                      $this->db->update("t_serial", $t_serial);

                      $t_serial_movement=array(
                        "cl"=>$this->sd['cl'],
                        "bc"=>$this->sd['branch'],
                        "trans_type"=>14,
                        "trans_no"=>$this->max_no,
                        "item"=>$_POST['0_'.$x],
                        "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                        "serial_no"=>$p[$i],
                        "qty_in"=>1,
                        "qty_out"=>0,
                        "cost"=>$_POST['3_' . $x],
                        "store_code"=>$_POST['store_to'],
                        "computer"=>$this->input->ip_address(),
                        "oc"=>$this->sd['oc'],
                      );

                      $this->db->insert("t_serial_movement", $t_serial_movement);

                      $t_serial_movement2=array(
                        "cl"=>$this->sd['cl'],
                        "bc"=>$this->sd['branch'],
                        "trans_type"=>14,
                        "trans_no"=>$this->max_no,
                        "item"=>$_POST['0_'.$x],
                        "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                        "serial_no"=>$p[$i],
                        "qty_in"=>0,
                        "qty_out"=>1,
                        "cost"=>$_POST['3_' . $x],
                        "store_code"=>$_POST['store_from'],
                        "computer"=>$this->input->ip_address(),
                        "oc"=>$this->sd['oc'],
                      );

                      $this->db->insert("t_serial_movement", $t_serial_movement2);
                   
                } //end execute
              }//check is serial item
            }
          }
        }//for loop
      }// close else part
  }





  public function batch_item() {
    $sql   = "SELECT qry_current_stock.`qty`,
                    qry_current_stock.`batch_no`,
                    t_item_batch.`max_price` AS cost 
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


  public function item_list_all() {
    if ($_POST['search'] == 'Key Word: code, name') {
      $_POST['search'] = "";
    }
    $sql   = "SELECT qry_current_stock.batch_no, 
                    m_item.description, 
                    qry_current_stock.item, 
                    t_item_batch.purchase_price as cost, 
                    sum(qry_current_stock.qty) as qty  
            FROM qry_current_stock  
            left join m_item on m_item.code = qry_current_stock.item
            left join t_item_batch ON t_item_batch.`item` = m_item.`code` 
            WHERE store_code='$_POST[stores]' AND (m_item.description LIKE '$_POST[search]%' OR `t_item_batch`.`purchase_price` LIKE '$_POST[search]%' 
             OR qry_current_stock.item LIKE '$_POST[search]%') 
              AND qry_current_stock.`cl`='".$this->sd['cl']."'
              AND qry_current_stock.`bc`='".$this->sd['branch']."'
              group by qry_current_stock.item 
              LIMIT 25";

    $query = $this->db->query($sql);
    $a     = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th'>Item Name</th>";
    $a .= "<th class='tb_head_th'>Quantity</th>";
     $a .= "<th class='tb_head_th'>Cost</th>";
    $a .= "</thead></tr>";
    $a .= "<tr class='cl'>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
       $a .= "<td>&nbsp;</td>";
    $a .= "</tr>";
    foreach ($query->result() as $r) {
      $a .= "<tr class='cl'>";
      $a .= "<td>" . $r->item . "</td>";
      $a .= "<td>" . $r->description . "</td>";
      $a .= "<td>" . $r->qty . "</td>";
      $a .= "<td>" . $r->cost . "</td>";
      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }
  public function get_display() {
    $this->db->select(array(
      't_damage_sum.nno',
      't_damage_sum.ddate',
      't_damage_sum.ref_no',
      't_damage_sum.store_from',
      't_damage_sum.store_to',
      't_damage_sum.officer',
      't_damage_sum.memo',
      't_damage_sum.dr_acc',
      't_damage_sum.cr_acc',
      't_damage_sum.is_cancel',
      't_damage_sum.amount as net_amount',
      'm_employee.name'
    ));
    $this->db->from('t_damage_sum');
    $this->db->join('m_employee', 'm_employee.code=t_damage_sum.officer');
    $this->db->where('t_damage_sum.nno', $_POST['max_no']);
    $this->db->where('t_damage_sum.cl', $this->sd['cl']);
    $this->db->where('t_damage_sum.bc', $this->sd['branch']);
    $query = $this->db->get();
    $x     = 0;
    if ($query->num_rows() > 0) {
      $a['sum'] = $query->result();
    } else {
      $x = 2;
    }
    $this->db->select(array(
      't_damage_det.nno',
      't_damage_det.code',
      't_damage_det.qty',
      't_damage_det.cost',
      't_damage_det.amount',
      't_damage_det.batch_no',
      'm_item.description'
    ));
    $this->db->from('t_damage_det');
    $this->db->join('m_item', 'm_item.code=t_damage_det.code');
    $this->db->where('t_damage_det.nno', $_POST['max_no']);
    $this->db->where('t_damage_det.cl', $this->sd['cl']);
    $this->db->where('t_damage_det.bc', $this->sd['branch']);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $a['det'] = $query->result();
    } else {
      $x = 2;
    }







     $query=$this->db->query("SELECT DISTINCT `t_serial_movement`.`item`, 
          `t_serial_movement`.`serial_no` FROM (`t_serial_movement`) JOIN `t_damage_sum` 
          ON `t_serial_movement`.`trans_no`=`t_damage_sum`.`nno` 
          WHERE `t_serial_movement`.`trans_type` = 14 
          AND `t_serial_movement`.`trans_no` = '$_POST[max_no]' 
          AND `t_damage_sum`.`cl` = '".$this->sd['cl']."' 
          AND `t_damage_sum`.`bc` = '".$this->sd['branch']."'");

      $a['serial']=$query->result();


    if ($x == 0) {
      echo json_encode($a);
    } else {
      echo json_encode($x);
    }
  }
  public function PDF_report() {
    

    $this->db->select(array(
      'name',
      'address',
      'tp',
      'fax',
      'email'
    ));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $r_detail['branch']        = $this->db->get('m_branch')->result();
    $r_detail['dt']            = $_POST['dt'];
    $r_detail['qno']           = $_POST['qno'];
    $r_detail['page']          = $_POST['page'];
    $r_detail['header']        = $_POST['header'];
    $r_detail['orientation']   = $_POST['orientation'];
    $r_detail['pdf_page_type'] = $_POST['type1'];
    $r_detail['store_to_id']   = $_POST['store_from_id'];

    $invoice_number= $this->utility->invoice_format($_POST['qno']);
        $session_array = array(
             $this->sd['cl'],
             $this->sd['branch'],
             $invoice_number
        );
    $r_detail['session'] = $session_array;



    $this->db->select(array(
      'qry_current_stock.cl',
      'qry_current_stock.bc',
      'qry_current_stock.item',
      'qry_current_stock.cost',
      'qry_current_stock.batch_no',
      'qry_current_stock.description',
      't_damage_det.qty as dqty',
      't_damage_sum.officer',
      't_damage_sum.ref_no',
      't_damage_sum.store_to',
      't_damage_sum.memo',
      'm_employee.name',
      't_damage_det.nno',
      't_damage_sum.amount',
      't_damage_sum.ddate'
    ));
    $this->db->from('qry_current_stock');
    $this->db->join('t_damage_det', 't_damage_det.code=qry_current_stock.item AND t_damage_det.batch_no=qry_current_stock.batch_no AND t_damage_det.cl=qry_current_stock.cl AND t_damage_det.bc=qry_current_stock.bc');
    $this->db->join('t_damage_sum', 't_damage_det.nno=t_damage_sum.nno');
    $this->db->join('m_employee', 'm_employee.code=t_damage_sum.officer');
    $this->db->where('t_damage_det.nno', $_POST['pdf_id']);
    $this->db->where('qry_current_stock.cl', $this->sd['cl']);
    $this->db->where('qry_current_stock.bc', $this->sd['branch']);
    $this->db->group_by("item"); 
    $query1 = $this->db->get();
    if ($query1->num_rows() > 0) {
      $r_detail['det'] = $query1->result();
    }
    $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
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
    $this->db->select(array(
      'serial_no'
    ));
    $this->db->where("code", $code);
    $this->db->limit(1);
    return $this->db->get("m_item")->first_row()->serial_no;
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

  

  public function checkdelete(){
    $id = $_POST['no'];
   
    $sql="SELECT *
    FROM `t_damage_sum` 
    WHERE (`t_damage_sum`.`nno` = '$id')
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

    $check_cancellation = $this->trans_cancellation->damage_update_status($no,14,'t_damage_sum','t_damage_det');
    if ($check_cancellation != 1) {
      return $check_cancellation;
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
      if($this->user_permissions->is_delete('t_damage_sum')){
      $delete_validation_status=$this->delete_validation();
      if($delete_validation_status==1){
    
        $this->db->trans_start();
        $no = $_POST['id'];

        $this->db->where('nno',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->update('t_damage_sum', array("is_cancel"=>1));

        $this->db->select(array('store_from'));
        $this->db->from('t_damage_sum');
        $this->db->where('nno',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $query_store = $this->db->get();

        $this->db->select(array('item','batch_no','serial_no'));
        $this->db->from('t_serial_movement');
        $this->db->where('trans_type',14);
        $this->db->where('trans_no',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $query1 = $this->db->get();

        foreach($query1->result() as $row){
          $this->db->where('item',$row->item);
          $this->db->where('batch',$row->batch_no);
          $this->db->where('serial_no',$row->serial_no);
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->update('t_serial', array("store_code"=>$query_store->row()->store_from));
        }

        $this->load->model('trans_settlement');
        $this->trans_settlement->delete_item_movement('t_item_movement',14,$no);

        $this->db->where('trans_code',14);
        $this->db->where('trans_no',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->delete("t_item_movement_sub");

        $this->db->where('trans_type',14);
        $this->db->where('trans_no',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->delete("t_serial_movement");

         $this->utility->save_logger("CANCEL",14,$no,$this->mod);
         echo  $this->db->trans_commit();
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
        echo "Operation fail please contact admin"; 
    }   
  }
}
?>