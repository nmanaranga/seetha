<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_employee_item_commission extends CI_Model{

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

    $cluster  =$_POST['cluster'];
    $branch   =$_POST['branch'];
    $emp    =$_POST['emp'];
    $t_date   =$_POST['to'];
    $f_date   =$_POST['from'];

    $r_detail['cluster']=$_POST['cluster'];
    $r_detail['branchs']=$_POST['branch'];
    $r_detail['emp']=$_POST['emp'];
    $r_detail['emp_des']=$_POST['emp_des'];


    if($cluster!="0"){
      $cl=" AND s.cl='$cluster'";
    }else{
      $cl=" ";
    }

    if($branch !="0"){
      $bc=" AND s.bc='$branch'";
    }else{
      $bc=" ";
    }

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
    $sql="SELECT s.`ddate`,s.`rep`,d.`code`,i.`description`,i.`model`,e.name,IFNULL(c.`rate`,0)AS rate,IFNULL(c.`amount`,0)AS amount,d.qty,IFNULL(r.`qty`,0) AS rt_qty,d.qty-IFNULL(r.`qty`,0) AS net_qty,d.`price`,(d.qty-IFNULL(r.`qty`,0))*d.price AS value_of_item,IFNULL(ROUND((d.qty-IFNULL(r.`qty`,0))*((d.price*rate)/100),2),0) AS value_of_com
    FROM `t_cash_sales_det`d 
    JOIN `t_cash_sales_sum` s ON d.`cl`=s.`cl` AND d.`bc`=s.`bc` AND d.`nno`=s.`nno`
    JOIN `m_item` i ON i.`code`=d.`code`
    LEFT JOIN (SELECT * FROM `m_item_commission` WHERE date_from BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' OR date_to BETWEEN '".$_POST['from']."' AND '".$_POST['to']."') c ON c.`cl`=d.`cl` AND c.`bc`=d.`bc` AND c.`code`=d.`code`
    LEFT JOIN `t_sales_return_det` r ON r.`cl`=d.`cl` AND r.`bc`=d.`bc` AND r.`code`=d.`code`
    LEFT JOIN m_employee e ON e.code=s.rep
    WHERE s.is_cancel=0  $cl $bc AND s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";
    if($emp!=""){
      $sql.=" AND s.rep='$emp'";
    }
    $sql.="  GROUP BY d.cl,d.bc,d.code,s.`ddate`,s.`rep` UNION ALL

    SELECT s.`ddate`,s.`rep`,d.`code`,i.`description`,e.name,i.`model`,IFNULL(c.`rate`,0)AS rate,IFNULL(c.`amount`,0)AS amount,d.qty,IFNULL(r.`qty`,0) AS rt_qty,d.qty-IFNULL(r.`qty`,0) AS net_qty,d.`price`,(d.qty-IFNULL(r.`qty`,0))*d.price AS value_of_item,IFNULL(ROUND((d.qty-IFNULL(r.`qty`,0))*((d.price*rate)/100),2),0) AS value_of_com
    FROM `t_credit_sales_det`d 
    JOIN `t_credit_sales_sum` s ON d.`cl`=s.`cl` AND d.`bc`=s.`bc` AND d.`nno`=s.`nno`
    JOIN `m_item` i ON i.`code`=d.`code`
    LEFT JOIN (SELECT * FROM `m_item_commission` WHERE date_from BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' OR date_to BETWEEN '".$_POST['from']."' AND '".$_POST['to']."') c ON c.`cl`=d.`cl` AND c.`bc`=d.`bc` AND c.`code`=d.`code`
    LEFT JOIN `t_sales_return_det` r ON r.`cl`=d.`cl` AND r.`bc`=d.`bc` AND r.`code`=d.`code`
    LEFT JOIN m_employee e ON e.code=s.rep
    WHERE s.is_cancel=0 $cl $bc AND s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";
    if($emp!="" ){
      $sql.=" AND s.rep='$emp'";
    }

    $sql.=" GROUP BY d.cl,d.bc,d.code,s.`ddate`,s.`rep` ORDER BY rep";

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