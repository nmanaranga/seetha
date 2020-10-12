<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_int_tr_status extends CI_Model {

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

		$cl=$_POST['cluster'];
		$bc=$_POST['branch'];
		$from=$_POST['from'];
		$to=$_POST['to'];

		$r_detail['store_code']=$_POST['stores'];	
		$r_detail['type']=$_POST['type'];        
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];

		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']=$_POST['orientation'];
		$r_detail['from']=$_POST['from'];
		$r_detail['to']=$_POST['to'];
		



		$sql="SELECT 	a.ddate AS o_date,
		a.bc AS o_bc,
		a.b_name,
		a.nno AS o_nno,
		a.item_code AS o_item,
		a.qty AS o_qty,
		a.amount AS o_amount,

		b.ddate AS i_date,
		b.bc As i_bc,
		b.b_name,
		b.nno AS i_nno,
		b.item_code As i_item,
		b.qty AS i_qty,
		b.amount AS i_amount,

		c.ddate AS r_date,
		c.bc AS r_bc,
		c.b_name,
		c.nno AS r_nno,
		c.item_code AS r_item,
		c.qty AS r_qty,
		c.amount AS r_amount,

		d.ddate AS rt_date,
		d.bc AS rt_bc,
		d.b_name,
		d.nno AS rt_nno,
		d.item_code AS rt_item,
		d.accept_qty AS rt_qty,
		d.amount AS rt_amount 

		FROM(
		SELECT s.ddate, mb.name AS b_name, s.bc,s.sub_no AS nno,s.cl,
		d.`item_code`,d.`qty`,(d.`qty`*d.`item_cost`) AS amount
		FROM t_internal_transfer_order_sum s
		JOIN m_branch mb ON mb.bc=s.bc
		JOIN t_internal_transfer_order_det d ON s.`cl`=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
		WHERE s.cl='$cl' AND s.bc='$bc' AND s.is_cancel=0
		AND s.ddate BETWEEN '$from' AND '$to' 
		GROUP BY d.`item_code`, s.`nno`
		)a

		LEFT JOIN (SELECT ss.ddate,ss.bc,ss.`sub_no`,dd.item_code, dd.qty, (dd.qty*dd.item_cost) AS amount,ss.`trans_code`, mb.`name` AS b_name,
		order_no,to_cl,to_bc,ss.`sub_no` AS nno
		FROM t_internal_transfer_sum ss
		JOIN m_branch mb ON mb.bc=ss.bc
		JOIN t_internal_transfer_det dd ON ss.`cl`=dd.cl AND ss.bc=dd.bc AND ss.nno=dd.nno
		WHERE ss.is_cancel=0
		GROUP BY dd.`item_code`, ss.`nno`,ss.`sub_no`
		) b ON b.order_no=a.nno AND b.to_cl=a.cl AND b.to_bc=a.bc	AND b.item_code=a.item_code

		LEFT JOIN(
		SELECT sss.`ddate`,sss.`sub_no` AS nno ,sss.bc,ddd.`item_code`,ddd.`qty`,(ddd.`item_cost` * ddd.`qty`) AS amount,sss.ref_trans_code,sss.ref_sub_no,sss.issue_bc,mb.`name` AS b_name
		FROM t_internal_transfer_sum sss
		JOIN m_branch mb ON mb.bc=sss.bc
		JOIN t_internal_transfer_det ddd ON sss.cl=ddd.cl AND sss.bc=ddd.bc AND sss.nno=ddd.nno
		WHERE sss.is_cancel=0
		GROUP BY ddd.`item_code`, sss.`nno`
		)c	ON c.`ref_trans_code` = b.`trans_code` AND c.ref_sub_no = b.`sub_no` AND c.issue_bc = b.bc AND b.item_code=c.item_code

		LEFT JOIN 
		(SELECT ssss.`ddate`,ssss.`sub_no` AS nno,ssss.bc,dddd.`item_code`,dddd.`accept_qty`,(dddd.`item_cost` * dddd.`accept_qty`) AS amount,mb.`name` AS b_name,ssss.`issue_no` 
		FROM `t_internal_transfer_return_sum` ssss 
		JOIN m_branch mb ON mb.bc = ssss.bc 
		JOIN `t_internal_transfer_return_det` dddd ON ssss.cl = dddd.cl AND ssss.bc = dddd.bc AND ssss.nno = dddd.nno 
		WHERE ssss.is_cancel=0
		GROUP BY dddd.`item_code`,ssss.`nno` 
		)d ON  d.issue_no = b.`sub_no` AND d.bc = b.bc AND d.item_code = b.item_code 

		ORDER BY a.nno";


		$r_detail['details']=$this->db->query($sql)->result();	

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