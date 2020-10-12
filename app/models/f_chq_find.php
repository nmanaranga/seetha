<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class f_chq_find extends CI_Model {
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

  public function load_chq_details_received(){
    if($_POST['cl']!=""){
      $cluster =" AND r.cl='".$_POST['cl']."'";
    }else{
      $cluster =" ";
    }
    if($_POST['bc']!=""){
      $branch =" AND r.bc='".$_POST['bc']."'";
    }else{
      $branch =" ";
    }
    $begin=0;
    $find_text ="AND (r.ddate!=''";
    if(isset($_POST['date'])){
      if($_POST['date']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.ddate LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['chq'])){
      if($_POST['chq']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`cheque_no` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['amount'])){
      if($_POST['amount']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.amount LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['r_date'])){
      if($_POST['r_date']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`bank_date` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['acc'])){
      if($_POST['acc']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.account LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['bank'])){
      if($_POST['bank']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`bank` LIKE '%".$_POST['find']."%' OR b.description LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['b_branch'])){
      if($_POST['b_branch']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`branch` LIKE '%".$_POST['find']."%' OR bb.`description` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['f_racc'])){
      if($_POST['f_racc']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.received_from_acc LIKE '%".$_POST['find']."%' OR a.description LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['status'])){
      if($_POST['status']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        if($_POST['find']=="Pending"){
          $_POST['find']="P";
        }
        if($_POST['find']=="Deposit"){
          $_POST['find']="D";
        }
        if($_POST['find']=="Returned"){
          $_POST['find']="R";
        }
        $find_text .=" r.`status` LIKE '%".$_POST['find']."%'";
      }
    }
    
    $find_text.=" ) "; 

    $sql="SELECT  r.cl,              
    r.bc,
    r.ddate,
    r.amount,           
    r.`bank`,
    r.received_from_acc ,
    a.description as acc_name,
    r.account, 
    b.description AS bank_name,
    r.`branch`,
    bb.`description` AS branch_name,
    r.`cheque_no`,
    r.`bank_date`,
    r.`trans_code`,
    t.description,
    r.`trans_no`,
    r.status
    FROM t_cheque_received r
    JOIN m_bank b ON b.code = r.`bank`
    JOIN m_bank_branch bb ON bb.`bank` = r.bank AND bb.`code`=r.`branch`
    JOIN t_trans_code t ON t.code = r.`trans_code`
    JOIN m_account a ON a.code = r.received_from_acc 
    WHERE r.ddate !='' $cluster $branch $find_text";

    $sql.=" LIMIT 50";
    $query = $this->db->query($sql);
    if($query->num_rows()>0){
      $result = $query->result();
    }else{
      $result = 2;
    }
    echo json_encode($result);
  }

  public function load_chq_details_issued(){
    if($_POST['cl']!=""){
      $cluster =" AND r.cl='".$_POST['cl']."'";
    }else{
      $cluster =" ";
    }
    if($_POST['bc']!=""){
      $branch =" AND r.bc='".$_POST['bc']."'";
    }else{
      $branch =" ";
    }
    $begin=0;
    $find_text ="AND (r.ddate!=''";
    if(isset($_POST['date'])){
      if($_POST['date']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.ddate LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['chq'])){
      if($_POST['chq']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`cheque_no` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['amount'])){
      if($_POST['amount']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.amount LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['r_date'])){
      if($_POST['r_date']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`bank_date` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['acc'])){
      if($_POST['acc']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.account LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['bank'])){
      if($_POST['bank']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`bank` LIKE '%".$_POST['find']."%' OR b.description LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['b_branch'])){
      if($_POST['b_branch']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.`branch` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['f_racc'])){
      if($_POST['f_racc']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" r.issued_to_acc LIKE '%".$_POST['find']."%' OR aa.description LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['status'])){
      if($_POST['status']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        if($_POST['find']=="Pending"){
          $_POST['find']="P";
        }
        if($_POST['find']=="Deposit"){
          $_POST['find']="D";
        }
        if($_POST['find']=="Returned"){
          $_POST['find']="R";
        }
        $find_text .=" r.`status` LIKE '%".$_POST['find']."%'";
      }
    }
    
    $find_text.=" ) "; 

    $sql="SELECT r.status,r.cl,
    r.bc,
    r.`bank`,
    a.description AS bank_name,
    aa.description as issued_acc,
    r.issued_to_acc,
    r.`branch`, 
    r.`cheque_no`,
    r.`bank_date`,
    r.`trans_code`,
    t.description,
    r.`trans_no`,
    r.bank_date,
    r.amount,
    r.account,
    r.ddate
    FROM t_cheque_issued r
    JOIN m_account a ON a.code = r.`bank`
    JOIN m_account aa ON aa.code = r.issued_to_acc 
    JOIN t_trans_code t ON t.code = r.`trans_code`
    WHERE r.ddate !='' $cluster $branch $find_text";

    $sql.=" LIMIT 50";
    $query = $this->db->query($sql);
    if($query->num_rows()>0){
      $result = $query->result();
    }else{
      $result = 2;
    }
    echo json_encode($result);
  }

  public function chq_acknowledge(){

    if($_POST['cl']!=""){
      $cluster =" AND dt.cl='".$_POST['cl']."'";
    }else{
      $cluster =" ";
    }
    if($_POST['bc']!=""){
      $branch =" AND dt.bc='".$_POST['bc']."'";
    }else{
      $branch =" ";
    }
    $begin=0;
    $find_text ="AND (sm.date!=''";
    if(isset($_POST['date'])){
      if($_POST['date']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" sm.date LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['chq'])){
      if($_POST['chq']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" dt.cheque_no LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['amount'])){
      if($_POST['amount']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" dt.amount LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['r_date'])){
      if($_POST['r_date']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" dt.realize_date LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['acc'])){
      if($_POST['acc']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" dt.account LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['bank'])){
      if($_POST['bank']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" dt.bank LIKE '%".$_POST['find']."%' OR mb.`description` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['b_branch'])){
      if($_POST['b_branch']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        $find_text .=" mbr.`branch_code` LIKE '%".$_POST['find']."%' OR mbr.`description` LIKE '%".$_POST['find']."%'";
      }
    }
    if(isset($_POST['status'])){
      if($_POST['status']=="1"){
        if($begin==0){
          $find_text .=" AND ";
        }else{
          $find_text .=" OR ";
        }
        $begin=1;
        if($_POST['find']=="Pending"){
          $_POST['find']="P";
        }
        if($_POST['find']=="Deposit"){
          $_POST['find']="D";
        }
        if($_POST['find']=="Receipted"){
          $_POST['find']="R";
        }
        $find_text .=" dt.`status` LIKE '%".$_POST['find']."%'";
      }
    }
    
    $find_text.=" ) "; 

    $sql = "SELECT 
    sm.`date`,
    dt.`cl`,
    dt.`bc` ,
    dt.`cheque_no`,
    dt.`amount`,
    dt.`account`,
    dt.`status`,
    dt.`realize_date`,
    mbr.`bank`,
    mbr.`description` AS branch_name,
    mb.`description` AS bank_name,
    mbr.`branch_code`

    FROM 
    t_receipt_temp_cheque_det dt
    JOIN m_bank_branch mbr ON mbr.`code`=dt.`branch` AND mbr.`bank`=dt.`bank`
    JOIN t_receipt_temp_cheque_sum sm ON sm.`cl`=dt.`cl` AND sm.`bc`=dt.`bc` AND sm.`nno`=dt.`nno`
    JOIN m_bank mb ON mb.`code`=dt.`bank`
    WHERE dt.bc != '' AND sm.is_cancel='0' $cluster $branch $find_text
    LIMIT 50 ";
    
    $query= $this->db->query($sql);
    if($query->num_rows()>0){
      $a['data']=$this->db->query($sql)->result();
    }else{
      $a['data']=2;
    }
    echo json_encode($a);
  }

  public function PDF_report(){

    $r_detail['type']="pd_chq_registry";        
    $r_detail['dt']=$_POST['dt'];
    $r_detail['qno']=$_POST['qno'];

    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];
    $r_detail['title']="Customer Balance  ";
    $r_detail['all_det']=$_POST;

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  }

}
