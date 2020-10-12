
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cancelled_petty_cash extends CI_Model{
    
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

    $cluster=$_POST['cluster'];
    $branch=$_POST['branch'];
    $acc=$_POST['acc_code'];
    $no_range_frm=$_POST['t_range_from'];
    $no_range_to=$_POST['t_range_to'];

    $r_detail['page']='A4';
    $r_detail['orientation']='P'; 

    $r_detail['cluster1']=$cluster;
    $r_detail['branch1']=$branch;
    $r_detail['acc']=$_POST['acc_code'];
    $r_detail['acc_des']=$_POST['acc_code_des'];
    $r_detail['t_no_from']=$_POST['t_range_from'];
    $r_detail['t_no_to']=$_POST['t_range_to'];
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];
      // var_dump($_POST['t_range_from'], $_POST['t_range_to']);
      // exit();
        $date_range=$_POST['from'];
        $acc_code=$_POST['acc_code'];
        $no_range=$_POST['t_range_from'];

        if(isset($_POST['chknumRange']))
        {
          $checkR=1;
        }else{
          $checkR=0;
        }
      
        if ($date_range!="") {
          $date1="AND date between'".$_POST['from']."' AND '".$_POST['to']."'";
        }
        else
          $date1="";
        
        if($acc_code!=""){
          $acc1="AND pettycash_account='".$_POST['acc_code']."'";
        }
        else
          $acc1="";

        if($checkR==1){
          $num_range="AND no between'".$_POST['t_range_from']."' AND '".$_POST['t_range_to']."'";
          }
        else
          $num_range="";
        
        if(!empty($cluster)) 
        {
           $cluster1= "AND cl ='".$_POST['cluster']."'";
        }
        else
         $cluster1="";
    $sql="SELECT 
              (SELECT
                  COUNT(*) 
                FROM
                  t_petty_cash_sum 
                WHERE `is_cancel` = '1' $date1 $acc1 $num_range 
                 $cluster1) AS counts, 
              no,
             `date`,
              PCM.description AS dis,
              `total`,
              PCM .cl,
              PCM .bc,
              MA.`description` AS description,
              MBC.`name`AS bc_name, 
              MCL.`description` AS cl_description

            FROM
              t_petty_cash_sum AS PCM 
              INNER JOIN `m_account` AS MA 
                ON MA.`code` = PCM .`pettycash_account` 
              INNER JOIN `m_branch` MBC 
                ON PCM .bc = MBC.bc 
              INNER JOIN `m_cluster` MCL 
                ON (MBC.`cl` = MCL.`code`) 
            WHERE `is_cancel` = '1' ";

         if(!empty($_POST['from']))
        {
           $sql.=" AND PCM.date between '".$_POST['from']."' AND '".$_POST['to']."'";
        }
       if(!empty($_POST['acc_code']))
        {
           $sql.=" AND MA.`code` = '$acc'";
        }
        
        if(!empty($cluster))
        {
           $sql.=" AND PCM.cl = '$cluster'";
        }
       if(!empty($branch))
        {
            $sql.=" AND PCM.bc = '$branch'";
        }
         
        if(!empty($_POST['t_range_from']) && empty($_POST['t_range_to']))
        {
           $sql.=" AND PCM.`no` >= '$no_range_frm'";
        }
        
         if(!empty($_POST['t_range_to']) && empty($_POST['t_range_from']))
        {
           $sql.=" AND PCM.`no` <= '$no_range_to'";
        }

        if(!empty($_POST['t_range_to']) && !empty($_POST['t_range_to']))
        {
           $sql.=" AND PCM.`no` BETWEEN  '$no_range_frm' AND '$no_range_to'";
        }

        $sql.="ORDER BY PCM.cl,PCM.bc,PCM.no ASC";
          

      $query=$this->db->query($sql);


      
     
    $sql_branch="SELECT 
                        MBC.`name`,
                        MCL.`description`,
                        MCL.`code`,
                         MBC.`bc`   
                        FROM
                            `m_branch` MBC
                            INNER JOIN `m_cluster` MCL
                                ON (MBC.`cl` = MCL.`code`) WHERE MBC.`bc`='$bc' AND MCL.`code`='$cl'";  
        
                 
          
    $r_detail['r_petty_cash_summery']=$this->db->query($sql)->result();
    //$r_detail['cancelled']=$this->db->query($sql1)->result();        
    $r_detail['r_branch_name']=$this->db->query($sql_branch)->result();       
    
    
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