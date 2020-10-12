<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class f_post_dated_chq_reg extends CI_Model {
  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct(){
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
  }
  
  public function base_details(){}

  public function load_cluster(){
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
    $sql="SELECT `code`,description 
    FROM m_cluster m
    JOIN u_branch_to_user u ON u.cl = m.code
    WHERE user_id='".$this->sd['oc']."' AND (code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%')
    GROUP BY m.code";

    $query=$this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Cluster</th>";
    $a .= "<th class='tb_head_th' colspan='2'>Name</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->code."</td>";
      $a .= "<td>".$r->description."</td>";
      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }

  public function load_branch(){
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
    
    if($_POST['cl'] != ""){
      $sql="SELECT m.`bc`,name 
      FROM m_branch m
      JOIN u_branch_to_user u ON u.bc = m.bc
      WHERE user_id='".$this->sd['oc']."' AND m.cl='".$_POST['cl']."' AND (m.`bc` LIKE '%$_POST[search]%' OR name LIKE '%$_POST[search]%')
      GROUP BY m.bc";
    }else{
      $sql="SELECT m.`bc`,name 
      FROM m_branch m
      JOIN u_branch_to_user u ON u.bc = m.bc
      WHERE user_id='".$this->sd['oc']."' AND (m.`bc` LIKE '%$_POST[search]%' OR name LIKE '%$_POST[search]%')
      GROUP BY m.bc";  
    }

    $query=$this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Branch</th>";
    $a .= "<th class='tb_head_th' colspan='2'>Name</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->bc."</td>";
      $a .= "<td>".$r->name."</td>";
      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }

  public function load_chq_details(){

    $date_from = $_POST['f_date'];
    $date_to   = $_POST['t_date']; 

    if($_POST['cl']!=""){
      $cluster ="AND s.cl='".$_POST['cl']."'";
    }else{
      $cluster ="";
    }

    if($_POST['bc']!=""){
      $branch ="AND s.bc='".$_POST['bc']."'";
    }else{
      $branch ="";
    }

    if($_POST['status']=="A"){
      $status ="";
    }else if($_POST['status']=="P"){
      $status ="AND d.status='P'";
    }else if($_POST['status']=="R"){
      $status ="AND d.status='R'";
    }else if($_POST['status']=="D"){
      $status ="AND ch.status='D'";
    }
    
    if($_POST['customer']!=""){
      $search_cus = " AND s.customer='".$_POST['customer']."'";
    }else{
      $search_cus = "";
    }

    $sql="SELECT  s.date,
    d.realize_date,
    s.customer,
    m.name AS cus_name,
    d.cheque_no,
    d.realize_date AS chq_date,
    d.amount,
    d.account,
    b.description AS bank_name,
    d.bank,
    d.branch,
    d.status,
    bb.description AS branch_name,
    d.nno AS ack_no,
    IFNULL(IFNULL(r.`ddate`,gr.`ddate`),'')AS rcpt_date,
    IFNULL(op.trans_no,'') AS rcpt_no,
    IFNULL(ch.bank_date,'') AS bank_date,
    IFNULL(rs.`no`,0) AS return_no,
    IFNULL(rs.`ddate`,'') AS return_date,
    IFNULL(rs.`description`,'') AS return_reason
    FROM `t_receipt_temp_cheque_sum` s
    JOIN `t_receipt_temp_cheque_det` d ON s.cl=d.cl AND s.bc=d.bc AND s.nno=d.nno
    JOIN m_customer m ON m.code = s.customer
    JOIN m_bank b ON b.code = d.bank
    JOIN m_bank_branch bb ON bb.code = d.branch
    LEFT JOIN opt_post_dated_cheque_det op ON op.`account_no` = d.account AND op.`bank`= d.bank AND op.`branch` = d.branch AND op.`cheque_no` = d.cheque_no AND op.`cl`= d.cl AND op.`bc` = d.bc 
    LEFT JOIN t_receipt r ON r.cl=op.`cl` AND r.bc=op.`bc` AND r.nno = op.`trans_no` AND op.`trans_code` = 16
    LEFT JOIN t_receipt_gl_sum gr ON gr.cl=op.`cl` AND gr.bc=op.`bc` AND gr.nno = op.`trans_no` AND op.`trans_code` = 49
    LEFT JOIN `t_cheque_received` ch ON ch.cl = d.cl AND ch.bc = d.bc AND ch.bank = d.bank AND ch.branch = d.branch AND ch.cheque_no = d.cheque_no AND ch.account=d.account
    LEFT JOIN t_cheque_rtn_sum rs ON rs.`cl` = s.cl AND rs.bc = s.bc AND rs.`bank` = d.bank AND rs.`account` = d.account AND rs.`cheque_no` = d.cheque_no
    WHERE s.is_cancel='0' AND d.realize_date BETWEEN '$date_from' AND '$date_to' $cluster $branch $status $search_cus";

    
    $query = $this->db->query($sql);

    if($query->num_rows()>0){
      $result = $query->result();

    }else{
      $result = 2;
    }

    echo json_encode($result);

  }

  public function PDF_report(){


   $this->db->select(array('name','address','tp','fax','email'));
   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $r_detail['branch']=$this->db->get('m_branch')->result();


   $r_detail['type']="pd_chq_registry";        
   $r_detail['dt']=$_POST['dt'];
   $r_detail['qno']=$_POST['qno'];

   $r_detail['page']=$_POST['page'];
   $r_detail['header']=$_POST['header'];
   $r_detail['orientation']=$_POST['orientation'];
   $r_detail['title']="Customer Balance  ";
        //$r_detail['all_det']=$_POST;

   $sql=" SELECT  s.date,
   d.realize_date,
   s.customer,
   m.name AS cus_name,
   d.cheque_no,
   d.realize_date AS chq_date,
   d.amount,
   d.account,
   b.description AS bank_name,
   d.bank,
   d.branch,
   d.status,
   bb.description AS branch_name,
   d.nno AS ack_no,
   IFNULL(IFNULL(r.`ddate`,gr.`ddate`),'')AS rcpt_date,
   IFNULL(op.trans_no,'') AS rcpt_no,
   IFNULL(ch.bank_date,'') AS bank_date,
   IFNULL(rs.`no`,0) AS return_no,
   IFNULL(rs.`ddate`,'') AS return_date,
   IFNULL(rs.`description`,'') AS return_reason
   FROM `t_receipt_temp_cheque_sum` s
   JOIN `t_receipt_temp_cheque_det` d ON s.cl=d.cl AND s.bc=d.bc AND s.nno=d.nno
   JOIN m_customer m ON m.code = s.customer
   JOIN m_bank b ON b.code = d.bank
   JOIN m_bank_branch bb ON bb.code = d.branch
   LEFT JOIN opt_post_dated_cheque_det op ON op.`account_no` = d.account AND op.`bank`= d.bank AND op.`branch` = d.branch AND op.`cheque_no` = d.cheque_no AND op.`cl`= d.cl AND op.`bc` = d.bc 
   LEFT JOIN t_receipt r ON r.cl=op.`cl` AND r.bc=op.`bc` AND r.nno = op.`trans_no` AND op.`trans_code` = 16
   LEFT JOIN t_receipt_gl_sum gr ON gr.cl=op.`cl` AND gr.bc=op.`bc` AND gr.nno = op.`trans_no` AND op.`trans_code` = 49
   LEFT JOIN `t_cheque_received` ch ON ch.cl = d.cl AND ch.bc = d.bc AND ch.bank = d.bank AND ch.branch = d.branch AND ch.cheque_no = d.cheque_no AND ch.account=d.account
   LEFT JOIN t_cheque_rtn_sum rs ON rs.`cl` = s.cl AND rs.bc = s.bc AND rs.`bank` = d.bank AND rs.`account` = d.account AND rs.`cheque_no` = d.cheque_no
   WHERE s.is_cancel='0' AND d.realize_date BETWEEN '".$_POST['f_date']."' AND '".$_POST['t_date']."'";

   if($_POST['cluster']!="" || $_POST['cluster']!="0"){
    $sql.=" AND s.cl='".$_POST['cluster']."'";
  }
  if($_POST['branch']!="" || $_POST['branch']!="0"){
    $sql.=" AND s.bc='".$_POST['branch']."'";
  }
  if($_POST['status']!="A" ){
    $sql.=" AND d.status='".$_POST['status']."'";
  }
  if($_POST['t_search']!=""){
    $sql.=" AND s.customer='".$_POST['t_search']."'";
  }


  $query = $this->db->query($sql);
  $r_detail['all_det'] = $this->db->query($sql)->result();

  if($query->num_rows()>0)
  {
    $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  }
  else
  {
    echo "<script>alert('No Data');window.close();</script>";
  }
}

}
