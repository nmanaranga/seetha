
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_petty_cash_details extends CI_Model{
    
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
   
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];
   
    
    $sql="SELECT 
                PCS.`no`,
                PCS.`date`,
                PCS.`description` AS s_description,
                PCS.`narration`,
                RG.`name`,
                RSC.`description`,
                PCS.`total`,
                PCS.`cl`,
                PCS.`bc`,
                A.d_description,
                A.`amount`,
                A.d_no
                FROM t_petty_cash_sum PCS
                INNER JOIN r_sales_category AS RSC 
                  ON (PCS.`category_id` = RSC.`code`) 
                LEFT JOIN r_groups AS RG 
                  ON (PCS.`group_sales_id` = RG.`code`)
                  JOIN(SELECT 
                PCD.`no` AS d_no,
                PCD.`description` AS d_description,
                PCD.`amount`,
                PCD.`cl`,
                PCD.`bc`
                   FROM t_petty_cash_det AS PCD 
              )A ON A.d_no=PCS.`no`
              WHERE PCS.`date` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND A.cl = '$cl' AND A.bc = '$bc' AND PCS.cl = '$cl' AND PCS.bc = '$bc'";


    $sql_branch="SELECT 
                        MBC.`name`,
                        MCL.`description`,
                        MCL.`code`,
                         MBC.`bc`   
                        FROM
                            `m_branch` MBC
                            INNER JOIN `m_cluster` MCL
                                ON (MBC.`cl` = MCL.`code`) WHERE MBC.`bc`='$bc' AND MCL.`code`='$cl'";  

                                     
          
    $r_detail['r_petty_cash_details']=$this->db->query($sql)->result();
    //$r_detail['r_petty_cash_details']=$this->db->query($sql_Pc_details)->result();       
    $r_detail['r_branch_name']=$this->db->query($sql_branch)->result();

   //var_dump($sql2);
  //exit();   
    
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