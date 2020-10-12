<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_account_chart extends CI_Model {

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
		$r_detail['orientation']='L';

		$cluster=$_POST['cluster'];
		$branch=$_POST['branch'];


		// $sql="SELECT 
		// 	  m_account_type.`heading` as rtype,
		// 	  m_account_type.code AS type,
		// 	  m_account.`control_acc`,
		// 	  m_account.`code`,
		// 	  m_account.`description` ,
		// 	  m_account.`is_control_acc`
		// 	FROM m_account
 		// 		LEFT JOIN m_account_type 
		// 	ON m_account.`type` = m_account_type.`code` 
		// 	GROUP BY m_account.`control_acc`,m_account.code ORDER BY m_account_type.`heading` ASC

		// ";


		// $sql="SELECT t.`code` , t.`heading` , t.`control_category`  ,tt.`heading` AS ConName
		// 		FROM `m_account_type` t
		// 			LEFT JOIN `m_account_type` tt
		// 			ON t.`control_category`=tt.`code`
		// 		ORDER BY t.`code`";



		$sql ="SELECT * FROM `m_account_type` t WHERE LENGTH( t.`code`)=1";

		/*if(!empty($cluster))
		{
			$sql.=" AND i.`cl` = '$cluster'";
		}
		if(!empty($branch))
		{
			$sql.=" AND i.`bc` = '$branch'";
		}*/

		$r_detail['acc_det']=$this->db->query($sql)->result();	

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