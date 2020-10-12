<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_transfer_rece_outstanding extends CI_Model{

  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_privilege_card'];
    $this->m_customer = $this->tables->tb['m_customer'];
    $this->t_sales_sum=$this->tables->tb['t_sales_sum'];
    $this->t_previlliage_trans=$this->tables->tb['t_previlliage_trans'];
    $this->t_privilege_card=$this->tables->tb['t_privilege_card']; 
  }

  public function base_details(){

    $a['table_data'] = $this->data_table();

    return $a;
  }


  public function PDF_report($RepTyp=""){
    
    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();
    $r_detail['page']='A4';
    $r_detail['orientation']='P';  

    $r_detail['acc_code']=$_POST['acc_code'];
    $r_detail['acc_code_des']=$_POST['acc_code_des'];

    $sql_outstanding="SELECT sub_cl,sub_bc,name,s.cl,s.bc,trans_no,trans_code,ddate,'Internal Transfer Outstanding' AS des, SUM(dr) AS amount , SUM(dr-cr) AS  balance
    FROM `t_internal_transfer_trans` s
    join m_branch b ON b.bc=s.bc
    WHERE acc_code='".$_POST['acc_code']."' AND s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' 
    GROUP BY s.cl,s.bc,acc_code,trans_code,trans_no
    HAVING balance > 0";  
    $query=$this->db->query($sql_outstanding);
    $r_detail['branch_outs']=$this->db->query($sql_outstanding)->result();        
    
    
    if($query->num_rows()>0){
      $exTy=($RepTyp=="")?'pdf':'excel';
      $this->load->view($_POST['by'].'_'.$exTy,$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }
  }

  public function Excel_report(){
    $this->PDF_report("Excel");
  }
}