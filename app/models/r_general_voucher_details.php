
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_general_voucher_details extends CI_Model{
    
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

    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];

    $r_detail['page']='A4';
    $r_detail['orientation']='L';  
    $r_detail['card_no']=$_POST['card_no1'];
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];
  
    
    $sql="SELECT 
                VGD.`nno` AS D_nno,
                VGD.`acc_code`,
                VGD.`amount`,
                VGS.`nno` ,
                VGS.`ddate`,
                VGS.`type`,
                VGS.`note`,
                VGS.`paid_acc`,
                VGS.`category_id`,
                VGS.`group_id`,
                VGS.`cash_amount`,
                VGS.`cheque_amount`,
                MA.`description`,
                MACC.`description` AS acc_name,
                SC.`description` AS category,
                RG.`name` AS group_name
                FROM
                  `t_voucher_gl_det` AS VGD
                  JOIN  `t_voucher_gl_sum` AS VGS ON (VGD.`nno`=VGS.`nno` AND VGD.`cl`=VGS.`cl` AND VGD.`bc`=VGS.`bc`)
                  LEFT JOIN `m_account` MA ON (VGS.`paid_acc` = MA.`code`)
                  LEFT JOIN `m_account` MACC ON (VGD.`acc_code`=MACC.`code`)
                  LEFT JOIN r_sales_category  SC ON(SC.`code`=VGS.`category_id`)
                  LEFT JOIN r_groups RG ON(RG.`code`=VGS.`group_id`)
                    WHERE VGS.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND VGD.`cl` = '$cl' AND VGS.`cl` = '$cl' AND VGD.`bc` = '$bc' AND VGS.`bc` = '$bc'";



    $sql_branch="SELECT 
                        MBC.`name`,
                        MCL.`description`,
                        MCL.`code`,
                         MBC.`bc`   
                        FROM
                            `m_branch` MBC
                            INNER JOIN `m_cluster` MCL
                                ON (MBC.`cl` = MCL.`code`) WHERE MBC.`bc`='$bc' AND MCL.`code`='$cl'";  
        
               
          
   $r_detail['r_general_voucher_details']=$this->db->query($sql)->result();       
    $r_detail['r_branch_name']=$this->db->query($sql_branch)->result();       
    
    //$this->load->view($_POST['by'].'_'.'pdf',$r_detail);

    if($this->db->query($sql)->num_rows()>0)
    {
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }
    else
    {
        echo "<script>alert('No Data');window.close();</script>";
    }
  }
}