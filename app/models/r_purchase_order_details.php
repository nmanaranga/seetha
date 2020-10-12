
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_purchase_order_details extends CI_Model{
    
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

          $query1=$this->db->query('SELECT SUM(`t_po_sum`.`total_amount`) AS gsum , SUM(`t_po_sum`.`total_amount`) AS nsum
			FROM `t_po_sum`
			WHERE `t_po_sum`.`cl`="'.$_POST['cluster'].'" AND `t_po_sum`.`bc`= "'.$_POST['branch'].'"
			and `t_po_sum`.`ddate` between "'.$_POST['from'].'" and "'.$_POST['to'].'"
			GROUP BY `t_po_sum`.`nno`  ORDER BY `t_po_sum`.`nno` ASC');
            $r_detail['sum']=$query1->result();
         
          $query = $this->db->query('SELECT s.`nno` , s.`ddate` , s.`ship_to_bc`,s.`supplier`, s.`deliver_date` , p.`name` , b.`name` AS ship,
			d.`item` as code , i.`description` ,  d.`current_qty` , d.`qty` AS approve , d.`amount` as net_amount 
			FROM `t_po_sum` s
			LEFT JOIN `m_supplier` p ON p.`code` = s.`supplier` 
			LEFT JOIN `m_branch` b ON b.`bc` = s.`ship_to_bc`
			LEFT JOIN `t_po_det` d ON d.`nno` = s.`nno` AND d.`cl` = s.`cl` AND d.`bc` = s.`bc`
			LEFT JOIN `m_item` i ON i.`code` = d.`item`
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