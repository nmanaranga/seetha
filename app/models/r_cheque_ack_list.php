
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cheque_ack_list extends CI_Model{
    
  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct(){
  parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_cheque_received'];
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
    $r_detail['acc']=$_POST['acc_code'];

    $acc_code=$_POST['acc_code'];
    $cluster=$_POST['cluster'];
    $branch=$_POST['branch'];

    $sql="SELECT  s.`date`,
                  s.`nno`,
                  CONCAT(s.customer, ' - ',c.name) AS customer,
                  d.`cheque_no`,
                  d.`amount`,
                  d.`status`,
                  d.`realize_date`
          FROM t_receipt_temp_cheque_sum s
          JOIN t_receipt_temp_cheque_det d ON d.cl=s.cl AND d.bc=s.bc AND d.nno=s.`nno`
          JOIN m_customer c ON s.customer = c.`code`
          WHERE s.date BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";
   
          if(!empty($_POST['cluster'])){
            $sql.=" AND s.`cl` = '".$_POST['cluster']."'";
          }

          if(!empty($_POST['branch'])){
            $sql.=" AND s.`bc` = '".$_POST['branch']."'";
          }
          
          $sql.="GROUP BY d.cl,d.bc,d.nno,d.cheque_no ORDER BY d.realize_date";
        
    $sql_branch="SELECT 
                        MBC.`name`,
                        MCL.`description`,
                        MCL.`code`,
                        MBC.`bc`   
                        FROM
                            `m_branch` MBC
                            INNER JOIN `m_cluster` MCL
                                ON (MBC.`cl` = MCL.`code`) WHERE MBC.`bc`='$branch' AND MCL.`code`='$cluster'";  
        
        
            
    $r_detail['r_branch_name']=$this->db->query($sql_branch)->result();  
     
    $r_detail['r_cheque_in_hand']=$this->db->query($sql)->result();       
     
   
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