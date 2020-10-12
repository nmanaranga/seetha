<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_purchase_bill extends CI_Model {

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

		$sql="SELECT s.`supp_id`, 
		q.`name`,
		s.`ddate`, 
		s.`nno`,
		s.`po_no`,
		s.`inv_no`,
		SUM(d.`price` * d.`qty`) AS amount,
		SUM(d.`discount`) AS discount,
		s.`net_amount`,
		IFNULL(c.paid,0) AS paid,
		IFNULL(h.return_q,0) AS return_q
		FROM t_grn_sum s
		JOIN m_supplier q ON q.`code` = s.`supp_id` 
		INNER JOIN t_grn_det d ON d.`nno` = s.`nno` AND d.`cl` = s.`cl` AND d.`bc`=s.`bc`
		LEFT JOIN (SELECT cl, bc,trans_no, SUM(cr) AS paid 
		FROM t_sup_settlement
		WHERE trans_code='3' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['bc']."'
		GROUP BY trans_no) c 
		ON c.trans_no=s.`nno` AND c.`cl` = s.`cl` AND c.`bc`=s.`bc` 
		LEFT JOIN (SELECT grn_no, cl, bc, net_amount AS return_q 
		FROM t_pur_ret_sum) h 
		ON h.grn_no = s.`nno` AND h.`cl` = s.`cl` AND h.`bc`=s.`bc` 
		WHERE s.ddate between '$from' AND '$to'
		";


		if(!empty($supplier))
		{
			$sql.=" AND s.supp_id = '$supplier'";
		}

		if(!empty($cluster))
		{
			$sql.=" AND s.cl = '$cluster'";
		}

		if(!empty($branch))
		{
			$sql.=" AND s.bc = '$branch'";
		}

		$sql.= " GROUP BY s.`nno` ";

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