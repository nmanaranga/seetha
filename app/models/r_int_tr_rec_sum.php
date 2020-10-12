<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_int_tr_rec_sum extends CI_Model{

  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
  }
  
  public function base_details(){

    $a['table_data'] = $this->data_table();

    return $a;
  }
  

  public function PDF_report(){

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $this->db->select(array('code','description'));
    $this->db->where("code",$_POST['sales_category']);
    $r_detail['category']=$this->db->get('r_sales_category')->result();

    $r_detail['page']='A4';
    $r_detail['orientation']='P';  
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];
    $r_detail['cl']=$_POST['cluster'];
    $r_detail['bc']=$_POST['branch'];


    $query = $this->db->query("SELECT ts.`nno` AS rec_no,ts.`sub_no` AS res_sub,ts.`ddate`,ts.`ref_trans_no` AS tr_no,ts.`ref_sub_no` AS tr_sub,ts.`issue_bc`,b.`name`,ts.`store`,s.`description` AS stores,ts.`net_amount`
      FROM `t_internal_transfer_sum` ts 
      JOIN `m_branch` b ON b.`bc`=ts.`issue_bc`
      JOIN m_stores s ON s.`code`=ts.`store`
      WHERE ts.`trans_code`=43 AND ts.ddate between'".$_POST['from']."' AND '".$_POST['to']."' AND ts.cl='".$_POST['cluster']."' AND ts.bc='".$_POST['branch']."' ");

    $r_detail['rec_sum']=$query->result();  

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