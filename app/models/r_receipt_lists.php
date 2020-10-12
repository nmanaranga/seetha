<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_receipt_lists extends CI_Model{
    
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
    $r_detail['card_no']=$_POST['card_no1'];
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];
    

    /*
    $query1=$this->db->query('SELECT sum(s.`gross_amount`) as gsum ,sum(s.`net_amount`) as nsum
    FROM `t_sales_sum` s
    LEFT JOIN `m_customer` c ON s.`cus_id`=c.`code`
    WHERE s.`cl`="'.$this->sd['cl'].'" AND s.`bc`= "'.$this->sd['branch'].'" GROUP BY c.`name`');
    $r_detail['sum']=$query1->result();
    */
    /*
    $query = $this->db->query('SELECT s.`nno` ,s.`ddate` ,s.`cus_id` ,s.`gross_amount` ,s.`net_amount`  ,c.`name` , s.`rep` ,s.`store` , s.`additional`
    FROM `t_sales_sum` s
    LEFT JOIN `m_customer` c ON s.`cus_id`=c.`code`
    WHERE s.`cl`="'.$this->sd['cl'].'" AND s.`bc`= "'.$this->sd['branch'].'" order by s.`nno`, c.`name`');
    */

    $query1=$this->db->query('SELECT SUM(r.`payment`) AS gsum , SUM(r.`balance` - r.`payment`) AS nsum
      FROM `t_receipt` r
      WHERE r.`cl`="'.$this->sd['cl'].'" AND r.`bc`= "'.$this->sd['branch'].'"
      and r.ddate between "'.$_POST['from'].'" and "'.$_POST['to'].'"');
    $r_detail['sum']=$query1->result();

    $sql="SELECT r.pay_cash as cash_amount, r.pay_receive_chq as cheque_amount, r.pay_ccard as pay_card,
    r.`nno` , r.`ddate` ,CONCAT(r.`cus_acc`,' - ',c.`name`) as cus , r.`rep`,   
    e.`name` as emp_name , r.`payment` , r.`balance` - r.`payment` AS balance,
    r.pay_post_dated_chq as pd_amount 
    FROM `t_receipt` r
    LEFT JOIN `m_customer` c ON c.`code` = r.`cus_acc`
    LEFT JOIN `m_employee` e ON e.`code` = r.`rep`
    WHERE r.ddate between '".$_POST['from']."' and '".$_POST['to']."'";

    if(!empty($_POST['cluster'])){
        $sql.=" AND r.cl = '".$_POST['cluster']."'";
    }
    if(!empty($_POST['branch'])){
        $sql.=" AND r.bc = '".$_POST['branch']."'";
    }
    $sql.=' group by r.`nno` ORDER BY r.`nno`'; 

    $query = $this->db->query($sql);
    
    $r_detail['purchase']=$query->result();  

    $sql_branch="SELECT 
    MBC.`name`,
    MCL.`description`,
    MCL.`code`,
    MBC.`bc`   
    FROM
    `m_branch` MBC
    INNER JOIN `m_cluster` MCL
    ON (MBC.`cl` = MCL.`code`) WHERE MBC.`bc`='".$_POST['branch']."' AND MCL.`code`='".$_POST['cluster']."'";  
    
    

    $r_detail['r_branch_name']=$this->db->query($sql_branch)->result();        
    
    //$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    if($this->db->query($sql)->num_rows()>0){
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