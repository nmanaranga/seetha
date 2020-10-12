
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cash_sales_ret_detail extends CI_Model{
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
  parent::__construct();
  
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_privilege_card'];
    $this->m_customer = $this->tables->tb['m_customer'];
    $this->t_sales_sum=$this->tables->tb['t_credit_sales_sum'];
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
          /*
         	$query1=$this->db->query('SELECT sum(s.`gross_amount`) as gsum ,sum(s.`net_amount`) as nsum
            FROM `t_credit_sales_sum` s
            LEFT JOIN `m_customer` c ON s.`cus_id`=c.`code`
            LEFT JOIN `t_credit_sales_det` d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
            LEFT JOIN `m_item` i ON d.`code`=i.`code` WHERE d.`cl`="'.$this->sd['cl'].'" AND d.`bc`= "'.$this->sd['branch'].'" GROUP BY c.`name`');
            $r_detail['sum']=$query1->result();
		  */
 			$sType = '4';

          $query1=$this->db->query('SELECT `t_sales_return_sum`.`gross_amount` as gsum , `t_sales_return_sum`.`net_amount` as nsum
            FROM `t_sales_return_sum`
            LEFT JOIN `t_sales_return_det` ON `t_sales_return_sum`.`nno`=`t_sales_return_det`.`nno` AND `t_sales_return_det`.`cl` = `t_sales_return_sum`.`cl` AND `t_sales_return_det`.`bc` = `t_sales_return_sum`.`bc`
            WHERE  `t_sales_return_sum`.`is_cancel`="0" AND `t_sales_return_sum`.`cl`="'.$this->sd['cl'].'" AND `t_sales_return_sum`.`bc`= "'.$this->sd['branch'].'" AND `t_sales_return_sum`.`sales_type` = "'.$sType.'" 
            and `t_sales_return_sum`.`ddate` between "'.$_POST['from'].'" and "'.$_POST['to'].'"
             GROUP BY `t_sales_return_sum`.`nno`  ORDER BY `t_sales_return_det`.`nno` ASC');
            $r_detail['sum']=$query1->result();
         
          $query = $this->db->query('SELECT `t_sales_return_sum`.`nno`, 
    			`t_sales_return_det`.`code`,
    			`t_sales_return_det`.`qty`,
    			`t_sales_return_sum`.`ddate`,
    			`m_item`.`description`,
    			`t_sales_return_sum`.`cus_id`,
    			`m_customer`.`name`,
    			`m_stores`.`description` AS store,
    			`m_employee`.`name` AS rep,
    			`t_sales_return_sum`.`rep` as rep_id,
    			`t_sales_return_det`.`price`,
    			`t_sales_return_det`.`qty` * `t_sales_return_det`.`price` AS gross_a, 
    			`t_sales_return_det`.`discount`, 
    			`t_sales_return_det`.`amount`- `t_sales_return_det`.`discount` AS net_amount
    			FROM `t_sales_return_sum`
    			LEFT JOIN `t_sales_return_det` ON `t_sales_return_det`.`nno` = `t_sales_return_sum`.`nno` AND `t_sales_return_det`.`cl` = `t_sales_return_sum`.`cl` AND `t_sales_return_det`.`bc` = `t_sales_return_sum`.`bc`
    			LEFT JOIN `m_employee` ON `m_employee`.`code` = `t_sales_return_sum`.`rep`
    			LEFT JOIN `m_customer` ON `m_customer`.`code` = `t_sales_return_sum`.`cus_id`
    			LEFT JOIN `m_stores` ON `m_stores`.`code` = `t_sales_return_sum`.`store`
    			LEFT JOIN `m_item` ON `m_item`.`code`= `t_sales_return_det`.`code`
    			WHERE `t_sales_return_sum`.`is_cancel`="0" AND `t_sales_return_sum`.`cl`="'.$this->sd['cl'].'" AND `t_sales_return_sum`.`bc`= "'.$this->sd['branch'].'" AND `t_sales_return_sum`.`sales_type` = "'.$sType.'" 
    			and `t_sales_return_sum`.`ddate` between "'.$_POST['from'].'" and "'.$_POST['to'].'"
    			order by `t_sales_return_det`.`nno` ASC');
          $r_detail['purchase']=$query->result();          
          
          //$this->load->view($_POST['by'].'_'.'pdf',$r_detail);

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