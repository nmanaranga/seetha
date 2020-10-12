<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_sub_item extends CI_Model {
    
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
		$this->db->where("code",$_POST['sub_item']);
		$r_detail['itm']=$this->db->get('r_sub_item')->result();

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
		$r_detail['sub_item']=$_POST['sub_item'];
		

		$to=$_POST['to'];
		$from=$_POST['from'];
		$cluster=$_POST['cluster'];
		$branch=$_POST['branch'];
		$store=$_POST['store'];
		$item=$_POST['sub_item'];
		
		$sql="SELECT  `sub_item`,
				      `description` AS item_name,
					  SUM(
						  `t_item_movement_sub`.`qty_in` - `t_item_movement_sub`.`qty_out`
						 ) AS qty 
			FROM
					t_item_movement_sub 
					JOIN r_sub_item 
			ON r_sub_item.`code` = t_item_movement_sub.`sub_item` 
			WHERE 	t_item_movement_sub.`ddate` <=  '".$to."'";

				
		if(!empty($cluster))
		{
			$sql.=" AND `t_item_movement_sub`.`cl` = '$cluster'";
		}
		
		if(!empty($branch))
		{
			$sql.=" AND `t_item_movement_sub`.`bc` = '$branch'";
		}
		
		if(!empty($store))
		{
			$sql.=" AND `t_item_movement_sub`.`store_code` = '$store'";
		}

		
		if(!empty($item))
		{
			$sql.=" AND `t_item_movement_sub`.`sub_item` = '$item'";
			
		}

		$sql.= " GROUP BY `sub_item` HAVING qty > '0'";

		$r_detail['sub_item_det']=$this->db->query($sql)->result();	
		
        if($this->db->query($sql)->num_rows()>0){
			$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}
	}


}	
?>