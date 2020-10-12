<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class m_dialy_summery_reset extends CI_Model {

  private $sd;
  private $mtb;
  private $trans_code = 52;
  private $mod = '003';
  private $tb_sum;
  private $tb_det;
  
  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);   
    $this->load->model('user_permissions');
    $this->max_no = $this->utility->get_max_no("t_daily_collection_sum_history", "rnno");

  }
  
  public function base_details() {
    $a['max_no'] = $this->utility->get_max_no("t_daily_collection_sum_history", "rnno");
    
    return $a;
  }


  public function validation()
  {
    $status         = 1;

    return $status;
  }
  
  
  public function save() {
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine) {
      throw new Exception($errMsg."==".$errFile."==". $errLine);
    }
    set_error_handler('exceptionThrower');
    try {    

      $validation_status = $this->validation();
      if ($validation_status == 1) {         
        if ($this->user_permissions->is_add('m_dialy_summery_reset')){


         $sqlInDet="INSERT INTO `t_daily_collection_det_history`(`cl`,`bc`,`nno`,`entry_no`,`bank_acc`,`amount`,`rnno`,`rddate`)
         (SELECT d.`cl`,d.`bc`,d.`nno`,d.`entry_no`,d.`bank_acc`,d.`amount`,'".$_POST['id']."','".$_POST['date']."'
         FROM `t_daily_collection_det` d
         JOIN `t_daily_collection_sum` s ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
         WHERE d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."' AND s.ddate<='".$_POST['rdate']."')";
         $queryInDet= $this->db->query($sqlInDet);  

         $SqlSlctD="SELECT * FROM `t_daily_collection_det` d
         JOIN `t_daily_collection_sum` s ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
         WHERE d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."' AND s.ddate<='".$_POST['rdate']."'";
         $querySlctD= $this->db->query($SqlSlctD);  

         if($querySlctD->num_rows()>0){
           $sqlDeDet="DELETE d FROM `t_daily_collection_det` d
           JOIN `t_daily_collection_sum` s ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
           WHERE d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."' AND s.ddate<='".$_POST['rdate']."'";
           $queryDeDet= $this->db->query($sqlDeDet); 
         }


         $sqlInSum="INSERT INTO `t_daily_collection_sum_history` (`cl`,`bc`,`nno`,`ddate`,`cash_acc`,`opb`,`cash_float`,`cash_sales_system`,`cash_sales_manual`,`rcp_transport`,`rcp_advance`,`rcp_others`,`rcp_cancel`,`cash_voucher`,`rcp_manual`,
         `dn_5000`,`dn_2000`,`dn_1000`,`dn_500`,`dn_100`,`dn_50`,`dn_20`,`dn_10`,`dn_coints`,`inv_cash`,`inv_credit`,`inv_finance`,`inv_card`,`inv_internal`,`inv_return`,`rcp_cash`,`rcp_card`,`rcp_cheque`,`is_cancel`,`shortage`,`cash_sale_tot`,`gift_sale_tot`,`rnno`,`rddate`)
         
         SELECT `cl`,`bc`,`nno`,`ddate`,`cash_acc`,`opb`,`cash_float`,`cash_sales_system`,`cash_sales_manual`,`rcp_transport`,`rcp_advance`,`rcp_others`,`rcp_cancel`,`cash_voucher`,`rcp_manual`,
         `dn_5000`,`dn_2000`,`dn_1000`,`dn_500`,`dn_100`,`dn_50`,`dn_20`,`dn_10`,`dn_coints`,`inv_cash`,`inv_credit`,`inv_finance`,`inv_card`,`inv_internal`,`inv_return`,`rcp_cash`,`rcp_card`,`rcp_cheque`,`is_cancel`,`shortage`,`cash_sale_tot`,`gift_sale_tot`,'".$_POST['id']."','".$_POST['date']."'
         FROM t_daily_collection_sum 
         WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND ddate<='".$_POST['rdate']."'";
         $queryInSum= $this->db->query($sqlInSum);  

         $SqlSlct="SELECT * FROM t_daily_collection_sum 
         WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND ddate<='".$_POST['rdate']."'";
         $querySlct= $this->db->query($SqlSlct);  

         if($querySlct->num_rows()>0){
           $sqlDeSum="DELETE FROM t_daily_collection_sum 
           WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND ddate<='".$_POST['rdate']."'";
           $queryDeSum= $this->db->query($sqlDeSum); 
         }


         $this->utility->save_logger("SAVE", 136, $this->max_no, $this->mod);
         echo $this->db->trans_commit();

       }else{
        echo "No permission to save records";
        $this->db->trans_commit();
      }
    } else {
      echo $validation_status;
      $this->db->trans_commit();
    }
  }catch (Exception $e) {
    $this->db->trans_rollback();
    echo $e->getMessage()."Operation fail please contact admin";
  }
}


public function PDF_report(){
  $invoice_number= $this->utility->invoice_format($_POST['qno']);
  $session_array = array(
   $this->sd['cl'],
   $this->sd['branch'],
   $invoice_number
 );
  $r_detail['session'] = $session_array;



  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $r_detail['branch']=$this->db->get('m_branch')->result();



  $r_detail['qno']=$_POST['qno'];
  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];
  $r_detail['type']=$_POST['type'];


  $sql="select * from t_daily_collection_sum_history 
  JOIN m_account on m_account.code=t_daily_collection_sum_history.cash_acc
  where cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'  AND ddate='".$_POST['pd']."' ";

  $query=$this->db->query($sql);
  if($query->num_rows>0){
    $r_detail['sum']=$query->result();
  }else{
    $r_detail['sum']=2;
  }


  $sql2="SELECT * from t_daily_collection_det_history d
  JOIN `t_daily_collection_sum` s ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
  WHERE d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."' AND s.ddate='".$_POST['pd']."'";

  $query2=$this->db->query($sql2);
  if($query->num_rows>0){
    $r_detail['bank_entry']=$query2->result();
  }else{
    $r_detail['bank_entry']=2;
  }


  $sql3="SELECT b.name, l.description 
  FROM m_branch b
  JOIN m_cluster l ON l.code = b.cl
  where cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";

  $query3=$this->db->query($sql3);
  if($query3->num_rows>0){
    $r_detail['cl']=$query3->result();
  }else{
    $r_detail['cl']=2;
  }



  $this->db->select(array('loginName'));
  $this->db->where('cCode',$this->sd['oc']);
  $r_detail['user']=$this->db->get('users')->result();



  $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

}

}

