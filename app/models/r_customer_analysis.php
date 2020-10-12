<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_customer_analysis extends CI_Model {

	private $tb_items;
	private $tb_storse;
	private $tb_department;
	private $sd;
	private $w = 297;
	private $h = 210;

	function __construct(){
		parent::__construct();

		$this->load->database();
		$this->load->library('useclass');

		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);

		$this->tb_items = $this->tables->tb['m_items'];
		$this->tb_storse = $this->tables->tb['m_stores'];
	}

	public function base_details(){
		$this->load->model('m_stores');
		$a['store_list']=$this->m_stores->select3();
		$this->load->model('m_branch');
		$a['branch']=$this->get_branch_name();
		return $a;
	}


	public function get_branch_name(){
		$this->db->select('name');
		$this->db->where('bc',$this->sd['branch']);
		return $this->db->get('m_branch')->row()->name;
	}


	public function PDF_report($RepTyp=""){

		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();



		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];

		$r_detail['store_code']=$_POST['stores'];	
		$r_detail['type']=$_POST['type'];        
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];

		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']=$_POST['orientation'];
		$r_detail['from']=$_POST['from'];
		$r_detail['to']=$_POST['to'];
		$to=$_POST['to'];


/*
		$sql="SELECT
				acc_code
				, NAME 
				, SUM(balance) balance
				, SUM(Over90) AS Over90
				, SUM(D60t90) AS D60t90
				, SUM(D30t60) AS D30t60
				, SUM(Below30) AS Below30
				,t.`nic` 
			FROM	(SELECT t.`acc_code`
						, cu.`name`
						,`nic`
						, t.`trans_code`
						, MIN(t.dDate) AS minDate
						, SUM(t.dr)-SUM(t.cr) AS balance
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) >90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Over90'
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 60 AND 90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D60t90'
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 30 AND 59 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D30t60'
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate))  <30 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Below30'
					FROM t_cus_settlement t
					INNER JOIN `m_customer` cu ON t.`acc_code`=cu.`code`
					WHERE (t.`trans_code`='5')  AND (t.`ddate`<= '$to')
					GROUP BY t.`acc_code`
						, cu.`name`
						, t.`trans_code`
						 , t.`trans_no`

					UNION ALL

					SELECT t.`acc_code`
						, cu.`name`
						,`nic`
						, t.`trans_code`
						, MIN(t.dDate) AS minDate
						, SUM(t.dr)-SUM(t.cr) AS balance
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) >90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Over90'
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 60 AND 90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D60t90'
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 30 AND 59 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D30t60'
						,CASE WHEN MAX(DATEDIFF('$to',T.dDate))  <30 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Below30'
					FROM t_debit_note_trans t
					INNER JOIN `m_customer` cu ON t.`acc_code`=cu.`code`
					WHERE (t.`ddate`<= '$to')";

		if(!empty($_POST['cluster'])){
          $sql.=" AND t.`cl` = '".$_POST['cluster']."'";
        }if(!empty($_POST['branch'])){
          $sql.=" AND t.`bc` = '".$_POST['branch']."'";
        }  			

		$sql .= "GROUP BY t.`acc_code`
						, cu.`name`
						, t.`trans_code`
						 , t.`trans_no`) t
						 GROUP BY acc_code , NAME";*/


						 if(!empty($_POST['cluster'])){
						 	$cl=$_POST['cluster'];
						 }if(!empty($_POST['branch'])){
						 	$bc=$_POST['branch'];
						 }  


						 $sql="SELECT
						 t.acc_code
						 ,NAME 
						 , SUM(balance) balance
						 , SUM(Over90) AS Over90
						 , SUM(D60t90) AS D60t90
						 , SUM(D30t60) AS D30t60
						 , SUM(Below30) AS Below30
						 ,t.`nic`
						 , IFNULL( MAX(unsett),0) AS unSettle
						 FROM	(SELECT t.`acc_code`
						 , cu.`name`
						 ,`nic`
						 , t.`trans_code`
						 , MIN(t.dDate) AS minDate
						 , SUM(t.dr)-SUM(t.cr) AS balance
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) >90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Over90'
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 60 AND 90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D60t90'
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 30 AND 59 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D30t60'
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate))  <30 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Below30'
						 
						 FROM t_cus_settlement t
						 INNER JOIN `m_customer` cu ON t.`acc_code`=cu.`code`
						 WHERE (t.`trans_code`='5')  AND (t.`ddate`<= '$to')
						 ";
						 if(!empty($_POST['cluster'])){
						 	$sql.=" AND t.`cl` = '".$_POST['cluster']."'";
						 }if(!empty($_POST['branch'])){
						 	$sql.=" AND t.`bc` = '".$_POST['branch']."'";
						 }  			

						 $sql .= "GROUP BY t.`acc_code`
						 , cu.`name`
						 , t.`trans_code`
						 , t.`trans_no`";

						 $sql .=" UNION ALL ";

						 $sql .="SELECT t.`acc_code`
						 , cu.`name`
						 ,`nic`
						 , t.`trans_code`
						 , MIN(t.dDate) AS minDate
						 , SUM(t.dr)-SUM(t.cr) AS balance
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) >90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Over90'
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 60 AND 90 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D60t90'
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate)) BETWEEN 30 AND 59 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'D30t60'
						 ,CASE WHEN MAX(DATEDIFF('$to',T.dDate))  <30 THEN SUM(T.dr) - SUM(T.cr) ELSE 0 END AS 'Below30'
						 FROM t_debit_note_trans t
						 INNER JOIN `m_customer` cu ON t.`acc_code`=cu.`code`
						 WHERE (t.`ddate`<= '$to') AND (cu.code,t.`trans_code`,t.`trans_no`) IN(SELECT acc_code,trans_code,trans_no FROM `t_account_trans`)";

						 if(!empty($_POST['cluster'])){
						 	$sql.=" AND t.`cl` = '".$_POST['cluster']."'";
						 }if(!empty($_POST['branch'])){
						 	$sql.=" AND t.`bc` = '".$_POST['branch']."'";
						 }  			

						 $sql .= "GROUP BY t.`acc_code`
						 , cu.`name`
						 , t.`trans_code`
						 , t.`trans_no`) t

						 LEFT JOIN (SELECT   d.acc_code , SUM(d.balance ) AS unsett
						 FROM ( SELECT t.acc_code ,t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, 
						 MIN(t.ddate) ddate, s.amount ,s.balance 
						 FROM `t_credit_note_trans` t INNER JOIN `t_credit_note` s ON t.trans_no=s.nno WHERE (t.trans_code='17')  AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0' 
						 GROUP BY t.acc_code , t.sub_cl,t.sub_bc, t.trans_no, t.trans_code ) d WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' AND balance>0   GROUP BY d.acc_code 
						 UNION ALL 
						 SELECT d.acc_code , SUM(d.balance) AS unsett FROM ( SELECT t.acc_code ,t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, 
						 MIN(t.ddate) ddate, s.settle_balance AS amount , s.receipt_balance AS balance FROM `t_cus_settlement` t 
						 INNER JOIN `t_receipt` s ON t.trans_no=s.nno WHERE (t.trans_code='16') 	AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0' 
						 GROUP BY t.acc_code , t.sub_cl,t.sub_bc,t.trans_no, t.trans_code 
						 ) d   WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' AND balance>0 GROUP BY d.acc_code ) AS u ON t.acc_code = u.acc_code	

						 GROUP BY t.acc_code , name";

						 $sql.=" HAVING (balance+Over90+D60t90+D30t60+Below30>0) ";

						 $r_detail['cus_det']=$this->db->query($sql)->result();	

						 if($this->db->query($sql)->num_rows()>0){
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