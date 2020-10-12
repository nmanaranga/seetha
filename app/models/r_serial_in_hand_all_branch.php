<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_serial_in_hand_all_branch extends CI_Model {

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
//////////////////

		$this->db->select(array('description','code'));
		$this->db->where("code",$_POST['cluster']);
		$r_detail['clus']=$this->db->get('m_cluster')->result();

		$this->db->select(array('name','bc'));
		$this->db->where("bc",$_POST['branch']);
		$r_detail['bran']=$this->db->get('m_branch')->result();

		$this->db->select(array('description','code'));
		$this->db->where("cl",$_POST['cluster']);
		$this->db->where("code",$_POST['store']);
		$r_detail['str']=$this->db->get('m_stores')->result();

		$this->db->select(array('description','code'));
		$this->db->where("code",$_POST['department']);
		$r_detail['dp']=$this->db->get('r_department')->result();

		$this->db->select(array('description','code'));
		$this->db->where("code",$_POST['main_category']);
		$r_detail['cat']=$this->db->get('r_category')->result();

		$this->db->select(array('description','code'));
		$this->db->where("code",$_POST['sub_category']);
		$r_detail['scat']=$this->db->get('r_sub_category')->result();

		$this->db->select(array('description','code'));
		$this->db->where("code",$_POST['item']);
		$r_detail['itm']=$this->db->get('m_item')->result();

		$this->db->select(array('description','code'));
		$this->db->where("code",$_POST['unit']);
		$r_detail['unt']=$this->db->get('r_unit')->result();

		$this->db->select(array('description','code'));
		$this->db->where("code",$_POST['brand']);
		$r_detail['brnd']=$this->db->get('r_brand')->result();

		$this->db->select(array('name','code'));
		$this->db->where("code",$_POST['supplier']);
		$r_detail['sup']=$this->db->get('m_supplier')->result();
///////



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
		$r_detail['cluster']=$_POST['cluster'];
		$r_detail['branchs']=$_POST['branch'];
		$r_detail['store']=$_POST['store'];
		$r_detail['department']=$_POST['department'];
		$r_detail['main_category']=$_POST['main_category'];
		$r_detail['sub_category']=$_POST['sub_category'];
		$r_detail['item_des']=$_POST['item_des'];
		$r_detail['item']=$_POST['item'];
		$r_detail['unit']=$_POST['unit'];
		$r_detail['brand']=$_POST['brand'];
		$r_detail['supplier']=$_POST['supplier'];

		$to=$_POST['to'];
		$from=$_POST['from'];
		$cluster=$_POST['cluster'];
		$branch=$_POST['branch'];
		$store=$_POST['store'];
		$department=$_POST['department'];
		$main_category=$_POST['main_category'];
		$sub_category=$_POST['sub_category'];
		$item=$_POST['item'];
		$item_des=$_POST['item_des'];
		$unit=$_POST['unit'];
		$brand=$_POST['brand'];
		$supplier=$_POST['supplier'];


		$sql = "SELECT 
		t_serial.`bc`,
		b.`name`,
		m_item.`code`,
		m_item.`description`,
		m_item.`model`,
		c.item_tot AS qty,
		GROUP_CONCAT(t_serial.`serial_no`) AS serial_no
		FROM  t_serial 
		JOIN m_item ON t_serial.`item` = m_item.`code` 
		INNER JOIN (SELECT cl,bc,item,qty AS item_tot,batch_no FROM qry_current_stock  GROUP BY item,cl,bc,batch_no) c ON t_serial.item = c.item AND t_serial.batch = c.batch_no AND t_serial.cl=c.cl AND t_serial.bc=c.bc
		JOIN m_branch b ON b.`bc`=t_serial.`bc`
		WHERE t_serial.`available` = '1' 
		AND m_item.`serial_no` ='1' AND t_serial.item='".$_POST['item']."' 
		GROUP BY t_serial.`item`,t_serial.cl,t_serial.bc";

		$r_detail['serial_det']=$this->db->query($sql)->result();
		$query = $this->db->query($sql)->result();	

		if($this->db->query($sql)->num_rows()>0){
			$this->load->view($_POST['by'].'_'.'pdf',$r_detail,$serials);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}



	}



}	
?>