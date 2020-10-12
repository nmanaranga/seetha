<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class payment_cash_sales_sum extends CI_Model{
    
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

    $r_detail['category_field']=$_POST['sales_category'];

    if($_POST['group_id']!=""){
      $sql=" AND s.group_no ='".$_POST['group_id']."' ";
      $sql2=" AND s.group_no ='".$_POST['group_id']."' ";
    }else{
      $sql="";
      $sql2="";
    }

    if($_POST['sales_category']!="0"){

      $query = $this->db->query('SELECT  s.`nno`,
                    s.`cus_id`,
                    c.`name`,
                    s.`ddate`,
                    s.`gross_amount` gross,
                    discount_amount AS discount,
                    s.`net_amount`,
                    pay_cash,
                    pay_ccard
                    FROM `t_cash_sales_sum` s
                    JOIN `m_customer` c ON s.`cus_id`=c.`code`                   
                  WHERE s.`cl` = "'.$this->sd['cl'].'" 
                    AND s.`bc` = "'.$this->sd['branch'].'"
                    AND s.category ="'.$_POST['sales_category'].'"
                    AND s.`ddate` BETWEEN "'.$_POST['from'].'" 
                    AND "'.$_POST['to'].'" '.$sql.'
                  GROUP BY s.nno ' );
      
      $r_detail['purchase']=$query->result();  

    }else{  

      $query = $this->db->query('SELECT  s.`nno`,
                s.`cus_id`,
                c.`name`,
                s.`ddate`,
                s.`gross_amount` gross,
                discount_amount AS discount,
                s.`net_amount`,
                pay_cash,
                pay_ccard
              FROM `t_cash_sales_sum` s
              JOIN `m_customer` c ON s.`cus_id`=c.`code`
              JOIN `m_stores` ms ON ms.`code` = s.`store` 
            WHERE s.`cl` = "'.$this->sd['cl'].'" 
              AND s.`bc` = "'.$this->sd['branch'].'"
              AND s.`ddate` BETWEEN "'.$_POST['from'].'" 
              AND "'.$_POST['to'].'" '.$sql.'
            GROUP BY s.nno ' );

        $r_detail['purchase']=$query->result();   
    }


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