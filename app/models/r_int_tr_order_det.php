<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class r_int_tr_order_det extends CI_Model{    
    private $sd;
    private $mtb;    
    private $mod = '003';    
    function __construct(){
      parent::__construct();
      $this->sd = $this->session->all_userdata();
      $this->load->database($this->sd['db'], true);
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
      $r_detail['category_field']=$_POST['sales_category'];  
            
      $sql="SELECT  s.bc as f_bc,
                    s.cl as f_cl,
                    bb.name as f_bc_name,
                    s.`nno`,
                    s.`sub_no`,
                    s.`to_bc`, 
                    b.name AS to_bc_name,
                    s.`ddate`,
                    b.`cl` AS to_cl,
                    (CASE
                      WHEN s.`status` = 1 THEN 'PENDING' 
                      WHEN s.`status` = 2 THEN 'ISSUED' 
                      WHEN s.`status` = 3 THEN 'REJECTED' 
                      END
                    )AS status,
                    d.`item_code`,
                    i.`description`,
                    d.`qty`,
                    d.`item_cost`,
                    (d.`qty` * d.item_cost) AS amount,
                    j.total  
            FROM t_internal_transfer_order_sum s
            JOIN m_branch b ON b.`bc` = s.`to_bc`
            JOIN m_branch bb ON bb.`bc` = s.`bc`
            JOIN t_internal_transfer_order_det d ON d.`cl` = s.`cl` AND d.`bc` = s.`bc` AND d.`nno` = s.`nno`
            JOIN m_item i ON i.`code` = d.`item_code`
            JOIN (SELECT cl,bc,nno,SUM(item_cost * qty)AS total FROM t_internal_transfer_order_det GROUP BY cl,bc,nno)j ON j.cl=s.`cl` 
              AND j.bc=s.`bc` AND j.nno=s.nno 
            WHERE s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."'
            AND s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
            GROUP BY s.cl,s.bc,d.`item_code`,s.nno
            ORDER BY s.`nno`";

      $query=$this->db->query($sql);

      $r_detail['sum']=$query->result();
         
      if($query->num_rows()>0){
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
      }else{
        echo "<script>alert('No Data');window.close();</script>";
      }
  }
}