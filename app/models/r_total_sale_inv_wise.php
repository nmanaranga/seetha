<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_total_sale_inv_wise extends CI_Model{

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
    $cluster=$_POST['cluster'];
    $branch =$_POST['branch'];
    $items    =$_POST['item'];
    $t_date=$_POST['to'];
    $f_date =$_POST['from'];

    $r_detail['cluster']=$_POST['cluster'];
    $r_detail['branchs']=$_POST['branch'];
    $r_detail['item']=$_POST['item'];
    $r_detail['item_des']=$_POST['item_des'];


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

    $sql="SELECT s.`ddate` ,  c.`name`  , s.`cus_id`  ,'CASH' AS s_type , CONCAT(  s.`cl` , s.`bc` , s.`nno` ) AS non,CONCAT(c.address1,',',c.address2,',',address3) as address
    , s.`gross_amount` as purchase_price, SUM(d.`discount`) AS discount, SUM(d.amount) AS net_amount,  s.cus_name,s.cus_address
    FROM `t_cash_sales_sum` s
    LEFT JOIN `m_customer` c ON s.`cus_id`= c.`code`
    JOIN t_cash_sales_det d ON s.cl=d.cl AND s.bc=d.bc AND s.nno=d.nno
    WHERE s.`ddate` BETWEEN '$f_date' AND '$t_date'  AND s.`is_cancel`=0 $cl $bc
    GROUP BY s.nno

    UNION ALL 

    SELECT s.`ddate` ,  c.`name`  , s.`cus_id`  ,'CREDIT' AS s_type , CONCAT(  s.`cl` , s.`bc` , s.`nno` ) AS non,CONCAT(c.address1,',',c.address2,',',address3) as address
    , s.`gross_amount`  as purchase_price, SUM(d.`discount`) AS discount, SUM(d.amount) AS net_amount,  s.cus_name,s.cus_address
    FROM `t_credit_sales_sum` s
    LEFT JOIN `m_customer` c ON s.`cus_id`= c.`code`
    JOIN t_credit_sales_det d ON s.cl=d.cl AND s.bc=d.bc AND s.nno=d.nno
    WHERE s.`ddate` BETWEEN '$f_date' AND '$t_date'   AND s.`is_cancel`=0 $cl $bc
    GROUP BY s.nno

    UNION ALL 

    SELECT s.`ddate` ,  c.`name`  , s.`cus_id`  ,'POS' AS s_type , CONCAT(  s.`cl` , s.`bc` , s.`nno` ) AS non,CONCAT(c.address1,',',c.address2,',',address3) AS address
    , s.`gross_amount` AS purchase_price, SUM(d.`discount`) AS discount , SUM(d.amount) AS net_amount,'' AS cus_name,'' AS cus_address 
    FROM `t_pos_sales_sum` s
    LEFT JOIN `m_customer` c ON s.`cus_id`= c.`code`
    JOIN t_pos_sales_det d ON s.cl=d.cl AND s.bc=d.bc AND s.nno=d.nno
    WHERE s.`ddate` BETWEEN '$f_date' AND '$t_date'   AND s.`is_cancel`=0 $cl $bc 
    GROUP BY s.nno";

    $data=$this->db->query($sql); 

    $sql_add="SELECT s.cl,s.bc,s.`nno`,IFNULL(adi.amount,0) - IFNULL(sddc.amnt,0) AS amount, adi.is_add
    FROM
    t_cash_sales_sum s 
    JOIN(SELECT ai.cl,ai.bc,ai.nno,ai.`amount`,aii.`is_add`,aii.account FROM `t_cash_sales_additional_item` ai 
    JOIN `r_additional_item` aii ON aii.code = ai.`type` )adi ON adi.cl=s.cl AND adi.bc=s.bc AND adi.nno=s.nno
    LEFT JOIN (SELECT s.cl,s.bc,s.`nno`,s.ddate,s.`rep`,s.inv_no,IFNULL(SUM(s.`discount`),0)+IFNULL(ai.amount,0) AS amnt 
    FROM t_sales_return_sum s LEFT JOIN t_srn_ret_additional_item ai ON ai.`cl`=s.`cl` AND ai.bc=s.`bc` 
    AND ai.`nno`=s.`nno` WHERE s.ddate BETWEEN '$f_date' AND '$t_date' AND s.sales_type='4' 
    AND s.is_approve='1' AND s.is_cancel='0' $cl $bc GROUP BY s.ddate )AS sddc ON 
    sddc.cl = s.cl AND sddc.bc = s.bc AND sddc.inv_no = s.`nno`
    WHERE s.ddate BETWEEN '$f_date' AND '$t_date' AND s.is_cancel='0' $cl $bc $item2 AND adi.account='600000002034'
    GROUP BY s.nno,adi.nno,adi.is_add 
    UNION
    ALL 
    SELECT s.cl,s.bc,s.`nno` ,IFNULL(adi.amount,0) - IFNULL(sdd.amnt,0) AS amount,adi.is_add
    FROM
    t_credit_sales_sum s 
    JOIN(SELECT ai.cl,ai.bc,ai.nno,ai.`amount`,aii.`is_add`,aii.account FROM `t_credit_sales_additional_item` ai 
    JOIN `r_additional_item` aii ON aii.code = ai.`type` )adi ON adi.cl=s.cl AND adi.bc=s.bc AND adi.nno=s.nno
    LEFT JOIN (SELECT s.cl,s.bc,s.`nno`,s.ddate,s.`rep`,s.inv_no,IFNULL(SUM(s.`discount`),0)+IFNULL(ai.amount,0) AS amnt 
    FROM t_sales_return_sum s LEFT JOIN t_srn_ret_additional_item ai ON ai.`cl`=s.`cl` AND ai.bc=s.`bc` 
    AND ai.`nno`=s.`nno` WHERE s.ddate BETWEEN '$f_date' AND '$t_date' AND s.sales_type='5' 
    AND s.is_approve='1' AND s.is_cancel='0' $cl $bc GROUP BY s.ddate )AS sdd ON 
    sdd.cl = s.cl AND sdd.bc = s.bc AND sdd.inv_no = s.`nno`
    WHERE s.ddate BETWEEN '$f_date' AND '$t_date' AND s.is_cancel='0' $cl $bc $item2 AND adi.account='600000002034'
    GROUP BY s.nno,adi.nno,adi.is_add 

    UNION ALL SELECT cl,bc,nno,sum(amount) as amount,0 as is_add FROM t_credit_note s WHERE s.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='SALES_DISCOUNT') AND  dis_type in('5','0') AND is_approve!='0' AND is_cancel=0 AND s.ddate BETWEEN '$f_date' AND '$t_date' $cl $bc GROUP BY ddate

    UNION ALL SELECT  d.cl,d.bc,d.`qty`,SUM(d.`amount`)  AS amount, 3 AS is_add  FROM t_sales_return_sum s JOIN t_sales_return_det d ON d.cl=s.`cl` AND d.bc=s.`bc` AND d.`nno`=s.`nno` WHERE s.is_cancel='0' AND s.is_approve=1 AND s.ddate BETWEEN '$f_date' AND '$t_date'  $cl $bc GROUP BY ddate
    ";

    $data_add=$this->db->query($sql_add); 
    $r_detail['add_data']=$data_add->result();

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