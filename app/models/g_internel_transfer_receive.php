<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class g_internel_transfer_receive  extends CI_Model {
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



  public function base_details() {
    $a['id'] = $this->utility->get_max_no("g_t_internal_transfer_sum", "nno");
    $a['sub_max_no']= $this->utility->get_max_no_in_type_transfer("g_t_internal_transfer_sum","sub_no","request","64");

    $a['branch'] = $this->branch();
    $a['cluster'] = $this->cluster();
    $a['store'] = $this->store();
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
                  m.`cost`,
                  m.`price`,
                  q.`qty` AS cur, 
                  d.`qty` 
                FROM g_t_internal_transfer_order_sum s
                JOIN g_t_internal_transfer_order_det d ON d.`nno` = s.`nno`
                JOIN g_m_gift_voucher m ON m.`code` = d.`item_code`
                JOIN gift_qry_current_stock q ON q.`item`=d.`item_code`
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
          FROM g_t_internal_transfer_order_sum
          WHERE nno='".$_POST['order_no']."'";

    $query=$this->db->query($sql)->row()->status;

    echo $query;
    }


  public function reject(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 

      $sql="UPDATE g_t_internal_transfer_order_sum SET status='3' WHERE nno='".$_POST['order_no']."'";
      $query=$this->db->query($sql);

      echo $this->db->trans_commit();
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
    } 

  }

  public function validation() {
    $status  = 1;

    $this->sub_max_no=$this->utility->get_max_no_in_type_echo2_transfer("g_t_internal_transfer_sum","sub_no",$_POST['types'],"64");
    // $check_is_delete = $this->validation->check_is_cancel($this->max_no, 'g_t_internal_transfer_sum');
    // if ($check_is_delete != 1) {
    //   return "Internal transfer already deleted";
    // }
    // $chk_item_store_validation = $this->validation->check_item_with_store($_POST['store_from'], '0_');
    // if ($chk_item_store_validation != 1) {
    //   return $chk_item_store_validation;
    // }
    $serial_validation_status = $this->validation->serial_update_gift('0_', '2_','all_serial_');
    if ($serial_validation_status != 1) {
      return $serial_validation_status;
    }
    // $check_batch_validation = $this->utility->batch_updatee('0_', '1_', '2_');
    // if ($check_batch_validation != 1) {
    //   return $check_batch_validation;
    // }
    // $check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
    //   if($check_zero_value!=1){
    //     return $check_zero_value;
    // }
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
        $this->load->model("t_grn_sum");
         
          $execute=0;
          $subs="";

          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
              if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" &&  $_POST['3_'.$x] != ""){
                 
                $g_t_item_movement[]=array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>64,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['ddate'],
                  "qty_in"=>$_POST['2_'.$x],
                  "qty_out"=>0,
                  "store_code"=>$_POST['store_from'],
                  "sales_price"=>$_POST['3_' . $x],
                  "cost"=>$_POST['c_' . $x],
                );
                //var_dump($_POST['v_store']);
                $g_t_item_movement_out[]=array(
                  "cl"=>$_POST['hid_cl'],
                  "bc"=>$_POST['hid_bc'],
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>64,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['ddate'],
                  "qty_in"=>0,
                  "qty_out"=>$_POST['2_'.$x],
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
                  "item_cost" => $_POST['c_'. $x],
                  "trans_code" =>64,
                  
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
          "trans_code" =>64,
          "trans_no" =>$this->max_no,
          "ref_trans_code" =>62,
          "ref_trans_no" =>$_POST['request_no'],
          "ref_sub_no"=>$_POST['request_no'],
          "ref_type"=>$_POST['types'],
          "sub_no"=>$this->sub_max_no,
          "type"=>$_POST['types'],
          "vehicle"=>$_POST['vehicle'],
          "location_store"=>$_POST['v_store']

        );

        $status=array(
          "status" => "R", //-- status update to received
        );

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){

          if($this->user_permissions->is_add('g_internel_transfer_receive')){  
            if($_POST['df_is_serial']=='1'){
              $this->serial_save_transfer();
              $this->serial_save();    
            }
           

            $this->db->insert("g_t_internal_transfer_sum", $data);                        
            if(count($b)){$this->db->insert_batch("g_t_internal_transfer_det",$b);}

            if(count($g_t_item_movement)){ $this->db->insert_batch("g_t_item_movement", $g_t_item_movement);}
            if(count($g_t_item_movement_out)){ $this->db->insert_batch("g_t_item_movement", $g_t_item_movement_out);}
            
            $this->db->where("sub_no", $_POST['request_no']);
            $this->db->where("cl", $_POST['hid_cl']);
            $this->db->where("bc", $_POST['hid_bc']);
            $this->db->where("type", $_POST['types']);
            $this->db->update("g_t_internal_transfer_sum", $status);   

            $this->utility->save_logger("SAVE",64,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }   
        }else{
          if($this->user_permissions->is_edit('g_internel_transfer_receive')){
            if($_POST['df_is_serial']=='1'){
              $this->serial_save_transfer();
              $this->serial_save();    
            }
           
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
                "trans_code" =>64,
                "trans_no" =>$this->max_no,
                "ref_trans_code" =>64,
                "ref_trans_no" =>$_POST['request_no'],
                "ref_sub_no"=>$_POST['request_no'],
                "ref_type"=>$_POST['types'],
                "sub_no"=>$this->sub_max_no,
                "type"=>$_POST['types'],
                "vehicle"=>$_POST['vehicle'],
                "location_store"=>$_POST['v_store']
              );

              $this->db->where("trans_code", 64);
              $this->db->where("trans_no", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("g_t_item_movement");

              $this->db->where("trans_code", 64);
              $this->db->where("trans_no", $_POST['hid']);
              $this->db->where("cl", $_POST['hid_cl']);
              $this->db->where("bc", $_POST['hid_bc']);
              $this->db->delete("g_t_item_movement");

              $this->db->where("nno", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("g_t_internal_transfer_det");

              $this->db->where("nno", $_POST['hid']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("g_t_internal_transfer_sum", $data_update);

              $this->db->where("sub_no", $_POST['request_no']);
              $this->db->where("cl", $_POST['hid_cl']);
              $this->db->where("bc", $_POST['hid_bc']);
              $this->db->where("type", $_POST['types']);
              $this->db->update("g_t_internal_transfer_sum", $status);              

              $this->utility->save_logger("EDIT",64,$this->max_no,$this->mod);
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
              if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
                $serial = $_POST['all_serial_'.$x];
                $p=explode(",",$serial);
                for($i=0; $i<count($p); $i++){
                  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                    $g_t_seriall = array(
                        "cl" =>$this->sd['cl'],
                        "bc" =>$this->sd['branch'],
                        "trans_type" => 64,
                        "trans_no" => $this->max_no,
                        "date" => $_POST['date'],
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
                    $this->db->update("g_t_serial", $g_t_seriall);

                    $this->db->query("INSERT INTO g_t_serial_movement SELECT * FROM g_t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                    $g_t_serial_movement[] = array(
                        "cl" => $this->sd['cl'],
                        "bc" => $this->sd['branch'],
                        "trans_type" => 64,
                        "trans_no" => $this->max_no,
                        "item" => $_POST['0_'.$x],
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
                    $this->db->delete("g_t_serial_movement_out");

                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("item", $_POST['0_'.$x]);
                    $this->db->where("serial_no", $p[$i]);
                    $this->db->delete("g_t_serial_movement_out");
                  }
                }
              }
            }
          }
        }
            //$this->db->insert_batch("t_serial_movement", $t_serial_movement); 
        }else{
          $g_t_serial = array(           
            "cl" =>$this->sd['cl'],
            "bc" =>$this->sd['branch'],
            "trans_type" => 64,
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
           $this->db->where("out_doc", 64);
           $this->db->update("g_t_serial", $g_t_serial);

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 64);
           $this->db->where("cl", $_POST['hid_cl']);
           $this->db->where("bc", $_POST['hid_bc']);
           $query = $this->db->get("g_t_serial_movement_out");

           foreach ($query->result() as $row) {
           $this->db->query("INSERT INTO g_t_serial_movement SELECT * FROM g_t_serial_movement_out WHERE item='$row->item' AND serial_no='$row->serial_no'");
           $this->db->where("item", $row->item);
           $this->db->where("serial_no", $row->serial_no);
           $this->db->where("cl", $_POST['hid_cl']);
           $this->db->where("bc", $_POST['hid_bc']);
           $this->db->delete("g_t_serial_movement_out");
           }

           $this->db->where("cl", $this->sd['cl']);
           $this->db->where("bc", $this->sd['branch']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 64);
           $this->db->delete("g_t_serial_movement");

           $this->db->where("cl", $_POST['hid_cl']);
           $this->db->where("bc", $_POST['hid_bc']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 62);
           $this->db->delete("g_t_serial_movement_out");


          for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x])) {
              if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
                if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
                  $serial = $_POST['all_serial_'.$x];
                  $p=explode(",",$serial);
                  for($i=0; $i<count($p); $i++){

                    $g_t_seriall = array(
                        "cl" =>$this->sd['cl'],
                        "bc" =>$this->sd['branch'],
                        "trans_type" => 64,
                        "trans_no" => $this->max_no,
                        "date" => $_POST['date'],
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
                    $this->db->update("g_t_serial", $g_t_seriall);

                    $this->db->query("INSERT INTO g_t_serial_movement SELECT * FROM g_t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                    $g_t_serial_movement[] = array(
                         "cl" => $this->sd['cl'],
                         "bc" => $this->sd['branch'],
                         "trans_type" => 64,
                         "trans_no" => $this->max_no,
                         "item" => $_POST['0_'.$x],
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
                    $this->db->delete("g_t_serial_movement_out");

                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("item", $_POST['0_'.$x]);
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


    public function serial_save_transfer() {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        for ($x = 0; $x < 25; $x++) {
          if (isset($_POST['0_' . $x])) {
            if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
              if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
                $serial = $_POST['all_serial_'.$x];
                $p=explode(",",$serial);
                for($i=0; $i<count($p); $i++){
                  if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

                    $g_t_seriall = array(
                        "cl" =>$_POST['hid_cl'],
                        "bc" =>$_POST['hid_bc'],
                        "trans_type" => 64,
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
                    $this->db->where('serial_no', $p[$i]);
                    $this->db->where("item",$_POST['0_'.$x]);
                    $this->db->update("g_t_serial", $g_t_seriall);

                    $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                    $g_t_serial_movement_out[] = array(
                        "cl" => $_POST['hid_cl'],
                        "bc" => $_POST['hid_bc'],
                        "trans_type" => 64,
                        "trans_no" => $this->max_no,
                        "item" => $_POST['0_'.$x],
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
                    $this->db->delete("g_t_serial_movement");

                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("item", $_POST['0_'.$x]);
                    $this->db->where("serial_no", $p[$i]);
                    $this->db->delete("g_t_serial_movement");
                  }
                }
              }
            }
          }
        }
            //$this->db->insert_batch("t_serial_movement", $t_serial_movement); 
        }else{
          $g_t_serial = array(           
            "cl" =>$_POST['hid_cl'],
            "bc" =>$_POST['hid_bc'],
            "trans_type" => 64,
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
           $this->db->where("out_doc", 64);
           $this->db->update("g_t_serial", $g_t_serial);

           $this->db->select(array('item', 'serial_no'));
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 64);
           $this->db->where("cl", $_POST['hid_cl']);
           $this->db->where("bc", $_POST['hid_bc']);
           $query = $this->db->get("g_t_serial_movement");

           foreach ($query->result() as $row) {
           $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='$row->item' AND serial_no='$row->serial_no'");
           $this->db->where("item", $row->item);
           $this->db->where("serial_no", $row->serial_no);
           $this->db->where("cl", $_POST['hid_cl']);
           $this->db->where("bc", $_POST['hid_bc']);
           $this->db->delete("g_t_serial_movement");
           }

           $this->db->where("cl", $_POST['hid_cl']);
           $this->db->where("bc", $_POST['hid_bc']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 64);
           $this->db->delete("g_t_serial_movement_out");

           $this->db->where("cl", $_POST['hid_cl']);
           $this->db->where("bc", $_POST['hid_bc']);
           $this->db->where("trans_no", $this->max_no);
           $this->db->where("trans_type", 62);
           $this->db->delete("g_t_serial_movement");


          for ($x = 0; $x < 25; $x++) {
            if (isset($_POST['0_' . $x])) {
              if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
                if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
                  $serial = $_POST['all_serial_'.$x];
                  $p=explode(",",$serial);
                  for($i=0; $i<count($p); $i++){

                    $g_t_seriall = array(
                        "cl" =>$_POST['hid_cl'],
                        "bc" =>$_POST['hid_bc'],
                        "trans_type" => 64,
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
                    $this->db->where('serial_no', $p[$i]);
                    $this->db->where("item", $_POST['0_'.$x]);
                    $this->db->update("g_t_serial", $g_t_seriall);

                    $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."'");

                    $g_t_serial_movement_out[] = array(
                         "cl" => $_POST['hid_cl'],
                         "bc" => $_POST['hid_bc'],
                         "trans_type" => 64,
                         "trans_no" => $this->max_no,
                         "item" => $_POST['0_'.$x],
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
                    $this->db->delete("g_t_serial_movement");

                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("item", $_POST['0_'.$x]);
                    $this->db->where("serial_no", $p[$i]);
                    $this->db->delete("g_t_serial_movement");
                  }
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



            
  public function get_item() {
  

    $sql   = "SELECT description, item, cost, qty  FROM gift_qry_current_stock  WHERE store_code='$_POST[stores]' AND item='".$_POST['code']."'  
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

    $id= $_POST['max_no'];

    // $sql="SELECTs nno, ddate, store, ref_no, to_bc, note, order_no, is_cancel, to_bc, m_branch.`cl` as t_cl
    //       FROM g_t_internal_transfer_sum
    //       JOIN m_branch ON m_branch.`bc` = g_t_internal_transfer_sum.`to_bc`
    //       WHERE nno = '$id' AND g_t_internal_transfer_sum.cl = '".$this->sd['cl']."' AND g_t_internal_transfer_sum.bc ='".$this->sd['branch']."' AND trans_code='64'";

    $sql="SELECT a.nno AS receive_no,
                a.`ddate`AS receive_date,
                a.`ref_no`AS receive_ref_no,
                a.`store`AS receive_store,
                a.`is_cancel` AS is_cancel,
                a.`note` AS issue_note,
                b.`nno` AS issue_no,
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
                s.description as location_des
          FROM  g_t_internal_transfer_sum a 
          JOIN g_t_internal_transfer_sum b ON a.`ref_trans_code`=b.`trans_code` AND a.ref_sub_no = b.`sub_no` AND a.bc=b.`to_bc`
          JOIN m_cluster m ON m.`code` = b.`cl`
          JOIN m_branch mb ON mb.`bc` = b.`bc`
          JOIN m_stores s ON s.`code` = a.`location_store`
          JOIN m_vehicle_setup v ON v.`code` = a.vehicle
          WHERE a.sub_no = '$id' 
                AND a.trans_code='64'
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
            d.`item_code`,
            d.`item_cost`,
            i.`price`,
            d.`qty`,
            q.`qty` AS cur, 
            i.`description`
          FROM g_t_internal_transfer_det d
          JOIN gift_qry_current_stock q ON q.`item` = d.`item_code`
          JOIN g_t_internal_transfer_sum s ON s.`nno` = d.`nno` AND s.cl = d.cl AND s.`bc` = s.`bc` AND s.`trans_code` = d.`trans_code` 
          JOIN g_m_gift_voucher i ON i.`code` = d.`item_code`
          WHERE d.sub_no = '$id' AND s.type='".$_POST['type']."'
                AND d.cl='".$this->sd['cl']."' 
                AND d.bc='".$this->sd['branch']."' 
                AND s.trans_code='64'
          GROUP BY d.`item_code`
    ";
    
    $query2 = $this->db->query($sql2);


    if ($query2->num_rows() > 0) {
      
      $a['det'] = $query2->result();
    } else {
      $x = 2;
    }

    
    $this->db->select(array('g_t_serial.item', 'g_t_serial.serial_no'));
    $this->db->from('g_t_serial_movement');
    $this->db->join('g_t_serial', 'g_t_serial.serial_no=g_t_serial_movement.serial_no');
    $this->db->join('g_t_internal_transfer_sum', 'g_t_serial_movement.trans_no=g_t_internal_transfer_sum.nno');
    $this->db->where('g_t_serial_movement.trans_type', "64");
    $this->db->where('g_t_serial_movement.trans_no', $ids);
    $this->db->where('g_t_internal_transfer_sum.cl', $this->sd['cl']);
    $this->db->where('g_t_internal_transfer_sum.bc', $this->sd['branch']);
    $this->db->group_by("g_t_serial.serial_no");
    $query3 = $this->db->get();

    $a['serial'] = $query3->result();
   


    if ($x == 0) {
      echo json_encode($a);
    } else {
      echo json_encode($x);
    }
  }

public function check_issue_transfer(){
  $sql="SELECT status FROM g_t_internal_transfer_sum WHERE trans_code='62' AND type='".$_POST['type']."' AND sub_no = '".$_POST['issue_no']."' AND cl='".$_POST['cl']."' AND bc='".$_POST['bc']."' ";
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

        $r_detail['org_print'] = $_POST['org_print'];

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

        /*$sqll = "SELECT b.`name` as bc , l.`description` as cl, c.name as to_bc, c.description as to_cl
                FROM g_t_internal_transfer_sum s
                JOIN m_branch b ON s.`bc` = b.`bc` 
                JOIN m_cluster l ON l.`code` = s.`cl` 
                JOIN(SELECT m_branch.`name`, m_cluster.description , nno FROM g_t_internal_transfer_sum
                     JOIN m_branch ON m_branch.`bc` = g_t_internal_transfer_sum.`to_bc`
                     JOIN m_cluster ON m_cluster.`code` = g_t_internal_transfer_sum.to_cl) c ON c.nno = s.nno 
                WHERE s.nno ='".$_POST['qno']."' AND  s.`trans_code` ='64'";*/
        $sqll="SELECT m.`description` AS cl,
                      mb.`name` AS bc
                FROM  g_t_internal_transfer_sum a 
                JOIN g_t_internal_transfer_sum b ON a.`ref_trans_code`=b.`trans_code` AND a.ref_sub_no = b.`sub_no` AND a.bc = b.to_bc
                JOIN m_cluster m ON m.`code` = b.`cl`
                JOIN m_branch mb ON mb.`bc` = b.`bc`
          WHERE a.nno = '".$_POST['qno']."' 
                AND a.trans_code='64'
                -- AND a.type='branch'
                AND a.cl = '".$this->sd['cl']."' 
                AND a.bc = '".$this->sd['branch']."' 
                LIMIT 1";

        $rr=$this->db->query($sqll);
        $r_detail['sum'] = $rr->result();
       
             $sql="SELECT `g_t_internal_transfer_det`.`item_code` AS code,
                     `g_t_internal_transfer_det`.`qty`,
                    `g_t_internal_transfer_det`.item_cost,
                    `g_m_gift_voucher`.`description`,
                    `g_t_internal_transfer_det`.item_cost * `g_t_internal_transfer_det`.`qty` AS amount 
            FROM (`g_t_internal_transfer_det`) 
            JOIN `g_m_gift_voucher` ON `g_m_gift_voucher`.`code` = `g_t_internal_transfer_det`.`item_code` 
            WHERE `g_t_internal_transfer_det`.`cl` = '".$this->sd['cl']."' 
            AND `g_t_internal_transfer_det`.`bc` = '".$this->sd['branch']."'
            AND `g_t_internal_transfer_det`.`nno` = '".$_POST['qno']."'  
            GROUP BY g_t_internal_transfer_det.`item_code`
            ORDER BY `g_t_internal_transfer_det`.`auto_no` ASC" ;



      /*  $sql="SELECT * from m_item limit 25
        ";*/

        $query = $this->db->query($sql);
        $r_detail['items'] = $this->db->query($sql)->result();

        $this->db->SELECT(array('serial_no','item'));
        $this->db->FROM('g_t_serial_movement');
        $this->db->WHERE('g_t_serial_movement.cl', $this->sd['cl']);
        $this->db->WHERE('g_t_serial_movement.bc', $this->sd['branch']);
        $this->db->WHERE('g_t_serial_movement.trans_type','64');
        $this->db->WHERE('g_t_serial_movement.trans_no',$_POST['qno']);
        $this->db->group_by('serial_no');
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
    $this->db->delete("t_damage_det");

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

  public function get_batch_serial_wise($item, $serial) {
    if ($_POST['df_is_serial'] == '1') {
      $this->db->select("batch");
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where("item", $item);
      $this->db->where("serial_no", $serial);
      //return $this->db->get('t_serial')->first_row()->batch+1;

      $bb=1;
      $query=$this->db->get('g_t_serial')->result();
      foreach ($query as $a){
        $bb = $a->batch;
      }
      return $bb+1;

    }
  }

  

  public function checkdelete(){
    $id2 = $_POST['hid_nno'];
   
    $sql="SELECT *
    FROM `g_t_internal_transfer_sum` 
    WHERE `g_t_internal_transfer_sum`.`trans_no` = '$id2' 
    AND cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."' 
    AND trans_code='64'
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

    // $check_cancellation = $this->trans_cancellation->damage_update_status($no,64,'t_internal_transfer_sum','t_internal_transfer_det');
    // if ($check_cancellation != 1) {
    //   return $check_cancellation;
    // }
    return $status;
  }

  public function delete(){

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errFile); 
    }
    set_error_handler('exceptionThrower'); 
    try {
      $bc=$this->sd['branch'];
      $cl=$this->sd['cl'];
      $trans_no=$_POST['hid_nno'];

      if($this->user_permissions->is_delete('g_internel_transfer_receive')){
      $delete_validation_status=$this->delete_validation();
      if($delete_validation_status==1){
    
          $this->db->where('cl',$cl);
          $this->db->where('bc',$bc);
          $this->db->where('trans_code',64);
          $this->db->where('trans_no',$trans_no);
          $this->db->delete('g_t_item_movement');

                   
          $g_t_serial = array(
            "cl" =>$_POST['hid_cl'],
            "bc" =>$_POST['hid_bc'],
            "trans_type" =>65,
            "trans_no" =>$trans_no,
            "out_doc" => 64,
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
         $this->db->where("trans_type", 64);
         $this->db->update("g_t_serial", $g_t_serial);


         $this->db->where("cl", $_POST['hid_cl']);
         $this->db->where("bc", $_POST['hid_bc']);
         $this->db->where("sub_no", $_POST['hid_nno']);
         $this->db->where("trans_code", 64);
         $this->db->update("g_t_internal_transfer_sum", $update_status);

         $this->db->select(array('item', 'serial_no'));
         $this->db->where("trans_no", $trans_no);
         $this->db->where("trans_type", '64');
         $this->db->where("cl", $this->sd['cl']);
         $this->db->where("bc", $this->sd['branch']);
         $query = $this->db->get("g_t_serial_movement");

         foreach ($query->result() as $row) {
         $this->db->query("INSERT INTO g_t_serial_movement_out SELECT * FROM g_t_serial_movement WHERE item='$row->item' AND serial_no='$row->serial_no' ");
         $this->db->where("item", $row->item);
         $this->db->where("serial_no", $row->serial_no);
         // $this->db->where("cl", $this->sd['cl']);
         // $this->db->where("bc", $this->sd['branch']);
         $this->db->delete("g_t_serial_movement");


         $this->db->where("item", $row->item);
         $this->db->where("serial_no", $row->serial_no);
         $this->db->where("cl", $this->sd['cl']);
         $this->db->where("bc", $this->sd['branch']);
         $this->db->where("trans_type", '64');
         $this->db->where("trans_no", $trans_no);
         $this->db->delete("g_t_serial_movement_out");
         }      

        $data=array('is_cancel'=>'1');
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code',64);
        $this->db->where('trans_no',$trans_no);
        $this->db->update('g_t_internal_transfer_sum',$data);

        // $status=array("status" => 1);
        // $this->db->where("nno", $_POST['order_no']);
        // $this->db->update("t_internal_transfer_order_sum", $status);   

        $this->utility->save_logger("CANCEL",64,$_POST['trans_no'],$this->mod);
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

    $sql="SELECT d.`nno`,
                d.`item_code`,
                m.`description`,
                m.`cost`,
                m.`price`,
                q.`qty` AS cur,
                d.`qty` 
          FROM g_t_internal_transfer_sum s 
          JOIN g_t_internal_transfer_det d ON d.`nno` = s.`nno`  AND s.cl = d.cl AND s.bc = d.bc
          JOIN g_m_gift_voucher m ON m.`code` = d.`item_code` 
          JOIN gift_qry_current_stock q ON q.`item` = d.`item_code` 
          WHERE s.sub_no = '".$_POST['issue_no']."'  AND s.cl='".$_POST['cl']."' AND s.bc='".$_POST['bc']."' AND s.type = '".$_POST['type']."'
          AND s.trans_code='62'
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
            WHERE t.is_cancel ='0' AND t.trans_code='64' AND to_bc = '".$this->sd['branch']."' AND status='P' AND
            ( t.`nno`LIKE '%$_POST[search]%' OR m.`name` LIKE '%$_POST[search]%' ) AND type = '".$_POST['type']."'
      ";*/

      $sql="SELECT t.nno as max_no,
                t.cl,
                t.bc,
                t.`sub_no` AS nno,
                m.`name`,
                c.`description`,
                c.code,
                t.note,
                t.store,
                t.`vehicle`,
                v.description AS v_des,
                t.location_store,
                s.description AS location_name 
                FROM g_t_internal_transfer_sum t 
                JOIN m_branch m ON m.`bc` = t.`bc` 
                JOIN m_cluster c ON c.`code` = t.`cl` 
                LEFT JOIN m_vehicle_setup v ON v.`code` = t.`vehicle` 
                JOIN m_stores s ON s.code = t.location_store  AND s.cl = t.cl AND s.bc = t.bc 
                WHERE t.is_cancel = '0' 
                AND t.trans_code = '62'  AND to_bc = '".$this->sd['branch']."' AND status='P' AND
                ( t.`nno`LIKE '%$_POST[search]%' OR m.`name` LIKE '%$_POST[search]%' ) AND type = '".$_POST['type']."'";

      $query = $this->db->query($sql);
      $a = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Transfer Number</th>";
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
      $a .= "</tr>";
    }
      $a .= "</table>";
      echo $a;
  }
}
?>