
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cheque_in_hand extends CI_Model{
    
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
    $sql="SELECT
              tcr.`cl`,
              tcr.`bc`,
              tcr.`bc`,
              tcr.`ddate`,
              tcr.`trans_code`,
              tcr.`trans_no`,
              tcr.`bank`,
              tcr.`branch`,
              tcr.`account`,
              tcr.`cheque_no`,
              tcr.`amount`,
              tcr.`bank_date`,
              tcr.`status`,
              tcr.`oc`,
              tcr.`action_date`,
              tcr.`received_from_acc`,
              ma.`description` 
            FROM
              `t_cheque_received` tcr 
              JOIN m_account ma ON ma.`code`=tcr.`received_from_acc`
            WHERE STATUS='P' AND tcr.`ddate` <= '".$_POST['to']."' AND tcr.cl = '$cluster' AND tcr.bc = '$branch'";

            if(!empty($_POST['acc_code'])){
              $sql.=" AND tcr.`received_from_acc` = '$acc_code'";
            }
            $sql.=" ORDER BY tcr.bank_date";
        
       
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