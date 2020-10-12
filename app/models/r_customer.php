 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 class r_customer extends CI_Model{

 	function __construct()
 	{
 		parent::__construct();
 		$this->sd = $this->session->all_userdata();
 	}

 	public function PDF_report(){

 		$this->db->select(array('name','address','tp','fax','email'));
 		$this->db->where("cl",$this->sd['cl']);
 		$this->db->where("bc",$this->sd['branch']);
 		$r_detail['branch']=$this->db->get('m_branch')->result();

 		$r_detail['page']='A4';
 		$r_detail['orientation']='P'; 


 		$sql = "SELECT 
 		m.`credit_limit`,
 		m.`code`,
 		m.`name` ,
 		m.`nic`,
 		m.`address1`,
 		m.`address2`,
 		m.`address3`,
 		m.`email`,
 		m.`occupation`,
 		m.`company_name`,
 		m.`cn_name`,
 		m.`cn_address`,
 		m.`cn_tel`,
 		m.`cn_email`,
 		m.`tax_reg_no`,
 		m.`credit_period`,
 		GROUP_CONCAT(mc.`tp`) AS cus_tp

 		FROM m_customer m
 		JOIN m_customer_contact mc ON mc.`code`=m.`code` 
 		WHERE m.`code`='".$_POST['cus_code']."'";

 		$query=$this->db->query($sql);

 		if($query->num_rows()>0){
 			$r_detail['data']=$query->result();
 			$this->load->view($_POST['by'].'_'.'pdf',$r_detail);	
 		}else{
 			echo"<script>alert('No records');history.go(-1);</script>";
 		}



 		

 		




 	}

 }