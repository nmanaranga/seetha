<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_dep_wise_details extends CI_Model{

  private $sd;
  private $w = 210;
  private $h = 297;

  private $mtb;
  private $tb_client;
  private $tb_branch;

  function __construct()
  {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
  }

  public function base_details(){
		//return $a;
  }


  public function PDF_report($RepTyp=""){

    $r_detail['type']=$_POST['type'];
    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']="L";
    $r_detail['type']="";
    $r_detail['to']=$_POST['to'];
    $r_detail['from']=$_POST['from'];
    $r_detail['cluster']=$_POST['cluster'];
    $r_detail['branchs']=$_POST['branch'];

    $r_detail['department']=$_POST['department'];
    $r_detail['salesman']=$_POST['salesman'];
    $r_detail['department_des']=$_POST['department_des'];
    $r_detail['salesman_des']=$_POST['salesman_des'];
    
    $cluster=$_POST['cluster'];
    $branch =$_POST['branch'];


    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['cluster']);
    $r_detail['clus']=$this->db->get('m_cluster')->row()->description;

    $this->db->select(array('name','bc'));
    $this->db->where("bc",$_POST['branch']);
    $r_detail['bran']=$this->db->get('m_branch')->row()->name;

    $sql="SELECT s.*,i.`description`,i.model,i.`department`,d.`description` AS dep_name,e.`name` FROM 
    (SELECT cs.cl,cs.bc,cs.`ddate`,'CASH' AS  type,cs.`nno`,cd.`code`,cd.`price`,cd.`discount`,cd.`amount`,cs.`rep` 
    FROM `t_cash_sales_det` cd 
    JOIN `t_cash_sales_sum` cs ON cs.`cl`=cd.`cl` AND cs.`bc`=cd.`bc` AND cs.`nno`=cd.`nno`
    WHERE cs.cl='".$_POST['cluster']."' AND cs.`bc`='".$_POST['branch']."' AND cs.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND cs.is_cancel=0
    GROUP BY cs.`ddate`,cs.`nno`,cd.`code` 

    UNION ALL

    SELECT cs.cl,cs.bc,cs.`ddate`,'CREDIT' AS  type,cs.`nno`,cd.`code`,cd.`price`,cd.`discount`,cd.`amount`,cs.`rep` 
    FROM `t_credit_sales_det` cd 
    JOIN `t_credit_sales_sum` cs ON cs.`cl`=cd.`cl` AND cs.`bc`=cd.`bc` AND cs.`nno`=cd.`nno`
    WHERE cs.cl='".$_POST['cluster']."' AND cs.`bc`='".$_POST['branch']."' AND cs.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND cs.is_cancel=0
    GROUP BY cs.`ddate`,cs.`nno`,cd.`code` )s
    JOIN `m_item` i ON i.`code`=s.code
    JOIN `r_department` d ON d.`code`=i.`department`
    JOIN `m_employee` e ON e.`code`=s.rep
    JOIN r_sub_category sc ON sc.code=i.`category` AND sc.`main_category`=i.`main_category`
    WHERE s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";

    if($_POST['department']!=""){
      $sql.=" AND `department`='".$_POST['department']."'";
    }

    if($_POST['salesman']!=""){
      $sql.=" AND `rep`='".$_POST['salesman']."'";
    }

    $sql.=" ORDER BY ddate,s.rep,type,nno,d.`code`";

    $data=$this->db->query($sql); 

    if($data->num_rows()>0){
      $r_detail['r_data']=$data->result();
      $exTy=($RepTyp=="")?'pdf':'excel';
      $this->load->view($_POST['by'].'_'.$exTy,$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    } 


  }
  public function Excel_report(){
    $this->PDF_report("Excel");
  }
}
?>