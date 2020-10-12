<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class t_receipt_temp extends CI_Model
{

  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct()
  {
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['m_items'];
    $this->load->model('user_permissions');
    $this->max_no = $this->utility->get_max_no("t_receipt_temp_cheque_sum", "nno");
  }
  
  public function base_details()
  {
    $a['id'] = $this->utility->get_max_no("t_receipt_temp_cheque_sum", "nno");

    return $a;
  }

  public function validation(){

    $status  = 1;

    $check_zero_value=$this->validation->empty_net_value($_POST['tot_dr']);
    if($check_zero_value!=1){
      return $check_zero_value;
    }
    $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_receipt_temp_cheque_sum');
    if ($check_is_delete != 1) {
      return "Damages already deleted";
    }
    $check_validation_employee = $this->validation->check_is_employer($_POST['officer_id']);
    if ($check_validation_employee != 1) {
      return "Please selece valid officer";
    }
    if(isset($_POST['curr_acc'])==true){
      $check_validation_account = $this->validation->check_is_account($_POST['customer_id']);
      if ($check_validation_account != 1) {
        return "Please selece valid account";
      }
    }else{
      $check_validation_employee = $this->validation->check_is_customer($_POST['customer_id']);
      if ($check_validation_employee != 1) {
        return "Please selece valid customer";
      }
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
      $validation_status=$this->validation();
      if($validation_status==1){


        for($x = 0; $x<25; $x++){
          if(isset($_POST['0_'.$x],$_POST['3_'.$x],$_POST['4_'.$x],$_POST['6_'.$x])){
            if($_POST['0_'.$x] != "" && $_POST['3_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['6_'.$x] != ""){
              $det[]= array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "nno"=>$this->max_no,
                "bank"=>$_POST['0_'.$x],
                "branch"=>$_POST['1_'.$x],
                "account" => $_POST['3_'.$x],
                "cheque_no"=>$_POST['4_'.$x],
                "realize_date"=>$_POST['5_'.$x],
                "amount"=>$_POST['6_'.$x]
              );              
            }
          }
        }            

        if(!isset($_POST['curr_acc'])){$_POST['curr_acc']=0;}else{$_POST['curr_acc']=1;}
        $sum=array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->max_no,
          "ref_no" => $_POST['ref_no'],
          "date" => $_POST['date'],
          "customer" => $_POST['customer_id'],
          "customer_des" => $_POST['customer'],
          "employee" => $_POST['officer_id'],
          "remarks" => $_POST['remark'],
          "total" => $_POST['tot_dr'],
          "oc" => $this->sd['oc'],
          "curr_acc" => $_POST['curr_acc']
        );

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_receipt_temp')){ 

            $this->db->insert('t_receipt_temp_cheque_sum',$sum);
            if(isset($det)){
              if(count($det)){$this->db->insert_batch("t_receipt_temp_cheque_det",$det);}            
            }
            $this->utility->save_logger("SAVE",51,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }   
        }else{
          if($this->user_permissions->is_edit('t_receipt_temp')){

            $sum_update=array(
              "cl" => $this->sd['cl'],
              "bc" => $this->sd['branch'],
              "ref_no" => $_POST['ref_no'],
              "date" => $_POST['date'],
              "customer" => $_POST['customer_id'],
              "employee" => $_POST['officer_id'],
              "remarks" => $_POST['remark'],
              "total" => $_POST['tot_dr'],
              "oc" => $this->sd['oc'],
              "curr_acc" => $_POST['curr_acc']
            );

            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update("t_receipt_temp_cheque_sum", $sum_update);

            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_receipt_temp_cheque_det");

            if(isset($det)){
              if(count($det)){$this->db->insert_batch("t_receipt_temp_cheque_det",$det);}            
            }
            $this->utility->save_logger("EDIT",51,$this->max_no,$this->mod);
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
      echo "Operation fail please contact admin"; 
    }        
  }
  
  
  public function load(){

    $no = $_POST['id'];

    $sql="SELECT s.nno,
    s.date,
    s.ref_no,
    s.customer,
    s.customer_des as cus_name,
    s.employee,
    s.remarks,
    s.total,
    e.name AS emp_name,
    s.is_cancel,
    s.curr_acc
    FROM t_receipt_temp_cheque_sum s
    JOIN m_employee e ON   e.code = s.employee
    WHERE s.nno='$no' AND s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."'";
    
    $query=$this->db->query($sql);      
    $x  = 0;
    if ($query->num_rows() > 0) {
      $a['sum'] = $query->result();
    } else {
      $x = 2;
    } 

    $sql_det="SELECT d.bank,
    d.branch,
    d.account,
    d.cheque_no,
    d.amount,
    d.realize_date, 
    b.description AS bank_name,
    bb.description AS branch_name
    FROM t_receipt_temp_cheque_det d
    JOIN m_bank b ON b.code = d.`bank`
    LEFT JOIN m_bank_branch bb ON bb.bank = b.code
    WHERE d.nno='$no' AND d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."'
    GROUP BY d.auto_no";

    $query2=$this->db->query($sql_det);          
    if ($query2->num_rows() > 0) {
      $a['det'] = $query2->result();
    } else {
      $x = 2;
    } 

    if ($x == 0) {
      echo json_encode($a);
    } else {
      echo json_encode($x);
    }
  }
  
  public function delete_records(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try {
      if($this->user_permissions->is_delete('t_receipt_temp')){
        $no = $_POST['code'];

        $this->db->where('nno',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->delete('t_receipt_temp_cheque_det');

        $this->db->where('nno',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->delete('t_receipt_temp_cheque_sum');

        $this->utility->save_logger("DELETE",51,$no,$this->mod);
        echo  $this->db->trans_commit();
      }else{
        echo $delete_validation_status;
        $this->db->trans_commit();
      }
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo "Operation fail please contact admin"; 
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
      if($this->user_permissions->is_delete('t_receipt_temp')){
        $no = $_POST['code'];
        $this->db->where('nno',$no);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->update('t_receipt_temp_cheque_sum', array("is_cancel"=>1));
        $this->utility->save_logger("CANCEL",51,$no,$this->mod);
        echo  $this->db->trans_commit();
      }else{
        echo $delete_validation_status;
        $this->db->trans_commit();
      }
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo "Operation fail please contact admin"; 
    }     
  }


  public function bank_list_all() {
    if ($_POST['search'] == 'Key Word: code, name') {
      $_POST['search'] = "";
    }

    $sql = "SELECT m.code,
    m.description,
    mb.code AS branch_code,
    mb.description AS branch_description 
    FROM m_bank m 
    LEFT JOIN m_bank_branch mb ON m.code = mb.bank 
    WHERE mb.code LIKE '%$_POST[search]%' OR mb.description LIKE '%$_POST[search]%'
    GROUP BY mb.code
    LIMIT 25 ";  

    $query = $this->db->query($sql);
    $a = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Branch Code</th>";
    $a .= "<th class='tb_head_th'>Branch Name</th>";
    $a .= "<th class='tb_head_th'>Bank Code</th>";
    $a .= "<th class='tb_head_th'>Bank Name</th>";
    

    $a .= "</thead></tr>";
    $a .= "<tr class='cl'>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";

    $a .= "</tr>";
    foreach ($query->result() as $r) {
      $a .= "<tr class='cl'>";
      $a .= "<td>" . $r->branch_code . "</td>";
      $a .= "<td>" . $r->branch_description . "</td>";
      $a .= "<td>" . $r->code . "</td>";
      $a .= "<td>" . $r->description . "</td>";

      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }


  public function currentAcc_list() {
    if ($_POST['search'] == 'Key Word: code, name') {
      $_POST['search'] = "";
    }

    $sql = "SELECT mb.`code`,mb.`description`
    FROM `r_branch_current_acc` m 
    LEFT JOIN `m_account` mb ON m.acc_code = mb.code 
    WHERE mb.code LIKE '%$_POST[search]%' OR mb.description LIKE '%$_POST[search]%'
    GROUP BY mb.code
    LIMIT 25 ";  

    $query = $this->db->query($sql);
    $a = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th'>Name</th>";
    
    $a .= "</thead></tr>";
    $a .= "<tr class='cl'>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";

    $a .= "</tr>";
    foreach ($query->result() as $r) {
      $a .= "<tr class='cl'>";
      $a .= "<td>" . $r->code . "</td>";
      $a .= "<td>" . $r->description . "</td>";

      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
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

    $this->db->where("code",$_POST['sales_type']);
    $query= $this->db->get('t_trans_code'); 
    if ($query->num_rows() > 0){
      foreach ($query->result() as $row){
        $r_detail['r_type']= $row->description;       
      }
    } 

    $r_detail['type']=$_POST['type'];        
    $r_detail['dt']=$_POST['dt'];
    $r_detail['qno']=$_POST['qno'];
    $r_detail['num']=$_POST['recivied'];
    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];
    $num =$_POST['recivied'];
    $no = $_POST['qno'];

    $this->db->select(array('code','name'));
    $this->db->where("code",$_POST['cus_id']);
    $r_detail['customer']=$this->db->get('m_customer')->result();

    $this->db->select(array('name'));
    $this->db->where("code",$_POST['salesp_id']);
    $query=$this->db->get('m_employee');

    foreach ($query->result() as $row){
      $r_detail['employee']= $row->name;
    }

    $sql="SELECT s.nno,
    s.date,
    s.ref_no,
    s.customer,
    s.employee,
    s.remarks,
    s.total,
    s.customer_des AS cus_name,
    e.name AS emp_name,
    s.is_cancel
    FROM t_receipt_temp_cheque_sum s
    JOIN m_employee e ON   e.code = s.employee
    WHERE s.nno='$no' AND s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."'";
    $query = $this->db->query($sql);
    $r_detail['sum'] = $query->result();

    $sql_det="SELECT d.bank,
    d.branch,
    d.account,
    d.cheque_no,
    d.amount,
    d.realize_date, 
    b.description AS bank_name,
    bb.description AS branch_name
    FROM t_receipt_temp_cheque_det d
    JOIN m_bank b ON b.code = d.`bank`
    LEFT JOIN m_bank_branch bb ON bb.bank = b.code AND d.branch=bb.code
    WHERE d.nno='$no' AND d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."'
    GROUP BY d.auto_no ";

    $query2=$this->db->query($sql_det); 
    $r_detail['det'] = $query2->result();         

    $this->db->select(array('loginName'));
    $this->db->where('cCode',$this->sd['oc']);
    $r_detail['user']=$this->db->get('users')->row()->loginName;

    if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }

    
  }
}