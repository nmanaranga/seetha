
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cash_sales_ret extends CI_Model{
    
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
          $r_detail['page']='A4';
          $r_detail['orientation']='P';  
          $r_detail['card_no']=$_POST['card_no1'];
          $r_detail['dfrom']=$_POST['from'];
          $r_detail['dto']=$_POST['to'];

			    $sType='4';
			    $query1=$this->db->query('SELECT sum(s.`gross_amount`) as gsum ,sum(s.`net_amount`) as nsum
        	FROM `t_sales_return_sum` s
        	WHERE s.`cl`="'.$this->sd['cl'].'" and s.is_cancel="0" 
          AND s.`bc`= "'.$this->sd['branch'].'" AND s.`sales_type` = "'.$sType.'" and s.ddate between "'.$_POST['from'].'" and "'.$_POST['to'].'"');
          $r_detail['sum']=$query1->result();

          $sql_cancel='SELECT s.`nno` ,s.`ddate` ,s.`cus_id`  ,s.`gross_amount` ,s.`discount` ,s.`net_amount` ,m.`name` ,f.`description` as store
          FROM `t_sales_return_sum` s
          LEFT JOIN `m_customer` m ON s.`cus_id`=m.`code`
          LEFT JOIN `m_stores` f ON s.`store` = f.`code`
          WHERE s.`cl`="'.$this->sd['cl'].'" AND s.`bc`="'.$this->sd['branch'].'" AND s.`sales_type` = "'.$sType.'"  
          and s.ddate between "'.$_POST['from'].'" and "'.$_POST['to'].'" and s.is_cancel="1"
          ORDER BY s.`nno`';
          $r_detail['cancel']=$this->db->query($sql_cancel)->result();


          $query = $this->db->query('SELECT s.`nno` ,s.`ddate` ,s.`cus_id`  ,s.`gross_amount` ,s.`discount` ,s.`net_amount` ,m.`name` ,f.`description` as store
          FROM `t_sales_return_sum` s
          LEFT JOIN `m_customer` m ON s.`cus_id`=m.`code`
          LEFT JOIN `m_stores` f ON s.`store` = f.`code`
          WHERE s.`cl`="'.$this->sd['cl'].'" AND s.`bc`="'.$this->sd['branch'].'" AND s.`sales_type` = "'.$sType.'"  
          and s.ddate between "'.$_POST['from'].'" and "'.$_POST['to'].'" and s.is_cancel="0"
          ORDER BY s.`nno`');
          $r_detail['purchase']=$query->result();          



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