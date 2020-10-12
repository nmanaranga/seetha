<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_dispatch_sum extends CI_Model {

  private $sd;
  private $mtb;

  private $mod = '003';

  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['m_items'];
    $this->m_item = $this->tables->tb['m_item'];
    $this->t_item_movement = $this->tables->tb['t_item_movement'];
    $this->m_stores=$this->tables->tb['m_stores'];
    $this->qry_current_stock=$this->tables->tb['qry_current_stock'];
    $this->m_employee=$this->tables->tb['m_employee'];
    $this->t_loading_sum=$this->tables->tb['t_loading_sum'];
    $this->load->model("utility");
    $this->max_no = $this->utility->get_max_no("t_loading_sum", "nno");
    $this->load->model('user_permissions');
  }

  public function base_details(){
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("group_sale",'1');
    $query = $this->db->get($this->m_stores);

    $st = "<select name='to_store' id='to_store'>";
    $st .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
      $st .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
    }
    $st .= "</select>";

    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("group_sale",'0');
    $query = $this->db->get($this->m_stores);
    $sf = "<select name='from_store' id='from_store' class='store11'>";
    $sf .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
      $sf .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
    }
    $sf .= "</select>";

    $a['get_to_store']=$st;
    $a['get_from_store']=$sf;
    $a['cluster']=$this->sd['cl'];
    $a['branch']=$this->sd['branch'];
    $a['id']=$this->get_next_no();  
    return $a;

  }

  public function get_next_no(){
    $field="nno";
    $this->db->select_max($field);
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']); 
    return $this->db->get("t_loading_sum")->first_row()->$field+1;
  }

  public function validation(){
    $this->max_no = $this->utility->get_max_no("t_loading_sum", "nno");
    $status=1;

    $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_loading_sum');
    if($check_is_delete!=1){
      return "Dispatch loading already deleted";
    }

    $check_validation_group=$this->validation->check_is_group($_POST['group_id']);
    if($check_validation_group!=1){
      return $check_validation_group;
    } 

    $check_validation_employee=$this->validation->check_is_employer($_POST['officer_id']);
    if($check_validation_employee!=1){
      return "Please enter valid Driver";
    } 
    $chk_item_store_validation=$this->validation->check_item_with_store($_POST['from_store'],'0_');
    if($chk_item_store_validation!=1){ 
      return $chk_item_store_validation;
    }
    $serial_validation_status=$this->validation->serial_update('0_','3_','all_serial_');
    if($serial_validation_status!=1){
      return $serial_validation_status;
    }
    $check_batch_validation=$this->utility->batch_update2('0_','2_','3_',$_POST['from_store']);
    if($check_batch_validation!=1){
      return $check_batch_validation;
    }
    $chk_zero_qty=$this->validation->empty_qty('0_','3_');
    if($chk_zero_qty!=1){
      return $chk_zero_qty;
    }
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
      $_POST['date']=$_POST['action_date'];
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
                  $sub_qty = (int)$_POST['3_'.$x] * (int)$item_sub[1];

                  $t_sub_item_movement[]=array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "item"=>$_POST['0_'.$x],
                    "sub_item"=>$item_sub[0],
                    "trans_code"=>11,
                    "trans_no"=>$this->max_no,
                    "ddate"=>$_POST['action_date'],
                    "qty_in"=>0,
                    "qty_out"=>$sub_qty,
                    "store_code"=>$_POST['from_store'],
                    "avg_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                    "batch_no"=>$_POST['2_'.$x],
                    "sales_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                    "last_sales_price"=>$this->utility->get_min_price($_POST['0_' . $x]),
                    "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                    "group_sale_id"=>'001'
                  );

                  $t_sub_item_movement2[]=array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "item"=>$_POST['0_'.$x],
                    "sub_item"=>$item_sub[0],
                    "trans_code"=>11,
                    "trans_no"=>$this->max_no,
                    "ddate"=>$_POST['action_date'],
                    "qty_in"=>$sub_qty,
                    "qty_out"=>0,
                    "store_code"=>$_POST['to_store'],
                    "avg_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                    "batch_no"=>$_POST['2_'.$x],
                    "sales_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                    "last_sales_price"=>$this->utility->get_min_price($_POST['0_' . $x]),
                    "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                    "group_sale_id"=>$_POST['group_id']
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
              "qty"=>$_POST['3_'.$x],
              "batch_no"=>$_POST['2_'.$x],
              "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
              "min"=>$_POST['min_'.$x],
              "max"=>$_POST['max_'.$x]
            );              
          }
        }
      }                    

      $data=array(
        "cl"=>$this->sd['cl'],
        "bc"=>$this->sd['branch'],
        "nno"=>$this->max_no,
        "ref_no"=>$_POST['ref_no'],
        "memo"=>$_POST['memo'],
        "store_from"=>$_POST['from_store'],
        "store_to"=>$_POST['to_store'],
        "type"=>$_POST['l_type'],
        "officer"=>$_POST['officer_id'],
        "driver"=>$_POST['driver_id'],
        "helper"=>$_POST['helper_id'],
        "group_sale_id"=>$_POST['group_id'],
        "oc"=>$this->sd['oc'],
        "ddate"=>$_POST['action_date']
      );



      if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_dispatch_sum')){
          if($_POST['df_is_serial']=='1'){
            $this->serial_save();    
          }
          $this->db->insert($this->t_loading_sum,$data);
          if(count($b)){$this->db->insert_batch("t_loading_det",$b);}
          $this->load->model('trans_settlement');
          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
              if($_POST['0_'.$x] != ""){
                $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '11',
                  $this->max_no,
                  $_POST['action_date'],
                  0,
                  $_POST['3_'.$x],
                  $_POST['from_store'],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['2_'.$x],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $this->utility->get_min_price($_POST['0_' . $x]),
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['group_id']);

                $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '11',
                  $this->max_no,
                  $_POST['action_date'],
                  $_POST['3_'.$x],
                  0,
                  $_POST['to_store'],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['2_'.$x],
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $this->utility->get_min_price($_POST['0_' . $x]),
                  $this->utility->get_cost_price($_POST['0_' . $x]),
                  $_POST['group_id']);

              }
            }     
          }

          if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
          if(isset($t_sub_item_movement2)){if(count($t_sub_item_movement2)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement2);}}
          $this->utility->save_logger("SAVE",13,$this->max_no,$this->mod);
          echo $this->db->trans_commit(); 
        }else{
          echo "No permission to save records";
          $this->db->trans_commit();
        }  

      }else{
        if($this->user_permissions->is_edit('t_dispatch_sum')){
          $check_update = $this->trans_cancellation->damage_update_status($this->max_no,11,'t_loading_sum','t_loading_det');
          if ($check_update == 1){
            if($_POST['df_is_serial']=='1'){
              $this->serial_save();    
            }

            $data_update=array(
              "ref_no"=>$_POST['ref_no'],
              "memo"=>$_POST['memo'],
              "store_from"=>$_POST['from_store'],
              "store_to"=>$_POST['to_store'],
              "type"=>$_POST['l_type'],
              "officer"=>$_POST['officer_id'],
              "driver"=>$_POST['driver_id'],
              "helper"=>$_POST['helper_id'],
              "oc"=>$this->sd['oc'],
              "ddate"=>$_POST['action_date']
            );

            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_item_movement('t_item_movement',11,$_POST['hid']);

            $this->db->where("trans_code", 11);
            $this->db->where("trans_no", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_item_movement_sub");

            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_loading_det");

            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update("t_loading_sum", $data_update);

            if(count($b)){$this->db->insert_batch("t_loading_det",$b);}
            $this->load->model('trans_settlement');
            for($x = 0; $x<25; $x++){
              if(isset($_POST['0_'.$x])){
                if($_POST['0_'.$x] != ""){
                  $this->trans_settlement->save_item_movement('t_item_movement',
                    $_POST['0_'.$x],
                    '11',
                    $this->max_no,
                    $_POST['action_date'],
                    0,
                    $_POST['3_'.$x],
                    $_POST['from_store'],
                    $this->utility->get_cost_price($_POST['0_' . $x]),
                    $_POST['2_'.$x],
                    $this->utility->get_cost_price($_POST['0_' . $x]),
                    $this->utility->get_min_price($_POST['0_' . $x]),
                    $this->utility->get_cost_price($_POST['0_' . $x]),
                    $_POST['group_id']);

                  $this->trans_settlement->save_item_movement('t_item_movement',
                    $_POST['0_'.$x],
                    '11',
                    $this->max_no,
                    $_POST['action_date'],
                    $_POST['3_'.$x],
                    0,
                    $_POST['to_store'],
                    $this->utility->get_cost_price($_POST['0_' . $x]),
                    $_POST['2_'.$x],
                    $this->utility->get_cost_price($_POST['0_' . $x]),
                    $this->utility->get_min_price($_POST['0_' . $x]),
                    $this->utility->get_cost_price($_POST['0_' . $x]),
                    $_POST['group_id']);

                }
              }     
            }

            if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
            if(isset($t_sub_item_movement2)){if(count($t_sub_item_movement2)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement2);}}
            $this->utility->save_logger("EDIT",13,$this->max_no,$this->mod);
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
   echo $e->getMessage()." - Operation fail please contact admin"; 
 } 
}


public function serial_save() {
  for ($x = 0; $x < 25; $x++) {
    if (isset($_POST['0_' . $x])) {
      if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
        if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
          $serial = $_POST['all_serial_'.$x];
          $p=explode(",",$serial);
          for($i=0; $i<count($p); $i++){
            if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

              $t_serial=array(
                "store_code"=>$_POST['to_store'],
                "out_date"=>$_POST['action_date'],
              );

              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->where('serial_no',$p[$i]);
              $this->db->where("item", $_POST['0_'.$x]);
              $this->db->where("store_code", $_POST['from_store']);
              $this->db->update("t_serial", $t_serial);

              $t_serial_movement=array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "trans_type"=>11,
                "trans_no"=>$this->max_no,
                "item"=>$_POST['0_'.$x],
                "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                "serial_no"=>$p[$i],
                "qty_in"=>1,
                "qty_out"=>0,
                "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                "store_code"=>$_POST['to_store'],
                "computer"=>$this->input->ip_address(),
                "oc"=>$this->sd['oc'],
              );

              $this->db->insert("t_serial_movement", $t_serial_movement);

              $t_serial_movement2=array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "trans_type"=>11,
                "trans_no"=>$this->max_no,
                "item"=>$_POST['0_'.$x],
                "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                "serial_no"=>$p[$i],
                "qty_in"=>0,
                "qty_out"=>1,
                "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                "store_code"=>$_POST['from_store'],
                "computer"=>$this->input->ip_address(),
                "oc"=>$this->sd['oc'],
              );

              $this->db->insert("t_serial_movement", $t_serial_movement2);
                    }//only if save 
                  }//end serial for loop 
                }
              }
            }
          }

          if($_POST['hid'] == "0" || $_POST['hid'] == ""){

          }else{

            $update_serial_movement = array(
              "qty_in" => 1,
              "qty_out" => 0
            );

            $this->db->select(array('item', 'serial_no'));
            $this->db->where("trans_no", $_POST['hid']);
            $this->db->where("trans_type", '11');
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $query = $this->db->get("t_serial_movement");

            foreach ($query->result() as $row) {
              $this->db->query("UPDATE t_serial SET store_code='$_POST[from_store]' WHERE item='$row->item' AND serial_no='$row->serial_no' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'");
            }


            $this->db->where('trans_type', 11);
            $this->db->where("trans_no", $_POST['hid']);
            $this->db->where("qty_out", 1);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update("t_serial_movement", $update_serial_movement);


            $this->db->where("trans_type", '11');
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
                        "store_code"=>$_POST['to_store'],
                        "out_date"=>$_POST['action_date'],
                      );

                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->where('serial_no',$p[$i]);
                      $this->db->where("item", $_POST['0_'.$x]);
                      $this->db->where("store_code", $_POST['from_store']);
                      $this->db->update("t_serial", $t_serial);

                      $t_serial_movement=array(
                        "cl"=>$this->sd['cl'],
                        "bc"=>$this->sd['branch'],
                        "trans_type"=>11,
                        "trans_no"=>$this->max_no,
                        "item"=>$_POST['0_'.$x],
                        "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                        "serial_no"=>$p[$i],
                        "qty_in"=>1,
                        "qty_out"=>0,
                        "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                        "store_code"=>$_POST['to_store'],
                        "computer"=>$this->input->ip_address(),
                        "oc"=>$this->sd['oc'],
                      );

                      $this->db->insert("t_serial_movement", $t_serial_movement);

                      $t_serial_movement2=array(
                        "cl"=>$this->sd['cl'],
                        "bc"=>$this->sd['branch'],
                        "trans_type"=>11,
                        "trans_no"=>$this->max_no,
                        "item"=>$_POST['0_'.$x],
                        "batch_no"=>$this->get_batch_serial_wise($_POST['0_'.$x],$p[$i]),
                        "serial_no"=>$p[$i],
                        "qty_in"=>0,
                        "qty_out"=>1,
                        "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                        "store_code"=>$_POST['from_store'],
                        "computer"=>$this->input->ip_address(),
                        "oc"=>$this->sd['oc'],
                      );

                      $this->db->insert("t_serial_movement", $t_serial_movement2);
                  } //end execute
                }//check is serial item
              }
            }
          }
        }
      }

      public function check_code(){
       $this->db->where('code', $_POST['code']);
       $this->db->limit(1);
       echo $this->db->get($this->mtb)->num_rows;
     }

     public function load(){

      $cl=$this->sd['cl'];
      $bc=$this->sd['branch'];
      $this->db->select(
        array(
          't_loading_sum.type',  
          't_loading_sum.officer',
          't_loading_sum.driver',
          't_loading_sum.helper',
          't_loading_sum.store_to',
          't_loading_sum.store_from',
          't_loading_sum.memo',
          't_loading_sum.ref_no',
          't_loading_sum.ddate',
          't_loading_sum.is_cancel',
          't_loading_sum.group_sale_id',
          'o.name as o_name',
          'd.name as d_name',
          'h.name as h_name',
          'r_groups.name as gro'
        ));

      $this->db->from('t_loading_sum');
      $this->db->join('m_employee o','o.code=t_loading_sum.officer');
      $this->db->join('m_employee d','d.code=t_loading_sum.driver','left');
      $this->db->join('m_employee h','h.code=t_loading_sum.helper','left');
      $this->db->join('r_groups','r_groups.code=t_loading_sum.group_sale_id');
      $this->db->where('t_loading_sum.nno',$_POST['id']);
      $this->db->where('t_loading_sum.cl',$this->sd['cl'] );
      $this->db->where('t_loading_sum.bc',$this->sd['branch'] );

      $query=$this->db->get();
      $x=0;
      if($query->num_rows()>0){
        $a['sum']=$query->result();
      }else{
        $x=2;
      }

      $this->db->select(
        array(
          't_loading_det.code',
          't_loading_det.qty',
          't_loading_det.cost',
          't_loading_det.min',
          't_loading_det.max',
          't_loading_det.batch_no',
          'm_item.model',
          'm_item.description',
        ));

      $this->db->from('t_loading_det');
      $this->db->join('m_item','m_item.code=t_loading_det.code');
      $this->db->where('t_loading_det.nno',$_POST['id']);
      $this->db->where('t_loading_det.cl',$cl );
      $this->db->where('t_loading_det.bc',$bc);

      $query=$this->db->get();
      if($query->num_rows()>0){
       $a['det']=$query->result();
     }else{
      $x=2;
    }

    $query=$this->db->query("SELECT DISTINCT `t_serial_movement`.`item`, 
      `t_serial_movement`.`serial_no` FROM (`t_serial_movement`) JOIN `t_loading_sum` 
      ON `t_serial_movement`.`trans_no`=`t_loading_sum`.`nno` 
      WHERE `t_serial_movement`.`trans_type` = 11 
      AND `t_serial_movement`.`trans_no` = '$_POST[id]' 
      AND `t_loading_sum`.`cl` = '$cl' 
      AND `t_loading_sum`.`bc` = '$bc'");

    $a['serial']=$query->result();

    if($x==0){
      echo json_encode($a);
    }else{
      echo json_encode($x);
    }       
  }


  public function select(){
    $query = $this->db->get($this->mtb);
    $s = "<select name='sales_ref' id='sales_ref'>";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
      $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
    }
    $s .= "</select>";

    return $s;
  }

  public function get_from_store(){
    $query = $this->db->get($this->m_stores);
    $s = "<select name='from_store' id='from_store' class='store11'>";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
      $s .= "<option title='".$r->description."' value='".$r->description."'>".$r->description."</option>";
    }
    $s .= "</select>";

    echo  $s;

  }
  public function get_to_store(){
    $query = $this->db->get($this->m_stores);

    $s = "<select name='to_store' id='to_store'>";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
      $s .= "<option title='".$r->description."' value='".$r->description."'>".$r->description."</option>";
    }
    $s .= "</select>";

    echo  $s;

  }
  public function auto_com(){
   $this->db->like('code', $_GET['q']);
   $this->db->or_like($this->m_employee.'.name', $_GET['q']);
   $query = $this->db->select(array('code', $this->m_employee.'.name'))
   ->get($this->m_employee);
   $abc = "";
   foreach($query->result() as $r){
    $abc .= $r->code."|".$r->name;

    $abc .= "\n";
  }

  echo $abc;
}  


