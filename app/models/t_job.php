<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_job extends CI_Model {

    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
     parent::__construct();

     $this->sd = $this->session->all_userdata();
     $this->load->database($this->sd['db'], true);
     $this->mtb = $this->tables->tb['t_job'];
     $this->load->model('user_permissions');

 }

 public function base_details(){

    $this->load->model("utility");
    $a['max_no'] = $this->utility->get_max_no("t_job", "nno");
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

public function f1_selection_inv_no() {
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
    $inv_type=$_POST['inv_type'];
    if($inv_type=="1"){
        $sql = "SELECT s.nno,s.ddate,s.cus_id,c.`address1`,c.`address2`,c.`address3`,c.`name`,s.net_amount 
        FROM `t_cash_sales_sum` s
        JOIN m_customer c ON c.`code`=s.`cus_id`
        WHERE s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND(s.nno LIKE '%$_POST[search]%' OR s.cus_id LIKE '%$_POST[search]%' OR c.name LIKE '%$_POST[search]%') LIMIT 25";

    }if($inv_type=="2"){
        $sql = "SELECT s.nno,s.ddate,s.cus_id,c.`address1`,c.`address2`,c.`address3`,c.name,s.net_amount
        FROM `t_credit_sales_sum` s
        JOIN m_customer c ON c.`code` = s.`cus_id`
        WHERE s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND(s.nno LIKE '%$_POST[search]%' OR s.cus_id LIKE '%$_POST[search]%' OR c.name LIKE '%$_POST[search]%') LIMIT 25";

    }

    $query = $this->db->query($sql);

    $a = "<table id='item_list' style='width : 100%' >";

    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Inv No</th>";
    $a .= "<th class='tb_head_th'>Date</th>";
    $a .= "<th class='tb_head_th'>Customer</th>";
    $a .= "<th class='tb_head_th'>Amount</th>";

    $a .= "</thead></tr>";
    $a .= "<tr class='cl'>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";


    $a .= "</tr>";
    foreach ($query->result() as $r) {
        $a .= "<tr class='cl'>";
        $a .= "<td>" . $r->nno . "</td>";
        $a .= "<td>" . $r->ddate . "</td>";
        $a .= "<td>" . $r->name . "</td>";

        $a .= "<td style='text-align:right;'>" . $r->net_amount . "</td>";
        $a .= "<td style='display:none;'>" . $r->cus_id . "</td>";
        $a .= "<td style='display:none;'>" . $r->address1 . "</td>";
        $a .= "<td style='display:none;'>" . $r->address2 . "</td>";
        $a .= "<td style='display:none;'>" . $r->address3 . "</td>";

        $a .= "</tr>";
    }
    $a .= "</table>";

    echo $a;
}

public function f1_selection_item() {

    $inv_no=$_POST['inv_no'];
    $cus=$_POST['cus'];
    $inv_type=$_POST['inv_type'];
    if($inv_type=="1"){
        $sql = "SELECT d.nno,d.code,i.`description`,i.`brand`,b.description as br_name,i.`serial_no`,i.`model`,i.`max_price`,s.`cus_id`,i.`supplier`,m.`name` FROM `t_cash_sales_det` d
        JOIN `t_cash_sales_sum` s ON s.`nno`=d.`nno` AND s.cl=d.`cl` AND s.bc=d.`bc`
        JOIN m_item i ON i.`code`=d.`code`
        JOIN r_brand b ON b.`code`=i.`brand`
        JOIN `m_supplier` m ON m.`code`=i.`supplier`
        WHERE  s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND d.nno='$inv_no' AND s.cus_id='$cus' LIMIT 25";
    }if($inv_type=="2"){
        $sql = "SELECT d.nno,d.code,i.`description`,i.`brand`,b.description as br_name,i.`serial_no`,i.`model`,i.`max_price`,s.`cus_id`,i.`supplier`,m.`name` FROM `t_credit_sales_det` d
        JOIN `t_credit_sales_sum` s ON s.`nno`=d.`nno`  AND s.cl=d.`cl` AND s.bc=d.`bc`
        JOIN m_item i ON i.`code`=d.`code`
        JOIN r_brand b ON b.`code`=i.`brand`
        JOIN `m_supplier` m ON m.`code`=i.`supplier`
        WHERE s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND d.nno='$inv_no' AND s.cus_id='$cus' LIMIT 25 ";
    }
    $query = $this->db->query($sql);

    $a = "<table id='item_list' style='width : 100%' >";

    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Item Code</th>";
    $a .= "<th class='tb_head_th'>Item Name</th>";
    $a .= "<th class='tb_head_th'>Price</th>";
    $a .= "<th class='tb_head_th'>Serial No</th>";

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
        $a .= "<td style='text-align:right;'>" . $r->max_price . "</td>";
        $a .= "<td>" . $r->serial_no . "</td>";
        $a .= "<td style='display:none;'>" . $r->brand . "</td>";
        $a .= "<td style='display:none;'>" . $r->model . "</td>";
        $a .= "<td style='display:none;'>" . $r->br_name . "</td>";
        $a .= "<td style='display:none;'>" . $r->supplier . "</td>";
        $a .= "<td style='display:none;'>" . $r->name . "</td>";
        $a .= "</tr>";
    }
    $a .= "</table>";

    echo $a;
}


