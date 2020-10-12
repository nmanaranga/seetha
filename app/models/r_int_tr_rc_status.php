<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_int_tr_rc_status extends CI_Model {

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

		$sql="SELECT s.cl,c.`description` AS t_clus,s.bc,b.`name` AS t_bc,s.sub_no AS tr_no,a.sub_no AS rec_no,s.ddate,s.to_cl,cc.`description` AS r_clus,s.to_bc,bb.`name` AS r_bc,s.status,d.`item_code`,d.`qty`,IFNULL(a.qty,0) AS received_qty FROM t_internal_transfer_sum s
		JOIN t_internal_transfer_det d ON d.`nno`=s.`nno` AND d.cl=s.`cl` AND d.bc=s.`bc`
		LEFT JOIN (SELECT s.cl,s.bc,s.nno,s.sub_no,s.ddate,s.to_cl,s.to_bc,s.status,d.`item_code`,d.`qty`,d.`issued_qty`,ref_trans_code FROM t_internal_transfer_sum s
		JOIN t_internal_transfer_det d ON d.`nno`=s.`nno` AND d.cl=s.`cl` AND d.bc=s.`bc` WHERE s.trans_code='43')a ON s.to_cl=a.`cl` AND s.to_bc=a.`bc` AND d.item_code=a.item_code AND a.ref_trans_code=s.`nno`
		JOIN m_cluster c ON c.`code`=s.`cl`
		JOIN m_cluster cc ON cc.`code`=s.`to_cl`
		JOIN m_branch b ON b.`bc`=s.`bc`
		JOIN m_branch bb ON bb.`bc`=s.`to_bc`
		WHERE s.trans_code='42'AND s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";
		
		if(!empty($_POST['cluster'])){
			$sql.=" AND s.`cl` = '".$_POST['cluster']."'";
		}if(!empty($_POST['branch'])){
			$sql.=" AND s.`bc` = '".$_POST['branch']."'";
		}  			
		
		$r_detail['int_trans']=$this->db->query($sql)->result();	

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