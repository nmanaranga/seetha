<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_dispatch_note extends CI_Model {

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
   $this->t_dispatch_sum=$this->tables->tb['t_dispatch_sum'];
   $this->load->model("utility");
   $this->max_no = $this->utility->get_max_no("t_dispatch_sum", "nno");
   $this->load->model('user_permissions');
 }

 public function base_details(){
  $this->db->select(array('name','code'));
  $query =$this->db->get($this->m_employee);
  $s = "<select name='officer' id='officer'>";
  $s .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->name."</option>";
  }
  $s .= "</select>";

  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $query = $this->db->get($this->m_stores);

  $st = "<select name='to_store' id='to_store'>";
  $st .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $st .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
  }
  $st .= "</select>";

  $sf = "<select name='from_store' id='from_store' class='store11'>";
  $sf .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $sf .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
  }
  $sf .= "</select>";

  $a['get_to_store']=$st;
  $a['get_from_store']=$sf;
  $a['officer']=$s;
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
  return $this->db->get("t_dispatch_sum")->first_row()->$field+1;
}

public function validation(){
  $status=1;

  $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_dispatch_sum');
  if($check_is_delete!=1){
    return "Dispatch note already deleted";
  }

  $check_validation_employee=$this->validation->check_is_employer($_POST['officer']);
  if($check_validation_employee!=1){
    return "Please select valid officer";
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
                    "trans_code"=>13,
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
                    );


                  $t_sub_item_movement2[]=array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "item"=>$_POST['0_'.$x],
                    "sub_item"=>$item_sub[0],
                    "trans_code"=>13,
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
                  //"type"=>13,
              "nno"=>$this->max_no,
              "code"=>$_POST['0_'.$x],
              "qty"=>$_POST['3_'.$x],
              "batch_no"=>$_POST['2_'.$x],
              "cost"=>$this->utility->get_cost_price($_POST['0_' . $x])
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
        "officer"=>$_POST['officer'],
        "oc"=>$this->sd['oc'],
        "ddate"=>$_POST['action_date'],
        "group_sale_id"=>$_POST['group_id']
        );

      if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_dispatch_note')){
          if($_POST['df_is_serial']=='1')
          {
            $this->serial_save();    
          }
          $this->db->insert($this->t_dispatch_sum,$data);
          if(count($b)){$this->db->insert_batch("t_dispatch_det",$b);}

          $this->load->model('trans_settlement');
          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
              if($_POST['0_'.$x] != ""){
                $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '13',
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
                  '001');

                $this->trans_settlement->save_item_movement('t_item_movement',
                  $_POST['0_'.$x],
                  '13',
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
                  '001');
              }
            }     
          }

          if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
          if(isset($t_sub_item_movement2)){if(count($t_sub_item_movement2)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement2);}}
          $this->utility->save_logger("SAVE",13,$this->max_no,$this->mod);
          echo $this->db->trans_commit()."@".$this->max_no;
        }else{
          echo "No permission to save records";
          $this->db->trans_commit(); 
        }  

      }else{
        if($this->user_permissions->is_edit('t_dispatch_note')){
          $check_update = $this->trans_cancellation->damage_update_status($this->max_no,13,'t_dispatch_sum','t_dispatch_det');
          if ($check_update == 1){
            if($_POST['df_is_serial']=='1')
            {
              $this->serial_save();    
            }
            $data_update=array(
              "ref_no"=>$_POST['ref_no'],
              "memo"=>$_POST['memo'],
              "store_from"=>$_POST['from_store'],
              "store_to"=>$_POST['to_store'],
              "officer"=>$_POST['officer'],
              "oc"=>$this->sd['oc'],
              "ddate"=>$_POST['action_date'],
              "group_sale_id"=>$_POST['group_id']
              );


            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_item_movement('t_item_movement',13,$_POST['hid']);

            $this->db->where("trans_code", 13);
            $this->db->where("trans_no", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_item_movement_sub");

              //$this->db->where("type", 13);
            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_dispatch_det");

              //$this->db->where("type", 13);
            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update("t_dispatch_sum", $data_update);

            if(count($b)){$this->db->insert_batch("t_dispatch_det",$b);}
            $this->load->model('trans_settlement');
            for($x = 0; $x<25; $x++){
              if(isset($_POST['0_'.$x])){
                if($_POST['0_'.$x] != ""){
                  $this->trans_settlement->save_item_movement('t_item_movement',
                    $_POST['0_'.$x],
                    '13',
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
                    '001');

                  $this->trans_settlement->save_item_movement('t_item_movement',
                    $_POST['0_'.$x],
                    '13',
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
                    '001');
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
    echo "Operation fail please contact admin"; 
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
                "trans_type"=>13,
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
                "trans_type"=>13,
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
      $this->db->where("trans_type", '13');
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $query = $this->db->get("t_serial_movement");

      foreach ($query->result() as $row) {
        $this->db->query("UPDATE t_serial SET store_code='$_POST[from_store]' WHERE item='$row->item' AND serial_no='$row->serial_no' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'");
      }    

      $this->db->where('trans_type', 13);
      $this->db->where("trans_no", $_POST['hid']);
      $this->db->where("qty_out", 1);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->update("t_serial_movement", $update_serial_movement);

      $this->db->where("trans_type", '13');
      $this->db->where("trans_no", $_POST['hid']);
      $this->db->where("qty_in",'1');
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_serial_movement");

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
                  "trans_type"=>13,
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
                  "trans_type"=>13,
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
        }//for loop
      }// close else part
    }

    public function check_code(){
     $this->db->where('code', $_POST['code']);
     $this->db->limit(1);
     echo $this->db->get($this->mtb)->num_rows;
   }

   public function load(){
     $this->db->select(
      array(
        't_dispatch_sum.officer',
        't_dispatch_sum.store_to',
        't_dispatch_sum.store_from',
        't_dispatch_sum.memo',
        't_dispatch_sum.ref_no',
        't_dispatch_sum.ddate',
        't_dispatch_sum.is_cancel',
        't_dispatch_sum.group_sale_id',
        'r_groups.name as group_name',
        'm_employee.name'
        ));

     $this->db->from('t_dispatch_sum');
     $this->db->join('m_employee','m_employee.code=t_dispatch_sum.officer');
     $this->db->join('r_groups','r_groups.code=t_dispatch_sum.group_sale_id','left');
     $this->db->where('t_dispatch_sum.nno',$_POST['id']);
     $this->db->where('t_dispatch_sum.cl',$this->sd['cl'] );
     $this->db->where('t_dispatch_sum.bc',$this->sd['branch'] );

     $query=$this->db->get();
     $x=0;
     if($query->num_rows()>0){
      $a['sum']=$query->result();
    }else{
      $x=2;
    }
    

    $this->db->select(
      array(
        't_dispatch_det.code',
        't_dispatch_det.qty',
        't_dispatch_det.batch_no',
        'm_item.description',
        ));

    $this->db->from('t_dispatch_det');
    $this->db->join('m_item','m_item.code=t_dispatch_det.code');
    $this->db->where('t_dispatch_det.nno',$_POST['id']);
    $this->db->where('t_dispatch_det.cl',$this->sd['cl'] );
    $this->db->where('t_dispatch_det.bc',$this->sd['branch'] );

    $query=$this->db->get();
    if($query->num_rows()>0){
     $a['det']=$query->result();
   }else{
    $x=2;
  }

  $query=$this->db->query("SELECT DISTINCT `t_serial_movement`.`item`, 
    `t_serial_movement`.`serial_no` FROM (`t_serial_movement`) JOIN `t_dispatch_sum` 
    ON `t_serial_movement`.`trans_no`=`t_dispatch_sum`.`nno` 
    WHERE `t_serial_movement`.`trans_type` = 13 
    AND `t_serial_movement`.`trans_no` = '$_POST[id]' 
    AND `t_dispatch_sum`.`cl` = '".$this->sd['cl']."' 
    AND `t_dispatch_sum`.`bc` = '".$this->sd['branch']."'");

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
  if($_POST['search'] == ""){
    $sql="SELECT item, description, batch_no, qty FROM qry_current_stock WHERE store_code='$_POST[stores]' 
    AND qry_current_stock.`cl`='".$this->sd['cl']."'
    AND qry_current_stock.`bc`='".$this->sd['branch']."'
    group by item";
  }else{
    $sql="SELECT item, description, batch_no, qty FROM qry_current_stock WHERE store_code='$_POST[stores]' AND (item LIKE '$_POST[search]%' OR description LIKE'$_POST[search]%') 
    AND qry_current_stock.`cl`='".$this->sd['cl']."'
    AND qry_current_stock.`bc`='".$this->sd['branch']."'
    group by item";
  }

  $query =$this->db->query($sql);

  $a = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Item Code</th>";
  $a .= "<th class='tb_head_th'>Name</th>"; 
  $a .= "<th class='tb_head_th'>Batch No</th>"; 
  $a .= "<th class='tb_head_th'>Quantity</th>";

  $a .= "</thead></tr>
  <tr class='cl'>
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
    $a .= "<td>".$r->batch_no."</td>";
    $a .= "<td>".$r->qty."</td>";
    $a .= "</tr>";
  }
  $a .= "</table>";
  echo $a;
}

public function get_max_no(){
  $this->db->select_max('nno', 'max_nno');
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $query =$this->db->get($this->t_dispatch_sum);

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
      't_dispatch_det.qty as dqty',
      't_dispatch_sum.officer',
      't_dispatch_sum.ref_no',
      't_dispatch_sum.store_to',
      't_dispatch_sum.store_from',
      't_dispatch_sum.memo',
      't_dispatch_sum.action_date'
      ));

  $this->db->from('qry_current_stock');
  $this->db->join('t_dispatch_det','t_dispatch_det.code=qry_current_stock.item');
  $this->db->join('t_dispatch_sum','t_dispatch_det.nno=t_dispatch_sum.nno');
      //$this->db->where('t_dispatch_det.type',$_POST['type']);
  $this->db->where('t_dispatch_det.nno',$_POST['id']);
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

  $sql="SELECT d.code, m.`description`,m.`model`,d.qty,b.`max_price`,d.batch_no
        FROM `t_dispatch_det` d
        JOIN m_item m ON m.code = d.code
        JOIN t_item_batch b ON b.`item` = d.code AND b.`batch_no` = d.batch_no
        WHERE d.cl='".$this->sd['cl']."' 
        AND d.bc='".$this->sd['branch']."' 
        AND d.nno='".$_POST['qno']."'";
  $query=$this->db->query($sql);
  $r_detail['det']=$query->result();  

  $sql="SELECT s.* , s1.`description` AS tstore_des, s2.`description` AS fstore_des, e.`name` AS e_name FROM t_dispatch_sum s
        JOIN m_stores s1 ON s1.`code` = s.store_to
        JOIN m_stores s2 ON s2.`code` = s.store_from
        JOIN m_employee e ON e.`code` = s.officer 
        WHERE s.cl='".$this->sd['cl']."' 
        AND s.bc='".$this->sd['branch']."' 
        AND s.nno='".$_POST['qno']."'";
  $query=$this->db->query($sql);
  $r_detail['sum']=$query->result();        

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
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where("serial_no",$serial);
  $this->db->where("item",$item);
  $query=$this->db->get("t_serial");

  if($query->num_rows()>0){
    return 1;
  }else{
    return 0;
  }
}




public function get_batch_serial_wise($item,$serial){
  $this->db->select("batch");
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where("item",$item);
  $this->db->where("serial_no",$serial);
  return $this->db->get('t_serial')->first_row()->batch; 
}



public function batch_item() {
  $sql = "SELECT qry_current_stock.`qty`,qry_current_stock.`batch_no`,m_item.`max_price` AS cost 
  FROM qry_current_stock JOIN m_item ON qry_current_stock.`item`=m_item.`code` WHERE qry_current_stock.`qty`>'0'
  AND qry_current_stock.`store_code`='$_POST[stores]' AND qry_current_stock.`item`='$_POST[search]'
  AND qry_current_stock.`cl`='".$this->sd['cl']."'
  AND qry_current_stock.`bc`='".$this->sd['branch']."'";

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

public function is_batch_item() {
  $this->db->select(array("batch_no", "qty"));
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



public function checkdelete()
{
  $id = $_POST['no'];

  $sql="SELECT *
  FROM `t_dispatch_sum` 
  WHERE (`t_dispatch_sum`.`nno` = '$id')
  LIMIT 25 ";   

  $query=$this->db->query($sql);

  if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
  }else{
    echo json_encode("2");
  }
}

public function delete_validation()
{
  $status=1;
  $no = $_POST['id'];

  $check_cancellation = $this->trans_cancellation->damage_update_status($no,13,'t_dispatch_sum','t_dispatch_det');
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
    if($this->user_permissions->is_delete('t_dispatch_note')){

      $delete_validation_status=$this->delete_validation();
      if($delete_validation_status==1){

        $no = $_POST['id'];
        $this->db->where('nno',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->update('t_dispatch_sum', array("is_cancel"=>1));

        $this->db->select(array('store_from'));
        $this->db->from('t_dispatch_sum');
        $this->db->where('nno',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $query_store = $this->db->get();

        $this->db->select(array('item','batch_no','serial_no'));
        $this->db->from('t_serial_movement');
        $this->db->where('trans_type',13);
        $this->db->where('trans_no',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $query1 = $this->db->get();

        foreach($query1->result() as $row)
        {
          $this->db->where('item',$row->item);
          $this->db->where('batch',$row->batch_no);
          $this->db->where('serial_no',$row->serial_no);
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->update('t_serial', array("store_code"=>$query_store->row()->store_from));
        }

        $this->load->model('trans_settlement');
        $this->trans_settlement->delete_item_movement('t_item_movement',13,$no);

        $this->db->where('trans_code',13);
        $this->db->where('trans_no',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->delete("t_item_movement_sub");

        $this->db->where('trans_type',13);
        $this->db->where('trans_no',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->delete("t_serial_movement");

        $this->utility->save_logger("CANCEL",13,$no,$this->mod);
        echo  $this->db->trans_commit();
      }
      else
      {
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



public function get_batch_qty(){
  $batch_no=$_POST['batch_no'];
  $store=$_POST['store'];
  $no=$_POST['hid'];
  $item=$_POST['code'];

  if(isset($_POST['hid']) && $_POST['hid']=="0"){
    $this->db->select(array('qty'));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->where("batch_no", $this->input->post("batch_no"));
    $this->db->where("store_code", $this->input->post('store'));
    $this->db->where("item", $this->input->post('code'));
    $query = $this->db->get("qry_current_stock");
  }else{
    $sql="SELECT SUM(qry_current_stock.`qty`)+SUM(c.qty) as qty 
          FROM (`qry_current_stock`)  
          INNER JOIN (SELECT qty,code,batch_no,cl,bc 
            FROM t_dispatch_det 
            WHERE  `batch_no` = '$batch_no'  
            AND  nno='$no' 
            AND `code` = '$item') c 
      ON c.code=qry_current_stock.`item` AND c.cl=qry_current_stock.cl AND c.bc=qry_current_stock.bc AND c.batch_no=qry_current_stock.batch_no
      WHERE qry_current_stock.`batch_no` = '$batch_no' 
      AND qry_current_stock.`store_code` = '$store' 
      AND `item` = '$item'
      AND qry_current_stock.cl='".$this->sd['cl']."'
      AND qry_current_stock.bc='".$this->sd['branch']."'";
      $query = $this->db->query($sql);
  }
  if($query->num_rows() > 0) {
    echo $query->first_row()->qty;
  }else{
    echo 0;
  }
}




}