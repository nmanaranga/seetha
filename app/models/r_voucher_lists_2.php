
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_voucher_lists_2 extends CI_Model{
    
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
      $r_detail['orientation']='L';  
      $r_detail['card_no']=$_POST['card_no1'];
      $r_detail['dfrom']=$_POST['from'];
      $r_detail['dto']=$_POST['to'];

      //$cl=$this->sd['cl'];
      //$bc=$this->sd['branch'];
      $cluster=$_POST['cluster'];
      $branch=$_POST['branch'];
      $acc=$_POST['acc_code'];
      $no_range_frm=$_POST['t_range_from'];
      $no_range_to=$_POST['t_range_to'];

     
      $r_detail['cluster1']=$cluster;
      $r_detail['branch1']=$branch;
      $r_detail['acc']=$_POST['acc_code'];
      $r_detail['acc_des']=$_POST['acc_code_des'];
      $r_detail['t_no_from']=$_POST['t_range_from'];
      $r_detail['t_no_to']=$_POST['t_range_to'];
      // var_dump($_POST['t_range_from'], $_POST['t_range_to']);
      // exit();
        $date_range=$_POST['from'];
        $acc_code=$_POST['acc_code'];
        $no_range=$_POST['from'];
        if(isset($_POST['chknumRange']))
        {
          $checkR=1;
        }else{
          $checkR=0;
        }
      
        if ($date_range!="") {
          $date1="AND ddate between'".$_POST['from']."' AND '".$_POST['to']."'";
        }
        else
          $date1="";
        
        if($acc_code!=""){
          $acc1="AND acc_code='".$_POST['acc_code']."'";
        }
        else
          $acc1="";

        if($checkR==1){
          $num_range="AND nno between'".$_POST['t_range_from']."' AND '".$_POST['t_range_to']."'";
          }
        else
          $num_range="";
        
        if(!empty($cluster)) 
        {
           $cluster1= "AND t_voucher_sum.cl ='".$_POST['cluster']."'";
        }
        else
         $cluster1="";
       // var_dump($cluster1);
       // exit();
      $sql="SELECT 
              (SELECT
                  COUNT(*) 
                FROM
                  t_voucher_sum 
                WHERE `is_cancel` = '0' $date1 $acc1 $num_range 
                 $cluster1) AS counts,
              `nno`,
              `ddate`,
              `settle_amount`,
              `pay_cash`,
              `pay_receive_chq`,
              `pay_credit`,
              `pay_dnote`,
              `pay_discount`,
              `memo`,
              MA.`description` AS description,
              `acc_code`,
              t_voucher_sum.`cl` AS cl,
              t_voucher_sum.`bc` AS bc,
              MBC.`name`AS bc_name, 
              MCL.`description` AS cl_description
            FROM `t_voucher_sum`
            INNER JOIN `m_account` AS MA 
                  ON MA.`code`=t_voucher_sum.`acc_code`
            INNER JOIN `m_branch` MBC
            ON t_voucher_sum.bc=MBC.bc
             INNER JOIN `m_cluster` MCL
            ON (MBC.`cl` = MCL.`code`)
            WHERE `is_cancel`='0'  ";
  		
       if(!empty($_POST['from']))
        {
           $sql.=" AND t_voucher_sum.ddate between '".$_POST['from']."' AND '".$_POST['to']."'";
        }
       if(!empty($_POST['acc_code']))
        {
           $sql.=" AND MA.`code` = '$acc'";
        }
        
        if(!empty($cluster))
        {
           $sql.=" AND t_voucher_sum.cl = '$cluster'";
        }
       if(!empty($branch))
        {
            $sql.=" AND t_voucher_sum.bc = '$branch'";
        }
         
        if(!empty($_POST['t_range_from']) && empty($_POST['t_range_to']))
        {
           $sql.=" AND t_voucher_sum.`nno` >= '$no_range_frm'";
        }
        
         if(!empty($_POST['t_range_to']) && empty($_POST['t_range_from']))
        {
           $sql.=" AND t_voucher_sum.`nno` <= '$no_range_to'";
        }

        if(!empty($_POST['t_range_to']) && !empty($_POST['t_range_to']))
        {
           $sql.=" AND t_voucher_sum.`nno` BETWEEN  '$no_range_frm' AND '$no_range_to'";
        }

        $sql.="ORDER BY cl,bc,nno ASC";
          

      $query=$this->db->query($sql);

       $sql1="SELECT 
              (SELECT 
                  COUNT(*) 
                FROM
                  t_voucher_sum 
                WHERE `is_cancel` = '1' 
                  $date1 
                  AND t_voucher_sum.cl = '$cluster') AS count,
              `nno`,
              `ddate`,
              `settle_amount`,
              `pay_cash`,
              `pay_receive_chq`,
              `pay_credit`,
              `pay_dnote`,
              `pay_discount`,
              `memo`,
              MA.`description` AS description,
              `acc_code`,
              t_voucher_sum.`cl` AS cl,
              t_voucher_sum.`bc` AS bc,
              MBC.`name`AS bc_name, 
              MCL.`description` AS cl_description
            FROM `t_voucher_sum`
            INNER JOIN `m_account` AS MA 
                  ON MA.`code`=t_voucher_sum.`acc_code`
            INNER JOIN `m_branch` MBC
            ON t_voucher_sum.bc=MBC.bc
             INNER JOIN `m_cluster` MCL
            ON (MBC.`cl` = MCL.`code`)
            WHERE `is_cancel`='1'";
     
      if(!empty($_POST['from']))
        {
           $sql1.=" AND t_voucher_sum.ddate between '".$_POST['from']."' AND '".$_POST['to']."'";
        }
      if(!empty($_POST['acc_code']))
        {
           $sql1.=" AND MA.`code` = '$acc'";
        }
        
        if(!empty($cluster))
        {
           $sql1.=" AND t_voucher_sum.cl = '$cluster'";
        }
       if(!empty($branch))
        {
            $sql1.=" AND t_voucher_sum.bc = '$branch'";
        }
         
        if(!empty($_POST['t_range_from']) && empty($_POST['t_range_to']))
        {
           $sql1.=" AND t_voucher_sum.`nno` >= '$no_range_frm'";
        }
        
         if(!empty($_POST['t_range_to']) && empty($_POST['t_range_from']))
        {
           $sql1.=" AND t_voucher_sum.`nno` <= '$no_range_to'";
        }

        if(!empty($_POST['t_range_to']) && !empty($_POST['t_range_to']))
        {
           $sql1.=" AND t_voucher_sum.`nno` BETWEEN  '$no_range_frm' AND '$no_range_to'";
        }
          
        $sql.="ORDER BY cl,bc,nno ASC";
      $query1=$this->db->query($sql1);
     // var_dump( $query1);
     // exit();

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
      
      $r_detail['sum']=$query->result();
      $r_detail['cancled']=$query1->result();

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