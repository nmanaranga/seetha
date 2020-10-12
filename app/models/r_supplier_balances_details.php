<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_supplier_balances_details extends CI_Model {

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
		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();

		$this->db->select(array('code','description'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$this->db->where("code",$_POST['stores']);
		$r_detail['store_des']=$this->db->get('m_stores')->row()->description;

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
		$r_detail['supplier']=$_POST['supp'];

		$to=$_POST['to'];
		$from=$_POST['from'];
		$supplier=$_POST['supp'];
		$cluster=$_POST['cluster'];
		$branch=$_POST['branch'];

		$sql="SELECT tc.`description`, d.* FROM( 
		SELECT s.cl, s.bc, t.trans_code as type, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,s.balance 
		FROM t_grn_sum s  JOIN (
		SELECT t.sub_cl AS cl,t.sub_bc AS bc,t.trans_code,t.trans_no,t.acc_code
		FROM t_sup_settlement t
		WHERE t.acc_code='$supplier' ";
		if(!empty($cluster))
		{
			$sql.=" AND t.sub_cl='$cluster' ";
		}
		if(!empty($branch))
		{
			$sql.=" AND t.sub_bc='$branch' ";
		}
		$sql.=" GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code)t 
		ON t.trans_no=s.nno AND  t.acc_code=s.supp_id WHERE s.supp_id='$supplier' AND t.trans_code='3' 
		AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' HAVING balance > 0

		UNION ALL 

		SELECT tr.cl, 
		tr.bc, 
		tr.trans_code AS TYPE,
		tr.trans_no AS trans_no, 
		'Records From Opening Balance' AS memo, 
		s.date AS ddate, 
		SUM(tr.dr) AS amount,
		SUM(tr.cr)-SUM(tr.dr) AS balance  
		FROM t_opening_bal_trans tr 
		JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc
		WHERE tr.acc_code='$supplier' ";
		if(!empty($cluster))
		{
			$sql.=" AND tr.sub_cl='$cluster' ";
		}
		if(!empty($branch))
		{
			$sql.=" AND tr.sub_bc='$branch' ";
		}
		$sql.=" GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code,s.no
		HAVING balance >0  

		UNION ALL
		SELECT s.cl, s.bc,  t.trans_code as type ,s.nno AS trans_no, s.memo, s.ddate, s.amount,s.balance 
		FROM t_credit_note s INNER JOIN (
			SELECT t.sub_cl AS cl,t.sub_bc AS bc,
			t.trans_code,
			t.trans_no,
			t.acc_code

			FROM t_credit_note_trans t
			WHERE t.acc_code='$supplier' ";
			if(!empty($cluster))
			{
				$sql.=" AND t.sub_cl='$cluster' ";
			}
			if(!empty($branch))
			{
				$sql.=" AND t.sub_bc='$branch' ";
			}
			$sql.=" GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code

		) t 
		ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$supplier' 
		AND s.bc =t.bc AND s.cl = t.cl  ";
		if(!empty($cluster))
		{
			$sql.=" AND s.cl='$cluster' ";
		}
		if(!empty($branch))
		{
			$sql.=" AND s.bc='$branch' ";
		}
		$sql.=" HAVING balance > 0 
	) d 
	INNER JOIN t_trans_code tc ON tc.`code` = d.type";


	$r_detail['item_det']=$this->db->query($sql)->result();	

	if($this->db->query($sql)->num_rows()>0)
	{
		$exTy=($RepTyp=="")?'pdf':'excel';
		$this->load->view($_POST['by'].'_'.$exTy,$r_detail);
	}
	else
	{
		echo "<script>alert('No Data');window.close();</script>";
	}
}


public function Excel_report(){
	$this->PDF_report("Excel");
}
}	
?>