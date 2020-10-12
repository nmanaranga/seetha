
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_purchase_details extends CI_Model{
    
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

          $query1=$this->db->query('SELECT  sum(d.`qty` * d.`price`) as gsum  ,sum(d.`amount`+g.`additional`) as nsum, sum(g.`additional`) as addi  
            FROM `t_grn_sum` g
            LEFT JOIN `m_supplier` s ON g.`supp_id` = s.`code`
            LEFT JOIN `t_grn_det` d ON g.`cl`=d.`cl` AND g.`bc` =d.`bc` AND g.`nno` = d.`nno`
            LEFT JOIN `m_item` i ON d.`code` =i.`code` WHERE d.`cl`="'.$this->sd['cl'].'" AND d.`bc`= "'.$this->sd['branch'].'" 
            and g.ddate between "'.$_POST['from'].'" and "'.$_POST['to'].'" GROUP BY g.`nno` order by g.`nno`');
          $r_detail['sum']=$query1->result();
          
          $query = $this->db->query('SELECT g.`nno` ,g.`ddate` ,g.`supp_id`  ,s.`name` , g.`gross_amount` ,g.`net_amount` ,g.`inv_no` ,g.`store`  ,d.`code` , d.`qty`, (d.`qty` * d.`price`) as gross_a ,d.amount , d.`price`, d.`discount` ,i.`description`
            FROM `t_grn_sum` g
            LEFT JOIN `m_supplier` s ON g.`supp_id` = s.`code`
            LEFT JOIN `t_grn_det` d ON g.`cl`=d.`cl` AND g.`bc` =d.`bc` AND g.`nno` = d.`nno`
            LEFT JOIN `m_item` i ON d.`code` =i.`code` 
            WHERE g.`cl`="'.$this->sd['cl'].'" AND g.`bc`= "'.$this->sd['branch'].'" 
            and g.ddate between "'.$_POST['from'].'" and "'.$_POST['to'].'"order by g.`nno`');
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