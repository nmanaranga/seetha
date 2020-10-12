<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_customer_balances extends CI_Model {
	
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
		
		/*$sql="SELECT * FROM customer_balances b WHERE balance!=0 ";

		if(!empty($_POST['cluster'])){
			$sql.=" AND b.`cl` = '".$_POST['cluster']."'";
		}
		if(!empty($_POST['branch'])){
			$sql.=" AND b.`bc` = '".$_POST['branch']."'";
		}  	
		if($_POST['cus_id']!=""){
			$sql.=" AND b.`code` = '".$_POST['cus_id']."'";
		} 
*/

		/*$sql="SELECT m.`code` as nic,
		m.`name`,
		cc.tp,
		SUM(t.`dr_amount` - t.`cr_amount`) AS balance 
		FROM m_customer m 
		LEFT JOIN (SELECT IFNULL(GROUP_CONCAT(tp),'') AS tp, `code` FROM m_customer_contact GROUP BY CODE) cc  ON cc.`code` = m.`code` 
		JOIN t_account_trans t ON t.`acc_code` = m.`code`
		WHERE t.ddate <='$to'";*/

		$sql="SELECT m.`code`, 
		m.nic,
		m.`name`,
		IFNULL(cc.tp,'') AS tp,
		b.balance
		FROM m_customer_balance b 
		JOIN m_customer m ON m.code=b.code
		LEFT JOIN (SELECT IFNULL(GROUP_CONCAT(tp),'') AS tp, `code` FROM m_customer_contact GROUP BY CODE) cc  ON cc.`code` = m.`code` 
		WHERE m.inactive=0";

		if(!empty($_POST['cluster'])){
			$sql.=" AND b.`cl` = '".$_POST['cluster']."'";
		}
		if(!empty($_POST['branch'])){
			$sql.=" AND b.`bc` = '".$_POST['branch']."'";
		}  	
		if($_POST['cus_id']!=""){
			$sql.=" AND b.`code` = '".$_POST['cus_id']."'";
		} 

		$sql .="GROUP BY m.`code`,cc.`tp` HAVING b.balance != 0";	
		$r_detail['item_det']=$this->db->query($sql)->result();	

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