<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_dep_sg_report extends CI_Model {

	private $tb_items;
	private $tb_storse;
	private $tb_department;
	private $sd;
	private $w = 297;
	private $h = 210;

	function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->library('useclass');
		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
		$this->tb_items = $this->tables->tb['m_items'];
		$this->tb_storse = $this->tables->tb['m_stores'];
	}

	public function base_details()
	{
		$this->load->model('m_stores');
		$a['store_list']=$this->m_stores->select3();
		$this->load->model('m_branch');
		$a['branch']=$this->get_branch_name();
		return $a;
	}


	public function get_branch_name()
	{
		$this->db->select('name');
		$this->db->where('bc',$this->sd['branch']);
		return $this->db->get('m_branch')->row()->name;
	}


	public function PDF_report($RepTyp="")
	{
		 //-----------------------------------------------------------------------------------------------------------

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
		$r_detail['orientation']="L";
		$r_detail['dfrom']=$_POST['from'];
		$r_detail['dto']=$_POST['to'];
		$cluster=$_POST['cluster'];
		$branch=$_POST['branch'];

		$sql_b="SELECT * FROM m_branch WHERE inactive=0 ";
		if($_POST['cluster']!='0'){
			$sql_b.=" AND cl='".$_POST['cluster']."'";
		}if($_POST['branch']!='0'){
			$sql_b.=" AND bc='".$_POST['branch']."'";
		}

		$query_b=$this->db->query($sql_b)->result();

		$sql="SELECT department,description,";
		foreach($query_b as $r){
			$sql.="IFNULL(SUM(CASE WHEN bc='".$r->bc."' THEN (totsale) END),0) AS ".$r->bc.",";
		}
		$sql.=" SUM(totsale)AS totsale FROM 
		(SELECT s.cl,s.`bc`,i.`department`,de.description,SUM(d.`amount`) AS totsale FROM `t_cash_sales_det` d 
		JOIN t_cash_sales_sum s ON d.cl=s.`cl` AND d.`bc`=s.`bc` AND d.`nno`=s.`nno`
		JOIN m_item i ON i.`code`=d.`code`
		JOIN `r_department` de ON de.`code`=i.`department`
		WHERE s.`is_cancel`=0 AND s.`is_approve`=1 AND s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";
		if($_POST['cluster']!='0'){
			$sql.=" AND d.cl='".$_POST['cluster']."'";
		}if($_POST['branch']!='0'){
			$sql.=" AND d.bc='".$_POST['branch']."'";
		}

		$sql.=" GROUP BY i.`department`,s.cl,s.`bc`

		UNION ALL

		SELECT s.cl,s.`bc`,i.`department`,de.description,SUM(d.`amount`) AS totsale FROM `t_credit_sales_det` d 
		JOIN t_credit_sales_sum s ON d.cl=s.`cl` AND d.`bc`=s.`bc` AND d.`nno`=s.`nno`
		JOIN m_item i ON i.`code`=d.`code`
		JOIN `r_department` de ON de.`code`=i.`department`
		WHERE s.`is_cancel`=0 AND s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";

		if($_POST['cluster']!='0'){
			$sql.=" AND d.cl='".$_POST['cluster']."'";
		}if($_POST['branch']!='0'){
			$sql.=" AND d.bc='".$_POST['branch']."'";
		}

		$sql.=" GROUP BY i.`department`,s.cl,s.`bc`) a GROUP BY department ORDER BY bc";

		$r_detail['sales']=$this->db->query($sql)->result();


		$sql_grn="SELECT department,description,";
		foreach($query_b as $r){
			$sql_grn.="IFNULL(SUM(CASE WHEN bc='".$r->bc."' THEN (totsale) END),0) AS ".$r->bc.",";
		}
		$sql_grn.=" SUM(totsale)AS totsale FROM 
		(SELECT s.cl,s.`bc`,i.`department`,de.description,SUM(d.`amount`) AS totsale FROM `t_grn_det` d 
		JOIN t_grn_sum s ON d.cl=s.`cl` AND d.`bc`=s.`bc` AND d.`nno`=s.`nno`
		JOIN m_item i ON i.`code`=d.`code`
		JOIN `r_department` de ON de.`code`=i.`department`
		WHERE s.`is_cancel`=0 ";

		if($_POST['cluster']!='0'){
			$sql_grn.=" AND d.cl='".$_POST['cluster']."'";
		}if($_POST['branch']!='0'){
			$sql_grn.=" AND d.bc='".$_POST['branch']."'";
		}

		$sql_grn.=" GROUP BY i.`department`,s.cl,s.`bc`
	) a GROUP BY department ORDER BY bc";

	$r_detail['purchase']=$this->db->query($sql_grn)->result();

	$sql_br="SELECT * FROM m_branch WHERE inactive=0 ";
	if($_POST['cluster']!='0'){
		$sql_br.=" AND cl='".$_POST['cluster']."'";
	}if($_POST['branch']!='0'){
		$sql_br.=" AND bc='".$_POST['branch']."'";
	}
	$r_detail['branch_det']=$this->db->query($sql_br)->result();




	if($this->db->query($sql)->num_rows()>0){
		$exTy=($RepTyp=="")?'pdf':'excel';
		$this->load->view($_POST['by'].'_'.$exTy,$r_detail);
	}else{
		echo "<script>alert('No Data');window.close()</script>";
	}
}


public function Excel_report()
{
	$this->PDF_report("Excel");
}

}	
?>