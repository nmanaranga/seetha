<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_credit_sales_summary_group extends CI_Model{

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

    $this->db->select(array('code','description'));
    $this->db->where("code",$_POST['sales_category']);
    $r_detail['category']=$this->db->get('r_sales_category')->result();

    $r_detail['page']='A4';
    $r_detail['orientation']='P';  
    $r_detail['card_no']=$_POST['card_no1'];
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];

    $r_detail['category_field']=$_POST['sales_category'];
    $r_detail['group']=$_POST['group_id'];
    $r_detail['group_des']=$_POST['group_name'];


    $sql='SELECT SUM(`t_credit_sales_sum`.gross_amount) AS gsum,t_credit_sales_sum.additional_add,t_credit_sales_sum.additional_deduct,
    SUM(`t_credit_sales_sum`.`net_amount`) AS nsum,
    IFNULL(SUM(ad.amount),0) AS addi 
    FROM `t_credit_sales_sum`           
    LEFT JOIN `t_credit_sales_additional_item` ad 
    ON ad.nno = `t_credit_sales_sum`.`nno`
    AND `t_credit_sales_sum`.`cl` = `ad`.`cl`
    AND `t_credit_sales_sum`.`bc` = `ad`.`bc`          
    WHERE t_credit_sales_sum.is_cancel=0  AND `t_credit_sales_sum`.`cl`="'.$this->sd['cl'].'" AND `t_credit_sales_sum`.`bc`= "'.$this->sd['branch'].'"
    and `t_credit_sales_sum`.`ddate` between "'.$_POST['from'].'" and "'.$_POST['to'].'" AND t_credit_sales_sum.group_no ="'.$_POST['group_id'].'"';

    if($_POST['sales_category']!=""){
      $sql.=' AND t_credit_sales_sum.category ="'.$_POST['sales_category'].'"';
    }

    $r_detail['sum']=$this->db->query($sql)->result();

    $sql1 ='SELECT  s.`nno`,
    s.`sub_no`,
    s.`cus_id`,
    c.`name`,
    s.`ddate`,
    (s.`gross_amount` -s.`total_foc_amount` )gross,
    discount_amount AS discount,
    s.`net_amount`,
    s.additional_add,
    s.additional_deduct,
    ms.description,
    s.`vat_amount`,
    s.`cus_name`,
    s.`do_no`
    FROM `t_credit_sales_sum` s
    JOIN `m_customer` c ON s.`cus_id`=c.`code`
    JOIN `m_stores` ms ON ms.`code` = s.`store` 
    WHERE s.`cl` = "'.$this->sd['cl'].'" 
    AND s.`bc` = "'.$this->sd['branch'].'"
    AND s.is_cancel=0
    AND s.`ddate` BETWEEN "'.$_POST['from'].'" 
    AND "'.$_POST['to'].'" AND s.group_no ="'.$_POST['group_id'].'"
    GROUP BY s.nno ';

    if($_POST['sales_category']!=""){
      $sql.=' AND s.category ="'.$_POST['sales_category'].'"';
    }

    $query=$this->db->query($sql1);

    if($query->num_rows()>0){
      $r_detail['purchase'] = $query->result();
    }else{
      $r_detail['purchase'] = 0;
    }

    $cancel_sql='SELECT  s.`nno`,
    s.`sub_no`,
    s.`cus_id`,
    c.`name`,
    s.`ddate`,
    do_no,
    cus_name,
    (s.`gross_amount` -s.`total_foc_amount` )gross,
    discount_amount AS discount,
    s.`net_amount`,
    s.additional_add,
    s.additional_deduct,
    ms.description,
    s.`vat_amount`
    FROM `t_credit_sales_sum` s
    JOIN `m_customer` c ON s.`cus_id`=c.`code`
    JOIN `m_stores` ms ON ms.`code` = s.`store` 
    WHERE s.`cl` = "'.$this->sd['cl'].'" 
    AND s.`bc` = "'.$this->sd['branch'].'"
    AND s.is_cancel=1
    AND s.`ddate` BETWEEN "'.$_POST['from'].'" 
    AND "'.$_POST['to'].'"  AND s.group_no ="'.$_POST['group_id'].'"
    GROUP BY s.nno ';

    $r_detail['cancel']=$this->db->query($cancel_sql)->result();

    $sql_crn="SELECT ifnull(sum(amount),0) as tot_amount FROM t_credit_note
    WHERE `acc_code` IN (SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
    AND `ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND dis_type='5' AND is_cancel=0";

    $query_crn = $this->db->query($sql_crn);

    if($query_crn->num_rows()>0){
      $r_detail['crn'] = $query_crn->row()->tot_amount;
    }else{
      $r_detail['crn'] = 0;
    }


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