public function save(){

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
        $this->max_no = $this->utility->get_max_no("t_job", "nno");
        $this->credit_max_no = $this->get_credit_max_no();

        $total=$_POST['advance'];



        if(isset($_POST['gur_crd'])){
            $gurantee="1";
        }else{
            $gurantee="0";
        }
        if($_POST['inv_type_h']=="1"){
            $inv_type="Cash Sales";
        }

        if($_POST['inv_type_h']=="2"){
            $inv_type="Credit Sales";
        }


        if($_POST['inv_type_h']=="3"){
            $inv_type="Hire Purchase";
        }

        if($_POST['inv_type_h']=="4"){
            $inv_type="Cash and Card Sales";
        }

        if($_POST['inv_type_h']=="5"){
            $inv_type="Sales Order Sales";
        }
        if($_POST['inv_type_h']==""){
            $inv_type="";
        }

        if($_POST['item_des'] != ""){
            $item_name = $_POST['item_des'];
        }
        if($_POST['item'] !=""){
           $item_name  =$_POST['item'];
       }
       if(!empty($_POST['start_date'])){
        $start_dt = $_POST['start_date'];
    }else{
        $start_dt = "";
    }
    if(!empty($_POST['end_date'])){
        $end_dt= $_POST['end_date'];
    }else{
        $end_dt="";
    }

    if(!empty($_POST['brand'])){
        $brand_name=$_POST['brand'];
    }
    if(!empty($_POST['brand_des'])){
        $brand_name=$_POST['brand_des'];
    }

    $t_job = array(
        "cl"=>$this->sd['cl'],
        "bc"=>$this->sd['branch'],
        "nno"=>$this->max_no,
        "ddate"=>$_POST['date'],
        "ref_no"=>$_POST['ref_no'],
        "type"=>$_POST['type'],
        "inv_type"=>$inv_type,
        "inv_no"=>$_POST['inv_no'],
        "inv_date"=>$_POST['inv_date'],
        "cus_id"=>$_POST['cus_id'],
        "item_code"=>$_POST['item_id'],
        "Item_name"=>$item_name,
        "brand"=>$_POST['brand_id'],
        "brand_name"=>$brand_name,
        "model"=>$_POST['model'],
        "serial_no"=>$_POST['serial'],
        "is_guarantee"=>$gurantee,
        "guarantee_cardno"=>$_POST['gur_no'],
        "fault"=>$_POST['fault'],
        "parts"=>$_POST['parts'],
        "advance_amount"=>$_POST['advance'],
        "supplier"=>$_POST['sup_id'],
        "crn_no"=>$_POST['crn_no'],
        "w_start_date"=>$start_dt,
        "w_end_date"=>$end_dt,
        "oc"=>$this->sd['oc']

    );

    $t_credit_note = array(
      "cl" => $this->sd['cl'],
      "bc" => $this->sd['branch'],
      "nno" => $this->credit_max_no,
      "ddate" => $_POST['date'],
      "ref_no" => $_POST['ref_no'],
      "memo" => "SERVICE JOB - [" . $this->max_no . "]",
      "is_customer" => 1,
      "code" => $_POST['cus_id'],
      "acc_code" => $this->utility->get_default_acc('STOCK_ACC'),
      "amount" => $total,
      "oc" => $this->sd['oc'],
      "post" => "",
      "post_by" => "",
      "post_date" => "",
      "is_cancel" => 0,
      "ref_trans_no" => $this->max_no,
      "ref_trans_code" => 90,
      "balance"=>$total
  );


    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_job')){
            $this->db->insert($this->mtb, $t_job);
            $this->load->model('trans_settlement');
            $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['cus_id'], $_POST['date'], 17, $this->credit_max_no, 90, $this->max_no,$total, "0" );
            $this->db->insert('t_credit_note', $t_credit_note);
            $this->utility->save_logger("SAVE",90,$this->max_no,$this->mod); 
            echo $this->db->trans_commit();
        }else{
            $this->db->trans_commit();
            echo "No permission to save records";
        }
    }else{
        if($this->user_permissions->is_edit('t_job')){
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->where("nno", $_POST['hid']);
            $this->db->update($this->mtb, $t_job);
            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_settlement("t_credit_note_trans", 17, $this->credit_max_no);
            $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['cus_id'], $_POST['date'], 17, $this->credit_max_no, 90, $this->max_no,$total, "0");
            if($_POST['crn_no_hid']!=0){
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where('nno', $this->credit_max_no);
                $this->db->update('t_credit_note', $t_credit_note);
            }else{
                $this->db->insert('t_credit_note', $t_credit_note);
            }
            $this->utility->save_logger("EDIT",90,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
        }else{
            $this->db->trans_commit();
            echo "No permission to edit records";
        }
    }

}catch(Exception $e){ 
    $this->db->trans_rollback();
    echo $e->getmessage()."Operation fail  please contact admin"; 
} 
}


