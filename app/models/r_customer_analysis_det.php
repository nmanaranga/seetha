<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_customer_analysis_det extends CI_Model {

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


	public function PDF_report(){

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


		if(!empty($_POST['cluster'])){
			$cl=$_POST['cluster'];
		}if(!empty($_POST['branch'])){
			$bc=$_POST['branch'];
		}  

		$sql="SELECT t.acc_code,
		NAME,
		SUM(balance) balance,
		SUM(Over90) AS Over90,
		SUM(D60t90) AS D60t90,
		SUM(D30t60) AS D30t60,
		SUM(Below30) AS Below30,
		t.trans_no,
		t.trans_code,
		t.`nic`,
		t.ddate FROM 
		(SELECT t.`acc_code`,cu.`name`,`nic`,t.`trans_code`,t.trans_no,(t.dDate) AS ddate,SUM(t.dr) - SUM(t.cr) AS balance
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

		$sql .= "GROUP BY 
		 t.`trans_code`
		, t.`trans_no`";

		$sql .=" UNION ALL ";

		$sql .="SELECT t.`acc_code`,cu.`name`,`nic`,t.`trans_code`,t.trans_no,(t.dDate) AS ddate,SUM(t.dr) - SUM(t.cr) AS balance
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

		$sql .= "GROUP BY 
		t.`trans_code`
		, t.`trans_no`) t

		
		GROUP BY t.trans_no,t.trans_code";

		$sql.=" HAVING (balance+Over90+D60t90+D30t60+Below30>0) 
		ORDER BY t.acc_code";

		$r_detail['cus_det']=$this->db->query($sql)->result();	

		if($this->db->query($sql)->num_rows()>0){
			$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}



	}



}	
?>