<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_credit_sales_summary extends CI_Model{
    
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
          $r_detail['orientation']='L';  
          $r_detail['card_no']=$_POST['card_no1'];
          $r_detail['dfrom']=$_POST['from'];
          $r_detail['dto']=$_POST['to'];
          $r_detail['custom_name']=$_POST['r_customer_des'];
          $r_detail['catgory']=$_POST['sales_category'];
          $sales_cat=$_POST['sales_category'];
          $rel_cus=$_POST['r_customer'];

          if($_POST['group_id']!=""){
            $sql=" AND s.group_no ='".$_POST['group_id']."' ";
          }else{
            $sql="";
          }

          $sql='SELECT s.ddate,s.nno,s.sub_no,s.cus_id,c.name,c.`nic`,s.category,s.pay_credit,s.pay_ccard,s.pay_cnote,s.pay_gift_voucher,
                IFNULL(ccd.`int_amount`,0.00) AS int_amount,
                  s.`net_amount`
               FROM `t_credit_sales_sum` s 
          JOIN m_customer c ON c.`code`=s.`cus_id` 
          LEFT JOIN `opt_credit_card_det` ccd ON ccd.`trans_code`=5 AND ccd.`trans_no`=s.`nno` AND ccd.`cl`=s.`cl` AND ccd.`bc`=s.`bc` WHERE  s.`cl`="'.$this->sd['cl'].'" AND s.`bc`= "'.$this->sd['branch'].'"
          AND s.is_cancel="0"
          AND s.`ddate` between "'.$_POST['from'].'" AND "'.$_POST['to'].'" '.$sql.' ';
  
            if(!empty($sales_cat))
            {
              $sql.=" AND s.`category` = '".$_POST['sales_category']."'";
            }
            if(!empty($cus))
            {
              $sql.=" AND s.`cus_id` = '".$_POST['r_customer']."'";
            }
                               
        $r_detail['sum']=$this->db->query($sql)->result(); 

      if($this->db->query($sql)->num_rows()>0){

        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
      }else{
        echo "<script>alert('No Data');window.close();</script>";
      }

     }
}