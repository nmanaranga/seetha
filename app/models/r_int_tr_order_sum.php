<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_int_tr_order_sum extends CI_Model{
    
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

    $this->db->select(array('code','description'));
    $this->db->where("code",$_POST['sales_category']);
    $r_detail['category']=$this->db->get('r_sales_category')->result();

    $r_detail['page']='A4';
    $r_detail['orientation']='P';  
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];
    $r_detail['cl']=$_POST['cluster'];
    $r_detail['bc']=$_POST['branch'];


     $query = $this->db->query("SELECT os.cl AS from_cl,
                        mc.`description` AS fr_cl,
                        os.bc AS from_bc,
                        mb.`name` AS fr_bc,
                        os.nno,
                        sub_no,
                        ddate,
                        mb1.cl as to_cl,
                        to_bc,
                        os.cl,
                        os.bc,
                        mb1.`name` AS t_bc,
                        j.amount, 
                        (CASE 
                              WHEN os.`status`=1 THEN 'PENDING'
                              WHEN os.`status`=2 THEN 'ISSUED'
                              WHEN os.`status`=3 THEN 'REJECTED'
                              END) AS status
                      FROM `t_internal_transfer_order_sum` os
                      JOIN m_cluster mc ON mc.`code`=os.`cl`
                      JOIN m_branch mb ON mb.`bc`=os.`bc`
                      JOIN m_branch mb1 ON mb1.`bc`=os.`to_bc`
                      JOIN(SELECT cl,bc,nno,SUM(item_cost*qty) AS amount FROM `t_internal_transfer_order_det` GROUP BY cl,bc,nno)j ON j.nno=os.`nno` AND j.cl=os.cl  AND j.bc=os.`bc` 
                      WHERE  os.ddate between'".$_POST['from']."' AND '".$_POST['to']."' AND os.cl='".$_POST['cluster']."' AND os.bc='".$_POST['branch']."' 
                      GROUP BY os.cl,os.bc,os.nno
                      ORDER BY os.nno");
            
      $r_detail['ordr_sum']=$query->result();  

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