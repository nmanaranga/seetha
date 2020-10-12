<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_total_sale_return extends CI_Model{
    
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


        $query = $this->db->query('SELECT r.nno,r.ddate,r.inv_no,r.cus_id,c.`name`,r.store,r.gross_amount,r.discount,r.net_amount,r.`store`,s.`description`,r.`sales_type` FROM `t_sales_return_sum` r
        JOIN m_customer c ON c.`code`=r.`cus_id`
        JOIN m_stores s ON s.`code`=r.`store`
        WHERE r.`cl`="'.$this->sd['cl'].'" AND r.`bc`="'.$this->sd['branch'].'"
        and r.ddate between "'.$_POST['from'].'" and "'.$_POST['to'].'" and r.is_cancel="0"
        ORDER BY r.`nno`');
        $r_detail['purchase']=$query->result();          

        if($query->num_rows()>0){
          $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
        }else{
          echo "<script>alert('No Data');window.close();</script>";
        }
          

     }
}