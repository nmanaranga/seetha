<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_credit_sales_sum_02 extends CI_Model{

  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);

  }

  public function base_details(){

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
    $r_detail['card_no']=$_POST['card_no1'];
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];
    $r_detail['type']="r_credit_sales_sum_02";

    $r_detail['category_field']=$_POST['sales_category'];
    $r_detail['group']=$_POST['group_id'];
    $r_detail['group_des']=$_POST['group_name'];

    if(!empty($_POST['sales_category'])){
      $category = "AND sm.`category` = '".$_POST['sales_category']."'";
    }else{
      $category ="";
    }

    if(!empty($_POST['group_id'])){
      $group = "AND sm.`group_no` = '".$_POST['group_id']."'";
    }else{
      $group ="";
    }



    $query = $this->db->query('SELECT 
      mc.`name`,
      dt.`nno`,
      sm.`ddate`,
      sm.`net_amount`,
      SUM(dt.`qty`* bc.`purchase_price`) AS purchase_tot,
      SUM(dt.`qty` *bc.`min_price`) AS min_tot,
      SUM(dt.`qty` * bc.`max_price`) AS max_tot
      FROM t_credit_sales_sum sm
      JOIN t_credit_sales_det dt ON dt.`cl`=sm.`cl` AND dt.`bc`=sm.`bc` AND dt.`nno`=sm.`nno`
      JOIN t_item_batch bc ON bc.`item`=dt.`code` AND bc.`batch_no`=dt.`batch_no`
      JOIN m_customer mc ON mc.`code`=sm.`cus_id` 
      WHERE dt.`cl` ="'.$this->sd['cl'].'"
      AND dt.bc = "'.$this->sd['branch'].'"
      AND sm.`ddate` between "'.$_POST['from'].'" and "'.$_POST['to'].'"
      '.$category.' '.$group.'
      GROUP BY sm.cl, sm.bc, sm.nno
      ORDER BY dt.`nno` ');

    $r_detail['data']=$query->result();

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