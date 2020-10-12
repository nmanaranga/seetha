<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class f_return_chq_reg extends CI_Model {
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

    public function load_chq_details_r(){
      $date_from = $_POST['f_date'];
      $date_to   = $_POST['t_date']; 
      if($_POST['cl']!=""){
        $cluster =" AND s.cl='".$_POST['cl']."'";
      }else{
        $cluster ="";
      }
      if($_POST['bc']!=""){
        $branch =" AND s.bc='".$_POST['bc']."'";
      }else{
        $branch ="";
      }
      if($_POST['search']!=""){
          $search_cus= " AND s.customer='".$_POST['search']."'";
      }else{
          $search_cus="";
      }

      $sql="SELECT  ddate,
                    customer,
                    c.`name`,
                    cheque_no,
                    amount,
                    account,
                    bank,
                    b.description as bank_name,
                    trans_code,
                    t.`description`,
                    trans_no,
                    realize_date 
              FROM t_cheque_rtn_sum s
              JOIN t_trans_code t ON t.`code` = s.`trans_code`
              JOIN m_customer c ON c.`code` = s.`customer`
              JOIN m_bank b ON b.`code` = s.`bank`
              WHERE s.ddate BETWEEN '$date_from' AND '$date_to' $cluster $branch $search_cus";
      $query = $this->db->query($sql);
      if($query->num_rows()>0){
        $result = $query->result();
      }else{
        $result = 2;
      }
      echo json_encode($result);
    }

    public function load_chq_details_p(){
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
      
       if($_POST['search']!=""){
          $search_cus= " AND s.customer='".$_POST['search']."'";
      }else{
          $search_cus="";
      }

      $sql="SELECT  ddate,
                    supplier as customer,
                    p.`name`,
                    cheque_no,
                    amount,
                    account,
                    bank,
                    a.`description` AS bank_name,
                    trans_code,
                    t.`description`,
                    trans_no,
                    realize_date 
              FROM t_cheque_payment_rtn_sum s
              JOIN t_trans_code t ON t.`code` = s.`trans_code`
              JOIN m_supplier p ON p.`code` = s.`supplier`
              JOIN m_account a ON a.`code` = s.`bank`
              WHERE s.ddate BETWEEN '$date_from' AND '$date_to' $cluster $branch";

      $query = $this->db->query($sql);
      if($query->num_rows()>0){
        $result = $query->result();
      }else{
        $result = 2;
      }
      echo json_encode($result);
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
