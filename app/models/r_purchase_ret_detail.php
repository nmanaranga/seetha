
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_purchase_ret_detail extends CI_Model{
    
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

          $query1=$this->db->query('SELECT `t_pur_ret_sum`.`grn_no`, `t_pur_ret_sum`.`memo` ,SUM(`t_pur_ret_det`.`qty` * `t_pur_ret_det`.`price`) AS gsum , `t_pur_ret_sum`.`net_amount` AS nsum, `t_pur_ret_sum`.`other` as addi
			FROM `t_pur_ret_sum`
			LEFT JOIN `t_pur_ret_det` ON `t_pur_ret_sum`.`nno`=`t_pur_ret_det`.`nno` AND `t_pur_ret_sum`.`cl`=`t_pur_ret_det`.`cl` AND `t_pur_ret_sum`.`bc`=`t_pur_ret_det`.`bc`
      WHERE `t_pur_ret_sum`.`cl`="'.$_POST['cluster'].'" AND `t_pur_ret_sum`.`bc`= "'.$_POST['branch'].'" 
            and `t_pur_ret_sum`.`ddate` between "'.$_POST['from'].'" and "'.$_POST['to'].'"
            GROUP BY `t_pur_ret_sum`.`nno`  ORDER BY `t_pur_ret_sum`.`nno` ASC');
            $r_detail['sum']=$query1->result();
         
          $query = $this->db->query('SELECT s.`nno` , s.`ddate` , s.`supp_id`,  p.`name` , s.`store` as store_id , t.`description` as store , d.`code` , m.`description` ,
			 d.`qty` , d.`price` , d.`qty` * d.`price` AS gross , d.`discount` , d.`price` * d.`qty` - d.`discount` AS net_amount
			FROM `t_pur_ret_sum` s
			LEFT JOIN `m_supplier` p ON s.`supp_id` = p.`code`
			LEFT JOIN `m_stores` t ON s.`store` = t.`code`
			LEFT JOIN `t_pur_ret_det` d  ON s.`nno` = d.`nno` AND s.`cl` = d.`cl` AND s.`bc` = d.`bc`  
			LEFT JOIN `m_item` m ON m.`code` = d.`code`
			WHERE s.`cl`="'.$_POST['cluster'].'" AND s.`bc`= "'.$_POST['branch'].'"
			 and s.`ddate` between "'.$_POST['from'].'" and "'.$_POST['to'].'"
			 order by s.`nno` ASC');
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