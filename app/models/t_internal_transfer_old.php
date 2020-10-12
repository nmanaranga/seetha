<?php
// 1=pending,2=issued,3=rejected t_internal_transfer_sum table, status field
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class t_internal_transfer extends CI_Model {
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
    $this->max_no = $this->utility->get_max_no("t_internal_transfer_sum", "nno");
    
  }

  public function base_details(){
    $a['id'] = $this->utility->get_max_no("t_internal_transfer_sum", "nno");
    $a['sub_max_no']= $this->utility->get_max_no_in_type_transfer("t_internal_transfer_sum","sub_no","request","42");
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
                LEFT JOIN t_internal_transfer_order_det d ON d.`nno` = s.`nno` AND d.`cl` = s.`cl` AND d.`bc` = s.`bc`
                LEFT JOIN m_item m ON m.`code` = d.`item_code`
                LEFT JOIN qry_current_stock q ON q.`item` = m.`code`
                LEFT JOIN t_item_batch ON t_item_batch.`item` = m.`code` 
                WHERE s.sub_no = '$order'  AND s.`to_bc`='".$this->sd['branch']."' AND s.is_cancel !='1' AND d.type ='".$_POST['type']."'
                AND s.`cl` = '".$_POST['get_cl']."' AND s.`bc` = '".$_POST['get_bc']."'
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
          WHERE sub_no='".$_POST['order_no']."' AND to_bc='".$this->sd['branch']."' AND type ='".$_POST['type']."'";

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
    $this->sub_max_no=$this->utility->get_max_no_in_type_echo2_transfer("t_internal_transfer_sum","sub_no",$_POST['types'],"42");
    
    $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_internal_transfer_sum');
    if ($check_is_delete != 1) {
      return "Internal transfer already deleted";
    }
    $chk_item_store_validation = $this->validation->check_item_with_store($_POST['store_from'], '0_');
    if ($chk_item_store_validation != 1) {
      return $chk_item_store_validation;
    }
    $serial_validation_status = $this->validation->serial_update('0_', '2_','all_serial_');
    if ($serial_validation_status != 1) {
      return $serial_validation_status;
    }
    // $check_batch_validation = $this->utility->batch_updatee('0_', '1_', '2_');
    // if ($check_batch_validation != 1) {
    //   return $check_batch_validation;
    // }
    $check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
      if($check_zero_value!=1){
        return $check_zero_value;
    }
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
            if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['1_'.$x],$_POST['3_'.$x])){
              if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['1_'.$x] != "" && $_POST['3_'.$x] != ""){
               
                $t_item_movement[]=array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],                  
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>42,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['ddate'],
                  "qty_in"=>0,
                  "qty_out"=>$_POST['2_'.$x],
                  "store_code"=>$_POST['store_from'],
                  "avg_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                  "batch_no"=>$_POST['1_'.$x],
                  "sales_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                  "last_sales_price"=>$this->utility->get_min_price($_POST['0_' . $x]),
                  "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                );

                $t_item_movement_out[]=array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],                  
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>42,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['ddate'],
                  "qty_in"=>$_POST['2_'.$x],
                  "qty_out"=>0,
                  "store_code"=>$_POST['v_store'],
                  "avg_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                  "batch_no"=>$_POST['1_'.$x],
                  "sales_price"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                  "last_sales_price"=>$this->utility->get_min_price($_POST['0_' . $x]),
                  "cost"=>$this->utility->get_cost_price($_POST['0_' . $x]),
                );
              }
          }     
        }

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
                  "qty"=>$_POST['2_'.$x],
                  "item_cost" => $this->utility->get_cost_price($_POST['0_' . $x]),
                  "trans_code" =>42,
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
          "trans_code" => 42,
          "trans_no" => $this->max_no,
          "vehicle" => $_POST['vehicle'],
          "location_store" => $_POST['v_store']
          //"trans_no" =>$this->sub_max_no
        );


        $status=array(
          "status" => 2, //-- status update to issued
        );


        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_internal_transfer')){  
            if($_POST['df_is_serial']=='1'){
              $this->serial_save(); 
              $this->serial_save_transfer();   
            }
            $this->save_sub_items();

            $this->db->insert('t_internal_transfer_sum',$data);
            if(count($b)){$this->db->insert_batch("t_internal_transfer_det",$b);}
            if(count($t_item_movement)){ $this->db->insert_batch("t_item_movement", $t_item_movement);}
            if(count($t_item_movement_out)){ $this->db->insert_batch("t_item_movement", $t_item_movement_out);}

            $this->db->where("to_bc", $_POST['f_branch']);
            $this->db->where("type", $_POST['types']);
            $this->db->where("sub_no", $_POST['order_no']);
            $this->db->update("t_internal_transfer_order_sum", $status);   

            $this->utility->save_logger("SAVE",42,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }   
        }else{

          
          
          if($this->user_permissions->is_edit('t_internal_transfer')){
            $check_cancellation = $this->check_transfer_issue_received($_POST['id'],$_POST['to_cluster'],$_POST['t_branch']);
            if ($check_cancellation == 1) {           
              if($_POST['df_is_serial']=='1'){
                $this->serial_save();   
                $this->serial_save_transfer(); 
              }
              $this->save_sub_items();
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

              $this->db->where("trans_code", 42);
              $this->db->where("trans_no", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_item_movement");

              $this->db->where("nno", $_POST['hid']);
              $this->db->where("type",$_POST['types']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_internal_transfer_det");

              $this->db->where("nno", $_POST['hid']);
              $this->db->where("type",$_POST['types']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_internal_transfer_sum", $data_update);

              if(count($b)){$this->db->insert_batch("t_internal_transfer_det",$b);}
              if(count($t_item_movement)){ $this->db->insert_batch("t_item_movement", $t_item_movement);}
              if(count($t_item_movement_out)){ $this->db->insert_batch("t_item_movement", $t_item_movement_out);}
              
              $this->db->where("to_bc", $_POST['f_branch']);
              $this->db->where("type", $_POST['types']);
              $this->db->where("sub_no", $_POST['order_no']);
              $this->db->update("t_internal_transfer_order_sum", $status);   

              $this->utility->save_logger("EDIT",42,$this->max_no,$this->mod);
              echo $this->db->trans_commit();   
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
    
    public function serial_save() {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x])) {
            if ($_POST['0_' . $x] != "" || !empty($_POST['0_'.$x])) {
            if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){
                  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                    $t_seriall = array(                        
                        "out_doc" => 42,
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
                        "trans_type" => 42,
                        "trans_no" => $this->max_no,
                        "item" => $_POST['0_'.$x],
                        "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
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
           
           "out_doc" => "",
           "out_no" => "",
           "out_date" => $_POST['ddate'],
           "available" => '1'
           );

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc", $this->sd['branch']);
           $this->db->where("out_no", $this->max_no);
           $this->db->where("out_doc", 42);
           $this->db->update("t_serial", $t_serial);

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 42);
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
           $this->db->where("trans_type", 42);
           $this->db->delete("t_serial_movement");

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc",$this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 42);
           $this->db->delete("t_serial_movement_out");


           for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x])) {
              if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
               if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
                    $serial = $_POST['all_serial_'.$x];
                    $p=explode(",",$serial);
                    for($i=0; $i<count($p); $i++){

                           $t_seriall = array(                              
                               "out_doc" => 42,
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
                               "trans_type" => 42,
                               "trans_no" => $this->max_no,
                               "item" => $_POST['0_'.$x],
                               "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
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
                           $this->db->where("item", $_POST['0_'.$x]);
                           $this->db->where("serial_no", $p[$i]);
                           $this->db->delete("t_serial_movement");
                        }
                        $execute = 1;
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



    public function serial_save_transfer() {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x])) {
            if ($_POST['0_' . $x] != "" || !empty($_POST['0_'.$x])) {
            if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
              $serial = $_POST['all_serial_'.$x];
              $p=explode(",",$serial);
              for($i=0; $i<count($p); $i++){
                  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                    $t_seriall = array(                        
                        "out_doc" => 42,
                        "store_code" =>$_POST['v_store'],
                        "out_no" => $this->max_no,
                        "out_date" => $_POST['date'],
                        "available" => '1'
                    );
                    

                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where('serial_no', $p[$i]);
                    $this->db->where("item", $_POST['0_'.$x]);
                    $this->db->update("t_serial", $t_seriall);

                    $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                    $t_serial_movement_in[] = array(
                        "cl" => $this->sd['cl'],
                        "bc" => $this->sd['branch'],
                        "trans_type" => 42,
                        "trans_no" => $this->max_no,
                        "item" => $_POST['0_'.$x],
                        "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
                        "serial_no" => $p[$i],
                        "qty_in" =>1,
                        "qty_out" => 0,
                        "cost" => $_POST['3_' . $x],
                        "store_code" => $_POST['v_store'],
                        "computer" => $this->input->ip_address(),
                        "oc" => $this->sd['oc'],
                    );

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

        }else{
           $t_serial = array(
           
           "out_doc" => "",
           "out_no" => "",
           "out_date" => $_POST['ddate'],
           "available" => '0'
           );

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc", $this->sd['branch']);
           $this->db->where("out_no", $this->max_no);
           $this->db->where("out_doc", 42);
           $this->db->update("t_serial", $t_serial);

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 42);
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
           $this->db->where("bc", $this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 42);
           $this->db->delete("t_serial_movement_out");

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc",$this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 42);
           $this->db->delete("t_serial_movement");


           for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x])) {
              if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
               if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
                    $serial = $_POST['all_serial_'.$x];
                    $p=explode(",",$serial);
                    for($i=0; $i<count($p); $i++){

                           $t_seriall = array(                              
                               "out_doc" => 42,
                               "out_no" => $this->max_no,
                               "store_code" =>$_POST['v_store'],
                               "out_date" => $_POST['ddate'],
                               "available" => '1'
                           );

                           $this->db->where("cl", $this->sd['cl']);
                           $this->db->where("bc", $this->sd['branch']);
                           $this->db->where('serial_no', $p[$i]);
                           $this->db->where("item", $_POST['0_'.$x]);
                           $this->db->update("t_serial", $t_seriall);

                           $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                           $t_serial_movement_in[] = array(
                               "cl" => $this->sd['cl'],
                               "bc" => $this->sd['branch'],
                               "trans_type" => 42,
                               "trans_no" => $this->max_no,
                               "item" => $_POST['0_'.$x],
                               "batch_no" => $this->get_batch_serial_wise($_POST['0_'.$x], $p[$i]),
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
                           $this->db->where("item", $_POST['0_'.$x]);
                           $this->db->where("serial_no", $p[$i]);
                           $this->db->delete("t_serial_movement_out");
                        }
                        $execute = 1;
                    }
                } 
            }
        }
    }

        if($_POST['hid'] == "0" || $_POST['hid'] == "") {
            if(isset($t_serial_movement_in)) {
                if(count($t_serial_movement_in)) {
                    $this->db->insert_batch("t_serial_movement", $t_serial_movement_in);
                }
            }
        }else{
            if(isset($t_serial_movement_in)) {
                if(count($t_serial_movement_in)) {
                    $this->db->insert_batch("t_serial_movement", $t_serial_movement_in);
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
                        "trans_code" => 42,
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

                        $t_sub_item_movement_out[] = array(
                        "cl" => $this->sd['cl'],
                        "bc" => $this->sd['branch'],
                        "item" => $item_code,
                        "sub_item"=>$row->sub_item,
                        "trans_code" => 42,
                        "trans_no" => $max,
                        "ddate" => $date,
                        "qty_in" => $total_qty,
                        "qty_out" => 0,
                        "store_code" => $_POST['v_store'],
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
            if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
            if(isset($t_sub_item_movement_out)){if(count($t_sub_item_movement_out)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_out);}}
        }else{
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("trans_code", 42);
            $this->db->where("trans_no", $_POST['hid']);
            $this->db->delete("t_item_movement_sub");

            if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
            if(isset($t_sub_item_movement_out)){if(count($t_sub_item_movement_out)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement_out);}}
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
                 t_internal_transfer_sum.vehicle,
                 m_vehicle_setup.description as vehicle_des,
                 location_store
          FROM t_internal_transfer_sum
          JOIN m_branch ON m_branch.`bc` = t_internal_transfer_sum.`to_bc`
          JOIN m_vehicle_setup ON m_vehicle_setup.`code` = t_internal_transfer_sum.`vehicle`
          WHERE sub_no = '$sub_id' AND trans_code='42' AND t_internal_transfer_sum.cl = '".$this->sd['cl']."' AND t_internal_transfer_sum.bc ='".$this->sd['branch']."' AND t_internal_transfer_sum.type ='$type'";

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
              m.`description` , 
              m.`model` , 
              d.`batch_no` , 
              d.`item_cost` AS purchase_price,
              IFNULL(t_item_batch.`min_price`,m.`min_price`) AS min_price,
              IFNULL(t_item_batch.`max_price`,m.max_price) AS max_price,
              q.`qty` as cur,
              d.qty

            FROM t_internal_transfer_det d
            JOIN m_item m ON m.`code` = d.`item_code`
            left JOIN qry_current_stock q ON q.`item` = d.`item_code` AND q.batch_no = d.`batch_no` 
            left JOIN t_item_batch ON t_item_batch.`item` = m.`code` AND t_item_batch.`batch_no` = d.batch_no
            WHERE d.sub_no ='$sub_id' AND d.`trans_code` ='42' AND d.type='$type' AND d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."'
            GROUP BY d.`item_code`
    ";
    
    $query2 = $this->db->query($sql2);


    if ($query2->num_rows() > 0) {
      $a['det'] = $query2->result();
    } else {
      $x = 2;
    }

    $this->db->select(array('t_serial.item', 't_serial.serial_no'));
    $this->db->from('t_serial');
    $this->db->join('t_internal_transfer_sum', 't_serial.out_no=t_internal_transfer_sum.nno');
    $this->db->where('t_serial.out_doc', 42);
    $this->db->where('t_serial.out_no', $id);
    $this->db->where('t_internal_transfer_sum.cl', $this->sd['cl']);
    $this->db->where('t_internal_transfer_sum.bc', $this->sd['branch']);
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

        $sqll = "SELECT b.`name` as bc , l.`description` as cl, c.name as to_bc, c.description as to_cl
                FROM t_internal_transfer_sum s
                JOIN m_branch b ON s.`bc` = b.`bc` 
                JOIN m_cluster l ON l.`code` = s.`cl` 
                JOIN(SELECT m_branch.`name`, m_cluster.description , nno FROM t_internal_transfer_sum
                     JOIN m_branch ON m_branch.`bc` = t_internal_transfer_sum.`to_bc`
                     JOIN m_cluster ON m_cluster.`code` = t_internal_transfer_sum.to_cl) c ON c.nno = s.nno 
                WHERE s.nno ='".$_POST['qno']."' AND  s.`trans_code` ='42'";

        $rr=$this->db->query($sqll);
        $r_detail['sum'] = $rr->result();

        $sql="SELECT `t_internal_transfer_det`.`item_code` as code, 
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
        ";

        $query = $this->db->query($sql);
        $r_detail['items'] = $this->db->query($sql)->result();

        $this->db->SELECT(array('serial_no','item'));
        $this->db->FROM('t_serial_movement_out');
        $this->db->WHERE('t_serial_movement_out.cl', $this->sd['cl']);
        $this->db->WHERE('t_serial_movement_out.bc', $this->sd['branch']);
        $this->db->WHERE('t_serial_movement_out.trans_type','42');
        $this->db->WHERE('t_serial_movement_out.trans_no',$_POST['qno']);
        $r_detail['serial'] = $this->db->get()->result();

        $s="SELECT SUM(item_cost)*qty AS amount
            FROM t_internal_transfer_det
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

  

  public function checkdelete(){
    $id = $_POST['no'];
   
    $sql="SELECT *
    FROM `t_internal_transfer_sum` 
    WHERE `t_internal_transfer_sum`.`nno` = '$id'
    AND cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."' 
    AND trans_code='42'
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
          FROM t_internal_transfer_sum 
          WHERE cl='".$to_cl."' 
          AND bc='".$to_bc."' 
          AND ref_trans_code='42' 
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

      if($this->user_permissions->is_delete('t_internal_transfer')){
      $delete_validation_status=$this->delete_validation();
      if($delete_validation_status==1){
    
          $this->db->where('cl',$cl);
          $this->db->where('bc',$bc);
          $this->db->where('trans_code',42);
          $this->db->where('trans_no',$trans_no);
          $this->db->delete('t_item_movement');

          $this->db->where('cl',$cl);
          $this->db->where('bc',$bc);
          $this->db->where('trans_code',42);
          $this->db->where('trans_no',$trans_no);
          $this->db->delete('t_item_movement_sub');
         
         $t_serial = array(
         "out_doc" => "",
         "out_no" => "",
         "out_date" => $_POST['ddate'],
         "available" => '1'
         );

         $this->db->where("cl", $this->sd['cl']);
         $this->db->where("bc", $this->sd['branch']);
         $this->db->where("out_no", $trans_no);
         $this->db->where("out_doc", 42);
         $this->db->update("t_serial", $t_serial);

         $this->db->select(array('item', 'serial_no'));
         $this->db->where("trans_no", $trans_no);
         $this->db->where("trans_type", 42);
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
        $this->db->where('nno',$trans_no);
        $this->db->update('t_internal_transfer_sum',$data);

        $status=array("status" => 1);
        $this->db->where("nno", $_POST['order_no']);
        $this->db->update("t_internal_transfer_order_sum", $status);   

        $this->utility->save_logger("CANCEL",42,$_POST['trans_no'],$this->mod);
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









}
?>