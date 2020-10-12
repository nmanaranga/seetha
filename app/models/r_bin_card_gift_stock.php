<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_bin_card_gift_stock extends CI_Model {
    
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
		$this->db->where("code",$_POST['item']);
		$r_detail['itm']=$this->db->get('g_m_gift_voucher')->result();

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
		$unit=$_POST['unit'];
		$brand=$_POST['brand'];
		$supplier=$_POST['supplier'];

		//if(!empty($store))

		$sql="SELECT i.ddate,
		t_trans_code.description as trans_des,
		i.trans_no,
		i.qty_in,
		i.qty_out
		FROM g_t_item_movement i
		JOIN t_trans_code  ON i.`trans_code`=t_trans_code.`code` 
		JOIN g_m_gift_voucher m ON i.`item` = m.code
		WHERE i.ddate  BETWEEN '".$from."' AND '".$to."'" ;

	
		if(!empty($cluster))
		{
			$sql.=" AND i.`cl` = '$cluster'";
		}
		if(!empty($branch))
		{
			$sql.=" AND i.`bc` = '$branch'";
		}

		if(!empty($store))
		{
			$sql.=" AND i.`store_code` = '$store'";
		}

		
		if(!empty($item))
		{
			$sql.=" AND `m`.`code` = '$item'";
		}

		$sql.= "ORDER BY i.auto_num";

		
		$r_detail['item_det']=$this->db->query($sql)->result();	


		//var_dump("expression");
		$sql2="SELECT  IFNULL(SUM(qty_in-qty_out),0) AS op , i.ddate
		FROM g_t_item_movement i
		JOIN g_m_gift_voucher m ON i.`item` = m.code
		WHERE i.ddate <'".$from."'";

		if(!empty($cluster))
		{
			$sql2.=" AND i.`cl` = '$cluster'";
		}
		if(!empty($branch))
		{
			$sql2.=" AND i.`bc` = '$branch'";
		}

		if(!empty($store))
		{
			$sql2.=" AND i.`store_code` = '$store'";
		}

		
		if(!empty($item))
		{
			$sql2.=" AND `m`.`code` = '$item'";
		}

		
		$r_detail['op_detail']=$this->db->query($sql2)->result();	



        if($this->db->query($sql)->num_rows()>0){
			$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}



	}



}	
?>