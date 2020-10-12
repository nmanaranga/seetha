
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_recievable_invoice_details extends CI_Model{
    
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
                RIS.`no`,
                RIS.`date`,
                RIS.`description`,
                RIS.`receivable_account`,
                RIS.`receivable_type`,
                RIS.`total`,
                JTS.`description` AS r_type,
                MA.`description` AS ac_name,
                MAC.`description` AS d_ac_name,
                RID.`account_code`,
                RID.`amount`,
                RID.`no` AS D_nno
                FROM t_receivable_invoice_sum AS RIS
                LEFT JOIN m_account AS MA
                ON (MA.`code`=RIS.`receivable_account`)
                LEFT JOIN m_journal_type_sum AS JTS
                ON (JTS.`code`=RIS.`receivable_type`)
                JOIN t_receivable_invoice_det AS RID
                ON (RID.`no`=RIS.`no` AND RID.`bc`=RIS.`bc` AND RID.`cl`=RIS.`cl`)
                LEFT JOIN m_account AS MAC
                ON (RID.`account_code`=MAC.`code`)
                WHERE RIS.`date` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND RIS.cl = '$cl' AND RID.bc = '$bc' AND RIS.cl = '$cl' AND RID.bc = '$bc'";



    $sql_branch="SELECT 
                        MBC.`name`,
                        MCL.`description`,
                        MCL.`code`,
                         MBC.`bc`   
                        FROM
                            `m_branch` MBC
                            INNER JOIN `m_cluster` MCL
                                ON (MBC.`cl` = MCL.`code`) WHERE MBC.`bc`='$bc' AND MCL.`code`='$cl'";  
        
        
                    
          
    $r_detail['r_payable_invoice_details']=$this->db->query($sql)->result();       
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