public function load(){
    $nno=$_POST['id'];
    $sql="SELECT 
    j.nno,
    j.ddate,
    j.ref_no,
    j.type,
    j.`inv_type`,
    j.inv_no,
    j.inv_date,
    j.cus_id,
    j.item_code,
    j.Item_name,
    j.brand_name,
    j.brand,
    j.model,
    j.serial_no,
    j.is_guarantee,
    j.guarantee_cardno,
    j.fault,
    j.parts,
    j.advance_amount,
    j.supplier,
    j.crn_no,
    j.w_start_date,
    j.w_end_date,
    c.`name` AS cus,
    c.address1,
    c.address2,
    c.address3,
    i.`description` AS item_n,
    b.`description` AS brand_n,
    s.`name` AS sup,
    j.is_cancel
    FROM
    t_job j 
    JOIN m_customer c ON c.`code`=j.`cus_id`
    LEFT JOIN m_item i ON i.`code`=j.`item_code`
    LEFT JOIN r_brand b ON b.code=j.`brand`
    LEFT JOIN m_supplier s ON s.`code`=j.`supplier`
    WHERE j.nno='$nno'
    AND j.cl = '".$this->sd['cl']."'
    AND j.bc = '".$this->sd['branch']."'";

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
        $a["a"] = $this->db->query($sql)->first_row();
    } else {
        $a["a"] = 2;
    }  



    echo json_encode($a);
}


public function delete(){

    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine)
    {
      throw new Exception($errMsg);
  }

  set_error_handler('exceptionThrower');
  try {
      if($this->user_permissions->is_delete('t_job')) {
        $trans_no=$_POST['id'];
        $this->db->where("nno", $_POST['id']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->limit(1);
        $this->db->update($this->mtb, array("is_cancel" => 1));  
        $this->load->model('trans_settlement');
        $this->trans_settlement->delete_settlement_sub("t_credit_note_trans","90",$trans_no); 

        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("ref_trans_code","90");
        $this->db->where("ref_trans_no",$trans_no);
        $this->db->limit(1);
        $this->db->update("t_credit_note", array("is_cancel" => 1));  

        $sql="SELECT cus_id FROM `t_job` WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['id']."'";
        $cus_id=$this->db->query($sql)->first_row()->cus_id;
        $this->utility->update_credit_note_balance($cus_id);

        $this->utility->save_logger("CANCEL", 90, $_POST['id'], $this->mod);
        echo $this->db->trans_commit();
    } else {
        echo "No permission to delete records";
        $this->db->trans_commit();
    }
}
catch (Exception $e) {
  $this->db->trans_rollback();
  echo $e->getMessage() . "Operation fail please contact admin";
}
}

public function PDF_report(){

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();

    $this->db->select(array('loginName'));
    $this->db->where('cCode', $this->sd['oc']);
    $r_detail['user'] = $this->db->get('users')->result();

    $r_detail['session']=$session_array;

    $r_detail['header'] = $_POST['header'];
    $r_detail['orientation'] = "L";
    $r_detail['dt'] = $_POST['dt'];

    $r_detail['page'] = "A5";

    $sql = "SELECT
    tj.`inv_type`,
    tj.`inv_no`,
    tj.`inv_date`,
    tj.`nno`,
    tj.`cus_id`,
    mc.`name` AS cus_name,
    tj.`ddate`,
    tj.`ref_no`,
    tj.`item_code`,
    tj.`Item_name`,
    tj.`model`,
    tj.`brand_name`,
    tj.`serial_no`,
    tj.`guarantee_cardno` AS gur_no,
    tj.`supplier` AS sup_code,
    tj.`w_start_date`,
    tj.`w_end_date`,
    ms.`name` AS sup_name,
    mc.`address1`,
    mc.`address2`,
    ms.`address3`,
    tj.`fault`,
    tj.`advance_amount`

    FROM t_job tj
    JOIN `m_customer` mc ON mc.`code` = tj.`cus_id`
    JOIN `m_supplier` ms ON ms.`code` = tj.`supplier`

    WHERE tj.`cl`= '".$this->sd['cl']."'
    AND tj.`bc` = '".$this->sd['branch']."'
    AND tj.`nno`= '".$_POST['qno']."'";

    $r_detail['jobs'] = $this->db->query($sql)->result();

    $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
}


}

