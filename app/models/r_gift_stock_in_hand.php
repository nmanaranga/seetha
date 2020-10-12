<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_gift_stock_in_hand extends CI_Model {
    
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
		$r_detail['type']="r_gift_stock_in_hand";       
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];
		$re_type = 0;
		if($_POST['to']==""){
			$_POST['to']=date('Y-m-d');
			$to=date('Y-m-d');
			$re_type = 1;
		}

		$r_detail['page']="A4";
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']="P";
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

		/*
		$sql="SELECT m_item.`code`, 
					m_item.`description`,
					m_item.`model`,
					qry_current_stock.`qty`
			FROM 	m_item
			JOIN 	qry_current_stock ON qry_current_stock.`item`=m_item.`code` 

			WHERE 	qry_current_stock.`store_code`='$_POST[stores]'
					AND qry_current_stock.`cl`='$cl' AND qry_current_stock.bc='$bc'";
		


		$sql="SELECT m_item.`code`, 
					m_item.`description`,
					m_item.`model`,
					SUM(`t_item_movement`.`qty_in` - `t_item_movement`.`qty_out`) as qty,
					t_item_movement.`store_code`,
					m_item.`purchase_price`,
					m_item.`min_price`,
  					m_item.`max_price`
			FROM 	t_item_movement
			LEFT JOIN m_item ON t_item_movement.`item`=m_item.`code`
			WHERE 	t_item_movement.`ddate` <=  '".$to."' 
			";
		*/
		
		if(!empty($cluster)){
			$ccl=" AND `g_t_item_movement`.`cl` = '$cluster'";
		}else{
			$ccl=" ";
		}
		
		if(!empty($branch)){
			$bbc=" AND `g_t_item_movement`.`bc` = '$branch'";
		}else{
			$bbc=" ";
		}
		
		if(!empty($store)){
			$sstore=" AND `g_t_item_movement`.`store_code` = '$store'";
		}else{
			$sstore=" ";
		}

		
		if(!empty($item)){
			$iitem=" AND `m`.`code` = '$item'";
		}else{
			$iitem=" ";
		}

		
 					
				
		if($re_type=="1"){
			$sql="SELECT m.`code` AS code
					, m.`description`
					, m.model
					, IFNULL(c.item_tot,0) AS qty
					, IFNULL(c.store_code,'') AS store_code
					, m.cost AS purchase_price 
					, m.price AS max_price 
				FROM `g_m_gift_voucher` `m`
				LEFT JOIN (SELECT item
						, SUM(qty_in - qty_out) AS item_tot
						, `store_code`
						, cl
						, bc 
						, MIN(ddate) AS p_date
						, cost
						FROM g_t_item_movement 
						WHERE (ddate <='".$to."' 
								AND `g_t_item_movement`.`cl` = '".$this->sd['cl']."'
								AND `g_t_item_movement`.`bc` = '".$this->sd['branch']."'
								$sstore
						) 
						GROUP BY item, cl, bc) c ON m.`code` = c.item
				 WHERE 	m.code !=''
							$ddep
							$m_cat
							$s_cat
							$iitem
							$uunit
							$bbrand
							$sup	
				having qty>0";
		}else{
			$sql="SELECT m.`code` AS code
						, m.`description`
						, IFNULL(c.item_tot,0) AS qty
						, IFNULL(c.store_code,'') AS store_code
						, m.cost AS purchase_price 
						, m.price AS max_price 
					FROM `g_m_gift_voucher` `m`
					LEFT JOIN (SELECT item
							, SUM(qty_in - qty_out) AS item_tot
							, `store_code`
							, cl
							, bc 
							, MIN(ddate) AS p_date
							, cost
							FROM g_t_item_movement 
							WHERE (ddate <='".$to."' 
									$ccl
									$bbc
									$sstore
							) 
							GROUP BY item, cl, bc) c ON m.`code` = c.item
					 WHERE 	m.code !=''
								$ddep
								$m_cat
								$s_cat
								$iitem
								$uunit
								$bbrand
								$sup	
					having qty>0";
		}		


		
		
		$r_detail['item_det']=$this->db->query($sql)->result();	


        if($this->db->query($sql)->num_rows()>0){
			$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}
	}



}	
?>