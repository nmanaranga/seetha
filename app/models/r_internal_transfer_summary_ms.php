<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class r_internal_transfer_summary_ms extends CI_Model{    
	private $sd;
	private $mtb;  
	private $mod = '003';  
	function __construct(){
		parent::__construct();

		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
	}

	public function base_details(){      
		$a['table_data'] = $this->data_table();
		return $a;
	}


	public function PDF_report($RepTyp=""){

		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();

		$this->db->select(array('code','description'));
		$this->db->where("code",$_POST['sales_category']);
		$r_detail['category']=$this->db->get('r_sales_category')->result();

		$r_detail['page']='A4';
		$r_detail['orientation']='L';  
		$r_detail['card_no']=$_POST['card_no1'];
		$r_detail['dfrom']=$_POST['from'];
		$r_detail['dto']=$_POST['to'];
		$r_detail['category_field']=$_POST['sales_category'];  


		$sql="SELECT 
		b.bc AS f_bc,
		b.`b_name` AS f_bc_name,
		b.nno,
		b.`ddate`,
		b.`sub_no`,
		b.trns_mode,
		b.mode_no,
		b.`store` AS from_store,
		b.to_bcName,
		b.dep,
		b.store_name,
		b.order_no,
		IFNULL(b.qty,0) AS t_qty,
		IFNULL(b.amount,0) AS t_amo,


		d.nno AS rt_no,
		d.`to_bc`,
		d.b_name AS to_bc_name,
		IFNULL(d.qty,0) AS rt_qty,
		IFNULL(d.amount,0) AS rt_amo,

		c.nno as r_no,
		c.`to_bc`,
		c.b_name AS to_bc_name,
		IFNULL(c.qty,0) AS r_qty,
		IFNULL(c.amount,0) AS r_amo,
		c.ddate as r_date

		FROM
		(SELECT ss.ddate,ss.bc,ss.`sub_no`,dd.item_code,rd.`description` AS dep,mbb.`name` AS to_bcName,SUM(dd.qty) AS  qty,
		SUM(dd.qty * dd.item_cost) AS amount,ss.`trans_code`,mb.`name` AS b_name,order_no,to_cl,to_bc,ss.nno,
		ss.store,ms. description AS store_name,ss.trns_mode,ss.mode_no
		FROM
		t_internal_transfer_sum ss 
		JOIN m_branch mb ON mb.bc = ss.bc 
		JOIN m_branch mbb ON mbb.`bc`=ss.`to_bc`
		JOIN t_internal_transfer_det dd ON ss.`cl` = dd.cl AND ss.bc = dd.bc AND ss.nno = dd.nno AND ss.`sub_no`=dd.`sub_no`
		JOIN m_item i ON i.`code`=dd.`item_code`
		JOIN `r_department` rd ON rd.`code`=i.`department`
		JOIN m_stores ms ON ms.code=ss.store
		WHERE ss.trans_code=42 AND ss.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
		AND ss.cl='".$_POST['cluster']."'
		AND ss.bc='".$_POST['branch']."' AND is_cancel=0
		GROUP BY ss.`nno`)b

		LEFT JOIN 
		(SELECT sss.`ddate`,sss.`sub_no` AS nno,sss.bc AS to_bc,ddd.`item_code`,SUM(ddd.`qty`) AS  qty,SUM(ddd.`item_cost` * ddd.`qty`) AS amount,
		sss.ref_trans_code,sss.ref_sub_no,sss.issue_bc,mb.`name` AS b_name 
		FROM t_internal_transfer_sum sss 
		JOIN m_branch mb ON mb.bc = sss.bc 
		JOIN t_internal_transfer_det ddd ON sss.cl = ddd.cl AND sss.bc = ddd.bc AND sss.nno = ddd.nno  AND sss.`sub_no`=ddd.`sub_no`
		WHERE sss.trans_code=43 AND sss.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND is_cancel=0
		GROUP BY sss.`nno`,sss.bc,sss.`sub_no`) c ON c.`ref_trans_code` = b.`trans_code` AND c.ref_sub_no = b.`sub_no` AND c.issue_bc = b.bc  

		LEFT JOIN 
		(SELECT ssss.`ddate`,ssss.`sub_no` AS nno,ssss.bc AS to_bc,dddd.`item_code`,SUM(dddd.`accept_qty`) AS  qty,SUM(dddd.`item_cost` * dddd.`accept_qty`) AS amount,
		'44' AS ref_trans_code,ssss.issue_no,ssss.bc,mb.`name` AS b_name 
		FROM `t_internal_transfer_return_sum` ssss 
		JOIN m_branch mb ON mb.bc = ssss.bc 
		JOIN `t_internal_transfer_return_det` dddd ON ssss.cl = dddd.cl AND ssss.bc = dddd.bc AND ssss.nno = dddd.nno AND ssss.`sub_no`=dddd.`sub_no`
		WHERE ssss.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND is_cancel=0
		GROUP BY ssss.`nno`,ssss.bc) d ON  d.issue_no = b.`sub_no` AND d.bc = b.bc  
		ORDER BY b.nno ";


		$query = $this->db->query($sql);   
		$r_detail['sum']=$query->result();   

		if($query->num_rows()>0){
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