public function item_list_all(){
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

       /*if($_POST['search'] == ""){
          $sql="SELECT item, 
                      description, 
                      batch_no, 
                      sum(qty) as qty 
                FROM qry_current_stock 
          WHERE store_code='$_POST[stores]' 
          AND qry_current_stock.`cl`='$cl'
          AND qry_current_stock.`bc`='$bc'
          group by item,batch_no LIMIT 25";
        }else{
          $sql="SELECT item, 
                      description, 
                      batch_no, 
                      sum(qty) as qty 
          FROM qry_current_stock 
          WHERE store_code='$_POST[stores]' AND (item LIKE '$_POST[search]%' OR description LIKE'$_POST[search]%') 
          AND qry_current_stock.`cl`='$cl'
          AND qry_current_stock.`bc`='$bc'
          group by item,batch_no LIMIT 25";
        }*/

        $sql="SELECT DISTINCT(m_item.code) as item, 
        m_item.`description`,
        m_item.`model`,
        m_item.`warranty`,
        t_item_batch.`max_price`, 
        t_item_batch.`batch_no`, 
        t_item_batch.`min_price`,
        t_item_batch.purchase_price,
        qry_current_stock.qty 
        FROM m_item 
        JOIN qry_current_stock ON m_item.`code`=qry_current_stock.`item`
        JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no`
        WHERE qry_current_stock.`store_code`='$_POST[stores]' 
        AND qry_current_stock.qty > 0 
        AND qry_current_stock.cl='$cl' 
        AND qry_current_stock.bc='$bc' 
        AND (m_item.`description` LIKE '%$_POST[search]%' 
        OR m_item.`code` LIKE '%$_POST[search]%' 
        OR m_item.model LIKE '%$_POST[search]%' 
        OR t_item_batch.`max_price` LIKE '%$_POST[search]%'
        OR `t_item_batch`.`min_price` LIKE '%$_POST[search]%' 
        OR `t_item_batch`.`max_price` LIKE '%$_POST[search]%') 
        AND `m_item`.`inactive`='0'
        GROUP BY  m_item.code
        LIMIT 25";



        $query =$this->db->query($sql);


        $a = "<table id='item_list' style='width : 100%' >";
        $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Item Code</th>";
        $a .= "<th class='tb_head_th'>Name</th>"; 
        $a .= "<th class='tb_head_th'>Model</th>";
        $a .= "<th class='tb_head_th'>Batch No</th>"; 
        $a .= "<th class='tb_head_th'>Quantity</th>";
        $a .= "<th class='tb_head_th'>Min Price</th>";
        $a .= "<th class='tb_head_th'>Max Price</th>";

        $a .= "</thead></tr>
        <tr class='cl'>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        ";            
        foreach($query->result() as $r){
          $a .= "<tr class='cl'>";
          $a .= "<td>".$r->item."</td>";
          $a .= "<td>".$r->description."</td>"; 
          $a .= "<td style='display:none;'>".$r->batch_no."</td>";
          $a .= "<td style='display:none;'>".$r->qty."</td>";
          $a .= "<td style='display:none;'>".$r->min_price."</td>";
          $a .= "<td style='display:none;'>".$r->max_price."</td>";
          $a .= "<td>".$r->model."</td>"; 
          $a .= "<td>".$r->batch_no."</td>";
          $a .= "<td>".$r->qty."</td>";
          $a .= "<td>".$r->min_price."</td>";
          $a .= "<td>".$r->max_price."</td>";

          $a .= "</tr>";
        }
        $a .= "</table>";
        echo $a;
      }

      public function batch_item() {
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $sql="SELECT b.batch_no,q.`qty`,b.purchase_price AS cost,b.min_price,b.max_price 
        FROM qry_current_stock q
        JOIN t_item_batch b ON b.`item`=q.`item` AND b.`batch_no`=q.`batch_no`
        WHERE q.`cl`='$cl' AND q.`bc`='$bc' 
        AND q.`item`='$_POST[search]' AND q.`store_code`='$_POST[stores]' AND q.`qty`>0";

/*        $sql = "SELECT qry_current_stock.`qty`,qry_current_stock.`batch_no`,m_item.`max_price` AS cost 
               FROM qry_current_stock JOIN m_item ON qry_current_stock.`item`=m_item.`code` WHERE qry_current_stock.`qty`>'0'
               AND qry_current_stock.`store_code`='$_POST[stores]' AND qry_current_stock.`item`='$_POST[search]'
               AND qry_current_stock.`cl`='$cl'
               AND qry_current_stock.`bc`='$bc'";*/

               $query = $this->db->query($sql);

               $a = "<table id='batch_item_list' style='width : 100%' >";

               $a .= "<thead><tr>";
               $a .= "<th class='tb_head_th'>Batch No</th>";
               $a .= "<th class='tb_head_th'>Available Quantity</th>";
               $a .= "<th class='tb_head_th'>Cost</th>";
               $a .= "<th class='tb_head_th'>Min Price</th>";
               $a .= "<th class='tb_head_th'>Max Price</th>";

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
                $a .= "<td>" . $r->cost . "</td>";
                $a .= "<td>" . $r->min_price . "</td>";
                $a .= "<td>" . $r->max_price . "</td>";
                $a .= "</tr>";
              }
              $a .= "</table>";
              echo $a;
            }

            public function is_batch_item() {
        /*$this->db->select(array("batch_no", "qty"));
        $this->db->where("item", $_POST['code']);
        $this->db->where("store_code", $_POST['store']);
        $this->db->where("qty >", "0");
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $query = $this->db->get("qry_current_stock");*/

        $sql="SELECT b.batch_no,q.`qty`,b.purchase_price AS cost,b.min_price,b.max_price 
        FROM qry_current_stock q
        JOIN t_item_batch b ON b.`item`=q.`item` AND b.`batch_no`=q.`batch_no`
        WHERE q.`cl`='".$this->sd['cl']."' AND q.`bc`='".$this->sd['branch']."' 
        AND q.`item`='$_POST[code]' AND q.`store_code`='$_POST[store]'
        AND q.qty>0";
        $query=$this->db->query($sql);
        if ($query->num_rows() == 1) {
          foreach ($query->result() as $row) {
            echo $row->batch_no."-".$row->qty."-".$row->cost."-".$row->min_price."-".$row->max_price;
          }
        } else if ($query->num_rows() > 0) {
          echo "1";
        } else {
          echo "0";
        }
      }


      public function get_max_no(){
        $this->db->select_max('nno', 'max_nno');
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $query =$this->db->get($this->t_loading_sum);

        foreach($query->result() as $value) {
         $max_nno=$value->max_nno;
       }
       echo (float)$max_nno+1;                
     }

     public function get_officer(){
       $this->db->select('name');
       $query =$this->db->get($this->m_employee);
       $s = "<select name='officer' id='officer'>";
       $s .= "<option value='0'>---</option>";
       foreach($query->result() as $r){
        $s .= "<option title='".$r->code."' value='".$r->code."'>".$r->name."</option>";
      }
      $s .= "</select>";

      echo  $s;
    }
    public function check_qty(){
      $this->db->select(array('qty','purchase_price'));
      $this->db->from($this->qry_current_stock );
      $this->db->join($this->m_item, $this->m_item.'.code='.$this->qry_current_stock.'.item');
      $this->db->where('item',$_POST['item']);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);      
      $this->db->limit(1);
      $query = $this->db->get();

      if($query->num_rows() > 0){
       $data['a']=$query->result() ;
     }

     echo json_encode($data);

   }

   public function get_display(){



    $this->db->select(
      array(
        'qry_current_stock.cl',
        'qry_current_stock.bc',
        'qry_current_stock.item',
        'qry_current_stock.batch_no',
        'qry_current_stock.description',
        't_loading_det.qty as dqty',
        't_loading_sum.officer',
        't_loading_sum.ref_no',
        't_loading_sum.store_to',
        't_loading_sum.store_from',
        't_loading_sum.memo',
        't_loading_sum.action_date'
      ));

    $this->db->from('qry_current_stock');
    $this->db->join('t_loading_det','t_loading_det.code=qry_current_stock.item');
    $this->db->join('t_loading_sum','t_loading_det.nno=t_loading_sum.nno');
    $this->db->where('t_loading_det.nno',$_POST['id']);
    $this->db->where('qry_current_stock.cl',$this->sd['cl'] );
    $this->db->where('qry_current_stock.bc',$this->sd['branch'] );

    $query1=$this->db->get();

    if($query1->num_rows()>0){
      $a['det']=$query1->result();
      echo json_encode($a);
    }
    else{
      echo json_encode("2");
    }

  }

  public function PDF_report(){

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $invoice_number= $this->utility->invoice_format($_POST['qno']);
    $session_array = array(
     $this->sd['cl'],
     $this->sd['branch'],
     $invoice_number
   );
    $r_detail['session'] = $session_array;

    $r_detail['dt']=$_POST['dt'];
    $r_detail['qno']=$_POST['qno'];

    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];
    $r_detail['pdf_page_type']=$_POST['type1'];

    $sql="SELECT 
    s.cl,
    s.bc,
    d.code AS item,
    d.batch_no,
    m.description,
    d.qty AS dqty,
    s.ref_no,
    s.store_to,
    s1.`description` AS store_to_name,
    s.store_from,
    s2.`description` AS store_fr_name,
    s.memo,
    s.action_date,
    s.group_sale_id,
    r.name AS gr_name,
    d.min,
    d.max,
    m.model 
    FROM t_loading_sum s 
    JOIN t_loading_det d ON s.cl=d.cl and s.bc=d.bc and s.nno=d.nno 
    JOIN m_employee e on e.code = s.officer
    JOIN m_item m on m.code=d.code
    JOIN m_stores s1 ON s1.code=s.store_to
    JOIN m_stores s2 ON s2.code=s.store_from
    JOIN `r_groups` r ON r.code=s.group_sale_id
    WHERE s.cl='".$this->sd['cl']."' 
    AND s.bc='".$this->sd['branch']."'
    AND s.nno='".$_POST['qno']."'  
    GROUP BY d.code";

    $query1=$this->db->query($sql);

      /*$this->db->select(
                  array(
                  'qry_current_stock.cl',
                  'qry_current_stock.bc',
                  'qry_current_stock.item',
                  'qry_current_stock.batch_no',
                  'qry_current_stock.description',
                  't_loading_det.qty as dqty',
                  'm_employee.name',
                  't_loading_sum.ref_no',
                  't_loading_sum.store_to',
                  't_loading_sum.store_from',
                  't_loading_sum.memo',
                  't_loading_sum.action_date',
                  't_loading_sum.group_sale_id'
                  ));

      $this->db->from('qry_current_stock');
      $this->db->join('t_loading_det','t_loading_det.code=qry_current_stock.item');
      $this->db->join('t_loading_sum','t_loading_det.nno=t_loading_sum.nno');
      $this->db->join('m_employee','m_employee.code=t_loading_sum.officer');
      $this->db->where('t_loading_det.nno',$_POST['qno']);
      $this->db->where('qry_current_stock.cl',$this->sd['cl'] );
      $this->db->where('qry_current_stock.bc',$this->sd['branch'] );
      $this->db->group_by("qry_current_stock.item"); 

      $query1=$this->db->get();*/

      if($query1->num_rows()>0){
        $r_detail['det']=$query1->result();       
      }

      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }

    public function check_is_serial_item(){
      $this->db->select(array('serial_no'));
      $this->db->where("code",$this->input->post('code'));
      $this->db->limit(1);
      echo  $this->db->get("m_item")->first_row()->serial_no;
    }


    public function check_is_serial_items($code){
      $this->db->select(array('serial_no'));
      $this->db->where("code",$code);
      $this->db->limit(1);
      return $this->db->get("m_item")->first_row()->serial_no;
    }

    public function is_serial_entered($trans_no,$item,$serial){
      $this->db->select(array('available'));
      $this->db->where("serial_no",$serial);
      $this->db->where("item",$item);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $query=$this->db->get("t_serial");

      if($query->num_rows()>0){
        return 1;
      }else{
        return 0;
      }
    }

    public function get_batch_serial_wise($item,$serial){
      $this->db->select("batch");
      $this->db->where("item",$item);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where("serial_no",$serial);
      return $this->db->get('t_serial')->first_row()->batch; 
    }

    public function checkdelete(){
      $id = $_POST['no'];

      $sql="SELECT *
      FROM `t_loading_sum` 
      WHERE (`t_loading_sum`.`nno` = '$id')
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

      $check_cancellation = $this->trans_cancellation->damage_update_status($no,11,'t_loading_sum','t_loading_det');
      if ($check_cancellation != 1){
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
        if($this->user_permissions->is_delete('t_dispatch_sum')){
          $delete_validation_status=$this->delete_validation();
          if($delete_validation_status==1){

            $no = $_POST['id'];

            $this->db->where('nno',$no);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->update('t_loading_sum', array("is_cancel"=>1));

            $this->db->select(array('store_from'));
            $this->db->from('t_loading_sum');
            $this->db->where('nno',$no);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $query_store = $this->db->get();

            $this->db->select(array('item','batch_no','serial_no'));
            $this->db->from('t_serial_movement');
            $this->db->where('trans_type',11);
            $this->db->where('trans_no',$no);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $query1 = $this->db->get();

            foreach($query1->result() as $row) {
              $this->db->where('item',$row->item);
              $this->db->where('batch',$row->batch_no);
              $this->db->where('serial_no',$row->serial_no);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update('t_serial', array("store_code"=>$query_store->row()->store_from));
            }

            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_item_movement('t_item_movement',11,$no);

            $this->db->where('trans_code',11);
            $this->db->where('trans_no',$no);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->delete("t_item_movement_sub");

            $this->db->where('trans_type',11);
            $this->db->where('trans_no',$no);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->delete("t_serial_movement");
            $this->utility->save_logger("CANCEL",11,$no,$this->mod);  

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