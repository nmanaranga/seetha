
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_advanced_payment_lists extends CI_Model{
    
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

    $cluster=$_POST['cluster'];
    $branch=$_POST['branch'];
    $acc=$_POST['acc_code'];
    $no_range_frm=$_POST['t_range_from'];
    $no_range_to=$_POST['t_range_to'];

    $r_detail['acc']=$_POST['acc_code'];
    $r_detail['t_no_from']=$_POST['t_range_from'];
    $r_detail['t_no_to']=$_POST['t_range_to'];

    $r_detail['cluster1']=$cluster;
    $r_detail['branch1']=$branch;
    $r_detail['acc']=$_POST['acc_code'];
    $r_detail['acc_des']=$_POST['acc_code_des'];
    
   

    $date_range=$_POST['from'];
    $acc_code=$_POST['acc_code'];
    $no_range=$_POST['t_range_from'];

    if(isset($_POST['chknumRange']))
    {
      $checkR=1;
    }else{
      $checkR=0;
    }

    if(isset($_POST['cchkdate1']))
    {
      $checkD=1;
    }else{
      $checkD=0;
    }

  if($checkD==1){

      $r_detail['dfrom']=$_POST['from'];
      $r_detail['dto']=$_POST['to'];

      $date1="AND ddate between'".$_POST['from']."' AND '".$_POST['to']."'";
      }
    else
    {
      $date1="";

      $r_detail['dfrom']="";
      $r_detail['dto']="";
    }

    
   
    // if($acc_code!=""){
    //   $acc1="AND pettycash_account='".$_POST['acc_code']."'";
    // }
    // else
    //   $acc1="";

    if($checkR==1){

      $r_detail['t_no_from']=$_POST['t_range_from'];
      $r_detail['t_no_to']=$_POST['t_range_to'];

      $num_range="AND nno between'".$_POST['t_range_from']."' AND '".$_POST['t_range_to']."'";
      }
    else
    {
      $num_range="";
      $r_detail['t_no_from']="";
      $r_detail['t_no_to']="";
    }
    
    if(!empty($cluster)) 
    {
       $cluster1= "AND cl ='".$_POST['cluster']."'";
    }
    else
     $cluster1="";

   if(!empty($branch)) 
    {
       $branch1= " AND bc ='".$_POST['branch']."'";
    }
    else
     $branch1="";
    
    $sql="SELECT 
          (SELECT
                  COUNT(*) 
                FROM
                 t_advance_sum 
                WHERE `is_cancel` = '0' $date1 $acc1 $num_range 
                 $cluster1 $branch1) AS counts, 
             TAS.`ddate`,
             TAS.`nno`,
             TAS.`cn_no`,
             TAS.`expire_date`,
             TAS.`total_amount`,
             MC.`nic`,
             MC.`name`,
             TAS.cl,
             TAS.bc,
             TAS.cash_amount,
             TAS.card_amount,
             TAS.cheque_amount,
             MBC.`name`AS bc_name, 
             MCL.`description` AS cl_description     
            FROM
                `t_advance_sum` AS TAS
                INNER JOIN `m_customer` AS MC
                    ON TAS.`acc_code` = MC.`code` 
                INNER JOIN `m_account` AS MA 
                  ON MA.`code`=TAS.`acc_code`
                   INNER JOIN `m_branch` MBC 
                    ON TAS.bc = MBC.bc 
                  INNER JOIN `m_cluster` MCL 
                    ON (MBC.`cl` = MCL.`code`)
            WHERE `is_cancel` = '0' ";

     // echo($_POST['acc_code']);
        
       if(!empty($_POST['from']))
        {
           $sql.=" AND TAS.ddate between '".$_POST['from']."' AND '".$_POST['to']."'";
        }
        if(!empty($_POST['acc_code']))
        {
           $sql.=" AND MA.`code` = '$acc'";
        }
        
        if(!empty($cluster))
        {
           $sql.=" AND  TAS.cl = '$cluster'";
        }
       if(!empty($branch))
        {
            $sql.=" AND  TAS.bc = '$branch'";
        }
         
        if(!empty($_POST['t_range_from']) && empty($_POST['t_range_to']))
        {
           $sql.=" AND  TAS.`nno` >= '$no_range_frm'";
        }
        
         if(!empty($_POST['t_range_to']) && empty($_POST['t_range_from']))
        {
           $sql.=" AND TAS.`nno` <= '$no_range_to'";
        }

        if(!empty($_POST['t_range_to']) && !empty($_POST['t_range_to']))
        {
           $sql.=" AND  TAS.`nno` BETWEEN  '$no_range_frm' AND '$no_range_to'";
        }
         $sql.="ORDER BY TAS.cl,TAS.bc,TAS.nno ASC";

        $query=$this->db->query($sql);  


    $sql1="SELECT 
          (SELECT
                  COUNT(*) 
                FROM
                 t_advance_sum 
                WHERE `is_cancel` = '1' $date1 $acc1 $num_range 
                 $cluster1 $branch1) AS counts, 
             TAS.`ddate`,
             TAS.`nno`,
             TAS.`cn_no`,
             TAS.`expire_date`,
             TAS.`total_amount`,
             MC.`nic`,
             MC.`name`,
             TAS.cl,
             TAS.bc,
             TAS.cash_amount,
             TAS.card_amount,
             TAS.cheque_amount,
             MBC.`name`AS bc_name, 
             MCL.`description` AS cl_description     
            FROM
                `t_advance_sum` AS TAS
                INNER JOIN `m_customer` AS MC
                    ON TAS.`acc_code` = MC.`code` 
                INNER JOIN `m_account` AS MA 
                  ON MA.`code`=TAS.`acc_code`
                   INNER JOIN `m_branch` MBC 
                    ON TAS.bc = MBC.bc 
                  INNER JOIN `m_cluster` MCL 
                    ON (MBC.`cl` = MCL.`code`)
            WHERE `is_cancel` = '1' ";

     // echo($_POST['acc_code']);
        
       if(!empty($_POST['from']))
        {
           $sql1.=" AND TAS.ddate between '".$_POST['from']."' AND '".$_POST['to']."'";
        }
        if(!empty($_POST['acc_code']))
        {
           $sql1.=" AND MA.`code` = '$acc'";
        }
        
        if(!empty($cluster))
        {
           $sql1.=" AND TAS.cl = '$cluster'";
        }
       if(!empty($branch))
        {
            $sql1.=" AND TAS.bc = '$branch'";
        }
         
        if(!empty($_POST['t_range_from']) && empty($_POST['t_range_to']))
        {
           $sql1.=" AND TAS.`nno` >= '$no_range_frm'";
        }
        
         if(!empty($_POST['t_range_to']) && empty($_POST['t_range_from']))
        {
           $sql1.=" AND TAS.`nno` <= '$no_range_to'";
        }

        if(!empty($_POST['t_range_to']) && !empty($_POST['t_range_to']))
        {
           $sql1.=" AND TAS.`nno` BETWEEN  '$no_range_frm' AND '$no_range_to'";
        }

        $sql1.=" ORDER BY TAS.cl,TAS.bc,TAS.nno ASC";

        $query=$this->db->query($sql1);  

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
      
    $r_detail['r_advanced_payment_list']=$this->db->query($sql)->result(); 
    $r_detail['cancled']=$this->db->query($sql1)->result();      
        
    
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